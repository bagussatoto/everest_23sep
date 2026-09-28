<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Setting extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        /* ----------------------------------------------------------------------------------
         * validasi session bila tidak ada dipaksa ke halaman login
         * ----------------------------------------------------------------------------------*/
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
        /* ----------------------------------------------------------------------------------
         * loader dari main CI
         * ----------------------------------------------------------------------------------*/
        //arrPrintWebs($this->session->login);
        // $this->load->helper("he_stepping");
        // $this->load->helper("he_access_right");
        // $this->load->library("MobileDetect");
        // $this->load->helper("he_session_replacer");
        // $this->load->model("Mdls/MdlCurrency");
        // $this->load->helper('he_angka');
        //
        // $this->load->config("heWebs");
        // $maintenanceTransaksi = $this->config->item("maintenanceTransaksi");
        // $this->transaksiMaintenance = $maintenanceTransaksi != null && $maintenanceTransaksi == true ? true : false;
        // $maintenanceOption = $this->config->item("maintenanceOptions");
        // $this->transaksiMaintenanceMsg = isset($maintenanceOption[1]) ? $maintenanceOption[1] : array();
        $this->cabang_id = CB_ID_PUSAT;
        $this->harga_jenis = "jual_reseller";
        $this->pph23 = 15;
    }

    /* -------------------------------------------------------------------------------------
     * create_form ada di index
     * lanjut_ke -> preview -> save
     * -------------------------------------------------------------------------------------*/
    public function index()
    {
        /* ----------------------------------------------------------------
         * default tab yg aktif diatur di viewer pada array isi tab
         * ----------------------------------------------------------------*/

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $this->load->library("Diskon");
        $dk = new Diskon();

        /*-----------grosir-----------------*/
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        $dg->setTokoId(my_toko_id());
        $src_dg_obj = $dg->callProdukGrosir("");
        // arrPrint($src_dg_obj);
        foreach ($src_dg_obj as $item) {
            $dg++;
            if (!isset($pr_grosir_aktive[$item->produk_id])) {
                $pr_grosir_aktive[$item->produk_id] = 0;
            }
            $pr_grosir_aktive[$item->produk_id] += 1;
        }

        // arrPrintHijau($pr_grosir_aktive);
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();
        // ceklIme($this->db->last_query());
        // arrPrint($prod_hargas);
        // cekHere(__LINE__);
        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        // $src_pr = $pr->lookupAll()->result();
        $src_pr_obj = $pr->callSpecs();
        // arrPrintKuning($src_pr_obj);
        foreach ($src_pr_obj as $prod_id => $item) {
            $diskon_persen = $item->diskon_persen * 1;
            $nama = $item->nama;

            $harga_speks = array();
            if (isset($prod_hargas[$prod_id])) {
                foreach ($prod_hargas[$prod_id] as $spek_harga) {
                    $harga_speks[$spek_harga->jenis_value] = $spek_harga;
                }
            }
            $hrg_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;
            $hrg_jual = isset($harga_speks["jual"]) ? $harga_speks["jual"]->nilai * 1 : 0;
            $hrg_list = isset($harga_speks["harga_list"]) ? $harga_speks["harga_list"]->nilai * 1 : 0;
            $diskon_satu = $dk->calcDiskon($hrg_list, array("satu" => $diskon_persen), array());
            // cekBiru($diskon_satu);
            $diskon_nilai = $diskon_satu['nilai'];
            $hrg_jual_diskon = $diskon_satu['harga_af'];

            $hrg_margin = $hrg_jual > 0 ? (($hrg_jual - $hrg_beli) / $hrg_jual) * 100 : 0;
            $jml_grosir = isset($pr_grosir_aktive[$prod_id]) ? $pr_grosir_aktive[$prod_id] : 0;
            $grosir_cek = $jml_grosir > 0 ? "<i class='fa fa-check text-green'> $jml_grosir</i>" : "";

            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&nilai=";
            $url_grosir = base_url() . "diskon/setting/viewGrosir?id=$prod_id";
            $link_grosir = modalDialogBtn("grosir $nama", $url_grosir);
            $item_array = (array)$item;
            //            $item_array["diskon_persen"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);\" value='$diskon_persen'>";
            $item_array["diskon_persen"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1'  value='$diskon_persen'>";
            $item_array["grosir"] = "<button type='button' class='btn btn-warning' onclick=\"$link_grosir\">grosir</button> $grosir_cek";
            $item_array["harga_jual"] = $hrg_list;
            $item_array["harga_aft"] = $hrg_jual_diskon;
            $item_array["harga_beli"] = $hrg_beli;
            $item_array["margin"] = $hrg_margin;
            $src_pr[$prod_id] = $item_array;
        }
        // arrPrintHijau($src_pr);
        // cekHere(__LINE__);
        /* ---------------------
         * dta produk per supplier
         * ---------------------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();

        if (isset($_GET['suppliers_id'])) {
            $condites = array(
                "suppliers_id" => $_GET['suppliers_id'],
            );
            $this->db->where($condites);
        }
        $src_pps_0 = $pps->lookupAll()->result();
        // showLast_query("kuning");
        // arrPrint($src_pps_0);

        foreach ($src_pps_0 as $src_pp) {
            $suppliers_id = $src_pp->suppliers_id;
            $produk_id = $src_pp->produk_id;

            $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
            // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
            $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
        }
        // arrPrintHijau($src_pps);

        // $allowTmpSave = isset($this->configUi[$jenisTr]['allowTmpSave']) ? $this->configUi[$jenisTr]['allowTmpSave'] : false;
        $arrHeaders = array(
            "nama" => array(
                "label" => "nama produk",
            ),
            "harga_beli" => array(
                "label" => "hpp",
                "attr" => "class='text-right'",
            ),
            "margin" => array(
                "label" => "margin (%)",
                "attr" => "class='text-right'",
                "format" => "formatField_he_format",
            ),
            "harga_jual" => array(
                "label" => "harga list",
                "attr" => "class='text-right'",
            ),
            "diskon_persen" => array(
                "label" => "diskon",
                // "attr" => "onblur=''",
            ),
            "harga_aft" => array(
                "label" => "harga netto",
                "attr" => "class='text-right'",
            ),
            "grosir" => array(
                "label" => "harga grosir",
                // "attr" => "onblur=''",
            ),
        );

        /* --------------------------------------
         * grosir
         * --------------------------------------*/
        // $this->load->model("Mdls/MdlDiskonGrosir");
        // $dg = new MdlDiskonGrosir();
        // $dg->setTokoId(my_toko_id());
        // $src_dg_obj = $dg->callProdukGrosir("");
        // showLast_query("merah");
        // arrPrint($src_dg_obj);
        // arrPrint($src_pr_obj);
        $src_dg = array();
        foreach ($src_dg_obj as $item) {
            $prod_id = $item->produk_id;
            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&nilai=";
            $item_array = (array)$item;
            // $item_array["diskon_persen"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);\" value='$diskon_persen'>";
            $prod_speks = isset($src_pr_obj[$prod_id]) ? (array)$src_pr_obj[$prod_id] : array();
            $src_dg[] = $item_array + addPrefixKeyI_he_format($prod_speks);
        }
        // arrPrintHijau($src_dg);
        $grosir_header = array(
            "produk_id" => array(
                "label" => "produk id",
            ),
            "i_nama" => array(
                "label" => "produk",
            ),
            "minim" => array(
                "label" => "qty minmal",
            ),
            // "maxim"     => array(
            //     "label" => "mak",
            // ),
            "nilai" => array(
                "label" => "harga",
            ),
            "harga" => array(
                "label" => "harga satuan",
            ),
        );

        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        // $this->load->model("Mdls/MdlCustomerLevel");
        // $cl = new MdlCustomerLevel();

        $dk->setTokoId(my_toko_id());
        $src_cls = $dk->callCustomerLevelDiskon();
        // showLast_query("kuning");
        // arrPrint($src_cls);
        $tmp_cls = array();
        foreach ($src_cls['customer_level_diskon'] as $src_cl) {
            $jenis = $src_cl['jenis'];
            $minim = $src_cl['minim'];
            $persen = isset($src_cl['persen']) ? $src_cl['persen'] : 0;
            $customer_level = $src_cl['customer_level'];
            if (!isset($data_koloms[$jenis][$minim]["level_$customer_level"])) {
                $data_koloms[$jenis][$minim]["level_$customer_level"] = 0;
            }
            $data_koloms[$jenis][$minim]["level_$customer_level"] = $persen;

            $data_koloms[$jenis][$minim]['jenis'] = $jenis;
            $data_koloms[$jenis][$minim]['minim'] = $minim;
            $data_koloms[$jenis][$minim]['tanggal_start'] = $src_cl['tanggal_start'];
            $data_koloms[$jenis][$minim]['tanggal_stop'] = $src_cl['tanggal_stop'];

            $tmp_cls = $data_koloms;
        }
        // arrPrintHijau($tmp_cls);
        $src_clevel_diskons = array();
        foreach ($tmp_cls as $tmp_cl_0) {
            foreach ($tmp_cl_0 as $tmp_cl) {

                $src_clevel_diskons[] = $tmp_cl;
            }
        }
        // arrPrintKuning($src_clevel_diskons);
        $level_header = array();
        $level_header = array(
            "jenis" => array(
                "label" => "jenis diskon",
                "attr_footer" => "class='form-control' required",
                "tipe_input" => "select",
                "data_srcs" => array(
                    "transaksi",
                    "birthday"
                ),
            ),
        );
        $level_header['minim'] = array(
            "label" => "minimal transaksi",
            "attr_footer" => "class='form-control' required",
        );
        foreach ($src_cls['customer_level'] as $src_cl) {
            $level_id = $src_cl->id;
            $level_nama = $src_cl->nama;

            $attributs['label'] = "level " . $level_nama;
            $attributs['attr_footer'] = "class='form-control' max='100'";
            $attributs['tipe_input'] = "number";
            $level_header['level_' . $level_id] = $attributs;
            // $level_header['level_'][$level_id] = $attributs;

        }
        $level_header['quota_global'] = array(
            "label" => "quota",
            "attr_footer" => "class='form-control'",
        );
        $level_header['periode'] = array(
            "label" => "periode",
            "tipe_input" => "select",
            "data_srcs" => array(
                "bulanan",
                "tahunan",
            ),
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
        );

        // arrPrintKuning($level_header);
        $data = array(
            "mode" => "index",
            "isMobile" => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting Diskon",
            "subTitle" => "-",
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            "grosir_header" => $grosir_header,
            "grosir_data" => $src_dg,
            "level_header" => $level_header,
            "level_data" => $src_clevel_diskons,
            // "level_data"     => array(),
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
        //        $this->session->errMsg = "";
    }

    public function do_update()
    {
        arrPrint($_GET);
        //        arrPrint($_GET['ky']);

        $key_harga = $_GET['ky'];
        $produk_id = $_GET['id'];

        $diskon_id = isset($_GET['diskonid']) ? $_GET['diskonid'] : 0;
        $getcCode = isset($_GET['cCode']) ? $_GET['cCode'] : "";
        $getUrlBack = isset($_GET['urlBack']) ? $_GET['urlBack'] : "";
        $jenis = isset($_GET['jenis']) ? $_GET['jenis'] : "";

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $pr->setTokoId(my_toko_id());
        $getNya = json_encode($_GET);

        //        mati_disini("ky: $key_harga, pid: $produk_id, diskonid: $diskon_id, cCode: $getcCode <br>$getNya");

        $this->db->trans_start();

        switch ($key_harga) {
            case "hpp":
            case "hpp_supplier":
            case "hpp_supplier_0":
            case "jual_reseller":
            case "jual_online":
            case "jual":
            case "harga_list":
                //                matiHere($key_harga);
                $this->load->model("Mdls/MdlHargaProdukPerSupplier");
                $pp = new MdlHargaProdukPerSupplier();
                $pp->setTokoId(my_toko_id());
                $pp->setCabangId($this->cabang_id);
                $pp_datas = $pp->callSpecs($produk_id);
                showLast_query("hijau");
                $src_hargas = isset($pp_datas[$produk_id]) ? $pp_datas[$produk_id] : array();
                $harga_list_0 = array();
                if (sizeof($src_hargas) > 0) {
                    foreach ($src_hargas as $item) {
                        $harga_list_0[$item->jenis_value] = $item;
                    }
                }
                $this->load->model("Mdls/MdlProduk");
                $pr = new MdlProduk();
                $src_pr = $pr->callSpecs($produk_id);
                $supplier_id = $src_pr[$produk_id]->supplier_id;
                $src_list = isset($harga_list_0[$key_harga]) ? $harga_list_0[$key_harga] : 0;
                $dt_id = isset($src_list->id) ? $src_list->id : 0;
                $dt_jenis_value = isset($src_list->jenis_value) ? $src_list->jenis_value : $key_harga;

                $this->db->trans_start();
                if ($dt_id > 0) {
                    $data_upd = array(
                        "trash" => 1
                    );
                    $condites = array(
                        "id" => $dt_id
                    );
                    $pp->updateData($condites, $data_upd);
                    showLast_query("biru");
                    // -------------------------------------
                    $data_new = array(
                        "cabang_id" => CB_ID_PUSAT,
                        "toko_id" => my_toko_id(),
                        "oleh_id" => my_id(),
                        "oleh_nama" => my_name(),
                        "jenis_value" => $dt_jenis_value,
                        "nilai" => $_GET['nilai'],
                        "produk_id" => $produk_id,
                        "suppliers_id" => $supplier_id,
                    );
                    $pp->addData($data_new);
                    showLast_query("hijau");
                }
                else {
                    $data_new = array(
                        "cabang_id" => my_cabang_id(),
                        "toko_id" => my_toko_id(),
                        "oleh_id" => my_id(),
                        "oleh_nama" => my_name(),
                        "jenis_value" => $dt_jenis_value,
                        "nilai" => $_GET['nilai'],
                        "produk_id" => $produk_id,
                        "suppliers_id" => $supplier_id,
                    );
                    $pp->addData($data_new);
                    showLast_query("hijau");
                }
                // matiHere("belum commit @" . __LINE__);
                $this->db->trans_complete();

                echo "<script>
                    let hrga_reseller = $('#harga_list_reseller_$produk_id').val();
                    let hrga_enduser = $('#harga_list_$produk_id').val();
                    let row = $('#harga_list_$produk_id').closest('td').data('row');
                                            
                    let hrga_aft = hrga_reseller > 0 ? hrga_reseller : hrga_enduser;
                    
                    $('#harga_aft_' + row).text(addCommas(hrga_aft));
                </script>";
                break;
            case "diskon_pembelian":
                //                matiHere($key_harga);
                $pr->addFilter("id=$produk_id");
                $pr_datas = $pr->lookupAll()->result();
                $supplier_id = $pr_datas[0]->supplier_id;
                $this->load->model("Mdls/MdlDiskonPembelian");
                $dp = new MdlDiskonPembelian();
                $dp->setTokoId(my_toko_id());
                $condites = array(
                    "supplier_id" => $supplier_id,
                    "produk_id" => $produk_id,
                    // "per_supplier_diskon_id"     => $jenis,
                    "per_supplier_diskon_nama" => $jenis,
                );
                $this->db->where($condites);
                $dp_datas = $dp->lookupAll()->result();
                showLast_query("hijau");
                arrPrintWebs($dp_datas);
                $jenis = $_GET['jenis'];
                $harga_basik = $_GET['basik'];
                $persen = $_GET['nilai'];
                $nilai = $_GET['nilaidk'];
                $nilai_npph = $_GET['nilaidknpph'];
                if (count($dp_datas) == 0) {
                    // insert
                    $data_new = array(
                        // "cabang_id"   => CB_ID_PUSAT,
                        // "toko_id"     => my_toko_id(),
                        "oleh_id" => my_id(),
                        // "oleh_nama" => my_name(),
                        "per_supplier_diskon_id" => $diskon_id,
                        "per_supplier_diskon_nama" => $jenis,
                        "produk_id" => $produk_id,
                        "persen" => $persen,
                        "nilai" => $nilai,
                        "nilai_plus" => $nilai_npph,
                        "supplier_id" => $supplier_id,
                    );
                    $dp->addData($data_new);
                    showLast_query("hijau");
                }
                else {
                    // update
                    cekHijau("update");
                    $data_upd = array(
                        "trash" => 1
                    );
                    $condites = array(
                        //                        "per_supplier_diskon_nama" => $jenis, //ORI hanya ini
                        "id" => $dp_datas[0]->id
                    );
                    $dp->updateData($condites, $data_upd);
                    showLast_query("biru");
                    $data_new = array(
                        // "cabang_id"   => my_cabang_id(),
                        // "toko_id"     => my_toko_id(),
                        "oleh_id" => my_id(),
                        // "oleh_nama"   => my_name(),
                        "per_supplier_diskon_id" => $diskon_id,
                        "per_supplier_diskon_nama" => $jenis,
                        "produk_id" => $produk_id,
                        "persen" => $persen,
                        "nilai" => $nilai,
                        "nilai_plus" => $nilai_npph,
                        "supplier_id" => $supplier_id,
                    );
                    $dp->addData($data_new);
                    showLast_query("hijau");
                }
                break;
            case "rebate_qty":
                // matiHere(__LINE__);

                //                matiHere($key_harga);
                $pr->addFilter("id=$produk_id");
                $pr_datas = $pr->lookupAll()->result();
                $supplier_id = $pr_datas[0]->supplier_id;
                $this->load->model("Mdls/MdlDiskonPembelian");
                $dp = new MdlDiskonPembelian();
                $dp->setTokoId(my_toko_id());

                $jenis = $_GET['jenis'];
                $basik = $_GET['basik'];
                $persen = $_GET['nilai'];
                $nilai = $_GET['nilai'];
                $nilai_npph = $_GET['nilaidknpph'];

                $condites = array(
                    "supplier_id" => $supplier_id,
                    "produk_id" => $produk_id,
                    // $basik     => $nilai,
                    "jenis" => "khusus",
                    "per_supplier_diskon_nama" => $jenis,
                );
                $this->db->where($condites);
                $dp_datas = $dp->lookupAll()->result();
                showLast_query("hijau");
                arrPrintWebs($dp_datas);

                if (count($dp_datas) == 0) {
                    // insert
                    $data_new = array(
                        // "cabang_id"   => CB_ID_PUSAT,
                        // "toko_id"     => my_toko_id(),
                        "oleh_id" => my_id(),
                        // "oleh_nama" => my_name(),
                        "per_supplier_diskon_id" => $diskon_id,
                        "per_supplier_diskon_nama" => $jenis,
                        "produk_id" => $produk_id,
                        // "persen" => $persen,
                        $basik => $nilai,
                        "jenis" => "khusus",
                        "supplier_id" => $supplier_id,
                    );
                    $dp->addData($data_new);
                    showLast_query("hijau");
                }
                else {
                    // update
                    cekHijau("update");
                    $data_upd = array(
                        "trash" => 1
                    );
                    $condites = array(
                        //                        "per_supplier_diskon_nama" => $jenis, //ORI hanya ini
                        "id" => $dp_datas[0]->id
                    );
                    $dp->updateData($condites, $data_upd);
                    showLast_query("biru");

                    switch ($basik){
                        case "maxim":
                            $nonbasik = "persen";
                            $nonbasik_nilai = $dp_datas[0]->$nonbasik;
                            $nonbasik_2 = "nilai";
                            $nonbasik_nilai_2 = $dp_datas[0]->$nonbasik_2;
                            break;
                        case "persen":
                            $nonbasik = "maxim";
                            $nonbasik_nilai = $dp_datas[0]->$nonbasik;
                            $nonbasik_2 = "nilai";
                            // $nonbasik_nilai_2 = $dp_datas[0]->$nonbasik_2;
                            $nonbasik_nilai_2 = 0;
                            break;
                        case "nilai":
                            $nonbasik = "maxim";
                            $nonbasik_nilai = $dp_datas[0]->$nonbasik;
                            $nonbasik_2 = "persen";
                            // $nonbasik_nilai_2 = $dp_datas[0]->$nonbasik_2;
                            $nonbasik_nilai_2 = 0;
                            break;
                    }

                    $data_new = array(
                        // "cabang_id"   => my_cabang_id(),
                        // "toko_id"     => my_toko_id(),
                        "oleh_id" => my_id(),
                        // "oleh_nama"   => my_name(),
                        "per_supplier_diskon_id" => $diskon_id,
                        "per_supplier_diskon_nama" => $jenis,
                        "produk_id" => $produk_id,
                        $nonbasik => $nonbasik_nilai,
                        $nonbasik_2 => $nonbasik_nilai_2,
                        $basik => $nilai,
                        "jenis" => "khusus",
                        "supplier_id" => $supplier_id,
                    );
                    $dp->addData($data_new);
                    showLast_query("hijau");
                }

                $method = "viewProdukRebate";
                break;
            case "harga_tandas":
                // break;
            case "jual_bawah":
            case "jual_online_nppn":
            case "jual_nppn":
            case "jual_reseller_nppn":
                $arrKey = array(
                    "jual_online_nppn" => "jual_online",
                    "jual_nppn" => "jual",
                    "jual_reseller_nppn" => "jual_reseller",
                    "jual_bawah" => "jual_bawah",
                    "harga_tandas" => "tandas_manual",
                );

                $idTarget = isset($_GET['kyb']) ? $_GET['kyb'] : "";

                $ppn_factor = my_ppn_factor();
                $nilai = $_GET['nilai'];

                $nilai_new = $nilai * (1 + ($ppn_factor / 100));
                $nilai_dpp = $nilai / (1 + ($ppn_factor / 100));
                switch ($key_harga){
                    case "harga_tandas":
                        $nilai_new = $nilai;
                        $nilai_dpp = $nilai;
                        break;
                }

                cekHijau($key_harga);
                if($key_harga == "jual_bawah"){
                    $nilai_dpp = $nilai;
                }
                cekHere("$nilai_dpp ==== $nilai_new");
                /*-----------produk harga------------*/
                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $hp->setTokoId(my_toko_id());
                $hp->setCabangId($this->cabang_id);
                $condites = array(
                    "produk_id" => $produk_id,
                    "jenis_value" => $arrKey[$key_harga],
                );
                $this->db->where($condites);
                $prod_hargas = $hp->callSpecs();
                showLast_query("hijau");
                $dataHargas = $prod_hargas[$produk_id][0];
                // arrPrintHijau($dataHargas);
                // arrPrint(count($dataHargas));
                if (count($dataHargas) > 0) {
                    $dt_id = $dataHargas->id;
                    $data_upd = array(
                        "trash" => 1
                    );
                    $condites = array(
                        "id" => $dt_id
                    );
                    $hp->updateData($condites, $data_upd);
                    showLast_query("biru");
                    // -------------------------------------
                    $data_new = array(
                        "cabang_id" => CB_ID_PUSAT,
                        "toko_id" => my_toko_id(),
                        "oleh_id" => my_id(),
                        "oleh_nama" => my_name(),
                        "jenis_value" => $arrKey[$key_harga],
                        "nilai" => $nilai_dpp,
                        "produk_id" => $produk_id,
                        // "suppliers_id" => $supplier_id,
                    );
                    $hp->addData($data_new);
                    showLast_query("hijau");
                }
                else {
                    $data_new = array(
                        "cabang_id" => my_cabang_id(),
                        "toko_id" => my_toko_id(),
                        "oleh_id" => my_id(),
                        "oleh_nama" => my_name(),
                        "jenis_value" => $arrKey[$key_harga],
                        "nilai" => $nilai_dpp,
                        "produk_id" => $produk_id,
                        // "suppliers_id" => $supplier_id,
                    );
                    $hp->addData($data_new);
                    showLast_query("hijau");
                }

                $idRow = "harga_$key_harga" . "_";
                echo "<script>
                    // let hrga_reseller = $('#harga_list_reseller_$produk_id').val();
                    // let hrga_enduser = $('#harga_list_$produk_id').val();
                    // let row = $('#$idRow'+'$produk_id').closest('td').data('row');
                    let row = this.input;
                                            
                    // let hrga_aft = hrga_reseller > 0 ? hrga_reseller : hrga_enduser;
                    console.log('row: ', row)
                    $('#$idTarget' + '_' + row).text($nilai_dpp.toFixed(2));
                </script>";

                break;
            default:
                //                matiHere($key_harga);
                $data_upd = array(
                    $_GET['ky'] => $_GET['nilai']
                );
                $condites = array(
                    "id" => $produk_id
                );
                $pr->updateData($condites, $data_upd);
                showLast_query("kuning");
                break;
        }

        // matiHere("belum commit" . __LINE__);
        $this->db->trans_complete();
        // cekHere("commit");
        $linkMethod = isset($method) ? $method : "viewProdukHarga";
        $link_viewProdukHarga = base_url() . "diskon/Setting/$linkMethod";

        $str = "<script>
                    // $('#satu').load('$link_viewProdukHarga');
                    // alert('okok');
                </script>";

        if (isset($getcCode) && ($getcCode != "")) {
            $urlBack = blobDecode($getUrlBack);
            $str .= "<script>";
            $str .= "  if(top.document.getElementById('followupPreview')){";
            $str .= "  top.$('#followupPreview').load('$urlBack');";
            $str .= "  }";
            $str .= "</script>";
        }

        echo $str;
    }

    public function viewGrosir_ori()
    {
        // matiHere();
        /* --------------------------------------
                 * grosir
                 * --------------------------------------*/
        // echo my_toko_id();
        // cekMerah(my_cabang_id());
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $condites = array(
                "produk_id" => $prod_id,
            );
            $this->db->where($condites);
        }
        $dg->setTokoId(my_toko_id());
        $src_dg_obj_0 = $dg->callProdukGrosir("");
        // showLast_query("kuning");
        $src_dg_obj = array();
        foreach ($src_dg_obj_0 as $item_obj) {
            $dtime_start = $item_obj->dtime_start;

            if ($dtime_start == null) {
                $src_dg_obj[] = $item_obj;
            }
        }
        /*-----------produk speks------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prod_speks = $pr->callSpecs($prod_id);
        // arrPrint($prod_speks);
        $premi_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->premi_jual * 1 : 0;
        $diskon_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->diskon_persen * 1 : 0;
        // $harga_jual = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->harga_jual * 1 : 0;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs($prod_id);
        // showLast_query("kuning");
        // arrPrint($prod_hargas);
        $harga_speks = array();
        if (isset($prod_hargas[$prod_id])) {
            foreach ($prod_hargas[$prod_id] as $spek_harga) {
                $harga_speks[$spek_harga->jenis_value] = $spek_harga;
            }
        }

        $this->load->library("Diskon");
        $dk = new Diskon();

        // arrPrint($harga_speks[$this->harga_jenis]->nilai);
        // arrPrint($harga_speks);
        $harga_list = isset($harga_speks[$this->harga_jenis]) ? $harga_speks[$this->harga_jenis]->nilai * 1 : 0;
        $harga_list_f = format_harga($harga_list);
        // cekHijau($harga_list);
        $harga_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;

        $hrg_jual = $harga_list + (($premi_persen / 100) * $harga_list);
        $hrg_jual_f = formatField_he_format("harga", $hrg_jual);
        $harga_beli_f = formatField_he_format("harga", $harga_beli);
        $jml_grosir = sizeof($src_dg_obj);
        $type_awal = $jml_grosir == 0 ? "text" : "text";
        $diskon_satu = $dk->calcDiskon($hrg_jual, array("satu" => $diskon_persen), array());
        // arrPrint($diskon_satu);
        $diskon_nilai = $diskon_satu['nilai'];
        $hrg_jual_diskon = $diskon_satu['harga_af'];
        $str = "";
        $str .= "<style type='text/css'>
                .form-control {
                margin-top: 1px;
                    padding: 0 5px !important;
                    height: 30px !important;
                }
            </style>";
        $str .= "<div class='row' style='margin-bottom: 20px;'><div class='col-md-6'>";
        $str .= "<h5 class='text-uppercase' style='margin-left: 15px;'>harga beli $harga_beli_f</h5>";
        $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan $hrg_jual_f</h4>";
        $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list: Rp. $harga_list_f | premi: $premi_persen%</p>";
        $str .= "</div></div>";
        $str .= "<div class='row col-md-12'>";
        $str .= "<div class='col-xs-3'><div class='input-group marginn'>Jumlah Minimal<input type='$type_awal' id='jml_222' disabled class='form-control' value='1'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>diskon (%)<input type='$type_awal' id='persen_222' disabled class='form-control' value='$diskon_persen'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>diskon (Rp)<input type='$type_awal' id='nilai_222' disabled class='form-control' value='$diskon_nilai'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        $str .= "</div>";
        // $str .= "<div class='col-xs-12'>----</div>";

        $cont = 222;
        $cont_data = $cont + $jml_grosir + 1;
        // cekHere($cont_data);
        $ix = '-1';
        // arrPrint($src_dg_obj);
        $url_action = base_url() . "diskon/Setting/do_save_grosir";
        $str .= "<form method='post' action='$url_action' target='result'>";
        for ($i = 1; $i <= 5; $i++) {

            $cont++;
            $ix++;
            $item = isset($src_dg_obj[$ix]) ? $src_dg_obj[$ix] : (object)array();
            // arrPrintHijau($item);

            $id_data = isset($item->id) ? $item->id : "";
            $jml_id = "jml_$cont";
            $persen_id = "persen_$cont";
            $nilai_id = "nilai_$cont";
            $harga_id = "harga_$cont";
            $grosir_id = "grosir_$cont";

            $minim = isset($item->minim) ? $item->minim : 0;
            $persen = isset($item->persen) ? $item->persen * 1 : 0;
            $nilai = isset($item->nilai) ? $item->nilai * 1 : 0;
            $persen_f = number_format($persen, 2);
            $harga = isset($item->harga) ? $item->harga * 1 : 0;
            $disabled = $minim == 0 ? "disabled" : "";

            // $diskon_loop = $dk->calcDiskon($hrg_jual, array("satu" => $persen), array());
            $diskon_loop = $dk->calcPotongan($hrg_jual, $nilai);

            // arrPrint($diskon_loops);

            $d_nilai = $diskon_loop['nilai'];
            $harga_be = $diskon_loop['harga_be'];
            $harga_af = $diskon_loop['harga_af'];
            $grosir_af = $harga_af * $minim;
            $f_pembulat = 100;
            $link_delete = base_url() . "diskon/Setting/do_delete_grosir?id=$id_data&id_row=$cont";

            $str .= "<div class='row col-md-12'>";
            $str .= "<div class='col-xs-3'><div class='input-group marginn'><span class='input-group-btn'><button type='button' class='btn btn-default'>&#8805;</button></span><input type='text' id='$jml_id' class='form-control' name='minim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$minim'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen_f'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$d_nilai'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$harga_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$harga_af'> </div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$grosir_id' class='form-control' autocomplete='off' disabled onclick=\"this.select()\" value='$grosir_af'> </div></div>";
            $str .= "<div class='col-xs-1'><div class='input-group marginn'><button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button></div></div>";
            $str .= "</div>";
            $str .= "<script type='text/javascript'>
                        // var jml_data = $jml_grosir;
                        var cont_data = $cont_data;
                        var cont_be = $cont -1;
                        var harga = $hrg_jual;
                        if(harga > 100){
                            $('#jml_'+cont_data).prop('disabled', false);
                            
                            // $('#$jml_id').prop('disabled', true);
                            $('#$jml_id').prop('readonly', true);
                            $('#jml_'+cont_data).prop('readonly', false);
                        }                            
                                               
                        /*----membuka field diskon persen dan nilai---*/
                        $('#jml_$cont').keydown(function() {
                            $('#persen_$cont').prop('disabled', false);
                            $('#nilai_$cont').prop('disabled', false);
                            $('#harga_$cont').prop('disabled', false);
                        });
                        
                        /*-----validasi jml harus lebih besar dg jml sebelumnya-- dan nilai diskon harus > sebelumnya--*/
                        $('#jml_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml_be = $('#jml_'+ cont_be).val();
                                var jml_now = $('#jml_$cont').val();
                                var nilai_be = $('#nilai_'+ cont_be).val();
                                var nilai_now = $('#nilai_$cont').val();
                                var harga = $('#harga_$cont').val();
                                var grosir = $('#grosir_$cont').val();
                                var grosir_baru = jml_now * harga;
                                
                                if(Number(jml_now) <= Number(jml_be)){
                                    
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                    });
                                    
                                    $('#persen_$cont').prop('disabled', true);
                                    $('#nilai_$cont').prop('disabled', true);  
                                    $('#jml_$cont').css('color','red');
                                    $('#btn_simpan').prop('disabled', true);
                                            
                                }
                                else {
                                    $('#jml_' + cont_af).prop('disabled', true);     
                                    $('#jml_$cont').css({'background-color': 'yellow','color':'red'});
                                    $('#persen_$cont').css('color','');
                                    $('#nilai_$cont').css('color','');
                                    $('#harga_$cont').css('color','');
                                    $('#grosir_$cont').val(grosir_baru).css({'background-color': 'yellow','color':'green'}); 
                                    
                                    if(Number(nilai_now) > Number(nilai_be)){                                       
                                        $('#btn_simpan').prop('disabled', false);
                                        $('#jml_' + cont_af).prop('disabled', false);
                                    }
                                    else {
                                        $('#btn_simpan').prop('disabled', true);
                                        
                                        // swal({
                                        //     title: 'Upsss.. !!',
                                        //     html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                        // });
                                    }
                                }
                            }, 2000);     
                        });
                                                                                                                                               
                    </script>";
            $str .= "<script type='text/javascript'>
                                                /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/
                        $('#persen_$cont').keyup(function() {
                            setTimeout(function(){
                                var harga = $hrg_jual;
                                var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var persen_be = $('#persen_'+ cont_be).val();
                                var persen_now = $('#persen_$cont').val();
                                var nilai_diskon = harga * (persen_now / 100);
                                // var persen_max = $harga_beli;
                                var harga_baru = harga - nilai_diskon;
                                var rugilaba = harga_baru - hpp;
                                
                                // console.log(persen_now +' *<=* '+ persen_be + ' *** ' + cont_be);
                                if(Number(persen_now) <= Number(persen_be)){
                                    // console.log('ahah');                                    
                                        swal({
                                            title: 'Upsss.. !!',
                                            html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                        });
                                        
                                        $('#btn_simpan').prop('disabled', true);
                                        $('#persen_$cont').css('color','red');
                                        $('#nilai_$cont').css('color','red');
                                        $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                        $('#persen_$cont').css('color','');
                                        $('#nilai_$cont').css('color','');
                                }
                                                                  
                                if(Number(harga_baru) <= Number(hpp)){
                                    swal({
                                            title: 'Upsss.. !!',
                                            html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan ubah diskon sebelum disimpan',
                                        });
                                    
                                    $('#btn_simpan').prop('disabled', true);
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);
                                    $('#harga_$cont').css({'background-color': 'yellow','color':'red'});      
                                }
                                else{
                                    $('#harga_$cont').css({'background-color': '','color':''});
                                }
                                                                
                                $('#persen_$cont').css({'color':'red','background-color':'yellow'});
                                $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                                $('#harga_$cont').css({'color':'green','background-color':'yellow'});
                                $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                                $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                
                            }, 2000);
                        });

                         /*-----validasi diskon nilai harus lebih besar dg yang sebelumnya----*/
                        $('#nilai_$cont').keyup(function() {
                            setTimeout(function(){
                            // delay_v2(function(){
                                var harga = $hrg_jual;
                                var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var nilai_be = $('#nilai_'+ cont_be).val();
                                var nilai_now = $('#nilai_$cont').val();
                                var jml = $('#jml_$cont').val();
                                var harga_baru = harga - nilai_now;
                                var rugilaba = harga_baru - hpp;
                                var grosir = harga_baru * jml;
                                
                                $('#grosir_$cont').val(grosir);
                                // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                                if(Number(nilai_now) <= Number(nilai_be)){                                   
                                            swal({
                                                title: 'Upsss.. !!',
                                                html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                            });
                                            
                                            $('#btn_simpan').prop('disabled', true);         
                                            $('#persen_$cont').css('color','red');
                                            $('#nilai_$cont').css('color','red');
                                            $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                            $('#persen_$cont').css('color','');
                                            $('#nilai_$cont').css('color','');
                                }
                                
                                if(Number(harga_baru) <= Number(hpp)){
                                    swal({
                                            title: 'Upsss.. !!',
                                            html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan',
                                        });
                                    
                                    $('#btn_simpan').prop('disabled', true);         
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);                                    
                                    $('#harga_$cont').css({'background-color': 'red','color':'yellow'});                                    
                                }
                                else{
                                    $('#persen_$cont').css({'background-color': 'yellow','color':'green'});
                                    $('#nilai_$cont').css({'background-color': 'yellow','color':'red'});
                                    $('#harga_$cont').css({'background-color': 'yellow','color':'green'});
                                    $('#grosir_$cont').css({'background-color': 'yellow','color':'green'});
                                    $('#jml_$cont').css({'background-color': 'yellow','color':''});
                                }

                            }, 2000);
                        });                                                                                                    
                        
                    </script>";
            $str .= "<script type='text/javascript'>
                        /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */
                        $('#persen_$cont').blur(function() {
                            var fpembulat = $f_pembulat;
                            var harga = $hrg_jual;
                            var hpp = $harga_beli;
                            var minim = $('#jml_$cont').val();
                            var persen_diskon = $('#persen_$cont').val();
                            var nilai_diskon = harga * (persen_diskon / 100);
                            var harga_baru = harga - nilai_diskon;                            
                            var rugilaba = harga_baru - hpp;                                                          
                            var harga_bulat = RoundTo(harga_baru,fpembulat);
                            
                             if(harga_bulat != harga_baru){
                                 var nilai_diskon = harga - harga_bulat;
                                 var persen_diskon = ((nilai_diskon / harga) * 100);
                                 var harga_baru = harga_bulat;
                             }                             
                                 var grosir_baru = harga_bulat * minim;
                                                    
                            // $('#persen_$cont').val(persen_diskon.toFixed(2));
                            $('#persen_$cont').val(persen_diskon);
                            $('#nilai_$cont').val(nilai_diskon);
                            $('#harga_$cont').val(harga_baru);       
                            $('#grosir_$cont').val(grosir_baru);       
                            console.log(grosir_baru)
                            
                            var cont_af = $cont + 1; 
                            $('#jml_'+cont_af).prop('disabled', false).prop('readonly', false);
                            $('#btn_simpan').prop('disabled', false);
                        });
                        
                         // mengisi diskon persen bila kolom nilai diskon yg diisi
                         $('#nilai_$cont').blur(function() {
                             var fpembulat = $f_pembulat;
                            var harga = $hrg_jual;
                            var hpp = $harga_beli;
                            var minim = $('#jml_$cont').val();
                            var nilai_diskon = $('#nilai_$cont').val();
                            var persen_diskon = (nilai_diskon / harga) * 100;
                            var harga_baru = harga - nilai_diskon;
                            var rugilaba = harga_baru - hpp;
                            var harga_bulat = RoundTo(harga_baru,fpembulat);
                            
                            // $('#persen_$cont').val(persen_diskon.toFixed(2));
                            $('#persen_$cont').val(persen_diskon);
                            $('#harga_$cont').val(harga_baru);
                            
                            var cont_af = $cont + 1; 
                            $('#jml_'+cont_af).prop('disabled', false);
                            $('#btn_simpan').prop('disabled', false);
                                                                            
                        });
                         
                        /*-----validasi harga jual harus lebih besar dg yang sebelumnya----*/
                        $('#harga_$cont').keyup(function() {
                            setTimeout(function(){
                                var harga = $hrg_jual;
                                var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml = $('#jml_$cont').val();
                                var nilai_be = $('#harga_'+ cont_be).val();
                                var nilai_now = $('#harga_$cont').val();
                                var harga_baru = nilai_now;
                                var rugilaba = harga_baru - hpp;
                                var nilai_diskon = harga - harga_baru;
                                // var persen_diskon = ((nilai_diskon / harga) * 100).toFixed(2);
                                var persen_diskon = ((nilai_diskon / harga) * 100);
                                var grosir = jml * harga_baru;
                                
                                $('#nilai_$cont').val(nilai_diskon);  
                                $('#persen_$cont').val(persen_diskon);
                                $('#grosir_$cont').val(grosir);
                                // $('#persen_$cont').css('background-color','');
                                
                                // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                                if(Number(nilai_now) <= Number(hpp)){                                   
                                            swal({
                                                title: 'Upsss.. !!',
                                                html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan 99',
                                            });
                                            
                                            $('#btn_simpan').prop('disabled', true);         
                                            $('#persen_$cont').css('color','red');
                                            $('#nilai_$cont').css('color','red');
                                            $('#harga_$cont').css({'color':'yellow','background-color':'red'});
                                            $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else if(Number(harga_baru) >= Number(nilai_be)){
                                    swal({
                                            title: 'Upsss.. !!',
                                            html: 'harga diskon harus lebih kecil dari ' + nilai_be + 'rupiah 88',
                                        });
                                   
                                    $('#btn_simpan').prop('disabled', true);         
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);                                    
                                    $('#harga_$cont').css({'background-color': 'red','color':'yellow'});
                                }
                                else {
                                    $('#persen_$cont').css({'color':'green','background-color':'yellow'});
                                    $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                                    $('#harga_$cont').css({'color':'red','background-color':'yellow'});
                                    $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                                    $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                    $('#jml_' + cont_af).prop('disabled', false);
                                }
                                
                            }, 2000);
                        });
                        
                        /*--normalisasi fields--*/
//                        $('input').blur(function(){
//                           setTimeout(function(){
//                                // $('input').css({'color':'','background-color':''});
//                           },2000);
//                        });
                                                
                    </script>";

            // $str .= "<div class='col-xs-12 border-cek'>----</div>";
        }
        $str_hidden = "<input type='hidden' name='produk_id' value='$prod_id'>";
        $str .= "<div class='col-xs-12' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
        $str .= "</form>";
        $str .= "<script>
                     $('#btn_simpan').click(function() {
                            setTimeout(function(){                               
                                $('#btn_simpan').prop('disabled', true);
                            }, 500);
                        });
                </script>";

        $form = "";
        $form .= "<div class='overflow-h'>";
        $form .= "<div class='row'>";
        // $form .= "<div class='border-cek col-xs-8 overflow-h'>";
        $form .= $str;
        // $form .= "</div>";
        $form .= "</div>";
        $form .= "</div>";

        echo $form;
    }

    public function viewGrosir()
    {
        $ppn_persen_set = my_ppn_factor();
        // matiHere();
        /* --------------------------------------
                 * grosir
                 * --------------------------------------*/
        // echo my_toko_id();
        // cekMerah(my_cabang_id());
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $condites = array(
                "produk_id" => $prod_id,
            );
            $this->db->where($condites);
        }
        $dg->setTokoId(my_toko_id());
        $src_dg_obj_0 = $dg->callProdukGrosir("");
        // showLast_query("kuning");
        $src_dg_obj = array();
        foreach ($src_dg_obj_0 as $item_obj) {
            $dtime_start = $item_obj->dtime_start;

            if ($dtime_start == null) {
                $src_dg_obj[] = $item_obj;
            }
        }
        /*-----------produk speks------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prod_speks = $pr->callSpecs($prod_id);
        // arrPrint($prod_speks);
        $premi_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->premi_jual * 1 : 0;
        $diskon_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->diskon_persen * 1 : 0;
        // $harga_jual = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->harga_jual * 1 : 0;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs($prod_id);
        // showLast_query("kuning");
        // arrPrint($prod_hargas);
        $harga_speks = array();
        if (isset($prod_hargas[$prod_id])) {
            foreach ($prod_hargas[$prod_id] as $spek_harga) {
                $harga_speks[$spek_harga->jenis_value] = $spek_harga;
            }
        }

        $this->load->library("Diskon");
        $dk = new Diskon();

        // arrPrint($harga_speks[$this->harga_jenis]->nilai);
        // arrPrint($harga_speks);
        $harga_list = isset($harga_speks[$this->harga_jenis]) ? $harga_speks[$this->harga_jenis]->nilai * 1 : 0;
        $harga_list_f = format_harga($harga_list);

        $harga_list_ppn = $harga_list * ((100 + $ppn_persen_set) / 100);
        $harga_list_ppn_f = format_harga($harga_list_ppn);

        // cekHijau($harga_list);
        $harga_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;

        $hrg_jual = $harga_list + (($premi_persen / 100) * $harga_list);
        $hrg_jual_f = formatField_he_format("harga", $hrg_jual);

        $hrg_jual_ppn = $hrg_jual * ((100 + $ppn_persen_set) / 100);
        $hrg_jual_ppn_f = formatField_he_format("harga", $hrg_jual_ppn);


        $harga_beli_f = formatField_he_format("harga", $harga_beli);
        $jml_grosir = sizeof($src_dg_obj);
        $type_awal = $jml_grosir == 0 ? "text" : "text";
        $diskon_satu = $dk->calcDiskon($hrg_jual, array("satu" => $diskon_persen), array());
        // arrPrint($diskon_satu);
        $diskon_nilai = $diskon_satu['nilai'];
        $hrg_jual_diskon = $diskon_satu['harga_af'];
        $hrg_jual_diskon_ppn = $hrg_jual_diskon * ((100 + $ppn_persen_set) / 100);
        $str = "";
        $str .= "<style type='text/css'>
                .form-control {
                margin-top: 1px;
                    padding: 0 5px !important;
                    height: 30px !important;
                }
            </style>";

        $str .= "<div class='row' style='margin-bottom: 20px;'><div class='col-md-8'>";
        $str .= "<h5 class='text-uppercase' style='margin-left: 15px;'>harga beli $harga_beli_f</h5>";
        //        $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan $hrg_jual_f</h4>";
        //        $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list: Rp. $harga_list_f | premi: $premi_persen%</p>";
        $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan <span class='meta'>include PPN</span> $hrg_jual_ppn_f</h4>";
        $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list <span class='meta'>include PPN</span>: Rp. $harga_list_ppn_f | premi: $premi_persen%</p>";
        $str .= "</div></div>";

        $str .= "<div class='row'>";
        $str .= "<div class='col-lg-1'><div class='input-group marginn'>Qty Minimal<input type='$type_awal' id='jml_222' disabled class='form-control text-center' value='1'></div></div>";
        $str .= "<div class='col-lg-2'><div class='input-group marginn'>diskon (%)<input type='$type_awal' id='persen_222' disabled class='form-control' value='$diskon_persen'></div></div>";
        $str .= "<div class='col-lg-2'><div class='input-group marginn'>diskon (Rp)<input type='$type_awal' id='nilai_222' disabled class='form-control' value='$diskon_nilai'></div></div>";
        //        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        //        $str .= "<div class='col-xs-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";

        //        $str .= "<div class='col-lg-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        $str .= "<div class='col-lg-2'><div class='input-group marginn'>Harga (incl. PPN)<input type='$type_awal' id='_harga_222' disabled class='form-control' value='" . number_format($hrg_jual_diskon_ppn, 0) . "'></div></div>";
        //        $str .= "<div class='col-lg-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        $str .= "<div class='col-lg-2'><div class='input-group marginn'>grosir (incl. PPN)<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='" . number_format($hrg_jual_diskon_ppn, 0) . "'></div></div>";

        $str .= "</div>";

        // $str .= "<div class='col-xs-12'>----</div>";

        $cont = 222;
        $cont_data = $cont + $jml_grosir + 1;
        // cekHere($cont_data);
        $ix = '-1';
        // arrPrint($src_dg_obj);
        $url_action = base_url() . "diskon/Setting/do_save_grosir";
        $str .= "<form method='post' action='$url_action' target='result'>";
        for ($i = 1; $i <= 5; $i++) {

            $cont++;
            $ix++;
            $item = isset($src_dg_obj[$ix]) ? $src_dg_obj[$ix] : (object)array();
            // arrPrintHijau($item);

            $id_data = isset($item->id) ? $item->id : "";
            $jml_id = "jml_$cont";
            $persen_id = "persen_$cont";
            $nilai_id = "nilai_$cont";
            $harga_id = "harga_$cont";
            $grosir_id = "grosir_$cont";
            $harga_ppn_id = "harga_ppn_$cont";
            $grosir_ppn_id = "grosir_ppn_$cont";

            $minim = isset($item->minim) ? $item->minim : 0;
            $persen = isset($item->persen) ? $item->persen * 1 : 0;
            $nilai = isset($item->nilai) ? $item->nilai * 1 : 0;
            $persen_f = number_format($persen, 6);
            $harga = isset($item->harga) ? $item->harga * 1 : 0;
            $disabled = $minim == 0 ? "disabled" : "";

            // $hrg_jual_ppn_x = $harga;
            $hrg_jual_ppn_x = $hrg_jual_ppn / ((100 + $ppn_persen_set) / 100);
            // $diskon_loop = $dk->calcDiskon($hrg_jual, array("satu" => $persen), array());
            // $diskon_loop = $dk->calcPotongan($hrg_jual, $nilai);
            // cekHijau("$nilai");
            $diskon_loop = $dk->calcPotongan($hrg_jual_ppn_x, $nilai);

            // arrPrint($diskon_loop);

            $d_nilai = ($diskon_loop['nilai'] * 1) * ((100 + $ppn_persen_set) / 100);
            // $d_nilai = ($diskon_loop['nilai'] * 1);
            $harga_be = $diskon_loop['harga_be'];
            $harga_af = $diskon_loop['harga_af'] * 1;
            $grosir_af = $harga_af * $minim;

            $harga_ppn_af = $harga_af * ((100 + $ppn_persen_set) / 100);
            $grosir_ppn_af = $grosir_af * ((100 + $ppn_persen_set) / 100);
            // $harga_ppn_af = $harga_af;
            // $grosir_ppn_af = $grosir_af;
            // cekHere("$harga_ppn_af = $harga_af * ((100 + $ppn_persen_set) / 100);");

            $f_pembulat = 100;
            $link_delete = base_url() . "diskon/Setting/do_delete_grosir?id=$id_data&id_row=$cont";

            $str .= "<div class='row'>";
            $str .= "<div class='col-xs-1'><div class='input-group marginn'><span class='input-group-btn'><button type='button' class='btn btn-default'>&#8805;</button></span><input type='text' id='$jml_id' class='form-control text-center' name='minim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$minim'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen_f'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($d_nilai) . "'></div></div>";
            //            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($d_nilai, 4) . "'></div></div>";
            //            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$harga_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$harga_af'> </div></div>";
            //            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$grosir_id' class='form-control' autocomplete='off' disabled onclick=\"this.select()\" value='$grosir_af'> </div></div>";

            //            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$harga_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$harga_af'> </div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='harga[]' id='$harga_ppn_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($harga_ppn_af, 0) . "'> </div></div>";
            //            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$grosir_id' class='form-control' autocomplete='off' disabled onclick=\"this.select()\" value='$grosir_af'> </div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='harga[]' id='$grosir_ppn_id' class='form-control' autocomplete='off' disabled onclick=\"this.select()\" value='" . number_format($grosir_ppn_af, 0) . "'> </div></div>";

            $str .= "<div class='col-xs-1'><div class='input-group marginn'><button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button></div></div>";
            $str .= "</div>";
            $str .= "<script type='text/javascript'>
                        // var jml_data = $jml_grosir;
                        var cont_data = $cont_data;
                        var cont_be = $cont -1;
                        var harga = $hrg_jual;
                        var harga_ppn = $hrg_jual_ppn;
                        if(harga > 100){
                            $('#jml_'+cont_data).prop('disabled', false);

                            // $('#$jml_id').prop('disabled', true);
                            $('#$jml_id').prop('readonly', true);
                            $('#jml_'+cont_data).prop('readonly', false);
                        }                            
                                               
                        /*----membuka field diskon persen dan nilai---*/
                        $('#jml_$cont').keydown(function() {
                            $('#persen_$cont').prop('disabled', false);
                            $('#nilai_$cont').prop('disabled', false);
//                            $('#harga_$cont').prop('disabled', false);
                        });
                        
                        /*-----validasi jml harus lebih besar dg jml sebelumnya-- dan nilai diskon harus > sebelumnya--*/
                        $('#jml_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml_be = $('#jml_'+ cont_be).val();
                                var jml_now = $('#jml_$cont').val();
                                var nilai_be = $('#nilai_'+ cont_be).val();
                                var nilai_now = $('#nilai_$cont').val();
                                var harga = $('#harga_$cont').val();
                                var grosir = $('#grosir_$cont').val();
                                var grosir_baru = jml_now * harga;
                                
                                var harga_ppn = removeCommas($('#harga_ppn_$cont').val());
                                var grosir_ppn = $('#grosir_ppn_$cont').val();
                                var grosir_ppn_baru = addCommas( (jml_now*harga_ppn).toFixed(4) );
                                
                                if(Number(jml_now) <= Number(jml_be)){
                                    
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                    });
                                    
                                    $('#persen_$cont').prop('disabled', true);
                                    $('#nilai_$cont').prop('disabled', true);  
                                    $('#jml_$cont').css('color','red');
                                    $('#btn_simpan').prop('disabled', true);
                                            
                                }
                                else {
                                    $('#jml_' + cont_af).prop('disabled', true);     
                                    $('#jml_$cont').css({'background-color': 'yellow','color':'red'});
                                    $('#persen_$cont').css('color','');
                                    $('#nilai_$cont').css('color','');
                                    $('#harga_$cont').css('color','');
                                    $('#grosir_$cont').val(grosir_baru).css({'background-color': 'yellow','color':'green'}); 
                                    
                                    $('#harga_ppn_$cont').css('color','');
                                    $('#grosir_ppn_$cont').val(grosir_ppn_baru).css({'background-color': 'yellow','color':'green'}); 
                                    
                                    if(Number(nilai_now) > Number(nilai_be)){                                       
                                        $('#btn_simpan').prop('disabled', false);
                                        $('#jml_' + cont_af).prop('disabled', false);
                                    }
                                    else {
                                        $('#btn_simpan').prop('disabled', true);
                                        
                                        // swal({
                                        //     title: 'Upsss.. !!',
                                        //     html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                        // });
                                    }
                                }
                            }, 2000);     
                        });
                                                                                                                                               
                    </script>";
            $str .= "<script type='text/javascript'>
                                                /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/
                        $('#persen_$cont').keyup(function() {
                            setTimeout(function(){
                                var harga = $hrg_jual;
                                var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var persen_be = $('#persen_'+ cont_be).val();
                                var persen_now = $('#persen_$cont').val();
                                var nilai_diskon = harga * (persen_now / 100);
                                // var persen_max = $harga_beli;
                                var harga_baru = harga - nilai_diskon;
                                var rugilaba = harga_baru - hpp;
                                
                                // console.log(persen_now +' *<=* '+ persen_be + ' *** ' + cont_be);
                                if(Number(persen_now) <= Number(persen_be)){
                                    // console.log('ahah');                                    
                                        swal({
                                            title: 'Upsss.. !!',
                                            html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                        });
                                        
                                        $('#btn_simpan').prop('disabled', true);
                                        $('#persen_$cont').css('color','red');
                                        $('#nilai_$cont').css('color','red');
                                        $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                        $('#persen_$cont').css('color','');
                                        $('#nilai_$cont').css('color','');
                                }
                                                                  
                                if(Number(harga_baru) <= Number(hpp)){
                                    swal({
                                            title: 'Upsss.. !!',
                                            html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan ubah diskon sebelum disimpan',
                                        });
                                    
                                    $('#btn_simpan').prop('disabled', true);
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);
                                    $('#harga_$cont').css({'background-color': 'yellow','color':'red'});      
                                }
                                else{
                                    $('#harga_$cont').css({'background-color': '','color':''});
                                }
                                                                
                                $('#persen_$cont').css({'color':'red','background-color':'yellow'});
                                $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                                $('#harga_$cont').css({'color':'green','background-color':'yellow'});
                                $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                                $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                
                            }, 2000);
                        });

                         /*-----validasi diskon nilai harus lebih besar dg yang sebelumnya----*/
                        $('#nilai_$cont').keyup(delay_v2(function(){
//                            setTimeout(function(){
                            // delay_v2(function(){
                                var harga = $hrg_jual;
                                var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var nilai_be = $('#nilai_'+ cont_be).val();
                                var nilai_now = removeCommas($('#nilai_$cont').val());
                                var jml = $('#jml_$cont').val();
                                var harga_baru = harga - nilai_now;
                                var rugilaba = harga_baru - hpp;
                                var grosir = harga_baru * jml;

                                var harga_ppn = (harga*1.11)-nilai_now;
                                var grosir_ppn_baru = addCommas( (jml*harga_ppn).toFixed(4) );

                                $('#grosir_$cont').val(grosir);
                                $('#grosir_ppn_$cont').val(grosir_ppn_baru).css({'background-color': 'yellow','color':'green'});
                                $('#harga_ppn_$cont').val(addCommas(harga_ppn.toFixed(4))).css({'background-color': 'yellow','color':'green'});
                                $('#nilai_$cont').val(addCommas(nilai_now))
                                // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);

                                if(Number(nilai_now) <= Number(nilai_be)){
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                    });

                                    $('#btn_simpan').prop('disabled', true);
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                    $('#persen_$cont').css('color','');
                                    $('#nilai_$cont').css('color','');
                                }
                                
                                if(Number(harga_baru) <= Number(hpp)){
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan',
                                    });
                                    
                                    $('#btn_simpan').prop('disabled', true);         
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);                                    
                                    $('#harga_$cont').css({'background-color': 'red','color':'yellow'});

                                }
                                else{
                                    $('#persen_$cont').css({'background-color': 'yellow','color':'green'});
                                    $('#nilai_$cont').css({'background-color': 'yellow','color':'red'});
                                    $('#harga_$cont').css({'background-color': 'yellow','color':'green'});
                                    $('#grosir_$cont').css({'background-color': 'yellow','color':'green'});
                                    $('#jml_$cont').css({'background-color': 'yellow','color':''});
                                }

//                            }, 2000);
                        },250));
                        
                    </script>";
            $str .= "<script type='text/javascript'>
                        /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */
                        $('#persen_$cont').blur(function() {
                            var fpembulat = $f_pembulat;
                            var harga = $hrg_jual;
                            var hpp = $harga_beli;
                            var minim = $('#jml_$cont').val();
                            var persen_diskon = $('#persen_$cont').val();
                            var nilai_diskon = (harga * (persen_diskon / 100))*1.11;
                            var harga_baru = ( (harga*1.11) - nilai_diskon);
                            var rugilaba = harga_baru - hpp;                                                          
//                            var harga_bulat = RoundTo(harga_baru,fpembulat);
                            var harga_bulat = harga_baru;

                             if(harga_bulat != harga_baru){
                                 var nilai_diskon = harga - harga_bulat;
                                 var persen_diskon = ((nilai_diskon / harga) * 100);
                                 var harga_baru = harga_bulat;
                             }                             

                            var grosir_baru = harga_bulat * minim;
                                                    
                            // $('#persen_$cont').val(persen_diskon.toFixed(2));
                            $('#persen_$cont').val(persen_diskon);
                            $('#nilai_$cont').val( addCommas( nilai_diskon.toFixed(4) ));
                            $('#harga_$cont').val( addCommas( harga_baru.toFixed(4) ));
                            $('#harga_ppn_$cont').val( addCommas( (harga_baru).toFixed(4) ));
                            $('#grosir_$cont').val( addCommas( grosir_baru.toFixed(4) ));
                            $('#grosir_ppn_$cont').val( addCommas( (grosir_baru).toFixed(4) ));

                            console.log('grosir_baru: ' + grosir_baru)
                            console.log('harga_baru: ' + harga_baru)

                            var cont_af = $cont + 1; 
                            $('#jml_'+cont_af).prop('disabled', false).prop('readonly', false);
                            $('#btn_simpan').prop('disabled', false);
                        });
                        
                         // mengisi diskon persen bila kolom nilai diskon yg diisi
                         $('#nilai_$cont').blur(function() {
                             var fpembulat = $f_pembulat;
                            var harga = $hrg_jual;
                            var hpp = $harga_beli;
                            var minim = $('#jml_$cont').val();
                            var nilai_diskon = removeCommas($('#nilai_$cont').val())/1.11;
                            var persen_diskon = (nilai_diskon / harga) * 100;
                            var harga_baru = harga - nilai_diskon;
                            var rugilaba = harga_baru - hpp;
                            var harga_bulat = RoundTo(harga_baru,fpembulat);
                            
                            // $('#persen_$cont').val(persen_diskon.toFixed(2));
                            $('#persen_$cont').val(persen_diskon);
                            $('#harga_$cont').val(harga_baru*1.11);
                            
                            var cont_af = $cont + 1; 
                            $('#jml_'+cont_af).prop('disabled', false);
                            $('#btn_simpan').prop('disabled', false);
                                                                            
                        });
                         
                        /*-----validasi harga jual harus lebih besar dg yang sebelumnya----*/
                        $('#harga_$cont').keyup(function() {
                            setTimeout(function(){
                                var harga = $hrg_jual;
                                var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml = $('#jml_$cont').val();
                                var nilai_be = $('#harga_'+ cont_be).val();
                                var nilai_now = removeCommas($('#harga_$cont').val());
                                var harga_baru = nilai_now;
                                var rugilaba = harga_baru - hpp;
                                var nilai_diskon = harga - harga_baru;
                                // var persen_diskon = ((nilai_diskon / harga) * 100).toFixed(2);
                                var persen_diskon = ((nilai_diskon / harga) * 100);
                                var grosir = jml * harga_baru;
                                
                                $('#nilai_$cont').val(nilai_diskon);  
                                $('#persen_$cont').val(persen_diskon);
                                $('#grosir_$cont').val(grosir);
                                // $('#persen_$cont').css('background-color','');
                                
                                // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                                if(Number(nilai_now) <= Number(hpp)){                                   
                                            swal({
                                                title: 'Upsss.. !!',
                                                html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan 99',
                                            });
                                            
                                            $('#btn_simpan').prop('disabled', true);         
                                            $('#persen_$cont').css('color','red');
                                            $('#nilai_$cont').css('color','red');
                                            $('#harga_$cont').css({'color':'yellow','background-color':'red'});
                                            $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else if(Number(harga_baru) >= Number(nilai_be)){
                                    swal({
                                            title: 'Upsss.. !!',
                                            html: 'harga diskon harus lebih kecil dari ' + nilai_be + 'rupiah 88',
                                        });
                                   
                                    $('#btn_simpan').prop('disabled', true);         
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);                                    
                                    $('#harga_$cont').css({'background-color': 'red','color':'yellow'});
                                }
                                else {
                                    $('#persen_$cont').css({'color':'green','background-color':'yellow'});
                                    $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                                    $('#harga_$cont').css({'color':'red','background-color':'yellow'});
                                    $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                                    $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                    $('#jml_' + cont_af).prop('disabled', false);
                                }
                                
                            }, 2000);
                        });
                        
                        /*--normalisasi fields--*/
//                        $('input').blur(function(){
//                           setTimeout(function(){
//                                // $('input').css({'color':'','background-color':''});
//                           },2000);
//                        });
                                                
                    </script>";

            // $str .= "<div class='col-xs-12 border-cek'>----</div>";
        }
        $str_hidden = "<input type='hidden' name='produk_id' value='$prod_id'>";
        $str .= "<div class='col-xs-12' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
        $str .= "</form>";
        $str .= "<script>
                     $('#btn_simpan').click(function() {
                            setTimeout(function(){                               
                                $('#btn_simpan').prop('disabled', true);
                            }, 500);
                        });
                </script>";

        $form = "";
        $form .= "<div class='overflow-h'>";
        $form .= "<div class='container-fluid'>";
        // $form .= "<div class='border-cek col-xs-8 overflow-h'>";
        $form .= $str;
        // $form .= "</div>";
        $form .= "</div>";
        $form .= "</div>";
        $form .= "<script>$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl');</script>";
        echo $form;
    }

    public function viewScheduler()
    {
        // matiHere();
        /* --------------------------------------
                 * grosir
                 * --------------------------------------*/
        // echo my_toko_id();
        // cekMerah(my_cabang_id());
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $condites = array(
                "produk_id" => $prod_id,
            );
            $this->db->where($condites);
        }
        $dg->setTokoId(my_toko_id());
        $src_dg_obj_0 = $dg->callProdukGrosir("");
        // showLast_query("kuning");
        // arrPrintPink($src_dg_obj_0);

        $komp = array();
        $src_dg_obj = array();
        $src_dg_obj_default = array();
        foreach ($src_dg_obj_0 as $item_obj) {
            $dtime_start = $item_obj->dtime_start;
            $dtime_start = $item_obj->dtime_start;
            $minim = $item_obj->minim;

            if ($dtime_start != null) {
                $src_dg_obj[] = $item_obj;
                $komp[$minim]['sch'] = $item_obj;
            }
            else {
                $src_dg_obj_default[] = $item_obj;
                $komp[$minim]['def'] = $item_obj;
            }

            // $minim_keis[$minim] =  $minim;
        }
        // arrPrintHijau($src_dg_obj);
        // arrPrintHijau($src_dg_obj_default);
        // arrPrintHijau($komp);
        // arrPrint(array_keys($komp));
        $minim_keis = array_keys($komp);

        /*-----------produk speks------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prod_speks = $pr->callSpecs($prod_id);
        // arrPrint($prod_speks);
        $premi_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->premi_jual * 1 : 0;
        $diskon_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->diskon_persen * 1 : 0;
        $harga_jual = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->harga_jual * 1 : 0;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs($prod_id);
        // showLast_query("kuning");
        // arrPrint($prod_hargas);
        $harga_speks = array();
        if (isset($prod_hargas[$prod_id])) {
            foreach ($prod_hargas[$prod_id] as $spek_harga) {
                $harga_speks[$spek_harga->jenis_value] = $spek_harga;
            }
        }

        $this->load->library("Diskon");
        $dk = new Diskon();

        // arrPrint($harga_speks);
        $harga_list = isset($harga_speks["harga_list"]) ? $harga_speks["harga_list"]->nilai * 1 : 0;
        $harga_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;
        $hrg_jual = $harga_list + (($premi_persen / 100) * $harga_list);
        // $hrg_jual_f = formatField_he_format("harga", $hrg_jual);
        $hrg_jual_f = formatField_he_format("harga", $hrg_jual);
        $harga_beli_f = formatField_he_format("harga", $harga_beli);
        $jml_grosir = sizeof($src_dg_obj);
        $type_awal = $jml_grosir == 0 ? "text" : "text";
        $diskon_satu = $dk->calcDiskon($hrg_jual, array("satu" => $diskon_persen), array());
        // arrPrint($diskon_satu);
        $diskon_nilai = $diskon_satu['nilai'];
        $hrg_jual_diskon = $diskon_satu['harga_af'];
        $str = "";
        $str .= "<style type='text/css'>
                .form-control {
                margin-top: 1px;
                    padding: 0 5px !important;
                    height: 30px !important;
                }
            </style>";

        $str .= "<div class='row' style='margin-bottom: 20px;'><div class='col-md-6'>";
        $str .= "<h5 class='text-uppercase' style='mmargin-left: 15px;'>harga beli $harga_beli_f</h5>";
        $str .= "<h4 class='text-uppercase' style='mmargin-left: 15px;'>harga jual satuan $hrg_jual_f</h4>";
        // $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list: Rp. $hrg_jual_f | premi: $premi_persen%</p>";
        $str .= "</div></div>";
        // $str .= "<div class='row col-md-12'>";
        // $str .= "<div class='col-xs-3'><div class='input-group marginn'>Jumlah Minimal<input type='$type_awal' id='jml_222' disabled class='form-control' value='1'></div></div>";
        // $str .= "<div class='col-xs-2'><div class='input-group marginn'>diskon (%)<input type='$type_awal' id='persen_222' class='form-control' value='$diskon_persen'></div></div>";
        // $str .= "<div class='col-xs-2'><div class='input-group marginn'>diskon (Rp)<input type='$type_awal' id='nilai_222' class='form-control' value='$diskon_nilai'></div></div>";
        // $str .= "<div class='col-xs-3'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' class='form-control' value='$hrg_jual_diskon'></div></div>";
        // $str .= "</div>";
        // $str .= "<div class='col-xs-12'>----</div>";

        $cont = 222;
        $cont_data = $cont + $jml_grosir + 1;
        // cekHere($cont_data);
        $ix = '-1';
        // arrPrint($src_dg_obj);
        $url_action = base_url() . "diskon/Setting/do_save_scheduler";
        $str .= "<form method='post' action='$url_action' target='result'>";
        $str .= "<table>";

        $str .= "<tr>";
        $str .= "<th>jumlah minimal</th>";
        $str .= "<th>ddefault (%)</th>";
        $str .= "<th>diskon (%)</th>";
        $str .= "<th>diskon nilai (Rp)</th>";
        $str .= "<th>dndefault (Rp)</th>";
        $str .= "<th>harga</th>";
        $str .= "<th>tgl berlaku</th>";
        $str .= "<th>tgl berakhir</th>";
        $str .= "<th>delete</th>";
        $str .= "</tr>";

        for ($i = 1; $i <= 5; $i++) {
            $str .= "<tr>";

            $cont++;
            $ix++;
            $item = isset($src_dg_obj[$ix]) ? $src_dg_obj[$ix] : (object)array();
            // arrPrintHijau($item);

            $id_data = isset($item->id) ? $item->id : "";
            $jml_id = "jml_$cont";
            $persen_id = "persen_$cont";
            $nilai_id = "nilai_$cont";
            $harga_id = "harga_$cont";
            $tgl_id = "tgl_start_$cont";
            $tgl_stop_id = "tgl_stop_$cont";

            $minim = isset($item->minim) ? $item->minim : 0;
            $persen = isset($item->persen) ? $item->persen * 1 : 0;
            $harga = isset($item->harga) ? $item->harga * 1 : 0;
            $disabled = $minim == 0 ? "disabled" : "";

            $persen_default = isset($item->persen_default) ? $item->persen_default * 1 : 0;
            $nilai_default = isset($item->nilai_default) ? $item->nilai_default * 1 : 0;

            $diskon_loop = $dk->calcDiskon($hrg_jual, array("satu" => $persen), array());
            $d_nilai = $diskon_loop['nilai'];
            $harga_af = $diskon_loop['harga_af'];
            $link_delete = base_url() . "diskon/Setting/do_delete_grosir?id=$id_data&id_row=$cont";

            $tgl_start_af = isset($item->dtime_start) ? formatTanggal($item->dtime_start, 'Y-m-d') : "";
            $tgl_stop_af = isset($item->dtime_end) ? formatTanggal($item->dtime_end, 'Y-m-d') : "";
            $readonly = $tgl_start_af != null ? "" : "readonly";
            // $str .= "<div class='row col-md-12'>";
            // $str .= "<div class='col-xs-3'><div class='input-group marginn'><span class='input-group-btn'><button type='button' class='btn btn-default'>&#8805;</button></span><input type='text' id='$jml_id' class='form-control' name='minim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$minim'></div></div>";
            // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen'></div></div>";
            // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$d_nilai'></div></div>";
            // $str .= "<div class='col-xs-4'><div class='input-group marginn'><input type='text' name='harga[]' id='$harga_id' class='form-control' readonly value='$harga_af'></div></div>";
            // $str .= "<div class='col-xs-1'><div class='input-group marginn'><button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button></div></div>";
            // $str .= "</div>";
            $btn_delete = "<button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button>";
            $str .= "<td><div class='input-group marginn'><span class='input-group-btn'><button type='button' class='btn btn-default'>&#8805;</button></span><input type='text' id='$jml_id' class='form-control' name='minim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$minim'></td>";

            $str .= "<td><input type='text' name='' id='' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen_default'></td>";
            $str .= "<td><input type='text' name='' id='' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$nilai_default'></td>";

            $str .= "<td><input type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen'></td>";
            $str .= "<td><input type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$d_nilai'></td>";
            $str .= "<td><input type='text' name='harga[]' id='$harga_id' class='form-control' readonly value='$harga_af'></td>";
            $str .= "<td><input type='date' name='tgl_start[]' id='$tgl_id' class='form-control' $readonly value='$tgl_start_af'></td>";
            $str .= "<td><input type='date' name='tgl_stop[]' id='$tgl_stop_id' class='form-control' $readonly value='$tgl_stop_af'></td>";
            $str .= "<td>$btn_delete</td>";
            // $str .= "<td>";

            $str .= "<script>
                        // var jml_data = $jml_grosir;
                        var cont_data = $cont_data;
                        var cont_be = $cont -1;
                        var harga = $hrg_jual;
                        if(harga > 100){
                            $('#jml_'+cont_data).prop('disabled', false);
                        }

                        /*----membuka field diskon persen dan nilai---*/
                        $('#jml_$cont').keydown(function() {
                            $('#persen_$cont').prop('disabled', false);
                            $('#nilai_$cont').prop('disabled', false);
                            // $('#harga_$cont').prop('disabled', false);
                        });

                        /*-----validasi jml harus lebih besar dg jml sebelumnya----*/
                        $('#jml_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml_be = $('#jml_'+ cont_be).val();
                                var jml_now = $('#jml_$cont').val();

                                if(Number(jml_now) <= Number(jml_be)){

                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                    });

                                    $('#persen_$cont').prop('disabled', true);
                                    $('#nilai_$cont').prop('disabled', true);

                                    $('#jml_$cont').css('color','red');

                                }
                                else {
                                    $('#jml_$cont').css('color','');
                                    $('#jml_' + cont_af).prop('disabled', true);
                                    $('#tgl_start_$cont').prop('readonly', false);
                                    $('#tgl_stop_$cont').prop('readonly', false);
                                }
                            }, 3000);
                        });

                        /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/
                        $('#persen_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var persen_be = $('#persen_'+ cont_be).val();
                                var persen_now = $('#persen_$cont').val();
                                // console.log(persen_now +' *<=* '+ persen_be + ' *** ' + cont_be);
                                if(Number(persen_now) <= Number(persen_be)){
                                    // console.log('ahah');
                                        swal({
                                            title: 'Upsss.. !!',
                                            html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                        });

                                        $('#btn_simpan').prop('disabled', true);
                                        $('#persen_$cont').css('color','red');
                                        $('#nilai_$cont').css('color','red');
                                        $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                        $('#persen_$cont').css('color','');
                                        $('#nilai_$cont').css('color','');
                                }
                            }, 2500);
                        });

                        /*-----validasi diskon nilai harus lebih besar dg yang sebelumnya----*/
                        $('#nilai_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var nilai_be = $('#nilai_'+ cont_be).val();
                                var nilai_now = $('#nilai_$cont').val();
                                console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                                if(Number(nilai_now) <= Number(nilai_be)){
                                    console.log('ahah');

                                            swal({
                                                title: 'Upsss.. !!',
                                                html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                            });

                                            $('#btn_simpan').prop('disabled', true);
                                            $('#persen_$cont').css('color','red');
                                            $('#nilai_$cont').css('color','red');
                                            $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                            $('#persen_$cont').css('color','');
                                            $('#nilai_$cont').css('color','');
                                }
                            }, 3000);
                        });

                        // mensisi diskon nilai bila kolom persen diskon yg diisi
                        $('#persen_$cont').blur(function() {
                            var harga = $hrg_jual;
                            var minim = $('#jml_$cont').val();
                            var persen_diskon = $('#persen_$cont').val();
                            var nilai_diskon = harga * (persen_diskon / 100);
                            var harga_baru = harga - nilai_diskon;

                            $('#nilai_$cont').val(nilai_diskon);
                            $('#harga_$cont').val(harga_baru);

                            var cont_af = $cont + 1;
                            $('#jml_'+cont_af).prop('disabled', false);
                            $('#btn_simpan').prop('disabled', false);
                        });

                         // mengisi diskon persen bila kolom nilai diskon yg diisi
                         $('#nilai_$cont').blur(function() {
                            var harga = $hrg_jual;
                            var minim = $('#jml_$cont').val();
                            var nilai_diskon = $('#nilai_$cont').val();
                            var persen_diskon = (nilai_diskon / harga) * 100;
                            var harga_baru = harga - nilai_diskon;

                            $('#persen_$cont').val(persen_diskon);
                            $('#harga_$cont').val(harga_baru);

                            var cont_af = $cont + 1;
                            $('#jml_'+cont_af).prop('disabled', false);
                            $('#btn_simpan').prop('disabled', false);
                         });
                    </script>";

            // $str .= "<div class='col-xs-12 border-cek'>----</div>";
            $str .= "</tr>";
        }
        $str .= "</table>";
        $str_hidden = "<input type='hidden' name='produk_id' value='$prod_id'>";
        $str .= "<div class='col-xs-12' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
        $str .= "</form>";

        $form = "";
        $form .= "<div class='overflow-h'>";
        $form .= "<div class=''>";
        // $form .= "<div class='border-cek col-xs-8 overflow-h'>";
        $form .= $str;
        // $form .= "</div>";
        $form .= "</div>";
        $form .= "</div>";

        echo $form;
    }

    public function do_save_scheduler()
    {
        arrPrint($_POST);
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();

        $minims = $_POST['minim'];
        // $maxims = $_POST['maxim'];
        $persens = $_POST['persen'];
        $nilais = $_POST['nilai'];
        $hargas = $_POST['harga'];
        $produk_id = $_POST['produk_id'];
        $tgl_start = $_POST['tgl_start'];
        $tgl_stop = $_POST['tgl_stop'];
        $toko_id = my_toko_id();

        $this->db->trans_start();
        $urutan = 0;
        foreach ($persens as $ix => $persen) {
            $urutan++;
            cekBiru($persen);
            $ix_af = $ix + 1;
            $jml_maxim = isset($minims[$ix_af]) && ($minims[$ix_af] > 0) ? ($minims[$ix_af] - 1) : 0;
            $data_barus = array(
                "minim" => $minims[$ix],
                "maxim" => $jml_maxim,
                "persen" => $persen,
                "nilai" => $nilais[$ix],
                "harga" => $hargas[$ix],
                "produk_id" => $produk_id,
                "dtime_start" => $tgl_start[$ix],
                "dtime_end" => $tgl_stop[$ix],
                "cabang_id" => my_cabang_id(),
                "toko_id" => $toko_id,
                "urutan" => $urutan,
                "author" => my_id(),
                "status" => 1,
            );

            arrPrintHijau($data_barus);
            $dg->saveProdukGrosir($data_barus);
            showLast_query("kuning");
            // break;

            /*update ke relasi satuan*/
            // $this->load->model("Mdls/MdlProdukSatuanRelasi");
            // echo "<div id='update_satuan'></div>";
            // $link_update_satuan = base_url() . "Satuan/doEditRelasi?key=qty&pid=1&id=1&value=".$minims[$ix]."&tokoID=$toko_id";
            // echo "<script>$('#update_satuan').load('$link_update_satuan');</script>";
        }


        // matiHere(__LINE__ . " belum commit");
        $this->db->trans_complete();

        echo lgShowSuccess("Sukses", "Harga grosir berhasil disimpan");
    }

    public function viewSatuan()
    {
        /* --------------------------------------
                 * grosir
                 * --------------------------------------*/
        // arrPrintKuning($_GET);
        // echo my_toko_id();
        // cekMerah(my_cabang_id());
        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $psr = new MdlProdukSatuanRelasi();
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $psr->setTokoId(my_toko_id());
            $src_satuan = $psr->lookUpRelasiSatuan($prod_id);
            // showLast_query("hijau");
            // arrPrintKuning($src_satuan[$prod_id]);
            $src_dg_obj = $src_satuan[$prod_id];

        }

        // arrPrintPink($src_dg_obj);
        /*-----------produk speks------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $prod_speks = $pr->callSpecs($prod_id);
        // arrPrint($prod_speks);
        $premi_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->premi_jual * 1 : 0;
        $diskon_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->diskon_persen * 1 : 0;
        $harga_jual = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->harga_jual * 1 : 0;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs($prod_id);
        // showLast_query("kuning");
        // arrPrint($prod_hargas);
        $harga_speks = array();
        if (isset($prod_hargas[$prod_id])) {
            foreach ($prod_hargas[$prod_id] as $spek_harga) {
                $harga_speks[$spek_harga->jenis_value] = $spek_harga;
            }
        }

        $diskon_nilai = 0;
        $main_src = array();
        foreach ($src_dg_obj as $ke => $item) {
            $item_tambahan['diskon_persen'] = $ke == 0 ? $diskon_persen : 0;
            $item_tambahan['diskon_nilai'] = $diskon_nilai;
            $main_src[$ke] = $item + $item_tambahan;
        }
        // arrPrintWebs($main_src);

        $this->load->library("Diskon");
        $dk = new Diskon();

        // arrPrint($harga_speks);
        $harga_list = isset($harga_speks[$this->harga_jenis]) ? $harga_speks[$this->harga_jenis]->nilai * 1 : 0;
        $harga_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;
        $hrg_jual = $harga_list + (($premi_persen / 100) * $harga_list);
        $hrg_beli_f = formatField_he_format("harga", $harga_beli);
        $hrg_jual_f = formatField_he_format("harga", $hrg_jual);
        $jml_grosir = sizeof($src_dg_obj);
        $type_awal = $jml_grosir == 0 ? "hidden" : "hidden";
        $diskon_satu = $dk->calcDiskon($hrg_jual, array("satu" => $diskon_persen), array());
        // arrPrint($diskon_satu);
        $dk->setTokoId(my_toko_id());
        $pro_grosiers = $dk->callProdukDiskon($prod_id);
        $pro_diskons = $pro_grosiers['grosir'];
        // arrPrintKuning($pro_diskons);
        $db_diskon = array();
        foreach ($pro_diskons as $pro_diskon) {
            $db_minim = $pro_diskon['minim'];
            $db_dtime_start = $pro_diskon['dtime_start'];

            if ($db_dtime_start == null) {
                $db_diskon[$db_minim] = $pro_diskon;
            }
        }
        // arrPrintHijau($db_diskon);
        $diskon_nilai = $diskon_satu['nilai'];
        $hrg_jual_diskon = $diskon_satu['harga_af'];
        $hrg_jual_diskon_dasar = "";
        $str = "";
        $str .= "<style type='text/css'>
                .form-control {
                margin-top: 1px;
                    padding: 0 5px !important;
                    height: 30px !important;
                }
            </style>";
        $str .= "<div class='row' style='margin-bottom: 20px;'>
<div class='col-md-6'>
<h5 class='text-uppercase ' style='margin-left: 15px;'>harga beli $hrg_beli_f</h5>
<h4 class='text-uppercase ' style='margin-left: 15px;margin-bottom: 0;margin-top: 0;'>harga jual satuan $hrg_jual_f</h4>
</div>
</div>";
        $str .= "<div class='row col-md-12'>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Satuan Yang Berlaku<input type='$type_awal' id='jmll_222' disabled class='form-control' value='1'></div></div>";
        $str .= "<div class='col-xs-1'><div class='input-group marginn'>QTY<input type='$type_awal' id='persenn_222' class='form-control' value='$diskon_persen'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>diskon (%)<input type='$type_awal' id='nilain_222' class='form-control' value='$diskon_nilai'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>diskon (Rp)<input type='$type_awal' id='nilaii_222' class='form-control' value='$diskon_nilai'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Harga satuan<input type='$type_awal' id='_hargaa_222' class='form-control' value='$hrg_jual_diskon'></div></div>";
        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Harga unit<input type='$type_awal' id='_harga_dasarr_222' class='form-control' value='$hrg_jual_diskon_dasar'></div></div>";
        $str .= "</div>";
        // $str .= "<div class='col-xs-12'>----</div>";

        $cont = 22;
        $cont_data = $cont + $jml_grosir + 1;
        // cekHere($cont_data);
        $ix = '-1';
        // arrPrint($src_dg_obj);
        $url_action = base_url() . "diskon/Setting/do_save_grosir";
        $str .= "<form method='post' action='$url_action' target='result'>";
        // for ($i = 1; $i <= 5; $i++) {
        foreach ($main_src as $item) {

            // arrPrintWebs($item);
            $cont++;
            $ix++;
            // $item = isset($src_dg_obj[$ix]) ? $src_dg_obj[$ix] : (object)array();
            // arrPrintHijau($item);
            $produk_id = $item['produk_id'];
            $satuan_nama = $item['satuan_nama'];
            $minim = $satuan_qty = $item['qty'];
            // $d_persen = $diskon_persen;
            $db_persen = isset($db_diskon[$minim]) ? $db_diskon[$minim]['persen'] * 1 : 0;
            // arrPrintPink($db_diskon[$minim]['persen']);

            $id_data = isset($item->id) ? $item->id : "";
            $jml_id = "jml_$cont";
            $persen_id = "persen_$cont";
            $nilai_id = "nilai_$cont";
            $harga_id = "harga_$cont";
            $harga_dasar_id = "harga_dasar_$cont";

            // $minim = isset($item->minim) ? $item->minim : 0;
            $d_persen = $persen = isset($item['diskon_persen']) && ($item['diskon_persen'] > 0) ? $item['diskon_persen'] * 1 : $db_persen;
            $harga = isset($item->harga) ? $item->harga * 1 : 0;
            $disabled = $minim == 0 ? "disabled" : "";

            $diskon_loop = $dk->calcDiskon($hrg_jual, array("satu" => $persen), array());
            // arrPrint($diskon_loop);
            $d_nilai = $diskon_loop['nilai'];
            $harga_dasar_af = $diskon_loop['harga_af'];
            $harga_af = $harga_dasar_af * $minim;
            $link_delete = base_url() . "diskon/Setting/do_delete_grosir?id=$id_data&id_row=$cont";

            $str .= "<div class='row col-md-12'>";
            $str .= "<div class='col-xs-2'>$satuan_nama</div>";
            $str .= "<div class='col-xs-1'><div class='input-group marginn'><input type='text' name='minim[]' id='$jml_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$satuan_qty'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$d_persen'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$d_nilai'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$harga_id' class='form-control' value='$harga_af'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input type='text' name='harga[]' id='$harga_dasar_id' class='form-control' readonly value='$harga_dasar_af'></div></div>";
            $str .= "<div class='col-xs-1'><div class='input-group marginn'><button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button></div></div>";
            $str .= "</div>";

            $toko_id = my_toko_id();
            $this->load->model("Mdls/MdlProdukSatuanRelasi");
            $ps = new MdlProdukSatuanRelasi();
            $conditesPs = array(
                "produk_id" => $produk_id,
                "toko_id" => $toko_id,
                "qty" => $minim,
            );
            $this->db->where($conditesPs);
            $temp = $ps->lookUpAll()->row_array();
            // showLast_query("kuning");
            // arrPrint($temp);
            $ps_id = $temp["id"];

            $link_update_satuan = base_url() . "Satuan/doEditRelasi?key=qty&pid=$produk_id&id=$ps_id&value=";
            $str .= "<script>
                        // var jml_data = $jml_grosir;
                        var cont_data = $cont_data;
                        var cont_be = $cont -1;
                        var harga = $hrg_jual;
                        if(harga > 100){
                            $('#jml_'+cont_data).prop('disabled', false);
                        }
                                               
                        /*----membuka field diskon persen dan nilai---*/
                        $('#jml_$cont').keydown(function() {
                            $('#persen_$cont').prop('disabled', false);
                            $('#nilai_$cont').prop('disabled', false);
                            // $('#harga_$cont').prop('disabled', false);
                        });
                        
                        /*-----validasi jml harus lebih besar dg jml sebelumnya----*/
                        $('#jml_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml_be = $('#jml_'+ cont_be).val();
                                var jml_now = $('#jml_$cont').val();
    
                                if(Number(jml_now) <= Number(jml_be)){
                                    
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                    });
                                    
                                    $('#persen_$cont').prop('disabled', true);
                                    $('#nilai_$cont').prop('disabled', true);  
                                    $('#jml_$cont').css('color','red');
                                            
                                }
                                else {
                                    $('#jml_$cont').css('color','');
                                    $('#jml_' + cont_af).prop('disabled', true);  
                                }
                            }, 3000);     
                        });
                        
                        /*-----validasi diskon nilai harus lebih besar dg yang sebelumnya----*/
                        $('#nilai_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var nilai_be = $('#nilai_'+ cont_be).val();
                                var nilai_now = $('#nilai_$cont').val();
                                console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                                if(Number(nilai_now) <= Number(nilai_be)){
                                    console.log('ahah');
                                    
                                            swal({
                                                title: 'Upsss.. !!',
                                                html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                            });
                                            
                                            $('#btn_simpan').prop('disabled', true);         
                                            $('#persen_$cont').css('color','red');
                                            $('#nilai_$cont').css('color','red');
                                            $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                            $('#persen_$cont').css('color','');
                                            $('#nilai_$cont').css('color','');
                                }
                            }, 3000);
                        });
                        
                                                    
                        
                         // mengisi diskon persen bila kolom nilai diskon yg diisi
                         $('#nilai_$cont').blur(function() {
                            var harga = $hrg_jual;
                            var minim = $('#jml_$cont').val();
                            var nilai_diskon = $('#nilai_$cont').val();
                            var persen_diskon = (nilai_diskon / harga) * 100;
                            var harga_baru = harga - nilai_diskon;
                            
                            $('#persen_$cont').val(persen_diskon);
                            $('#harga_dasar_$cont').val(harga_baru);
                            
                            var cont_af = $cont + 1; 
                            $('#jml_'+cont_af).prop('disabled', false);
                            $('#btn_simpan').prop('disabled', false);
                         });
                         
                         $('#jml_$cont').blur(function() {       
                            var minim = $('#jml_$cont').val();
                                  
                            $('#anu').load('$link_update_satuan'+minim+'&tokoID=$toko_id');
                         });
                         
                         /*------------ngisi harga bulk satuan---------------*/
                         $('#$harga_id').blur(function() {
                             var harga = $hrg_jual;
                             var harga_satuan = $('#$harga_id').val();
                             var jml = $('#jml_$cont').val();
                             var harga_baru = harga_satuan / jml;
                             var nilai_diskon = harga - harga_baru;
                             var persen_diskon = (nilai_diskon / harga) * 100;
                             
                             $('#harga_dasar_$cont').val(harga_baru);  
                             $('#nilai_$cont').val(nilai_diskon);  
                             $('#persen_$cont').val(persen_diskon);  
                             $('#btn_simpan').prop('disabled', false);
                         });
                    </script>";
            $str .= "<script>
                        /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/
                        $('#persen_$cont').blur(function() {
                            setTimeout(function(){
                                var harga = $hrg_jual;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var persen_bef = $('#persen_'+ cont_be);
                                if (persen_bef.length > 0) {
                                    var persen_be = persen_bef.val();
                                    console.log(persen_be);
                                } else {
                                    var persen_be = '0';
                                    console.log(persen_be);
                                }
                                
                                var persen_now = $('#persen_$cont').val();
                                // var harga_baru = harga - nilai_diskon;
                                console.log(persen_now +' *<=* '+ persen_be + ' *** ' + cont_be);
                                
                                if(Number(persen_now) <= Number(persen_be)){
                                    // console.log('ahah');                                    
                                        swal({
                                            title: 'Upsss.. !!',
                                            html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                        });
                                        
                                        $('#btn_simpan').prop('disabled', true);
                                        $('#persen_$cont').css('color','red');
                                        $('#nilai_$cont').css('color','red');
                                        $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else if (Number(persen_now) >= 100){
                                    console.log('hhhhhh');
                                    swal({
                                            title: 'Upsss.. !!',
                                            html: 'diskon harus < 100%, ' + persen_now
                                        });
                                    
                                    $('#btn_simpan').prop('disabled', true);
                                    $('#persen_$cont').css('color','red');
                                    $('#nilai_$cont').css('color','red');
                                    $('#harga_$cont').css('color','red');
                                    $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                        $('#persen_$cont').css('color','');
                                        $('#nilai_$cont').css('color','');
                                }
                            }, 2500);
                        });
                        
                        // mensisi diskon nilai bila kolom persen diskon yg diisi
                        $('#persen_$cont').blur(function() {
                            var harga_beli = $harga_beli;
                            var harga = $hrg_jual;
                            var minim = $('#jml_$cont').val();
                            var persen_diskon = $('#persen_$cont').val();
                            var nilai_diskon = harga * (persen_diskon / 100);
                            var harga_baru = harga - nilai_diskon;
                            var harga_bulk_baru = harga_baru * minim;
                                                    
                            $('#nilai_$cont').val(nilai_diskon);
                            $('#harga_$cont').val(harga_bulk_baru);       
                            $('#harga_dasar_$cont').val(harga_baru);       
                            
                            // console.log('harga_beli:', harga_beli);
                            // console.log('harga:', harga);
                            // console.log('harga_baru:', harga_baru);
                            
                            var cont_af = $cont + 1; 
                            if(harga_baru < harga_beli){
                                swal({
                                    title: 'Upsss.. !!',
                                    html: 'harga jual akan dibawah harga beli ' + harga_beli
                                });
                                
                                $('#btn_simpan').prop('disabled', true);
                                $('#harga_dasar_$cont').css('color','red');
                            }
                            else {
                                
                                $('#jml_'+cont_af).prop('disabled', false);
                                $('#btn_simpan').prop('disabled', false);
                            }
                        });
                    </script>";
            // $str .= "<div class='col-xs-12 border-cek'>----</div>";
        }
        $str_hidden = "<input type='hidden' name='produk_id' value='$prod_id'>";
        $str .= "<div class='col-xs-12' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
        $str .= "</form>";

        $form = "";
        $form .= "<div class='overflow-h'>";
        $form .= "<div class='row'>";
        // $form .= "<div class='border-cek col-xs-8 overflow-h'>";
        $form .= $str;
        // $form .= "</div>";
        $form .= "</div>";
        $form .= "</div>";

        echo $form;
    }

    public function do_save_grosir()
    {
        $ppn_persen = my_ppn_factor();
        $this->load->helper("he_Angka");
        arrPrint($_POST);
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();

        $minims = $_POST['minim'];
        // $maxims = $_POST['maxim'];
        $persens = unFormatAngka($_POST['persen']);
        $nilais = unFormatAngka($_POST['nilai']);
        $hargas = unFormatAngka($_POST['harga']);
        $produk_id = $_POST['produk_id'];
        $toko_id = my_toko_id();

        $this->db->trans_start();
        $urutan = 0;
        foreach ($persens as $ix => $persen) {
            $urutan++;
            cekBiru($persen);
            $ix_af = $ix + 1;
            $jml_maxim = isset($minims[$ix_af]) && ($minims[$ix_af] > 0) ? ($minims[$ix_af] - 1) : 0;
            $data_barus = array(
                "minim" => $minims[$ix],
                "maxim" => $jml_maxim,
                "persen" => $persen,
                // "nilai" => $nilais[$ix],
                "nilai" => $nilais[$ix] / ((100 + $ppn_persen) / 100),
                // "harga" => $hargas[$ix],
                "harga" => $hargas[$ix] / ((100 + $ppn_persen) / 100),
                "produk_id" => $produk_id,
                "cabang_id" => my_cabang_id(),
                "toko_id" => $toko_id,
                "urutan" => $urutan,
                "author" => my_id(),
                "status" => 1,
            );

            arrPrintHijau($data_barus);
            $dg->saveProdukGrosir($data_barus);
            showLast_query("kuning");
            // break;

            /*update ke relasi satuan*/
            // $this->load->model("Mdls/MdlProdukSatuanRelasi");
            // echo "<div id='update_satuan'></div>";
            // $link_update_satuan = base_url() . "Satuan/doEditRelasi?key=qty&pid=1&id=1&value=".$minims[$ix]."&tokoID=$toko_id";
            // echo "<script>$('#update_satuan').load('$link_update_satuan');</script>";
        }


        // matiHere(__LINE__ . " belum commit");
        $this->db->trans_complete();

        echo lgShowSuccess("Sukses", "Harga grosir berhasil disimpan");
    }

    public function do_delete_grosir()
    {
        arrPrint($_GET);
        $id = $_GET['id'];
        $id_row = $_GET['id_row'];
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();

        $this->db->trans_start();

        $dg->deleteProdukGrosir($id);
        showLast_query("merah");

        $this->db->trans_complete();

        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
        $id_row_ = $id_row + 1;
        echo "<script>

                top.$('#persen_$id_row').val(0);
                top.$('#nilai_$id_row').val(0);
                top.$('#jml_$id_row').val(0);
                
                top.$('#persen_$id_row').prop('disabled', true);
                top.$('#nilai_$id_row').prop('disabled', true);
                top.$('#jml_$id_row_').prop('disabled', true);
            </script>";
    }

    public function do_save_member()
    {
        // arrPrintWebs($_REQUEST);
        // $level_id = $_GET['id'];
        // $persen = $_GET['persen'];
        // $nilai = $_GET['nilai'];
        // $harga = $_GET['harga'];

        // $levels = $_POST[]
        // $data_post = $_POST;

        $this->load->library("Diskon");
        $dk = new Diskon();
        $my_controler = $_POST['my_controler'];
        $my_div = $_POST['my_div'];
        /* -------------------------------------------------------------
         * bila kolom data yg akan disimpan daftarkan dalam array ini ya
         * -------------------------------------------------------------*/
        $data_koloms = array(
            "tanggal_start",
            "tanggal_stop",
            "jenis",
            "tipe",
            "minim",
            "minim_be",
            "periode",
            "quota_global",
            // "customer_level",
        );
        $dk->setTokoId(my_toko_id());
        $src_cls = $dk->callCustomerLevelDiskon();
        $data_posts = array();
        foreach ($src_cls['customer_level'] as $src_cl) {
            $level_id = $src_cl->id;
            $level_nama = $src_cl->nama;

            // cekBiru($level_id);
            $persen = isset($_POST['level_' . $level_id]) ? $_POST['level_' . $level_id] : 0;
            if ($persen > 0) {
                foreach ($data_koloms as $data_kolom) {
                    $data_post[$data_kolom] = $_POST[$data_kolom];
                }

                $data_post['customer_level'] = $level_id;
                $data_post['persen'] = $persen;

                $data_posts[$level_id] = $data_post;
            }
        }
        // arrPrint($data_posts);
        // cekHijau("------------------------masukin data-------------------------");
        $this->db->trans_start();
        /*----------insert-----*/
        $dk->setTokoId(my_toko_id());
        foreach ($data_posts as $clevel_id => $data_post) {
            cekMerah("$clevel_id");
            arrPrintPink($data_post);
            echo "--------------------------------------- " . __METHOD__;
            $dk->setCustomerLevelCondite(array("minim" => $_POST['minim']));
            $xx = $dk->doSaveCustomerLevelDiskon($clevel_id, $data_post);

            // break;
        }

        // matiHere("belum commit @" . __LINE__);
        $this->db->trans_complete();
        $link_member = base_url() . "diskon/Setting/$my_controler";
        echo "<script>
                top.$('#$my_div').load('$link_member');
            </script>";
    }

    public function viewProdukHarga_1()
    {
        $this->load->library("Diskon");
        $dk = new Diskon();


        /*-----------grosir-----------------*/
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        $dg->setTokoId(my_toko_id());
        $src_dg_obj = $dg->callProdukGrosir("");
        // showLast_query("kuning");
        // arrPrint($src_dg_obj);
        foreach ($src_dg_obj as $item) {
            $dg++;
            if (!isset($pr_grosir_aktive[$item->produk_id])) {
                $pr_grosir_aktive[$item->produk_id] = 0;
            }
            $pr_grosir_aktive[$item->produk_id] += 1;
        }
        // arrPrintHijau($pr_grosir_aktive);
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();
        // arrPrint($prod_hargas);
        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                // $produk_id = $param_prod_harga->produk_id;
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }
        // arrPrintKuning($prod_hrg_speks);


        // tool unutk ngupdate harga list dari harga jual pada price
        foreach ($prod_hrg_speks as $pid => $param_item) {

            $harga_jual = $param_item["jual"]->nilai;
            foreach ($param_item as $jvalue => $item_00) {
                $dbid = $item_00->id;
                $dbnilai = $item_00->nilai;

                if ($jvalue == "harga_list") {
                    // cekBiru("update $dbnilai | $pid >> $harga_jual");
                    $dtUpds = array("nilai" => $harga_jual);
                    $kondisi = array("id" => $dbid);
                    // $hp->updateData($kondisi, $dtUpds);
                    // showLast_query("merah");
                }
            }
        }
        // tool

        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        // $src_pr = $pr->lookupAll()->result();
        // $this->db->limit(5);
        $src_pr_obj = $pr->callSpecs();
        // arrPrintKuning($src_pr_obj);
        foreach ($src_pr_obj as $prod_id => $item) {
            $diskon_persen = $item->diskon_persen * 1;
            $nama = $item->nama;
            $premi_jual = $item->premi_jual;
            $biaya_jual = $item->biaya_jual;
            $premi_beli = $item->premi_beli;
            $biaya_beli = $item->biaya_beli;
            $diskon_beli = $item->diskon_beli;

            //            cekHere($diskon_beli);

            $harga_speks = array();
            if (isset($prod_hargas[$prod_id])) {
                foreach ($prod_hargas[$prod_id] as $spek_harga) {
                    $harga_speks[$spek_harga->jenis_value] = $spek_harga;
                }
            }
            $hrg_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;
            // $hrg_jual = isset($harga_speks["jual"]) ? $harga_speks["jual"]->nilai * 1 : 0;
            $hrg_list = isset($harga_speks["harga_list"]) ? $harga_speks["harga_list"]->nilai * 1 : 0;

            $diskon_enol = $dk->calcDiskon($hrg_list, array("satu" => $diskon_persen), array(), "diskon", $biaya_jual);
            $nDiskonJual = $diskon_enol['nilai'];
            $diskon_satu = $dk->calcDiskon($hrg_list, array("satu" => $premi_jual), array(), "premi", $biaya_jual);
            $nPremiJual = $diskon_satu['nilai'];
            // cekBiru($diskon_satu);

            $diskon_nilai = $diskon_satu['nilai'];
            $hrg_jual = $hrg_list - $nDiskonJual + $nPremiJual;

            $hrg_margin = $hrg_jual > 0 ? (($hrg_jual - $hrg_beli) / $hrg_jual) * 100 : 0;
            $jml_grosir = isset($pr_grosir_aktive[$prod_id]) ? $pr_grosir_aktive[$prod_id] : 0;
            $grosir_cek = $jml_grosir > 0 ? "<i class='fa fa-check text-green'> $jml_grosir</i>" : "";

            $link_update_hrg_list = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=harga_list&nilai=";
            $link_update_premi_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_jual&nilai=";
            $link_update_biaya_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_jual&nilai=";
            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_persen&nilai=";

            $url_grosir = base_url() . "diskon/setting/viewGrosir?id=$prod_id";
            $link_grosir = modalDialogBtn("grosir $nama", $url_grosir);
            $url_satuan = base_url() . "diskon/Setting/viewSatuan?id=$prod_id";
            $link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);
            $url_scheduler = base_url() . "diskon/Setting/viewScheduler?id=$prod_id";
            $link_scheduler = modalDialogBtn("Scheduler diskon $nama", $url_scheduler);
            $item_array = (array)$item;

            $item_array["harga_jual"] = $hrg_list;
            $item_array["harga_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_list'>";
            $item_array["premi_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_premi_jual'+this.value);\" value='$premi_jual'>";
            $item_array["biaya_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_jual'+this.value);\" value='$biaya_jual'>";
            $item_array["diskon_persen"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);\" value='$diskon_persen'>";

            //            $item_array["harga_jual"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-hrg_list'        type='number' max='100' min='0' step='1' value='$hrg_list'>";
            //            $item_array["harga_jual"]    = $hrg_list;
            //            $item_array["premi_jual"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-premi_jual'      type='number' max='100' min='0' step='1' value='$premi_jual'>";
            //            $item_array["premi_jual"]    = $premi_jual;
            //            $item_array["biaya_jual"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-biaya_jual'      type='number' max='100' min='0' step='1' value='$biaya_jual'>";
            //            $item_array["biaya_jual"]    = $biaya_jual;
            //            $item_array["diskon_persen"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-diskon_persen'   type='number' max='100' min='0' step='1' value='$diskon_persen'>";
            //            $item_array["diskon_persen"] = $diskon_persen;


            // $btn_grosir  = "<button type='button' class='btn btn-danger' onclick=\"$link_satuan\">satuan</button>";
            // $btn_grosir .= "<button type='button' class='btn btn-warning' onclick=\"$link_grosir\">grosir</button> $grosir_cek";
            // $btn_grosir .= "<button type='button' class='btn btn-info' onclick=\"$link_scheduler\">scheduler</button>";

            $btn_grosir = "";
            $btn_grosir .= "<div class='btn-group'>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sat_$prod_id' class='btn-satuan btn btn-xs btn-danger tombol-action btn-satuan'>satuan</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir$grosir_cek</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sch_$prod_id' class='btn-scheduler btn btn-xs btn-info tombol-action btn-scheduler'>scheduler</button>";
            $btn_grosir .= "</div>";

            $item_array["grosir"] = "$btn_grosir";
            $item_array["harga_aft"] = $hrg_jual;
            /*--------PEMBELIAN----------*/
            $item_array["margin"] = $hrg_margin;
            $diskon_dua = $dk->calcDiskon($hrg_list, array("dua" => $diskon_beli), array(), "diskon", $biaya_beli);
            $nDiskonBeli = $diskon_dua['nilai'];
            $diskon_tiga = $dk->calcDiskon($hrg_list, array("dua" => $premi_beli), array(), "premi", $biaya_beli);
            $nPremiBeli = $diskon_tiga['nilai'];
            $hrg_beli = $prod_hrg_speks[$prod_id]["hpp"]->nilai;
            // $harga_beli = $hrg_list - $nDiskonBeli + $nPremiBeli;
            $item_array["harga_beli"] = $hrg_beli;

            $link_update_biaya_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_beli&nilai=";
            $link_update_diskon_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_beli&nilai=";
            $link_update_premi_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_beli&nilai=";

            $item_array["biaya_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_beli'+this.value);\" value='$biaya_beli'>";
            $item_array["diskon_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_diskon_beli'+this.value);\" value='$diskon_beli'>";
            $item_array["premi_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_premi_beli'+this.value);\" value='$premi_beli'>";

            //            $item_array["harga_beli"] = $harga_beli;
            //            $item_array["biaya_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-biaya_beli'  type='number' max='100' min='0' step='1' value='$biaya_beli'>";
            //            $item_array["biaya_beli"]   = $biaya_beli;
            //            $item_array["diskon_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-diskon_beli' type='number' max='100' min='0' step='1' value='$diskon_beli'>";
            //            $item_array["diskon_beli"]  = $diskon_beli;
            //            $item_array["premi_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-premi_beli'  type='number' max='100' min='0' step='1' value='$premi_beli'>";
            //            $item_array["premi_beli"]   = $premi_beli;

            $src_pr[$prod_id] = $item_array;
        }

        // arrPrintHijau($src_pr);
        /* ---------------------
         * dta produk per supplier
         * ---------------------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();

        if (isset($_GET['suppliers_id'])) {
            $condites = array(
                "suppliers_id" => $_GET['suppliers_id'],
            );
            $this->db->where($condites);
        }
        $src_pps_0 = $pps->lookupAll()->result();
        // showLast_query("kuning");
        // arrPrint($src_pps_0);

        foreach ($src_pps_0 as $src_pp) {
            $suppliers_id = $src_pp->suppliers_id;
            $produk_id = $src_pp->produk_id;

            $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
            // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
            $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
        }
        //         arrPrintHijau($src_pr);

        // $allowTmpSave = isset($this->configUi[$jenisTr]['allowTmpSave']) ? $this->configUi[$jenisTr]['allowTmpSave'] : false;
        $arrHeaders = array(
            "id" => array(
                "label" => "pid",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "barcode" => array(
                "label" => "barcode",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "nama" => array(
                "label" => "nama produk",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            // "harga_jual"    => array(
            //     "label"       => "harga list",
            //     "attr"        => "class='text-right'",
            //     "span_header" => true,
            // ),
            // "diskon_beli"   => array(
            //     "label"  => "diskon pembelian",
            //     "attr"   => "class='text-right bg-warning'",
            //     "format" => "formatField_he_format",
            // ),
            // "premi_beli"    => array(
            //     "label"  => "premi pembelian",
            //     "attr"   => "class='text-right bg-warning'",
            //     "format" => "formatField_he_format",
            // ),
            // "biaya_beli"    => array(
            //     "label"  => "biaya pembelian",
            //     "attr"   => "class='text-right bg-warning'",
            //     "format" => "formatField_he_format",
            // ),
            "harga_beli" => array(
                "label" => "hpp",
                "attr" => "class='text-right bg-warning'",
            ),
            // "margin"        => array(
            //     "label"  => "margin (%)",
            //     "attr"   => "class='text-right'",
            //     "format" => "formatField_he_format",
            // ),
            /*--penjualan--*/
            "diskon_persen" => array(
                "label" => "diskon penjualan",
                "attr" => "class='text-right bg-danger'",
                "parent" => "",
            ),
            "premi_jual" => array(
                "label" => "premi penjualan",
                "attr" => "class='text-right bg-danger'",
            ),
            "biaya_jual" => array(
                "label" => "biaya penjualan",
                "attr" => "class='text-right bg-danger'",
            ),
            "harga_aft" => array(
                "label" => "harga jual",
                "attr" => "class='text-right bg-danger'",
            ),
            "grosir" => array(
                "label" => "harga jual grosir",
                "attr" => "class='text-left bg-danger'",
            ),
        );


        $data = array(
            "mode" => "viewProdukHarga",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting Diskon",
            "subTitle" => "-",
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            // "level_header"   => $level_header,
            // "level_data"     => $src_clevel_diskons,
            // "level_data"     => array(),
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);

    }

    public function viewProdukKategori()
    {
        // cekHijau(url_segment());
        // cekKuning($_GET);
        // cekHere(__LINE__);
        // matiHere(__LINE__);

        $this->load->model("Mdls/MdlProdukKategori");
        $hp = new MdlProdukKategori();
        // $hp->setTokoId(my_toko_id());
        // $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->lookupAll()->result();
        $dt_srcs = $prod_hargas;
        // showLast_query("merah");
        // -------------------------------
        // matiHere(__LINE__);
        // $md_nama = $srcs->nama;

        // $tabData = array(
        //     "MdlMerek"        => "MdlProduk",
        //     "MdlRakCabang"    => "MdlProduk",
        //     "MdlFolderProduk" => "MdlProduk",
        //     "MdlSupplier"     => "MdlProdukPerSupplier",
        // );
        // /* ----------------------------------------------------------
        //  * mendapatkan jml data per kategori(subjec)
        //  * ----------------------------------------------------------*/
        // $jml_isi_data = array();
        // // if (isset($tabData['model_data'])) {
        // //
        // $mdl_data = isset($tabData[$mdl]) ? $tabData[$mdl] : "";
        $this->load->model("Mdls/MdlDiskonCustomer");
        $dcu = new MdlDiskonCustomer();
        // // $this->db->limit(100);
        // $this->db->where($kolom, $tabId);
        $dcu_srcs = $dcu->callDiskonAktive();
        // showLast_query("orange");
        // cekHijau(count($dcu_srcs));
        // arrPrint($dcu_srcs);
        $dcu_count = 0;
        foreach ($dcu_srcs as $dcu_jenis => $dcu_src_2) {
            foreach ($dcu_src_2 as $dcu_urutan => $dcu_src) {
                $dcu_count++;

                $dcu_nilai[$dcu_jenis][$dcu_urutan] = $dcu_src['nilai'] * 1;
                $dcu_minim[$dcu_jenis][$dcu_urutan] = $dcu_src['minim'];
                $dcu_maxim[$dcu_jenis][$dcu_urutan] = $dcu_src['maxim'];
                $dcu_id[$dcu_jenis][$dcu_urutan] = $dcu_src['id'];
            }
        }

        // arrPrintHijau($dcu_minim);

        $jml_diskon = 3;
        /* --------------------------------
         * data builder
         * -----------------------------*/
        $arrDiskons = array(
            // "qty",
            // "persen",
            // "nilai",
            //   "<input class='btn btn-link' value='min. qty'><input class='btn btn-link' value='nilai diskon'>"
            "min. qty | nilai diskon"
        );
        foreach ($dt_srcs as $src) {
            // arrPrintHijau($src);
            $src_array = (array)$src;
            $id = $src->id;
            $kategori_nama = str_replace(" ", "_", $src->nama);

            for ($i = 1; $i <= $jml_diskon; $i++) {
                $diskon_key = "diskon_$i";
                $dk = "dk__$i";
                $id_ky = $id . "_$i";
                // $diskon_1['qty'] = "<input type='number' name='qty' placeholder='qty_$i'>";
                // $diskon_1['persen'] = "<input type='number' name='diskon' placeholder='persen_$i'>";
                // $diskon_1['nilai'] = "<input type='number' name='persen' placeholder='nilai_$i'>";
                $dcu_dtid = isset($dcu_id[$kategori_nama][$i]) ? $dcu_id[$kategori_nama][$i] : "";
                $dcu_min = isset($dcu_minim[$kategori_nama][$i]) ? $dcu_minim[$kategori_nama][$i] : "";
                // arrPrintPink($dcu_min);

                $stat_qty = "";
                $stat_nilai = "";
                if ($dcu_dtid == 0) {

                    $stat_qty = "readonly";
                    $stat_nilai = "disabled";
                }

                $diskon_1 = "<input $stat_qty type='number' id='qty_$id_ky' data-kategori='$id' data-jenis='$kategori_nama' data-dcuid='$dcu_dtid' name='minim' placeholder='qty_$i' value='$dcu_min' onblur=\"dk_qty(this.id, this.value);\" ondblclick=\"enableInput(this.id);\">";
                // $diskon_1 .= "<input type='number' name='diskon' placeholder='persen_$i'>";
                $dcu_value = isset($dcu_nilai[$kategori_nama][$i]) ? $dcu_nilai[$kategori_nama][$i] : "";
                $diskon_1 .= "<input $stat_nilai type='number' id='nilai_$id_ky' data-kategori='$id' data-jenis='$kategori_nama' data-dcuid='$dcu_dtid'  name='nilai' placeholder='nilai_$i' value='$dcu_value' onblur=\"dk_qty(this.id, this.value);\" ondblclick=\"enableInput(this.id);\">";
                $add_array[$diskon_key] = $diskon_1;
            }

            $src2 = $src_array + $add_array;
            $dt2_src[$id] = (object)$src2;
        }

        /* ----------------------------------------
         * header data
         * ----------------------------------------*/
        $headers_1 = array(
            // "id"   => array(
            //     "label" => "pid",
            // ),
            // "barcode" => array(
            //     "label" => "barcode",
            // ),
            "nama" => array(
                "label" => "nama",
            ),
        );

        for ($i = 1; $i <= $jml_diskon; $i++) {
            $head_anggota = array(
                "label" => "diskon #" . $i,
            );
            $headers_2["diskon_$i"] = $head_anggota;
        }

        $headers = $headers_1 + $headers_2;
        /* ----------------------------------------------
         * header
         * ----------------------------------------------*/
        $head_colspan = count($arrDiskons);
        $heads = "<tr style='text-transform: uppercase;background-color: #9d9d9d;'>";
        $heads .= "<th rowspan='2'>no</th>";
        foreach ($headers_1 as $header) {
            $label = isset($header['label']) ? $header['label'] : $header;

            $heads .= "<th rowspan='2'>$label</th>";
        }
        foreach ($headers_2 as $header) {
            $label = isset($header['label']) ? $header['label'] : $header;

            $heads .= "<th colspan='$head_colspan'>$label</th>";
        }
        $heads .= "</tr>";

        $heads .= "<tr style='text-transform: uppercase;background-color: #9d9d9d;'>";
        foreach ($headers_2 as $header) {
            foreach ($arrDiskons as $arrDiskon) {
                $heads .= "<th>$arrDiskon</th>";
            }
        }
        $heads .= "</tr>";

        /* ----------------------------------------------
         * body
         * ----------------------------------------------*/
        $bodies = "";
        $no = 0;
        // arrPrintHijau($headers);
        // arrPrint($dt2_src);
        foreach ($dt2_src as $src) {
            // arrPrint($src);
            $no++;

            $bodies .= "<tr>";
            $bodies .= "<td>$no</td>";
            foreach ($headers as $kolom => $header) {
                $label = isset($header['label']) ? $header['label'] : $header;
                $nilai = isset($src->$kolom) ? $src->$kolom : "-";

                $bodies .= "<td>$nilai</td>";
            }

            $bodies .= "</tr>";
        }

        /* ----------------------------------------------
         * penampil data
         * ----------------------------------------------*/
        $this_halaman = MODUL_PATH . "Setting/viewProdukKategori";
        $view = "<style type='text/css'>
                th, td {
                  // text-align: left;
                  padding: 2px;
                }
                tr:nth-child(even) {
                  background-color: #f2f2f2;
                }
                .judul{
                    text-transform: uppercase;
                    margin-bottom: 2px;
                }
            </style>";
        // $view .= "<h2 class='judul'><small style='color: #9d9d9d;'>$tab</small><br>$md_nama</h2>";
        if (ipadd() == "192.168.5.7" || ipadd() == "202.65.117.72") {
            $view .= "<button type='button' onclick=\"$('#kategori').load('$this_halaman');\">f5</button>";
        }
        $view .= "<table>";
        $view .= $heads;
        $view .= $bodies;
        $view .= "</table>";
        $view .= "<div id='tmp_box'></div>";
        $do_save_link = MODUL_PATH . "Setting/doSaveDiskonKategori";
        $view .= "<script>
            function dk_qty(id, x) {
                var do_save_link = '$do_save_link'; 
              // var dataKategoriValue = $('#' + elementId).data('kategori');
              // var targetClass = $(this).data('kategori');
              let dataraw = $('#'+id);
              var dcuId = dataraw.data('dcuid');
              var kategoriId = dataraw.data('kategori');
              var jenis = dataraw.data('jenis');
              var nama = dataraw.attr('name');
              var defVal = document.getElementById(id).defaultValue;
              
              
                console.log('defVal : ', defVal);
                console.log('jenis : ', jenis);
                console.log('kategoriId : ', kategoriId);
                console.log('nama : ', nama);
                console.log('id: ', id);
                console.log('x: ', x);
                console.log('do_save : ', do_save_link);
                if(defVal != x){                    
                    $('#tmp_box').load(do_save_link + '?input_id=' + id + '&nilai=' + x + '&kolom=' + nama + '&kat_id=' + kategoriId + '&jenis=' + jenis + '&dcuid=' + dcuId);
                }
                
            }
            
            function enableInput(id) {                                
                console.log('inputElement::', id)
    
                $('#' + id).prop('readonly', false);
            }
        </script>";
        $view .= "<script>
            // function enableInput(id) {                                
            //     console.log('inputElement::', id)
            //
            //     $('#' + id).prop('disabled', false);
            // }
        </script>";

        echo $view;

        // $data = array(
        //     "mode"             => "viewProdukHarga",
        //     "errMsg"           => $this->session->errMsg,
        //     "globalTemplate"   => isset($globalTemplate) ? $globalTemplate : "",
        //     "title"            => "Setting Diskon",
        //     "subTitle"         => "-",
        //     "arrHeaderParents" => $arrHeaderParents,
        //     "arrHeaders"       => $arrHeaders,
        //     "master_data"      => isset($src_pr) ? $src_pr : array(),
        //     "is_po"            => $is_po,
        //     "cCode"            => isset($cCode) ? $cCode : "",
        //     "urlBack"          => isset($urlBack) ? $urlBack : "",
        //     "pph23"            => $this->pph23,
        //     "ppn"            => my_ppn_factor(),
        //
        //     // "grosir_header"        => $grosir_header,
        //     // "grosir_data"          => $src_dg,
        //     // "level_header"         => $level_header,
        //     // "level_data"           => $src_clevel_diskons,
        //     // "level_data"           => array(),
        //     // "jenisTransaksi"       => $jenisTr,
        //     // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        //     // "template"             => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
        //     // "isMobile"             => $isMob,
        // );
        // //arrPrint($data);
        // $this->load->view("setting", $data);
    }

    public function doSaveDiskonKategori()
    {
        arrPrintHijau($_GET);
        cekHere(__METHOD__);
        $get_data = $_GET;
        $kolom = $_GET['kolom'];
        $new_nilai = $_GET['nilai'];
        $jenis_id = $_GET['kat_id'];
        $db_id = isset($get_data['dcuid']) ? $get_data['dcuid'] : 0;
        $jenis = $_GET['jenis'];
        $tipe = "diskon_kategori";
        $this->load->model("Mdls/MdlDiskonCustomer");
        $dcu = new MdlDiskonCustomer();

        $this->db->trans_start();

        /*---trashing data lama-----------*/
        if ($db_id > 0) {
            $condite_upd = array(
                "id" => $db_id,
            );
            $data_upd = array(
                "trash" => 1,
            );
            $dcu->updateData($condite_upd, $data_upd);
            showLast_query("kuning");


            /*-----melihat data lama------------*/
            $old_condites = array(
                "id" => $db_id,
            );
            $old_srcs = $dcu->lookupByCondition($old_condites)->row();
            $old_urutan = isset($old_srcs->urutan) ? $old_srcs->urutan : 0;
            $old_nilai = isset($old_srcs->nilai) ? $old_srcs->nilai : 0;
            $old_minim = isset($old_srcs->minim) ? $old_srcs->minim : 0;
            $old_maxim = isset($old_srcs->maxim) ? $old_srcs->maxim : 0;
            showLast_query("orange");
            arrPrintKuning($old_srcs);
        }
        else {
            cekOrange("tidak ada data lama");
        }
        /*----melihat yg sudah ada ada untuk tipe dan jenis ini----------*/
        $condites = array(
            "trash" => "0",
            "tipe" => $tipe,
            "jenis" => $jenis,
        );
        $this->db->order_by("urutan desc");
        $srcs = $dcu->lookupByCondition($condites)->result();
        showLast_query("hijau");
        arrPrint($srcs);
        $jml_raw = count($srcs);
        $urutan_last = $srcs[0]->urutan;
        $id_last = $srcs[0]->id;

        cekBiru("urutan_last:: $urutan_last");

        /*----creat new data----------*/
        cekHitam("$new_nilai");
        if ($new_nilai > 0) {
            $new_datas = array();
            switch ($kolom) {
                case "minim":
                    $new_datas = array(
                        "nilai" => $old_nilai,
                        "minim" => $new_nilai,
                        "maxim" => $old_maxim,
                    );

                    break;
                case "nilai":
                    $new_datas = array(
                        "nilai" => $new_nilai,
                        "minim" => $old_minim,
                        "maxim" => $old_maxim,
                    );
                    break;
            }

            if (($old_urutan == 0) && ($urutan_last == 0)) {
                $new_urutan = 1;
            }
            elseif (($old_urutan == 0) && ($urutan_last > 0)) {
                $new_urutan = $urutan_last + 1;
            }
            elseif (($old_urutan > 0)) {
                $new_urutan = $old_urutan;
            }
            else {
                matiHere("undefine urutan @" . __LINE__);
            }
            $op_datas = array(
                    "tipe" => $tipe,
                    "jenis" => $jenis,
                    "urutan" => $new_urutan,
                    // "nilai" => $nilai,
                    // "minim" => $minim,
                    // "maxim" => $maxim,
                ) + $new_datas;
            $op_datas_clean = array_filter($op_datas);
            $dcu->addData($op_datas_clean);
            showLast_query("merah");
            arrPrintHijau($op_datas_clean);

            if ($kolom == "minim") {
                /*-----update maxim sebelumnya---*/
                cekHere("id_last: $id_last");
                $condite_lasr = array(
                    "id" => $id_last
                );
                $data_last = array(
                    "maxim" => ($new_nilai - 1)
                );
                $dcu->updateData($condite_lasr, $data_last);
                showLast_query("kuning");
            }
        }


        $this->db->trans_complete();

        echo lgShowSuccess("Berhasil", "perubahan data berhasil disimpan");
        $this_halaman = MODUL_PATH . "Setting/viewProdukKategori";

        if ($new_nilai == 0) {
            echo "<script>
                $('#kategori').load('$this_halaman');
            </script>";
        }
    }

    public function viewProdukHarga_ori()
    {
        $is_po = isset($_GET['id_item']) ? 1 : 0;
        if ($is_po == true) {
            $urlBack = $_GET['urlBack'];
            $cCode = $_GET['cCode'];
            //            cekHijau(":: $is_po :: [$cCode] ");
            $this->iterasiGerbangItem($cCode);
        }
        else{
            session_write_close();
        }
        $req_produk_ids = isset($_GET['id_item']) ? blobDecode($_GET['id_item']) : array();
        $harga_per_supplier = false;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();
        // showLast_query("orange");
        // arrPrintHijau($prod_hargas);
        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                // $produk_id = $param_prod_harga->produk_id;
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        // showLast_query("pink", __LINE__);
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;
            $dp_speks['nilai_plus'] = $dp_src->nilai_plus * 1;

            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks;
        }

        /*-------------MdlDiskonPembelianSupplier-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dps = new MdlDiskonPembelianSupplier();
        $dps_srcs = $dps->lookupAll()->result();
        // showLast_query("hijau", __LINE__);
        // arrPrintPink($dps_srcs);
        $dp_speks = array();
        $dps_datas = array();
        foreach ($dps_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_supplier_id = $dp_src->supplier_id;
            $dp_diskon_id = $dp_src->per_supplier_diskon_id;
            // $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_diskon_id;
            $dp_speks['per_supplier_diskon_nama'] = $dp_src->per_supplier_diskon_nama;
            $dp_speks['supplier_id'] = $dp_src->supplier_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;

            $dps_datas[$dp_supplier_id][$dp_diskon_id] = $dp_speks;
        }
        // arrPrintHijau($dps_datas);

        $this->load->library("Diskon");
        $dk = new Diskon();
        /*-----------grosir-----------------*/
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        $dg->setTokoId(my_toko_id());
        $src_dg_obj = $dg->callProdukGrosir("");

        // showLast_query("kuning");
        // cekHere(count($src_dg_obj));
        // arrPrint(array_slice($src_dg_obj,0,1));

        foreach ($src_dg_obj as $item) {
            $dg_produk_id = $item->produk_id;
            $dg_jenis = $item->jenis;
            $dg_minim = $item->minim;
            $dg_nilai = $item->nilai;
            $dg_persen = $item->persen;
            $dg_urutan = $item->urutan;
            $dg++;
            if (!isset($pr_grosir_aktive[$dg_produk_id])) {
                $pr_grosir_aktive[$dg_produk_id] = 0;
            }
            $pr_grosir_aktive[$dg_produk_id] += 1;

            $prod_hrg_jual = isset($prod_hrg_speks[$dg_produk_id]) ? (isset($prod_hrg_speks[$dg_produk_id]["harga_list"]) ? $prod_hrg_speks[$dg_produk_id]["harga_list"]->nilai : 0) : 0;


            $produk_grosir[$dg_produk_id]["minim_$dg_urutan"] = $dg_minim;
            $produk_grosir[$dg_produk_id]["persen_$dg_urutan"] = $dg_persen;
            $data_calc = $dk->calcDiskon($prod_hrg_jual, array($dg_persen), array());
            $dg_nilai_calc = $data_calc['nilai'];
            $produk_grosir[$dg_produk_id]["nilai_$dg_urutan"] = $dg_nilai_calc;
        }
        $sortGrosir = $pr_grosir_aktive;

        // asort($sortGrosir);
        // $maxGrosir = end($sortGrosir);
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($pr_grosir_aktive,0,1,true));
        // arrPrintWebs($produk_grosir);

        // region membaca hpp rata-rata stok yang tersedia
        $this->load->model("Mdls/MdlFifoAverage");
        $ff = New MdlFifoAverage();
        $ff->setFilters(array());
        // sementara ditembak cabang id 100, nanti kalau tambah cabang diganti metode
        // sepakat selalu melihat cb -1 25/5/23
        $ff->addFilter("cabang_id='-1'");
        $arrSelect = array(
            "produk_id",
            "avg(hpp) as hpp",
        );
        $this->db->group_by("produk_id");
        $this->db->select($arrSelect);
        $ffTmp = $ff->lookupAll()->result();
        //        showLast_query("biru");
        //        arrprint(array_slice($ffTmp, 0,1));
        $arrHppAvg = array();
        foreach ($ffTmp as $ffSpec) {
            $arrHppAvg[$ffSpec->produk_id] = (array)$ffSpec;
        }
        // endregion membaca hpp rata-rata stok yang tersedia
        // tool unutk ngupdate harga list dari harga jual pada price
        foreach ($prod_hrg_speks as $pid => $param_item) {

            $harga_jual = isset($param_item["jual"]) ? $param_item["jual"]->nilai : 0;
            foreach ($param_item as $jvalue => $item_00) {
                $dbid = $item_00->id;
                $dbnilai = $item_00->nilai;

                if ($jvalue == "harga_list") {
                    // cekBiru("update $dbnilai | $pid >> $harga_jual");
                    $dtUpds = array("nilai" => $harga_jual);
                    $kondisi = array("id" => $dbid);
                    // $hp->updateData($kondisi, $dtUpds);
                    // showLast_query("merah");
                }
            }
        }
        // tool

        /*-------produk_per_supplier-------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();

        if (isset($_GET['suppliers_id'])) {
            $condites = array(
                "suppliers_id" => $_GET['suppliers_id'],
            );
            // $this->db->where($condites);
        }
        $src_pps_0 = $pps->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintWebs($src_pps_0);
        $produk_suppliers = array();
        foreach ($src_pps_0 as $item) {

            $produk_suppliers[$item->produk_id][] = $item->suppliers_id;
            $produk_supplier[$item->produk_id] = $item->suppliers_id;
        }
        // arrPrintHijau($produk_supplier);
        // arrPrintWebs($produk_suppliers);

        if ($harga_per_supplier == true) {
            /*-------harga_produk_per_supplier-------*/
            $this->load->model("Mdls/MdlHargaProdukPerSupplier");
            $hpps = new MdlHargaProdukPerSupplier();
            $src_hpps_0 = $hpps->lookupAll()->result();
            // showLast_query('kuning');
            // $prod_hargas = array();
            foreach ($src_hpps_0 as $itemHpps) {
                // arrPrintHijau($itemHpps);
                $param_prod_harga = (array)$itemHpps;
                $produk_id = $itemHpps->produk_id;
                $jenis_value = $itemHpps->jenis_value;
                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
                $prod_hargas[$produk_id][] = (object)$param_prod_harga;
            }
        }
        // arrPrintHijau($src_hpps_0);
        // arrPrintKuning($prod_hargas);
        // arrPrint($prod_hrg_speks);

        /* ----------------------------------------------------------
       * freeproduk relasi
       * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $dpps = new MdlDiskonPembelianPairSupplier();
        $src_freeProduks = $dpps->callSpecs();
        // showLast_query("here");
        $dp_freeproduk = array_keys($src_freeProduks);
        // arrPrintKuning($src_freeProduks);
        // arrPrintKuning($dp_freeproduk);
        // foreach ($src_freeProduks as $pd_id => $src_freeProduk) {
        //
        // }

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $srcMereks = $mr->callSpecs();
        // showLast_query("kuning");
        // arrPrintHijau($srcMereks);

        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();

        if (ipadd() == "202.65.117.72") {
            // echo cekAlert("data dilimit karena dalam mode debug dalam network MGK");
            // if (ipadd() == "202.65.117.80") {
            //            $this->db->limit(20);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
            //            $this->db->where("merek_id",array("42"));
        }

        if ($is_po == false) {
            if (isset($_GET['f'])) {
                $filters = array(
                    $_GET['f'] => $_GET['v'],
                );

                if($_GET['v'] === 'null'){}
                else{
                $this->db->where($filters);
                }

            }
            else {
                echo cekAlert("silahkan pilih merek terlebih dahulu");
                $this->db->limit(1);
            }
        }
        // $this->db->limit(50);
        // $this->db->where_in("id", array("1582", "121", "73", "944", "957"));
        // $this->db->where_in("id", array("1582", "3365"));
        // $this->db->where_in("supplier_id",array("1",));
        // $this->db->where_in("supplier_id",array("4",));
        if (count($req_produk_ids) > 0) {
            $this->db->where_in("id", $req_produk_ids);
        }
        $src_pr_obj_00 = $pr->callSpecs();
        // showLast_query("hijau");
        $filter_4 = url_segment(4);

        switch ($filter_4) {
            case "grosir":
                foreach ($pr_grosir_aktive as $item_id => $jml_grosir) {
                    if (isset($src_pr_obj_00[$item_id])) {
                        $src_pr_obj[$item_id] = $src_pr_obj_00[$item_id];
                    }
                }
                break;
            case "non_diskon":
                $src_pr_obj = array_diff_key($src_pr_obj_00, $pr_grosir_aktive);
                break;
            default:
                $src_pr_obj = $src_pr_obj_00;
                break;
        }

        $sortGrosir = array_intersect_key($pr_grosir_aktive, $src_pr_obj);
        asort($sortGrosir);
        $maxGrosir = end($sortGrosir);

        // $maxGrosir = 2;
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($sortGrosir,0,3, true));
        // arrPrintKuning($src_pr_obj);
        // arrPrintKuning(url_segment());
        // cekHere("all>".sizeof($src_pr_obj_00) ." diskon>". sizeof($pr_grosir_aktive) ." yg tampil>". sizeof($src_pr_obj));
        // cekHere(sizeof($src_pr_obj));
        // arrPrint(my_ppn_factor());

        /* ----------------------------------------------------------
         * diambilkandari MdlSupplierDiskon
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlSupplierDiskon");
        $spd = New MdlSupplierDiskon();
        $spd->addFilter("jenis='reguler'");
        $spdTmp = $spd->lookupAll()->result();
        // showLast_query("kuning", __LINE__);
        foreach ($spdTmp as $spdSpec) {
            $kolomDiskonPembeliansId[$spdSpec->nama] = $spdSpec->id;
            $kolomDiskonPembelians[$spdSpec->nama] = $spdSpec->label;
        }

        $kolomKreditnotePembelians = array(
            // "hpp_ppn"       => "hpp + ppn",
            // "diskon_1" => "event billing",
            // "diskon_2" => "otp rebate",
            // "diskon_3" => "monthly rebate",
            // "diskon_4" => "blind bonus",
            // "diskon_5" => "add suport",
            // "pph23"     => "pph23",
        );
        $kolomPembelians = $kolomDiskonPembelians + $kolomKreditnotePembelians;

        // arrPrint($kolomDiskonPembeliansId);
        // arrPrint($kolomPembelians);

        /* -----------------------------------
         * master data builder
         * ----------------------------------*/
        $arrAddData = array();
        $diskonPembelians = array();
        $row_id = 999;
        foreach ($src_pr_obj as $prod_id => $item) {
            $row_id++;
            // arrPrintHijau($item);
            $diskon_persen = $item->diskon_persen * 1;
            $nama = $item->nama;
            $spl_id = $item->supplier_id;
            $premi_jual = isset($item->premi_jual) ? $item->premi_jual : 0;
            $biaya_jual = isset($item->biaya_jual) ? $item->biaya_jual : 0;
            $premi_beli = isset($item->premi_beli) ? $item->premi_beli : 0;
            $biaya_beli = isset($item->biaya_beli) ? $item->biaya_beli : 0;
            $diskon_beli = isset($item->diskon_beli) ? $item->diskon_beli : 0;

            /* -----------------------------------------------
             * update relasi ke-supplier
             * -----------------------------------------------*/
            // $spl_id_new = isset($produk_supplier[$prod_id]) ? $produk_supplier[$prod_id] : "";
            // $upCondites = array(
            //   "id" => $prod_id,
            //   "supplier_id" => null,
            // );
            // $upDatas = array(
            //     "supplier_id" => $spl_id_new,
            // );
            // $pr->updateData($upCondites,$upDatas);
            // showLast_query("biru");

            // /*----delete produkpersupplier*/
            // $upCondites2 = array(
            //     "suppliers_id !=" => $spl_id_new,
            //     "produk_id" => $prod_id,
            // );
            // $upDatas2 = array(
            //     "trash" => 1,
            // );
            // $pps->updateData($upCondites2,$upDatas2);
            // showLast_query("merah");

            $harga_speks = array();
            if (isset($prod_hargas[$prod_id])) {
                foreach ($prod_hargas[$prod_id] as $spek_harga) {
                    $harga_speks[$spek_harga->jenis_value] = $spek_harga;
                }
            }

            $hrg_beli = isset($arrHppAvg[$prod_id]["hpp_nppv"]) ? ($arrHppAvg[$prod_id]["hpp_nppv"] * 1) : 0;
            $hrg_pp = isset($arrHppAvg[$prod_id]["hpp"]) ? ($arrHppAvg[$prod_id]["hpp"] * 1) : 0;
            $hrg_pp_f = format_harga($hrg_pp);

            // $hpp_supplier = isset($harga_speks['hpp']) ? $harga_speks['hpp']->nilai * 1 : 0;
            //            $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            if (isset($_SESSION[$cCode]["items"][$prod_id])) {
                $hpp_supplier = $_SESSION[$cCode]["items"][$prod_id]["hpp"];
            }
            else {
                $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            }
            // $hpp_supplier = $hrg_pp;
            // arrPrintKuning($harga_speks);
            $hrg_jual_online = isset($harga_speks['jual_online']) ? $harga_speks['jual_online']->nilai * 1 : 0;
            $hrg_list_jual = isset($harga_speks['jual']) ? $harga_speks['jual']->nilai * 1 : 0;
            $hrg_list_0 = $hrg_list_reseller = isset($harga_speks['jual_reseller']) ? $harga_speks['jual_reseller']->nilai * 1 : 0;
            $hrg_list = $hrg_list_0 > 0 ? $hrg_list_0 : $hrg_list_jual;
            $diskon_enol = $dk->calcDiskon($hrg_list, array("satu" => $diskon_persen), array(), "diskon", $biaya_jual);
            $nDiskonJual = $diskon_enol['nilai'];
            // ----------------------------------------------
            $diskon_satu = $dk->calcDiskon($hrg_list, array("satu" => $premi_jual), array(), "premi", $biaya_jual);
            $nPremiJual = $diskon_satu['nilai'];
            $diskon_nilai = $diskon_satu['nilai'];
            $hrg_jual = $hrg_list - $nDiskonJual + $nPremiJual;

            $hrg_margin = $hrg_jual > 0 ? (($hrg_jual - $hrg_beli) / $hrg_jual) * 100 : 0;
            $jml_grosir = isset($pr_grosir_aktive[$prod_id]) ? $pr_grosir_aktive[$prod_id] : 0;
            $grosir_cek = $jml_grosir > 0 ? "<i class='fa fa-check text-green'> $jml_grosir</i>" : "";
            $grosir_yes = $jml_grosir > 0 ? "yes" : "no";

            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_pembelian&nilai=";
            $link_update_hrg_jual_online = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online&nilai=";
            $link_update_hrg_jual_online_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online_nppn&kyb=harga_jual_online_nilai&nilai=";
            $link_update_hrg_list_reseller = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller&nilai=";
            $link_update_hrg_list_reseller_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller_nppn&kyb=harga_jual_reseler&nilai=";
            $link_update_hrg_list = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual&nilai=";
            $link_update_hrg_list_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_nppn&kyb=harga_jual&nilai=";
            $link_update_premi_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_jual&nilai=";
            $link_update_biaya_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_jual&nilai=";
            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_persen&nilai=";
            $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier&nilai=";
            $link_update_hrg_beli_supplier_0 = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier_0&nilai=";
            // $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp&nilai=";

            $url_grosir = base_url() . "diskon/setting/viewGrosir?id=$prod_id";
            $link_grosir = modalDialogBtn("grosir $nama", $url_grosir);
            $url_satuan = base_url() . "diskon/Setting/viewSatuan?id=$prod_id";
            $link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);
            $url_scheduler = base_url() . "diskon/Setting/viewScheduler?id=$prod_id";
            $link_scheduler = modalDialogBtn("Scheduler diskon $nama", $url_scheduler);
            $item_array = (array)$item;

            if (($premi_jual * 1) > 0) {
                // if($diskon_persen > 0){
                $disabled_diskon = "disabled";
                $disabled_premi = "";
            }
            elseif ($premi_jual == 0 && $diskon_persen == 0) {
                $disabled_premi = "";
                $disabled_diskon = "";
            }
            else {
                $disabled_premi = "disabled";
                $disabled_diskon = "";
            }

            // $item_array["biaya_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_jual'+this.value);\" value='$biaya_jual'>";
            $item_array["biaya_jual"] = "<input id='biaya_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$biaya_jual' >";
            $item_array["biaya_jual_nilai"] = $biaya_jual;
            // ----------------
            $item_array["harga_jual_online_nilai"] = $hrg_jual_online;
            $item_array["harga_jual_online"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online'+this.value);\" value='$hrg_jual_online'>";
            $harga_jual_online_nppn = ($hrg_jual_online * my_ppn_factor() / 100) + $hrg_jual_online;
            $harga_jual_online_nppn_f = number_format($harga_jual_online_nppn, 0, '.', '');
            $item_array["harga_jual_online_nppn"] = "<input id='harga_jual_online_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online_nppn'+this.value);\" value='$harga_jual_online_nppn_f'>";
            // ----------------------------------------------
            $item_array["harga_list"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_list_jual'>";
            $harga_list_nppn = ($hrg_list_jual * my_ppn_factor() / 100) + $hrg_list_jual;
            $harga_list_nppn_f = number_format($harga_list_nppn, 0, '.', '');
            // cekHere("$harga_list_nppn_f //// $harga_list_nppn");
            $item_array["harga_list_nppn"] = "<input id='harga_list_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_nppn'+this.value);\" value='$harga_list_nppn_f'>";
            $item_array["harga_jual"] = $hrg_list_jual;
            // ---------------------------------------------
            $item_array["harga_list_reseller"] = "<input id='harga_list_reseller_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller'+this.value);\" value='$hrg_list_reseller'>";
            $harga_list_reseller_nppn = ($hrg_list_reseller * my_ppn_factor() / 100) + $hrg_list_reseller;
            $harga_list_reseller_nppn_f = number_format($harga_list_reseller_nppn, 0, '.', '');
            $item_array["harga_list_reseller_nppn"] = "<input id='harga_list_reseller_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller_nppn'+this.value);\" value='$harga_list_reseller_nppn_f'>";
            $item_array["harga_jual_reseller"] = $hrg_list_reseller;
            // ----------------
            $item_array["premi_jual"] = "<input $disabled_premi id='premi_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_premi_jual'+this.value);trigger_nilai('premi_jual',this.value,$prod_id,$row_id);\" value='$premi_jual'>";
            $item_array["premi_juale"] = $premi_jual;
            $item_array["premi_jual_nilai"] = "<input $disabled_premi id='premi_jual_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('premi_jual_nilai',this.value,$prod_id,$row_id);\" value='$nPremiJual'>";
            $item_array["premi_jual_nilaine"] = $nPremiJual;
            // ----------------
            $item_array["diskon_persen"] = "<input $disabled_diskon id='diskon_persen_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);trigger_nilai('diskon_persen',this.value,$prod_id,$row_id);\" value='$diskon_persen'>";
            $item_array["diskon_persene"] = $diskon_persen;
            $item_array["diskon_nilai"] = "<input $disabled_diskon id='diskon_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('diskon_nilai',this.value,$prod_id,$row_id);\" value='$nDiskonJual'>";
            $item_array["diskon_nilaine"] = $diskon_persen;

            /* -------------------------------------------
             * button action
             * -------------------------------------------*/
            $btn_grosir = "";
            $btn_grosir .= "<div class='btn-group'>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sat_$prod_id' class='btn-satuan btn btn-xs btn-danger tombol-action btn-satuan'>satuan</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir$grosir_cek</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sch_$prod_id' class='btn-scheduler btn btn-xs btn-info tombol-action btn-scheduler'>scheduler</button>";
            $btn_grosir .= "</div>";

            $item_array["grosir_cek"] = "$grosir_yes $grosir_cek";
            $item_array["grosir"] = "$btn_grosir";
            $item_array["harga_aft"] = $hrg_jual;
            $item_array["harga_aft_nppn"] = ($hrg_jual * my_ppn_factor() / 100) + $hrg_jual;
            $item_array["margin"] = $hrg_margin;

            /*------------------------------------------------
             * PEMBELIAN
             * --------------------------------------------------*/
            $diskon_dua = $dk->calcDiskon($hrg_list, array("dua" => $diskon_beli), array(), "diskon", $biaya_beli);
            $nDiskonBeli = $diskon_dua['nilai'];
            $diskon_tiga = $dk->calcDiskon($hrg_list, array("dua" => $premi_beli), array(), "premi", $biaya_beli);
            $nPremiBeli = $diskon_tiga['nilai'];


            $item_array["harga_beli_0"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_0_$prod_id' class='text-right form-control' type='text' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier'+removeCommas(this.value));trigger_nilai('hpp_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier'>";
            /* -------------------------------------------------------------------------------------------------------
             * diskon pembelian rebate custom sebelum ppn
             * -------------------------------------------------------------------------------------------------------*/
            $persen_dp_0 = $dp_datas[$prod_id]['diskon_0']['persen'];
            $persen_dp_0_f = number_format($persen_dp_0);
            // $diskon_nilai_0 = $dp_datas[$prod_id]['diskon_0']['nilai'];
            $diskon_nilai_0 = ($persen_dp_0 / 100) * $hpp_supplier;
            $diskon_nilai_0_f = number_format($diskon_nilai_0, 2);
            $hpp_supplier_0 = $hpp_supplier - $diskon_nilai_0;
            $hpp_supplier_0_f = number_format($hpp_supplier_0);
            $hrg_pp_nppn = ($hpp_supplier_0 * (my_ppn_factor() / 100)) + $hpp_supplier_0;
            $hrg_pp_nppn_f = number_format($hrg_pp_nppn);
            $no_kolom = 0;
            $diskon_0_id = "0";
            $item__persen_0 = "<input id='diskon_0_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'persen');\" value='$persen_dp_0_f'>";
            $item__nilai_0 = "<input id='diskon_0_nilai_$prod_id' placeholder='000' class='text-right form-control' size='3' type='text' minx='0' steps='1' onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'nilai');\" value='$diskon_nilai_0_f'>";
            $hpp_berjalan = "<br><input type='text' id='diskon_0_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            $item_array["diskon_0"] = "$item__persen_0 $item__nilai_0 $hpp_berjalan";

            // $item__persen_00 = "<input id='diskon_00_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'tes','persen');\" value='$persen_dp'>";
            // $item__nilai_00 = "<input id='diskon_00_nilai_$prod_id' placeholder='000' class='text-right form-control' size='4' type='text' minx='0' steps='1' onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'test,'nilai');\" value='$diskon_persen_0'>";
            // $hpp_berjalan_00 = "<br><input type='text' id='diskon_00_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            // arrPrintPink($src_freeProduks);
            $src_freeProduks_00 = isset($src_freeProduks[$prod_id]) ? $src_freeProduks[$prod_id] : array();
            $dp_freeproduk_nilai = isset($src_freeProduks[$prod_id]) ? ($src_freeProduks_00->produk_rel_harga / $src_freeProduks_00->qty_min) : 0;
            $dp_freeproduk_nilai_npph = $dp_freeproduk_nilai * ((100 - $this->pph23) / 100);
            $dp_freeproduk_nilai_f = number_format($dp_freeproduk_nilai);
            if (in_array($prod_id, $dp_freeproduk)) {
                $btn_warna = "btn-warning";
                $btn_persen_00_setting = 1;
            }
            else {
                $btn_warna = "btn-info";
                $btn_persen_00_setting = 0;
            }
            $link_modal = MODUL_PATH . "Setting/settingFreeProdukPembelian/$prod_id";
            $judul_form = strtoupper("free produk untuk $nama");
            $modal_btn = modalDialogBtn($judul_form, $link_modal);
            $btn_persen_00 = "<button type='button' id='diskon_00_btn_$prod_id' data-nilai='$dp_freeproduk_nilai' data-nilainpph='$dp_freeproduk_nilai_npph' class='btn $btn_warna btn-block text-uppercase' onclick=\"kirim_tanda('$row_id');$modal_btn \">sett $dp_freeproduk_nilai_f</button>";
            $item_array["diskon_00"] = "$btn_persen_00";
            $item_array["diskon_00_nilai"] = "$dp_freeproduk_nilai";
            $item_array["diskon_00_setting"] = "$btn_persen_00_setting";
            // -------------------------------------------------------------------------------------------------------

            $item_array["harga_beli_be_tax"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_be_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier_0'+removeCommas(this.value));trigger_nilai('hpp_supplier_0',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier_0_f'>";;
            $item_array["harga_beli_af_tax"] = "<input size='5' placeholder='$hrg_pp_nppn' id='harga_beli_af_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"trigger_nilai('hpp_nppn_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hrg_pp_nppn_f'>";
            //            $item_array["harga_beli_af_tax"] = $hrg_pp_nppn;

            $link_update_biaya_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_beli&nilai=";
            $link_update_diskon_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_beli&nilai=";
            $link_update_premi_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_beli&nilai=";

            $item_array["biaya_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_beli'+this.value);\" value='$biaya_beli'>";
            $item_array["diskon_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_diskon_beli'+this.value);\" value='$diskon_beli'>";

            /* ----------------------------------------------------------------------
             * komponen harga tandas
             * diskon yg diterima
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            $src_dps = isset($dp_datas[$prod_id]) ? $dp_datas[$prod_id] : array();
            $src_dpersupplier = isset($dps_datas[$spl_id]) ? $dps_datas[$spl_id] : 0;
            // arrPrintKuning($src_dps);

            $metode_dpp_berjalan = false;
            $total_nilai_dp0 = 0;
            $hpp_supplier_2 = 0;
            foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $kp_id = $kolomDiskonPembeliansId[$kp_key];
                $setting_persen = $src_dpersupplier[$kp_id]["persen"];

                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $iddppnilai = $kp_key . "_dpp_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp

                //                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen']*1>0 ? round($src_dps[$kp_key]['persen']) : $setting_persen;//ORI
                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['persen'], 2, '.', '') : 0;
                $nilai_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai'], 0, '.', '') : 0;
                $nilai_plus_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai_plus'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai_plus'], 0, '.', '') : 0;

                /* ---------------------------------------------------------------------------------
                 * bila ada nilai absolut gukakan klo tdk ada hitungkan dr persen setting per supplier
                 * ---------------------------------------------------------------------------------*/
                if ($metode_dpp_berjalan == true) {
                    if ($no_kolom == 1) {
                        $nilai_dp_calc = ($persen_dp / 100) * $hrg_pp_nppn; // dpp berjalan

                        $hpp_supplier_2 = $hrg_pp_nppn - $nilai_dp_calc;

                        // cekHere("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }
                    else {
                        $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2;
                        $hpp_supplier_2 = $hpp_supplier_2 - $nilai_dp_calc;

                        // cekHere(__LINE__ . " $hpp_supplier_2");
                        // cekHijau("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }

                    // $nilai_dp = isset($src_dps[$kp_key]) ? $src_dps[$kp_key]['nilai'] : $nilai_dp_calc;
                    if (isset($src_dps[$kp_key])) {
                        $nilai_dp = $src_dps[$kp_key]['nilai'];
                    }
                    else {
                        $nilai_dp = $nilai_dp_calc;
                        // menulis ke setting diskon supplier
                        if (isset($cCode) && ($cCode != null)) {
                            $arrAddData[$prod_id][$kp_id] = array(
                                "per_supplier_diskon_id" => $kp_id,
                                "per_supplier_diskon_nama" => $kp_key,
                                "persen" => $persen_dp,
                                "nilai" => $nilai_dp,
                                "produk_id" => $prod_id,
                                "supplier_id" => $spl_id,
                                "status" => 1,
                            );
                        }
                    }
                }
                else {
                    $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_0;

                    $nilai_dp = $nilai_dp_calc;
                }

                // cekHere("$nilai_dp_0");
                // $nilai_dp_f =  round($nilai_dp);
                $nilai_dp_f = $nilai_dp_0 > 0 ? round($nilai_dp_0) : round($nilai_dp);
                $dpp_berjalan = "<br><input type='text' class='text-right form-control shadow_nilai hidden' style='width: 85px;' id='$iddppnilai' value='$hpp_supplier_2'>";
                // cekHijau("$nilai_dp_f");
                $total_nilai_dp0 += $nilai_dp_f;
                $nilai_dp_npph = $nilai_plus_dp_0 > 0 ? round($nilai_plus_dp_0) : $nilai_dp / 1.15;
                // cekHijau("$nilai_dp_npph");
                // $nilai_dp_dpp_f = number_format($nilai_dp_npph);
                $nilai_dp_npph_f = round($nilai_dp_npph);
                // $nilai_dp_dpp_f = ($nilai_dp_npph);
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'persen');\" value='$persen_dp'>";
                $item__nilainpph = "<input id='$idnilainpph' class='text-right form-control' style='background-color: #69dc39;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilainpph');\" value='$nilai_dp_npph_f'>";
                // $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' style='background-color: #ffeb3b;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $item__nilainpph $dpp_berjalan ";
                $item_array[$kp_key] = "<input id='$idnilai' class='text-right form-control' style='background-color: #ffeb3b;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $item__nilainpph $dpp_berjalan ";
                $item_array[$kp_key . "_o"] = "$nilai_dp_npph_f";

                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_nama'] = $kp_key;
                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_id'] = $kp_id;
                $dataDiskonPembelian[$kp_key]['persen'] = $persen_dp;
                $dataDiskonPembelian[$kp_key]['nilai'] = $nilai_dp;
                $dataDiskonPembelian[$kp_key]['supplier_id'] = $spl_id;
                $dataDiskonPembelian[$kp_key]['status'] = 1;
            }
            $total_nilai_dp = $total_nilai_dp0 + $dp_freeproduk_nilai; // termausk free produk
            // cekHere("$total_nilai_dp = $total_nilai_dp0 + $dp_freeproduk_nilai;");
            /* ----------------------------------------------------------------------
             * diskon untuk pembayaran kredit note
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";
                $item__nilainpph = "<input id='$idnilainpph' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";

                $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$premi_beli'> $item__nilainpph";
            }

            $diskon_pajak = $total_nilai_dp * ($this->pph23 / 100);
            $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak;
            // $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak + $dp_freeproduk_nilai_npph; // termasuk diskon freeproduk
            $hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp); //ORI
            // $hrg_beli = $hrg_beli_be_pph + $diskon_pajak; //ORI
            // $hrg_beli = $hrg_pp_nppn - $total_nilai_dp;
            /* -----------------------------------------
             * dihitung dg dasat hpp tanpa ppn (netto)
             * -----------------------------------------*/
            $hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            // cekHere("$hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;");
            /* -----------------------------------------
             * dihitung dg dasa hpp include ppn sample panasonic mengunkan ini dan dr supplier harga rebate termasuk pph
             * -----------------------------------------*/
            $hrg_beli = $hrg_pp_nppn - $total_nilai_dp_af_tax;
            // cekHere("$hrg_beli = $hrg_pp_nppn - $total_nilai_dp_af_tax;");
            $hrg_beli_be_tax = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            // $hrg_beli_af_tax = $hrg_beli * ((100 + my_ppn_factor()) / 100);
            $hrg_beli_af_tax = $hrg_beli;
            $hrg_beli_npph = ($hrg_pp_nppn - $total_nilai_dp) + $diskon_pajak;
            // cekKuning("$hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp);");
            // cekHijau("$prod_id :: $hpp_supplier - $total_nilai_dp + $diskon_pajak ==== $hrg_beli");
            /*---tandas---*/
            $item_array["harga_beline_be_pph"] = $hrg_beli_be_pph;
            $item_array["total_nilai_dp"] = $total_nilai_dp;
            $item_array["total_nilai_dp_af_tax"] = $total_nilai_dp_af_tax;
            $item_array["harga_pajak_beline"] = $diskon_pajak;
            // ------------------------tandas
            $item_array["harga_beline"] = $hrg_beli;
            $item_array["harga_beline"] = $hrg_beli_be_tax;
            // $item_array["harga_beline_af_tax"] = $hrg_beli_npph;
            $item_array["harga_beline_af_tax"] = $hrg_beli_af_tax;
            $item_array["harga_beli"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_beli'>";

            /*---validasi diskon----*/
            $gr_persen_1 = "";
            $diskon_cek = 0;
            $diskon_ygbener = 0;
            $grosir_cek = "no";
            if (isset($produk_grosir[$prod_id])) {
                // cekMerah($hrg_list);
                $data_grosiers = $produk_grosir[$prod_id];
                $gr_persen_1 = isset($data_grosiers['persen_1']) ? $data_grosiers['persen_1'] : 0;

                $gr_nilai_1a = isset($data_grosiers['nilai_1']) ? $data_grosiers['nilai_1'] : 0;
                $diskon_nilai_1 = $dk->calcDiskon($hrg_list, array("satu" => $gr_persen_1));
                // cekHere("id:$prod_id: $gr_nilai_1a");
                // arrPrint($diskon_nilai_1);
                $gr_nilai_1 = isset($data_grosiers['nilai_1']) ? $diskon_nilai_1['nilai'] : 0;

                $nilai_1_calc = $hrg_list * ($gr_persen_1 / 100);
                $nilai_1_calc_f = round($nilai_1_calc);
                // cekBiru("$nilai_1_calc_f");
                // arrPrintPink($produk_grosir[$prod_id]);


                $diskon_cek = $nilai_1_calc_f != $gr_nilai_1 ? 1 : 0;

                if ($diskon_cek == 1) {
                    $diskon_ygbener = ($gr_nilai_1 / $hrg_list) * 100;
                    $harga_ygbener = $hrg_list - $gr_nilai_1;

                    $dg_condites = array(
                        "produk_id" => $prod_id,
                        "urutan" => 1,
                        "trash" => 0,
                        "status" => 1,
                        "jenis" => "produk_grosir",
                        "toko_id" => my_toko_id(),
                    );
                    $dg_datas = array(
                        "persen" => $diskon_ygbener,
                        "harga" => $harga_ygbener,
                    );
                    // $dg->setTableName("diskon");
                    // $dg->updateData($dg_condites, $dg_datas);
                    // showLast_query("merah");
                    if ($prod_id == "9076") {
                        arrPrintKuning($dg_datas);
                        // matiHere(__LINE__);
                    }
                    $grosir_cek = "yes";
                }

                // arrPrintKuning($data_grosiers);
            }

            $item_array["diskon_cek"] = $diskon_cek;
            $item_array["diskon_ygbener"] = $diskon_ygbener;
            $item_array["grosir_cek"] = $grosir_cek;

            //            $item_array["harga_beli"] = $harga_beli;
            //            $item_array["biaya_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-biaya_beli'  type='number' max='100' min='0' step='1' value='$biaya_beli'>";
            //            $item_array["biaya_beli"]   = $biaya_beli;
            //            $item_array["diskon_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-diskon_beli' type='number' max='100' min='0' step='1' value='$diskon_beli'>";
            //            $item_array["diskon_beli"]  = $diskon_beli;
            //            $item_array["premi_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-premi_beli'  type='number' max='100' min='0' step='1' value='$premi_beli'>";
            //            $item_array["premi_beli"]   = $premi_beli;
            $drosirs = isset($produk_grosir[$prod_id]) ? $produk_grosir[$prod_id] : array();
            $src_pr[$prod_id] = $item_array + $drosirs;

            /* --------------------------------------------
             * update ke diskon per produk dikirim dari komponen tandas
             * --------------------------------------------*/
            $diskonPembelians[$prod_id] = $dataDiskonPembelian;

        }

        if (sizeof($arrAddData) > 0) {
            $this->db->trans_start();

            foreach ($arrAddData as $produk_id => $subSpec) {
                foreach ($subSpec as $disk_id => $subData) {
                    $dp = new MdlDiskonPembelian();
                    $dp->addData($subData);
                    //                    showLast_query("hijau");
                }
            }
            //                matiHere("belum comit " . __LINE__);
            $this->db->trans_complete();
        }
        if (isset($cCode) && ($cCode != null)) {
            $this->iterasiGerbangItem($cCode);
        }


        /* ------------------------------------------------------------
         * $dp
         * untuk ngupdate diskon pembelian per-produk
         * ------------------------------------------------------------*/
        // $this
        // $cek = $dp->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintPink($diskonPembelians);
        // ------------------------------------------------------------end----


        /* ---------------------
         * dta produk per supplier
         * ---------------------*/

        $vendor = false;
        if ($vendor == true) {
            $this->load->model("Mdls/MdlProdukPerSupplier");
            $pps = new MdlProdukPerSupplier();
            if (isset($_GET['suppliers_id'])) {
                $condites = array(
                    "suppliers_id" => $_GET['suppliers_id'],
                );
                $this->db->where($condites);
            }
            $src_pps_0 = $pps->lookupAll()->result();// showLast_query("kuning");
            // arrPrint($src_pps_0);
            foreach ($src_pps_0 as $src_pp) {
                $suppliers_id = $src_pp->suppliers_id;
                $produk_id = $src_pp->produk_id;

                $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
                // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
                $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
            }
        }

        $arrHeaders_01 = array(
            "id" => array(
                "label" => "pid",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
                "links" => array(
                    // "modal_size" => "modal-xl",
                    "target" => "diskon/Setting/viewHistory",
                    "title" => "History ",
                    "title_head_key" => "nama",
                    "key" => "id",
                ),
            ),
            // "grosir_cek" => array(
            //     "label" => "grosir",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "barcode" => array(
                "label" => "barcode",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "nama" => array(
                "label" => "nama produk",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            // "satuan" => array(
            //     "label" => "satuan",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "merek_nama" => array(
                "label" => "merek",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "kategori_nama" => array(
                "label" => "kat",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
        );

        // -----------------------------------------beli-beli----------------------------------
        $arrHeaders_02 = array(
            "harga_beli_0" => array(
                "label" => "harga list",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            // "diskon_00"         => array(
            //     "label"       => "free produk",
            //     "attr_header" => "class='bg-danger'",
            //     "attr"        => "class='text-right bg-danger'",
            //     "top_parent"  => "harga_beli",
            //     "data_order"  => "diskon_00_setting",
            // ),
            "diskon_0" => array(
                "label" => "diskon 0",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_be_tax" => array(
                "label" => "dpp",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_af_tax" => array(
                "label" => "incl. ppn",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
        );

        /* -----------------------------------------------------------
         * komponen pembentuk tandas rebate
         * -----------------------------------------------------------*/
        $arrHeaders_02["diskon_00"] = array(
            "label" => "free produk",
            "attr_header" => "class='bg-warning'",
            "attr" => "class='text-right bg-warning'",
            "top_parent" => "pembelian",
            "data_order" => "diskon_00_setting",
        );
        foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-warning'",
                "attr" => "class='text-right bg-warning'",
                "top_parent" => "pembelian",
                // "data_order"  => false,
                "data_order" => $kp_key . "_o",
            );
        }

        foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "top_parent" => "pembelian",
                "data_order" => false,
            );
        }

        // "diskon_beli"   => array(
        //     "label"  => "diskon pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "premi_beli"    => array(
        //     "label"  => "premi pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "biaya_beli"    => array(
        //     "label"  => "biaya pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),

        /* ----------------------------------------------
         * disembunyikan ngikuti kolom dari client
         * ----------------------------------------------*/
        $arrHeaders_02["total_nilai_dp"] = array(
            "label" => "total rebate sb. pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "total_nilai_dp",
        );
        $arrHeaders_02["harga_pajak_beline"] = array(
            "label" => "pph23",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "harga_beline",
        );

        $arrHeaders_02["total_nilai_dp_af_tax"] = array(
            "label" => "total rebate st.&nbsp;pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "total_nilai_dp",
        );
        $arrHeaders_02["harga_beline"] = array(
            "label" => "harga tandas w/o ppn",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "harga_beline",
        );
        $arrHeaders_02["harga_beline_af_tax"] = array(
            "label"       => "harga tandas inc. ppn",
            "attr_header" => "class='bg-danger'",
            "attr"        => "class='text-right bg-danger'",
            "format"      => "formatField_he_format",
            "format_key"  => "harga",
            "top_parent"  => "pembelian",
            "data_order"  => "harga_beline_af_tax",
        );

        // -----------------------------------------jual-jual----------------------------------
        $arrHeaders_03 = array(
            // "margin"        => array(
            //     "label"  => "margin (%)",
            //     "attr"   => "class='text-right'",
            //     "format" => "formatField_he_format",
            // ),
            /*---penjualan---*/
            "ppn" => array(
                "label" => "ppn",
                "attr_header" => "class='bg-olive-active'",
                "attr" => "class='text-right bg-olive-active'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "ppn",
            ),
            "harga_jual_online_nilai" => array(
                "label" => "online non ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_online_nilai",
            ),
            "harga_jual_online_nppn" => array(
                "label" => "online incl. ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_online_nilai",
            ),
            //---------------------
            "harga_jual" => array(
                "label" => "end user non ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual",
            ),
            "harga_list_nppn" => array(
                "label" => "end user incl. ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual",
            ),
            //----------
            "harga_jual_reseller" => array(
                "label" => "dealer non ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_reseller",
            ),
            "harga_list_reseller_nppn" => array(
                "label" => "dealer incl. ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_reseller",
            ),
            // ------------------------------------------
            // "diskon_persen"     => array(
            //     "label"      => "persen",
            //     "attr_header" => "class='bg-purple'",
            //     "attr"       => "class='text-right bg-purple'",
            //     "top_parent" => "simpel",
            //     // "top_sub_parent" => "simpel",
            //     "data_order" => "diskon_persene",
            // ),
            // "diskon_nilai"     => array(
            //     "label"      => "nilai",
            //     "attr_header" => "class='bg-purple'",
            //     "attr"       => "class='text-right bg-purple'",
            //     "top_parent" => "simpel",
            //     // "top_sub_parent" => "simpel",
            //     "data_order" => "diskon_nilaine",
            // ),
            // ---------------------------
            "premi_jual" => array(
                "label" => "persen",
                "attr_header" => "class='bg-teal'",
                "attr" => "class='text-right bg-teal'",
                "top_parent" => "simpel",
                // "top_sub_parent" => "simpel",
                // "data_order" => "premi_jual",
                "data_order" => "premi_juale",
            ),
            "premi_jual_nilai" => array(
                "label" => "nilai",
                "attr_header" => "class='bg-teal'",
                "attr" => "class='text-right bg-teal'",
                "top_parent" => "simpel",
                // "top_sub_parent" => "simpel",
                "data_order" => "premi_jual_nilaine",
            ),
            // "biaya_jual"        => array(
            //     "label"      => "biaya penjualan",
            //     "attr"       => "class='text-right bg-danger'",
            //     "top_parent" => "simpel",
            //     "data_order" => "biaya_jual_nilai",
            // ),
            "harga_aft" => array(
                "label" => "harga absolut",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "simpel",
            ),
            "harga_aft_nppn" => array(
                "label" => "harga absolut incl. ppn",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "simpel",
                "data_order" => "harga_aft_nppn",
            ),

            // "diskon_ygbener" => array(
            //     "label"      => "diskon_ygbener",
            //     "attr"       => "class='text-right bg-danger'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "dikson",
            //     "top_parent" => "simpel",
            // ),

        );
        $arrHeaders = $arrHeaders_01 + $arrHeaders_02 + $arrHeaders_03;

        /*---grosir---*/
        $data_grosir = false;
        if ($data_grosir == true) {
            for ($i = 1; $i <= $maxGrosir; $i++) {

                $bg_warna = $i % 2 == 0 ? "bg-warning" : "bg-success";

                $arrHeaders["minim_" . $i] = array(
                    "label" => "minimal<br>#$i",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "top_parent" => "grosir",
                );
                $arrHeaders["persen_" . $i] = array(
                    "label" => "potongan<br>#$i (%)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "diskon",
                    "top_parent" => "grosir",
                );
                $arrHeaders["nilai_" . $i] = array(
                    "label" => "potongan<br>#$i (Rp)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "top_parent" => "grosir",
                );
            }
        }
        $arrHeaders["grosir"] = array(
            "label" => "tindakan",
            "attr" => "class='text-right'",
            "data_order" => false,
            // "top_parent" => "grosir",
        );

        // --------------------------------------------------------------------
        $arrHeaderParents = array(
            "pembelian" => array(
                "label" => "diskon/komponen pembentuk harga tandas ",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_beli" => array(
                "label" => "harga beli <r>wajib diisi</r>",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_list" => array(
                "label" => "harga list <r> wajib diisi</r>",
                "attr_header" => "class='bg-aqua'",
            ),
            "simpel" => array(
                "label" => "premium",
                "attr_header" => "class='bg-blue'",
            ),
            "grosir" => array(
                "label" => "diskon berjenjang",
                "attr_header" => "class='bg-success'",
            ),
        );

        $data = array(
            "mode" => "viewProdukHarga",
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            "title" => "Setting Diskon",
            "subTitle" => "-",
            "arrHeaderParents" => $arrHeaderParents,
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            "is_po" => $is_po,
            "cCode" => isset($cCode) ? $cCode : "",
            "urlBack" => isset($urlBack) ? $urlBack : "",
            "pph23" => $this->pph23,
            "ppn" => my_ppn_factor(),
            "srcMereks" => $srcMereks,
            // "grosir_header"        => $grosir_header,
            // "grosir_data"          => $src_dg,
            // "level_header"         => $level_header,
            // "level_data"           => $src_clevel_diskons,
            // "level_data"           => array(),
            // "jenisTransaksi"       => $jenisTr,
            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
            // "template"             => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "isMobile"             => $isMob,
        );

        //arrPrint($data);

        $this->load->view("setting", $data);

    }

    public function viewProdukHarga()
    {
        $is_po = isset($_GET['id_item']) ? 1 : 0;
        if ($is_po == true) {
            $urlBack = $_GET['urlBack'];
            $cCode = $_GET['cCode'];
            //            cekHijau(":: $is_po :: [$cCode] ");
            $this->iterasiGerbangItem($cCode);
        }
        else{
            session_write_close();
        }
        $req_produk_ids = isset($_GET['id_item']) ? blobDecode($_GET['id_item']) : array();
        $harga_per_supplier = false;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();
        // showLast_query("orange");
        // arrPrintHijau($prod_hargas);
        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                // $produk_id = $param_prod_harga->produk_id;
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        // showLast_query("pink", __LINE__);
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;
            $dp_speks['nilai_plus'] = $dp_src->nilai_plus * 1;

            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks;
        }

        /*-------------MdlDiskonPembelianSupplier-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dps = new MdlDiskonPembelianSupplier();
        $dps_srcs = $dps->lookupAll()->result();
        // showLast_query("hijau", __LINE__);
        // arrPrintPink($dps_srcs);
        $dp_speks = array();
        $dps_datas = array();
        foreach ($dps_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_supplier_id = $dp_src->supplier_id;
            $dp_diskon_id = $dp_src->per_supplier_diskon_id;
            // $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_diskon_id;
            $dp_speks['per_supplier_diskon_nama'] = $dp_src->per_supplier_diskon_nama;
            $dp_speks['supplier_id'] = $dp_src->supplier_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;

            $dps_datas[$dp_supplier_id][$dp_diskon_id] = $dp_speks;
        }
        // arrPrintHijau($dps_datas);

        $this->load->library("Diskon");
        $dk = new Diskon();
        /*-----------grosir-----------------*/
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        $dg->setTokoId(my_toko_id());
        $src_dg_obj = $dg->callProdukGrosir("");

        // showLast_query("kuning");
        // cekHere(count($src_dg_obj));
        // arrPrint(array_slice($src_dg_obj,0,1));

        foreach ($src_dg_obj as $item) {
            $dg_produk_id = $item->produk_id;
            $dg_jenis = $item->jenis;
            $dg_minim = $item->minim;
            $dg_nilai = $item->nilai;
            $dg_persen = $item->persen;
            $dg_urutan = $item->urutan;
            $dg++;
            if (!isset($pr_grosir_aktive[$dg_produk_id])) {
                $pr_grosir_aktive[$dg_produk_id] = 0;
            }
            $pr_grosir_aktive[$dg_produk_id] += 1;

            $prod_hrg_jual = isset($prod_hrg_speks[$dg_produk_id]) ? (isset($prod_hrg_speks[$dg_produk_id]["harga_list"]) ? $prod_hrg_speks[$dg_produk_id]["harga_list"]->nilai : 0) : 0;


            $produk_grosir[$dg_produk_id]["minim_$dg_urutan"] = $dg_minim;
            $produk_grosir[$dg_produk_id]["persen_$dg_urutan"] = $dg_persen;
            $data_calc = $dk->calcDiskon($prod_hrg_jual, array($dg_persen), array());
            $dg_nilai_calc = $data_calc['nilai'];
            $produk_grosir[$dg_produk_id]["nilai_$dg_urutan"] = $dg_nilai_calc;
        }
        $sortGrosir = $pr_grosir_aktive;

        // asort($sortGrosir);
        // $maxGrosir = end($sortGrosir);
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($pr_grosir_aktive,0,1,true));
        // arrPrintWebs($produk_grosir);

        // region membaca hpp rata-rata stok yang tersedia
        $this->load->model("Mdls/MdlFifoAverage");
        $ff = New MdlFifoAverage();
        $ff->setFilters(array());
        // sementara ditembak cabang id 100, nanti kalau tambah cabang diganti metode
        // sepakat selalu melihat cb -1 25/5/23
        $ff->addFilter("cabang_id='-1'");
        $arrSelect = array(
            "produk_id",
            "avg(hpp) as hpp",
        );
        $this->db->group_by("produk_id");
        $this->db->select($arrSelect);
        $ffTmp = $ff->lookupAll()->result();
        //        showLast_query("biru");
        //        arrprint(array_slice($ffTmp, 0,1));
        $arrHppAvg = array();
        foreach ($ffTmp as $ffSpec) {
            $arrHppAvg[$ffSpec->produk_id] = (array)$ffSpec;
        }
        // endregion membaca hpp rata-rata stok yang tersedia
        // tool unutk ngupdate harga list dari harga jual pada price
        foreach ($prod_hrg_speks as $pid => $param_item) {

            $harga_jual = isset($param_item["jual"]) ? $param_item["jual"]->nilai : 0;
            foreach ($param_item as $jvalue => $item_00) {
                $dbid = $item_00->id;
                $dbnilai = $item_00->nilai;

                if ($jvalue == "harga_list") {
                    // cekBiru("update $dbnilai | $pid >> $harga_jual");
                    $dtUpds = array("nilai" => $harga_jual);
                    $kondisi = array("id" => $dbid);
                    // $hp->updateData($kondisi, $dtUpds);
                    // showLast_query("merah");
                }
            }
        }
        // tool

        /*-------produk_per_supplier-------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();

        if (isset($_GET['suppliers_id'])) {
            $condites = array(
                "suppliers_id" => $_GET['suppliers_id'],
            );
            // $this->db->where($condites);
        }
        $src_pps_0 = $pps->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintWebs($src_pps_0);
        $produk_suppliers = array();
        foreach ($src_pps_0 as $item) {

            $produk_suppliers[$item->produk_id][] = $item->suppliers_id;
            $produk_supplier[$item->produk_id] = $item->suppliers_id;
        }
        // arrPrintHijau($produk_supplier);
        // arrPrintWebs($produk_suppliers);

        if ($harga_per_supplier == true) {
            /*-------harga_produk_per_supplier-------*/
            $this->load->model("Mdls/MdlHargaProdukPerSupplier");
            $hpps = new MdlHargaProdukPerSupplier();
            $src_hpps_0 = $hpps->lookupAll()->result();
            // showLast_query('kuning');
            // $prod_hargas = array();
            foreach ($src_hpps_0 as $itemHpps) {
                // arrPrintHijau($itemHpps);
                $param_prod_harga = (array)$itemHpps;
                $produk_id = $itemHpps->produk_id;
                $jenis_value = $itemHpps->jenis_value;
                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
                $prod_hargas[$produk_id][] = (object)$param_prod_harga;
            }
        }
        // arrPrintHijau($src_hpps_0);
        // arrPrintKuning($prod_hargas);
        // arrPrint($prod_hrg_speks);

        /* ----------------------------------------------------------
       * freeproduk relasi
       * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $dpps = new MdlDiskonPembelianPairSupplier();
        $src_freeProduks = $dpps->callSpecs();
        // showLast_query("here");
        $dp_freeproduk = array_keys($src_freeProduks);
        // arrPrintKuning($src_freeProduks);
        // arrPrintKuning($dp_freeproduk);
        // foreach ($src_freeProduks as $pd_id => $src_freeProduk) {
        //    
        // }

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $srcMereks = $mr->callSpecs();
        // showLast_query("kuning");
        // arrPrintHijau($srcMereks);

        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();

        if (ipadd() == "202.65.117.72") {
            // echo cekAlert("data dilimit karena dalam mode debug dalam network MGK");
            // if (ipadd() == "202.65.117.80") {
            //            $this->db->limit(20);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
            //            $this->db->where("merek_id",array("42"));
        }

        if ($is_po == false) {
            if (isset($_GET['f'])) {
                $filters = array(
                    $_GET['f'] => $_GET['v'],
                );

                if($_GET['v'] === 'null'){}
                else{
                $this->db->where($filters);
                }

            }
            else {
                echo cekAlert("silahkan pilih merek terlebih dahulu");
                $this->db->limit(1);
            }
        }
        // $this->db->limit(50);
        // $this->db->where_in("id", array("1582", "121", "73", "944", "957"));
        // $this->db->where_in("id", array("1582", "3365"));
        // $this->db->where_in("supplier_id",array("1",));
        // $this->db->where_in("supplier_id",array("4",));
        if (count($req_produk_ids) > 0) {
            $this->db->where_in("id", $req_produk_ids);
        }
        $src_pr_obj_00 = $pr->callSpecs();
        // showLast_query("hijau");
        $filter_4 = url_segment(4);

        switch ($filter_4) {
            case "grosir":
                foreach ($pr_grosir_aktive as $item_id => $jml_grosir) {
                    if (isset($src_pr_obj_00[$item_id])) {
                        $src_pr_obj[$item_id] = $src_pr_obj_00[$item_id];
                    }
                }
                break;
            case "non_diskon":
                $src_pr_obj = array_diff_key($src_pr_obj_00, $pr_grosir_aktive);
                break;
            default:
                $src_pr_obj = $src_pr_obj_00;
                break;
        }

        $sortGrosir = array_intersect_key($pr_grosir_aktive, $src_pr_obj);
        asort($sortGrosir);
        $maxGrosir = end($sortGrosir);

        // $maxGrosir = 2;
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($sortGrosir,0,3, true));
        // arrPrintKuning($src_pr_obj);
        // arrPrintKuning(url_segment());
        // cekHere("all>".sizeof($src_pr_obj_00) ." diskon>". sizeof($pr_grosir_aktive) ." yg tampil>". sizeof($src_pr_obj));
        // cekHere(sizeof($src_pr_obj));
        // arrPrint(my_ppn_factor());

        /* ----------------------------------------------------------
         * diambilkandari MdlSupplierDiskon
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlSupplierDiskon");
        $spd = New MdlSupplierDiskon();
        $spd->addFilter("jenis='reguler'");
        $spdTmp = $spd->lookupAll()->result();
        // showLast_query("kuning", __LINE__);
        foreach ($spdTmp as $spdSpec) {
            $kolomDiskonPembeliansId[$spdSpec->nama] = $spdSpec->id;
            $kolomDiskonPembelians[$spdSpec->nama] = $spdSpec->label;
        }

        $kolomKreditnotePembelians = array(
            // "hpp_ppn"       => "hpp + ppn",
            // "diskon_1" => "event billing",
            // "diskon_2" => "otp rebate",
            // "diskon_3" => "monthly rebate",
            // "diskon_4" => "blind bonus",
            // "diskon_5" => "add suport",
            // "pph23"     => "pph23",
        );
        $kolomPembelians = $kolomDiskonPembelians + $kolomKreditnotePembelians;

        // arrPrint($kolomDiskonPembeliansId);
        // arrPrint($kolomPembelians);

        /** -----------------------------------
         * master data builder
         * ----------------------------------*/
        $arrAddData = array();
        $diskonPembelians = array();
        $row_id = 999;
        foreach ($src_pr_obj as $prod_id => $item) {
            $row_id++;
            // arrPrintHijau($item);
            $diskon_persen = $item->diskon_persen * 1;
            $nama = $item->nama;
            $spl_id = $item->supplier_id;
            $premi_jual = isset($item->premi_jual) ? $item->premi_jual : 0;
            $biaya_jual = isset($item->biaya_jual) ? $item->biaya_jual : 0;
            $premi_beli = isset($item->premi_beli) ? $item->premi_beli : 0;
            $biaya_beli = isset($item->biaya_beli) ? $item->biaya_beli : 0;
            $diskon_beli = isset($item->diskon_beli) ? $item->diskon_beli : 0;

            /* -----------------------------------------------
             * update relasi ke-supplier
             * -----------------------------------------------*/
            // $spl_id_new = isset($produk_supplier[$prod_id]) ? $produk_supplier[$prod_id] : "";
            // $upCondites = array(
            //   "id" => $prod_id,
            //   "supplier_id" => null,
            // );
            // $upDatas = array(
            //     "supplier_id" => $spl_id_new,
            // );
            // $pr->updateData($upCondites,$upDatas);
            // showLast_query("biru");

            // /*----delete produkpersupplier*/
            // $upCondites2 = array(
            //     "suppliers_id !=" => $spl_id_new,
            //     "produk_id" => $prod_id,
            // );
            // $upDatas2 = array(
            //     "trash" => 1,
            // );
            // $pps->updateData($upCondites2,$upDatas2);
            // showLast_query("merah");

            $harga_speks = array();
            if (isset($prod_hargas[$prod_id])) {
                foreach ($prod_hargas[$prod_id] as $spek_harga) {
                    $harga_speks[$spek_harga->jenis_value] = $spek_harga;
                }
            }

            $hrg_beli = isset($arrHppAvg[$prod_id]["hpp_nppv"]) ? ($arrHppAvg[$prod_id]["hpp_nppv"] * 1) : 0;
            $hrg_pp = isset($arrHppAvg[$prod_id]["hpp"]) ? ($arrHppAvg[$prod_id]["hpp"] * 1) : 0;
            $hrg_pp_f = format_harga($hrg_pp);

            // $hpp_supplier = isset($harga_speks['hpp']) ? $harga_speks['hpp']->nilai * 1 : 0;
            //            $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            if (isset($_SESSION[$cCode]["items"][$prod_id])) {
                $hpp_supplier = $_SESSION[$cCode]["items"][$prod_id]["hpp"];
            }
            else {
                $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            }
            // $hpp_supplier = $hrg_pp;
            // arrPrintKuning($harga_speks);
            $hrg_tandas_manual = isset($harga_speks['tandas_manual']) ? $harga_speks['tandas_manual']->nilai * 1 : 0;
            $harga_jual_bawah = isset($harga_speks['jual_bawah']) ? $harga_speks['jual_bawah']->nilai * 1 : 0;
            $hrg_jual_online = isset($harga_speks['jual_online']) ? $harga_speks['jual_online']->nilai * 1 : 0;
            $hrg_list_jual = isset($harga_speks['jual']) ? $harga_speks['jual']->nilai * 1 : 0;
            $hrg_list_0 = $hrg_list_reseller = isset($harga_speks['jual_reseller']) ? $harga_speks['jual_reseller']->nilai * 1 : 0;
            $hrg_list = $hrg_list_0 > 0 ? $hrg_list_0 : $hrg_list_jual;
            $diskon_enol = $dk->calcDiskon($hrg_list, array("satu" => $diskon_persen), array(), "diskon", $biaya_jual);
            $nDiskonJual = $diskon_enol['nilai'];
            // ----------------------------------------------
            $diskon_satu = $dk->calcDiskon($hrg_list, array("satu" => $premi_jual), array(), "premi", $biaya_jual);
            $nPremiJual = $diskon_satu['nilai'];
            $diskon_nilai = $diskon_satu['nilai'];
            $hrg_jual = $hrg_list - $nDiskonJual + $nPremiJual;

            $hrg_margin = $hrg_jual > 0 ? (($hrg_jual - $hrg_beli) / $hrg_jual) * 100 : 0;
            $jml_grosir = isset($pr_grosir_aktive[$prod_id]) ? $pr_grosir_aktive[$prod_id] : 0;
            $grosir_cek = $jml_grosir > 0 ? "<i class='fa fa-check text-green'> $jml_grosir</i>" : "";
            $grosir_yes = $jml_grosir > 0 ? "yes" : "no";

            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_pembelian&nilai=";
            $link_update_hrg_jual_online = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online&nilai=";
            $link_update_hrg_jual_online_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online_nppn&kyb=harga_jual_online_nilai&nilai=";
            $link_update_hrg_list_reseller = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller&nilai=";
            $link_update_hrg_list_reseller_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller_nppn&kyb=harga_jual_reseler&nilai=";
            $link_update_hrg_list = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual&nilai=";
            $link_update_hrg_list_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_nppn&kyb=harga_jual&nilai=";
            $link_update_premi_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_jual&nilai=";
            $link_update_biaya_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_jual&nilai=";
            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_persen&nilai=";
            $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier&nilai=";
            $link_update_hrg_beli_supplier_0 = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier_0&nilai=";
            $link_update_hrg_tandas = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=harga_tandas&nilai=";

            $url_grosir = base_url() . "diskon/setting/viewGrosir?id=$prod_id";
            $link_grosir = modalDialogBtn("grosir $nama", $url_grosir);
            $url_satuan = base_url() . "diskon/Setting/viewSatuan?id=$prod_id";
            $link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);
            $url_scheduler = base_url() . "diskon/Setting/viewScheduler?id=$prod_id";
            $link_scheduler = modalDialogBtn("Scheduler diskon $nama", $url_scheduler);
            $item_array = (array)$item;

            if (($premi_jual * 1) > 0) {
                // if($diskon_persen > 0){
                $disabled_diskon = "disabled";
                $disabled_premi = "";
            }
            elseif ($premi_jual == 0 && $diskon_persen == 0) {
                $disabled_premi = "";
                $disabled_diskon = "";
            }
            else {
                $disabled_premi = "disabled";
                $disabled_diskon = "";
            }

            // $item_array["biaya_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_jual'+this.value);\" value='$biaya_jual'>";
            $item_array["biaya_jual"] = "<input id='biaya_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$biaya_jual' >";
            $item_array["biaya_jual_nilai"] = $biaya_jual;
            // ----------------
            $item_array["harga_jual_online_nilai"] = $hrg_jual_online;
            $item_array["harga_jual_online"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online'+this.value);\" value='$hrg_jual_online'>";
            $harga_jual_online_nppn = ($hrg_jual_online * my_ppn_factor() / 100) + $hrg_jual_online;
            $harga_jual_online_nppn_f = number_format($harga_jual_online_nppn, 0, '.', '');
            $item_array["harga_jual_online_nppn"] = "<input id='harga_jual_online_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online_nppn'+this.value);\" value='$harga_jual_online_nppn_f'>";
            // ----------------------------------------------
            $item_array["harga_list"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_list_jual'>";
            $harga_list_nppn = ($hrg_list_jual * my_ppn_factor() / 100) + $hrg_list_jual;
            $harga_list_nppn_f = number_format($harga_list_nppn, 0, '.', '');
            // cekHere("$harga_list_nppn_f //// $harga_list_nppn");
            $item_array["harga_list_nppn"] = "<input id='harga_list_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_nppn'+this.value);\" value='$harga_list_nppn_f'>";
            $item_array["harga_jual"] = $hrg_list_jual;
            // ---------------------------------------------
            $item_array["harga_list_reseller"] = "<input id='harga_list_reseller_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller'+this.value);\" value='$hrg_list_reseller'>";
            $harga_list_reseller_nppn = ($hrg_list_reseller * my_ppn_factor() / 100) + $hrg_list_reseller;
            $harga_list_reseller_nppn_f = number_format($harga_list_reseller_nppn, 0, '.', '');
            $item_array["harga_list_reseller_nppn"] = "<input id='harga_list_reseller_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller_nppn'+this.value);\" value='$harga_list_reseller_nppn_f'>";
            $item_array["harga_jual_reseller"] = $hrg_list_reseller;
            // ----------------
            $item_array["premi_jual"] = "<input $disabled_premi id='premi_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_premi_jual'+this.value);trigger_nilai('premi_jual',this.value,$prod_id,$row_id);\" value='$premi_jual'>";
            $item_array["premi_juale"] = $premi_jual;
            $item_array["premi_jual_nilai"] = "<input $disabled_premi id='premi_jual_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('premi_jual_nilai',this.value,$prod_id,$row_id);\" value='$nPremiJual'>";
            $item_array["premi_jual_nilaine"] = $nPremiJual;
            // ----------------
            $item_array["diskon_persen"] = "<input $disabled_diskon id='diskon_persen_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);trigger_nilai('diskon_persen',this.value,$prod_id,$row_id);\" value='$diskon_persen'>";
            $item_array["diskon_persene"] = $diskon_persen;
            $item_array["diskon_nilai"] = "<input $disabled_diskon id='diskon_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('diskon_nilai',this.value,$prod_id,$row_id);\" value='$nDiskonJual'>";
            $item_array["diskon_nilaine"] = $diskon_persen;

            $item_array["harga_jual_bawah"] = "<input $disabled_diskon id='harga_jual_bawah_$prod_id' style='width: 70px !important;' class='text-right form-control' type='text' step='1' onblur=\"trigger_nilai_bawah('harga_jual_bawah',this.value,$prod_id,$row_id);\" value='$harga_jual_bawah'>";
            // $item_array["diskon_nilaine"] = $diskon_persen;

            /* -------------------------------------------
             * button action button-button
             * -------------------------------------------*/
            $btn_grosir = "";
            $btn_grosir .= "<div class='btn-group'>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sat_$prod_id' class='btn-satuan btn btn-xs btn-danger tombol-action btn-satuan'>satuan</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir$grosir_cek</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sch_$prod_id' class='btn-scheduler btn btn-xs btn-info tombol-action btn-scheduler'>scheduler</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='had_$prod_id' class='btn btn-xs btn-info tombol-action btn-hadiah_penjualan'>hadiah penjualan</button>";
            $btn_grosir .= "</div>";

            $item_array["grosir_cek"] = "$grosir_yes $grosir_cek";
            $item_array["grosir"] = "$btn_grosir";
            $item_array["harga_aft"] = $hrg_jual;
            $item_array["harga_aft_nppn"] = ($hrg_jual * my_ppn_factor() / 100) + $hrg_jual;
            $item_array["margin"] = $hrg_margin;

            /*------------------------------------------------
             * PEMBELIAN
             * --------------------------------------------------*/
            $diskon_dua = $dk->calcDiskon($hrg_list, array("dua" => $diskon_beli), array(), "diskon", $biaya_beli);
            $nDiskonBeli = $diskon_dua['nilai'];
            $diskon_tiga = $dk->calcDiskon($hrg_list, array("dua" => $premi_beli), array(), "premi", $biaya_beli);
            $nPremiBeli = $diskon_tiga['nilai'];


            $item_array["harga_beli_0"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_0_$prod_id' class='text-right form-control' type='text' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier'+removeCommas(this.value));trigger_nilai('hpp_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier'>";
            /* -------------------------------------------------------------------------------------------------------
             * diskon pembelian rebate custom sebelum ppn
             * -------------------------------------------------------------------------------------------------------*/
            $persen_dp_0 = $dp_datas[$prod_id]['diskon_0']['persen'];
            $persen_dp_0_f = number_format($persen_dp_0);
            // $diskon_nilai_0 = $dp_datas[$prod_id]['diskon_0']['nilai'];
            $diskon_nilai_0 = ($persen_dp_0 / 100) * $hpp_supplier;
            $diskon_nilai_0_f = number_format($diskon_nilai_0, 2);
            $hpp_supplier_0 = $hpp_supplier - $diskon_nilai_0;
            $hpp_supplier_0_f = number_format($hpp_supplier_0);
            $hrg_pp_nppn = ($hpp_supplier_0 * (my_ppn_factor() / 100)) + $hpp_supplier_0;
            $hrg_pp_nppn_f = number_format($hrg_pp_nppn);
            $no_kolom = 0;
            $diskon_0_id = "0";
            $item__persen_0 = "<input id='diskon_0_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'persen');\" value='$persen_dp_0_f'>";
            $item__nilai_0 = "<input id='diskon_0_nilai_$prod_id' placeholder='000' class='text-right form-control' size='3' type='text' minx='0' steps='1' onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'nilai');\" value='$diskon_nilai_0_f'>";
            $hpp_berjalan = "<br><input type='text' id='diskon_0_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            $item_array["diskon_0"] = "$item__persen_0 $item__nilai_0 $hpp_berjalan";

            // $item__persen_00 = "<input id='diskon_00_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'tes','persen');\" value='$persen_dp'>";
            // $item__nilai_00 = "<input id='diskon_00_nilai_$prod_id' placeholder='000' class='text-right form-control' size='4' type='text' minx='0' steps='1' onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'test,'nilai');\" value='$diskon_persen_0'>";
            // $hpp_berjalan_00 = "<br><input type='text' id='diskon_00_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            // arrPrintPink($src_freeProduks);
            $src_freeProduks_00 = isset($src_freeProduks[$prod_id]) ? $src_freeProduks[$prod_id] : array();
            $dp_freeproduk_nilai = isset($src_freeProduks[$prod_id]) ? ($src_freeProduks_00->produk_rel_harga / $src_freeProduks_00->qty_min) : 0;
            $dp_freeproduk_nilai_npph = $dp_freeproduk_nilai * ((100 - $this->pph23) / 100);
            $dp_freeproduk_nilai_f = number_format($dp_freeproduk_nilai);
            if (in_array($prod_id, $dp_freeproduk)) {
                $btn_warna = "btn-warning";
                $btn_persen_00_setting = 1;
            }
            else {
                $btn_warna = "btn-info";
                $btn_persen_00_setting = 0;
            }
            $link_modal = MODUL_PATH . "Setting/settingFreeProdukPembelian/$prod_id";
            $judul_form = strtoupper("free produk untuk $nama");
            $modal_btn = modalDialogBtn($judul_form, $link_modal);
            $btn_persen_00 = "<button type='button' id='diskon_00_btn_$prod_id' data-nilai='$dp_freeproduk_nilai' data-nilainpph='$dp_freeproduk_nilai_npph' class='btn $btn_warna btn-block text-uppercase' onclick=\"kirim_tanda('$row_id');$modal_btn \">sett $dp_freeproduk_nilai_f</button>";
            $item_array["diskon_00"] = "$btn_persen_00";
            $item_array["diskon_00_nilai"] = "$dp_freeproduk_nilai";
            $item_array["diskon_00_setting"] = "$btn_persen_00_setting";
            // -------------------------------------------------------------------------------------------------------

            $item_array["harga_beli_be_tax"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_be_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier_0'+removeCommas(this.value));trigger_nilai('hpp_supplier_0',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier_0_f'>";;
            $item_array["harga_beli_af_tax"] = "<input size='5' placeholder='$hrg_pp_nppn' id='harga_beli_af_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"trigger_nilai('hpp_nppn_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hrg_pp_nppn_f'>";
            //            $item_array["harga_beli_af_tax"] = $hrg_pp_nppn;

            $link_update_biaya_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_beli&nilai=";
            $link_update_diskon_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_beli&nilai=";
            $link_update_premi_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_beli&nilai=";

            $item_array["biaya_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_beli'+this.value);\" value='$biaya_beli'>";
            $item_array["diskon_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_diskon_beli'+this.value);\" value='$diskon_beli'>";

            /* ----------------------------------------------------------------------
             * komponen harga tandas
             * diskon yg diterima
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            $src_dps = isset($dp_datas[$prod_id]) ? $dp_datas[$prod_id] : array();
            $src_dpersupplier = isset($dps_datas[$spl_id]) ? $dps_datas[$spl_id] : 0;
            // arrPrintKuning($src_dps);

            $metode_dpp_berjalan = false;
            $total_nilai_dp0 = 0;
            $hpp_supplier_2 = 0;
            foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $kp_id = $kolomDiskonPembeliansId[$kp_key];
                $setting_persen = $src_dpersupplier[$kp_id]["persen"];

                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $iddppnilai = $kp_key . "_dpp_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp

                //                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen']*1>0 ? round($src_dps[$kp_key]['persen']) : $setting_persen;//ORI
                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['persen'], 2, '.', '') : 0;
                $nilai_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai'], 0, '.', '') : 0;
                $nilai_plus_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai_plus'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai_plus'], 0, '.', '') : 0;

                /* ---------------------------------------------------------------------------------
                 * bila ada nilai absolut gukakan klo tdk ada hitungkan dr persen setting per supplier
                 * ---------------------------------------------------------------------------------*/
                if ($metode_dpp_berjalan == true) {
                    if ($no_kolom == 1) {
                        $nilai_dp_calc = ($persen_dp / 100) * $hrg_pp_nppn; // dpp berjalan

                        $hpp_supplier_2 = $hrg_pp_nppn - $nilai_dp_calc;

                        // cekHere("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }
                    else {
                        $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2;
                        $hpp_supplier_2 = $hpp_supplier_2 - $nilai_dp_calc;

                        // cekHere(__LINE__ . " $hpp_supplier_2");
                        // cekHijau("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }

                    // $nilai_dp = isset($src_dps[$kp_key]) ? $src_dps[$kp_key]['nilai'] : $nilai_dp_calc;
                    if (isset($src_dps[$kp_key])) {
                        $nilai_dp = $src_dps[$kp_key]['nilai'];
                    }
                    else {
                        $nilai_dp = $nilai_dp_calc;
                        // menulis ke setting diskon supplier
                        if (isset($cCode) && ($cCode != null)) {
                            $arrAddData[$prod_id][$kp_id] = array(
                                "per_supplier_diskon_id" => $kp_id,
                                "per_supplier_diskon_nama" => $kp_key,
                                "persen" => $persen_dp,
                                "nilai" => $nilai_dp,
                                "produk_id" => $prod_id,
                                "supplier_id" => $spl_id,
                                "status" => 1,
                            );
                        }
                    }
                }
                else {
                    $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_0;

                    $nilai_dp = $nilai_dp_calc;
                }

                // cekHere("$nilai_dp_0");
                // $nilai_dp_f =  round($nilai_dp);
                $nilai_dp_f = $nilai_dp_0 > 0 ? round($nilai_dp_0) : round($nilai_dp);
                $dpp_berjalan = "<br><input type='text' class='text-right form-control shadow_nilai hidden' style='width: 85px;' id='$iddppnilai' value='$hpp_supplier_2'>";
                // cekHijau("$nilai_dp_f");
                $total_nilai_dp0 += $nilai_dp_f;
                $nilai_dp_npph = $nilai_plus_dp_0 > 0 ? round($nilai_plus_dp_0) : $nilai_dp / 1.15;
                // cekHijau("$nilai_dp_npph");
                // $nilai_dp_dpp_f = number_format($nilai_dp_npph);
                $nilai_dp_npph_f = round($nilai_dp_npph);
                // $nilai_dp_dpp_f = ($nilai_dp_npph);
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'persen');\" value='$persen_dp'>";
                $item__nilainpph = "<input id='$idnilainpph' class='text-right form-control' style='background-color: #69dc39;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilainpph');\" value='$nilai_dp_npph_f'>";
                // $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' style='background-color: #ffeb3b;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $item__nilainpph $dpp_berjalan ";
                $item_array[$kp_key] = "<input id='$idnilai' class='text-right form-control' style='background-color: #ffeb3b;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $item__nilainpph $dpp_berjalan ";
                $item_array[$kp_key . "_o"] = "$nilai_dp_npph_f";

                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_nama'] = $kp_key;
                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_id'] = $kp_id;
                $dataDiskonPembelian[$kp_key]['persen'] = $persen_dp;
                $dataDiskonPembelian[$kp_key]['nilai'] = $nilai_dp;
                $dataDiskonPembelian[$kp_key]['supplier_id'] = $spl_id;
                $dataDiskonPembelian[$kp_key]['status'] = 1;
            }
            $total_nilai_dp = $total_nilai_dp0 + $dp_freeproduk_nilai; // termausk free produk
            // cekHere("$total_nilai_dp = $total_nilai_dp0 + $dp_freeproduk_nilai;");
            /* ----------------------------------------------------------------------
             * diskon untuk pembayaran kredit note
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";
                $item__nilainpph = "<input id='$idnilainpph' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";

                $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$premi_beli'> $item__nilainpph";
            }

            $diskon_pajak = $total_nilai_dp * ($this->pph23 / 100);
            $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak;
            // $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak + $dp_freeproduk_nilai_npph; // termasuk diskon freeproduk
            $hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp); //ORI
            // $hrg_beli = $hrg_beli_be_pph + $diskon_pajak; //ORI
            // $hrg_beli = $hrg_pp_nppn - $total_nilai_dp;
            /* -----------------------------------------
             * dihitung dg dasat hpp tanpa ppn (netto)
             * -----------------------------------------*/
            $hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            // cekHere("$hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;");
            /* -----------------------------------------
             * dihitung dg dasa hpp include ppn sample panasonic mengunkan ini dan dr supplier harga rebate termasuk pph
             * -----------------------------------------*/
            $hrg_beli = $hrg_pp_nppn - $total_nilai_dp_af_tax;
            // cekHere("$hrg_beli = $hrg_pp_nppn - $total_nilai_dp_af_tax;");
            $hrg_beli_be_tax = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            // $hrg_beli_af_tax = $hrg_beli * ((100 + my_ppn_factor()) / 100);
            $hrg_beli_af_tax = $hrg_beli;
            $hrg_beli_npph = ($hrg_pp_nppn - $total_nilai_dp) + $diskon_pajak;
            // cekKuning("$hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp);");
            // cekHijau("$prod_id :: $hpp_supplier - $total_nilai_dp + $diskon_pajak ==== $hrg_beli");
            /*---tandas---*/
            $item_array["harga_beline_be_pph"] = $hrg_beli_be_pph;
            $item_array["total_nilai_dp"] = $total_nilai_dp;
            $item_array["total_nilai_dp_af_tax"] = $total_nilai_dp_af_tax;
            $item_array["harga_pajak_beline"] = $diskon_pajak;
            // ------------------------tandas
            $item_array["harga_beline"] = $hrg_beli;
            $item_array["harga_beline"] = $hrg_beli_be_tax;
            // $item_array["harga_beline_af_tax"] = $hrg_beli_npph;
            $item_array["harga_beline_af_tax"] = $hrg_beli_af_tax;
            $item_array["harga_beline_manual"] = "<input id='harga_tandas_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_tandas'+this.value);\" value='$hrg_tandas_manual'>";
            $item_array["harga_beli"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_beli'>";

            /*---validasi diskon----*/
            $gr_persen_1 = "";
            $diskon_cek = 0;
            $diskon_ygbener = 0;
            $grosir_cek = "no";
            if (isset($produk_grosir[$prod_id])) {
                // cekMerah($hrg_list);
                $data_grosiers = $produk_grosir[$prod_id];
                $gr_persen_1 = isset($data_grosiers['persen_1']) ? $data_grosiers['persen_1'] : 0;

                $gr_nilai_1a = isset($data_grosiers['nilai_1']) ? $data_grosiers['nilai_1'] : 0;
                $diskon_nilai_1 = $dk->calcDiskon($hrg_list, array("satu" => $gr_persen_1));
                // cekHere("id:$prod_id: $gr_nilai_1a");
                // arrPrint($diskon_nilai_1);
                $gr_nilai_1 = isset($data_grosiers['nilai_1']) ? $diskon_nilai_1['nilai'] : 0;

                $nilai_1_calc = $hrg_list * ($gr_persen_1 / 100);
                $nilai_1_calc_f = round($nilai_1_calc);
                // cekBiru("$nilai_1_calc_f");
                // arrPrintPink($produk_grosir[$prod_id]);


                $diskon_cek = $nilai_1_calc_f != $gr_nilai_1 ? 1 : 0;

                if ($diskon_cek == 1) {
                    $diskon_ygbener = ($gr_nilai_1 / $hrg_list) * 100;
                    $harga_ygbener = $hrg_list - $gr_nilai_1;

                    $dg_condites = array(
                        "produk_id" => $prod_id,
                        "urutan" => 1,
                        "trash" => 0,
                        "status" => 1,
                        "jenis" => "produk_grosir",
                        "toko_id" => my_toko_id(),
                    );
                    $dg_datas = array(
                        "persen" => $diskon_ygbener,
                        "harga" => $harga_ygbener,
                    );
                    // $dg->setTableName("diskon");
                    // $dg->updateData($dg_condites, $dg_datas);
                    // showLast_query("merah");
                    if ($prod_id == "9076") {
                        arrPrintKuning($dg_datas);
                        // matiHere(__LINE__);
                    }
                    $grosir_cek = "yes";
                }

                // arrPrintKuning($data_grosiers);
            }

            $item_array["diskon_cek"] = $diskon_cek;
            $item_array["diskon_ygbener"] = $diskon_ygbener;
            $item_array["grosir_cek"] = $grosir_cek;

            //            $item_array["harga_beli"] = $harga_beli;
            //            $item_array["biaya_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-biaya_beli'  type='number' max='100' min='0' step='1' value='$biaya_beli'>";
            //            $item_array["biaya_beli"]   = $biaya_beli;
            //            $item_array["diskon_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-diskon_beli' type='number' max='100' min='0' step='1' value='$diskon_beli'>";
            //            $item_array["diskon_beli"]  = $diskon_beli;
            //            $item_array["premi_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-premi_beli'  type='number' max='100' min='0' step='1' value='$premi_beli'>";
            //            $item_array["premi_beli"]   = $premi_beli;
            $drosirs = isset($produk_grosir[$prod_id]) ? $produk_grosir[$prod_id] : array();
            $src_pr[$prod_id] = $item_array + $drosirs;

            /* --------------------------------------------
             * update ke diskon per produk dikirim dari komponen tandas
             * --------------------------------------------*/
            $diskonPembelians[$prod_id] = $dataDiskonPembelian;

        }

        if (sizeof($arrAddData) > 0) {
            $this->db->trans_start();

            foreach ($arrAddData as $produk_id => $subSpec) {
                foreach ($subSpec as $disk_id => $subData) {
                    $dp = new MdlDiskonPembelian();
                    $dp->addData($subData);
                    //                    showLast_query("hijau");
                }
            }
            //                matiHere("belum comit " . __LINE__);
            $this->db->trans_complete();
        }
        if (isset($cCode) && ($cCode != null)) {
            $this->iterasiGerbangItem($cCode);
        }


        /* ------------------------------------------------------------
         * $dp
         * untuk ngupdate diskon pembelian per-produk
         * ------------------------------------------------------------*/
        // $this
        // $cek = $dp->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintPink($diskonPembelians);
        // ------------------------------------------------------------end----


        /* ---------------------
         * dta produk per supplier
         * ---------------------*/

        $vendor = false;
        if ($vendor == true) {
            $this->load->model("Mdls/MdlProdukPerSupplier");
            $pps = new MdlProdukPerSupplier();
            if (isset($_GET['suppliers_id'])) {
                $condites = array(
                    "suppliers_id" => $_GET['suppliers_id'],
                );
                $this->db->where($condites);
            }
            $src_pps_0 = $pps->lookupAll()->result();// showLast_query("kuning");
            // arrPrint($src_pps_0);
            foreach ($src_pps_0 as $src_pp) {
                $suppliers_id = $src_pp->suppliers_id;
                $produk_id = $src_pp->produk_id;

                $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
                // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
                $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
            }
        }

        $arrHeaders_01 = array(
            "id" => array(
                "label" => "pid",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
                "links" => array(
                    // "modal_size" => "modal-xl",
                    "target" => "diskon/Setting/viewHistory",
                    "title" => "History ",
                    "title_head_key" => "nama",
                    "key" => "id",
                ),
            ),
            // "grosir_cek" => array(
            //     "label" => "grosir",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "barcode" => array(
                "label" => "barcode",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "nama" => array(
                "label" => "nama produk",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            // "satuan" => array(
            //     "label" => "satuan",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "merek_nama" => array(
                "label" => "merek",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "kategori_nama" => array(
                "label" => "kat",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
        );

        // -----------------------------------------beli-beli----------------------------------
        $arrHeaders_02 = array(
            "harga_beli_0" => array(
                "label" => "harga list",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            // "diskon_00"         => array(
            //     "label"       => "free produk",
            //     "attr_header" => "class='bg-danger'",
            //     "attr"        => "class='text-right bg-danger'",
            //     "top_parent"  => "harga_beli",
            //     "data_order"  => "diskon_00_setting",
            // ),
            "diskon_0" => array(
                "label" => "diskon 0",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_be_tax" => array(
                "label" => "dpp",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_af_tax" => array(
                "label" => "incl. ppn",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
        );

        /* -----------------------------------------------------------
         * komponen pembentuk tandas rebate
         * -----------------------------------------------------------*/
        $arrHeaders_02["diskon_00"] = array(
            "label" => "free produk",
            "attr_header" => "class='bg-warning'",
            "attr" => "class='text-right bg-warning'",
            "top_parent" => "pembelian",
            "data_order" => "diskon_00_setting",
        );
        foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-warning'",
                "attr" => "class='text-right bg-warning'",
                "top_parent" => "pembelian",
                // "data_order"  => false,
                "data_order" => $kp_key . "_o",
            );
        }

        foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "top_parent" => "pembelian",
                "data_order" => false,
            );
        }

        // "diskon_beli"   => array(
        //     "label"  => "diskon pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "premi_beli"    => array(
        //     "label"  => "premi pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "biaya_beli"    => array(
        //     "label"  => "biaya pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),

        /* ----------------------------------------------
         * disembunyikan ngikuti kolom dari client
         * ----------------------------------------------*/
        $arrHeaders_02["total_nilai_dp"] = array(
            "label" => "total rebate sb. pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "total_nilai_dp",
        );
        $arrHeaders_02["harga_pajak_beline"] = array(
            "label" => "pph23",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "harga_beline",
        );

        $arrHeaders_02["total_nilai_dp_af_tax"] = array(
            "label" => "total rebate st.&nbsp;pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "total_nilai_dp",
        );
        $arrHeaders_02["harga_beline"] = array(
            "label" => "harga tandas w/o ppn",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "harga_beline",
        );
        $arrHeaders_02["harga_beline_af_tax"] = array(
            "label"       => "harga tandas inc. ppn",
            "attr_header" => "class='bg-danger'",
            "attr"        => "class='text-right bg-danger'",
            "format"      => "formatField_he_format",
            "format_key"  => "harga",
            "top_parent"  => "pembelian",
            "data_order"  => "harga_beline_af_tax",
        );
        $arrHeaders_02["harga_beline_manual"] = array(
            "label"       => "harga tandas manual",
            "attr_header" => "class='bg-danger'",
            "attr"        => "class='text-right bg-danger'",
            "format"      => "formatField_he_format",
            "format_key"  => "harga",
            "top_parent"  => "pembelian",
            "data_order"  => "harga_beline_af_tax",
        );

        // -----------------------------------------jual-jual----------------------------------
        $arrHeaders_03 = array(
            // "margin"        => array(
            //     "label"  => "margin (%)",
            //     "attr"   => "class='text-right'",
            //     "format" => "formatField_he_format",
            // ),
            /*---penjualan---*/
            "ppn" => array(
                "label" => "ppn",
                "attr_header" => "class='bg-olive-active'",
                "attr" => "class='text-right bg-olive-active'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "ppn",
            ),
            "harga_jual_online_nilai" => array(
                "label" => "online non ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_online_nilai",
            ),
            "harga_jual_online_nppn" => array(
                "label" => "online incl. ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_online_nilai",
            ),
            //---------------------
            "harga_jual" => array(
                "label" => "end user non ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual",
            ),
            "harga_list_nppn" => array(
                "label" => "end user incl. ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual",
            ),
            //----------
            "harga_jual_reseller" => array(
                "label" => "dealer non ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_reseller",
            ),
            "harga_list_reseller_nppn" => array(
                "label" => "dealer incl. ppn",
                "attr_header" => "class='bg-aqua'",
                "attr" => "class='text-right bg-aqua'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_reseller",
            ),
            // ------------------------------------------
            // "diskon_persen"     => array(
            //     "label"      => "persen",
            //     "attr_header" => "class='bg-purple'",
            //     "attr"       => "class='text-right bg-purple'",
            //     "top_parent" => "simpel",
            //     // "top_sub_parent" => "simpel",
            //     "data_order" => "diskon_persene",
            // ),
            // "diskon_nilai"     => array(
            //     "label"      => "nilai",
            //     "attr_header" => "class='bg-purple'",
            //     "attr"       => "class='text-right bg-purple'",
            //     "top_parent" => "simpel",
            //     // "top_sub_parent" => "simpel",
            //     "data_order" => "diskon_nilaine",
            // ),
            // ---------------------------
            "premi_jual" => array(
                "label" => "persen",
                "attr_header" => "class='bg-teal'",
                "attr" => "class='text-right bg-teal'",
                "top_parent" => "simpel",
                // "top_sub_parent" => "simpel",
                // "data_order" => "premi_jual",
                "data_order" => "premi_juale",
            ),
            "premi_jual_nilai" => array(
                "label" => "nilai",
                "attr_header" => "class='bg-teal'",
                "attr" => "class='text-right bg-teal'",
                "top_parent" => "simpel",
                // "top_sub_parent" => "simpel",
                "data_order" => "premi_jual_nilaine",
            ),
            // "biaya_jual"        => array(
            //     "label"      => "biaya penjualan",
            //     "attr"       => "class='text-right bg-danger'",
            //     "top_parent" => "simpel",
            //     "data_order" => "biaya_jual_nilai",
            // ),
            "harga_aft" => array(
                "label" => "harga absolut",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "simpel",
            ),
            "harga_aft_nppn" => array(
                "label" => "harga absolut incl. ppn",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "simpel",
                "data_order" => "harga_aft_nppn",
            ),

            "harga_jual_bawah" => array(
                "label" => "batas bawah harga jual",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_jual",
                "data_order" => "harga_aft_nppn",
            ),
            // "diskon_ygbener" => array(
            //     "label"      => "diskon_ygbener",
            //     "attr"       => "class='text-right bg-danger'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "dikson",
            //     "top_parent" => "simpel",
            // ),

        );
        // $arrHeaders = $arrHeaders_01 + $arrHeaders_02 + $arrHeaders_03;
        $arrHeaders = $arrHeaders_01 + $arrHeaders_03 + $arrHeaders_02;

        /*---grosir---*/
        $data_grosir = false;
        if ($data_grosir == true) {
            for ($i = 1; $i <= $maxGrosir; $i++) {

                $bg_warna = $i % 2 == 0 ? "bg-warning" : "bg-success";

                $arrHeaders["minim_" . $i] = array(
                    "label" => "minimal<br>#$i",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "top_parent" => "grosir",
                );
                $arrHeaders["persen_" . $i] = array(
                    "label" => "potongan<br>#$i (%)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "diskon",
                    "top_parent" => "grosir",
                );
                $arrHeaders["nilai_" . $i] = array(
                    "label" => "potongan<br>#$i (Rp)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "top_parent" => "grosir",
                );
            }
        }
        $arrHeaders["grosir"] = array(
            "label" => "tindakan",
            "attr" => "class='text-right'",
            "data_order" => false,
            // "top_parent" => "grosir",
        );

        // --------------------------------------------------------------------
        $arrHeaderParents = array(
            "pembelian" => array(
                "label" => "diskon/komponen pembentuk harga tandas ",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_beli" => array(
                "label" => "harga beli <r>wajib diisi</r>",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_list" => array(
                "label" => "harga list <r> wajib diisi</r>",
                "attr_header" => "class='bg-aqua'",
            ),
            "simpel" => array(
                "label" => "premium",
                "attr_header" => "class='bg-blue'",
            ),
            "harga_jual" => array(
                "label" => "harga jual",
                "attr_header" => "class='bg-green'",
            ),
            "grosir" => array(
                "label" => "diskon berjenjang",
                "attr_header" => "class='bg-success'",
            ),
        );

        $data = array(
            "mode" => "viewProdukHarga",
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            "title" => "Setting Diskon",
            "subTitle" => "-",
            "arrHeaderParents" => $arrHeaderParents,
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            "is_po" => $is_po,
            "cCode" => isset($cCode) ? $cCode : "",
            "urlBack" => isset($urlBack) ? $urlBack : "",
            "pph23" => $this->pph23,
            "ppn" => my_ppn_factor(),
            "srcMereks" => $srcMereks,
            // "grosir_header"        => $grosir_header,
            // "grosir_data"          => $src_dg,
            // "level_header"         => $level_header,
            // "level_data"           => $src_clevel_diskons,
            // "level_data"           => array(),
            // "jenisTransaksi"       => $jenisTr,
            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
            // "template"             => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "isMobile"             => $isMob,
        );

        //arrPrint($data);

        $this->load->view("setting", $data);

    }

    public function viewProdukRebate()
    {
        $is_po = isset($_GET['id_item']) ? 1 : 0;
        if ($is_po == true) {
            $urlBack = $_GET['urlBack'];
            $cCode = $_GET['cCode'];
            //            cekHijau(":: $is_po :: [$cCode] ");
            $this->iterasiGerbangItem($cCode);
        }
        else{
            session_write_close();
        }
        $req_produk_ids = isset($_GET['id_item']) ? blobDecode($_GET['id_item']) : array();
        $harga_per_supplier = false;
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();
        // showLast_query("orange");
        // arrPrintHijau($prod_hargas);
        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                // $produk_id = $param_prod_harga->produk_id;
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        // showLast_query("pink", __LINE__);
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;
            $dp_speks['nilai_plus'] = $dp_src->nilai_plus * 1;

            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks;
        }

        if (isset($_GET['f'])) {
            $filters = array(
                $_GET['f'] => $_GET['v']
            );
        }
        $supplier_id = $filters['supplier_id'];

        /*-------------MdlDiskonPembelianSupplier-----------------*/
        $rebates = $dp->callRebate("", $supplier_id);
        $rebateUnit = $rebates['jenis']["unit"];
        // $rebates = $dp->callRebate();
        // showLast_query("biru");
        // arrPrint($rebates['jenis']);
        // arrPrint($rebateUnit);
        $rb_datas = array();
        foreach ($rebateUnit as $pro_id => $itemparams) {
            $dp_prod_id = $pro_id;
            foreach ($itemparams as $itemparam) {
                $dp_src = (object)$itemparam;
                $dp_jenis = $dp_src->per_supplier_diskon_nama;
                $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
                $dp_speks['persen'] = $dp_src->persen * 1;
                $dp_speks['nilai'] = $dp_src->nilai * 1;
                $dp_speks['nilai_plus'] = $dp_src->nilai_plus * 1;
                $dp_speks['minim'] = $dp_src->minim * 1;
                $dp_speks['maxim'] = $dp_src->maxim * 1;
                $rb_datas[$dp_prod_id][$dp_jenis] = $dp_speks;
            }
        }
        // arrPrintHijau($rb_datas);
        /*-------------MdlDiskonPembelianSupplier-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dps = new MdlDiskonPembelianSupplier();
        $dps_srcs = $dps->lookupAll()->result();
        // showLast_query("hijau", __LINE__);
        // arrPrintPink($dps_srcs);
        $dp_speks = array();
        $dps_datas = array();
        foreach ($dps_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_supplier_id = $dp_src->supplier_id;
            $dp_diskon_id = $dp_src->per_supplier_diskon_id;
            // $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_diskon_id;
            $dp_speks['per_supplier_diskon_nama'] = $dp_src->per_supplier_diskon_nama;
            $dp_speks['supplier_id'] = $dp_src->supplier_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;
            $dps_datas[$dp_supplier_id][$dp_diskon_id] = $dp_speks;
        }
        // arrPrintHijau($dps_datas);

        $this->load->library("Diskon");
        $dk = new Diskon();
        // /*-----------grosir-----------------*/
        // $this->load->model("Mdls/MdlDiskonGrosir");
        // $dg = new MdlDiskonGrosir();
        // $dg->setTokoId(my_toko_id());
        // $src_dg_obj = $dg->callProdukGrosir("");

        // showLast_query("kuning");
        // cekHere(count($src_dg_obj));
        // arrPrint(array_slice($src_dg_obj,0,1));

        // foreach ($src_dg_obj as $item) {
        //     $dg_produk_id = $item->produk_id;
        //     $dg_jenis = $item->jenis;
        //     $dg_minim = $item->minim;
        //     $dg_nilai = $item->nilai;
        //     $dg_persen = $item->persen;
        //     $dg_urutan = $item->urutan;
        //     $dg++;
        //     if (!isset($pr_grosir_aktive[$dg_produk_id])) {
        //         $pr_grosir_aktive[$dg_produk_id] = 0;
        //     }
        //     $pr_grosir_aktive[$dg_produk_id] += 1;
        //
        //     $prod_hrg_jual = isset($prod_hrg_speks[$dg_produk_id]) ? (isset($prod_hrg_speks[$dg_produk_id]["harga_list"]) ? $prod_hrg_speks[$dg_produk_id]["harga_list"]->nilai : 0) : 0;
        //
        //
        //     $produk_grosir[$dg_produk_id]["minim_$dg_urutan"] = $dg_minim;
        //     $produk_grosir[$dg_produk_id]["persen_$dg_urutan"] = $dg_persen;
        //     $data_calc = $dk->calcDiskon($prod_hrg_jual, array($dg_persen), array());
        //     $dg_nilai_calc = $data_calc['nilai'];
        //     $produk_grosir[$dg_produk_id]["nilai_$dg_urutan"] = $dg_nilai_calc;
        // }
        // $sortGrosir = $pr_grosir_aktive;

        // asort($sortGrosir);
        // $maxGrosir = end($sortGrosir);
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($pr_grosir_aktive,0,1,true));
        // arrPrintWebs($produk_grosir);

        // region membaca hpp rata-rata stok yang tersedia
        $this->load->model("Mdls/MdlFifoAverage");
        $ff = New MdlFifoAverage();
        $ff->setFilters(array());
        // sementara ditembak cabang id 100, nanti kalau tambah cabang diganti metode
        // sepakat selalu melihat cb -1 25/5/23
        $ff->addFilter("cabang_id='-1'");
        $arrSelect = array(
            "produk_id",
            "avg(hpp) as hpp",
        );
        $this->db->group_by("produk_id");
        $this->db->select($arrSelect);
        $ffTmp = $ff->lookupAll()->result();
        //        showLast_query("biru");
        //        arrprint(array_slice($ffTmp, 0,1));
        $arrHppAvg = array();
        foreach ($ffTmp as $ffSpec) {
            $arrHppAvg[$ffSpec->produk_id] = (array)$ffSpec;
        }
        // endregion membaca hpp rata-rata stok yang tersedia
        // tool unutk ngupdate harga list dari harga jual pada price
        foreach ($prod_hrg_speks as $pid => $param_item) {

            $harga_jual = isset($param_item["jual"]) ? $param_item["jual"]->nilai : 0;
            foreach ($param_item as $jvalue => $item_00) {
                $dbid = $item_00->id;
                $dbnilai = $item_00->nilai;

                if ($jvalue == "harga_list") {
                    // cekBiru("update $dbnilai | $pid >> $harga_jual");
                    $dtUpds = array("nilai" => $harga_jual);
                    $kondisi = array("id" => $dbid);
                    // $hp->updateData($kondisi, $dtUpds);
                    // showLast_query("merah");
                }
            }
        }
        // tool

        /*-------produk_per_supplier-------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();

        if (isset($_GET['suppliers_id'])) {
            $condites = array(
                "suppliers_id" => $_GET['suppliers_id'],
            );
            // $this->db->where($condites);
        }
        $src_pps_0 = $pps->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintWebs($src_pps_0);
        $produk_suppliers = array();
        foreach ($src_pps_0 as $item) {

            $produk_suppliers[$item->produk_id][] = $item->suppliers_id;
            $produk_supplier[$item->produk_id] = $item->suppliers_id;
        }
        // arrPrintHijau($produk_supplier);
        // arrPrintWebs($produk_suppliers);

        if ($harga_per_supplier == true) {
            /*-------harga_produk_per_supplier-------*/
            $this->load->model("Mdls/MdlHargaProdukPerSupplier");
            $hpps = new MdlHargaProdukPerSupplier();
            $src_hpps_0 = $hpps->lookupAll()->result();
            // showLast_query('kuning');
            // $prod_hargas = array();
            foreach ($src_hpps_0 as $itemHpps) {
                // arrPrintHijau($itemHpps);
                $param_prod_harga = (array)$itemHpps;
                $produk_id = $itemHpps->produk_id;
                $jenis_value = $itemHpps->jenis_value;
                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
                $prod_hargas[$produk_id][] = (object)$param_prod_harga;
            }
        }
        // arrPrintHijau($src_hpps_0);
        // arrPrintKuning($prod_hargas);
        // arrPrint($prod_hrg_speks);

        /* ----------------------------------------------------------
       * freeproduk relasi
       * ----------------------------------------------------------*/
        // $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        // $dpps = new MdlDiskonPembelianPairSupplier();
        // $src_freeProduks = $dpps->callSpecs();
        // // showLast_query("here");
        // $dp_freeproduk = array_keys($src_freeProduks);
        // arrPrintKuning($src_freeProduks);
        // arrPrintKuning($dp_freeproduk);
        // foreach ($src_freeProduks as $pd_id => $src_freeProduk) {
        //
        // }

        $this->load->model("Mdls/MdlSupplier");
        $mr = new MdlSupplier();
        // $this->db->order_by("nama", "asc");
        // $mr->setSortBy(array("kolom" => "nama", "mode" => "asc"));
        $srcMereks = $mr->callSpecs();
        // showLast_query("kuning");
        // arrPrintHijau($srcMereks);

        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pr = new MdlProdukPerSupplier();

        if (ipadd() == "202.65.117.72") {
            // echo cekAlert("data dilimit karena dalam mode debug dalam network MGK");
            // if (ipadd() == "202.65.117.80") {
            //            $this->db->limit(20);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
            //            $this->db->where("merek_id",array("42"));
        }

        // arrPrintHijau($_GET);
        if ($is_po == false) {
            if (isset($_GET['f'])) {
                $filters = array(
                    $_GET['f'] => $_GET['v'],
                );

                if($_GET['v'] === 'null'){}
                else{
                    $this->db->where($filters);
                }

            }
            else {
                echo cekAlert("silahkan pilih merek terlebih dahulu");
                $this->db->limit(1);
            }
        }
        // $this->db->limit(50);
        // $this->db->where_in("id", array("1582", "121", "73", "944", "957"));
        // $this->db->where_in("id", array("1582", "3365"));
        // $this->db->where_in("supplier_id",array("1",));
        // $this->db->where_in("supplier_id",array("4",));
        if (count($req_produk_ids) > 0) {
            $this->db->where_in("id", $req_produk_ids);
        }
        // $src_pr_obj_00 = $pr->callSpecs();
        $src_pr_obj_00 = $pr->lookupInnerJoin()->result();
        // showLast_query("hijau");
        $filter_4 = url_segment(4);
        // matiHere(__LINE__);
        switch ($filter_4) {
            // case "grosir":
            //     foreach ($pr_grosir_aktive as $item_id => $jml_grosir) {
            //         if (isset($src_pr_obj_00[$item_id])) {
            //             $src_pr_obj[$item_id] = $src_pr_obj_00[$item_id];
            //         }
            //     }
            //     break;
            // case "non_diskon":
            //     $src_pr_obj = array_diff_key($src_pr_obj_00, $pr_grosir_aktive);
            //     break;
            default:
                $src_pr_obj = $src_pr_obj_00;
                break;
        }

        // $sortGrosir = array_intersect_key($pr_grosir_aktive, $src_pr_obj);
        // asort($sortGrosir);
        // $maxGrosir = end($sortGrosir);

        // $maxGrosir = 2;
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($sortGrosir,0,3, true));
        // arrPrintKuning($src_pr_obj);
        // arrPrintKuning(url_segment());
        // cekHere("all>".sizeof($src_pr_obj_00) ." diskon>". sizeof($pr_grosir_aktive) ." yg tampil>". sizeof($src_pr_obj));
        // cekHere(sizeof($src_pr_obj));
        // arrPrint(my_ppn_factor());

        /* ----------------------------------------------------------
         * diambilkandari MdlSupplierDiskon tbl:per_supplier_diskon
         * untuk membuat kolom2
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlSupplierDiskon");
        $spd = New MdlSupplierDiskon();
        $spd->addFilter("jenis='khusus'");
        $spdTmp = $spd->lookupAll()->result();
        // showLast_query("kuning", __LINE__);
        foreach ($spdTmp as $spdSpec) {
            $kolomDiskonPembeliansId[$spdSpec->nama] = $spdSpec->id;
            $kolomDiskonPembelians[$spdSpec->nama] = $spdSpec->label;
        }
        $spd->addFilter("jenis='khusus_abs'");
        $spdTmp = $spd->lookupAll()->result();
        // showLast_query("kuning", __LINE__);
        foreach ($spdTmp as $spdSpec) {
            $kolomDiskonAbsolutPembeliansId[$spdSpec->nama] = $spdSpec->id;
            $kolomDiskonAbsolutPembelians[$spdSpec->nama] = $spdSpec->label;
        }

        $kolomKreditnotePembelians = array(
            // "hpp_ppn"       => "hpp + ppn",
            // "diskon_1" => "event billing",
            // "diskon_2" => "otp rebate",
            // "diskon_3" => "monthly rebate",
            // "diskon_4" => "blind bonus",
            // "diskon_5" => "add suport",
            // "pph23"     => "pph23",
        );
        $kolomPembelians = $kolomDiskonPembelians + $kolomKreditnotePembelians;

        // arrPrint($kolomDiskonPembeliansId);
        // arrPrint($kolomPembelians);

        /* -----------------------------------
         * master data builder
         * ----------------------------------*/
        $arrAddData = array();
        $diskonPembelians = array();
        $row_id = 999;
        foreach ($src_pr_obj as $prod_index => $item) {
            $prod_id = $item->produk_id;
            $row_id++;
            // arrPrintHijau($item);
            $diskon_persen = $item->diskon_persen * 1;
            $nama = $item->nama;
            $spl_id = $item->supplier_id;
            $premi_jual = isset($item->premi_jual) ? $item->premi_jual : 0;
            $biaya_jual = isset($item->biaya_jual) ? $item->biaya_jual : 0;
            $premi_beli = isset($item->premi_beli) ? $item->premi_beli : 0;
            $biaya_beli = isset($item->biaya_beli) ? $item->biaya_beli : 0;
            $diskon_beli = isset($item->diskon_beli) ? $item->diskon_beli : 0;

            /* -----------------------------------------------
             * update relasi ke-supplier
             * -----------------------------------------------*/
            // $spl_id_new = isset($produk_supplier[$prod_id]) ? $produk_supplier[$prod_id] : "";
            // $upCondites = array(
            //   "id" => $prod_id,
            //   "supplier_id" => null,
            // );
            // $upDatas = array(
            //     "supplier_id" => $spl_id_new,
            // );
            // $pr->updateData($upCondites,$upDatas);
            // showLast_query("biru");

            // /*----delete produkpersupplier*/
            // $upCondites2 = array(
            //     "suppliers_id !=" => $spl_id_new,
            //     "produk_id" => $prod_id,
            // );
            // $upDatas2 = array(
            //     "trash" => 1,
            // );
            // $pps->updateData($upCondites2,$upDatas2);
            // showLast_query("merah");

            $harga_speks = array();
            if (isset($prod_hargas[$prod_id])) {
                foreach ($prod_hargas[$prod_id] as $spek_harga) {
                    $harga_speks[$spek_harga->jenis_value] = $spek_harga;
                }
            }

            $hrg_beli = isset($arrHppAvg[$prod_id]["hpp_nppv"]) ? ($arrHppAvg[$prod_id]["hpp_nppv"] * 1) : 0;
            $hrg_pp = isset($arrHppAvg[$prod_id]["hpp"]) ? ($arrHppAvg[$prod_id]["hpp"] * 1) : 0;
            $hrg_pp_f = format_harga($hrg_pp);

            // $hpp_supplier = isset($harga_speks['hpp']) ? $harga_speks['hpp']->nilai * 1 : 0;
            //            $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            if (isset($_SESSION[$cCode]["items"][$prod_id])) {
                $hpp_supplier = $_SESSION[$cCode]["items"][$prod_id]["hpp"];
            }
            else {
                $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            }
            // $hpp_supplier = $hrg_pp;
            // arrPrintKuning($harga_speks);
            $hrg_jual_online = isset($harga_speks['jual_online']) ? $harga_speks['jual_online']->nilai * 1 : 0;
            $hrg_list_jual = isset($harga_speks['jual']) ? $harga_speks['jual']->nilai * 1 : 0;
            $hrg_list_0 = $hrg_list_reseller = isset($harga_speks['jual_reseller']) ? $harga_speks['jual_reseller']->nilai * 1 : 0;
            $hrg_list = $hrg_list_0 > 0 ? $hrg_list_0 : $hrg_list_jual;
            // $diskon_enol = $dk->calcDiskon($hrg_list, array("satu" => $diskon_persen), array(), "diskon", $biaya_jual);
            // $nDiskonJual = $diskon_enol['nilai'];
            // ----------------------------------------------
            // $diskon_satu = $dk->calcDiskon($hrg_list, array("satu" => $premi_jual), array(), "premi", $biaya_jual);
            // $nPremiJual = $diskon_satu['nilai'];
            // $diskon_nilai = $diskon_satu['nilai'];
            // $hrg_jual = $hrg_list - $nDiskonJual + $nPremiJual;

            // $hrg_margin = $hrg_jual > 0 ? (($hrg_jual - $hrg_beli) / $hrg_jual) * 100 : 0;
            $jml_grosir = isset($pr_grosir_aktive[$prod_id]) ? $pr_grosir_aktive[$prod_id] : 0;
            $grosir_cek = $jml_grosir > 0 ? "<i class='fa fa-check text-green'> $jml_grosir</i>" : "";
            $grosir_yes = $jml_grosir > 0 ? "yes" : "no";

            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_pembelian&nilai=";
            $link_update_hrg_jual_online = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online&nilai=";
            $link_update_hrg_jual_online_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online_nppn&kyb=harga_jual_online_nilai&nilai=";
            $link_update_hrg_list_reseller = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller&nilai=";
            $link_update_hrg_list_reseller_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller_nppn&kyb=harga_jual_reseler&nilai=";
            $link_update_hrg_list = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual&nilai=";
            $link_update_hrg_list_nppn = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_nppn&kyb=harga_jual&nilai=";
            $link_update_premi_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_jual&nilai=";
            $link_update_biaya_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_jual&nilai=";
            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_persen&nilai=";
            $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier&nilai=";
            $link_update_hrg_beli_supplier_0 = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier_0&nilai=";
            // $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp&nilai=";

            $url_grosir = base_url() . "diskon/setting/viewGrosir?id=$prod_id";
            $link_grosir = modalDialogBtn("grosir $nama", $url_grosir);
            $url_satuan = base_url() . "diskon/Setting/viewSatuan?id=$prod_id";
            $link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);
            $url_scheduler = base_url() . "diskon/Setting/viewScheduler?id=$prod_id";
            $link_scheduler = modalDialogBtn("Scheduler diskon $nama", $url_scheduler);
            $item_array = (array)$item;

            if (($premi_jual * 1) > 0) {
                // if($diskon_persen > 0){
                $disabled_diskon = "disabled";
                $disabled_premi = "";
            }
            elseif ($premi_jual == 0 && $diskon_persen == 0) {
                $disabled_premi = "";
                $disabled_diskon = "";
            }
            else {
                $disabled_premi = "disabled";
                $disabled_diskon = "";
            }

            // $item_array["biaya_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_jual'+this.value);\" value='$biaya_jual'>";
            $item_array["biaya_jual"] = "<input id='biaya_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$biaya_jual' >";
            $item_array["biaya_jual_nilai"] = $biaya_jual;
            // ----------------
            $item_array["harga_jual_online_nilai"] = $hrg_jual_online;
            $item_array["harga_jual_online"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online'+this.value);\" value='$hrg_jual_online'>";
            $harga_jual_online_nppn = ($hrg_jual_online * my_ppn_factor() / 100) + $hrg_jual_online;
            $harga_jual_online_nppn_f = number_format($harga_jual_online_nppn, 0, '.', '');
            $item_array["harga_jual_online_nppn"] = "<input id='harga_jual_online_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online_nppn'+this.value);\" value='$harga_jual_online_nppn_f'>";
            // ----------------------------------------------
            $item_array["harga_list"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_list_jual'>";
            $harga_list_nppn = ($hrg_list_jual * my_ppn_factor() / 100) + $hrg_list_jual;
            $harga_list_nppn_f = number_format($harga_list_nppn, 0, '.', '');
            // cekHere("$harga_list_nppn_f //// $harga_list_nppn");
            $item_array["harga_list_nppn"] = "<input id='harga_list_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_nppn'+this.value);\" value='$harga_list_nppn_f'>";
            $item_array["harga_jual"] = $hrg_list_jual;
            // ---------------------------------------------
            $item_array["harga_list_reseller"] = "<input id='harga_list_reseller_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller'+this.value);\" value='$hrg_list_reseller'>";
            $harga_list_reseller_nppn = ($hrg_list_reseller * my_ppn_factor() / 100) + $hrg_list_reseller;
            $harga_list_reseller_nppn_f = number_format($harga_list_reseller_nppn, 0, '.', '');
            $item_array["harga_list_reseller_nppn"] = "<input id='harga_list_reseller_nppn_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller_nppn'+this.value);\" value='$harga_list_reseller_nppn_f'>";
            $item_array["harga_jual_reseller"] = $hrg_list_reseller;
            // ----------------
            $item_array["premi_jual"] = "<input $disabled_premi id='premi_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_premi_jual'+this.value);trigger_nilai('premi_jual',this.value,$prod_id,$row_id);\" value='$premi_jual'>";
            $item_array["premi_juale"] = $premi_jual;
            $item_array["premi_jual_nilai"] = "<input $disabled_premi id='premi_jual_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('premi_jual_nilai',this.value,$prod_id,$row_id);\" value='$nPremiJual'>";
            $item_array["premi_jual_nilaine"] = $nPremiJual;
            // ----------------
            $item_array["diskon_persen"] = "<input $disabled_diskon id='diskon_persen_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);trigger_nilai('diskon_persen',this.value,$prod_id,$row_id);\" value='$diskon_persen'>";
            $item_array["diskon_persene"] = $diskon_persen;
            $item_array["diskon_nilai"] = "<input $disabled_diskon id='diskon_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('diskon_nilai',this.value,$prod_id,$row_id);\" value='$nDiskonJual'>";
            $item_array["diskon_nilaine"] = $diskon_persen;

            /* -------------------------------------------
             * button action
             * -------------------------------------------*/
            $btn_grosir = "";
            $btn_grosir .= "<div class='btn-group'>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sat_$prod_id' class='btn-satuan btn btn-xs btn-danger tombol-action btn-satuan'>satuan</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir$grosir_cek</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sch_$prod_id' class='btn-scheduler btn btn-xs btn-info tombol-action btn-scheduler'>scheduler</button>";
            $btn_grosir .= "</div>";

            $item_array["grosir_cek"] = "$grosir_yes $grosir_cek";
            $item_array["grosir"] = "$btn_grosir";
            $item_array["harga_aft"] = $hrg_jual;
            $item_array["harga_aft_nppn"] = ($hrg_jual * my_ppn_factor() / 100) + $hrg_jual;
            $item_array["margin"] = $hrg_margin;

            /*------------------------------------------------
             * PEMBELIAN
             * --------------------------------------------------*/
            // $diskon_dua = $dk->calcDiskon($hrg_list, array("dua" => $diskon_beli), array(), "diskon", $biaya_beli);
            // $nDiskonBeli = $diskon_dua['nilai'];
            // $diskon_tiga = $dk->calcDiskon($hrg_list, array("dua" => $premi_beli), array(), "premi", $biaya_beli);
            // $nPremiBeli = $diskon_tiga['nilai'];


            $item_array["harga_beli_0"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_0_$prod_id' class='text-right form-control' type='text' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier'+removeCommas(this.value));trigger_nilai('hpp_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier'>";
            /* -------------------------------------------------------------------------------------------------------
             * diskon pembelian rebate custom sebelum ppn
             * -------------------------------------------------------------------------------------------------------*/
            $persen_dp_0 = $dp_datas[$prod_id]['diskon_0']['persen'];
            $persen_dp_0_f = number_format($persen_dp_0);
            // $diskon_nilai_0 = $dp_datas[$prod_id]['diskon_0']['nilai'];
            $diskon_nilai_0 = ($persen_dp_0 / 100) * $hpp_supplier;
            $diskon_nilai_0_f = number_format($diskon_nilai_0, 2);
            $hpp_supplier_0 = $hpp_supplier - $diskon_nilai_0;
            $hpp_supplier_0_f = number_format($hpp_supplier_0);
            $hrg_pp_nppn = ($hpp_supplier_0 * (my_ppn_factor() / 100)) + $hpp_supplier_0;
            $hrg_pp_nppn_f = number_format($hrg_pp_nppn);
            $no_kolom = 0;
            $diskon_0_id = "0";
            $item__persen_0 = "<input id='diskon_0_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'persen');\" value='$persen_dp_0_f'>";
            $item__nilai_0 = "<input id='diskon_0_nilai_$prod_id' placeholder='000' class='text-right form-control' size='3' type='text' minx='0' steps='1' onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'nilai');\" value='$diskon_nilai_0_f'>";
            $hpp_berjalan = "<br><input type='text' id='diskon_0_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            $item_array["diskon_0"] = "$item__persen_0 $item__nilai_0 $hpp_berjalan";

            // $item__persen_00 = "<input id='diskon_00_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'tes','persen');\" value='$persen_dp'>";
            // $item__nilai_00 = "<input id='diskon_00_nilai_$prod_id' placeholder='000' class='text-right form-control' size='4' type='text' minx='0' steps='1' onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'test,'nilai');\" value='$diskon_persen_0'>";
            // $hpp_berjalan_00 = "<br><input type='text' id='diskon_00_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            // arrPrintPink($src_freeProduks);
            $src_freeProduks_00 = isset($src_freeProduks[$prod_id]) ? $src_freeProduks[$prod_id] : array();
            $dp_freeproduk_nilai = isset($src_freeProduks[$prod_id]) ? ($src_freeProduks_00->produk_rel_harga / $src_freeProduks_00->qty_min) : 0;
            $dp_freeproduk_nilai_npph = $dp_freeproduk_nilai * ((100 - $this->pph23) / 100);
            $dp_freeproduk_nilai_f = number_format($dp_freeproduk_nilai);
            if (in_array($prod_id, $dp_freeproduk)) {
                $btn_warna = "btn-warning";
                $btn_persen_00_setting = 1;
            }
            else {
                $btn_warna = "btn-info";
                $btn_persen_00_setting = 0;
            }
            $link_modal = MODUL_PATH . "Setting/settingFreeProdukPembelian/$prod_id";
            $judul_form = strtoupper("free produk untuk $nama");
            $modal_btn = modalDialogBtn($judul_form, $link_modal);
            $btn_persen_00 = "<button type='button' id='diskon_00_btn_$prod_id' data-nilai='$dp_freeproduk_nilai' data-nilainpph='$dp_freeproduk_nilai_npph' class='btn $btn_warna btn-block text-uppercase' onclick=\"kirim_tanda('$row_id');$modal_btn \">sett $dp_freeproduk_nilai_f</button>";
            $item_array["diskon_00"] = "$btn_persen_00";
            $item_array["diskon_00_nilai"] = "$dp_freeproduk_nilai";
            $item_array["diskon_00_setting"] = "$btn_persen_00_setting";
            // -------------------------------------------------------------------------------------------------------

            $item_array["harga_beli_be_tax"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_be_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier_0'+removeCommas(this.value));trigger_nilai('hpp_supplier_0',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier_0_f'>";;
            $item_array["harga_beli_af_tax"] = "<input size='5' placeholder='$hrg_pp_nppn' id='harga_beli_af_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"trigger_nilai('hpp_nppn_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hrg_pp_nppn_f'>";
            //            $item_array["harga_beli_af_tax"] = $hrg_pp_nppn;

            $link_update_biaya_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_beli&nilai=";
            $link_update_diskon_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_beli&nilai=";
            $link_update_premi_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_beli&nilai=";

            $item_array["biaya_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_beli'+this.value);\" value='$biaya_beli'>";
            $item_array["diskon_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_diskon_beli'+this.value);\" value='$diskon_beli'>";

            /* ----------------------------------------------------------------------
             * komponen harga tandas
             * diskon yg diterima
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            $src_dps = isset($dp_datas[$prod_id]) ? $dp_datas[$prod_id] : array();
            $src_rbs = isset($rb_datas[$prod_id]) ? $rb_datas[$prod_id] : array();
            $src_dpersupplier = isset($dps_datas[$spl_id]) ? $dps_datas[$spl_id] : 0;
            // arrPrintKuning($rb_datas);
            // cekHijau($prod_id);

            // arrPrintPink($kolomDiskonPembelians);
            $metode_dpp_berjalan = false;
            $total_nilai_dp0 = 0;
            $hpp_supplier_2 = 0;
            foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $kp_id = $kolomDiskonPembeliansId[$kp_key];
                $setting_persen = $src_dpersupplier[$kp_id]["persen"];

                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idmaxim = $kp_key . "_maxim_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $iddppnilai = $kp_key . "_dpp_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp
                // arrPrintWebs($src_rbs[$kp_key]);
                //                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen']*1>0 ? round($src_dps[$kp_key]['persen']) : $setting_persen;//ORI
                $persen_dp = isset($src_rbs[$kp_key]) && $src_rbs[$kp_key]['persen'] * 1 > 0 ? number_format((float)$src_rbs[$kp_key]['persen'], 2, '.', '') : 0;
                $maxim_dp = isset($src_rbs[$kp_key]) && $src_rbs[$kp_key]['maxim'] * 1 > 0 ? number_format((float)$src_rbs[$kp_key]['maxim'], 0, '.', '') : 0;
                $nilai_dp = isset($src_rbs[$kp_key]) && $src_rbs[$kp_key]['nilai'] * 1 > 0 ? number_format((float)$src_rbs[$kp_key]['nilai'], 0, '.', '') : 0;
                // $nilai_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai'], 0, '.', '') : 0;
                // $nilai_plus_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai_plus'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai_plus'], 0, '.', '') : 0;

                /* ---------------------------------------------------------------------------------
                 * bila ada nilai absolut gukakan klo tdk ada hitungkan dr persen setting per supplier
                 * ---------------------------------------------------------------------------------*/
                // if ($metode_dpp_berjalan == true) {
                //     if ($no_kolom == 1) {
                //         $nilai_dp_calc = ($persen_dp / 100) * $hrg_pp_nppn; // dpp berjalan
                //
                //         $hpp_supplier_2 = $hrg_pp_nppn - $nilai_dp_calc;
                //
                //         // cekHere("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                //     }
                //     else {
                //         $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2;
                //         $hpp_supplier_2 = $hpp_supplier_2 - $nilai_dp_calc;
                //
                //         // cekHere(__LINE__ . " $hpp_supplier_2");
                //         // cekHijau("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                //     }
                //
                //     // $nilai_dp = isset($src_dps[$kp_key]) ? $src_dps[$kp_key]['nilai'] : $nilai_dp_calc;
                //     if (isset($src_dps[$kp_key])) {
                //         $nilai_dp = $src_dps[$kp_key]['nilai'];
                //     }
                //     else {
                //         $nilai_dp = $nilai_dp_calc;
                //         // menulis ke setting diskon supplier
                //         if (isset($cCode) && ($cCode != null)) {
                //             $arrAddData[$prod_id][$kp_id] = array(
                //                 "per_supplier_diskon_id" => $kp_id,
                //                 "per_supplier_diskon_nama" => $kp_key,
                //                 "persen" => $persen_dp,
                //                 "nilai" => $nilai_dp,
                //                 "produk_id" => $prod_id,
                //                 "supplier_id" => $spl_id,
                //                 "status" => 1,
                //             );
                //         }
                //     }
                // }
                // else {
                //     $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_0;
                //
                //     $nilai_dp = $nilai_dp_calc;
                // }

                // cekHere("$nilai_dp_0");
                // $nilai_dp_f =  round($nilai_dp);
                // $nilai_dp_f = $nilai_dp_0 > 0 ? round($nilai_dp_0) : round($nilai_dp);
                $dpp_berjalan = "<br><input type='text' class='text-right form-control shadow_nilai hidden' style='width: 85px;' id='$iddppnilai' value='$hpp_supplier_2'>";
                // cekHijau("$nilai_dp_f");
                // $total_nilai_dp0 += $nilai_dp_f;
                // $nilai_dp_npph = $nilai_plus_dp_0 > 0 ? round($nilai_plus_dp_0) : $nilai_dp / 1.15;
                // cekHijau("$nilai_dp_npph");
                // $nilai_dp_dpp_f = number_format($nilai_dp_npph);
                // $nilai_dp_npph_f = round($nilai_dp_npph);
                // $nilai_dp_dpp_f = ($nilai_dp_npph);
                $item__persen = "<input id='$idpersen' class='text-right form-control' style='background-color: #97ebff;' type='number' max='100' min='0' step='1'  onblur=\"trigger_rebate_qty('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'persen');\" value='$persen_dp'>";
                $item__nilai = "<input id='$idnilai' class='text-right form-control' style='background-color: #ffa74a;' type='number' max='100' min='0' step='1' onblur=\"trigger_rebate_qty('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp'>";

                $item_array[$kp_key] = "<input id='$idmaxim' class='text-right form-control' style='background-color: #63ff5a;' type='number' max='100' min='0' step='1' onblur=\"trigger_rebate_qty('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'maxim');\" value='$maxim_dp'> $item__nilai $item__persen ";
                // $item_array[$kp_key . "_o"] = "$nilai_dp_npph_f";

                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_nama'] = $kp_key;
                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_id'] = $kp_id;
                $dataDiskonPembelian[$kp_key]['persen'] = $persen_dp;
                $dataDiskonPembelian[$kp_key]['nilai'] = $nilai_dp;
                $dataDiskonPembelian[$kp_key]['supplier_id'] = $spl_id;
                $dataDiskonPembelian[$kp_key]['status'] = 1;
            }
            $total_nilai_dp = $total_nilai_dp0 + $dp_freeproduk_nilai; // termausk free produk
            // cekHere("$total_nilai_dp = $total_nilai_dp0 + $dp_freeproduk_nilai;");
            /*------------absolut----------------*/
            foreach ($kolomDiskonAbsolutPembelians as $kp_key => $kp_label) {

                $no_kolom++;
                $kp_id = $kolomDiskonPembeliansId[$kp_key];
                $setting_persen = $src_dpersupplier[$kp_id]["persen"];

                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $iddppnilai = $kp_key . "_dpp_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp

                //                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen']*1>0 ? round($src_dps[$kp_key]['persen']) : $setting_persen;//ORI
                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['persen'], 2, '.', '') : 0;
                $nilai_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai'], 0, '.', '') : 0;
                $nilai_plus_dp_0 = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['nilai_plus'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['nilai_plus'], 0, '.', '') : 0;

                /* ---------------------------------------------------------------------------------
                 * bila ada nilai absolut gukakan klo tdk ada hitungkan dr persen setting per supplier
                 * ---------------------------------------------------------------------------------*/
                if ($metode_dpp_berjalan == true) {
                    if ($no_kolom == 1) {
                        $nilai_dp_calc = ($persen_dp / 100) * $hrg_pp_nppn; // dpp berjalan

                        $hpp_supplier_2 = $hrg_pp_nppn - $nilai_dp_calc;

                        // cekHere("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }
                    else {
                        $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2;
                        $hpp_supplier_2 = $hpp_supplier_2 - $nilai_dp_calc;

                        // cekHere(__LINE__ . " $hpp_supplier_2");
                        // cekHijau("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }

                    // $nilai_dp = isset($src_dps[$kp_key]) ? $src_dps[$kp_key]['nilai'] : $nilai_dp_calc;
                    if (isset($src_dps[$kp_key])) {
                        $nilai_dp = $src_dps[$kp_key]['nilai'];
                    }
                    else {
                        $nilai_dp = $nilai_dp_calc;
                        // menulis ke setting diskon supplier
                        if (isset($cCode) && ($cCode != null)) {
                            $arrAddData[$prod_id][$kp_id] = array(
                                "per_supplier_diskon_id" => $kp_id,
                                "per_supplier_diskon_nama" => $kp_key,
                                "persen" => $persen_dp,
                                "nilai" => $nilai_dp,
                                "produk_id" => $prod_id,
                                "supplier_id" => $spl_id,
                                "status" => 1,
                            );
                        }
                    }
                }
                else {
                    $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_0;

                    $nilai_dp = $nilai_dp_calc;
                }

                // cekHere("$nilai_dp_0");
                // $nilai_dp_f =  round($nilai_dp);
                $nilai_dp_f = $nilai_dp_0 > 0 ? round($nilai_dp_0) : round($nilai_dp);
                $dpp_berjalan = "<br><input type='text' class='text-right form-control shadow_nilai hidden' style='width: 85px;' id='$iddppnilai' value='$hpp_supplier_2'>";
                // cekHijau("$nilai_dp_f");
                $total_nilai_dp0 += $nilai_dp_f;
                $nilai_dp_npph = $nilai_plus_dp_0 > 0 ? round($nilai_plus_dp_0) : $nilai_dp / 1.15;
                // cekHijau("$nilai_dp_npph");
                // $nilai_dp_dpp_f = number_format($nilai_dp_npph);
                $nilai_dp_npph_f = round($nilai_dp_npph);
                // $nilai_dp_dpp_f = ($nilai_dp_npph);
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'persen');\" value='$persen_dp'>";
                $item__nilainpph = "<input id='$idnilainpph' class='text-right form-control' style='background-color: #69dc39;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilainpph');\" value='$nilai_dp_npph_f'>";
                // $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' style='background-color: #ffeb3b;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $item__nilainpph $dpp_berjalan ";
                $item_array[$kp_key] = "<input id='$idnilai' class='text-right form-control' style='background-color: #ffeb3b;' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $item__nilainpph $dpp_berjalan ";
                $item_array[$kp_key . "_o"] = "$nilai_dp_npph_f";

                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_nama'] = $kp_key;
                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_id'] = $kp_id;
                $dataDiskonPembelian[$kp_key]['persen'] = $persen_dp;
                $dataDiskonPembelian[$kp_key]['nilai'] = $nilai_dp;
                $dataDiskonPembelian[$kp_key]['supplier_id'] = $spl_id;
                $dataDiskonPembelian[$kp_key]['status'] = 1;

            }

            /* ----------------------------------------------------------------------
             * diskon untuk pembayaran kredit note
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $idnilainpph = $kp_key . "_nilainpph_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";
                $item__nilainpph = "<input id='$idnilainpph' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";

                $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$premi_beli'> $item__nilainpph";
            }

            $diskon_pajak = $total_nilai_dp * ($this->pph23 / 100);
            $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak;
            // $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak + $dp_freeproduk_nilai_npph; // termasuk diskon freeproduk
            $hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp); //ORI
            // $hrg_beli = $hrg_beli_be_pph + $diskon_pajak; //ORI
            // $hrg_beli = $hrg_pp_nppn - $total_nilai_dp;
            /* -----------------------------------------
             * dihitung dg dasat hpp tanpa ppn (netto)
             * -----------------------------------------*/
            $hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            // cekHere("$hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;");
            /* -----------------------------------------
             * dihitung dg dasa hpp include ppn sample panasonic mengunkan ini dan dr supplier harga rebate termasuk pph
             * -----------------------------------------*/
            $hrg_beli = $hrg_pp_nppn - $total_nilai_dp_af_tax;
            // cekHere("$hrg_beli = $hrg_pp_nppn - $total_nilai_dp_af_tax;");
            $hrg_beli_be_tax = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            // $hrg_beli_af_tax = $hrg_beli * ((100 + my_ppn_factor()) / 100);
            $hrg_beli_af_tax = $hrg_beli;
            $hrg_beli_npph = ($hrg_pp_nppn - $total_nilai_dp) + $diskon_pajak;
            // cekKuning("$hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp);");
            // cekHijau("$prod_id :: $hpp_supplier - $total_nilai_dp + $diskon_pajak ==== $hrg_beli");
            /*---tandas---*/
            $item_array["harga_beline_be_pph"] = $hrg_beli_be_pph;
            $item_array["total_nilai_dp"] = $total_nilai_dp;
            $item_array["total_nilai_dp_af_tax"] = $total_nilai_dp_af_tax;
            $item_array["harga_pajak_beline"] = $diskon_pajak;
            // ------------------------tandas
            $item_array["harga_beline"] = $hrg_beli;
            $item_array["harga_beline"] = $hrg_beli_be_tax;
            // $item_array["harga_beline_af_tax"] = $hrg_beli_npph;
            $item_array["harga_beline_af_tax"] = $hrg_beli_af_tax;
            $item_array["harga_beli"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_beli'>";

            /*---validasi diskon----*/
            $gr_persen_1 = "";
            $diskon_cek = 0;
            $diskon_ygbener = 0;
            $grosir_cek = "no";
            if (isset($produk_grosir[$prod_id])) {
                // cekMerah($hrg_list);
                $data_grosiers = $produk_grosir[$prod_id];
                $gr_persen_1 = isset($data_grosiers['persen_1']) ? $data_grosiers['persen_1'] : 0;

                $gr_nilai_1a = isset($data_grosiers['nilai_1']) ? $data_grosiers['nilai_1'] : 0;
                $diskon_nilai_1 = $dk->calcDiskon($hrg_list, array("satu" => $gr_persen_1));
                // cekHere("id:$prod_id: $gr_nilai_1a");
                // arrPrint($diskon_nilai_1);
                $gr_nilai_1 = isset($data_grosiers['nilai_1']) ? $diskon_nilai_1['nilai'] : 0;

                $nilai_1_calc = $hrg_list * ($gr_persen_1 / 100);
                $nilai_1_calc_f = round($nilai_1_calc);
                // cekBiru("$nilai_1_calc_f");
                // arrPrintPink($produk_grosir[$prod_id]);


                $diskon_cek = $nilai_1_calc_f != $gr_nilai_1 ? 1 : 0;

                if ($diskon_cek == 1) {
                    $diskon_ygbener = ($gr_nilai_1 / $hrg_list) * 100;
                    $harga_ygbener = $hrg_list - $gr_nilai_1;

                    $dg_condites = array(
                        "produk_id" => $prod_id,
                        "urutan" => 1,
                        "trash" => 0,
                        "status" => 1,
                        "jenis" => "produk_grosir",
                        "toko_id" => my_toko_id(),
                    );
                    $dg_datas = array(
                        "persen" => $diskon_ygbener,
                        "harga" => $harga_ygbener,
                    );
                    // $dg->setTableName("diskon");
                    // $dg->updateData($dg_condites, $dg_datas);
                    // showLast_query("merah");
                    if ($prod_id == "9076") {
                        arrPrintKuning($dg_datas);
                        // matiHere(__LINE__);
                    }
                    $grosir_cek = "yes";
                }

                // arrPrintKuning($data_grosiers);
            }

            $item_array["diskon_cek"] = $diskon_cek;
            $item_array["diskon_ygbener"] = $diskon_ygbener;
            $item_array["grosir_cek"] = $grosir_cek;

            //            $item_array["harga_beli"] = $harga_beli;
            //            $item_array["biaya_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-biaya_beli'  type='number' max='100' min='0' step='1' value='$biaya_beli'>";
            //            $item_array["biaya_beli"]   = $biaya_beli;
            //            $item_array["diskon_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-diskon_beli' type='number' max='100' min='0' step='1' value='$diskon_beli'>";
            //            $item_array["diskon_beli"]  = $diskon_beli;
            //            $item_array["premi_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-premi_beli'  type='number' max='100' min='0' step='1' value='$premi_beli'>";
            //            $item_array["premi_beli"]   = $premi_beli;
            $drosirs = isset($produk_grosir[$prod_id]) ? $produk_grosir[$prod_id] : array();
            $src_pr[$prod_id] = $item_array + $drosirs;

            /* --------------------------------------------
             * update ke diskon per produk dikirim dari komponen tandas
             * --------------------------------------------*/
            $diskonPembelians[$prod_id] = $dataDiskonPembelian;

        }

        if (sizeof($arrAddData) > 0) {
            $this->db->trans_start();

            foreach ($arrAddData as $produk_id => $subSpec) {
                foreach ($subSpec as $disk_id => $subData) {
                    $dp = new MdlDiskonPembelian();
                    $dp->addData($subData);
                    //                    showLast_query("hijau");
                }
            }
            //                matiHere("belum comit " . __LINE__);
            $this->db->trans_complete();
        }
        if (isset($cCode) && ($cCode != null)) {
            $this->iterasiGerbangItem($cCode);
        }


        /* ------------------------------------------------------------
         * $dp
         * untuk ngupdate diskon pembelian per-produk
         * ------------------------------------------------------------*/
        // $this
        // $cek = $dp->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintPink($diskonPembelians);
        // ------------------------------------------------------------end----


        /* ---------------------
         * dta produk per supplier
         * ---------------------*/

        $vendor = false;
        if ($vendor == true) {
            $this->load->model("Mdls/MdlProdukPerSupplier");
            $pps = new MdlProdukPerSupplier();
            if (isset($_GET['suppliers_id'])) {
                $condites = array(
                    "suppliers_id" => $_GET['suppliers_id'],
                );
                $this->db->where($condites);
            }
            $src_pps_0 = $pps->lookupAll()->result();// showLast_query("kuning");
            // arrPrint($src_pps_0);
            foreach ($src_pps_0 as $src_pp) {
                $suppliers_id = $src_pp->suppliers_id;
                $produk_id = $src_pp->produk_id;

                $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
                // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
                $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
            }
        }

        $arrHeaders_01 = array(
            "id" => array(
                "label" => "pid",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
                "links" => array(
                    // "modal_size" => "modal-xl",
                    "target" => "diskon/Setting/viewHistory",
                    "title" => "History ",
                    "title_head_key" => "nama",
                    "key" => "id",
                ),
            ),
            // "grosir_cek" => array(
            //     "label" => "grosir",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "barcode" => array(
                "label" => "barcode",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "nama" => array(
                "label" => "nama produk",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            // "satuan" => array(
            //     "label" => "satuan",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "merek_nama" => array(
                "label" => "merek",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "kategori_nama" => array(
                "label" => "kat",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
        );

        // -----------------------------------------beli-beli----------------------------------
        $arrHeaders_02 = array(
            "harga_beli_0" => array(
                "label" => "harga list",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            // "diskon_00"         => array(
            //     "label"       => "free produk",
            //     "attr_header" => "class='bg-danger'",
            //     "attr"        => "class='text-right bg-danger'",
            //     "top_parent"  => "harga_beli",
            //     "data_order"  => "diskon_00_setting",
            // ),
            "diskon_0" => array(
                "label" => "diskon 0",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_be_tax" => array(
                "label" => "dpp",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_af_tax" => array(
                "label" => "incl. ppn",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
        );

        /* -----------------------------------------------------------
         * komponen pembentuk tandas rebate
         * -----------------------------------------------------------*/
        // $arrHeaders_02["diskon_00"] = array(
        //     "label" => "free produk",
        //     "attr_header" => "class='bg-warning'",
        //     "attr" => "class='text-right bg-warning'",
        //     "top_parent" => "pembelian",
        //     "data_order" => "diskon_00_setting",
        // );
        foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-warning'",
                "attr" => "class='text-right bg-warning'",
                "top_parent" => "pembelian",
                // "data_order"  => false,
                "data_order" => $kp_key . "_o",
            );
        }

        /*---rebate absolud----*/
        foreach ($kolomDiskonAbsolutPembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "top_parent" => "pembelian_abs",
                // "data_order"  => false,
                "data_order" => $kp_key . "_o",
            );
        }

        // foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
        //     $arrHeaders_02[$kp_key] = array(
        //         "label" => "$kp_label",
        //         "attr_header" => "class='bg-info'",
        //         "attr" => "class='text-right bg-info'",
        //         "top_parent" => "pembelian",
        //         "data_order" => false,
        //     );
        // }

        // "diskon_beli"   => array(
        //     "label"  => "diskon pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "premi_beli"    => array(
        //     "label"  => "premi pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "biaya_beli"    => array(
        //     "label"  => "biaya pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),

        /* ----------------------------------------------
         * disembunyikan ngikuti kolom dari client
         * ----------------------------------------------*/
        // $arrHeaders_02["total_nilai_dp"] = array(
        //     "label" => "total rebate sb. pph",
        //     "attr_header" => "class='bg-danger'",
        //     "attr" => "class='text-right bg-danger'",
        //     "format" => "formatField_he_format",
        //     "format_key" => "harga",
        //     "top_parent" => "pembelian",
        //     "data_order" => "total_nilai_dp",
        // );
        // $arrHeaders_02["harga_pajak_beline"] = array(
        //     "label" => "pph23",
        //     "attr_header" => "class='bg-danger'",
        //     "attr" => "class='text-right bg-danger'",
        //     "format" => "formatField_he_format",
        //     "format_key" => "harga",
        //     "top_parent" => "pembelian",
        //     "data_order" => "harga_beline",
        // );
        //
        // $arrHeaders_02["total_nilai_dp_af_tax"] = array(
        //     "label" => "total rebate st.&nbsp;pph",
        //     "attr_header" => "class='bg-danger'",
        //     "attr" => "class='text-right bg-danger'",
        //     "format" => "formatField_he_format",
        //     "format_key" => "harga",
        //     "top_parent" => "pembelian",
        //     "data_order" => "total_nilai_dp",
        // );
        // $arrHeaders_02["harga_beline"] = array(
        //     "label" => "harga tandas w/o ppn",
        //     "attr_header" => "class='bg-danger'",
        //     "attr" => "class='text-right bg-danger'",
        //     "format" => "formatField_he_format",
        //     "format_key" => "harga",
        //     "top_parent" => "pembelian",
        //     "data_order" => "harga_beline",
        // );
        // $arrHeaders_02["harga_beline_af_tax"] = array(
        //     "label"       => "harga tandas inc. ppn",
        //     "attr_header" => "class='bg-danger'",
        //     "attr"        => "class='text-right bg-danger'",
        //     "format"      => "formatField_he_format",
        //     "format_key"  => "harga",
        //     "top_parent"  => "pembelian",
        //     "data_order"  => "harga_beline_af_tax",
        // );

        // -----------------------------------------jual-jual----------------------------------
        $arrHeaders_03 = array();
        // $arrHeaders_03 = array(
        //     // "margin"        => array(
        //     //     "label"  => "margin (%)",
        //     //     "attr"   => "class='text-right'",
        //     //     "format" => "formatField_he_format",
        //     // ),
        //     /*---penjualan---*/
        //     "ppn" => array(
        //         "label" => "ppn",
        //         "attr_header" => "class='bg-olive-active'",
        //         "attr" => "class='text-right bg-olive-active'",
        //         "format" => "formatField_he_format",
        //         "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "ppn",
        //     ),
        //     "harga_jual_online_nilai" => array(
        //         "label" => "online non ppn",
        //         "attr_header" => "class='bg-aqua'",
        //         "attr" => "class='text-right bg-aqua'",
        //         "format" => "formatField_he_format",
        //         "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "harga_jual_online_nilai",
        //     ),
        //     "harga_jual_online_nppn" => array(
        //         "label" => "online incl. ppn",
        //         "attr_header" => "class='bg-aqua'",
        //         "attr" => "class='text-right bg-aqua'",
        //         "format" => "formatField_he_format",
        //         "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "harga_jual_online_nilai",
        //     ),
        //     //---------------------
        //     "harga_jual" => array(
        //         "label" => "end user non ppn",
        //         "attr_header" => "class='bg-aqua'",
        //         "attr" => "class='text-right bg-aqua'",
        //         "format" => "formatField_he_format",
        //         // "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "harga_jual",
        //     ),
        //     "harga_list_nppn" => array(
        //         "label" => "end user incl. ppn",
        //         "attr_header" => "class='bg-aqua'",
        //         "attr" => "class='text-right bg-aqua'",
        //         "format" => "formatField_he_format",
        //         // "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "harga_jual",
        //     ),
        //     //----------
        //     "harga_jual_reseller" => array(
        //         "label" => "dealer non ppn",
        //         "attr_header" => "class='bg-aqua'",
        //         "attr" => "class='text-right bg-aqua'",
        //         "format" => "formatField_he_format",
        //         // "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "harga_jual_reseller",
        //     ),
        //     "harga_list_reseller_nppn" => array(
        //         "label" => "dealer incl. ppn",
        //         "attr_header" => "class='bg-aqua'",
        //         "attr" => "class='text-right bg-aqua'",
        //         "format" => "formatField_he_format",
        //         // "format_key" => "harga",
        //         "top_parent" => "harga_list",
        //         "data_order" => "harga_jual_reseller",
        //     ),
        //     // ------------------------------------------
        //     // "diskon_persen"     => array(
        //     //     "label"      => "persen",
        //     //     "attr_header" => "class='bg-purple'",
        //     //     "attr"       => "class='text-right bg-purple'",
        //     //     "top_parent" => "simpel",
        //     //     // "top_sub_parent" => "simpel",
        //     //     "data_order" => "diskon_persene",
        //     // ),
        //     // "diskon_nilai"     => array(
        //     //     "label"      => "nilai",
        //     //     "attr_header" => "class='bg-purple'",
        //     //     "attr"       => "class='text-right bg-purple'",
        //     //     "top_parent" => "simpel",
        //     //     // "top_sub_parent" => "simpel",
        //     //     "data_order" => "diskon_nilaine",
        //     // ),
        //     // ---------------------------
        //     "premi_jual" => array(
        //         "label" => "persen",
        //         "attr_header" => "class='bg-teal'",
        //         "attr" => "class='text-right bg-teal'",
        //         "top_parent" => "simpel",
        //         // "top_sub_parent" => "simpel",
        //         // "data_order" => "premi_jual",
        //         "data_order" => "premi_juale",
        //     ),
        //     "premi_jual_nilai" => array(
        //         "label" => "nilai",
        //         "attr_header" => "class='bg-teal'",
        //         "attr" => "class='text-right bg-teal'",
        //         "top_parent" => "simpel",
        //         // "top_sub_parent" => "simpel",
        //         "data_order" => "premi_jual_nilaine",
        //     ),
        //     // "biaya_jual"        => array(
        //     //     "label"      => "biaya penjualan",
        //     //     "attr"       => "class='text-right bg-danger'",
        //     //     "top_parent" => "simpel",
        //     //     "data_order" => "biaya_jual_nilai",
        //     // ),
        //     "harga_aft" => array(
        //         "label" => "harga absolut",
        //         "attr" => "class='text-right bg-danger'",
        //         "format" => "formatField_he_format",
        //         "format_key" => "harga",
        //         "top_parent" => "simpel",
        //     ),
        //     "harga_aft_nppn" => array(
        //         "label" => "harga absolut incl. ppn",
        //         "attr" => "class='text-right bg-danger'",
        //         "format" => "formatField_he_format",
        //         "format_key" => "harga",
        //         "top_parent" => "simpel",
        //         "data_order" => "harga_aft_nppn",
        //     ),
        //
        //     // "diskon_ygbener" => array(
        //     //     "label"      => "diskon_ygbener",
        //     //     "attr"       => "class='text-right bg-danger'",
        //     //     "format"     => "formatField_he_format",
        //     //     "format_key" => "dikson",
        //     //     "top_parent" => "simpel",
        //     // ),
        //
        // );

        $arrHeaders = $arrHeaders_01 + $arrHeaders_02 + $arrHeaders_03;

        /*---grosir---*/
        $data_grosir = false;
        if ($data_grosir == true) {
            for ($i = 1; $i <= $maxGrosir; $i++) {

                $bg_warna = $i % 2 == 0 ? "bg-warning" : "bg-success";

                $arrHeaders["minim_" . $i] = array(
                    "label" => "minimal<br>#$i",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "top_parent" => "grosir",
                );
                $arrHeaders["persen_" . $i] = array(
                    "label" => "potongan<br>#$i (%)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "diskon",
                    "top_parent" => "grosir",
                );
                $arrHeaders["nilai_" . $i] = array(
                    "label" => "potongan<br>#$i (Rp)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "top_parent" => "grosir",
                );
            }
        }
        /*button*/
        // $arrHeaders["grosir"] = array(
        //     "label" => "tindakan",
        //     "attr" => "class='text-right'",
        //     "data_order" => false,
        //     // "top_parent" => "grosir",
        // );

        // --------------------------------------------------------------------
        $arrHeaderParents = array(
            "pembelian" => array(
                "label" => "Rebates qyt",
                "attr_header" => "class='bg-warning'",
            ),
            "pembelian_abs" => array(
                "label" => "Rebates absolud",
                "attr_header" => "class='bg-info'",
            ),
            "harga_beli" => array(
                "label" => "harga beli <r>wajib diisi</r>",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_list" => array(
                "label" => "harga list <r> wajib diisi</r>",
                "attr_header" => "class='bg-aqua'",
            ),
            "simpel" => array(
                "label" => "premium",
                "attr_header" => "class='bg-blue'",
            ),
            "grosir" => array(
                "label" => "diskon berjenjang",
                "attr_header" => "class='bg-success'",
            ),
        );

        $data = array(
            "mode" => "viewProdukRebate",
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            "title" => "Setting Rebate",
            "subTitle" => "-",
            "arrHeaderParents" => $arrHeaderParents,
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            "is_po" => $is_po,
            "cCode" => isset($cCode) ? $cCode : "",
            "urlBack" => isset($urlBack) ? $urlBack : "",
            "pph23" => $this->pph23,
            "ppn" => my_ppn_factor(),
            "srcMereks" => $srcMereks,
            "supplier_id" => $supplier_id,
            // "grosir_header"        => $grosir_header,
            // "grosir_data"          => $src_dg,
            // "level_header"         => $level_header,
            // "level_data"           => $src_clevel_diskons,
            // "level_data"           => array(),
            // "jenisTransaksi"       => $jenisTr,
            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
            // "template"             => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "isMobile"             => $isMob,
        );

        //arrPrint($data);

        $this->load->view("setting", $data);

    }

    public function viewRebate_ori()
    {
        $ppn_persen_set = my_ppn_factor();
        // matiHere();
        // arrPrintHijau(url_segment());
        $jenis = $mode = url_segment(4);
        // arrPrint($_GET);
        /* --------------------------------------
                 * grosir
                 * --------------------------------------*/
        // echo my_toko_id();
        // cekMerah(my_cabang_id());
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dg = new MdlDiskonPembelianSupplier();
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $condites = array(
                "supplier_id" => $prod_id,
                "jenis" => $jenis,
            );
            // $this->db->where($condites);
        }
        $dg->setTokoId(my_toko_id());
        $src_dg_obj_0 = $dg->lookupByCondition($condites)->result();
        // showLast_query("kuning");
        // arrPrintKuning($src_dg_obj_0);
        $src_dg_obj = array();
        foreach ($src_dg_obj_0 as $item_obj) {
            $dtime_start = $item_obj->dtime_start;

            if ($dtime_start == null) {
                $src_dg_obj[] = $item_obj;
            }
        }
        /*-----------produk speks------------*/
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $prod_speks = $pr->callSpecs($prod_id);
        // arrPrint($prod_speks);
        // $premi_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->premi_jual * 1 : 0;
        // $diskon_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->diskon_persen * 1 : 0;
        // $harga_jual = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->harga_jual * 1 : 0;
        /*-----------produk harga------------*/
        // $this->load->model("Mdls/MdlHargaProduk");
        // $hp = new MdlHargaProduk();
        // $hp->setTokoId(my_toko_id());
        // // $hp->setCabangId(my_cabang_id());
        // $hp->setCabangId($this->cabang_id);
        // $prod_hargas = $hp->callSpecs($prod_id);
        // showLast_query("kuning");
        // arrPrint($prod_hargas);
        // $harga_speks = array();
        // if (isset($prod_hargas[$prod_id])) {
        //     foreach ($prod_hargas[$prod_id] as $spek_harga) {
        //         $harga_speks[$spek_harga->jenis_value] = $spek_harga;
        //     }
        // }

        // $this->load->library("Diskon");
        // $dk = new Diskon();

        // arrPrint($harga_speks[$this->harga_jenis]->nilai);
        // arrPrint($harga_speks);
        // $harga_list = isset($harga_speks[$this->harga_jenis]) ? $harga_speks[$this->harga_jenis]->nilai * 1 : 0;
        // $harga_list_f = format_harga($harga_list);
        //
        // $harga_list_ppn = $harga_list * ((100 + $ppn_persen_set) / 100);
        // $harga_list_ppn_f = format_harga($harga_list_ppn);
        //
        // // cekHijau($harga_list);
        // $harga_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;
        //
        // $hrg_jual = $harga_list + (($premi_persen / 100) * $harga_list);
        // $hrg_jual_f = formatField_he_format("harga", $hrg_jual);
        //
        // $hrg_jual_ppn = $hrg_jual * ((100 + $ppn_persen_set) / 100);
        // $hrg_jual_ppn_f = formatField_he_format("harga", $hrg_jual_ppn);
        //
        //
        // $harga_beli_f = formatField_he_format("harga", $harga_beli);
        $jml_grosir = sizeof($src_dg_obj);
        // $type_awal = $jml_grosir == 0 ? "text" : "text";
        // $diskon_satu = $dk->calcDiskon($hrg_jual, array("satu" => $diskon_persen), array());
        // // arrPrint($diskon_satu);
        // $diskon_nilai = $diskon_satu['nilai'];
        // $hrg_jual_diskon = $diskon_satu['harga_af'];
        // $hrg_jual_diskon_ppn = $hrg_jual_diskon * ((100 + $ppn_persen_set) / 100);
        //
        $str = "";
        $str .= "<style type='text/css'>
                .form-control {
                    margin-top: 1px;
                    padding: 0 5px !important;
                    height: 30px !important;
                }
            </style>";

        // $str .= "<div class='row' style='margin-bottom: 20px;'><div class='col-md-8'>";
        // $str .= "<h5 class='text-uppercase' style='margin-left: 15px;'>harga beli $harga_beli_f</h5>";
        // //        $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan $hrg_jual_f</h4>";
        // //        $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list: Rp. $harga_list_f | premi: $premi_persen%</p>";
        // $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan <span class='meta'>include PPN</span> $hrg_jual_ppn_f</h4>";
        // $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list <span class='meta'>include PPN</span>: Rp. $harga_list_ppn_f | premi: $premi_persen%</p>";
        // $str .= "</div></div>";

        $str .= "<div class='row'>";
        $str .= "<div class='col-lg-3'><div class='input-group marginn'>Campaign System<input type='$type_awal' id='jml_222' disabled class='form-control text-center' value='0'></div></div>";
        $str .= "<div class='col-lg-2'><div class='input-group marginn'>Rebate (%)<input type='$type_awal' id='persen_222' disabled class='form-control' value='0'></div></div>";
        // $str .= "<div class='col-lg-2'><div class='input-group marginn'>diskon (Rp)<input type='$type_awal' id='nilai_222' disabled class='form-control' value='$diskon_nilai'></div></div>";
        //        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        //        $str .= "<div class='col-xs-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";

        //        $str .= "<div class='col-lg-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        // $str .= "<div class='col-lg-2'><div class='input-group marginn'>Harga (incl. PPN)<input type='$type_awal' id='_harga_222' disabled class='form-control' value='" . number_format($hrg_jual_diskon_ppn, 0) . "'></div></div>";
        //        $str .= "<div class='col-lg-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
        // $str .= "<div class='col-lg-2'><div class='input-group marginn'>grosir (incl. PPN)<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='" . number_format($hrg_jual_diskon_ppn, 0) . "'></div></div>";

        $str .= "</div>";

        // $str .= "<div class='col-xs-12'>----</div>";

        $cont = 222;
        $cont_data = $cont + $jml_grosir + 1;
        // cekHere($cont_data);
        $ix = '-1';
        // arrPrint($src_dg_obj);
        $url_action = base_url() . "diskon/Setting/doSaveRebate/$mode";
        $str .= "<form method='post' action='$url_action' target='result'>";
        for ($i = 1; $i <= 5; $i++) {

            $cont++;
            $ix++;
            $item = isset($src_dg_obj[$ix]) ? $src_dg_obj[$ix] : (object)array();
            // arrPrintHijau($item);

            $id_data = isset($item->id) ? $item->id : "";
            $jml_id = "jml_$cont";
            $persen_id = "persen_$cont";
            $nilai_id = "nilai_$cont";
            $harga_id = "harga_$cont";
            $grosir_id = "grosir_$cont";
            $harga_ppn_id = "harga_ppn_$cont";
            $grosir_ppn_id = "grosir_ppn_$cont";

            // $minim = isset($item->minim) ? $item->minim : 0;
            $maxim = isset($item->maxim) ? $item->maxim : 0;
            $persen = isset($item->persen) ? $item->persen * 1 : 0;
            $nilai = isset($item->nilai) ? $item->nilai * 1 : 0;
            $persen_f = number_format($persen, 2);
            $harga = isset($item->harga) ? $item->harga * 1 : 0;
            $disabled = $maxim == 0 ? "disabled" : "";

            // $hrg_jual_ppn_x = $harga;
            // $hrg_jual_ppn_x = $hrg_jual_ppn / ((100 + $ppn_persen_set) / 100);
            // $diskon_loop = $dk->calcDiskon($hrg_jual, array("satu" => $persen), array());
            // $diskon_loop = $dk->calcPotongan($hrg_jual, $nilai);
            // cekHijau("$nilai");
            // $diskon_loop = $dk->calcPotongan($hrg_jual_ppn_x, $nilai);

            // arrPrint($diskon_loop);

            // $d_nilai = ($diskon_loop['nilai'] * 1) * ((100 + $ppn_persen_set) / 100);
            // // $d_nilai = ($diskon_loop['nilai'] * 1);
            // $harga_be = $diskon_loop['harga_be'];
            // $harga_af = $diskon_loop['harga_af'] * 1;
            // $grosir_af = $harga_af * $minim;
            //
            // $harga_ppn_af = $harga_af * ((100 + $ppn_persen_set) / 100);
            // $grosir_ppn_af = $grosir_af * ((100 + $ppn_persen_set) / 100);
            // $harga_ppn_af = $harga_af;
            // $grosir_ppn_af = $grosir_af;
            // cekHere("$harga_ppn_af = $harga_af * ((100 + $ppn_persen_set) / 100);");

            $f_pembulat = 100;
            $link_delete = base_url() . "diskon/Setting/doDeleteRebate?id=$id_data&id_row=$cont";

            $str .= "<div class='row'>";
            // $str .= "<div class='col-xs-3'><div class='input-group marginn'><span class='input-group-btn'><button type='button' class='btn btn-default'>&#8805;</button></span><input type='text' id='$jml_id' class='form-control text-center' name='minim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$minim'></div></div>";
            /*----balikan 8805*/
            $str .= "<div class='col-xs-3'><div class='input-group marginn'><span class='input-group-btn'>
<button type='button' class='btn btn-default'>&#8804;</button></span>
<input type='text' id='$jml_id' class='form-control text-center' name='maxim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$maxim'></div></div>";
            $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen_f'></div></div>";
            // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($d_nilai) . "'></div></div>";
            // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='harga[]' id='$harga_ppn_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($harga_ppn_af, 0) . "'> </div></div>";

            // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='harga[]' id='$grosir_ppn_id' class='form-control' autocomplete='off' disabled onclick=\"this.select()\" value='" . number_format($grosir_ppn_af, 0) . "'> </div></div>";
            $str .= "<div class='col-xs-1'><div class='input-group marginn'><button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button></div></div>";
            $str .= "</div>";

            $str .= "<script type='text/javascript'>
                        // var jml_data = $jml_grosir;
                        var cont_data = $cont_data;
                        var cont_be = $cont -1;
                        // var harga = $hrg_jual;
                        // var harga_ppn = $hrg_jual_ppn;
                            $('#jml_'+cont_data).prop('disabled', false);
                        // if(harga > 100){
                        //
                        //     // $('#$jml_id').prop('disabled', true);
                        //     $('#$jml_id').prop('readonly', true);
                        //     $('#jml_'+cont_data).prop('readonly', false);
                        // }                            
                                               
                        /*----membuka field diskon persen dan nilai---*/
                        $('#jml_$cont').keydown(function() {
                            $('#persen_$cont').prop('disabled', false);
                            // $('#nilai_$cont').prop('disabled', false);
//                            $('#harga_$cont').prop('disabled', false);
                        });
                        
                        /*-----validasi jml harus lebih besar dg jml sebelumnya-- dan nilai diskon harus > sebelumnya--*/
                        $('#jml_$cont').keyup(function() {
                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml_be = $('#jml_'+ cont_be).val();
                                var jml_now = $('#jml_$cont').val();
                                // var nilai_be = $('#nilai_'+ cont_be).val();
                                // var nilai_now = $('#nilai_$cont').val();
                                // var harga = $('#harga_$cont').val();
                                // var grosir = $('#grosir_$cont').val();
                                // var grosir_baru = jml_now * harga;
                                
                                // var harga_ppn = removeCommas($('#harga_ppn_$cont').val());
                                // var grosir_ppn = $('#grosir_ppn_$cont').val();
                                // var grosir_ppn_baru = addCommas( (jml_now*harga_ppn).toFixed(4) );
                                
                                if(Number(jml_now) <= Number(jml_be)){
                                    
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                    });
                                    
                                    $('#persen_$cont').prop('disabled', true);
                                    // $('#nilai_$cont').prop('disabled', true);  
                                    $('#jml_$cont').css('color','red');
                                    $('#btn_simpan').prop('disabled', true);
                                            
                                }
                                else {
                                    $('#jml_' + cont_af).prop('disabled', true);     
                                    $('#jml_$cont').css({'background-color': 'yellow','color':'red'});
                                    $('#persen_$cont').css('color','');
                                    // $('#nilai_$cont').css('color','');
                                    // $('#harga_$cont').css('color','');
                                    // $('#grosir_$cont').val(grosir_baru).css({'background-color': 'yellow','color':'green'}); 
                                    
                                    // $('#harga_ppn_$cont').css('color','');
                                    // $('#grosir_ppn_$cont').val(grosir_ppn_baru).css({'background-color': 'yellow','color':'green'}); 
                                    
                                    // if(Number(nilai_now) > Number(nilai_be)){                                       
                                    //     $('#btn_simpan').prop('disabled', false);
                                    //     $('#jml_' + cont_af).prop('disabled', false);
                                    // }
                                    // else {
                                        // $('#btn_simpan').prop('disabled', true);
                                        
                                        // swal({
                                        //     title: 'Upsss.. !!',
                                        //     html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                        // });
                                    // }
                                    $('#btn_simpan').prop('disabled', false);
                                }
                            }, 2000);     
                        });
                                                                                                                                               
                    </script>";
            $str .= "<script type='text/javascript'>
                        /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/
                        $('#persen_$cont').keyup(function() {
                            setTimeout(function(){
                                // var harga = $hrg_jual;
                                // var hpp = $harga_beli;
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var persen_be = $('#persen_'+ cont_be).val();
                                var persen_now = $('#persen_$cont').val();
                                // var nilai_diskon = harga * (persen_now / 100);
                                // var persen_max = $harga_beli;
                                // var harga_baru = harga - nilai_diskon;
                                // var rugilaba = harga_baru - hpp;
                                
                                // console.log(persen_now +' *<=* '+ persen_be + ' *** ' + cont_be);
                                if(Number(persen_now) <= Number(persen_be)){
                                    // console.log('ahah');                                    
                                        swal({
                                            title: 'Upsss.. !!',
                                            html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                        });
                                        
                                        $('#btn_simpan').prop('disabled', true);
                                        $('#persen_$cont').css('color','red');
                                        $('#nilai_$cont').css('color','red');
                                        $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                        $('#persen_$cont').css('color','');
                                        $('#nilai_$cont').css('color','');
                                        $('#btn_simpan').prop('disabled', false);
                                }
                                                                  
                                                                
                                $('#persen_$cont').css({'color':'red','background-color':'yellow'});
                                // $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                                // $('#harga_$cont').css({'color':'green','background-color':'yellow'});
                                // $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                                $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                
                            }, 2000);
                        });

                         /*-----validasi diskon nilai harus lebih besar dg yang sebelumnya----*/
//                         $('#nilai_$cont').keyup(delay_v2(function(){
// //                            setTimeout(function(){
//                             // delay_v2(function(){
//                                 var harga = $hrg_jual;
//                                 var hpp = $harga_beli;
//                                 var cont_be = $cont - 1;
//                                 var cont_af = $cont + 1;
//                                 var nilai_be = $('#nilai_'+ cont_be).val();
//                                 var nilai_now = removeCommas($('#nilai_$cont').val());
//                                 var jml = $('#jml_$cont').val();
//                                 var harga_baru = harga - nilai_now;
//                                 var rugilaba = harga_baru - hpp;
//                                 var grosir = harga_baru * jml;
//
//                                 var harga_ppn = (harga*1.11)-nilai_now;
//                                 var grosir_ppn_baru = addCommas( (jml*harga_ppn).toFixed(4) );
//
//                                 $('#grosir_$cont').val(grosir);
//                                 $('#grosir_ppn_$cont').val(grosir_ppn_baru).css({'background-color': 'yellow','color':'green'});
//                                 $('#harga_ppn_$cont').val(addCommas(harga_ppn.toFixed(4))).css({'background-color': 'yellow','color':'green'});
//                                 $('#nilai_$cont').val(addCommas(nilai_now))
//                                 // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
//
//                                 if(Number(nilai_now) <= Number(nilai_be)){
//                                     swal({
//                                         title: 'Upsss.. !!',
//                                         html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
//                                     });
//
//                                     $('#btn_simpan').prop('disabled', true);
//                                     $('#persen_$cont').css('color','red');
//                                     $('#nilai_$cont').css('color','red');
//                                     $('#jml_' + cont_af).prop('disabled', true);
//                                 }
//                                 else {
//                                     $('#persen_$cont').css('color','');
//                                     $('#nilai_$cont').css('color','');
//                                 }
//                                
//                                 if(Number(harga_baru) <= Number(hpp)){
//                                     swal({
//                                         title: 'Upsss.. !!',
//                                         html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan',
//                                     });
//                                    
//                                     $('#btn_simpan').prop('disabled', true);         
//                                     $('#persen_$cont').css('color','red');
//                                     $('#nilai_$cont').css('color','red');
//                                     $('#jml_' + cont_af).prop('disabled', true);                                    
//                                     $('#harga_$cont').css({'background-color': 'red','color':'yellow'});
//
//                                 }
//                                 else{
//                                     $('#persen_$cont').css({'background-color': 'yellow','color':'green'});
//                                     $('#nilai_$cont').css({'background-color': 'yellow','color':'red'});
//                                     $('#harga_$cont').css({'background-color': 'yellow','color':'green'});
//                                     $('#grosir_$cont').css({'background-color': 'yellow','color':'green'});
//                                     $('#jml_$cont').css({'background-color': 'yellow','color':''});
//                                 }
//
// //                            }, 2000);
//                         },250));
                        
                    </script>";
            $str .= "<script type='text/javascript'>
                        /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */
                        $('#persen_$cont').blur(function() {
                            var fpembulat = $f_pembulat;
                            // var harga = $hrg_jual;
                            // var hpp = $harga_beli;
                            // var minim = $('#jml_$cont').val();
                            var persen_diskon = $('#persen_$cont').val();
                            // var nilai_diskon = (harga * (persen_diskon / 100))*1.11;
                            // var harga_baru = ( (harga*1.11) - nilai_diskon);
                            // var rugilaba = harga_baru - hpp;                                                          
//                            var harga_bulat = RoundTo(harga_baru,fpembulat);
//                             var harga_bulat = harga_baru;

                             // if(harga_bulat != harga_baru){
                             //     var nilai_diskon = harga - harga_bulat;
                             //     var persen_diskon = ((nilai_diskon / harga) * 100);
                             //     var harga_baru = harga_bulat;
                             // }                             

                            // var grosir_baru = harga_bulat * minim;
                                                    
                            // $('#persen_$cont').val(persen_diskon.toFixed(2));
                            $('#persen_$cont').val(persen_diskon);
                            // $('#nilai_$cont').val( addCommas( nilai_diskon.toFixed(4) ));
                            // $('#harga_$cont').val( addCommas( harga_baru.toFixed(4) ));
                            // $('#harga_ppn_$cont').val( addCommas( (harga_baru).toFixed(4) ));
                            // $('#grosir_$cont').val( addCommas( grosir_baru.toFixed(4) ));
                            // $('#grosir_ppn_$cont').val( addCommas( (grosir_baru).toFixed(4) ));

                            // console.log('grosir_baru: ' + grosir_baru)
                            // console.log('harga_baru: ' + harga_baru)

                            var cont_af = $cont + 1; 
                            $('#jml_'+cont_af).prop('disabled', false).prop('readonly', false);
                            $('#btn_simpan').prop('disabled', false);
                        });
                        
                         // mengisi diskon persen bila kolom nilai diskon yg diisi
                        //  $('#nilai_$cont').blur(function() {
                        //      var fpembulat = $f_pembulat;
                        //     var harga = $hrg_jual;
                        //     var hpp = $harga_beli;
                        //     var minim = $('#jml_$cont').val();
                        //     var nilai_diskon = removeCommas($('#nilai_$cont').val())/1.11;
                        //     var persen_diskon = (nilai_diskon / harga) * 100;
                        //     var harga_baru = harga - nilai_diskon;
                        //     var rugilaba = harga_baru - hpp;
                        //     var harga_bulat = RoundTo(harga_baru,fpembulat);
                        //    
                        //     // $('#persen_$cont').val(persen_diskon.toFixed(2));
                        //     $('#persen_$cont').val(persen_diskon);
                        //     $('#harga_$cont').val(harga_baru*1.11);
                        //    
                        //     var cont_af = $cont + 1; 
                        //     $('#jml_'+cont_af).prop('disabled', false);
                        //     $('#btn_simpan').prop('disabled', false);
                        //                                                    
                        // });
                         
                        /*-----validasi harga jual harus lebih besar dg yang sebelumnya----*/
                        // $('#harga_$cont').keyup(function() {
                        //     setTimeout(function(){
                        //         var harga = $hrg_jual;
                        //         var hpp = $harga_beli;
                        //         var cont_be = $cont - 1;
                        //         var cont_af = $cont + 1;
                        //         var jml = $('#jml_$cont').val();
                        //         var nilai_be = $('#harga_'+ cont_be).val();
                        //         var nilai_now = removeCommas($('#harga_$cont').val());
                        //         var harga_baru = nilai_now;
                        //         var rugilaba = harga_baru - hpp;
                        //         var nilai_diskon = harga - harga_baru;
                        //         // var persen_diskon = ((nilai_diskon / harga) * 100).toFixed(2);
                        //         var persen_diskon = ((nilai_diskon / harga) * 100);
                        //         var grosir = jml * harga_baru;
                        //        
                        //         $('#nilai_$cont').val(nilai_diskon);  
                        //         $('#persen_$cont').val(persen_diskon);
                        //         $('#grosir_$cont').val(grosir);
                        //         // $('#persen_$cont').css('background-color','');
                        //        
                        //         // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                        //         if(Number(nilai_now) <= Number(hpp)){                                   
                        //                     swal({
                        //                         title: 'Upsss.. !!',
                        //                         html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan 99',
                        //                     });
                        //                    
                        //                     $('#btn_simpan').prop('disabled', true);         
                        //                     $('#persen_$cont').css('color','red');
                        //                     $('#nilai_$cont').css('color','red');
                        //                     $('#harga_$cont').css({'color':'yellow','background-color':'red'});
                        //                     $('#jml_' + cont_af).prop('disabled', true);
                        //         }
                        //         else if(Number(harga_baru) >= Number(nilai_be)){
                        //             swal({
                        //                     title: 'Upsss.. !!',
                        //                     html: 'harga diskon harus lebih kecil dari ' + nilai_be + 'rupiah 88',
                        //                 });
                        //           
                        //             $('#btn_simpan').prop('disabled', true);         
                        //             $('#persen_$cont').css('color','red');
                        //             $('#nilai_$cont').css('color','red');
                        //             $('#jml_' + cont_af).prop('disabled', true);                                    
                        //             $('#harga_$cont').css({'background-color': 'red','color':'yellow'});
                        //         }
                        //         else {
                        //             $('#persen_$cont').css({'color':'green','background-color':'yellow'});
                        //             $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                        //             $('#harga_$cont').css({'color':'red','background-color':'yellow'});
                        //             $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                        //             $('#jml_$cont').css({'color':'','background-color':'yellow'});
                        //             $('#jml_' + cont_af).prop('disabled', false);
                        //         }
                        //        
                        //     }, 2000);
                        // });
                        
                        /*--normalisasi fields--*/
//                        $('input').blur(function(){
//                           setTimeout(function(){
//                                // $('input').css({'color':'','background-color':''});
//                           },2000);
//                        });
                                                
                    </script>";

            // $str .= "<div class='col-xs-12 border-cek'>----</div>";
        }
        $str_hidden = "<input type='hidden' name='supplier_id' value='$prod_id'>";
        $str .= "<div class='col-xs-12' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
        $str .= "</form>";
        $str .= "<script>
                     $('#btn_simpan').click(function() {
                            setTimeout(function(){                               
                                $('#btn_simpan').prop('disabled', true);
                            }, 500);
                        });


                </script>";

        $form = "";
        $form .= "<div class='overflow-h'>";
        $form .= "<div class='container-fluid'>";
        // $form .= "<div class='border-cek col-xs-8 overflow-h'>";
        $form .= $str;
        // $form .= "</div>";
        $form .= "</div>";
        $form .= "</div>";
        // $form .= "<script>$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl');</script>";
        echo $form;
    }

    public function viewRebate()
    {
        $ppn_persen_set = my_ppn_factor();
        // matiHere();
        // arrPrintHijau(url_segment());
        $jenis = $mode = url_segment(4);
        // arrPrint($_GET);
        /* --------------------------------------
         * grosir
         * --------------------------------------*/
        // echo my_toko_id();
        // cekMerah(my_cabang_id());
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dg = new MdlDiskonPembelianSupplier();
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $condites = array(
                "supplier_id" => $prod_id,
                "jenis" => $jenis,
            );
            // $this->db->where($condites);
        }
        $dg->setTokoId(my_toko_id());
        $src_dg_obj_0 = $dg->lookupByCondition($condites)->result();
//         showLast_query("kuning");
//         arrPrintKuning($src_dg_obj_0);
        $src_dg_obj = array();
        $aktifKategori = array();
        foreach ($src_dg_obj_0 as $item_obj) {
            $dtime_start = isset($item_obj->dtime_start) ? $item_obj->dtime_start : "";
            if ($dtime_start == null) {
                $src_dg_obj[] = $item_obj;
                $aktifKategori[$item_obj->kelompok_id] = $item_obj;
            }
        }
        /*-----------produk speks------------*/
        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $prod_speks = $pr->callSpecs($prod_id);
        // arrPrint($prod_speks);
        // $premi_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->premi_jual * 1 : 0;
        // $diskon_persen = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->diskon_persen * 1 : 0;
        // $harga_jual = isset($prod_speks[$prod_id]) ? $prod_speks[$prod_id]->harga_jual * 1 : 0;
        /*-----------produk harga------------*/
        // $this->load->model("Mdls/MdlHargaProduk");
        // $hp = new MdlHargaProduk();
        // $hp->setTokoId(my_toko_id());
        // // $hp->setCabangId(my_cabang_id());
        // $hp->setCabangId($this->cabang_id);
        // $prod_hargas = $hp->callSpecs($prod_id);
        // showLast_query("kuning");
        // arrPrint($prod_hargas);
        // $harga_speks = array();
        // if (isset($prod_hargas[$prod_id])) {
        //     foreach ($prod_hargas[$prod_id] as $spek_harga) {
        //         $harga_speks[$spek_harga->jenis_value] = $spek_harga;
        //     }
        // }
        // $this->load->library("Diskon");
        // $dk = new Diskon();
        // arrPrint($harga_speks[$this->harga_jenis]->nilai);
        // arrPrint($harga_speks);
        // $harga_list = isset($harga_speks[$this->harga_jenis]) ? $harga_speks[$this->harga_jenis]->nilai * 1 : 0;
        // $harga_list_f = format_harga($harga_list);
        //
        // $harga_list_ppn = $harga_list * ((100 + $ppn_persen_set) / 100);
        // $harga_list_ppn_f = format_harga($harga_list_ppn);
        //
        // // cekHijau($harga_list);
        // $harga_beli = isset($harga_speks["hpp"]) ? $harga_speks["hpp"]->nilai * 1 : 0;
        //
        // $hrg_jual = $harga_list + (($premi_persen / 100) * $harga_list);
        // $hrg_jual_f = formatField_he_format("harga", $hrg_jual);
        //
        // $hrg_jual_ppn = $hrg_jual * ((100 + $ppn_persen_set) / 100);
        // $hrg_jual_ppn_f = formatField_he_format("harga", $hrg_jual_ppn);
        //
        //
        // $harga_beli_f = formatField_he_format("harga", $harga_beli);
        $jml_grosir = sizeof($src_dg_obj);
        // $type_awal = $jml_grosir == 0 ? "text" : "text";
        // $diskon_satu = $dk->calcDiskon($hrg_jual, array("satu" => $diskon_persen), array());
        // // arrPrint($diskon_satu);
        // $diskon_nilai = $diskon_satu['nilai'];
        // $hrg_jual_diskon = $diskon_satu['harga_af'];
        // $hrg_jual_diskon_ppn = $hrg_jual_diskon * ((100 + $ppn_persen_set) / 100);
        //


//        arrPrint($src_dk_obj_0);
//        matihere(__LINE__);

        $str = "";
        $str .= "<style type='text/css'>
                .form-control {
                    margin-top: 1px;
                    padding: 0 5px !important;
                    height: 30px !important;
                }
            </style>";

        // $str .= "<div class='row' style='margin-bottom: 20px;'><div class='col-md-8'>";
        // $str .= "<h5 class='text-uppercase' style='margin-left: 15px;'>harga beli $harga_beli_f</h5>";
        // //        $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan $hrg_jual_f</h4>";
        // //        $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list: Rp. $harga_list_f | premi: $premi_persen%</p>";
        // $str .= "<h4 class='text-uppercase' style='margin-left: 15px;'>harga jual satuan <span class='meta'>include PPN</span> $hrg_jual_ppn_f</h4>";
        // $str .= "<p class='text-uppercase text-red' style='margin-left: 15px;'>harga list <span class='meta'>include PPN</span>: Rp. $harga_list_ppn_f | premi: $premi_persen%</p>";
        // $str .= "</div></div>";

        switch($jenis){
            case "absolut":
                $str .= "<div class='row'>";
                $str .= "<div class='col-lg-3'><div class='input-group marginn'>Campaign System<br><span class='meta'>isikan rupiah total</span><input type='$type_awal' id='jml_222' disabled class='form-control text-center' value='0'></div></div>";
                $str .= "<div class='col-lg-2'><div class='input-group marginn'>Rebate (%)<br><span class='meta'>isikan diskon dalam persen</span><input type='$type_awal' id='persen_222' disabled class='form-control' value='0'></div></div>";
                $str .= "<div class='col-lg-2'><div class='input-group marginn'>Rebate (Rp)<br><span class='meta'>isikan diskon dalam rupiah</span><input type='$type_awal' id='nilai_222' disabled class='form-control' value='$diskon_nilai'></div></div>";

                //        $str .= "<div class='col-xs-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
                //        $str .= "<div class='col-xs-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";

                //        $str .= "<div class='col-lg-2'><div class='input-group marginn'>Harga<input type='$type_awal' id='_harga_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
                // $str .= "<div class='col-lg-2'><div class='input-group marginn'>Harga (incl. PPN)<input type='$type_awal' id='_harga_222' disabled class='form-control' value='" . number_format($hrg_jual_diskon_ppn, 0) . "'></div></div>";
                //        $str .= "<div class='col-lg-2'><div class='input-group marginn'>grosir<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='$hrg_jual_diskon'></div></div>";
                // $str .= "<div class='col-lg-2'><div class='input-group marginn'>grosir (incl. PPN)<input type='$type_awal' id='_grosir_222' disabled class='form-control' value='" . number_format($hrg_jual_diskon_ppn, 0) . "'></div></div>";

                $str .= "</div>";

                // $str .= "<div class='col-xs-12'>----</div>";

                $cont = 222;
                $cont_data = $cont + $jml_grosir + 1;
                // cekHere($cont_data);
                $ix = '-1';
                // arrPrint($src_dg_obj);
                $url_action = base_url() . "diskon/Setting/doSaveRebate/$mode";
                $str .= "<form id='formRabate' method='post' action='$url_action' target='result'>";
                for ($i = 1; $i <= 5; $i++) {

                    $cont++;
                    $ix++;
                    $item = isset($src_dg_obj[$ix]) ? $src_dg_obj[$ix] : (object)array();

                    //arrPrintHijau($item);

                    $id_data = isset($item->id) ? $item->id : "";
                    $jml_id = "jml_$cont";
                    $persen_id = "persen_$cont";
                    $nilai_id = "nilai_$cont";
                    $harga_id = "harga_$cont";
                    $grosir_id = "grosir_$cont";
                    $harga_ppn_id = "harga_ppn_$cont";
                    $grosir_ppn_id = "grosir_ppn_$cont";

                    // $minim = isset($item->minim) ? $item->minim : 0;

                    $maxim = isset($item->maxim) ? $item->maxim : 0;
                    $persen = isset($item->persen) ? $item->persen * 1 : 0;
                    $nilai = isset($item->nilai) ? $item->nilai * 1 : 0;
                    $persen_f = number_format($persen, 2);
                    $harga = isset($item->harga) ? $item->harga * 1 : 0;
                    $disabled = $maxim == 0 ? "disabled" : "";

                    // $hrg_jual_ppn_x = $harga;
                    // $hrg_jual_ppn_x = $hrg_jual_ppn / ((100 + $ppn_persen_set) / 100);
                    // $diskon_loop = $dk->calcDiskon($hrg_jual, array("satu" => $persen), array());
                    // $diskon_loop = $dk->calcPotongan($hrg_jual, $nilai);
                    // cekHijau("$nilai");
                    // $diskon_loop = $dk->calcPotongan($hrg_jual_ppn_x, $nilai);
                    // arrPrint($diskon_loop);

                    // $d_nilai = ($diskon_loop['nilai'] * 1) * ((100 + $ppn_persen_set) / 100);
                    // // $d_nilai = ($diskon_loop['nilai'] * 1);
                    // $harga_be = $diskon_loop['harga_be'];
                    // $harga_af = $diskon_loop['harga_af'] * 1;
                    // $grosir_af = $harga_af * $minim;

                    // $harga_ppn_af = $harga_af * ((100 + $ppn_persen_set) / 100);
                    // $grosir_ppn_af = $grosir_af * ((100 + $ppn_persen_set) / 100);
                    // $harga_ppn_af = $harga_af;
                    // $grosir_ppn_af = $grosir_af;
                    // cekHere("$harga_ppn_af = $harga_af * ((100 + $ppn_persen_set) / 100);");

                    $f_pembulat = 100;
                    $link_delete = base_url() . "diskon/Setting/doDeleteRebate?id=$id_data&id_row=$cont";

                    $str .= "<div class='row'>";
                    // $str .= "<div class='col-xs-3'><div class='input-group marginn'><span class='input-group-btn'><button type='button' class='btn btn-default'>&#8805;</button></span><input type='text' id='$jml_id' class='form-control text-center' name='minim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$minim'></div></div>";
                    /*----balikan 8805*/

                    $str .= "<div class='col-xs-3'>
                                <div class='input-group marginn'>
                                    <span class='input-group-btn'>
                                        <button type='button' class='btn btn-default'>&#8804;</button>
                                    </span>
                                    <input type='text' id='$jml_id' min='100000' class='form-control text-center' name='maxim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='" . number_format($maxim) . "'>
                                </div>
                            </div>";

                    $str .= "<div class='col-xs-2'>
                                <div class='input-group marginn'>
                                    <input size=24 type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen_f'>
                                </div>
                            </div>";

                    $str .= "<div class='col-xs-2'>
                                <div class='input-group marginn'>
                                    <input size=24 type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($nilai) . "'>
                                </div>
                            </div>";

                    // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='harga[]' id='$harga_ppn_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='" . number_format($harga_ppn_af, 0) . "'> </div></div>";
                    // $str .= "<div class='col-xs-2'><div class='input-group marginn'><input size=24 type='text' name='harga[]' id='$grosir_ppn_id' class='form-control' autocomplete='off' disabled onclick=\"this.select()\" value='" . number_format($grosir_ppn_af, 0) . "'> </div></div>";

                    $str .= "<div class='col-xs-1'>
                                <div class='input-group marginn'>
                                    <button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga grosir akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button>
                                </div>
                            </div>";

                    $str .= "</div>";

                    $str .= "<script type='text/javascript'>

                        // var jml_data = $jml_grosir;
                        // var harga = $hrg_jual;
                        // var harga_ppn = $hrg_jual_ppn;

                        var cont_data = $cont_data;
                        var cont_be = $cont -1;

                        $('#jml_'+cont_data).prop('disabled', false);

                        // if(harga > 100){
                        //     // $('#$jml_id').prop('disabled', true);
                        //     $('#$jml_id').prop('readonly', true);
                        //     $('#jml_'+cont_data).prop('readonly', false);
                        // }

                        /*----membuka field diskon persen dan nilai---*/
                        $('#jml_$cont').off();
                        $('#jml_$cont').keydown(function() {
                            $('#persen_$cont').prop('disabled', false);
                            $('#nilai_$cont').prop('disabled', false);
//                            $('#harga_$cont').prop('disabled', false);
                        });

                        /*----realtime keyup---*/
                        $('#jml_$cont').keyup(function() {
                            $('#jml_$cont').val( addCommas(this.value) );
                        });

                        /*-----validasi jml harus lebih besar dg jml sebelumnya-- dan nilai diskon harus > sebelumnya--*/
                        $('#jml_$cont').keyup(delay_v2(function() {
//                            setTimeout(function(){
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var jml_be = removeCommas($('#jml_'+ cont_be).val())*1;
                                var jml_now = removeCommas($('#jml_$cont').val())*1;

                                // var nilai_be = $('#nilai_'+ cont_be).val();
                                // var nilai_now = $('#nilai_$cont').val();
                                // var harga = $('#harga_$cont').val();
                                // var grosir = $('#grosir_$cont').val();
                                // var grosir_baru = jml_now * harga;
                                // var harga_ppn = removeCommas($('#harga_ppn_$cont').val());
                                // var grosir_ppn = $('#grosir_ppn_$cont').val();
                                // var grosir_ppn_baru = addCommas( (jml_now*harga_ppn).toFixed(4) );

                                if(Number(jml_now) <= Number(jml_be)){
                                    swal({
                                        title: 'Upsss.. !!',
                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                    });
                                    $('#persen_$cont').prop('disabled', true);
                                    $('#nilai_$cont').prop('disabled', true);
                                    $('#jml_$cont').css('color','red');
                                    $('#btn_simpan').prop('disabled', true);
                                }
                                else {
                                    $('#jml_' + cont_af).prop('disabled', true);
                                    $('#jml_$cont').css({'background-color': 'yellow','color':'red'});
                                    $('#persen_$cont').css('color','');
                                    $('#nilai_$cont').css('color','');

                                    // $('#harga_$cont').css('color','');
                                    // $('#grosir_$cont').val(grosir_baru).css({'background-color': 'yellow','color':'green'});
                                    // $('#harga_ppn_$cont').css('color','');
                                    // $('#grosir_ppn_$cont').val(grosir_ppn_baru).css({'background-color': 'yellow','color':'green'});
                                    // if(Number(nilai_now) > Number(nilai_be)){
                                    //     $('#btn_simpan').prop('disabled', false);
                                    //     $('#jml_' + cont_af).prop('disabled', false);
                                    // }
                                    // else {
                                        // $('#btn_simpan').prop('disabled', true);
                                        // swal({
                                        //     title: 'Upsss.. !!',
                                        //     html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
                                        // });
                                    // }
                                    $('#btn_simpan').prop('disabled', false);
                                }
//                            }, 2000);
                        },2000));

                    </script>";
                    $str .= "<script type='text/javascript'>
                        /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/
                        $('#persen_$cont').off();
                        $('#persen_$cont').keyup(delay_v2(function() {

//                            setTimeout(function(){

                                // var harga = $hrg_jual;
                                // var hpp = $harga_beli;
                                // var nilai_diskon = harga * (persen_now / 100);
                                // var persen_max = $harga_beli;
                                // var harga_baru = harga - nilai_diskon;
                                // var rugilaba = harga_baru - hpp;
                                // console.log(persen_now +' *<=* '+ persen_be + ' *** ' + cont_be);
                                var cont_be = $cont - 1;
                                var cont_af = $cont + 1;
                                var persen_be = $('#persen_'+ cont_be).val();
                                var persen_now = $('#persen_$cont').val();
                                if(Number(persen_now) <= Number(persen_be)){
                                        swal({
                                            title: 'Upsss.. !!',
                                            html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                        });
                                        $('#btn_simpan').prop('disabled', true);
                                        $('#persen_$cont').css('color','red');
                                        $('#nilai_$cont').css('color','red');
                                        $('#jml_' + cont_af).prop('disabled', true);
                                }
                                else {
                                        $('#persen_$cont').css('color','');
                                        $('#nilai_$cont').css('color','');
                                        $('#btn_simpan').prop('disabled', false);
                                }

                                $('#persen_$cont').css({'color':'red','background-color':'yellow'});
                                $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                // $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                                // $('#harga_$cont').css({'color':'green','background-color':'yellow'});
                                // $('#grosir_$cont').css({'color':'green','background-color':'yellow'});

//                            }, 2000);

                        }, 2000));

                         /*-----validasi diskon nilai harus lebih besar dg yang sebelumnya----*/

//                         $('#nilai_$cont').off();
//                         $('#nilai_$cont').keyup(delay_v2(function(){

//                         },250));

//                         $('#nilai_$cont').off();
//                         $('#nilai_$cont').keyup(delay_v2(function(){
//                             var harga = '$hrg_jual';
//                             var hpp = '$harga_beli';
//                             var cont_be = $cont - 1;
//                             var cont_af = $cont + 1;
//                             var nilai_be = $('#nilai_'+ cont_be).val();
//                             var nilai_now = removeCommas($('#nilai_$cont').val());
//                             var jml = $('#jml_$cont').val();
//                             var harga_baru = harga - nilai_now;
//                             var rugilaba = harga_baru - hpp;
//                             var grosir = harga_baru * jml;
//                             var harga_ppn = (harga*1.11)-nilai_now;
//                             var grosir_ppn_baru = addCommas( (jml*harga_ppn).toFixed(4) );

//                             $('#grosir_$cont').val(grosir);
//                             $('#grosir_ppn_$cont').val(grosir_ppn_baru).css({'background-color': 'yellow','color':'green'});
//                             $('#harga_ppn_$cont').val(addCommas(harga_ppn.toFixed(4))).css({'background-color': 'yellow','color':'green'});
//                             $('#nilai_$cont').val(addCommas(nilai_now));
                             // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);

//                             if(Number(nilai_now) <= Number(nilai_be)){
//                                 swal({
//                                     title: 'Upsss.. !!',
//                                     html: 'minimal Diskon harus lebih besar dari ' + nilai_be + ' sekarang ' + nilai_now
//                                 });
//                                 $('#btn_simpan').prop('disabled', true);
////                                 $('#persen_$cont').css('color','red');
////                                 $('#nilai_$cont').css('color','red');
////                                 $('#jml_' + cont_af).prop('disabled', true);
//                             }
//                             else {
//                                 $('#persen_$cont').css('color','');
//                                 $('#nilai_$cont').css('color','');
//                             }

//                             if(Number(harga_baru) <= Number(hpp)){
//                                 swal({
//                                     title: 'Upsss.. !!',
//                                     html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan',
//                                 });
//                                 $('#btn_simpan').prop('disabled', true);
//                                 $('#persen_$cont').css('color','red');
//                                 $('#nilai_$cont').css('color','red');
//                                 $('#jml_' + cont_af).prop('disabled', true);
//                                 $('#harga_$cont').css({'background-color': 'red','color':'yellow'});
//                             }
//                             else{
//                                 $('#persen_$cont').css({'background-color': 'yellow','color':'green'});
//                                 $('#nilai_$cont').css({'background-color': 'yellow','color':'red'});
//                                 $('#harga_$cont').css({'background-color': 'yellow','color':'green'});
//                                 $('#grosir_$cont').css({'background-color': 'yellow','color':'green'});
//                                 $('#jml_$cont').css({'background-color': 'yellow','color':''});
//                             }
//                         },250));

                    </script>";
                    $str .= "<script type='text/javascript'>

                        /*-------- NOL KAN KOLOM REBATE PERSEN JIKA REBATE RP DI ISI -------------- */
                        $('#nilai_$cont').off();
                        $('#nilai_$cont').keyup(function() {
                            var target = $('#persen_$cont');
                            $(target).val(0);
                            $('#nilai_$cont').val(addCommas(removeCommas(this.value)));
                            var jml_cont = removeCommas($('#jml_$cont').val());
                            var persen = $('#persen_$cont').val();
                            var nilai = removeCommas($(this).val());
                            if(jml_cont*1>0 && persen*1>0){
                                $('#btn_simpan').prop('disabled', false);
                            }
                            else if(jml_cont*1>0 && nilai*1>0){
                                $('#btn_simpan').prop('disabled', false);
                            }
                            else{
                                $('#btn_simpan').prop('disabled', true);
                            }
                        });

                        /*-------- NOL KAN KOLOM REBATE RP JIKA REBATE PERSEN DI ISI -------------- */
                        $('#persen_$cont').off();
                        $('#persen_$cont').keyup(function() {
                            var target = $('#nilai_$cont');
                            $(target).val(0);
                            var jml_cont = removeCommas($('#jml_$cont').val());
                            var nilai = $('#nilai_$cont').val();
                            var persen = removeCommas($(this).val());
                            if(jml_cont*1>0 && persen*1>0){
                                $('#btn_simpan').prop('disabled', false);
                            }
                            else if(jml_cont*1>0 && nilai*1>0){
                                $('#btn_simpan').prop('disabled', false);
                            }
                            else{
                                $('#btn_simpan').prop('disabled', true);
                            }
                        });

                        /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */
                        $('#persen_$cont').blur(function() {
                            var fpembulat = $f_pembulat;
                            // var harga = $hrg_jual;
                            // var hpp = $harga_beli;
                            // var minim = $('#jml_$cont').val();
                            var persen_diskon = $('#persen_$cont').val();
                            // var nilai_diskon = (harga * (persen_diskon / 100))*1.11;
                            // var harga_baru = ( (harga*1.11) - nilai_diskon);
                            // var rugilaba = harga_baru - hpp;
//                            var harga_bulat = RoundTo(harga_baru,fpembulat);
//                             var harga_bulat = harga_baru;

                             // if(harga_bulat != harga_baru){
                             //     var nilai_diskon = harga - harga_bulat;
                             //     var persen_diskon = ((nilai_diskon / harga) * 100);
                             //     var harga_baru = harga_bulat;
                             // }

                            // var grosir_baru = harga_bulat * minim;
                            // $('#persen_$cont').val(persen_diskon.toFixed(2));
                            $('#persen_$cont').val(persen_diskon);
                            // $('#nilai_$cont').val( addCommas( nilai_diskon.toFixed(4) ));
                            // $('#harga_$cont').val( addCommas( harga_baru.toFixed(4) ));
                            // $('#harga_ppn_$cont').val( addCommas( (harga_baru).toFixed(4) ));
                            // $('#grosir_$cont').val( addCommas( grosir_baru.toFixed(4) ));
                            // $('#grosir_ppn_$cont').val( addCommas( (grosir_baru).toFixed(4) ));
                            // console.log('grosir_baru: ' + grosir_baru)
                            // console.log('harga_baru: ' + harga_baru)

                            var cont_af = $cont + 1;
                            $('#jml_'+cont_af).prop('disabled', false).prop('readonly', false);
                            $('#btn_simpan').prop('disabled', false);
                        });

                         // mengisi diskon persen bila kolom nilai diskon yg diisi
                        //  $('#nilai_$cont').blur(function() {
                        //      var fpembulat = $f_pembulat;
                        //     var harga = $hrg_jual;
                        //     var hpp = $harga_beli;
                        //     var minim = $('#jml_$cont').val();
                        //     var nilai_diskon = removeCommas($('#nilai_$cont').val())/1.11;
                        //     var persen_diskon = (nilai_diskon / harga) * 100;
                        //     var harga_baru = harga - nilai_diskon;
                        //     var rugilaba = harga_baru - hpp;
                        //     var harga_bulat = RoundTo(harga_baru,fpembulat);
                        //
                        //     // $('#persen_$cont').val(persen_diskon.toFixed(2));
                        //     $('#persen_$cont').val(persen_diskon);
                        //     $('#harga_$cont').val(harga_baru*1.11);
                        //
                        //     var cont_af = $cont + 1;
                        //     $('#jml_'+cont_af).prop('disabled', false);
                        //     $('#btn_simpan').prop('disabled', false);
                        //
                        // });

                        /*-----validasi harga jual harus lebih besar dg yang sebelumnya----*/
                        // $('#harga_$cont').keyup(function() {
                        //     setTimeout(function(){
                        //         var harga = $hrg_jual;
                        //         var hpp = $harga_beli;
                        //         var cont_be = $cont - 1;
                        //         var cont_af = $cont + 1;
                        //         var jml = $('#jml_$cont').val();
                        //         var nilai_be = $('#harga_'+ cont_be).val();
                        //         var nilai_now = removeCommas($('#harga_$cont').val());
                        //         var harga_baru = nilai_now;
                        //         var rugilaba = harga_baru - hpp;
                        //         var nilai_diskon = harga - harga_baru;
                        //         // var persen_diskon = ((nilai_diskon / harga) * 100).toFixed(2);
                        //         var persen_diskon = ((nilai_diskon / harga) * 100);
                        //         var grosir = jml * harga_baru;
                        //
                        //         $('#nilai_$cont').val(nilai_diskon);
                        //         $('#persen_$cont').val(persen_diskon);
                        //         $('#grosir_$cont').val(grosir);
                        //         // $('#persen_$cont').css('background-color','');
                        //
                        //         // console.log(nilai_now +' *<=* '+ nilai_be + ' *** ' + cont_be);
                        //         if(Number(nilai_now) <= Number(hpp)){
                        //                     swal({
                        //                         title: 'Upsss.. !!',
                        //                         html: 'Diskon membuat <r>harga jual < HPP</r>, <br>Silahkan perbaiki diskon sebelum disimpan 99',
                        //                     });
                        //
                        //                     $('#btn_simpan').prop('disabled', true);
                        //                     $('#persen_$cont').css('color','red');
                        //                     $('#nilai_$cont').css('color','red');
                        //                     $('#harga_$cont').css({'color':'yellow','background-color':'red'});
                        //                     $('#jml_' + cont_af).prop('disabled', true);
                        //         }
                        //         else if(Number(harga_baru) >= Number(nilai_be)){
                        //             swal({
                        //                     title: 'Upsss.. !!',
                        //                     html: 'harga diskon harus lebih kecil dari ' + nilai_be + 'rupiah 88',
                        //                 });
                        //
                        //             $('#btn_simpan').prop('disabled', true);
                        //             $('#persen_$cont').css('color','red');
                        //             $('#nilai_$cont').css('color','red');
                        //             $('#jml_' + cont_af).prop('disabled', true);
                        //             $('#harga_$cont').css({'background-color': 'red','color':'yellow'});
                        //         }
                        //         else {
                        //             $('#persen_$cont').css({'color':'green','background-color':'yellow'});
                        //             $('#nilai_$cont').css({'color':'green','background-color':'yellow'});
                        //             $('#harga_$cont').css({'color':'red','background-color':'yellow'});
                        //             $('#grosir_$cont').css({'color':'green','background-color':'yellow'});
                        //             $('#jml_$cont').css({'color':'','background-color':'yellow'});
                        //             $('#jml_' + cont_af).prop('disabled', false);
                        //         }
                        //
                        //     }, 2000);
                        // });

                        /*--normalisasi fields--*/
//                        $('input').blur(function(){
//                           setTimeout(function(){
//                                // $('input').css({'color':'','background-color':''});
//                           },2000);
//                        });

                    </script>";

                    // $str .= "<div class='col-xs-12 border-cek'>----</div>";
                }
                $str_hidden = "<input type='hidden' name='supplier_id' value='$prod_id'>";

                $str .= "<div class='col-xs-12' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
                $str .= "</form>";

                $str .= "<script>
                     $('#btn_simpan').click(function() {
                            setTimeout(function(){
                                $('#btn_simpan').prop('disabled', true);
                            }, 200);
                        });

                        document.getElementById(\"formRabate\").addEventListener(\"submit\", function (event) {
                            let form = event.target;
                            let rows = form.querySelectorAll(\".row\");
                            let valid = false;

                            rows.forEach(row => {
                                let maxim = row.querySelector(\"input[name='maxim[]']\");
                                let persen = row.querySelector(\"input[name='persen[]']\");
                                let nilai = row.querySelector(\"input[name='nilai[]']\");

                                if (maxim && persen && nilai) {
                                    let maximVal =  parseInt(maxim.value.replace(/,/g, ''));
                                    let persenVal = parseFloat(persen.value.replace(/,/g, ''));
                                    let nilaiVal =  parseFloat(nilai.value.replace(/,/g, ''));

                                        top.console.log('maximVal==>', maximVal);
                                        top.console.log('persenVal==>', persenVal);
                                        top.console.log('nilaiVal==>', nilaiVal);
                                        top.console.log('==============================');

                                    if (maximVal >= 100000 && (persenVal > 0 || nilaiVal > 0)) {
                                        valid = true;

                                        $(maxim).removeClass('bg-red');
                                        $(persen).removeClass('bg-red');
                                        $(nilai).removeClass('bg-red');
                                    }
                                    else {

                                        top.console.log('INI MASUK ERROR.. KENAPA..??', maximVal);

                                        if(!$(maxim).prop('disabled') && maximVal > 0 && maximVal < 100000){
                                            $(maxim).addClass('bg-red');
                                            top.console.error('maximVal==>', maximVal);
                                            valid = false;
                                        }

                                        if(!$(persen).prop('disabled') && persenVal <= 0 && nilaiVal <= 0 || persenVal > 100){
                                            $(persen).addClass('bg-red');
                                            top.console.error('persenVal==>', persenVal);
                                            valid = false;
                                        }

                                        if(!$(nilai).prop('disabled') && nilaiVal <= 0 && persenVal <= 0){
                                            $(nilai).addClass('bg-red');
                                            top.console.error('nilaiVal==>', nilaiVal);
                                            valid = false;
                                        }

                                    }
                                }
                            });

                            if (!valid) {
                                swal('SETTINGAN BELUM DISIMPAN<br><r>karena ada kesalahan pengisian</r>', 'silahkan cek form berwarna merah<br><br><br><small><b>NOTES: <br><r>Nilai Campaign min 100.000</r><br><r>Diskon harus diisi salah satu (Rp atau Persen)</r></b></small>', 'error');
                                event.preventDefault();
                            }
                        });

                </script>";

                break;
            case "kelompok":

                $this->load->model("Mdls/MdlProdukPerSupplier");
                $pr = new MdlProdukPerSupplier();
                $type_awal = "";

                $mPrdList = $pr->callSpecs($prod_id);
                $ttProdukSupplier = count($mPrdList);

                $produk_sumber = array();
                foreach($mPrdList as $pRow){
                    $produk_sumber[$pRow->id] = $pRow->nama;
                }

                $this->load->model("Mdls/MdlDiskonPembelianSupplier");
                $dgg = new MdlDiskonPembelianSupplier();
                if (isset($_GET['id'])) {
                    $prod_id = $_GET['id'];
                    $conditesgg = array(
                        "supplier_id" => $prod_id,
                        "jenis" => "kelompok",
                    );
                }
                $dgg->setTokoId(my_toko_id());
                $src_dgg_obj_0 = $dgg->lookupByCondition($conditesgg)->result();

                $formGroup = array();
                $produkByKelompok = array();
                foreach($src_dgg_obj_0 as $dggRow){
                    $formGroup[$dggRow->kelompok_id] = array(
                        "kelompok_id" => $dggRow->kelompok_id,
                        "qty" => $dggRow->maxim,
                        "persen" => $dggRow->persen,
                        "nilai" => $dggRow->nilai,
                    );
                    $produkByKelompok[$dggRow->kelompok_id][$dggRow->produk_id] = array(
                        "id" => $dggRow->id,
                        "produk_id" => $dggRow->produk_id,
                        "produk_nama" => $produk_sumber[$dggRow->produk_id],
                    );
                    $produkBy[$dggRow->produk_id] = array(
                        "id" => $dggRow->id,
                        "produk_id" => $dggRow->produk_id,
                        "kelompok_id" => $dggRow->kelompok_id,
                        "produk_nama" => $produk_sumber[$dggRow->produk_id],
                    );
                }

                $this->load->model("Mdls/MdlDiskonKelompok");
                $dk = new MdlDiskonKelompok();
                $condites2 = array(
                    "supplier_id" => $prod_id,
                    "trash" => 0,
                );
                $src_dk_obj_0 = $dk->lookupByCondition($condites2)->result();

                $aktifGroup = array();
                if(!empty($src_dk_obj_0)){
                    foreach($src_dk_obj_0 as $obj_0){
                        $kelID  = $obj_0->id;
                        $minim    = $obj_0->minim;
                        $maxim    = $obj_0->maxim;
                        $persen = $obj_0->persen;
                        $nilai  = $obj_0->nilai;
                        $aktifGroup[$kelID] = array(
                            "kelompok_id" => $kelID,
                            "minim" => $minim,
                            "maxim" => $maxim,
                            "persen" => $persen,
                            "nilai" => $nilai,
                        );
                    }
                }

                $kelompok_aktif = count($src_dk_obj_0);

//                arrPrint($formGroup);
//                arrPrint($src_dk_obj_0);
//                arrPrintWebs($aktifGroup);

                $str .= "<style>
                        /* Mengatur warna teks untuk opsi yang disabled */
                        .dropdown-menu .disabled,
                        .dropdown-menu .disabled a {
                           color: #b3b3b3 !important;
                           pointer-events: none;
                        }
                        /* Tambahkan latar belakang atau gaya lainnya jika diperlukan */
                        .dropdown-menu .disabled:hover,
                        .dropdown-menu .disabled a:hover {
                           background-color: transparent !important; /* Hilangkan efek hover */
                        }
                        </style>";

                $str .= "<div class='row'>";
                $str .= "<div class='col-xs-4' style='margin: 0px 0px 23px 0px;'> <span id='' class='bg-default'><i class='fa fa-list'></i> Kelompok Aktif <r><b>($kelompok_aktif)</b></r></span> </div>";
                $str .= "<div class='col-xs-3' style='margin: 0px 0px 23px 0px;'> &nbsp; </div>";
                $str .= "<div class='col-xs-5' style='margin: 0px 0px 23px 0px;'>";
                $str .= "<div class='btn-group pull-right'>";
                $str .= "<button type='button' id='btn_tambah_kelompok' class='btn btn-success'><i class='fa fa-plus'></i> Tambah Kelompok</button>";
                $str .= "<button type='button' id='btn_refresh_kelompok' class='btn btn-warning'><i class='fa fa-refresh'></i> reload</button>";
                $str .= "</div>";
                $str .= "</div>";
                $str .= "</div>";

                if(!empty($src_dk_obj_0)){
                    $str .= "<div class='box-group' id='accordion'>";

                    $numKat = 0;
                    foreach($src_dk_obj_0 as $dRow){
                        $numKat++;
                        $cont_id = $dRow->id;
                        $cont_nama = $dRow->nama;
                        $cont = 222+$cont_id;
                        $accordingIn = $numKat==99 ? "in" : "";

                        $str .= "<div style='margin-bottom: 0px !important;' class='panel box box-success'>";
                        $str .= "<div class='box-header with-border'>";
                        $str .= "<h4 style='display: block!important;' class='box-title'>";

                        $str .= "<a data-toggle='collapse' data-parent='#accordion' href='#collapse$cont_id' class='' aria-expanded='true'>";
                        $str .= "<span class='badge bg-red'>" . $numKat . "</span> &nbsp; " . strtoupper($cont_nama);
                        $str .= "</a>";

                        $link_delete = base_url() . "diskon/Setting/doDeleteRebateKelompok?id=$cont_id"; //

                        $str .= "<span style='margin-left: 6px;' class='pull-right'>
                        <span idx='$cont_id' nama='$cont_nama' class='btn btn-xs btn-info pull-right edit_kelompok'>edit</span>
                        </span>";
                        $str .= "<span class='pull-right'> <span onclick=\"btn_alert_result('<i class=\'fa fa-warning text-orange\'></i> Peringatan <i class=\'fa fa-warning text-orange\'></i>','Apakah <b>($cont_nama)</b> akan dihapus permanen?<br>semua produk yang terelasi dan settingan aktif lainnya akan di delete.','$link_delete');\" class='btn btn-xs btn-danger pull-right'>delete</span> </span>";

                        $str .= "</h4>";
                        $str .= "</div>";
                        $str .= "<div id='collapse$cont_id' class='panel-collapse collapse $accordingIn' aria-expanded='true' style=''>";
                        $str .= "<div class='box-body'>";

                        $str .= "<div class='col-md-5'>";

                        $str .= "<div style='margin-bottom: 6px;' class='row'>";
                        $str .= "<div class='col-lg-3'><div class='input-group marginn'>QTY MAX<input type='$type_awal' id='jml_222' disabled class='form-control text-center hidden' value='0'></div></div>";
                        $str .= "<div class='col-lg-2 no-padding'><div class='input-group marginn'>REBATE (%)<input type='$type_awal' id='persen_222' disabled class='form-control hidden' value='0'></div></div>";
                        $str .= "<div class='col-lg-5'><div class='input-group marginn'>REBATE (Rp)<input type='$type_awal' id='absolut_222' disabled class='form-control hidden' value='0'></div></div>";
                        $str .= "<div class='col-lg-2 no-padding'>&nbsp;</div>";
                        $str .= "</div>";


                        $cont_data = $cont + $jml_grosir + 1;

//                        $ix = '-1';
//                        $ix = 0;

                        $url_action = base_url() . "diskon/Setting/doSaveRebate/$mode";
                        $str .= "<form method='post' action='$url_action' target='result'>";
//                        $cont++;
                        //REGION FORM QTY DAN RABATE
                        foreach($aktifGroup as $catID => $formRow){

//                            $ix++;
                            $item = isset($formRow) ? (object)$formRow : (object)array();
                            $id_data = isset($item->kelompok_id) ? $item->kelompok_id : "";

                            $jml_id = $catID != $cont_id ? "_jml_$cont" : "jml_$cont";
                            $persen_id = $catID != $cont_id ? "_persen_$cont" : "persen_$cont";
                            $nilai_id = $catID != $cont_id ? "_nilai_$cont" : "nilai_$cont";
//                            $absolut_id = "absolut_$cont";
                            $harga_id = "harga_$cont";
                            $grosir_id = "grosir_$cont";
                            $harga_ppn_id = "harga_ppn_$cont";
                            $grosir_ppn_id = "grosir_ppn_$cont";

                            $maxim = isset($item->maxim) ? $item->maxim : 0;
                            $persen = isset($item->persen) ? $item->persen * 1 : 0;
                            $nilai = isset($item->nilai) ? $item->nilai * 1 : 0;
                            $persen_f = number_format($persen, 2);
                            $nilai_f = number_format($nilai, 2);
                            $harga = isset($item->harga) ? $item->harga * 1 : 0;

                            $disabled = $catID != $cont_id ? "disabled" : "";
                            $hideRow = $catID != $cont_id ? "hidden" : "";

                            $f_pembulat = 100;


                            $str .= "<div class='row $hideRow'>";
                            $str .= "<div class='col-xs-3'>";
                            $str .= "<div class='input-group'>";
                            $str .= "<span class='input-group-btn'>";
                            $str .= "<button type='button' class='btn btn-default'>&#8804;</button>";
                            $str .= "</span>";
                            $str .= "<input type='text' id='$jml_id' class='form-control text-center' name='maxim[]' onclick=\"this.select()\" autocomplete='off' $disabled value='$maxim'>";
                            $str .= "</div>";
                            $str .= "</div>";

                            $str .= "<div class='col-xs-2 no-padding'>
                                        <!-- <div class='input-group marginn'>-->
                                            <input size=24 type='text' name='persen[]' id='$persen_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$persen_f'>
                                        <!-- </div>-->
                                    </div>";

                            $str .= "<div class='col-xs-5'>
                                        <!-- <div class='input-group marginn'>-->
                                            <input size=24 type='text' name='nilai[]' id='$nilai_id' class='form-control' autocomplete='off' $disabled onclick=\"this.select()\" value='$nilai_f'>
                                        <!-- </div>-->
                                    </div>";

//                            $str .= "<div class='col-xs-2 no-padding'>
//                                        <!-- <div style='margin: 5px;' class='input-group'> -->
//                                            <button type='button' class='btn btn-link' $disabled onclick=\"btn_alert_result('Peringatan','Apakah harga ($id_data) akan dihapus permanen?','$link_delete');\"><i class='fa fa-trash'></i></button>
//                                        <!-- </div> -->
//                                    </div>";

                            $str .= "</div>";


                            $str .= "<script>\n

                                        var cont_data = $cont_data;
                                        var cont_be = $cont -1;
                                        $('#jml_'+cont_data).prop('disabled', false);
                                        /*----membuka field diskon persen dan nilai---*/
                                        $('#jml_$cont').off();
                                        $('#jml_$cont').keydown(function() {
                                            $('#persen_$cont').prop('disabled', false);
                                        });
                                        /*-----validasi jml harus lebih besar dg jml sebelumnya-- dan nilai diskon harus > sebelumnya--*/
                                        $('#jml_$cont').off();
                                        $('#jml_$cont').keyup(function() {
                                            setTimeout(function(){
                                                var cont_be = $cont - 1;
                                                var cont_af = $cont + 1;
                                                var jml_be = $('#jml_'+ cont_be).val();
                                                var jml_now = $('#jml_$cont').val();
                                                if(Number(jml_now) <= Number(jml_be)){
                                                    swal({
                                                        title: 'Upsss.. !!',
                                                        html: 'jumlah minimal harus lebih besar dari ' + jml_be + ' sekarang ' + jml_now
                                                    });
                                                    $('#persen_$cont').prop('disabled', true);
                                                    $('#jml_$cont').css('color','red');
                                                    $('#btn_simpan').prop('disabled', true);
                                                }
                                                else {
                                                    $('#jml_' + cont_af).prop('disabled', true);
                                                    $('#jml_$cont').css({'background-color': 'yellow','color':'red'});
                                                    $('#persen_$cont').css('color','');
                                                    $('#btn_simpan').prop('disabled', false);
                                                }
                                            }, 2000);
                                        });

                                        /*-----validasi diskon persen harus lebih besar dg yang sebelumnya----*/\n
                                        $('#persen_$cont').off();
                                        $('#persen_$cont').keyup(function() {
                                            setTimeout(function(){
                                                var cont_be = $cont - 1;
                                                var cont_af = $cont + 1;
                                                var persen_be = $('#persen_'+ cont_be).val();
                                                var persen_now = $('#persen_$cont').val();
                                                if(Number(persen_now) <= Number(persen_be)){
                                                    swal({
                                                        title: 'Upsss.. !!',
                                                        html: 'minimal Diskon harus lebih besar dari ' + persen_be + ' sekarang ' + persen_now
                                                    });
                                                    $('#btn_simpan').prop('disabled', true);
                                                    $('#persen_$cont').css('color','red');
                                                    $('#nilai_$cont').css('color','red');
                                                    $('#jml_' + cont_af).prop('disabled', true);
                                                }
                                                else {
                                                    $('#persen_$cont').css('color','');
                                                    $('#nilai_$cont').css('color','');
                                                    $('#btn_simpan').prop('disabled', false);
                                                }
                                                $('#persen_$cont').css({'color':'red','background-color':'yellow'});
                                                $('#jml_$cont').css({'color':'','background-color':'yellow'});
                                            }, 2000);
                                        });\n

                                        /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */\n
                                        $('#persen_$cont').off();
                                        $('#persen_$cont').blur(function() {
                                            var fpembulat = $f_pembulat;
                                            var persen_diskon = $('#persen_$cont').val();
                                            $('#persen_$cont').val(persen_diskon);
                                            var cont_af = $cont + 1;
                                            $('#jml_'+cont_af).prop('disabled', false).prop('readonly', false);
                                            $('#btn_simpan').prop('disabled', false);
                                        });\n
                                        /*--normalisasi fields--*/\n
//                                        $('input').off();
//                                        $('input').blur(function(){
//                                           setTimeout(function(){
//                                                // $('input').css({'color':'','background-color':''});
//                                           },2000);
//                                        });\n

                                        /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */\n
                                        $('#persen_$cont').off();
                                        $('#persen_$cont').blur(function() {
                                            var fpembulat = $f_pembulat;
                                            var persen_diskon = $('#persen_$cont').val();
                                            $('#persen_$cont').val(persen_diskon);
                                            var cont_af = $cont + 1;
                                            $('#jml_'+cont_af).prop('disabled', false).prop('readonly', false);
                                            $('#btn_simpan').prop('disabled', false);
                                        });\n
                                        /*--normalisasi fields--*/\n
//                                        $('input').off();
//                                        $('input').blur(function(){
//                                           setTimeout(function(){
//                                                // $('input').css({'color':'','background-color':''});
//                                           },2000);
//                                        });\n

                                    </script>";
                        }
                        //ENDREGION FORM QTY DAN RABATE

                        $str_hidden  = "<input type='hidden' name='supplier_id' value='$prod_id'>";
                        $str_hidden .= "<input type='hidden' name='kelompok_id' value='$cont_id'>";

                        $str .= "<div class='col-xs-12 no-padding' style='margin-top: 20px;'>$str_hidden<button type='submit' id='btn_simpan_$cont' disabled class='btn btn-warning btn-block'>Simpan Data Setting</button></div>";
                        $str .= "</form>";
                        $str .= "</div>";

                        $str .= "<div class='col-md-7'>";
                        $str .= "<div class='text-center bg-gray'>== DAFTAR PRODUK RELASI ==</div>";

                        $str .= "<div class='panel no-margin'>";
                        $str .= "Pilih Produk <b><r>($ttProdukSupplier)</r></b> <i class='blink fa fa-angle-right text-red'></i>
                                <select data-style=\"btn-success\" multiple data-live-search=\"true\" title=\"silahkan pilih produk\" data-headers=\"pilih produk\" data-size=\"15\" data-container=\"body\" class='btnx btn-md btn-infox selectpicker select2' id='produk_supplier_$prod_id"."_"."$cont_id' onchanges=\"loadFilterSupplier('supplier_id',this.value);\">";
//                        $str .= "<option>---pilih Produk---</option>";

                        if(!empty($mPrdList)){
                            $pUrut = 0;
                            usort($mPrdList, function($a, $b) {
                                if ($a->kategori_id == $b->kategori_id) {
                                    return 0;
                                }
                                return ($a->kategori_id < $b->kategori_id) ? -1 : 1;
                            });
                            foreach($mPrdList as $pRow){
                                $pUrut++;
                                $pid = $pRow->id;
                                $pname = $pRow->nama;
                                $catID = $pRow->kategori_id == 1 ? "unit" : "non";
                                $disabled = isset($produkBy[$pid]) ? "disabled" : "";
                                $kelompok_mana = isset($produkBy[$pid]) && $produkBy[$pid]['kelompok_id'] !== $cont_id ? "[" . $produkBy[$pid]['kelompok_id'] . "]" : "";
                                $selected = isset($produkBy[$pid]) && $produkBy[$pid]['kelompok_id'] == $cont_id ? "<i class=\"fa text-green fa-check-square\"></i>" : "";

                                $str .= "<option value='$pid' $disabled mrnama='$pname' data-content='$pUrut. <span class=\"text-green text-bold\">($pid)</span> $pname <span class=\"text-red\">($catID)</span> $kelompok_mana $selected'>$pUrut. ($pid) $pname <r>($catID)</r> $kelompok_mana</option>";
                            }
                        }
                        $str .= "</select>";

                        $str .= "<button type='button' id='simpan_daftar_produk_$prod_id$cont_id' class='btn btn-md btn-info pull-right' disabled><i class='fa fa-save'></i> SIMPAN</button>";
                        $str .= "</div>";

                        $jml_produk_join = isset($produkByKelompok[$cont_id]) ? count($produkByKelompok[$cont_id]) : 0;
                        $str .= "<div style='height: 120px;max-height: 300px;margin: 3px 0 3px 0;overflow:scroll;' class='panel'>";

                        $jika_sidah_ada_Produk = 0;
                        if(!empty($produkByKelompok[$cont_id])){
//                            $str .= "<div class='text-center text-red'>{daftar produk yang sudah join}</div>";
                            $lNum=0;
                            $str .= "<table class='table dataTable compact'>";
                            $str .= "<caption class='no-padding text-center bg-success text-bold'>ada <r>($jml_produk_join)</r> produk yang sudah dalam <r>$cont_nama</r></caption>";
                            foreach($produkByKelompok[$cont_id] as $row){
                                $lNum++;
                                $rowID = $row['id'];
                                $pprodID = $row['produk_id'];
                                $produk_nama = $row['produk_nama'];
                                $str .= "<tr>";
                                $str .= "<td>".$lNum."</td>";
                                $str .= "<td>".$row['produk_id']."</td>";
                                $str .= "<td>".$row['produk_nama']."</td>";

                                $linkDeleteProduk = base_url() . "diskon/Setting/doDeleteRelasiKelompok?id=$rowID";
                                $str .= "<td><span onclick=\"btn_alert_result('Peringatan','Apakah <r>($produk_nama)</r> akan dihapus permanen dari daftar ini??<br><span class=\'meta\'>*produk tidak benar dihapus, hanya mencabut relasi saja.</span>','$linkDeleteProduk');\" class='btn btn-xs bg-danger'><i class='fa fa-trash'></i></span></td>";
                                $str .= "</tr>";
                            }
                            $str .= "</table>";
                        }
                        else{
                            $str .= "<div class='text-center text-red'>belum ada produk relasi</div>";
                            $str .= "<div class='text-center text-red'>pilih dari daftar produk, lalu simpan..</div>";
                        }

                        $str .= "</div>";
                        $str .= "</div>";

                        $str .= "<script>

//                                    $('#btn_simpan').click(function() {
//                                        setTimeout(function(){
//                                            $('#btn_simpan').prop('disabled', true);
//                                        }, 500);
//                                    });

                                    function initSelectPicker$prod_id$cont_id(){
                                        top.$('#produk_supplier_$prod_id"."_"."$cont_id')
                                        .selectpicker({ dropdownParent: $('div') })
                                        .on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                                            // Memanggil fungsi lain saat ada perubahan
                                            console.log('Option changed:', $(this).val()); // Nilai yang dipilih
                                            console.log('Clicked Index:', clickedIndex);  // Indeks opsi yang dipilih
                                            console.log('Is Selected:', isSelected);      // Status pilihan
                                            console.log('Previous Value:', previousValue); // Nilai sebelumnya
                                            if( countObj($(this).val()) > 0 ){
                                                $('#simpan_daftar_produk_$prod_id$cont_id').prop('disabled', false)
                                            }
                                            else{
                                                $('#simpan_daftar_produk_$prod_id$cont_id').prop('disabled', true)
                                            }
                                            // Panggil fungsi lain di sini
                                            //myCustomFunction($(this).val(), clickedIndex, isSelected);
                                        });
                                        //console.log('init #produk_supplier_$prod_id"."_"."$cont_id');
                                    }

                                    initSelectPicker$prod_id$cont_id();

                                    $('#simpan_daftar_produk_$prod_id$cont_id').off();
                                    $('#simpan_daftar_produk_$prod_id$cont_id').click(function(){
                                        var selectedValues = $('#produk_supplier_$prod_id"."_"."$cont_id').val();
                                        var jmlValues = $('#$jml_id').val();
                                        var persenValues = $('#$persen_id').val();
                                        var supp_id = $prod_id;
                                        var jenis = 'kelompok';
                                        var kel_id = $cont_id;
                                        if (!selectedValues) {
                                            swal('KAMU BELUM MEMILIH PRODUK','','info');
                                            return;
                                        }
                                        $.ajax({
                                            url: 'saveProdukKelompok',
                                            method: 'POST',
                                            data: {
                                                produk_ids: selectedValues,
                                                qty: jmlValues*1,
                                                rabate: persenValues*1,
                                                supp_id: supp_id,
                                                jenis: jenis,
                                                kel_id: kel_id,
                                            },
                                            success: function(response) {
                                                console.log('Data berhasil dikirim:', response);
                                                top.$('#btn_refresh_kelompok').click();
                                                swal('Data berhasil dikirim!');
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Terjadi kesalahan:', error);
                                                top.$('#btn_refresh_kelompok').click();
                                                swal('Terjadi kesalahan saat mengirim data.');
                                            }
                                        });
                                    });

                                    /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */\n
                                    $('#nilai_$cont').off();
                                    $('#nilai_$cont').keyup(function() {
                                        var target = $('#persen_$cont');
                                        $(target).val(0);
                                        $(this).val(addCommas(removeCommas(this.value)));
                                        initBtnSimpan_$cont();
                                    });\n

                                    console.log('init untuk #nilai_$cont');
                                    /*--------mensisi diskon nilai bila kolom persen diskon yg diisi -------------- */\n
                                    $('#persen_$cont').off();
                                    $('#persen_$cont').keyup(function() {
                                        var target = $('#nilai_$cont');
                                        $(target).val(0);
                                        $(this).val(addCommas(removeCommas(this.value)));
                                        initBtnSimpan_$cont();
                                    });\n
                                    function enableBtnSimpan_$cont(){
                                        var target = $('#btn_simpan_$cont');
                                        $(target).prop('disabled', false);
                                    }\n
                                    function disableBtnSimpan_$cont(){
                                        var target = $('#btn_simpan_$cont');
                                        $(target).prop('disabled', true);
                                    }\n
                                    function initBtnSimpan_$cont(){
                                        var target1 = removeCommas($('#nilai_$cont').val());
                                        var target2 = removeCommas($('#persen_$cont').val());
                                        var target3 = removeCommas($('#jml_$cont').val());

                                        if(target3*1>0 && target1*1>0){
                                            enableBtnSimpan_$cont();
                                        }
                                        else if(target3*1>0 && target2*1>0){
                                            enableBtnSimpan_$cont();
                                        }
                                        else{
                                            disableBtnSimpan_$cont();
                                        }
                                    }\n

                                </script>";

                        $str .= "</div>";
                        $str .= "</div>";
                        $str .= "</div>";

                    }

                    $str .= "</div>"; //accordion
                }
                else{

                    $str .= "<div class='callout callout-info'>
                                <h4>Belum Ada Kelompok Diskon!</h4>
                                <p>Anda bisa menambahkan kelompok diskon pada tombol <b><r>Tambah Kelompok</r></b>.</p>
                              </div>";
                }

                $str .= "<script>
                             function initMainFunction(){
                                console.log('mainfunction init');
                                 $('#btn_tambah_kelompok').off();
                                 $('#btn_tambah_kelompok').click(function() {
                                    BootstrapDialog.show({
                                        title:'NEW DATA KELOMPOK DISKON',
                                        message: $('<div></div>').load('".base_url()."statik/Data/add/DiskonKelompok?reqVal=$prod_id'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    });
                                 });

                                 $('.edit_kelompok').off();
                                 $('.edit_kelompok').click(function() {
                                    var nama = $(this).attr('nama');
                                    var id = $(this).attr('idx');
                                    BootstrapDialog.show({
                                        title:'EDIT DATA KELOMPOK',
                                        message: $('<div></div>').load('".base_url()."statik/Data/edit/DiskonKelompok/'+id),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    });
                                 });
                                 $('#btn_refresh_kelompok').off();
                                 $('#btn_refresh_kelompok').click(function() {
                                        localStorage.setItem('btn_refresh_kelompok', 1);
//                                    $('.bootstrap-dialog-close-button button').click();
                                        top.BootstrapDialog.closeAll();
                                        setTimeout(function(){
                                            $('#btn-kelompok').click();
                                        }, 400);
                                        swal('RELOAD KELOMPOK', 'MOHON TUNGGU SEBENTAR', 'info');
                                        swal.enableLoading();
                                        setTimeout(function(){
                                            swal.close();
                                            top.initMainFunction();
                                        }, 1200);
                                 });
                                 $('.collapse').off();
                                 $('.collapse').on('shown.bs.collapse', function (e) {
                                    localStorage.setItem('openCollapse', e.target.id);
                                 });

                             }

                             initMainFunction();

                             var btn_refresh_kelompok = localStorage.getItem('btn_refresh_kelompok');
                             var lastOpenedCollapse = localStorage.getItem('openCollapse');

                             if(btn_refresh_kelompok){
                                console.log('btn_refresh_kelompok: ', btn_refresh_kelompok);
                                localStorage.removeItem('btn_refresh_kelompok');
                                console.log('MATIKAN btn_refresh_kelompok: ', localStorage.getItem('btn_refresh_kelompok'));
                                console.log('lastOpenedCollapse: ', lastOpenedCollapse);
                                 if (lastOpenedCollapse) {
                                    top.$(\"a[href='#\"+lastOpenedCollapse+\"']\").click();
                                    console.log('lastOpenedCollapse SHOWN');
                                 }
                                 else{
                                    if( $($('[data-toggle=collapse]')) ){
                                        top.$('[data-toggle=collapse]')[0].click()
                                    }
                                 }
                             }
                             else{
                                if( $('[data-toggle=collapse]').length ){
                                    top.$('[data-toggle=collapse]')[0].click()
                                }
                             }
                        </script>";

                break;
        }


        $form = "";
        $form .= "<div class='overflow-h'>";
        $form .= "<div class='container-fluid no-padding'>";
        // $form .= "<div class='border-cek col-xs-8 overflow-h'>";
        $form .= $str;
        // $form .= "</div>";
        $form .= "</div>";
        $form .= "</div>";
        $form .= "<script>$('.modal-dialog').removeClass('modal-lg').addClass('modal-lg');</script>";
        echo $form;
    }

    public function doSaveRebate(){

        $mode = $jenis = url_segment(4);
        $persens = $_POST['persen'];
        $nilai = $_POST['nilai'];
        $maxim = $_POST['maxim'];
        $supplier_id = $_POST['supplier_id'];
        $kelompok_id = $_POST['kelompok_id'];

        $this->db->trans_start();

        switch($mode){
            case "absolut":
                $this->load->model("Mdls/MdlDiskonPembelianSupplier");
                $dg = new MdlDiskonPembelianSupplier();

                $urutan = 0;
                foreach ($persens as $ix => $persen) {
                    $urutan++;
                    cekBiru($persen);
                    $ix_af = $ix + 1;
                    // $jml_maxim = isset($minims[$ix_af]) && ($minims[$ix_af] > 0) ? ($minims[$ix_af] - 1) : 0;
                    $data_barus = array(
                        // "minim" => $minims[$ix],
                        "maxim" => str_replace(',', '', $maxim[$ix]),
                        "persen" => $persen,
                        "nilai" => str_replace(',', '', $nilai[$ix]),
                        "jenis" => $jenis,
                        "supplier_id" => $supplier_id,
                        "urutan" => $urutan,
                        // "author" => my_id(),
                        "status" => 1,
                    );

                    arrPrintHijau($data_barus);
                    $dg->saveRebate($data_barus);
                    showLast_query("kuning");
                    // break;

                    /*update ke relasi satuan*/
                    // $this->load->model("Mdls/MdlProdukSatuanRelasi");
                    // echo "<div id='update_satuan'></div>";
                    // $link_update_satuan = base_url() . "Satuan/doEditRelasi?key=qty&pid=1&id=1&value=".$minims[$ix]."&tokoID=$toko_id";
                    // echo "<script>$('#update_satuan').load('$link_update_satuan');</script>";
                }
                break;
            case "kelompok":
                $this->load->model("Mdls/MdlDiskonKelompok");
                $dg = new MdlDiskonKelompok();
                $data_barus = array(
                    "maxim" => str_replace(',', '', $maxim[0]),
                    "persen" => $persens[0]*1>0 ? $persens[0]*1 : 0,
                    "nilai" => str_replace(',', '', $nilai[0]),
                    "jenis" => $jenis,
                    "supplier_id" => $supplier_id,
                    "kelompok_id" => $kelompok_id,
                    "status" => 1,
                );
                $dg->saveRebate($data_barus);
                arrPrintWebs($data_barus);
//                showLast_query("kuning");
                break;
        }

//        matiHere(__LINE__ . " belum commit");
        $this->db->trans_complete();

        echo lgShowSuccess("Sukses", "setting $jenis berhasil disimpan");

        echo "<script>
                setTimeout(function(){
                    top.$(\".modal\").modal('hide');
                }, 1000);
                setTimeout(function(){
                    top.$(\"#btn-absolut\").click();
                }, 2500);
            </script>";
    }

    public function doDeleteRebate()
    {

        $id = $_GET['id'];
//        $id_row = $_GET['id_row'];
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dg = new MdlDiskonPembelianSupplier();
        $this->db->trans_start();
        $exe = $dg->deleteRebate($id);

        showLast_query("merah");

//        matiHere("mati dulu");
        $this->db->trans_complete();
        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
//        $id_row_ = $id_row + 1;
        echo "<script>
                setTimeout(function(){
                    top.$(\".modal\").modal('hide');
                }, 1000);
                setTimeout(function(){
                    top.$(\"#btn-absolut\").click();
                }, 2500);
            </script>";
    }

    /*
     * DELETE KELOMPOK UTAMA
     */
    public function doDeleteRebateKelompok()
    {

        $id = $_GET['id'];
//        $id_row = $_GET['id_row'];
        $this->load->model("Mdls/MdlDiskonKelompok");
        $dg = new MdlDiskonKelompok();
        $this->db->trans_start();
        $exe = $dg->deleteRebate($id);

        if($exe){
            //trash juga produk kelompok jika ada yang masih ON
            $this->load->model("Mdls/MdlDiskonPembelianSupplier");
            $dps = new MdlDiskonPembelianSupplier();
            $dps->setFilters(array());
            $dps->addFilter("kelompok_id=$id");
            $rowProduk = $dps->lookupAll()->result();
            if(!empty($rowProduk)){
                foreach($rowProduk as $row){
                    $ids = $row->id;
                    $dg->deleteProdukKelompok($ids);
                }
            }
        }
        showLast_query("merah");

//        matiHere("mati dulu");
        $this->db->trans_complete();
        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
//        $id_row_ = $id_row + 1;
        echo "<script>
                top.$('#btn_refresh_kelompok').click();
            </script>";
    }

    /*
     * DELETE PRODUK RELASI KELOMPOK
     */
    public function doDeleteRelasiKelompok()
    {
        arrPrint($_GET);
        $id = $_GET['id'];
        $id_row = $_GET['id_row'];
        $this->load->model("Mdls/MdlDiskonKelompok");
        $dg = new MdlDiskonKelompok();
        $this->db->trans_start();
        $exe = $dg->deleteProdukKelompok($id);

        showLast_query("merah");
        $this->db->trans_complete();
        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
//        $id_row_ = $id_row + 1;
        echo "<script>
                top.$('#btn_refresh_kelompok').click();
            </script>";
    }

    public function viewHistory(){
        // arrPrint($_GET);
         $produk_id = $produk_ids = $_GET['id'];
         $modal_size = $_GET['modalSize'];

         $this->load->model("Mdls/MdlHargaProdukPerSupplier");
         $pp = new MdlHargaProdukPerSupplier();
         $pp->setTokoId(my_toko_id());
         $pp->setCabangId($this->cabang_id);
         $pp_datas = $pp->callHistories($produk_id);
         // showLast_query("hijau");
         // arrPrint($pp_datas);

         $src_hargas = isset($pp_datas[$produk_id]) ? $pp_datas[$produk_id] : array();
         $harga_list_0 = array();
         if (sizeof($src_hargas) > 0) {
             foreach ($src_hargas as $item) {
                 $harga_list_0[$item->jenis_value][] = $item;
             }
         }

         // $this->load->model("Mdls/MdlProduk");
         // $pr = new MdlProduk();
         // $src_pr = $pr->callSpecs($produk_id);
         // showLast_query("biru");
         // arrPrintKuning($harga_list_0);

         $this->load->model("Mdls/MdlEmployee");
         $dp = new MdlEmployee();

         $em_datas = $dp->lookupAll()->result();
         $emp_lists = array();
         if(count($em_datas) > 0){
             foreach ($em_datas as $em_data) {
                 $emp_lists[$em_data->id] = $em_data;
             }
         }

         $this->load->model("Mdls/MdlDiskonPembelian");
         $dp = new MdlDiskonPembelian();
         $dp->setTokoId(my_toko_id());
         $dp->setFilters(array());
         $condites = array(
             // "supplier_id" => $supplier_id,
             "produk_id" => $produk_id,
             // "per_supplier_diskon_id"     => $jenis,
             // "per_supplier_diskon_nama" => $jenis,
         );
         $this->db->where($condites);
         $this->db->order_by("id,per_supplier_diskon_nama","desc");
         $dp_datas = $dp->lookupAll()->result();
         showLast_query("biru");
         // arrPrintHijau($dp_datas);
         if(count($dp_datas) > 0){
             foreach ($dp_datas as $dp_data) {
                $per_supplier_diskon_nama = $dp_data->per_supplier_diskon_nama;
                $oleh_id = $dp_data->oleh_id;
                $dp_data->dtime = $dp_data->last_update;
                $dp_data->oleh_nama = $emp_lists[$oleh_id]->nama;
                 $harga_list_0[$per_supplier_diskon_nama."_dk"][] = $dp_data;

                 $diskon_list_0[$per_supplier_diskon_nama."_dk"][] = $dp_data;
             }
         }

         $this->load->model("Mdls/MdlHargaProduk");
         $hp = new MdlHargaProduk();
         $hp->setTokoId(my_toko_id());
         $hp->setCabangId($this->cabang_id);
         $hp->setFilters(array());
         $condites = array(
             "produk_id" => $produk_id,
             // "jenis_value" => $arrKey[$key_harga],
         );
         $this->db->where($condites);
         $this->db->order_by("id","desc");
         $prod_hargas = $hp->callSpecs();
         showLast_query("kuning");
         // arrPrintKuning($prod_hargas);
         if(count($prod_hargas) > 0){
             foreach ($prod_hargas[$produk_id] as $prod_harga) {
                 $harga_list_0["hr_".$prod_harga->jenis_value][] = $prod_harga;

                 $price_list_0["hr_".$prod_harga->jenis_value][] = $dp_data;
             }
         }


// matiHere(__LINE__);

         // $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
         // $dpps = new MdlDiskonPembelianPairSupplier();
         // $dpps->setFilters(array());
         // $dpps->addFilter("produk_id=$produk_ids");
         // $this->db->order_by("id", "desc");
         // $src_prs = $dpps->lookupAll()->result();
         // arrPrintHijau($harga_list_0);
         // $src_prs = $harga_list_0['hpp_supplier'];
         // $src_prs = count($src_prs) > 0 ? $src_prs : array();
         // showLast_query("merah");
         // arrPrint($src_prs);
         // arrPrint($harga_list_0);



         $p = new Layout();
         $headers = array(
             // "id",
             "id" => array(
                 "label" => "tic",
                 "attr_head" => "class='text-uppercase'",
             ),
             "dtime" => array(
                 "label" => "tanggal",
                 "attr_head" => "class='text-uppercase'",
                 "format" => "formatField_he_format",
             ),
             "nilai" => array(
                 "label" => "nilai",
                 "attr_head" => "class='text-uppercase'",
                 "format" => "formatField_he_format",
                 "format_key" => "harga",
             ),
             // "qty_min" => array(
             //     "label" => "sdk (minim pembelian)",
             //     "attr_head" => "class='text-uppercase'",
             // ),
             // "produk_rel_nama" => array(
             //     "label" => "hadiah",
             //     "attr_head" => "class='text-uppercase'",
             // ),
             // "produk_rel_qty" => array(
             //     "label" => "qty hadiah",
             //     "attr_head" => "class='text-uppercase'",
             // ),
             // "produk_rel_harga" => array(
             //     "label" => "harga hadiah",
             //     "attr_head" => "class='text-uppercase'",
             // ),
             // "start_date" => array(
             //     "label" => "tgl mulai",
             //     "attr_head" => "class='text-uppercase'",
             // ),

             "oleh_nama" => array(
                 "label" => "pic",
                 "attr_head" => "class='text-uppercase'",
             ),
             "oleh_id" => array(
                 "label" => "pic id",
                 "attr_head" => "class='text-uppercase'",
             ),
             "trash" => array(
                 "label" => "trs",
                 "attr_head" => "class='text-uppercase'",
             ),

         );
         $p->setLayoutTableHeaderKolom($headers);

         foreach ($harga_list_0 as $mode => $src_data) {
             // cekHere("$mode");
             $tbls0 = "<div class='border-cekk'>";
             $tbls0 .= $p->setLayoutTableCaption("<h4>$mode</h4>");
             $tbls0 .= $p->layout_table($src_data);
             $tbls0 .= "</div>";

             $datasTbl[$mode] = $tbls0;
         }

// arrPrintWebs($datasTbl);
         $isi_tab = array();
         // $isi_tab["hpp_supplier"] = array(
         //     "label"  => "hpp list",
         //     "active" => true,
         //     "data"   => $datasTbl['hpp_supplier'],
         //     "css"    => "bg-aqua",
         //     "class"  => "bg-aaaaa",
         // );
         // $isi_tab["hpp_supplier_0"] = array(
         //     "label"  => "dpp",
         //     // "active" => true,
         //     "data"   => $datasTbl['hpp_supplier_0'],
         //     "css"    => "bg-aqua",
         //     "class"  => "bg-aaaaa",
         // );
         // $isi_tab["jual"] = array(
         //     "label"  => "h. reguler",
         //     // "active" => true,
         //     "data"   => $datasTbl['jual'],
         //     "css"    => "bg-aqua",
         //     "class"  => "bg-aaaaa",
         // );
         // $isi_tab["jual_reseller"] = array(
         //     "label"  => "h. reseller",
         //     // "active" => true,
         //     "data"   => $datasTbl['jual_reseller'],
         //     "css"    => "bg-aqua",
         //     "class"  => "bg-aaaaa",
         // );
         // $isi_tab["jual_online"] = array(
         //     "label"  => "h. online",
         //     // "active" => true,
         //     "data"   => $datasTbl['jual_online'],
         //     "css"    => "bg-aqua",
         //     "class"  => "bg-aaaaa",
         // );
         // $isi_tab["diskon_1_dk"] = array(
         //     "label"  => "diskon 1",
         //     // "active" => true,
         //     "data"   => $datasTbl['diskon_1_dk'],
         //     "css"    => "bg-aqua",
         //     "class"  => "bg-aaaaa",
         // );
         foreach ($diskon_list_0 as $mode_0 => $src_data) {
             $isi_tab[$mode_0] = array(
                 "label"  => "$mode_0",
                 // "active" => true,
                 "data"   => $datasTbl[$mode_0],
                 "css"    => "bg-aqua",
                 "class"  => "bg-aaaaa",
             );
         }
         foreach ($price_list_0 as $mode_0 => $src_data) {
             $isi_tab[$mode_0] = array(
                 "label"  => "$mode_0",
                 "active" => $mode_0 == "hr_jual" ? "active" : "",
                 "data"   => $datasTbl[$mode_0],
                 "css"    => "bg-aqua",
                 "class"  => "bg-aaaaa",
             );
         }
// arrPrintPink($isi_tab);
         $tbls = "";
         $tbls .= "<style type='text/css'>
                .nav-tabs-custom > .nav-tabs > li{
                    margin-bottom: 3px;
                    margin-right: 3px;
                }
                .nav>li>a {
                    padding: 0 5px;
                }
            </style>";
         $tbls .= $p->layout_tabs($isi_tab);
         // $form ="";
         if($modal_size != ""){
             $tbls .= "<script>$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl');</script>";
         }

         echo $tbls;
     }

    public function viewProdukHarga_ori1()
    {
        $is_po = isset($_GET['id_item']) ? 1 : 0;
        if ($is_po == true) {
            $urlBack = $_GET['urlBack'];
            $cCode = $_GET['cCode'];
            //            cekHijau(":: $is_po :: [$cCode] ");
            $this->iterasiGerbangItem($cCode);
        }
        $req_produk_ids = isset($_GET['id_item']) ? blobDecode($_GET['id_item']) : array();
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();

        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                // $produk_id = $param_prod_harga->produk_id;
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        // showLast_query("pink", __LINE__);
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;

            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks;
        }

        /*-------------MdlDiskonPembelianSupplier-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dps = new MdlDiskonPembelianSupplier();
        $dps_srcs = $dps->lookupAll()->result();
        // showLast_query("hijau", __LINE__);
        // arrPrintPink($dps_srcs);
        $dp_speks = array();
        $dps_datas = array();
        foreach ($dps_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_supplier_id = $dp_src->supplier_id;
            $dp_diskon_id = $dp_src->per_supplier_diskon_id;
            // $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_diskon_id;
            $dp_speks['per_supplier_diskon_nama'] = $dp_src->per_supplier_diskon_nama;
            $dp_speks['supplier_id'] = $dp_src->supplier_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;

            $dps_datas[$dp_supplier_id][$dp_diskon_id] = $dp_speks;
        }
        // arrPrintHijau($dps_datas);

        $this->load->library("Diskon");
        $dk = new Diskon();
        /*-----------grosir-----------------*/
        $this->load->model("Mdls/MdlDiskonGrosir");
        $dg = new MdlDiskonGrosir();
        $dg->setTokoId(my_toko_id());
        $src_dg_obj = $dg->callProdukGrosir("");

        // showLast_query("kuning");
        // cekHere(count($src_dg_obj));
        // arrPrint(array_slice($src_dg_obj,0,1));

        foreach ($src_dg_obj as $item) {
            $dg_produk_id = $item->produk_id;
            $dg_jenis = $item->jenis;
            $dg_minim = $item->minim;
            $dg_nilai = $item->nilai;
            $dg_persen = $item->persen;
            $dg_urutan = $item->urutan;
            $dg++;
            if (!isset($pr_grosir_aktive[$dg_produk_id])) {
                $pr_grosir_aktive[$dg_produk_id] = 0;
            }
            $pr_grosir_aktive[$dg_produk_id] += 1;

            $prod_hrg_jual = isset($prod_hrg_speks[$dg_produk_id]) ? (isset($prod_hrg_speks[$dg_produk_id]["harga_list"]) ? $prod_hrg_speks[$dg_produk_id]["harga_list"]->nilai : 0) : 0;


            $produk_grosir[$dg_produk_id]["minim_$dg_urutan"] = $dg_minim;
            $produk_grosir[$dg_produk_id]["persen_$dg_urutan"] = $dg_persen;
            $data_calc = $dk->calcDiskon($prod_hrg_jual, array($dg_persen), array());
            $dg_nilai_calc = $data_calc['nilai'];
            $produk_grosir[$dg_produk_id]["nilai_$dg_urutan"] = $dg_nilai_calc;
        }
        $sortGrosir = $pr_grosir_aktive;

        // asort($sortGrosir);
        // $maxGrosir = end($sortGrosir);
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($pr_grosir_aktive,0,1,true));
        // arrPrintWebs($produk_grosir);

        // region membaca hpp rata-rata stok yang tersedia
        $this->load->model("Mdls/MdlFifoAverage");
        $ff = New MdlFifoAverage();
        $ff->setFilters(array());
        // sementara ditembak cabang id 100, nanti kalau tambah cabang diganti metode
        // sepakat selalu melihat cb -1 25/5/23
        $ff->addFilter("cabang_id='-1'");
        $arrSelect = array(
            "produk_id",
            "avg(hpp) as hpp",
        );
        $this->db->group_by("produk_id");
        $this->db->select($arrSelect);
        $ffTmp = $ff->lookupAll()->result();
        //        showLast_query("biru");
        //        arrprint(array_slice($ffTmp, 0,1));
        $arrHppAvg = array();
        foreach ($ffTmp as $ffSpec) {
            $arrHppAvg[$ffSpec->produk_id] = (array)$ffSpec;
        }
        // endregion membaca hpp rata-rata stok yang tersedia
        // tool unutk ngupdate harga list dari harga jual pada price
        foreach ($prod_hrg_speks as $pid => $param_item) {

            $harga_jual = isset($param_item["jual"]) ? $param_item["jual"]->nilai : 0;
            foreach ($param_item as $jvalue => $item_00) {
                $dbid = $item_00->id;
                $dbnilai = $item_00->nilai;

                if ($jvalue == "harga_list") {
                    // cekBiru("update $dbnilai | $pid >> $harga_jual");
                    $dtUpds = array("nilai" => $harga_jual);
                    $kondisi = array("id" => $dbid);
                    // $hp->updateData($kondisi, $dtUpds);
                    // showLast_query("merah");
                }
            }
        }
        // tool

        /*-------produk_per_supplier-------*/
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();

        if (isset($_GET['suppliers_id'])) {
            $condites = array(
                "suppliers_id" => $_GET['suppliers_id'],
            );
            // $this->db->where($condites);
        }
        $src_pps_0 = $pps->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintWebs($src_pps_0);
        $produk_suppliers = array();
        foreach ($src_pps_0 as $item) {

            $produk_suppliers[$item->produk_id][] = $item->suppliers_id;
            $produk_supplier[$item->produk_id] = $item->suppliers_id;
        }
        // arrPrintHijau($produk_supplier);
        // arrPrintWebs($produk_suppliers);

        /*-------harga_produk_per_supplier-------*/
        $this->load->model("Mdls/MdlHargaProdukPerSupplier");
        $hpps = new MdlHargaProdukPerSupplier();
        $src_hpps_0 = $hpps->lookupAll()->result();
        // showLast_query('kuning');
        // $prod_hargas = array();
        foreach ($src_hpps_0 as $itemHpps) {
            // arrPrintHijau($itemHpps);
            $param_prod_harga = (array)$itemHpps;
            $produk_id = $itemHpps->produk_id;
            $jenis_value = $itemHpps->jenis_value;
            $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            $prod_hargas[$produk_id][] = (object)$param_prod_harga;
        }
        // arrPrintHijau($src_hpps_0);
        // arrPrintKuning($prod_hargas);
        // arrPrint($prod_hrg_speks);

        /* ----------------------------------------------------------
       * freeproduk relasi
       * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $dpps = new MdlDiskonPembelianPairSupplier();
        $src_freeProduks = $dpps->callSpecs();
        $dp_freeproduk = array_keys($src_freeProduks);
        arrPrintKuning($dp_freeproduk);

        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();

        if (ipadd() == "202.65.117.72") {
            // if (ipadd() == "202.65.117.80") {
            //            $this->db->limit(2);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
            $this->db->where_in("id", array("14", "178"));
        }
        // $this->db->where_in("supplier_id",array("1",));
        // $this->db->where_in("supplier_id",array("4",));
        if (count($req_produk_ids) > 0) {
            $this->db->where_in("id", $req_produk_ids);
        }
        $src_pr_obj_00 = $pr->callSpecs();
        // showLast_query("hijau");
        $filter_4 = url_segment(4);

        switch ($filter_4) {
            case "grosir":
                foreach ($pr_grosir_aktive as $item_id => $jml_grosir) {
                    if (isset($src_pr_obj_00[$item_id])) {
                        $src_pr_obj[$item_id] = $src_pr_obj_00[$item_id];
                    }
                }
                break;
            case "non_diskon":
                $src_pr_obj = array_diff_key($src_pr_obj_00, $pr_grosir_aktive);
                break;
            default:
                $src_pr_obj = $src_pr_obj_00;
                break;
        }

        $sortGrosir = array_intersect_key($pr_grosir_aktive, $src_pr_obj);
        asort($sortGrosir);
        $maxGrosir = end($sortGrosir);

        // $maxGrosir = 2;
        // arrPrintKuning($maxGrosir);
        // arrPrintHijau(array_slice($sortGrosir,0,3, true));
        // arrPrintKuning($src_pr_obj);
        // arrPrintKuning(url_segment());
        // cekHere("all>".sizeof($src_pr_obj_00) ." diskon>". sizeof($pr_grosir_aktive) ." yg tampil>". sizeof($src_pr_obj));
        // cekHere(sizeof($src_pr_obj));
        // arrPrint(my_ppn_factor());

        /* ----------------------------------------------------------
         * diambilkandari MdlSupplierDiskon
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlSupplierDiskon");
        $spd = New MdlSupplierDiskon();
        $spd->addFilter("jenis='reguler'");
        $spdTmp = $spd->lookupAll()->result();
        // showLast_query("kuning", __LINE__);
        foreach ($spdTmp as $spdSpec) {
            $kolomDiskonPembeliansId[$spdSpec->nama] = $spdSpec->id;
            $kolomDiskonPembelians[$spdSpec->nama] = $spdSpec->label;
        }

        $kolomKreditnotePembelians = array(
            // "hpp_ppn"       => "hpp + ppn",
            // "diskon_1" => "event billing",
            // "diskon_2" => "otp rebate",
            // "diskon_3" => "monthly rebate",
            // "diskon_4" => "blind bonus",
            // "diskon_5" => "add suport",
            // "pph23"     => "pph23",
        );
        $kolomPembelians = $kolomDiskonPembelians + $kolomKreditnotePembelians;

        // arrPrint($kolomDiskonPembeliansId);
        // arrPrint($kolomPembelians);

        /* -----------------------------------
         * master data builder
         * ----------------------------------*/
        $arrAddData = array();
        $diskonPembelians = array();
        $row_id = 999;
        foreach ($src_pr_obj as $prod_id => $item) {
            $row_id++;
            // arrPrintHijau($item);
            $diskon_persen = $item->diskon_persen * 1;
            $nama = $item->nama;
            $spl_id = $item->supplier_id;
            $premi_jual = isset($item->premi_jual) ? $item->premi_jual : 0;
            $biaya_jual = isset($item->biaya_jual) ? $item->biaya_jual : 0;
            $premi_beli = isset($item->premi_beli) ? $item->premi_beli : 0;
            $biaya_beli = isset($item->biaya_beli) ? $item->biaya_beli : 0;
            $diskon_beli = isset($item->diskon_beli) ? $item->diskon_beli : 0;

            /* -----------------------------------------------
             * update relasi ke-supplier
             * -----------------------------------------------*/
            // $spl_id_new = isset($produk_supplier[$prod_id]) ? $produk_supplier[$prod_id] : "";
            // $upCondites = array(
            //   "id" => $prod_id,
            //   "supplier_id" => null,
            // );
            // $upDatas = array(
            //     "supplier_id" => $spl_id_new,
            // );
            // $pr->updateData($upCondites,$upDatas);
            // showLast_query("biru");

            // /*----delete produkpersupplier*/
            // $upCondites2 = array(
            //     "suppliers_id !=" => $spl_id_new,
            //     "produk_id" => $prod_id,
            // );
            // $upDatas2 = array(
            //     "trash" => 1,
            // );
            // $pps->updateData($upCondites2,$upDatas2);
            // showLast_query("merah");

            $harga_speks = array();
            if (isset($prod_hargas[$prod_id])) {
                foreach ($prod_hargas[$prod_id] as $spek_harga) {
                    $harga_speks[$spek_harga->jenis_value] = $spek_harga;
                }
            }

            $hrg_beli = isset($arrHppAvg[$prod_id]["hpp_nppv"]) ? ($arrHppAvg[$prod_id]["hpp_nppv"] * 1) : 0;
            $hrg_pp = isset($arrHppAvg[$prod_id]["hpp"]) ? ($arrHppAvg[$prod_id]["hpp"] * 1) : 0;
            $hrg_pp_f = format_harga($hrg_pp);

            // $hpp_supplier = isset($harga_speks['hpp']) ? $harga_speks['hpp']->nilai * 1 : 0;
            //            $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            if (isset($_SESSION[$cCode]["items"][$prod_id])) {
                $hpp_supplier = $_SESSION[$cCode]["items"][$prod_id]["hpp"];
            }
            else {
                $hpp_supplier = isset($harga_speks['hpp_supplier']) ? ($harga_speks['hpp_supplier']->nilai * 1) : 0;
            }
            // $hpp_supplier = $hrg_pp;

            $hrg_jual_online = isset($harga_speks['jual_online']) ? $harga_speks['jual_online']->nilai * 1 : 0;
            $hrg_list_jual = isset($harga_speks['jual']) ? $harga_speks['jual']->nilai * 1 : 0;
            $hrg_list_0 = $hrg_list_reseller = isset($harga_speks['jual_reseller']) ? $harga_speks['jual_reseller']->nilai * 1 : 0;
            // ---------------------------
            $hrg_list = $hrg_list_0 > 0 ? $hrg_list_0 : $hrg_list_jual;
            $diskon_enol = $dk->calcDiskon($hrg_list, array("satu" => $diskon_persen), array(), "diskon", $biaya_jual);
            $nDiskonJual = $diskon_enol['nilai'];
            // ----------------------------------------------
            $diskon_satu = $dk->calcDiskon($hrg_list, array("satu" => $premi_jual), array(), "premi", $biaya_jual);
            $nPremiJual = $diskon_satu['nilai'];
            $diskon_nilai = $diskon_satu['nilai'];
            $hrg_jual = $hrg_list - $nDiskonJual + $nPremiJual;

            $hrg_margin = $hrg_jual > 0 ? (($hrg_jual - $hrg_beli) / $hrg_jual) * 100 : 0;
            $jml_grosir = isset($pr_grosir_aktive[$prod_id]) ? $pr_grosir_aktive[$prod_id] : 0;
            $grosir_cek = $jml_grosir > 0 ? "<i class='fa fa-check text-green'> $jml_grosir</i>" : "";
            $grosir_yes = $jml_grosir > 0 ? "yes" : "no";

            $link_update_diskon_pembelian = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_pembelian&nilai=";
            $link_update_hrg_jual_online = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_online&nilai=";
            $link_update_hrg_list_reseller = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual_reseller&nilai=";
            $link_update_hrg_list = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=jual&nilai=";
            $link_update_premi_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_jual&nilai=";
            $link_update_biaya_jual = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_jual&nilai=";
            $link_update = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_persen&nilai=";
            $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier&nilai=";
            $link_update_hrg_beli_supplier_0 = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp_supplier_0&nilai=";
            // $link_update_hrg_beli_supplier = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=hpp&nilai=";

            $url_grosir = base_url() . "diskon/setting/viewGrosir?id=$prod_id";
            $link_grosir = modalDialogBtn("grosir $nama", $url_grosir);
            $url_satuan = base_url() . "diskon/Setting/viewSatuan?id=$prod_id";
            $link_satuan = modalDialogBtn("Satuan $nama", $url_satuan);
            $url_scheduler = base_url() . "diskon/Setting/viewScheduler?id=$prod_id";
            $link_scheduler = modalDialogBtn("Scheduler diskon $nama", $url_scheduler);
            $item_array = (array)$item;

            if (($premi_jual * 1) > 0) {
                // if($diskon_persen > 0){
                $disabled_diskon = "disabled";
                $disabled_premi = "";
            }
            elseif ($premi_jual == 0 && $diskon_persen == 0) {
                $disabled_premi = "";
                $disabled_diskon = "";
            }
            else {
                $disabled_premi = "disabled";
                $disabled_diskon = "";
            }

            // $item_array["biaya_jual"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_jual'+this.value);\" value='$biaya_jual'>";
            $item_array["biaya_jual"] = "<input id='biaya_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$biaya_jual' >";
            $item_array["biaya_jual_nilai"] = $biaya_jual;
            // ----------------
            $item_array["harga_jual_online_nilai"] = $hrg_jual_online;
            $item_array["harga_jual_online"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_jual_online'+this.value);\" value='$hrg_jual_online'>";
            $item_array["harga_jual_online_nppn"] = ($hrg_jual_online * my_ppn_factor() / 100) + $hrg_jual_online;
            // ---------------------------------------------
            $item_array["harga_list"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_list_jual'>";
            $item_array["harga_list_nppn"] = ($hrg_list_jual * my_ppn_factor() / 100) + $hrg_list_jual;
            $item_array["harga_jual"] = $hrg_list_jual;
            // ---------------------------------------------
            $item_array["harga_list_reseller"] = "<input id='harga_list_reseller_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list_reseller'+this.value);\" value='$hrg_list_reseller'>";
            $item_array["harga_list_reseller_nppn"] = ($hrg_list_reseller * my_ppn_factor() / 100) + $hrg_list_reseller;
            $item_array["harga_jual_reseller"] = $hrg_list_reseller;
            // ----------------
            $item_array["premi_jual"] = "<input $disabled_premi id='premi_jual_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_premi_jual'+this.value);trigger_nilai('premi_jual',this.value,$prod_id,$row_id);\" value='$premi_jual'>";
            $item_array["premi_juale"] = $premi_jual;
            $item_array["premi_jual_nilai"] = "<input $disabled_premi id='premi_jual_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('premi_jual_nilai',this.value,$prod_id,$row_id);\" value='$nPremiJual'>";
            $item_array["premi_jual_nilaine"] = $nPremiJual;
            // ----------------
            $item_array["diskon_persen"] = "<input $disabled_diskon id='diskon_persen_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update'+this.value);trigger_nilai('diskon_persen',this.value,$prod_id,$row_id);\" value='$diskon_persen'>";
            $item_array["diskon_persene"] = $diskon_persen;
            $item_array["diskon_nilai"] = "<input $disabled_diskon id='diskon_nilai_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_nilai('diskon_nilai',this.value,$prod_id,$row_id);\" value='$nDiskonJual'>";
            $item_array["diskon_nilaine"] = $diskon_persen;

            /* -------------------------------------------
             * button action
             * -------------------------------------------*/
            $btn_grosir = "";
            $btn_grosir .= "<div class='btn-group'>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sat_$prod_id' class='btn-satuan btn btn-xs btn-danger tombol-action btn-satuan'>satuan</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir$grosir_cek</button>";
            $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='gro_$prod_id' class='btn-grosir btn btn-xs btn-warning tombol-action btn-grosir'>grosir</button>";
            // $btn_grosir .= "<button type='button' pid='$prod_id' nm='$nama' id='sch_$prod_id' class='btn-scheduler btn btn-xs btn-info tombol-action btn-scheduler'>scheduler</button>";
            $btn_grosir .= "</div>";

            $item_array["grosir_cek"] = "$grosir_yes $grosir_cek";
            $item_array["grosir"] = "$btn_grosir";
            $item_array["harga_aft"] = $hrg_jual;
            $item_array["harga_aft_nppn"] = ($hrg_jual * my_ppn_factor() / 100) + $hrg_jual;
            $item_array["margin"] = $hrg_margin;

            /*------------------------------------------------
             * PEMBELIAN
             * --------------------------------------------------*/
            $diskon_dua = $dk->calcDiskon($hrg_list, array("dua" => $diskon_beli), array(), "diskon", $biaya_beli);
            $nDiskonBeli = $diskon_dua['nilai'];
            $diskon_tiga = $dk->calcDiskon($hrg_list, array("dua" => $premi_beli), array(), "premi", $biaya_beli);
            $nPremiBeli = $diskon_tiga['nilai'];


            $item_array["harga_beli_0"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_0_$prod_id' class='text-right form-control' type='text' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier'+removeCommas(this.value));trigger_nilai('hpp_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier'>";
            /* -------------------------------------------------------------------------------------------------------
             * diskon pembelian rebate custom sebelum ppn
             * -------------------------------------------------------------------------------------------------------*/
            $persen_dp_0 = $dp_datas[$prod_id]['diskon_0']['persen'];
            // $diskon_nilai_0 = $dp_datas[$prod_id]['diskon_0']['nilai'];
            $diskon_nilai_0 = ($persen_dp_0 / 100) * $hpp_supplier;
            $hpp_supplier_0 = $hpp_supplier - $diskon_nilai_0;
            $hrg_pp_nppn = ($hpp_supplier_0 * (my_ppn_factor() / 100)) + $hpp_supplier_0;
            $no_kolom = 0;
            $diskon_0_id = "0";
            $item__persen_0 = "<input id='diskon_0_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'persen');\" value='$persen_dp_0'>";
            $item__nilai_0 = "<input id='diskon_0_nilai_$prod_id' placeholder='000' class='text-right form-control' size='3' type='text' minx='0' steps='1' onblur=\"trigger_hpp('diskon_0',this.value,$prod_id,$row_id,$no_kolom,$diskon_0_id,'nilai');\" value='$diskon_nilai_0'>";
            $hpp_berjalan = "<br><input type='text' id='diskon_0_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";
            $item_array["diskon_0"] = "$item__persen_0 $item__nilai_0 $hpp_berjalan";

            // $item__persen_00 = "<input id='diskon_00_persen_$prod_id' placeholder='%' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'tes','persen');\" value='$persen_dp'>";
            // $item__nilai_00 = "<input id='diskon_00_nilai_$prod_id' placeholder='000' class='text-right form-control' size='4' type='text' minx='0' steps='1' onblur=\"trigger_diskon_00('diskon_00',this.value,$prod_id,$row_id,$no_kolom,'test,'nilai');\" value='$diskon_persen_0'>";
            // $hpp_berjalan_00 = "<br><input type='text' id='diskon_00_hpp_$prod_id' class='text-right form-control shadow_nilai hidden' style='width: 60px;' value='$hpp_supplier_0'>";

            /* -----------------------------------------
             * free produk
             * -----------------------------------------*/
            arrPrintKuning($dp_freeproduk);
            if (in_array($prod_id, $dp_freeproduk)) {
                $btn_warna = "btn-warning";
                $btn_persen_00_setting = 1;
            }
            else {
                $btn_warna = "btn-info";
                $btn_persen_00_setting = 0;
            }
            $link_modal = MODUL_PATH . "Setting/settingFreeProdukPembelian/$prod_id";
            $judul_form = strtoupper("free produk untuk $nama");
            $modal_btn = modalDialogBtn($judul_form, $link_modal);
            $btn_persen_00 = "<button type='button' id='diskon_00_btn_$prod_id' class='btn $btn_warna btn-block text-uppercase' onclick=\"kirim_tanda('$row_id');$modal_btn \">sett</button>";
            $item_array["diskon_00"] = "$btn_persen_00";
            $item_array["diskon_00_setting"] = "$btn_persen_00_setting";
            // -------------------------------------------------------------------------------------------------------

            $item_array["harga_beli_be_tax"] = "<input size='5' placeholder='$hrg_pp_f' id='harga_beli_be_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"$('#anu').load('$link_update_hrg_beli_supplier_0'+removeCommas(this.value));trigger_nilai('hpp_supplier_0',removeCommas(this.value),$prod_id,$row_id);\" value='$hpp_supplier_0'>";;
            $item_array["harga_beli_af_tax"] = "<input size='5' placeholder='$hrg_pp_nppn' id='harga_beli_af_tax_$prod_id' class='text-right form-control' type='text' maxx='100' minx='0' steps='1' onblur=\"trigger_nilai('hpp_nppn_supplier',removeCommas(this.value),$prod_id,$row_id);\" value='$hrg_pp_nppn'>";;
            //            $item_array["harga_beli_af_tax"] = $hrg_pp_nppn;

            $link_update_biaya_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=biaya_beli&nilai=";
            $link_update_diskon_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=diskon_beli&nilai=";
            $link_update_premi_beli = base_url() . "diskon/Setting/do_update?id=$prod_id&ky=premi_beli&nilai=";

            $item_array["biaya_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_biaya_beli'+this.value);\" value='$biaya_beli'>";
            $item_array["diskon_beli"] = "<input class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_diskon_beli'+this.value);\" value='$diskon_beli'>";

            /* ----------------------------------------------------------------------
             * komponen harga tandas
             * diskon yg diterima
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            $src_dps = isset($dp_datas[$prod_id]) ? $dp_datas[$prod_id] : array();
            $src_dpersupplier = isset($dps_datas[$spl_id]) ? $dps_datas[$spl_id] : 0;

            $metode_dpp_berjalan = false;
            $total_nilai_dp = 0;
            $hpp_supplier_2 = 0;
            foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $kp_id = $kolomDiskonPembeliansId[$kp_key];
                $setting_persen = $src_dpersupplier[$kp_id]["persen"];

                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $iddppnilai = $kp_key . "_dpp_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp

                //                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen']*1>0 ? round($src_dps[$kp_key]['persen']) : $setting_persen;//ORI
                $persen_dp = isset($src_dps[$kp_key]) && $src_dps[$kp_key]['persen'] * 1 > 0 ? number_format((float)$src_dps[$kp_key]['persen'], 2, '.', '') : 0;

                /* ---------------------------------------------------------------------------------
                 * bila ada nilai absolut gukakan klo tdk ada hitungkan dr persen setting per supplier
                 * ---------------------------------------------------------------------------------*/
                if ($metode_dpp_berjalan == true) {
                    if ($no_kolom == 1) {
                        $nilai_dp_calc = ($persen_dp / 100) * $hrg_pp_nppn; // dpp berjalan

                        $hpp_supplier_2 = $hrg_pp_nppn - $nilai_dp_calc;

                        // cekHere("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }
                    else {
                        $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2;
                        $hpp_supplier_2 = $hpp_supplier_2 - $nilai_dp_calc;

                        // cekHere(__LINE__ . " $hpp_supplier_2");
                        // cekHijau("$prod_id>>>$no_kolom | $hpp_supplier_2 || $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_2");
                    }

                    // $nilai_dp = isset($src_dps[$kp_key]) ? $src_dps[$kp_key]['nilai'] : $nilai_dp_calc;
                    if (isset($src_dps[$kp_key])) {
                        $nilai_dp = $src_dps[$kp_key]['nilai'];
                    }
                    else {
                        $nilai_dp = $nilai_dp_calc;
                        // menulis ke setting diskon supplier
                        if (isset($cCode) && ($cCode != null)) {
                            $arrAddData[$prod_id][$kp_id] = array(
                                "per_supplier_diskon_id" => $kp_id,
                                "per_supplier_diskon_nama" => $kp_key,
                                "persen" => $persen_dp,
                                "nilai" => $nilai_dp,
                                "produk_id" => $prod_id,
                                "supplier_id" => $spl_id,
                                "status" => 1,
                            );
                        }
                    }
                }
                else {
                    $nilai_dp_calc = ($persen_dp / 100) * $hpp_supplier_0;
                    $nilai_dp = $nilai_dp_calc;
                }

                $nilai_dp_f = round($nilai_dp);
                $dpp_berjalan = "<br><input type='text' class='text-right form-control shadow_nilai hidden' style='width: 85px;' id='$iddppnilai' value='$hpp_supplier_2'>";
                $total_nilai_dp += $nilai_dp;
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1'  onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'persen');\" value='$persen_dp'>";
                $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id,'nilai');\" value='$nilai_dp_f'> $dpp_berjalan";

                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_nama'] = $kp_key;
                $dataDiskonPembelian[$kp_key]['per_supplier_diskon_id'] = $kp_id;
                $dataDiskonPembelian[$kp_key]['persen'] = $persen_dp;
                $dataDiskonPembelian[$kp_key]['nilai'] = $nilai_dp;
                $dataDiskonPembelian[$kp_key]['supplier_id'] = $spl_id;
                $dataDiskonPembelian[$kp_key]['status'] = 1;
            }

            /* ----------------------------------------------------------------------
             * diskon untuk pembayaran kredit note
             * ----------------------------------------------------------------------*/
            $item__persen = "";
            $no_kolom = 0;
            foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
                $no_kolom++;
                $idpersen = $kp_key . "_persen_" . $prod_id;
                $idnilai = $kp_key . "_nilai_" . $prod_id;
                $diskon_id = $kolomDiskonPembeliansId[$kp_key];// id diskon dari tabel per_supplier_diskon, dimasukkan ke trigger_hpp
                $item__persen = "<input id='$idpersen' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"trigger_hpp('$kp_key',this.value,$prod_id,$row_id,$no_kolom,$diskon_id);\" value='$premi_beli'>";
                $item_array[$kp_key] = "$item__persen <input id='$idnilai' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"\" value='$premi_beli'>";
            }

            $diskon_pajak = $total_nilai_dp * ($this->pph23 / 100);
            $total_nilai_dp_af_tax = $total_nilai_dp - $diskon_pajak;
            $hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp); //ORI
            // $hrg_beli = $hrg_beli_be_pph + $diskon_pajak; //ORI
            // $hrg_beli = $hrg_pp_nppn - $total_nilai_dp;
            $hrg_beli = $hpp_supplier_0 - $total_nilai_dp_af_tax;
            $hrg_beli_af_tax = $hrg_beli * ((100 + my_ppn_factor()) / 100);
            $hrg_beli_npph = ($hrg_pp_nppn - $total_nilai_dp) + $diskon_pajak;
            // cekKuning("$hrg_beli_be_pph = ($hpp_supplier - $total_nilai_dp);");
            // cekHijau("$prod_id :: $hpp_supplier - $total_nilai_dp + $diskon_pajak ==== $hrg_beli");
            /*---tandas---*/
            $item_array["harga_beline_be_pph"] = $hrg_beli_be_pph;
            $item_array["total_nilai_dp"] = $total_nilai_dp;
            $item_array["total_nilai_dp_af_tax"] = $total_nilai_dp_af_tax;
            $item_array["harga_pajak_beline"] = $diskon_pajak;
            $item_array["harga_beline"] = $hrg_beli;
            // $item_array["harga_beline_af_tax"] = $hrg_beli_npph;
            $item_array["harga_beline_af_tax"] = $hrg_beli_af_tax;
            $item_array["harga_beli"] = "<input id='harga_list_$prod_id' class='text-right form-control' type='number' max='100' min='0' step='1' onblur=\"$('#anu').load('$link_update_hrg_list'+this.value);\" value='$hrg_beli'>";

            /*---validasi diskon----*/
            $gr_persen_1 = "";
            $diskon_cek = 0;
            $diskon_ygbener = 0;
            $grosir_cek = "no";
            if (isset($produk_grosir[$prod_id])) {
                // cekMerah($hrg_list);
                $data_grosiers = $produk_grosir[$prod_id];
                $gr_persen_1 = isset($data_grosiers['persen_1']) ? $data_grosiers['persen_1'] : 0;

                $gr_nilai_1a = isset($data_grosiers['nilai_1']) ? $data_grosiers['nilai_1'] : 0;
                $diskon_nilai_1 = $dk->calcDiskon($hrg_list, array("satu" => $gr_persen_1));
                // cekHere("id:$prod_id: $gr_nilai_1a");
                // arrPrint($diskon_nilai_1);
                $gr_nilai_1 = isset($data_grosiers['nilai_1']) ? $diskon_nilai_1['nilai'] : 0;

                $nilai_1_calc = $hrg_list * ($gr_persen_1 / 100);
                $nilai_1_calc_f = round($nilai_1_calc);
                // cekBiru("$nilai_1_calc_f");
                // arrPrintPink($produk_grosir[$prod_id]);


                $diskon_cek = $nilai_1_calc_f != $gr_nilai_1 ? 1 : 0;

                if ($diskon_cek == 1) {
                    $diskon_ygbener = ($gr_nilai_1 / $hrg_list) * 100;
                    $harga_ygbener = $hrg_list - $gr_nilai_1;

                    $dg_condites = array(
                        "produk_id" => $prod_id,
                        "urutan" => 1,
                        "trash" => 0,
                        "status" => 1,
                        "jenis" => "produk_grosir",
                        "toko_id" => my_toko_id(),
                    );
                    $dg_datas = array(
                        "persen" => $diskon_ygbener,
                        "harga" => $harga_ygbener,
                    );
                    // $dg->setTableName("diskon");
                    // $dg->updateData($dg_condites, $dg_datas);
                    // showLast_query("merah");
                    if ($prod_id == "9076") {
                        arrPrintKuning($dg_datas);
                        // matiHere(__LINE__);
                    }
                    $grosir_cek = "yes";
                }

                // arrPrintKuning($data_grosiers);
            }

            $item_array["diskon_cek"] = $diskon_cek;
            $item_array["diskon_ygbener"] = $diskon_ygbener;
            $item_array["grosir_cek"] = $grosir_cek;

            //            $item_array["harga_beli"] = $harga_beli;
            //            $item_array["biaya_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-biaya_beli'  type='number' max='100' min='0' step='1' value='$biaya_beli'>";
            //            $item_array["biaya_beli"]   = $biaya_beli;
            //            $item_array["diskon_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-diskon_beli' type='number' max='100' min='0' step='1' value='$diskon_beli'>";
            //            $item_array["diskon_beli"]  = $diskon_beli;
            //            $item_array["premi_beli"] = "<input class='text-right form-control form-edit' pid='$prod_id' id='form-premi_beli'  type='number' max='100' min='0' step='1' value='$premi_beli'>";
            //            $item_array["premi_beli"]   = $premi_beli;
            $drosirs = isset($produk_grosir[$prod_id]) ? $produk_grosir[$prod_id] : array();
            $src_pr[$prod_id] = $item_array + $drosirs;

            /* --------------------------------------------
             * update ke diskon per produk dikirim dari komponen tandas
             * --------------------------------------------*/
            $diskonPembelians[$prod_id] = $dataDiskonPembelian;

        }

        if (sizeof($arrAddData) > 0) {
            $this->db->trans_start();

            foreach ($arrAddData as $produk_id => $subSpec) {
                foreach ($subSpec as $disk_id => $subData) {
                    $dp = new MdlDiskonPembelian();
                    $dp->addData($subData);
                    //                    showLast_query("hijau");
                }
            }
            //                matiHere("belum comit " . __LINE__);
            $this->db->trans_complete();
        }
        if (isset($cCode) && ($cCode != null)) {
            $this->iterasiGerbangItem($cCode);
        }


        /* ------------------------------------------------------------
         * $dp
         * untuk ngupdate diskon pembelian per-produk
         * ------------------------------------------------------------*/
        // $this
        // $cek = $dp->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintPink($diskonPembelians);
        // ------------------------------------------------------------end----


        /* ---------------------
         * dta produk per supplier
         * ---------------------*/

        $vendor = false;
        if ($vendor == true) {
            $this->load->model("Mdls/MdlProdukPerSupplier");
            $pps = new MdlProdukPerSupplier();
            if (isset($_GET['suppliers_id'])) {
                $condites = array(
                    "suppliers_id" => $_GET['suppliers_id'],
                );
                $this->db->where($condites);
            }
            $src_pps_0 = $pps->lookupAll()->result();// showLast_query("kuning");
            // arrPrint($src_pps_0);
            foreach ($src_pps_0 as $src_pp) {
                $suppliers_id = $src_pp->suppliers_id;
                $produk_id = $src_pp->produk_id;

                $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
                // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
                $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
            }
        }

        $arrHeaders_01 = array(
            "id" => array(
                "label" => "pid",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            // "grosir_cek" => array(
            //     "label" => "grosir",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "barcode" => array(
                "label" => "barcode",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "nama" => array(
                "label" => "nama produk",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            // "satuan" => array(
            //     "label" => "satuan",
            //     "attr_header" => "rowspan='2'",
            //     "span_header" => true,
            // ),
            "merek_nama" => array(
                "label" => "merek",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
            "kategori_nama" => array(
                "label" => "kat",
                "attr_header" => "rowspan='2'",
                "span_header" => true,
            ),
        );

        // -----------------------------------------beli-beli----------------------------------
        $arrHeaders_02 = array(
            "harga_beli_0" => array(
                "label" => "harga list",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "diskon_00" => array(
                "label" => "free produk",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "diskon_00_setting",
            ),
            "diskon_0" => array(
                "label" => "diskon 0",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_be_tax" => array(
                "label" => "dpp",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
            "harga_beli_af_tax" => array(
                "label" => "incl. ppn",
                "attr_header" => "class='bg-danger'",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_beli",
                "data_order" => "harga_beline",
            ),
        );

        foreach ($kolomDiskonPembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-warning'",
                "attr" => "class='text-right bg-warning'",
                "top_parent" => "pembelian",
                "data_order" => false,
            );
        }

        foreach ($kolomKreditnotePembelians as $kp_key => $kp_label) {
            $arrHeaders_02[$kp_key] = array(
                "label" => "$kp_label",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "top_parent" => "pembelian",
                "data_order" => false,
            );
        }

        // "diskon_beli"   => array(
        //     "label"  => "diskon pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "premi_beli"    => array(
        //     "label"  => "premi pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),
        // "biaya_beli"    => array(
        //     "label"  => "biaya pembelian",
        //     "attr"   => "class='text-right bg-warning'",
        //     "format" => "formatField_he_format",
        // ),

        /* ----------------------------------------------
         * disembunyikan ngikuti kolom dari client
         * ----------------------------------------------*/
        // $arrHeaders_02["total_nilai_dp"] = array(
        //     "label"       => "sub diskon sb pph",
        //     "attr_header" => "class='bg-danger'",
        //     "attr"        => "class='text-right bg-danger'",
        //     "format"      => "formatField_he_format",
        //     "format_key"  => "harga",
        //     "top_parent"  => "pembelian",
        //     "data_order"  => "total_nilai_dp",
        // );
        // $arrHeaders_02["harga_pajak_beline"] = array(
        //     "label"       => "pph23",
        //     "attr_header" => "class='bg-danger'",
        //     "attr"        => "class='text-right bg-danger'",
        //     "format"      => "formatField_he_format",
        //     "format_key"  => "harga",
        //     "top_parent"  => "pembelian",
        //     "data_order"  => "harga_beline",
        // );
        $arrHeaders_02["total_nilai_dp_af_tax"] = array(
            "label" => "total rebate st&nbsp;pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "total_nilai_dp",
        );
        $arrHeaders_02["harga_beline"] = array(
            "label" => "harga tandas sb pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "harga_beline",
        );
        $arrHeaders_02["harga_beline_af_tax"] = array(
            "label" => "harga tandas st pph",
            "attr_header" => "class='bg-danger'",
            "attr" => "class='text-right bg-danger'",
            "format" => "formatField_he_format",
            "format_key" => "harga",
            "top_parent" => "pembelian",
            "data_order" => "harga_beline_af_tax",
        );

        // -----------------------------------------jual-jual----------------------------------
        $arrHeaders_03 = array(
            // "margin"        => array(
            //     "label"  => "margin (%)",
            //     "attr"   => "class='text-right'",
            //     "format" => "formatField_he_format",
            // ),
            /*---penjualan---*/
            "harga_jual_online_nilai" => array(
                "label" => "online incl. ppn",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_online_nilai",
            ),
            // "harga_jual_online_nppn"   => array(
            //     "label"       => "online incl. ppn",
            //     "attr_header" => "class='bg-aqua'",
            //     "attr"        => "class='text-right bg-aqua'",
            //     "format"      => "formatField_he_format",
            //     "format_key"  => "harga",
            //     "top_parent"  => "harga_list",
            //     "data_order"  => "harga_jual_online_nppn",
            // ),
            //---------------------
            "harga_jual" => array(
                "label" => "end user incl. ppn",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual",
            ),
            // "harga_list_nppn"          => array(
            //     "label"       => "end user incl. ppn",
            //     "attr_header" => "class='bg-aqua'",
            //     "attr"        => "class='text-right bg-aqua'",
            //     "format"      => "formatField_he_format",
            //     // "format_key" => "harga",
            //     "top_parent"  => "harga_list",
            //     "data_order"  => "harga_list_nppn",
            // ),
            //----------
            "harga_jual_reseller" => array(
                "label" => "dealer incl. ppn",
                "attr_header" => "class='bg-info'",
                "attr" => "class='text-right bg-info'",
                "format" => "formatField_he_format",
                // "format_key" => "harga",
                "top_parent" => "harga_list",
                "data_order" => "harga_jual_reseller",
            ),
            // "harga_list_reseller_nppn" => array(
            //     "label"       => "dealer incl. ppn",
            //     "attr_header" => "class='bg-aqua'",
            //     "attr"        => "class='text-right bg-aqua'",
            //     "format"      => "formatField_he_format",
            //     // "format_key" => "harga",
            //     "top_parent"  => "harga_list",
            //     "data_order"  => "harga_list_reseller_nppn",
            // ),
            // ------------------------------------------
            // "diskon_persen"     => array(
            //     "label"      => "persen",
            //     "attr_header" => "class='bg-purple'",
            //     "attr"       => "class='text-right bg-purple'",
            //     "top_parent" => "simpel",
            //     // "top_sub_parent" => "simpel",
            //     "data_order" => "diskon_persene",
            // ),
            // "diskon_nilai"     => array(
            //     "label"      => "nilai",
            //     "attr_header" => "class='bg-purple'",
            //     "attr"       => "class='text-right bg-purple'",
            //     "top_parent" => "simpel",
            //     // "top_sub_parent" => "simpel",
            //     "data_order" => "diskon_nilaine",
            // ),
            // ---------------------------
            "premi_jual" => array(
                "label" => "persen",
                "attr_header" => "class='bg-teal'",
                "attr" => "class='text-right bg-teal'",
                "top_parent" => "simpel",
                // "top_sub_parent" => "simpel",
                // "data_order" => "premi_jual",
                "data_order" => "premi_juale",
            ),
            "premi_jual_nilai" => array(
                "label" => "nilai",
                "attr_header" => "class='bg-teal'",
                "attr" => "class='text-right bg-teal'",
                "top_parent" => "simpel",
                // "top_sub_parent" => "simpel",
                "data_order" => "premi_jual_nilaine",
            ),
            // "biaya_jual"        => array(
            //     "label"      => "biaya penjualan",
            //     "attr"       => "class='text-right bg-danger'",
            //     "top_parent" => "simpel",
            //     "data_order" => "biaya_jual_nilai",
            // ),
            "harga_aft" => array(
                "label" => "harga absolut incl. ppn",
                "attr" => "class='text-right bg-danger'",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "top_parent" => "simpel",
            ),
            // "harga_aft_nppn"           => array(
            //     "label"      => "harga absolut incl. ppn",
            //     "attr"       => "class='text-right bg-danger'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "top_parent" => "simpel",
            //     "data_order"  => "harga_aft_nppn",
            // ),

            // "diskon_ygbener" => array(
            //     "label"      => "diskon_ygbener",
            //     "attr"       => "class='text-right bg-danger'",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "dikson",
            //     "top_parent" => "simpel",
            // ),

        );

        $arrHeaders = $arrHeaders_01 + $arrHeaders_02 + $arrHeaders_03;

        /*---grosir---*/
        $data_grosir = false;
        if ($data_grosir == true) {
            for ($i = 1; $i <= $maxGrosir; $i++) {

                $bg_warna = $i % 2 == 0 ? "bg-warning" : "bg-success";

                $arrHeaders["minim_" . $i] = array(
                    "label" => "minimal<br>#$i",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "top_parent" => "grosir",
                );
                $arrHeaders["persen_" . $i] = array(
                    "label" => "potongan<br>#$i (%)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "diskon",
                    "top_parent" => "grosir",
                );
                $arrHeaders["nilai_" . $i] = array(
                    "label" => "potongan<br>#$i (Rp)",
                    "attr_header" => "class='$bg_warna'",
                    "attr" => "class='text-right $bg_warna'",
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "top_parent" => "grosir",
                );
            }
        }
        $arrHeaders["grosir"] = array(
            "label" => "tindakan",
            "attr" => "class='text-right'",
            "data_order" => false,
            // "top_parent" => "grosir",
        );

        // --------------------------------------------------------------------
        $arrHeaderParents = array(
            "pembelian" => array(
                "label" => "diskon/komponen pembentuk harga tandas",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_beli" => array(
                "label" => "harga beli",
                "attr_header" => "class='bg-warning'",
            ),
            "harga_list" => array(
                "label" => "harga list",
                "attr_header" => "class='bg-aqua'",
            ),
            "simpel" => array(
                "label" => "premium",
                "attr_header" => "class='bg-blue'",
            ),
            "grosir" => array(
                "label" => "diskon berjenjang",
                "attr_header" => "class='bg-success'",
            ),
        );

        $data = array(
            "mode" => "viewProdukHarga",
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            "title" => "Setting Diskon",
            "subTitle" => "-",
            "arrHeaderParents" => $arrHeaderParents,
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            "is_po" => $is_po,
            "cCode" => isset($cCode) ? $cCode : "",
            "urlBack" => isset($urlBack) ? $urlBack : "",
            "pph23" => $this->pph23,
            "ppn" => my_ppn_factor(),

            // "grosir_header"        => $grosir_header,
            // "grosir_data"          => $src_dg,
            // "level_header"         => $level_header,
            // "level_data"           => $src_clevel_diskons,
            // "level_data"           => array(),
            // "jenisTransaksi"       => $jenisTr,
            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
            // "template"             => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            // "isMobile"             => $isMob,
        );

        //arrPrint($data);

        $this->load->view("setting", $data);

    }

    public function settingFreeProdukPembelian()
    {
        // arrPrintHijau(url_segment());
        $produk_id = url_segment(4);

        /* ----------------------------------------------------------
         * freeproduk relasi
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $dpps = new MdlDiskonPembelianPairSupplier();
        $validationRules = $dpps->getValidationRules();
        $fields = $dpps->getFields();
        foreach ($validationRules as $field => $validate_field) {
            // arrPrintPink($validate_field);
            foreach ($validate_field as $validate_item) {
                $validateKoloms[$validate_item][] = $field;
            }
        }

        $src_freeProduks = $dpps->callSpecs($produk_id);
        // showLast_query("kuning");

        $src_freeProduk = isset($src_freeProduks[$produk_id]) ? $src_freeProduks[$produk_id] : "";
        // arrPrintHijau($src_freeProduk);
        $produk_rel_id = isset($src_freeProduk->produk_rel_id) ? $src_freeProduk->produk_rel_id : "";
        $supplier_id = isset($src_freeProduk->supplier_id) ? $src_freeProduk->supplier_id : "";

        $p = new Layout();
        $p->setFormGroupLeftClass("col-md-2 text-uppercase");
        $p->setFormGroupRightClass("col-md-10");
        $tbl_form = "";
        /* --------------------------
         * supplier
         * --------------------------*/
        $this->load->model("Mdls/MdlSupplier");
        $cu = new MdlSupplier();
        // $this->db->order_by("nama", "asc");
        $srcCus = $cu->callSpecs();
        // showLast_query("kuning");
        // arrPrintHijau($srcCus);

        $select_td = "<select data-style='btn btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker' name='supplier_id'>";
        $select_td .= "<option value=''>---pilih supplier----</option>";
        foreach ($srcCus as $cuid => $srcCus) {
            $cunama = $srcCus->nama;
            $tlp_1 = $srcCus->tlp_1;
            $tlp_f = strlen(($tlp_1)) > 3 ? "($tlp_1)" : "";
            $selected = $cuid == $supplier_id ? "selected" : "";
            $select_td .= "<option value='$cuid' $selected>$cunama $tlp_f</option>";
        }
        $select_td .= "</select>";
        $select_td .= "<script>
            $('.selectpicker').selectpicker();
        </script>";
        $tbl_form .= $p->form_group("supplier", "$select_td");
        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        if (ipadd() == "202.65.117.72") {
            //            $this->db->limit(2);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
        }
        $src_prs = $pr->callSpecs();

        $selector_hadiah = "";
        $selector_hadiah .= "<div class='btn-group'>";
        $selector_hadiah .= "<select data-style='btn btn-sm btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker select2' name='produk_rel_id'>";
        $selector_hadiah .= "<option value=''>---pilih hadiah-----</option>";
        foreach ($src_prs as $src_pr) {
            $pr_id = $src_pr->id;
            $pr_nama = $src_pr->nama;
            $pr_barcode = $src_pr->barcode;
            $pr_kategori = $src_pr->kategori_nama;
            if (strlen($pr_kategori) > 1) {
                if ($pr_kategori == "unit") {
                    $pr_kategori_0 = "<span style='color: darkmagenta;'>$pr_kategori*</span>";
                }
                else {
                    $pr_kategori_0 = "<span style='color: coral;'>$pr_kategori</span>";
                }

                $pr_kategori_f = "($pr_kategori_0)";

            }
            else {

                $pr_kategori_f = "";
            }

            $pr_selected = $produk_rel_id == $pr_id ? "selected" : "";
            $selected_color = $produk_rel_id == $pr_id ? "text-success font-size-1-5" : "";
            $selector_hadiah .= "<option class=' text-left $selected_color' value='$pr_id' $pr_selected>$pr_barcode | $pr_nama $pr_kategori_f</option>";

        }

        $selector_hadiah .= "</select>";
        $selector_hadiah .= "<button type='button' class='btn btn-warning pull-right' onclick=\"\"><i class='fa fa-plus'></i></button>";
        $selector_hadiah .= "</div>";

        $tbl_form .= $p->form_group("hadiah", "$selector_hadiah");
        // $tbl_form .= $p->form_group("qty hadiah", "<input type='number' name='produk_rel_qty' class='form-control'>");
        // $tbl_form .= $p->form_group("harga hadiah", "<input type='text' name='produk_rel_harga' class='form-control'>");
        // $tbl_form .= $p->form_group("sdk produk", "<input type='number' name='qty_min' step='1' class='form-control'>");
        // $tbl_form .= $p->form_group("tanggal mulau", "<input type='date' name='start_date' class='form-control'>");
        // $tbl_form .= $p->form_group("tanggal selesai", "<input type='date' name='expired_date' class='form-control'>");


        // arrPrintKuning($fields);
        // arrPrintKuning($validateKoloms);
        foreach ($fields as $fkey => $param_field) {
            $kolom = $param_field['kolom'];
            $label = $param_field['label'];
            $inputType = $param_field['inputType'];
            $inputDefaultValue = $param_field['defaultValue'];
            $format = isset($param_field['format']) ? $param_field['format'] : "";

            $nilai = isset($src_freeProduk->$kolom) ? $src_freeProduk->$kolom : (isset($param_field['defaultValue']) ? $param_field['defaultValue'] : "");

            if ($format == "angka") {
                $nilai_f = $nilai != 0 ? $nilai * 1 : "";
            }
            else {
                $nilai_f = $nilai;
            }


            $req_tanda = "";
            $therule = "";
            if (in_array($kolom, $validateKoloms['required'])) {
                $req_tanda = "<r>*</r>";
                $therule = "required";
            }

            switch ($inputType) {
                case "combo":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button><button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"location.href='$link_editor_act'\"><i class='fa fa-pencil'></i></button></div>" : "<div></div>";
                    // $optionals = "<option value=''> Pilih $str_label </option>";
                    // foreach ($dataSources as $key_src => $label_src) {
                    //     $fSelected = $fValue == $key_src ? "selected" : "";
                    //     $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    // }
                    // $eventSession = $this->createSessionData();
                    // $tbl_form = "<div class='input-group input-group-sm'>";
                    //
                    // if (count($dataSources) == 0) {
                    //     $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                    //     $tbl_form .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    // }
                    // else {
                    //     $tbl_form .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    // }
                    //
                    // $tbl_form .= $optionals;
                    // $tbl_form .= "</select>";
                    // $tbl_form .= $btn_add;
                    // $tbl_form .= "</div>";
                    // $tbl_form .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $tbl_form .= $p->form_group("$label $req_tanda", "<input $therule placeholder='$label' type='number' name='$kolom' class='form-control' value='$nilai_f'>");
                    break;
                case "date":
                    $tbl_form .= $p->form_group("$label $req_tanda", "<input $therule placeholder='$kolom' type='$inputType' name='$kolom' class='form-control' value='$nilai'>");
                    break;
            }
        }
        $kelipatan = isset($src_freeProduk->kelipatan) ? $src_freeProduk->kelipatan : "";
        $kelipatan_0 = $kelipatan == 0 ? "checked" : "";
        $kelipatan_1 = $kelipatan == 1 ? "checked" : "";
        $tbl_form .= $p->form_group("berlaku kelipatan", "<input type='radio' name='kelipatan' value='1' $kelipatan_1> yes <input type='radio' name='kelipatan' value='0' $kelipatan_0> no");
        $status = isset($src_freeProduk->status) ? $src_freeProduk->status : "";
        $status_0 = $status == 0 ? "checked" : "";
        $status_1 = $status == 1 ? "checked" : "";
        $tbl_form .= $p->form_group("status", "<input type='radio' name='status' value='1' $status_1> aktif <input type='radio' name='status' value='0' $status_0> non aktif");
        $tbl_form .= $p->form_group("produk", "<input type='number' name='produk_id' value='$produk_id'>", true);

        $link_action = MODUL_PATH . "Setting/do_save_free_produk_pembelian";
        $var = "";
        $var .= "<style type='text/css'>
            .form-control{
                height :30px;
            }
            .form-group{
                margin-bottom :5px;
            }
        </style>";
        $var .= "<div class='alert alert-danger'>";
        $var .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>";
        $var .= "Harga hadiah adalah nilai per unit";
        $var .= "</div>";

        $var .= "<div class='overflow-h'>";
        $var .= "<form method='post' enctype='application/x-www-form-urlencoded' action='$link_action' target='result'>";
        $var .= $tbl_form;
        $var .= "<hr>";
        $var .= "<button type='button' class='btn btn-danger pull-left' onclick=\"\">Delete</button>";
        $var .= "<button type='button' id='btn_history' class='btn btn-info pull-left' onclick=\"\">Show/Hidde Histori</button>";
        $var .= "<button type='submit' class='btn btn-primary pull-right'>Simpan</button>";
        $var .= "</form>";
        $var .= "</div>";

        $history = $this->viewHistoriFreeProduk($produk_id);
        $var .= "<div id='wadah_history' style='display: none;margin-top: 10px;'>$history</div>";

        $var .= "<script>            
            $('.selectpicker').selectpicker();
            $('#btn_history').click(function(){
                $('#wadah_history').fadeToggle();
            });
        </script>";

        echo $var;
    }

    public function do_save_free_produk_pembelian()
    {
        $post = $_POST;
        arrPrintHijau($post);
        $supplier_id = $post['supplier_id'];
        $produk_id = $post['produk_id'];
        $produk_rel_id = $post['produk_rel_id'];
        $produk_ids = array(
            $produk_id, $produk_rel_id
        );
        /* ----------------------------------------------------------
         * freeproduk relasi
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $dpps = new MdlDiskonPembelianPairSupplier();
        // $src_freeProduks = $dpps->callSpecs($produk_id);
        // $jml_data = count($src_freeProduks);
        // showLast_query("kuning", $jml_data);
        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        if (ipadd() == "202.65.117.72") {
            //            $this->db->limit(2);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
        }
        $src_prs = $pr->callSpecs($produk_ids);
        showLast_query("kuning");
        $src_pr = $src_prs[$produk_id];
        $src_rel_pr = $src_prs[$produk_rel_id];

        /*
         * supplier
         * */
        $this->load->model("Mdls/MdlSupplier");
        $cu = new MdlSupplier();
        // $this->db->order_by("nama", "asc");
        $srcCus = $cu->callSpecs($supplier_id);
        $supDatas = $srcCus[$supplier_id];
        arrPrintHijau($supDatas);
        $supplier_nama = $supDatas->nama;

        // arrPrintKuning($src_pr);
        $post['produk_nama'] = $src_pr->nama;
        // $post['supplier_id'] = $src_pr->supplier_id;
        $post['supplier_id'] = $supplier_id;
        $post['supplier_nama'] = $supplier_nama;
        $post['per_supplier_diskon_nama'] = $src_pr->supplier_nama;
        $post['produk_rel_nama'] = $src_rel_pr->nama;
        $post['nama'] = "diskon free produk";
        $post['dtime'] = dtimeNow();
        $post['oleh_id'] = my_id();
        $post['oleh_nama'] = my_name();
        // $post['per_supplier_diskon_nama'] = "diskon free produk";

        $this->db->trans_start();
        $dpps->setData($post);
        $ceking = $dpps->writeFreeDiscProduk();

        // $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        // $dps = new MdlDiskonPembelianSupplier();

        // matiHere("belum commit @" . __LINE__);
        $this->db->trans_complete();
        if ($ceking != false) {
            cekBiru("sukses");
            echo lgShowSuccess("Berhasil", "perubahan data berhasil disimpan");
        }
        else {
            cekBiru("gagal");
            echo lgShowError("Upss...", "Penyimpanan data tidak berhasil");
        }

    }

    public function viewHistoriFreeProduk($produk_ids)
    {

        $this->load->model("Mdls/MdlDiskonPembelianPairSupplier");
        $dpps = new MdlDiskonPembelianPairSupplier();
        $dpps->setFilters(array());
        $dpps->addFilter("produk_id=$produk_ids");
        $this->db->order_by("id", "desc");
        $src_prs = $dpps->lookupAll()->result();

        $src_prs = count($src_prs) > 0 ? $src_prs : array();
        // showLast_query("merah");
        // arrPrint($src_prs);

        foreach ($src_prs as $src_pr) {

        }

        $p = new Layout();
        $headers = array(
            // "id",
            "dtime" => array(
                "label" => "tanggal",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_nama" => array(
                "label" => "produk",
                "attr_head" => "class='text-uppercase'"
            ),
            "qty_min" => array(
                "label" => "sdk (minim pembelian)",
                "attr_head" => "class='text-uppercase'",
            ),
            "supplier_nama" => array(
                "label" => "supplier",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_rel_nama" => array(
                "label" => "hadiah",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_rel_qty" => array(
                "label" => "qty hadiah",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_rel_harga" => array(
                "label" => "harga hadiah",
                "attr_head" => "class='text-uppercase'",
            ),
            "start_date" => array(
                "label" => "tgl mulai",
                "attr_head" => "class='text-uppercase'",
            ),
            "expired_date" => array(
                "label" => "tgl selesai",
                "attr_head" => "class='text-uppercase'",
            ),
            "oleh_nama" => array(
                "label" => "oleh",
                "attr_head" => "class='text-uppercase'",
            ),
        );
        $p->setLayoutTableHeaderKolom($headers);
        $tbls = "<div class='border-cek'>";
        $tbls .= $p->layout_table($src_prs);
        $tbls .= "</div>";

        return $tbls;
    }

    public function viewSupplierRebate()
    {
        /* ---------------------
         * dta produk per supplier
         * ---------------------*/
        $vendor = false;
        if ($vendor == true) {
            $this->load->model("Mdls/MdlProdukPerSupplier");
            $pps = new MdlProdukPerSupplier();
            if (isset($_GET['suppliers_id'])) {
                $condites = array(
                    "suppliers_id" => $_GET['suppliers_id'],
                );
                $this->db->where($condites);
            }
            $src_pps_0 = $pps->lookupAll()->result();// showLast_query("kuning");
            // arrPrint($src_pps_0);
            foreach ($src_pps_0 as $src_pp) {
                $suppliers_id = $src_pp->suppliers_id;
                $produk_id = $src_pp->produk_id;

                $produk_speks = isset($src_pr[$produk_id]) ? $src_pr[$produk_id] : array();
                // $src_pps[$suppliers_id][$produk_id] = (array)$src_pp + (array)$produk_speks;
                $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
            }
        }

        $sID = isset($_GET['sID']) ? $_GET['sID'] : 0;

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
            $dp_speks['persen'] = $dp_src->persen * 1;
            $dp_speks['nilai'] = $dp_src->nilai * 1;

            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks;
        }

        /* -----------------------------------------
         * data suppliers
         * -----------------------------------------*/
        $this->load->model("Mdls/MdlSupplier");
        $sp = new MdlSupplier();
        if ($sID) {
            $sp->addFilter("id='$sID'");
        }
        $src_sp = $sp->lookupAll()->result();

        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dps = new MdlDiskonPembelianSupplier();
        if ($sID) {
            $dps->addFilter("supplier_id='$sID'");
        }
        $dpsTmp = $dps->lookupAll()->result();

        $arrDiskonPersupplier = array();
        if (!empty($dpsTmp)) {
            foreach ($dpsTmp as $k => $sdata) {
                $arrDiskonPersupplier[$sdata->supplier_id][$sdata->per_supplier_diskon_nama]['nilai'] = $sdata->nilai;
                $arrDiskonPersupplier[$sdata->supplier_id][$sdata->per_supplier_diskon_nama]['persen'] = $sdata->persen;
            }
        }


        $this->load->model("Mdls/MdlPerSupplierDiskon");
        $spd = New MdlPerSupplierDiskon();
        $spd->addFilter("jenis='reguler'");
        $spdTmp = $spd->lookupAll()->result();
        // showLast_query("merah");
        // arrPrintKuning($spdTmp);

        /* ----------------------------------------------
         * builder data
         * ----------------------------------------------*/
        foreach ($src_sp as $item) {
            $suppliers_id = $item->id;
            $item_array = (array)$item;
            $src_pr[$suppliers_id] = $item_array;
        }

        $arrHeaders_01 = array(
            //            "id" => array(
            //                "label"       => "sid",
            //                "attr_header" => "rowspan='2'",
            //                "span_header" => true,
            //            ),
            //            "nama" => array(
            //                "label"       => "nama supplier",
            //                "attr_header" => "rowspan='2'",
            //                "span_header" => true,
            //            ),
        );

        if (!empty($spdTmp)) {
            foreach ($spdTmp as $ky => $pData) {
                $arrHeaders_01[$pData->nama] = array(
                    "label" => $pData->label,
                    "attr_header" => "style='background: orange;text-align: center;font-weight: 700;'",
                    //                    "span_header" => true,
                    "top_parent" => "pembelian",
                );
                $arrSubHeaderDiskonPembelian[$pData->nama] = array(
                    "persen" => array(
                        "label" => "persen",
                        "diskon_id" => $pData->id,
                        "attr_header" => "style='background: lightgreen;text-align: center;font-weight: 700;'",
                        "icon" => "fa fa-percent",
                    ),
                    // "nilai"  => array(
                    //     "label"       => "Rp",
                    //     "diskon_id"   => $pData->id,
                    //     "attr_header" => "style='background: skyblue;text-align: center;font-weight: 700;'",
                    //     "icon"        => "fa fa-money",
                    // ),
                );
            }
        }

        $arrHeaders = $arrHeaders_01;

        $is_po = array();

        $arrHeaderParents = array(
            "pembelian" => array(
                "label" => "diskon/komponen pembentuk harga tandas",
                "attr_header" => "style='background: orange;text-align: center;font-weight: 700;'",
            ),
            // "harga_beli" => array(
            //     "label"       => "harga beli",
            //     "attr_header" => "class='bg-warning'",
            // ),
            // "harga_list" => array(
            //     "label"       => "harga list",
            //     "attr_header" => "class='bg-aqua'",
            // ),
            // "simpel"     => array(
            //     "label"       => "premium",
            //     "attr_header" => "class='bg-blue'",
            // ),
            // "grosir"     => array(
            //     "label"       => "diskon berjenjang",
            //     "attr_header" => "class='bg-success'",
            // ),
        );

        //        arrPrintWebs($arrHeaders);
        //        arrPrint($src_pr);
        //        arrPrintWebs($arrSubHeaderDiskonPembelian);

        $data = array(
            "mode" => "viewSupplierRebate",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting Diskon",
            "subTitle" => "-",
            "arrHeaderParents" => $arrHeaderParents,
            "arrHeaders" => $arrHeaders,
            "master_data" => isset($src_pr) ? $src_pr : array(),
            "arrSubHeaderDiskonPembelian" => $arrSubHeaderDiskonPembelian,
            "arrDiskonPersupplier" => $arrDiskonPersupplier,
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            // "level_header"   => $level_header,
            // "level_data"     => $src_clevel_diskons,
            // "level_data"     => array(),
            // "jenisTransaksi" => $jenisTr,
            "is_po" => $is_po,
            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
            "cCode" => isset($cCode) ? $cCode : "",
            "urlBack" => isset($urlBack) ? $urlBack : "",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function iterasiGerbangItem($cCode)
    {
        // arrPrint($_GET);
        // cekHijau($cCode . " hei");
        $sesItems = $_SESSION[$cCode]['items'];

        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs();
        // arrPrint($prod_hargas);
        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                // $produk_id = $param_prod_harga->produk_id;
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        //         showLast_query("biru");
        // arrPrint($dp_srcs);
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis_id = $dp_src->per_supplier_diskon_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['persen'] = $dp_src->persen;
            $dp_speks['nilai'] = $dp_src->nilai;

            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks + (array)$dp_src;
        }
        //        arrPrint($dp_datas);

        /* ----------------------------------------------------------
         * modif item dengan diskonPembelian (dp)
         * ----------------------------------------------------------*/
        foreach ($sesItems as $produk_id => $sesItem) {
            $item_jml = $sesItem['jml'];

            $dp_speks = $dp_datas[$produk_id];
            $hrg_speks = $prod_hrg_speks[$produk_id];
            //arrPrintPink($dp_speks);
            /*----pilih saja harga list supplier yg mau dipakai ----*/
            $hpp_supplier = $item_hpp = $sesItem['hpp'];
            // $hpp_supplier = $hrg_speks['hpp_supplier']->nilai * 1;
            // arrPrint($dp_speks);
            $sesItem['hpp_supplier'] = $hpp_supplier;
            $sesItem['hpp_supplier_nppn'] = $hpp_supplier + ((my_ppn_factor() / 100) * $hpp_supplier);
            $dp_nilai_total = 0;
            foreach ($dp_speks as $dp_jenis => $dp_spek) {
                $dp_persen = $dp_spek['persen'] * 1;
                $dp_nilai_db = $dp_spek['nilai'] * 1;
                $dp_nilai = $dp_persen / 100 * $hpp_supplier;

                $sesItem[$dp_jenis . "_id"] = $dp_spek['per_supplier_diskon_id'];
                $sesItem[$dp_jenis . "_nama"] = $dp_spek['per_supplier_diskon_nama'];
                $sesItem[$dp_jenis . "_alias"] = $dp_spek['per_supplier_diskon_alias'];
                $sesItem[$dp_jenis . "_persen"] = $dp_persen;
                $sesItem[$dp_jenis . "_nilai"] = $dp_nilai;
                $sesItem["sub_" . $dp_jenis . "_nilai"] = $dp_nilai * $item_jml;

                $dp_nilai_total += $dp_nilai;
            }
            $sesItem["diskon_nilai_total"] = $dp_nilai_total;
            $sesItem["sub_diskon_nilai_total"] = $dp_nilai_total * $item_jml;
            $diskon_pajak = $dp_nilai_total * ($this->pph23 / 100);
            $sesItem["diskon_pph23"] = $diskon_pajak;

            // cekHere("$hpp_supplier - ($dp_nilai_total + $diskon_pajak");
            $tandas = ($hpp_supplier - ($dp_nilai_total + $diskon_pajak));
            $sesItem["hrg_tandas"] = $tandas;
            $sesItem["hrg_tandas_npph23"] = $tandas + ($this->pph23 / 100 * $tandas);

            $newItems[$produk_id] = $sesItem;
        }

        $_SESSION[$cCode]['items'] = $newItems;

        // jejeran($sesItems);
        // jejeranPink($_SESSION[$cCode]['items']);
        // jejeran(arrPrint($sesItems));
    }

    public function viewMember()
    {
        $this->load->library("Diskon");
        $dk = new Diskon();
        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        // $this->load->model("Mdls/MdlCustomerLevel");
        // $cl = new MdlCustomerLevel();

        $dk->setTokoId(my_toko_id());
        $condites = array(
            // "jenis" => "transaksi",
            "tipe" => "diskon",
        );
        $dk->setCustomerLevelCondite($condites);
        $src_cls = $dk->callCustomerLevelDiskon();
        // showLast_query("kuning");
        // arrPrint($src_cls);
        $id_row = 777;
        $tmp_cls = array();
        foreach ($src_cls['customer_level_diskon'] as $src_cl) {
            $tipe = $src_cl['tipe'];
            $jenis = $src_cl['jenis'];
            $minim = $src_cl['minim'];
            $persen = isset($src_cl['persen']) ? $src_cl['persen'] : 0;
            $customer_level = $src_cl['customer_level'];
            if (!isset($data_koloms[$jenis][$minim]["level_$customer_level"])) {
                $data_koloms[$jenis][$minim]["level_$customer_level"] = 0;
            }
            $data_koloms[$jenis][$minim]["level_$customer_level"] = $persen;

            $data_koloms[$jenis][$minim]['id'] = $src_cl['id'];
            $data_koloms[$jenis][$minim]['quota_global'] = $src_cl['quota_global'];
            $data_koloms[$jenis][$minim]['periode'] = $src_cl['periode'];
            $data_koloms[$jenis][$minim]['tipe'] = $tipe;
            $data_koloms[$jenis][$minim]['jenis'] = $jenis;
            $data_koloms[$jenis][$minim]['minim'] = $minim;
            $data_koloms[$jenis][$minim]['tanggal_start'] = $src_cl['tanggal_start'];
            $data_koloms[$jenis][$minim]['tanggal_stop'] = $src_cl['tanggal_stop'];
            $data_koloms[$jenis][$minim]['status'] = $src_cl['status'];
            $id_row++;
            $id_row = "min_" . $minim;

            // $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim";
            //             $data_koloms[$jenis][$minim]['action'] = "<div class='btn-group'>
            // <button type='button' class='btn btn-link btn-sm' id='$id_row' onclick=\"btn_edit('$jenis','$minim');\"><i class='fa fa-pencil'></i></button>
            //  <button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";

            $tmp_cls = $data_koloms;
        }
        // arrPrintHijau($tmp_cls);
        $src_clevel_diskons = array();
        foreach ($tmp_cls as $tmp_cl_0) {
            foreach ($tmp_cl_0 as $tmp_cl) {

                $src_clevel_diskons[$tmp_cl['jenis']][] = $tmp_cl;
            }
        }
        // arrPrintKuning($src_clevel_diskons);
        /*pengelompokann berdasarkan jenis diskon*/
        foreach ($src_clevel_diskons as $src_clevel_diskon) {

        }

        $level_header = array();
        $level_header = array(
            "jenis" => array(
                "label" => "jenis diskon",
                "attr_footer" => "class='form-control' required readonly",
                "tipe_input" => "text",
                "default_data" => "transaksi",
                // "data_srcs"   => array(
                //     "transaksi",
                //     "birthday"
                // ),
            ),
        );
        $level_header['minim'] = array(
            "label" => "minimal transaksi",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        $level_header['diskon'] = array(
            "label" => "besarnya diskon (%)",
            // "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr_header" => "class='text-center bg-primary'",
            "parent" => true,
        );
        /*---header untuk masing2 level customer*/
        // arrPrintKuning($src_cls);
        foreach ($src_cls['customer_level'] as $src_cl) {
            $level_id = $src_cl->id;
            $level_nama = $src_cl->nama;

            $attributs['label'] = "level " . $level_nama;
            $attributs['attr_header'] = "class='bg-primary'";
            $attributs['attr_footer'] = "class='form-control text-right' max='100'";
            $attributs['tipe_input'] = "number";
            $attributs['attr'] = "class='text-center'";
            $attributs['child'] = true;
            $attributs['parent_ky'] = "diskon";

            $level_header['level_' . $level_id] = $attributs;
            // $level_header['level_'][$level_id] = $attributs;

        }

        $level_header['quota_global'] = array(
            "label" => "quota",
            "attr" => "class='text-right'",
            "attr_footer" => "class='form-control text-right'",
        );
        $level_header['periode'] = array(
            "label" => "periode",
            "tipe_input" => "select",
            "data_srcs" => array(
                "bulanan",
                "tahunan",
            ),
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "attr" => "class='text-center'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "attr" => "class='text-center'",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );

        // $level_header['author_nama'] = array(
        //     "label"       => "dibuat oleh",
        //     "attr"        => "class='text-center'",
        //     // "tipe_input"  => "date",
        //     "attr_footer" => "class='form-control'",
        // );
        // $level_header['dtime'] = array(
        //     "label"       => "ttanggal dibuat",
        //     "attr"        => "class='text-center'",
        //     // "tipe_input"  => "date",
        //     "attr_footer" => "class='form-control'",
        // );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
            "attr" => "class='text-left'",
            "default_data" => "save setting",
        );

        // arrPrintHijau($level_header);
        // arrPrintPink($src_clevel_diskons);

        $data = array(
            "mode" => "viewMember",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting Diskon",
            "subTitle" => "-",
            // "arrHeaders"     => $arrHeaders,
            // "master_data"    => isset($src_pr) ? $src_pr : array(),
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            "level_header" => $level_header,
            "level_data_0" => $src_clevel_diskons,
            "my_div" => "dua",
            "my_controler" => __FUNCTION__,
            "tipe" => "diskon",
            "jenis_kdata" => "diskon",
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    /*---setting cadangan diskon supplier------*/
    public function viewSupplier()
    {
        // viewSupplier
        $this->load->library("Diskon");
        $dk = new Diskon();
        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        $this->load->model("Mdls/MdlSupplier");
        $sp = new MdlSupplier();
        $srcSp = $sp->lookupAll()->result();

        $this->load->model("Mdls/MdlDiskonCadanganSupplier");
        $dps = new MdlDiskonCadanganSupplier();
        $dps_srcs = $dps->lookupAll()->result();
        foreach ($dps_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_supplier_id = $dp_src->supplier_id;


            $dps_datas[$dp_supplier_id]= $dp_src;
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp->addFilter("per_supplier_diskon_id>0");
        $dp->addFilter("nilai>0");
        $dp_srcs = $dp->lookupAll()->result();
        // showLast_query("pink", __LINE__);
        $dp_datas = array();
        foreach ($dp_srcs as $dp_src) {
            $dp_sp_id = $dp_src->supplier_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['per_supplier_diskon_id'] = $dp_src->per_supplier_diskon_id;
            $dp_speks['nilai'] = $dp_src->nilai * 1;

            $dp_datas[$dp_sp_id][$dp_jenis] = $dp_speks;
        }

        $dk->setTokoId(my_toko_id());

        // showLast_query("kuning");
        // arrPrint($src_cls);
        $id_row = 777;
        foreach ($srcSp as $item) {
            $spData = (array)$item;
            $id = $item->id;
            $spData2 = (array)$dps_datas[$id];

            if(isset($dp_datas[$id])){
                $dp123 = count($dp_datas[$id]);
            }
            else{
                $dp123 = 0;
            }
            $spData2['diskon_pembelian'] = $dp123;

            $src_pr[$id] = $spData + $spData2;
        }

        $arrHeaders = array(
            "id" => array(
                "label" =>"sid",
                "attr_h" =>"width='50px !important'",
            ),
          "nama" => array(
            "label" =>"supplier",
          ),
            "diskon_pembelian" => array(
                "label" =>"diskon pembelian",
                "nilai_replacer" =>"AKTIF <r>(untukbisa disetting, kosongkan semua diskon pembelian)</r>",
            ),
          "persen" => array(
              "label" =>"cadangan",
              "type" =>"text",
              "attr_h" =>"width='100px'",
              "data-order" =>"persen",
          ),
        );
        $level_header = array();
        $level_header = array(
            "jenis" => array(
                "label" => "jenis diskon",
                "attr_footer" => "class='form-control' required readonly",
                "tipe_input" => "text",
                "default_data" => "transaksi",
                // "data_srcs"   => array(
                //     "transaksi",
                //     "birthday"
                // ),
            ),
        );
        $level_header['minim'] = array(
            "label" => "minimal transaksi",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        $level_header['diskon'] = array(
            "label" => "besarnya diskon (%)",
            // "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr_header" => "class='text-center bg-primary'",
            "parent" => true,
        );
        /*---header untuk masing2 level customer*/
        // arrPrintKuning($src_cls);

        $level_header['quota_global'] = array(
            "label" => "quota",
            "attr" => "class='text-right'",
            "attr_footer" => "class='form-control text-right'",
        );
        $level_header['periode'] = array(
            "label" => "periode",
            "tipe_input" => "select",
            "data_srcs" => array(
                "bulanan",
                "tahunan",
            ),
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "attr" => "class='text-center'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "attr" => "class='text-center'",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );

        // $level_header['author_nama'] = array(
        //     "label"       => "dibuat oleh",
        //     "attr"        => "class='text-center'",
        //     // "tipe_input"  => "date",
        //     "attr_footer" => "class='form-control'",
        // );
        // $level_header['dtime'] = array(
        //     "label"       => "ttanggal dibuat",
        //     "attr"        => "class='text-center'",
        //     // "tipe_input"  => "date",
        //     "attr_footer" => "class='form-control'",
        // );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
            "attr" => "class='text-left'",
            "default_data" => "save setting",
        );

        // arrPrintHijau($level_header);
        // arrPrintPink($src_clevel_diskons);

        $data = array(
            "mode" => "viewSupplier",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting cadangan Diskon",
            "subTitle" => "-",
            "arrHeaders"     => $arrHeaders,
            "master_data"    => isset($src_pr) ? $src_pr : array(),
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            "level_header" => $level_header,
            // "level_data_0" => $src_clevel_diskons,
            "my_div" => "satu-dua",
            "my_controler" => __FUNCTION__,
            "tipe" => "diskon",
            "jenis_kdata" => "diskon",
            "link_save" => MODUL_PATH  ."/". get_class($this) . "/do_save_cadangan_diskon_supplier",

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function do_save_cadangan_diskon_supplier(){
        arrPrint($_GET);
        arrPrintKuning(url_segment());
        $sup_id = $_GET['sid'];
        $sup_nama = $_GET['snm'];
        $nilai =$val = $_GET['val'];

        $this->load->model("Mdls/MdlDiskonCadanganSupplier");
        $dps = new MdlDiskonCadanganSupplier();
        $dps->setFilters(array());
        $where = array(
            "supplier_id" => $sup_id,
            "trash" => 0,
        );
        $this->db->where($where);
        $dps_srcs = $dps->lookupAll()->result();
        showLast_query("merah");

        $this->db->trans_start();

        $data = array(
            "supplier_id" => $sup_id,
            "supplier_nama" => $sup_nama,
            "persen" => $val,
            "oleh_id" => my_id(),
            "oleh_nama" => my_name(),
        );
        if(count($dps_srcs) > 0){
            $upd = array(
              "trash" => 1,
              "trash_dtime" => dtimeNow(),
              "trash_oleh_id" => my_id(),
            );
            $dps->updateData($where, $upd);
            showLast_query("lime");

            $dps->addData($data);
            showLast_query("hijau");
        }
        else{
            $dps->addData($data);
            showLast_query("hijau");
        }

        $this->db->trans_complete();

        // echo lgShowSuccess("berhasil","okok");
        echo "<script>
            swal({
                type: 'success',
                title: '$sup_nama',
                html: 'data berhasil diperbaharui',
                showConfirmButton: false,
                timer: '1500',
            });
        </script>";
    }

    public function do_delete_member()
    {
        arrPrintKuning($_REQUEST);
        $minim = $_GET['minim'];
        $jenis = $_GET['jn'];
        $ctr = $_GET['ctr'];
        $div = $_GET['div'];
        $this->load->model("Mdls/MdlDiskonCustomer");
        $dg = new MdlDiskonCustomer();

        $this->db->trans_start();
        $data_upds = array(
            "trash" => 1,
        );
        $condites = array(
            "jenis" => $jenis,
            "minim" => $minim,
            "toko_id" => my_toko_id(),
        );
        $dg->updateData($condites, $data_upds);
        showLast_query("merah");
        //
        // matiHere("belum comit " . __LINE__);
        $this->db->trans_complete();

        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
        // $id_row_ = $id_row + 1;
        $link_member = base_url() . "diskon/Setting/$ctr";
        echo "<script>
                top.$('#$div').load('$link_member');
            </script>";
    }

    public function viewCashBackMember()
    {
        $this->load->library("Diskon");
        $dk = new Diskon();
        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        // $this->load->model("Mdls/MdlCustomerLevel");
        // $cl = new MdlCustomerLevel();

        $dk->setTokoId(my_toko_id());
        $condites = array(
            "tipe" => "cashback",
        );
        $dk->setCustomerLevelCondite($condites);
        $src_cls = $dk->callCustomerLevelDiskon();
        // showLast_query("kuning");
        // matiHere();
        // arrPrint($src_cls);
        $id_row = 666;
        $tmp_cls = array();
        $tipe = "";
        foreach ($src_cls['customer_level_diskon'] as $src_cl) {
            $tipe = $src_cl['tipe'];
            $jenis = $src_cl['jenis'];
            $minim = $src_cl['minim'];
            $persen = isset($src_cl['persen']) ? $src_cl['persen'] : 0;
            $customer_level = $src_cl['customer_level'];
            if (!isset($data_koloms[$jenis][$minim]["level_$customer_level"])) {
                $data_koloms[$jenis][$minim]["level_$customer_level"] = 0;
            }
            $data_koloms[$jenis][$minim]["level_$customer_level"] = $persen;

            $data_koloms[$jenis][$minim]['id'] = $src_cl['id'];
            $data_koloms[$jenis][$minim]['quota_global'] = $src_cl['quota_global'];
            $data_koloms[$jenis][$minim]['periode'] = $src_cl['periode'];
            $data_koloms[$jenis][$minim]['jenis'] = $jenis;
            $data_koloms[$jenis][$minim]['minim'] = $minim;
            $data_koloms[$jenis][$minim]['tanggal_start'] = $src_cl['tanggal_start'];
            $data_koloms[$jenis][$minim]['tanggal_stop'] = $src_cl['tanggal_stop'];
            $data_koloms[$jenis][$minim]['status'] = $src_cl['status'];
            $id_row++;
            $id_row = "min_" . $minim;

            // $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim";
            //             $data_koloms[$jenis][$minim]['action'] = "<div class='btn-group'>
            // <button type='button' class='btn btn-link btn-sm' id='$id_row' onclick=\"btn_edit('$jenis','$minim');\"><i class='fa fa-pencil'></i></button>
            //  <button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";

            $tmp_cls = $data_koloms;
        }
        // arrPrintHijau($tmp_cls);
        $src_clevel_diskons = array();
        foreach ($tmp_cls as $tmp_cl_0) {
            foreach ($tmp_cl_0 as $tmp_cl) {

                // $src_clevel_diskons[] = $tmp_cl;
                $src_clevel_diskons[$tmp_cl['jenis']][] = $tmp_cl;
            }
        }
        // arrPrintKuning($src_clevel_diskons);
        $level_header = array();
        $level_header = array(
            "jenis" => array(
                "label" => "jenis reward",
                // "attr"         => "class='form-control'",
                "attr_footer" => "class='form-control' readonly",
                "tipe_input" => "text",
                "default_data" => "cashback",
                "data_srcs" => array(
                    "transaksi",
                    "birthday"
                ),
            ),
        );
        $level_header['minim'] = array(
            "label" => "minimal transaksi",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        $level_header['point'] = array(
            "label" => "besarnya cashback (%)",
            // "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr_header" => "class='text-center bg-primary'",
            "parent" => true,
        );
        foreach ($src_cls['customer_level'] as $src_cl) {
            $level_id = $src_cl->id;
            $level_nama = $src_cl->nama;

            $attributs['label'] = "level " . $level_nama;
            $attributs['attr_header'] = "class='bg-primary'";
            $attributs['attr_footer'] = "class='form-control text-right' max='100'";
            $attributs['tipe_input'] = "number";
            $attributs['attr'] = "class='text-center'";
            $attributs['child'] = true;
            $attributs['parent_ky'] = "point";

            $level_header['level_' . $level_id] = $attributs;
            // $level_header['level_'][$level_id] = $attributs;

        }
        $level_header['quota_global'] = array(
            "label" => "quota",
            "attr" => "class='text-right'",
            "attr_footer" => "class='form-control text-right'",
        );
        $level_header['periode'] = array(
            "label" => "periode",
            "tipe_input" => "select",
            "data_srcs" => array(
                "bulanan",
                "tahunan",
            ),
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "attr" => "class='text-center'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "attr" => "class='text-center'",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "default_data" => "save cashback setting",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
            "attr" => "class='text-left'",
        );

        // arrPrintWebs($src_clevel_diskons);

        $data = array(
            "mode" => "viewCashBackMember",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting Diskon",
            "subTitle" => "-",
            // "arrHeaders"     => $arrHeaders,
            // "master_data"    => isset($src_pr) ? $src_pr : array(),
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            "level_header" => $level_header,
            "level_data_0" => $src_clevel_diskons,
            "my_div" => "tiga",
            "my_controler" => __FUNCTION__,
            "tipe" => $tipe,
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function viewPointMember()
    {
        $this->load->library("Diskon");
        $dk = new Diskon();
        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        // $this->load->model("Mdls/MdlCustomerLevel");
        // $cl = new MdlCustomerLevel();

        $dk->setTokoId(my_toko_id());
        $condites = array(
            "tipe" => "point",
        );
        $dk->setCustomerLevelCondite($condites);
        $src_cls = $dk->callCustomerLevelDiskon();
        // showLast_query("kuning");
        // matiHere();
        // arrPrint($src_cls);
        $id_row = 666;
        $tmp_cls = array();
        foreach ($src_cls['customer_level_diskon'] as $src_cl) {
            $tipe = $src_cl['tipe'];
            $jenis = $src_cl['jenis'];
            $minim = $src_cl['minim'];
            $nilai = $src_cl['nilai'];
            $persen = isset($src_cl['persen']) ? $src_cl['persen'] : 0;
            $customer_level = $src_cl['customer_level'];
            if (!isset($data_koloms[$jenis][$minim]["level_$customer_level"])) {
                $data_koloms[$jenis][$minim]["level_$customer_level"] = 0;
            }
            $data_koloms[$jenis][$minim]["level_$customer_level"] = $persen;

            $data_koloms[$jenis][$minim]['id'] = $src_cl['id'];
            $data_koloms[$jenis][$minim]['quota_global'] = $src_cl['quota_global'];
            $data_koloms[$jenis][$minim]['periode'] = $src_cl['periode'];
            $data_koloms[$jenis][$minim]['jenis'] = $jenis;
            $data_koloms[$jenis][$minim]['minim'] = $minim;
            $data_koloms[$jenis][$minim]['nilai'] = $nilai;
            $data_koloms[$jenis][$minim]['tanggal_start'] = $src_cl['tanggal_start'];
            $data_koloms[$jenis][$minim]['tanggal_stop'] = $src_cl['tanggal_stop'];
            $data_koloms[$jenis][$minim]['status'] = $src_cl['status'];
            // $data_koloms[$jenis][$minim]=$src_cl;
            $id_row++;
            $id_row = "min_" . $minim;

            // $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim";
            //             $data_koloms[$jenis][$minim]['action'] = "<div class='btn-group'>
            // <button type='button' class='btn btn-link btn-sm' id='$id_row' onclick=\"btn_edit('$jenis','$minim');\"><i class='fa fa-pencil'></i></button>
            //  <button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";

            $tmp_cls = $data_koloms;
        }
        // arrPrintHijau($tmp_cls);
        $src_clevel_diskons = array();
        foreach ($tmp_cls as $tmp_cl_0) {
            foreach ($tmp_cl_0 as $tmp_cl) {

                // $src_clevel_diskons[] = $tmp_cl;
                $src_clevel_diskons[$tmp_cl['jenis']][] = $tmp_cl;
            }
        }
        // arrPrintKuning($src_clevel_diskons);
        $level_header = array();
        $level_header = array(
            "jenis" => array(
                "label" => "jenis reward",
                // "attr"         => "class='form-control'",
                "attr_footer" => "class='form-control' readonly",
                "tipe_input" => "text",
                "default_data" => "point",
                "data_srcs" => array(
                    "transaksi",
                    "birthday"
                ),
            ),
        );
        $level_header['minim'] = array(
            "label" => "minimal transaksi",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        $level_header['point'] = array(
            "label" => "besarnya point",
            // "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr_header" => "class='text-center bg-primary'",
            "parent" => true,
        );
        foreach ($src_cls['customer_level'] as $src_cl) {
            $level_id = $src_cl->id;
            $level_nama = $src_cl->nama;

            $attributs['label'] = "level " . $level_nama;
            $attributs['attr_header'] = "class='bg-primary'";
            $attributs['attr_footer'] = "class='form-control text-right' max='100'";
            $attributs['tipe_input'] = "number";
            $attributs['attr'] = "class='text-center'";
            $attributs['child'] = true;
            $attributs['parent_ky'] = "point";

            $level_header['level_' . $level_id] = $attributs;
        }
        $level_header['nilai'] = array(
            "label" => "nilai tukar",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        // $level_header['quota_global'] = array(
        //     "label"       => "quota",
        //     "attr"        => "class='text-right'",
        //     "attr_footer" => "class='form-control text-right'",
        // );
        // $level_header['periode'] = array(
        //     "label"       => "periode",
        //     "tipe_input"  => "select",
        //     "data_srcs"   => array(
        //         "bulanan",
        //         "tahunan",
        //     ),
        //     "attr_footer" => "class='form-control'",
        // );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "attr" => "class='text-center'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "attr" => "class='text-center'",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "default_data" => "save cashback setting",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
            "attr" => "class='text-left'",
        );

        // arrPrintWebs($src_clevel_diskons);

        $data = array(
            "mode" => "viewCashBackMember",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting Diskon",
            "subTitle" => "-",
            // "arrHeaders"     => $arrHeaders,
            // "master_data"    => isset($src_pr) ? $src_pr : array(),
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            "level_header" => $level_header,
            "level_data_0" => $src_clevel_diskons,
            "my_div" => "tiga",
            "my_controler" => __FUNCTION__,
            "tipe" => $tipe,
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    /*----FREE PRODUK--------------------*/
    public function viewDiskonFreeProduk()
    {
        $this->load->library("Diskon");
        $dk = new Diskon();

        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        // $this->load->model("Mdls/MdlCustomerLevel");
        // $cl = new MdlCustomerLevel();

        $dk->setTokoId(my_toko_id());

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        // $src_pr = $pr->lookupAll()->result();
        $src_pr_obj = $pr->callSpecs();

        $arrSelectProduk = array();
        if (!empty($src_pr_obj)) {
            foreach ($src_pr_obj as $ky => $lbls) {
                $arrSelectProduk[$lbls->id] = $lbls->nama;
            }
        }

        //        arrPrint($arrSelectProduk);
        //        $condites = array(
        //            // "jenis" => "transaksi",
        //            "tipe" => "diskon",
        //        );

        //        $dk->setCustomerLevelCondite($condites);
        $src_cls = $dk->callDiskonFreeProduk(1);
        // showLast_query("kuning");
        //         arrPrint($src_cls);
        //        matiHere();
        $id_row = 777;
        $tmp_cls = array();
        foreach ($src_cls['diskon_free_produk'] as $src_cl) {

            $jenis = $src_cl['jenis'];
            $minim = $src_cl['minim'];
            $no_diskon = $src_cl['nomer_diskon'];

            $data_koloms[$jenis][$no_diskon]['jenis'] = $jenis;
            $data_koloms[$jenis][$no_diskon]['minim'] = $minim;

            $data_koloms[$jenis][$no_diskon]['label_diskon'] = $src_cl['label_diskon'];
            $data_koloms[$jenis][$no_diskon]['nomer_diskon'] = $src_cl['nomer_diskon'];

            $data_koloms[$jenis][$no_diskon]['qty_free_produk'] = $src_cl['free_produk_qty'];

            $data_koloms[$jenis][$no_diskon]['tanggal_start'] = $src_cl['dtime_start'];
            $data_koloms[$jenis][$no_diskon]['tanggal_stop'] = $src_cl['dtime_end'];

            $data_koloms[$jenis][$no_diskon]['hour_start'] = $src_cl['hour_start'];
            $data_koloms[$jenis][$no_diskon]['hour_end'] = $src_cl['hour_end'];

            $data_koloms[$jenis][$no_diskon]['kelipatan'] = $src_cl['kelipatan'];

            $data_koloms[$jenis][$no_diskon]['quota_global'] = $src_cl['quota_global'];

            $data_koloms[$jenis][$no_diskon]['produk_id'] = $src_cl['produk_id'];
            $data_koloms[$jenis][$no_diskon]['produk'] = $src_cl['produk_nama'];
            $data_koloms[$jenis][$no_diskon]['free_produk_id'] = $src_cl['free_produk_id'];
            $data_koloms[$jenis][$no_diskon]['free_produk'] = $src_cl['free_produk_nama'];
            $data_koloms[$jenis][$no_diskon]['persen'] = $src_cl['persen'];
            $data_koloms[$jenis][$no_diskon]['status'] = $src_cl['status'];
            $data_koloms[$jenis][$no_diskon]['id'] = $src_cl['id'];

            $id_row++;
            $id_row = "min_" . $no_diskon;
            $tmp_cls = $data_koloms;

        }

        // arrPrintHijau($tmp_cls);
        $src_clevel_diskons = array();
        foreach ($tmp_cls as $tmp_cl_0) {
            foreach ($tmp_cl_0 as $tmp_cl) {
                $src_clevel_diskons[$tmp_cl['jenis']][] = $tmp_cl;
            }
        }

        $level_header = array();
        $level_header = array(

            "nomer_diskon" => array(
                "label" => "diskon number",
                "attr_footer" => "class='form-control' required readonly",
                "attr" => "class=''",
                "default_data" => "auto",
            ),

            "label_diskon" => array(
                "label" => "diskon label",
                "attr_footer" => "class='form-control'",
                "attr" => "class=''",
            ),

            "produk" => array(
                "label" => "produk",
                "attr_footer" => "class='form-control' required ",
                "data_srcs" => $arrSelectProduk,
                "link_srcs" => "diskon/" . get_class($this) . "/searchProduk/",
                "attr" => "",
                "sub_key" => array(
                    "harga" => array(
                        "label" => "harga",
                        "nilai_key" => "produk_id",
                        "format" => "formatField_he_format",
                    )
                ),
                // "tipe_input"   => "select",
                //                "default_data" => "transaksi",
            ),

        );
        $level_header['minim'] = array(
            "label" => "qty beli",
            "attr_footer" => "class='form-control text-center' required",
            "attr" => "class='text-center'",
        );
        $level_header['free_produk'] = array(
            "label" => "produk free",
            "attr_footer" => "class='form-control' required",
            "attr" => "",
            "data_srcs" => $arrSelectProduk,
            "link_srcs" => "diskon/" . get_class($this) . "/searchProduk/",
            "sub_key" => array(
                "harga" => array(
                    "label" => "harga",
                    "nilai_key" => "free_produk_id",
                    "format" => "formatField_he_format",
                )
            ),
            // "tipe_input"   => "select",
        );
        $level_header['qty_free_produk'] = array(
            "label" => "qty produk free",
            "attr_footer" => "class='form-control text-center' required",
            "attr" => "class='text-center'",
            //            "data_srcs"   => array(
            //                "bulanan",
            //                "tahunan",
            //            ),
            "tipe_input" => "text",
        );
        $level_header['persen'] = array(
            "label" => "diskon %",
            "attr_footer" => "class='form-control text-center' readonly",
            "attr" => "class='text-center'",
            "tipe_input" => "text",
            "format_key" => "harga",
            "format" => "formatField_he_format",
        );
        $level_header['kelipatan'] = array(
            "label" => "berlaku kelipatan",
            "attr_footer" => "class='text-center'",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "kelipatan_cek",
        );
        /*---header untuk masing2 level customer*/
        //        foreach ($src_cls['customer_level'] as $src_cl) {
        //            $level_id = $src_cl->id;
        //            $level_nama = $src_cl->nama;
        //            $attributs['label'] = "level " . $level_nama;
        //            $attributs['attr_footer'] = "class='form-control text-right' max='100'";
        //            $attributs['tipe_input'] = "number";
        //            $attributs['attr'] = "class='text-center'";
        //            $level_header['level_' . $level_id] = $attributs;
        //        }
        $level_header['quota_global'] = array(
            "label" => "quota",
            "attr" => "class='text-right'",
            "attr_footer" => "class='form-control text-right'",
        );
        //        $level_header['periode'] = array(
        //            "label"       => "periode",
        //            "tipe_input"  => "select",
        //            "data_srcs"   => array(
        //                "bulanan",
        //                "tahunan",
        //            ),
        //            "attr_footer" => "class='form-control'",
        //        );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "format_key" => "fulldate",
            "format" => "formatField_he_format",
            "attr" => "class='text-right'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "attr" => "class='text-right'",
            "tipe_input" => "date",
            "format_key" => "fulldate",
            "format" => "formatField_he_format",
            "attr_footer" => "class='form-control'",
        );
        $level_header['hour_start'] = array(
            "label" => "jam mulai",
            "tipe_input" => "time",
            "attr" => "class='text-center'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['hour_end'] = array(
            "label" => "jam selesai",
            "attr" => "class='text-center'",
            "tipe_input" => "time",
            "attr_footer" => "class='form-control'",
        );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
            "attr" => "class='text-left'",
            "default_data" => "save setting",
        );

        //        arrPrintWebs($src_clevel_diskons);
        //        arrPrintWebs($level_header);

        $produk_hargas = array();
        $this->load->library("Harga");
        $ph = new Harga();
        $ph->setTokoId(my_toko_id());
        // $ph->setCabangId(my_cabang_id());
        $ph->setCabangId($this->cabang_id);
        $produk_hargas = $ph->HrgJual();

        //matiHere();
        $data = array(
            "mode" => "viewDiskonFreeProduk",
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            "title" => "Diskon Free Produk",
            "subTitle" => "-",
            "level_header" => $level_header,
            "level_data_0" => $src_clevel_diskons,
            "produk_harga" => $produk_hargas,
            "my_div" => "empat",
            "my_controler" => __FUNCTION__,
            "tipe" => "diskon",
        );
        $this->load->view("setting", $data);
    }

    public function do_save_free_produk()
    {
        arrPrintWebs($_REQUEST);

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $src_pr_obj = $pr->callSpecs();
        $arrSelectProduk = array();
        $arrProdukIdNama = array();
        if (!empty($src_pr_obj)) {
            foreach ($src_pr_obj as $ky => $lbls) {
                $arrSelectProduk[$lbls->id] = $lbls->nama;
            }
            $arrProdukIdNama = array_flip($arrSelectProduk);
        }

        $this->load->model("Mdls/MdlDiskonFreeProduk");
        $dk = new MdlDiskonFreeProduk();
        $my_controler = $_POST['my_controler'];
        $my_div = $_POST['my_div'];
        $no_diskon = $_POST['nomer_diskon'];
        /* -------------------------------------------------------------
         * bila kolom data yg akan disimpan daftarkan dalam array ini ya
         * -------------------------------------------------------------*/

        $dk->setTokoId(my_toko_id());

        cekHijau("------------------------masukin data- $no_diskon ------------------------");
        // cekBiru(is_numeric($no_diskon));
        $this->db->trans_start();

        $src_produk_id = isset($arrProdukIdNama[$_POST['produk']]) ? $arrProdukIdNama[$_POST['produk']] : 'none';
        $free_produk_id = isset($arrProdukIdNama[$_POST['free_produk']]) ? $arrProdukIdNama[$_POST['free_produk']] : 'none';
        $src_produk_qty = $_POST['minim'];
        $free_produk_qty = $_POST['qty_free_produk'];

        $produk_ids = array($src_produk_id, $free_produk_id);
        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $this->db->where("jenis_value", "harga_list");
        $prod_hargas = $hp->callSpecs($produk_ids);
        showLast_query("kuning");
        // arrPrintKuning($prod_hargas);
        $harga_list = array();
        foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
            foreach ($prod_harga_00s as $prod_harga) {
                $nilai = $prod_harga->nilai;
                $harga_list[$prod_id] = $nilai;
            }
        }
        arrPrintHijau($harga_list);
        $src_nilai_total = $harga_list[$src_produk_id] * $src_produk_qty;
        $free_nilai_total = $harga_list[$free_produk_id] * $free_produk_qty;

        $diskon_persen = ($free_nilai_total / $src_nilai_total) * 100;
        cekPink("$src_nilai_total = $harga_list[$src_produk_id] * $src_produk_qty;");
        cekPink("$diskon_persen = ($free_nilai_total / $src_nilai_total) * 100;");

        $data_barus = array(
            "minim" => $_POST['minim'],
            // "maxim" => $_POST['minim'],

            "jenis" => "free_produk",
            "label_diskon" => $_POST['label_diskon'],
            "persen" => $diskon_persen,
            //            "nilai"     => $nilais[$ix],
            //            "harga"     => $hargas[$ix],
            "produk_nama" => $_POST['produk'],
            "produk_id" => $src_produk_id,
            "free_produk_nama" => $_POST['free_produk'],
            "free_produk_id" => $free_produk_id,
            "free_produk_qty" => $_POST['qty_free_produk'],
            "dtime_start" => $_POST['tanggal_start'],
            "dtime_end" => $_POST['tanggal_stop'] . " 23:59:59",
            "hour_start" => $_POST['hour_start'],
            "hour_end" => $_POST['hour_end'],
            "kelipatan" => isset($_POST['kelipatan']) ? $_POST['kelipatan'] : "",
            "quota_global" => $_POST['quota_global'] * 1,
            "nomer_diskon" => is_numeric($no_diskon) ? $no_diskon : strtotime(date('Y-m-d H:i:s')),
            "expired" => strtotime(date('Y-m-d H:i:s', strtotime($_POST['tanggal_stop'] . " 23:59:59"))),
            "cabang_id" => my_cabang_id(),
            "toko_id" => my_toko_id(),
            "author" => my_id(),
            "status" => 1,
        );

        /*----------insert-----*/
        $dk->setTokoId(my_toko_id());
        // arrPrintHijau($data_barus);
        $dk->saveDiskonFreeProduk($data_barus);


        // matiHere("belum commit @" . __LINE__ . "<hr>");
        if ($this->db->trans_complete()) {
            $link_member = base_url() . "diskon/Setting/$my_controler";
            echo "<script>
                    top.$('#$my_div').load('$link_member', function(){ top.swal('sukses'); setTimeout( function(){ top.swal.close()}, 1000) });
                </script>";
        }
        else {
            echo "<script>
                top.swal('error cuyyy');
            </script>";
        }


    }

    public function do_delete_free_produk()
    {
        arrPrintKuning($_REQUEST);
        $minim = $_GET['minim'];
        $jenis = $_GET['jn'];
        $ctr = $_GET['ctr'];
        $div = $_GET['div'];
        $this->load->model("Mdls/MdlDiskonFreeProduk");
        $dg = new MdlDiskonFreeProduk();

        $this->db->trans_start();
        $data_upds = array(
            "trash" => 1,
        );
        $condites = array(
            "jenis" => $jenis,
            "minim" => $minim,
            "toko_id" => my_toko_id(),
        );
        $dg->updateData($condites, $data_upds);
        showLast_query("merah");
        //
        // matiHere("belum comit " . __LINE__);
        $this->db->trans_complete();

        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
        // $id_row_ = $id_row + 1;
        $link_member = base_url() . "diskon/Setting/$ctr";
        echo "<script>
                top.$('#$div').load('$link_member');
            </script>";
    }

    public function cek_free_produk()
    {
        $this->load->library("Diskon");
        $dk = new Diskon();
        $dk->setTokoId(my_toko_id());
        $src_cls = $dk->callDiskonFreeProduk(1);
        showLast_query("kuning");
        $src_datas = $src_cls["diskon_free_produk"];
        // arrPrint();
        $dtime_now = dtimeNow();
        $stamp_now = strtotime($dtime_now);
        cekBiru("$stamp_now | $dtime_now");
        foreach ($src_datas as $src_data) {
            $id = $src_data['id'];
            $expired = $src_data['expired'];
            $expired_f = date("Y-m-d H:i:s", $expired);
            $dtime_end = $src_data['dtime_end'];

            cekHijau("$id | $expired | $expired_f || $dtime_end");
            if ($stamp_now >= $expired) {
                // sent to trah

            }
        }
    }

    public function do_status_cek()
    {
        arrPrintPink($_GET);
        arrPrintPink(url_segment());
        $id = $_GET['id'];
        $mdl_get = url_segment(4);
        $mdl = isset($mdl_get) ? $mdl_get : "MdlDiskonFreeProduk";

        $this->load->model("Mdls/$mdl");
        $dg = new $mdl();

        $this->db->trans_start();

        $condites = array(
            "id" => $id,
            "toko_id" => my_toko_id(),
        );
        // $this->db->where($condites);
        $srcs = $dg->lookupByCondition($condites)->row();
        showLast_query("biru");
        // arrPrintHijau($srcs);
        $db_status = isset($srcs->status) ? $srcs->status : 0;

        // update status
        $new_status = $db_status == 1 ? 0 : 1;
        $data_upds = array(
            "status" => $new_status,
        );

        $dg->updateData($condites, $data_upds);
        showLast_query("merah");
        cekBiru("<hr>");
        //
        if ($new_status == 1) {
            echo lgShowSuccess("Sukses", "Diskon sudah aktif");
            $old_status_str = "non aktif";
            $new_status_str = "aktif";
        }
        else {
            echo lgShowWarning("Sukses", "Diskon sudah tidak aktif");
            $old_status_str = "aktif";
            $new_status_str = "non aktif";
        }
        // matiHere("belum comit " . __LINE__);
        $this->db->trans_complete();

        writeLog("diskon", "$mdl", "$new_status_str", "", my_id(), my_name(), $new_status_str, $old_status_str);


    }

    public function do_kelipatan_cek()
    {
        $id = $_GET['id'];

        $this->load->model("Mdls/MdlDiskonFreeProduk");
        $dg = new MdlDiskonFreeProduk();

        $this->db->trans_start();

        $condites = array(
            "id" => $id,
            "toko_id" => my_toko_id(),
        );
        // $this->db->where($condites);
        $srcs = $dg->lookupByCondition($condites)->row();
        showLast_query("biru");
        // arrPrintHijau($srcs);
        $db_status = $srcs->kelipatan;

        // update status
        $new_status = $db_status == 1 ? 0 : 1;
        $data_upds = array(
            "kelipatan" => $new_status,
        );

        $dg->updateData($condites, $data_upds);
        showLast_query("merah");
        //
        if ($new_status == 1) {
            echo lgShowSuccess("Sukses", "berlaku free produk setiap kelipatan");
            $old_status_str = "non aktif";
            $new_status_str = "aktif";
        }
        else {
            echo lgShowWarning("Sukses", "free produk tidak berlaku kelipatan");
            $old_status_str = "aktif";
            $new_status_str = "non aktif";
        }
        // matiHere("belum comit " . __LINE__);
        $this->db->trans_complete();

        writeLog("diskon", "free produk", "$new_status_str", "", my_id(), my_name(), $new_status_str, $old_status_str);


    }

    /*------untuk selector produk pada yg akan diberikan diskon-----*/
    public function searchProduk()
    {
        // arrPrintHijau($_GET);
        $kword_0 = $_GET['key'];

        $kword_00 = explode(" ", $kword_0);
        $input_id = $_GET['id'];
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $count_kword = strlen($kword_0);
        /*--keyword hasil explode */


        $src_pr_obj = array();
        $produk_ids = array();
        $jml_data = 0;
        if ($count_kword > 0) {
            foreach ($kword_00 as $kword) {
                $condites = array(
                    "nama like" => "%$kword%",
                    "kode like" => "%$kword%",
                    "barcode like" => "%$kword%",
                );
                $this->db->group_start();
                $this->db->or_where($condites);
                $this->db->group_end();
            }

            $src_pr_obj = $pr->callSpecs();

            // showLast_query("kuning");
            $jml_data = sizeof($src_pr_obj);
            // cekBiru(sizeof($src_pr_obj));
            $produk_ids = array_keys($src_pr_obj);
            /*-----------produk harga------------*/
            $prod_hargas = array();
            if (sizeof($produk_ids) > 0) {

                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $hp->setTokoId(my_toko_id());
                // $hp->setCabangId(my_cabang_id());
                $hp->setCabangId($this->cabang_id);
                $this->db->where("jenis_value", "harga_list");
                $prod_hargas = $hp->callSpecs($produk_ids);
            }
            // showLast_query("kuning");
            // arrPrintKuning($prod_hargas);
            $harga_list = array();
            foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
                foreach ($prod_harga_00s as $prod_harga) {
                    $nilai = $prod_harga->nilai * 1;
                    $harga_list[$prod_id] = $nilai;
                }
            }
            // arrPrintHijau($harga_list);
        }
        $var = "";
        $var_isi = "";
        $btn_hidde = "";
        // $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        if (sizeof($src_pr_obj) > 0) {
            $var = $jml_data;

            $var_isi .= "<ol class='todo-list ui-sortable'>";
            foreach ($src_pr_obj as $item) {
                // arrPrint($item);
                $id = $item->id;
                $nama = $item->nama;
                $satuan = $item->satuan;
                $harga_jual = isset($harga_list[$id]) ? $harga_list[$id] : 0;

                $nama_f = highlight_he_format($nama, $kword_0);
                // $nama_ff = highlight_2($nama,$kword_0);

                $var_isi .= "<li style='padding: 3px 5px;'><a href='javascript:void(0)' onclick=\"$('#$input_id').val('$nama');$('#harga_$input_id').val('$harga_jual');$('#harga_text_$input_id').val('$harga_jual');\">$nama_f ($satuan)</a></li>";
            }
            $var_isi .= "</ol>";
        }
        $display = "display:block;";
        if ($jml_data > 10) {
            echo "<div style='width: 100px'>ditemukan <span style='font-size: 1.3em;color: red;'>$var</span> item yang berkaitan dengan <span class='text-red'>$kword_0</span>";
            // echo "<button type='button' onclick=\"$('#hasil_$input_id').fadeIn();\">tampilkan</button> <button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
            echo "</div>";
            // $display = "display:none;";
            // echo $var_isi;
            // $btn_hidde = "tulisakan nama produk";
        }
        elseif ($jml_data == 0) {
            $var_isi = "tulisakan nama produk";
            if ($count_kword > 0) {

                $var_isi = "tidak ditemukan data yang berhubunga dengan <span class='font-size-1-2 text-red'>$kword_0</span>";
            }
        }
        // else{
        //     $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        // }
        echo "<div style='width: 150px; $display'  id='hasil_$input_id'>$var_isi <hr style='padding: 0;margin: 10px 0 0;'> $btn_hidde</div>";


    }

    /**
     * hadiah penjualan
     * */
    public function formHadiahPenjualan()
    {
        // arrPrint($_GET);
        // arrPrintHijau(url_segment());
        $produk_id = $_GET['id'];

        /* ----------------------------------------------------------
         * freeproduk relasi
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPenjualan");
        $dpps = new MdlDiskonPenjualan();
        $validationRules = $dpps->getValidationRules();
        $fields = $dpps->getFields();
        foreach ($validationRules as $field => $validate_field) {
            // arrPrintPink($validate_field);
            foreach ($validate_field as $validate_item) {
                $validateKoloms[$validate_item][] = $field;
            }
        }

        $src_freeProduks = $dpps->callSpecs($produk_id);
        // showLast_query("kuning");

        // arrPrintKuning($src_freeProduks);
        $src_freeProduk = isset($src_freeProduks[$produk_id]) ? $src_freeProduks[$produk_id] : "";
        // arrPrintHijau($src_freeProduk);
        $produk_rel_id = isset($src_freeProduk->produk_rel_id) ? $src_freeProduk->produk_rel_id : "";
        // $supplier_id = isset($src_freeProduk->supplier_id) ? $src_freeProduk->supplier_id : "";

        $p = new Layout();
        $p->setFormGroupLeftClass("col-md-3 text-uppercase");
        $p->setFormGroupRightClass("col-md-9");
        $tbl_form = "";
        /* --------------------------
         * supplier
         * --------------------------*/
        // $this->load->model("Mdls/MdlSupplier");
        // $cu = new MdlSupplier();
        // // $this->db->order_by("nama", "asc");
        // $srcCus = $cu->callSpecs();
        // showLast_query("kuning");
        // arrPrintHijau($srcCus);

        // $select_td = "<select data-style='btn btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker' name='supplier_id'>";
        // $select_td .= "<option value=''>---pilih supplier----</option>";
        // foreach ($srcCus as $cuid => $srcCus) {
        //     $cunama = $srcCus->nama;
        //     $tlp_1 = $srcCus->tlp_1;
        //     $tlp_f = strlen(($tlp_1)) > 3 ? "($tlp_1)" : "";
        //     $selected = $cuid == $supplier_id ? "selected" : "";
        //     $select_td .= "<option value='$cuid' $selected>$cunama $tlp_f</option>";
        // }
        // $select_td .= "</select>";
        // $select_td .= "<script>
        //     $('.selectpicker').selectpicker();
        // </script>";
        // $tbl_form .= $p->form_group("supplier", "$select_td");
        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        if (ipadd() == "202.65.117.72") {
            //            $this->db->limit(2);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
        }
        $src_prs = $pr->callSpecs();

        $selector_hadiah = "";
        $selector_hadiah .= "<div class='btn-group'>";
        $selector_hadiah .= "<select data-style='btn btn-sm btn-danger' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker select2' name='produk_rel_id' id='produk_rel_id'>";
        $selector_hadiah .= "<option value=''>---pilih hadiah-----</option>";
        foreach ($src_prs as $src_pr) {
            $pr_id = $src_pr->id;
            $pr_nama = $src_pr->nama;
            $pr_barcode = $src_pr->barcode;
            $pr_kategori = $src_pr->kategori_nama;
            if (strlen($pr_kategori) > 1) {
                if ($pr_kategori == "unit") {
                    $pr_kategori_0 = "<span style='color: darkmagenta;'>$pr_kategori*</span>";
                }
                else {
                    $pr_kategori_0 = "<span style='color: coral;'>$pr_kategori</span>";
                }

                $pr_kategori_f = "($pr_kategori_0)";

            }
            else {

                $pr_kategori_f = "";
            }

            $pr_selected = $produk_rel_id == $pr_id ? "selected" : "";
            $selected_color = $produk_rel_id == $pr_id ? "text-success font-size-1-5" : "";
            $selector_hadiah .= "<option class=' text-left $selected_color' value='$pr_id' $pr_selected>$pr_barcode | $pr_nama $pr_kategori_f</option>";

        }

        $selector_hadiah .= "</select>";
        $selector_hadiah .= "<button type='button' class='btn btn-warning pull-right' onclick=\"\"><i class='fa fa-plus'></i></button>";
        $selector_hadiah .= "</div>";

        $tbl_form .= $p->form_group("hadiah", "$selector_hadiah");

        $nilai_hadiah_params = array(
          "harga_modal" => array(
              "label" => "harga modal",
          ),
          "harga_jual" => array(
              "label" => "harga jual",
          ),
          "harga_custom" => array(
              "label" => "harga custom",
          ),

        );
        $selector_nilai_hadiah = "";

        foreach ($nilai_hadiah_params as $key => $param) {

            $label = isset($param['label']) ? ucfirst($param['label']) : ucfirst($key);
            $checked = $src_freeProduk->nilai_hadiah == $key ? "checked" : "";
            // $selector_nilai_hadiah .= '<div class="radio">';
            $selector_nilai_hadiah .= '  <label class="radio-inline">';
            $selector_nilai_hadiah .= '    <input type="radio" name="nilai_hadiah" id="'.$key.'" value="'.$key.'" '.$checked.'> '.$label;
            $selector_nilai_hadiah .= '  </label>';
            // $selector_nilai_hadiah .= '</div>';
        }
        $tbl_form .= $p->form_group("ref. nilai hadiah", "$selector_nilai_hadiah");
        // $tbl_form .= $p->form_group("harga hadiah", "<input type='text' name='produk_rel_harga' class='form-control'>");
        // $tbl_form .= $p->form_group("sdk produk", "<input type='number' name='qty_min' step='1' class='form-control'>");
        // $tbl_form .= $p->form_group("tanggal mulau", "<input type='date' name='start_date' class='form-control'>");
        // $tbl_form .= $p->form_group("tanggal selesai", "<input type='date' name='expired_date' class='form-control'>");


        // arrPrintKuning($fields);
        // arrPrintKuning($validateKoloms);
        foreach ($fields as $fkey => $param_field) {
            $kolom = $param_field['kolom'];
            $label = $param_field['label'];
            $inputType = $param_field['inputType'];
            $inputDefaultValue = $param_field['defaultValue'];
            $format = isset($param_field['format']) ? $param_field['format'] : "";

            $nilai = isset($src_freeProduk->$kolom) ? $src_freeProduk->$kolom : (isset($param_field['defaultValue']) ? $param_field['defaultValue'] : "");

            if ($format == "angka") {
                $nilai_f = $nilai != 0 ? $nilai * 1 : "";
            }
            else {
                $nilai_f = $nilai;
            }


            $req_tanda = "";
            $therule = "";
            if (in_array($kolom, $validateKoloms['required'])) {
                $req_tanda = "<r>*</r>";
                $therule = "required";
            }

            switch ($inputType) {
                case "combo":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button><button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"location.href='$link_editor_act'\"><i class='fa fa-pencil'></i></button></div>" : "<div></div>";
                    // $optionals = "<option value=''> Pilih $str_label </option>";
                    // foreach ($dataSources as $key_src => $label_src) {
                    //     $fSelected = $fValue == $key_src ? "selected" : "";
                    //     $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    // }
                    // $eventSession = $this->createSessionData();
                    // $tbl_form = "<div class='input-group input-group-sm'>";
                    //
                    // if (count($dataSources) == 0) {
                    //     $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                    //     $tbl_form .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    // }
                    // else {
                    //     $tbl_form .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    // }
                    //
                    // $tbl_form .= $optionals;
                    // $tbl_form .= "</select>";
                    // $tbl_form .= $btn_add;
                    // $tbl_form .= "</div>";
                    // $tbl_form .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $tbl_form .= $p->form_group("$label $req_tanda", "<input $therule placeholder='$label' id='$kolom' type='text' name='$kolom' class='form-control' value='$nilai_f'>");
                    break;
                case "date":
                    $tbl_form .= $p->form_group("$label $req_tanda", "<input $therule placeholder='$kolom' type='$inputType' name='$kolom' class='form-control' value='$nilai'>");
                    break;
            }
        }
        $kelipatan = isset($src_freeProduk->kelipatan) ? $src_freeProduk->kelipatan : "";
        $kelipatan_0 = $kelipatan == 0 ? "checked" : "";
        $kelipatan_1 = $kelipatan == 1 ? "checked" : "";
        $tbl_form .= $p->form_group("berlaku kelipatan", "<input type='radio' name='kelipatan' value='1' $kelipatan_1> yes <input type='radio' name='kelipatan' value='0' $kelipatan_0> no");
        $status = isset($src_freeProduk->status) ? $src_freeProduk->status : "";
        $status_0 = $status == 0 ? "checked" : "";
        $status_1 = $status == 1 ? "checked" : "";
        $tbl_form .= $p->form_group("status", "<input type='radio' name='status' value='1' $status_1> aktif <input type='radio' name='status' value='0' $status_0> non aktif");
        $tbl_form .= $p->form_group("produk", "<input type='number' name='produk_id' value='$produk_id'>", true);

        $link_action = MODUL_PATH . "Setting/do_save_hadiah_penjualan";
        $var = "";
        $var .= "<style type='text/css'>
            .form-control{
                height :30px;
            }
            .form-group{
                margin-bottom :5px;
            }
        </style>";
        $var .= "<div class='alert alert-danger'>";
        $var .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>";
        $var .= "KETERANGAN <br>";
        $var .= "Harga hadiah adalah nilai per unit <br>";
        $var .= "sdk (minimal qty) : syarat kuantiti minimal untuk mendapatkan hadiah";
        $var .= "</div>";

        $var .= "<div class='overflow-h'>";
        $var .= "<form method='post' enctype='application/x-www-form-urlencoded' action='$link_action' target='result'>";
        $var .= $tbl_form;
        $var .= "<hr>";
        $var .= "<button type='button' class='btn btn-danger pull-left' onclick=\"\">Delete</button>";
        $var .= "<button type='button' id='btn_history' class='btn btn-info pull-left' onclick=\"\">Show/Hidde Histori</button>";
        $var .= "<button type='submit' id='btn_simpan' class='btn btn-primary pull-right'>Simpan</button>";
        $var .= "</form>";
        $var .= "</div>";

        $history = $this->viewHistoriHadiahPenjualan($produk_id);
        $var .= "<div id='wadah_history' style='display: none;margin-top: 10px;'>$history</div>";

        $url_harga = MODUL_PATH ."Setting/getHargaProduk";
        $var .= "<script>            
            $('.selectpicker').selectpicker();
            
            $('#btn_history').click(function(){
                $('#wadah_history').fadeToggle();
            });
            
            $(\"#produk_rel_harga\").prop(\"readonly\", true);
            $(\"#btn_simpan\").prop(\"disabled\", true);
            
            $(\"input[name='nilai_hadiah']\").on(\"change\", function() {
                var tipe = $(this).val(); // harga_modal, harga_jual, harga_custom
                var produk_id = $(\"#produk_rel_id\").val(); // ambil dari select hadiah

                console.log(produk_id);
                
                if (!produk_id) {
                    swal({
                        type: 'warning',
                        html: 'Pilih hadiah terlebih dahulu',
                    });
                    
                    $(\"input[name='nilai_hadiah']\").prop(\"checked\", false); 
                    $(\"#produk_rel_harga\").val(\"\").prop(\"readonly\", true);
                    return;
                }
        
                if (tipe === \"harga_custom\") {
                    // aktifkan manual input
                    $(\"#produk_rel_harga\").prop(\"readonly\", false).val(\"\");
                    $(\"#btn_simpan\").prop(\"disabled\", false);
                } else {
                    // lock input, ambil data via ajax
                    $(\"#produk_rel_harga\").prop(\"readonly\", true).val(\"...\");
        
                    $.ajax({
                        url: \"$url_harga\",
                        type: \"GET\",
                        dataType: \"json\",
                        data: {
                            produk_id: produk_id,
                            tipe: tipe
                        },
                        success: function(res) {
                            if (res.status) {
                                var harga = res.harga.data.jual;
                                
                                switch (tipe) {
                                  case 'harga_modal':
                                      harga = res.harga.data.hpp;
                                      
                                      if(harga == 0){
                                           // $(\"#produk_rel_harga\").prop(\"readonly\", false).val(\"\");
                                            swal({
                                                type: 'warning',
                                                html: 'data pemebelian belum ada, silahkan pilih referensi hadiah kastum, untuk menuliskan secara manual',
                                            });
                                      }
                                      break;
                                      default:
                                          harga = res.harga.data.jual;
                                          break;
                                }
                                console.log('harga:', harga);
                                
                                $(\"#produk_rel_harga\").val(Math.floor(harga));
                                $(\"#btn_simpan\").prop(\"disabled\", false);
                            } else {
                                $(\"#produk_rel_harga\").val(\"\");
                                alert(\"Harga tidak ditemukan\");
                            }
                        },
                        error: function() {
                            $(\"#produk_rel_harga\").val(\"\");
                            alert(\"Gagal mengambil harga dari server\");
                        }
                    });
                }
            });
        </script>";

        echo $var;
    }

    public function do_save_hadiah_penjualan()
    {
        $post = $_POST;
        arrPrintHijau($post);
        // $supplier_id = $post['supplier_id'];
        $produk_id = $post['produk_id'];
        $produk_rel_id = $post['produk_rel_id'];
        $produk_ids = array(
            $produk_id, $produk_rel_id
        );
        /* ----------------------------------------------------------
         * freeproduk relasi
         * ----------------------------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPenjualan");
        $dpps = new MdlDiskonPenjualan();
        // $src_freeProduks = $dpps->callSpecs($produk_id);
        // $jml_data = count($src_freeProduks);
        // showLast_query("kuning", $jml_data);
        /* ---------------------
         * dta produk
         * ---------------------*/
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        if (ipadd() == "202.65.117.72") {
            //            $this->db->limit(2);
            //            $this->db->where_in("id",array("51580","55458","54756","55346"));
        }
        $src_prs = $pr->callSpecs($produk_ids);
        showLast_query("kuning");
        $src_pr = $src_prs[$produk_id];
        $src_rel_pr = $src_prs[$produk_rel_id];



        // arrPrintKuning($src_pr);
        $post['produk_nama'] = $src_pr->nama;
        // $post['supplier_id'] = $src_pr->supplier_id;
        $post['produk_rel_nama'] = $src_rel_pr->nama;
        $post['nama'] = "diskon free produk";
        $post['dtime'] = dtimeNow();
        $post['oleh_id'] = my_id();
        $post['oleh_nama'] = my_name();
        $post['status'] = 1;
        // $post['per_supplier_diskon_nama'] = "diskon free produk";

        // arrPrintOrange($post);
// matiHere(__LINE__);
        $this->db->trans_start();
        $dpps->setData($post);
        $ceking = $dpps->writeFreeDiscProduk();



        // matiHere("belum commit @" . __LINE__);
        $this->db->trans_complete();
        if ($ceking != false) {
            cekBiru("sukses");
            echo lgShowSuccess("Berhasil", "perubahan data berhasil disimpan");
        }
        else {
            cekBiru("gagal");
            echo lgShowError("Upss...", "Penyimpanan data tidak berhasil");
        }

    }

    public function viewHistoriHadiahPenjualan($produk_ids)
    {

        $this->load->model("Mdls/MdlDiskonPenjualan");
        $dpps = new MdlDiskonPenjualan();
        $dpps->setFilters(array());
        $dpps->addFilter("produk_id=$produk_ids");
        $this->db->order_by("id", "desc");
        $src_prs = $dpps->lookupAll()->result();

        $src_prs = count($src_prs) > 0 ? $src_prs : array();
        // showLast_query("merah");
        // arrPrint($src_prs);

        foreach ($src_prs as $src_pr) {

        }

        $p = new Layout();
        $headers = array(
            // "id",
            "dtime" => array(
                "label" => "tanggal",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_nama" => array(
                "label" => "produk",
                "attr_head" => "class='text-uppercase'"
            ),
            "qty_min" => array(
                "label" => "sdk (minim pembelian)",
                "attr_head" => "class='text-uppercase'",
            ),
            "supplier_nama" => array(
                "label" => "supplier",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_rel_nama" => array(
                "label" => "hadiah",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_rel_qty" => array(
                "label" => "qty hadiah",
                "attr_head" => "class='text-uppercase'",
            ),
            "produk_rel_harga" => array(
                "label" => "harga hadiah",
                "attr_head" => "class='text-uppercase'",
            ),
            "start_date" => array(
                "label" => "tgl mulai",
                "attr_head" => "class='text-uppercase'",
            ),
            "expired_date" => array(
                "label" => "tgl selesai",
                "attr_head" => "class='text-uppercase'",
            ),
            "oleh_nama" => array(
                "label" => "oleh",
                "attr_head" => "class='text-uppercase'",
            ),
        );
        $p->setLayoutTableHeaderKolom($headers);
        $tbls = "<div class='border-cek'>";
        $tbls .= $p->layout_table($src_prs);
        $tbls .= "</div>";

        return $tbls;
    }

    public function getHargaProduk(){
        // arrPrintHijau($_GET);
        $produk_ids = $_GET['produk_id'];
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        $hp->setCabangId($this->cabang_id);
        $prod_hargas = $hp->callSpecs($produk_ids);
        // showLast_query("hijau");

        // arrPrintOrange($prod_hargas);
        $hargas = array();
        foreach ($prod_hargas as $pro_id => $prod_hargas_1) {
            foreach ($prod_hargas_1 as $item_params) {
                $jenis_value = $item_params->jenis_value;
                $nilai = $item_params->nilai;

                $hargas[$jenis_value] = $nilai;
            }

        }

        $vars = [];
        $vars["data"] = $hargas;

        echo json_encode(array(
            'status' => true,
            'harga'  => $vars
        ));
    }

    /*---------------------------------------------------------------*/

    public function viewTebusMurah()
    {
        // arrPrint();
        $this->load->library("Diskon");
        $dk = new Diskon();
        // matiHere();
        /* ------------------------------------------------------
         * level
         * ------------------------------------------------------*/
        // $this->load->model("Mdls/MdlCustomerLevel");
        // $cl = new MdlCustomerLevel();

        $dk->setTokoId(my_toko_id());
        $condites = array(
            "tipe" => "tebus_murah",
            // "tipe" => "diskon",
        );
        $dk->setCustomerLevelCondite($condites);
        $src_cls = $dk->callCustomerLevelDiskon();
        // $src_cls = $dk->callTebusMurah();
        // showLast_query("kuning");
        // matiHere();
        // arrPrint($src_cls);
        // cekMerah(count($src_cls));
        $id_row = 8989;
        $tmp_cls = array();
        $tipe = "";
        foreach ($src_cls['customer_level_diskon'] as $src_cl) {
            // arrPrintWebs($src_cl);
            $tipe = $src_cl['tipe'];
            $jenis = $src_cl['jenis'];
            $minim = $src_cl['minim'];
            $persen = isset($src_cl['persen']) ? $src_cl['persen'] : 0;
            $nilai = isset($src_cl['nilai']) ? $src_cl['nilai'] : 0;
            $customer_level = $src_cl['customer_level'];
            if (!isset($data_koloms[$jenis][$minim]["level_$customer_level"])) {
                $data_koloms[$jenis][$minim]["level_$customer_level"] = 0;
            }
            $data_koloms[$jenis][$minim]["level_$customer_level"] = $persen;

            $data_koloms[$jenis][$minim] = $src_cl;

            $id_row++;
            $id_row = "min_" . $minim;

            // $link_hapus = base_url() . "diskon/Setting/do_delete_member?jn=$jenis&minim=$minim";
            //             $data_koloms[$jenis][$minim]['action'] = "<div class='btn-group'>
            // <button type='button' class='btn btn-link btn-sm' id='$id_row' onclick=\"btn_edit('$jenis','$minim');\"><i class='fa fa-pencil'></i></button>
            //  <button type='button' class='btn btn-sm btn-link' onclick=\"btn_alert_result('Oppss','akan meghapus setting diskon member?','$link_hapus');\"><i class='fa fa-trash'></i></button></div>";

            $tmp_cls = $data_koloms;
        }
        // arrPrintHijau($tmp_cls);
        $src_clevel_diskons = array();

        foreach ($tmp_cls as $tmp_cl_0) {
            foreach ($tmp_cl_0 as $tmp_cl) {

                $tmp_cl["button"] = "hadiah";
                $src_clevel_diskons[$tmp_cl['jenis']][] = $tmp_cl;

            }
        }
        // arrPrintKuning($src_clevel_diskons);
        if (count($src_clevel_diskons) == 0) {
            // $src_clevel_diskons[$condites['tipe']][] = array();
        }

        $level_header = array();
        $level_header = array(
            "jenis" => array(
                "label" => "jenis reward",
                // "attr"         => "class='form-control'",
                "attr_footer" => "class='form-control' readonly",
                "tipe_input" => "text",
                "default_data" => "tebus_murah",
                "data_srcs" => array(
                    "transaksi",
                    "birthday"
                ),
            ),
        );
        $level_header['minim'] = array(
            "label" => "minimal transaksi",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        $level_header['persen'] = array(
            "label" => "diskon",
            "attr_footer" => "class='form-control text-right' required",
            // "format" => "formatField_he_format",
            "attr" => "class='text-right'",
        );
        // foreach ($src_cls['customer_level'] as $src_cl) {
        //     $level_id = $src_cl->id;
        //     $level_nama = $src_cl->nama;
        //
        //     $attributs['label'] = "level " . $level_nama;
        //     $attributs['attr_footer'] = "class='form-control text-right' max='100'";
        //     $attributs['tipe_input'] = "number";
        //     $attributs['attr'] = "class='text-center'";
        //     $level_header['level_' . $level_id] = $attributs;
        //     // $level_header['level_'][$level_id] = $attributs;
        //
        // }
        $level_header['nilai'] = array(
            "label" => "max nilai diskon",
            "attr" => "class='text-right'",
            "attr_footer" => "class='form-control text-right'",
        );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        // $level_header['button'] = array(
        //     "label"       => "produk murah",
        //     // "tipe_input"  => "date",
        //     "links"       => array(
        //         "target" => "diskon/Setting/viewProdukMurah",
        //         "key"    => "minim",
        //         "title"  => "Daftar produk tebus murah untuk transaksi ",
        //     ),
        //     "attr"        => "class='text-center'",
        //     "attr_footer" => "class='form-control'",
        // );
        $level_header['tanggal_start'] = array(
            "label" => "tanggal mulai",
            "tipe_input" => "date",
            "attr" => "class='text-center'",
            "attr_footer" => "class='form-control'",
        );
        $level_header['tanggal_stop'] = array(
            "label" => "tanggal selesai",
            "attr" => "class='text-center'",
            "tipe_input" => "date",
            "attr_footer" => "class='form-control'",
        );
        $level_header['status'] = array(
            "label" => "status",
            "attr" => "class='text-center'",
            "tipe_input" => "checkbox",
            "onclick_fx" => "status_cek",
            // "attr_footer" => "class='form-control'",
        );
        $level_header['action'] = array(
            "label" => "action",
            "default_data" => "save cashback setting",
            "tipe_input" => "submit",
            "attr_footer" => "class='btn btn-danger'",
            "attr" => "class='text-left'",
            "links" => array(
                "target" => "diskon/Setting/viewProdukMurah",
                "key" => "minim",
                "title" => "Daftar produk tebus murah untuk transaksi ",
            ),
        );

        // arrPrintWebs($src_clevel_diskons);

        $data = array(
            "mode" => "viewTebusMurah",
            // "isMobile"       => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            // "template"       => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],
            "title" => "Setting tebus Murah",
            "subTitle" => "-",
            // "arrHeaders"     => $arrHeaders,
            // "master_data"    => isset($src_pr) ? $src_pr : array(),
            // "grosir_header"  => $grosir_header,
            // "grosir_data"    => $src_dg,
            "level_header" => $level_header,
            "level_data_0" => $src_clevel_diskons,
            "my_div" => "lima",
            "my_controler" => __FUNCTION__,
            "tipe" => $tipe,
            // "jenisTransaksi" => $jenisTr,

            // "submit_button_target" => $this->modul . "/Transaksi/validate/",
        );
        //arrPrint($data);
        $this->load->view("setting", $data);
    }

    public function do_save_tebus_murah()
    {
        arrPrintWebs($_REQUEST);
        // $level_id = $_GET['id'];
        // $persen = $_GET['persen'];
        // $nilai = $_GET['nilai'];
        // $harga = $_GET['harga'];

        // $levels = $_POST[]
        // $data_post = $_POST;

        $this->load->library("Diskon");
        $dk = new Diskon();
        $my_controler = $_POST['my_controler'];
        $my_div = $_POST['my_div'];
        /* -------------------------------------------------------------
         * bila kolom data yg akan disimpan daftarkan dalam array ini ya
         * -------------------------------------------------------------*/
        $data_koloms = array(
            "tanggal_start",
            "tanggal_stop",
            "jenis",
            "tipe",
            "minim",
            "minim_be",
            // "periode",
            "quota_global",
            "nilai",
            // "customer_level",
        );
        $dk->setTokoId(my_toko_id());
        $src_cls = $dk->callCustomerLevelDiskon();
        showLast_query("orange");
        // arrPrintHijau($src_cls);
        $data_posts = array();
        foreach ($src_cls['customer_level'] as $src_cl) {

            $level_id = $src_cl->id;
            $level_id = 0;

            $level_nama = $src_cl->nama;

            // cekBiru($level_id);
            $persen = isset($_POST['persen']) ? $_POST['persen'] : 0;
            if ($persen > 0) {
                foreach ($data_koloms as $data_kolom) {
                    $data_post[$data_kolom] = isset($_POST[$data_kolom]) ? $_POST[$data_kolom] : 0;
                }

                $data_post['customer_level'] = $level_id;
                $data_post['persen'] = $persen;

                $data_posts[$level_id] = $data_post;
            }
        }
        arrPrint($data_posts);
        cekHijau("------------------------masukin data-------------------------");
        $this->db->trans_start();
        /*----------insert-----*/
        $dk->setTokoId(my_toko_id());
        foreach ($data_posts as $clevel_id => $data_post) {
            cekMerah("$clevel_id");
            arrPrintPink($data_post);
            echo "--------------------------------------- " . __METHOD__;
            $dk->setCustomerLevelCondite(array("minim" => $_POST['minim']));
            $xx = $dk->doSaveCustomerLevelDiskon($clevel_id, $data_post);
            // showLast_query("kuning");
            // break;
        }

        // matiHere("belum commit @" . __LINE__);
        $this->db->trans_complete();
        $link_member = base_url() . "diskon/Setting/$my_controler";
        echo "<script>
                top.$('#$my_div').load('$link_member');
            </script>";
    }

    public function do_delete_tebus_murah()
    {
        arrPrintKuning($_REQUEST);
        $minim = $_GET['minim'];
        $jenis = $_GET['jn'];
        $ctr = $_GET['ctr'];
        $div = $_GET['div'];
        $this->load->model("Mdls/MdlDiskonFreeProduk");
        $dg = new MdlDiskonFreeProduk();

        $this->db->trans_start();
        $data_upds = array(
            "trash" => 1,
        );
        $condites = array(
            "jenis" => $jenis,
            "minim" => $minim,
            "toko_id" => my_toko_id(),
        );
        $dg->updateData($condites, $data_upds);
        showLast_query("merah");
        //
        // matiHere("belum comit " . __LINE__);
        $this->db->trans_complete();

        // echo lgShowSuccess("Sukses", "Harga grosir berhasil dihapus");
        // $id_row_ = $id_row + 1;
        $link_member = base_url() . "diskon/Setting/$ctr";
        echo "<script>
                top.$('#$div').load('$link_member');
            </script>";
    }

    /* --------------------------------------------------------------
     * 
     * ---------------------------------------------------------------*/
    public function viewProdukMurah()
    {
        // arrPrintHijau(url_segment());
        // arrPrintKuning($_REQUEST);

        $jenis = "tebus_murah";
        $minim = $_GET['minim'];
        $link_seelctorProduk = MODUL_PATH . get_class($this) . "/selectorProduk";
        $var = "";
        $var .= "<input type='text' class='form-control' placeholde='tulis nama produk' onkeyup=\"$('#pilihan').load('$link_seelctorProduk?jenis=$jenis&minim=$minim&key=' + encodeURI(this.value));\">";
        $var .= "<div id='pilihan'></div>";

        $var .= "<div id='yang_terpilih'>ff</div>";
        $link_produk_terpilih = base_url() . "diskon/" . get_class($this) . "/viewProdukPilihan?minim=$minim";
        $var .= "<script>$('#yang_terpilih').load('$link_produk_terpilih');</script>";

        echo $var;
    }

    public function selectorProduk()
    {
        // arrPrintWebs($_GET);
        $kword_0 = $_GET['key'];
        $minim = $_GET['minim'];
        $jenis = $_GET['jenis'];

        $kword_00 = explode(" ", $kword_0);
        $input_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $count_kword = strlen($kword_0);
        /*--keyword hasil explode */


        $src_pr_obj = array();
        $produk_ids = array();
        $jml_data = 0;
        if ($count_kword > 0) {
            foreach ($kword_00 as $kword) {
                $condites = array(
                    "nama like" => "%$kword%",
                    "kode like" => "%$kword%",
                    "barcode like" => "%$kword%",
                );
                $this->db->group_start();
                $this->db->or_where($condites);
                $this->db->group_end();
            }

            $src_pr_obj = $pr->callSpecs();

            // showLast_query("kuning");
            $jml_data = sizeof($src_pr_obj);
            // cekBiru(sizeof($src_pr_obj));
            $produk_ids = array_keys($src_pr_obj);
            /*-----------produk harga------------*/
            $prod_hargas = array();
            if (sizeof($produk_ids) > 0) {

                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $hp->setTokoId(my_toko_id());
                // $hp->setCabangId(my_cabang_id());
                $hp->setCabangId($this->cabang_id);
                $this->db->where("jenis_value", "harga_list");
                $prod_hargas = $hp->callSpecs($produk_ids);
            }
            // showLast_query("kuning");
            // arrPrintKuning($prod_hargas);
            $harga_list = array();
            foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
                foreach ($prod_harga_00s as $prod_harga) {
                    $nilai = $prod_harga->nilai * 1;
                    $harga_list[$prod_id] = $nilai;
                }
            }
            // arrPrintHijau($harga_list);
        }


        $var = "";
        $var_isi = "";
        $btn_hidde = "";
        // $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        if (sizeof($src_pr_obj) > 0) {
            $var = $jml_data;

            $var_isi .= "<ol class='todo-list ui-sortable'>";
            foreach ($src_pr_obj as $item) {
                // arrPrint($item);
                $id = $item->id;
                $nama = $item->nama;
                $satuan = $item->satuan;
                $harga_jual = isset($harga_list[$id]) ? $harga_list[$id] : 0;
                $harga_jual_f = formatField_he_format("harga", $harga_jual);
                $nama_f = highlight_he_format($nama, $kword_0);
                // $nama_ff = highlight_2($nama,$kword_0);

                // $var_isi .= "<li style='padding: 3px 5px;'><a href='javascript:void(0)' onclick=\"$('#$input_id').val('$nama');$('#harga_$input_id').val('$harga_jual');$('#harga_text_$input_id').val('$harga_jual');\">$nama_f ($satuan)</a><span class='pull-right'>$harga_jual_f</span></li>";
                $link_doSave = base_url() . "diskon/Setting/doSavePilihan?jenis=$jenis&minim=$minim&pid=$id&pnama=" . urlencode($nama);
                $var_isi .= "<li style='padding: 3px 5px;'><a href='javascript:void(0)' onclick=\"$('#yang_terpilih').load('$link_doSave');\">$nama_f ($satuan)</a><span class='pull-right'>$harga_jual_f</span></li>";
            }
            $var_isi .= "</ol>";
        }
        $display = "display:block;";
        if ($jml_data > 10) {
            echo "<div style='wwidth: 350px'>ditemukan <span style='font-size: 1.3em;color: red;'>$var</span> item yang berkaitan dengan <span class='text-red'>$kword_0</span>";
            // echo "<button type='button' onclick=\"$('#hasil_$input_id').fadeIn();\">tampilkan</button> <button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
            echo "</div>";
            // $display = "display:none;";
            // echo $var_isi;
            // $btn_hidde = "tulisakan nama produk";
        }
        elseif ($jml_data == 0) {
            $var_isi = "tulisakan nama produk";
            if ($count_kword > 0) {

                $var_isi = "tidak ditemukan data yang berhubunga dengan <span class='font-size-1-2 text-red'>$kword_0</span>";
            }
        }
        // else{
        //     $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        // }
        echo "<div style='wwidth: 250px; $display'  id='hasil_$input_id'>$var_isi <hr style='padding: 0;margin: 10px 0 0;'> $btn_hidde</div>";


        // echo "pilihan tebus murah";
    }

    public function doSavePilihan()
    {
        arrPrintKuning($_GET);
        $toko_id = my_toko_id();
        $minim = $_GET['minim'];
        $jenis = $_GET['jenis'];
        $pid = $_GET['pid'];
        $pnama = $_GET['pnama'];

        $this->load->model("Mdls/MdlDiskonTebusMurah");
        $dtm = new MdlDiskonTebusMurah();
        $newDatas = array(
            "minim" => $minim,
            "produk_id" => $pid,
            "produk_nama" => $pnama,
            "toko_id" => $toko_id,
            "jenis" => $jenis,
        );
        $dtm->saveDiskon($newDatas);

        echo "hasil pilihan";
    }

    public function viewProdukPilihan()
    {
        $this->load->model("Mdls/MdlDiskonTebusMurah");
        $dtm = new MdlDiskonTebusMurah();
        $transaksi_minim = $_GET['minim'];

        $dtm->setTokoId(my_toko_id());
        $srcs = $dtm->callDiskon($transaksi_minim);
        // arrPrintKuning($srcs);
        $produkIds = array();
        foreach ($srcs as $src) {
            $produk_id = $src->produk_id;
            $produkIds[] = $produk_id;
        }

        /* ---------------------------------------------------------------------------------------------
         * harga
         * ---------------------------------------------------------------------------------------------
         */
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();

        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($this->cabang_id);
        $this->db->where("jenis_value", "harga_list");
        $prod_hargas = $hp->callSpecs($produkIds);
        // $src_harga = $dtm->callhargaJual($produkIds);
        $harga_list = array();
        foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
            foreach ($prod_harga_00s as $prod_harga) {
                $nilai = $prod_harga->nilai * 1;
                $harga_list[$prod_id] = $nilai;
            }
        }
        // arrPrintKuning($prod_hargas);
        // arrPrintKuning($harga_list);
        // arrPrintKuning($srcs);

        $header = array(
            "produk_nama" => array(
                "label" => "nama produk"
            ),
            "produk_harga" => array(
                "label" => "harga jual normal"
            ),
        );


        $body = "";
        $no = 0;
        $produkIds = array();
        foreach ($srcs as $src_0) {
            $no++;
            $produk_id = $src_0->produk_id;
            $src = (object)((array)$src_0 + array("produk_harga" => $harga_list[$produk_id]));

            // arrPrintPink($src);

            $body .= "<tr title='$produk_id'>";
            $body .= "<td>$no</td>";
            foreach ($header as $kolom => $item) {
                $nilai = isset($src->$kolom) ? $src->$kolom : 0;
                $body .= "<td>$nilai</td>";
            }
            $body .= "</tr>";
        }


        $tabel = "";
        $tabel .= "<div class=''>";
        $tabel .= "<table >";
        $tabel .= $body;
        $tabel .= "</table>";
        $tabel .= "</div>";

        echo $tabel;
    }

    public function viewUnvalable()
    {
        echo "<style type='text/css'>
            .bg-gelap {
            background-color: #c3c3c3;
            padding: 10px 30px;
            // font-size: 1em;
            
            }
            .text-anu {
            -webkit-background-clip: text;
            -moz-background-clip: border;
            background-clip: content-box;
            color: transparent;
            text-shadow: rgba(255,255,255,0.5) 0px 3px 3px;
            }
            .engraved {
                font-size: 50px;
                font-family: sans-serif;
                background-color: #666666;
                -webkit-background-clip: text;
                -moz-background-clip: text;
                background-clip: text;
                color: transparent;
                text-shadow: rgba(245,245,245,0.5) 2px 2px 1px;
             }
</style>";
        echo "<div class='bg-gelap text-center' style='height: 145px;'>";
        echo "<span style='text-transform: uppercase;'>";
        echo "<span class='text-uppercase text-renggang-5'>untuk saat ini</span>";
        echo "<h1 class='engraved'>layanan belum aktif</h1>";
        echo "</span>";
        echo "</div>";
        // echo underConstruction();
    }

    public function saveDiskonSupplier()
    {
        $arrPost = json_decode($_POST['data']);
        // arrPrintHijau($arrPost);
        $arrData = array();
        $arrDiskons = array();
        if (!empty($arrPost)) {
            foreach ($arrPost as $k => $dat) {
                $diskon_id = $dat->diskon_id; //diskon id
                $sup = $dat->sup; //supplier id
                $pid = $dat->pid; //diskon diskon_1; diskon_5
                $pida = $dat->pida; // persen / nilai
                $val = $dat->val; // value
                $arrData[$sup][$pid][$pida] = $val;

                $arrDiskons[$pid] = $diskon_id;
            }
        }
        $supplier_id = $sup;
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dps = new MdlDiskonPembelianSupplier();

        $this->db->trans_start();

        if (!empty($arrData)) {
            foreach ($arrData as $sup_id => $data) {

                /* -------------------------------------------------
                 * nyimpen settingan diskon per supplier
                 * -------------------------------------------------*/
                foreach ($data as $diskon => $jenis_nilai) {
                    $dps->addFilter("supplier_id='$sup_id'");
                    $dps->addFilter("per_supplier_diskon_nama='$diskon'");
                    $dpsTmp = $dps->lookupAll()->result();
                    if (!empty($dpsTmp)) {
                        //update
                        $data_new = array(
                            "oleh_id" => $this->session->login["id"],
                        );
                        foreach ($jenis_nilai as $jn => $valNilai) {
                            $data_new[$jn] = $valNilai;
                        }
                        $where = array(
                            "id" => $dpsTmp[0]->id
                        );
                        $dps->updateData($where, $data_new);
                        // showLast_query("merah");
                    }
                    else {
                        //insert
                        $data_new = array(
                            "per_supplier_diskon_id" => $arrDiskons[$diskon],
                            "per_supplier_diskon_nama" => $diskon,
                            "supplier_id" => $sup_id,
                            "status" => 1,
                            "trash" => 0,
                            "oleh_id" => $this->session->login["id"],
                        );
                        foreach ($jenis_nilai as $jn => $valNilai) {
                            $data_new[$jn] = $valNilai;
                        }
                        $dps->addData($data_new);
                        // showLast_query("merah");
                    }
                }

                // matiHere(__LINE__);
                //generate to allProduk Relasi

                /* -------------------------------------------------
                 * memasukan setting per produk yg terelasi dg supplier
                 * -------------------------------------------------*/
                $this->load->model("Mdls/MdlProdukPerSupplier");
                $pps = new MdlProdukPerSupplier();
                if ($sup_id) {
                    $condites = array(
                        "suppliers_id" => $sup_id,
                    );
                    $this->db->where($condites);
                }
                $src_pps_0 = $pps->lookupAll()->result();
                foreach ($src_pps_0 as $src_pp) {
                    $suppliers_id = $src_pp->suppliers_id;
                    $produk_id = $src_pp->produk_id;
                    $src_pps[$suppliers_id][$produk_id] = (array)$src_pp;
                }
                // ---------------------------------------------------------------------
                $this->load->model("Mdls/MdlDiskonPembelian");
                $dp_ = new MdlDiskonPembelian();

                if (!empty($src_pps[$sup_id])) {
                    cekHitam(count($src_pps[$sup_id]));
                    foreach ($src_pps[$sup_id] as $pid => $pdata) {
                        foreach ($arrData[$sup_id] as $disc => $arVal) {

                            // arrPrint($arVal);
                            $dp_->setFilters(array());
                            $dp_->addFilter("per_supplier_diskon_nama='$disc'");
                            $dp_->addFilter("produk_id='$pid'");
                            $dp_->addFilter("supplier_id='$sup_id'");
                            $dp_Tmp = $dp_->lookupAll()->result();
                            showLast_query("orange");

                            if (!empty($dp_Tmp)) {
                                //udh ada update
                                $data_new = array(
                                    "oleh_id" => $this->session->login["id"],
                                    "nilai" => $arVal["nilai"],
                                    "persen" => $arVal["persen"],
                                );
                                $where = array(
                                    "id" => $dp_Tmp[0]->id
                                );
                                $dp_->updateData($where, $data_new);
                                showLast_query("hijau");
                            }
                            else {
                                //blm ada insert
                                $data_new = array(
                                    "per_supplier_diskon_id" => $arrDiskons[$disc],
                                    "per_supplier_diskon_nama" => $disc,
                                    "supplier_id" => $sup_id,
                                    "status" => 1,
                                    "trash" => 0,
                                    "oleh_id" => $this->session->login["id"],
                                    "produk_id" => $pid,
                                    "nilai" => $arVal["nilai"],
                                    "persen" => $arVal["persen"],
                                );
                                $dp_->addData($data_new);
                                showLast_query("here");
                            }
                        }

                    }
                }
            }
        }

        $arrDiskonSupplier = array();

        // matiHere("belum commit @" . __LINE__);
        $berhasil = $this->db->trans_complete();

        $result = array(
            "status" => $berhasil,
            "data" => $arrData,
            "src_pps" => $src_pps,
        );

        //        echo $this->db->last_query();
        echo json_encode($result);

    }

    public function saveProdukKelompok(){

        $dataValue = $_POST['produk_ids'];
        $qty = $_POST['qty'];
        $rabate = $_POST['rabate'];
        $supp_id = $_POST['supp_id'];
        $jenis = $_POST['jenis'];
        $kel_id = $_POST['kel_id'];

        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $dgg = new MdlDiskonPembelianSupplier();

        $result = array();
        if(!empty($dataValue)){
            foreach($dataValue as $pid){
                $conditesgg = array(
                    "supplier_id" => $supp_id,
                    "jenis" => $jenis,
                    "produk_id" => $pid,
                );
                $dgg->setTokoId(my_toko_id());
                $src_dgg_obj_0 = $dgg->lookupByCondition($conditesgg)->result();
                if(empty($src_dgg_obj_0)){
                    $data_new = array(
                        "produk_id"   => $pid,
                        "supplier_id" => $supp_id,
                        "kelompok_id" => $kel_id,
                        "persen"      => $rabate,
                        "maxim"       => $qty,
                        "jenis"       => $jenis,
                        "oleh_id"     => $this->session->login["id"],
                    );
                    $dgg->addData($data_new);
                    $result[] = $this->db->last_query();
                }
                else{
                    $data_new = array(
                        "produk_id"   => $pid,
                        "supplier_id" => $supp_id,
                        "kelompok_id" => $kel_id,
                        "persen"      => $rabate,
                        "maxim"       => $qty,
                        "jenis"       => $jenis,
                        "oleh_id"     => $this->session->login["id"],
                    );
                    $where = array(
                        "id" => $src_dgg_obj_0[0]->id
                    );
                    $dgg->updateData($where, $data_new);
                    $result[] = $this->db->last_query();
                }
            }
        }
        echo json_encode($result);
    }
}
