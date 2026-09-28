<?php

class Produksi extends MX_Controller
{
    protected $produk_id;

    public function getProdukId()
    {
        return $this->produk_id;
    }

    public function setProdukId($produk_id)
    {
        $this->produk_id = $produk_id;
    }


    public function __construct()
    {
        $this->modul_path = base_url() . "produksi/";
        $this->default_limit = 200;
        // $this->jenisTr = "582";
        $this->jenisTr_penjualan = "582spd";
        $this->jenisTrs = array("582spd", "982", "382spd");
    }

    public function cekBom_v1()
    {
        $src = $this->Efisiensi();
        // arrPrintKuning($src);

        $src_produks = $this->ProdukRakitan();
        // arrPrintKuning($src_produks);
        $src_komposisi = $this->ProdukKomposisi();
        $komposisi_data = $src_komposisi["data"];
        $komposisi_summ = $src_komposisi["summary"];
        // arrPrintKuning($src_komposisi);

        $src_komposisi_fase = $this->ProdukKomposisiFase();
        $fase_jml = $src_komposisi_fase["fase_jml"];
        $fase_boms = $src_komposisi_fase["summary"];
        // arrPrintPink($src_komposisi_fase["fase_jml"]);
        arrPrintPink($src_komposisi_fase["summary"]);

        $src_biaya = $this->ProdukPreBiaya();
        // showLast_query("merah");
        // arrPrintPink($src_biaya);

        // arrPrintKuning($komposisi_summ);
        $data_produks = array();
        foreach ($src_produks as $prod_id => $src_produk) {
            $data_komposisi_sum = isset($komposisi_summ[$prod_id]) ? $komposisi_summ[$prod_id] : array();

            $data_produks[$prod_id] = (object)($data_komposisi_sum + (array)$src_produk);
        }
        // arrPrintWebs($data_produks);

        $arrHeaders = array(
            // "nama"   => array(
            //     "label"      => "produk",
            //     // "rowspan"    => "2",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            //     "topth"      => true,
            //     // "top_parent" => "bom",
            // ),
            "produk" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),
            "biaya" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),

            "cek" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            ),
            "biaya_1" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            )
        );
        asort($fase_jml);
        $judul_lap = "jdl";
        $data = array(
            "mode" => "cekBomManual",
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title" => "Laporan produksi " . $judul_lap . " <small><br>(regular, export, project)</small>",
            "subTitle" => "Raw data pembelian",
            "modul_path" => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "data_id" => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout" => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            "tbl_id" => "produksi",
            "produk_bom_fase" => $fase_boms,
            "produks" => $data_produks,
            "produk_fase" => end($fase_jml),
            "produk_biaya" => $src_biaya,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            "loader_div" => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("produksi", $data);
    }

    public function cekBom_ori()
    {
        // arrPrintKuning(url_segment());
        $produk_id = url_segment(4);
        $this->setProdukId($produk_id);

        // cekHijau($produk_id);
        $src = $this->Efisiensi();
        // arrPrintKuning($src);

        $src_produks = $this->ProdukRakitan();
        $produk_nama = $src_produks[$produk_id]->nama;
        // arrPrintKuning($src_produks);
        $src_komposisi = $this->ProdukKomposisi();
        // showLast_query("orange");
        $komposisi_data = $src_komposisi["data"][$produk_id];
        $komposisi_summ = $src_komposisi["summary"][$produk_id];

        /*----termasuk data dari hasil produksi masing2 fase-*/
        $produk_boms = array_merge($komposisi_data["produk"], $komposisi_data["biaya"]);
        foreach ($produk_boms as $produk_bom) {
            $sub_total = $produk_bom->jml * $produk_bom->nilai;
            $produk_bom_total["sub_total"] = $sub_total;

            $gabungan_data = (array)$produk_bom + $produk_bom_total;

            $produk_bom_data[] = (object)$gabungan_data;

        }
        // arrPrintKuning($komposisi_data);

        $src_komposisi_fase = $this->ProdukKomposisiFase();
        $fase_jml = $src_komposisi_fase["fase_jml"];
        // $fase_boms = $src_komposisi_fase["summary"];
        $fase_boms = $src_komposisi_fase["data"];
        // arrPrintPink($src_komposisi_fase["fase_jml"]);
        // arrPrintPink($src_komposisi_fase["summary"]);

        $src_biaya = $this->ProdukPreBiaya();
        // showLast_query("merah");
        // arrPrintPink($src_biaya);

        // arrPrintKuning($komposisi_summ);
        $data_produks = array();
        foreach ($src_produks as $prod_id => $src_produk) {
            $data_komposisi_sum = isset($komposisi_summ[$prod_id]) ? $komposisi_summ[$prod_id] : array();

            $data_produks[$prod_id] = (object)($data_komposisi_sum + (array)$src_produk);
        }
        // arrPrintWebs($data_produks);

        $arrHeaders = array(
            // "nama"   => array(
            //     "label"      => "produk",
            //     // "rowspan"    => "2",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            //     "topth"      => true,
            //     // "top_parent" => "bom",
            // ),
            "produk" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),
            "biaya" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),

            "cek" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            ),
            "biaya_1" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            )
        );
        asort($fase_jml);
        $judul_lap = "$produk_nama";
        $data = array(
            "mode" => "cekBomManual",
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title" => "Laporan produksi " . $judul_lap,
            "subTitle" => "Raw data pembelian",
            "modul_path" => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "data_id" => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout" => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            "tbl_id" => "produksi",
            "produk_bom" => $produk_bom_data,
            "produk_bom_fase" => $fase_boms,
            "produks" => $data_produks,
            "produk_fase" => end($fase_jml),
            "produk_biaya" => $src_biaya,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            "loader_div" => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("produksi", $data);
    }

    /*----------laporan dengan image excell----------*/
    public function cekBom_v3()
    {
        // arrPrintKuning(url_segment());
        $produk_id = url_segment(4);
        $this->setProdukId($produk_id);

        // cekHijau($produk_id);
        $src = $this->Efisiensi();
        // arrPrintKuning($src);

        $src_produks = $this->ProdukRakitan();
        $produk_nama = $src_produks[$produk_id]->nama;
        // arrPrintKuning($src_produks);
        $src_komposisi = $this->ProdukKomposisi();
        // showLast_query("orange");
        $komposisi_data = $src_komposisi["data"][$produk_id];
        $komposisi_summ = $src_komposisi["summary"][$produk_id];
        // arrPrintKuning($komposisi_data);

        $src_komposisi_fase = $this->ProdukKomposisiFase();
        $fase_jml = $src_komposisi_fase["fase_jml"];
        // $fase_boms = $src_komposisi_fase["summary"];
        $fase_boms = $src_komposisi_fase["data"];
        // arrPrintPink($src_komposisi_fase["fase_jml"]);
        // arrPrintPink($src_komposisi_fase["summary"]);

        $src_biaya = $this->ProdukPreBiaya();
        // showLast_query("merah");
        // arrPrintPink($src_biaya);

        // arrPrintKuning($komposisi_summ);
        $data_produks = array();
        foreach ($src_produks as $prod_id => $src_produk) {
            $data_komposisi_sum = isset($komposisi_summ[$prod_id]) ? $komposisi_summ[$prod_id] : array();

            $data_produks[$prod_id] = (object)($data_komposisi_sum + (array)$src_produk);
        }
        // arrPrintWebs($data_produks);

        $arrHeaders = array(
            // "nama"   => array(
            //     "label"      => "produk",
            //     // "rowspan"    => "2",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            //     "topth"      => true,
            //     // "top_parent" => "bom",
            // ),
            "produk" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),
            "biaya" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),

            "cek" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            ),
            "biaya_1" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            )
        );
        asort($fase_jml);
        $judul_lap = "$produk_nama";
        $data = array(
            "mode" => "cekBomV3",
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title" => "Laporan produksi " . $judul_lap,
            "subTitle" => "Raw data pembelian",
            "modul_path" => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "data_id" => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout" => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            "tbl_id" => "produksi",
            "produk_bom" => array_merge($komposisi_data["produk"], $komposisi_data["biaya"]),
            "produk_bom_fase" => $fase_boms,
            "produks" => $data_produks,
            "produk_fase" => end($fase_jml),
            "produk_biaya" => $src_biaya,
            "date_min" => 1,
            "date_max" => dtimeNow('Y-m-d'),
            "loader_div" => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("produksi", $data);
    }

    public function Efisiensi()
    {
        $tbl = "__rek_pembantu_efisiensi__3020010";

        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);

        return $src;
    }

    public function SubEfisiensi()
    {
        $tbl = "__rek_pembantu_subefisiensi__3020010";

        $src = $this->db->get("$tbl");
        showLast_query("kuning");
        arrPrintKuning($src);
    }

    public function ProdukRakitan()
    {
        $tbl = "produk";
        $condites = array(
            "jenis" => "item_rakitan",
            "status" => "1",
            "trash" => "0",
            "id" => $this->produk_id,
        );
        $this->db->where($condites);
        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);
        $src_0 = array();
        foreach ($src as $item) {
            $src_0[$item->id] = $item;
        }

        return $src_0;
    }

    public function ProdukKomposisi__()
    {
        $tbl = "produk_komposisi";

        $condites = array(
            // "jenis"     => "item_rakitan",
            "status" => "1",
            "trash" => "0",
            "produk_id" => $this->produk_id,
        );
        $this->db->where($condites);
        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);
        $src_0 = array();
        foreach ($src as $item) {
            $jenis = $item->jenis;
            $src_0[$item->produk_id][$jenis][] = $item;
        }
        // arrPrintWebs($src_0);
        $anggaran = array();
        foreach ($src_0 as $prod_id => $item_0) {
            foreach ($item_0 as $jenis => $item_00) {
                // arrPrintPink($item_00);
                foreach ($item_00 as $item) {

                    $sub_nilai = $item->jml * $item->nilai;
                    if (!isset($anggaran[$prod_id][$jenis])) {
                        $anggaran[$prod_id][$jenis] = 0;
                    }
                    $anggaran[$prod_id][$jenis] += $sub_nilai;
                }
            }
        }

        // arrPrintHijau($anggaran);
        $vars = array();
        $vars["data"] = $src_0;
        $vars["summary"] = $anggaran;

        return $vars;
    }

    public function ProdukKomposisi()
    {
        $tbl = "produk_komposisi_fase";
        $this->load->model("Mdls/MdlProdukKomposisiFase");
        $mf = new MdlProdukKomposisiFase();
        $condites = array(

            "status" => "1",
            "trash" => "0",
            "produk_id" => $this->produk_id,
        );
        $mf->setFilters(array());
        $mf->addFilter("produk_id='" . $this->produk_id . "'");
        $mf->addFilter("jenis<>'target'");
        $mf->addFilter("status='1'");
        $mf->addFilter("trash='0'");

        // $this->db->where($condites);
        // $src = $this->db->get("$tbl")->result();
        $src = $mf->lookUpAll()->result();
        // showLast_query("kuning");
        // arrPrint($src);
        // matiHEre(__LINE__);
        // arrPrintKuning($src);
        $src_sub = array();
        $masterProdukKomposisi = array();
        foreach ($src as $item) {
            $jenis = $item->jenis;
            if (!isset($item->subtotal)) {
                $item->subtotal = $item->jml * $item->nilai;
            }
            $masterProdukKomposisi[$item->produk_id][$jenis][$item->produk_dasar_id] = $item;
            $src_sub[$item->produk_id][$jenis][$item->produk_dasar_id][] = array(
                "jml" => $item->jml,
                "subtotal" => $item->subtotal);

        }
        // arrPrint($src_sub);
        $src_0 = array();
        foreach ($masterProdukKomposisi as $produk_id => $temp_0) {

            foreach ($temp_0 as $jenis => $data_00) {
                foreach ($data_00 as $produk_dasrID => $tempData_000) {
                    if (isset($src_sub[$produk_id][$jenis][$produk_dasrID])) {

                        $jml_0 = 0;
                        $subtotal_0 = 0;
                        foreach ($src_sub[$produk_id][$jenis][$produk_dasrID] as $tempBilai) {
                            // arrPrint($tempBilai);
                            $jml_0 += $tempBilai["jml"];
                            $subtotal_0 += $tempBilai["subtotal"];
                            // cekMErah($tempBilai["subtotal"]);
                        }
                        $tempData_000->jml = $jml_0;
                        $tempData_000->subtotal = $subtotal_0;
                        $tempData_000->nilai = $subtotal_0 / $jml_0;
                        $tempData_000->harga = $subtotal_0 / $jml_0;
                        // if($jenis=="biaya"){
                        //     matiHere($jenis. "=".$jml_0." ||  ".$subtotal_0);
                        // }

                    }

                    $src_0[$produk_id][$jenis][] = $tempData_000;
                }
                // if($jenis=="biaya"){
                //     matiHere();
                // }
            }
        }
        // arrPrint($src_0);
        // arrPrintWebs($masterProdukKomposisi);
        // matiHere();
        $anggaran = array();
        foreach ($src_0 as $prod_id => $item_0) {
            foreach ($item_0 as $jenis => $item_00) {
                // arrPrintPink($item_00);
                foreach ($item_00 as $item) {
                    $sub_nilai = $item->jml * $item->nilai;
                    if (!isset($anggaran[$prod_id][$jenis])) {
                        $anggaran[$prod_id][$jenis] = 0;
                    }
                    $anggaran[$prod_id][$jenis] += $sub_nilai;
                }
            }
        }
        // matiHere();
        // arrPrintHijau($anggaran);
        $vars = array();
        $vars["data"] = $src_0;
        $vars["summary"] = $anggaran;

        return $vars;
    }

    public function ProdukKomposisiFase()
    {
        $tbl = "produk_komposisi_fase";

        $condites = array(
            // "jenis"  => "item_rakitan",
            // "status" => "1",
            // "trash"  => "0",
            "jenis !=" => "target",
            "produk_id" => $this->produk_id,
        );
        $this->db->where($condites);

        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);
        $src_0 = array();
        foreach ($src as $item) {
            $src_0[$item->produk_id][$item->fase_id][$item->jenis][$item->produk_dasar_id] = $item;
        }

        $produk_fase_jml = array();
        foreach ($src_0 as $prod_id => $item_0) {
            // arrPrint($item_0);
            $produk_fase_jml[$prod_id] = sizeof($item_0);
            foreach ($item_0 as $fase => $item_00) {

                foreach ($item_00 as $jenis => $item_000) {
                    // arrPrintHijau($item_000);
                    foreach ($item_000 as $item) {

                        if ($jenis == "produk") {
                            $sub_nilai = $item->jml * $item->nilai;

                            // $src_00[$item->produk_id][$item->fase_id][$item->jenis][$item->produk_dasar_id]["sub_total"] = $sub_nilai;

                            if (!isset($anggaran[$prod_id][$fase][$jenis])) {
                                $anggaran[$prod_id][$fase][$jenis] = 0;
                            }
                            $anggaran[$prod_id][$fase][$jenis] += $sub_nilai;
                        }
                        else {

                            $anggaran[$prod_id][$fase][$jenis][$item->produk_dasar_id] = $sub_nilai;
                        }
                    }
                }
            }
        }

        // arrPrintHijau($anggaran);
        // arrPrintHijau($src_0);
        $vars = array();
        $vars["data"] = $src_0;
        $vars["summary"] = $anggaran;
        $vars["fase_jml"] = $produk_fase_jml;

        return $vars;
    }

    public function ProdukPreBiaya()
    {
        $tbl = "produk_pre_biaya";

        $condites = array(
            // "jenis"  => "item_rakitan",
            "status" => "1",
            "trash" => "0",
            // "jenis !=" => "target",
        );
        $this->db->where($condites);
        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);
        $src_0 = array();
        foreach ($src as $item) {
            $src_0[$item->id] = $item;
        }
        //
        // $produk_fase_jml = array();
        // foreach ($src_0 as $prod_id => $item_0) {
        //     // arrPrint($item_0);
        //     $produk_fase_jml[$prod_id] = sizeof($item_0);
        //     foreach ($item_0 as $fase => $item_00) {
        //
        //         foreach ($item_00 as $jenis => $item_000) {
        //             // arrPrintPink($item_00);
        //             foreach ($item_000 as $item) {
        //
        //                 $sub_nilai = $item->jml * $item->nilai;
        //                 if (!isset($anggaran[$prod_id][$fase][$jenis])) {
        //                     $anggaran[$prod_id][$fase][$jenis] = 0;
        //                 }
        //                 $anggaran[$prod_id][$fase][$jenis] += $sub_nilai;
        //             }
        //         }
        //     }
        // }
        //
        // // arrPrintHijau($anggaran);
        // $vars = array();
        // $vars["data"] = $src_0;
        // $vars["summary"] = $anggaran;
        // $vars["fase_jml"] = $produk_fase_jml;

        return $src_0;
    }

    public function SuppliesStok()
    {
        $tbl = "__rek_pembantu_supplies__1010030010";

        $condites = array(
            // "jenis"  => "item_rakitan",
            // "status" => "1",
            "gudang_id" => "-250",
            // "jenis !=" => "target",
        );
        $this->db->where($condites);
        $selector_kolom = array(
            "extern_id",
            "sum(debet) as 'sub_total'",
            "avg(harga) as 'nilai'",
            "sum(qty_debet) as 'jml'",
            // "sum((jml * nilai)) as sub_total",
        );
        $this->db->select($selector_kolom);
        $this->db->group_by("extern_id");
        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);
        $src_0 = array();
        foreach ($src as $item) {
            $src_0[$item->extern_id] = $item;
        }
        // arrPrintKuning($src_0);
        //
        // $produk_fase_jml = array();
        // foreach ($src_0 as $prod_id => $item_0) {
        //     // arrPrint($item_0);
        //     $produk_fase_jml[$prod_id] = sizeof($item_0);
        //     foreach ($item_0 as $fase => $item_00) {
        //
        //         foreach ($item_00 as $jenis => $item_000) {
        //             // arrPrintPink($item_00);
        //             foreach ($item_000 as $item) {
        //
        //                 $sub_nilai = $item->jml * $item->nilai;
        //                 if (!isset($anggaran[$prod_id][$fase][$jenis])) {
        //                     $anggaran[$prod_id][$fase][$jenis] = 0;
        //                 }
        //                 $anggaran[$prod_id][$fase][$jenis] += $sub_nilai;
        //             }
        //         }
        //     }
        // }
        //
        // // arrPrintHijau($anggaran);
        // $vars = array();
        // $vars["data"] = $src_0;
        // $vars["summary"] = $anggaran;
        // $vars["fase_jml"] = $produk_fase_jml;

        return $src_0;
    }

    public function SuppliesStokOut()
    {
        $tbl = "__rek_pembantu_supplies__1010030010";

        $condites = array(
            // "jenis"  => "item_rakitan",
            // "status" => "1",
            "cabang_id" => "25",
            "gudang_id" => "-250",
            // "jenis !=" => "target",
        );
        $this->db->where($condites);
        $selector_kolom = array(
            "extern_id",
            "avg(harga) as 'nilai'",
            "sum(kredit) as 'sub_total'",
            "sum(qty_kredit) as 'jml'",
            // "(nilai * jml) as 'xsub_total'",
        );
        $this->db->select($selector_kolom);
        $this->db->group_by("extern_id");
        $src = $this->db->get("$tbl")->result();
        // showLast_query("kuning");
        // arrPrintKuning($src);
        $src_0 = array();
        foreach ($src as $item) {
            $src_0[$item->extern_id] = $item;
        }

        // arrPrintPink($src_0);
        // arrPrint($src_0);
        // matiHere();
        //
        // $produk_fase_jml = array();
        // foreach ($src_0 as $prod_id => $item_0) {
        //     // arrPrint($item_0);
        //     $produk_fase_jml[$prod_id] = sizeof($item_0);
        //     foreach ($item_0 as $fase => $item_00) {
        //
        //         foreach ($item_00 as $jenis => $item_000) {
        //             // arrPrintPink($item_00);
        //             foreach ($item_000 as $item) {
        //
        //                 $sub_nilai = $item->jml * $item->nilai;
        //                 if (!isset($anggaran[$prod_id][$fase][$jenis])) {
        //                     $anggaran[$prod_id][$fase][$jenis] = 0;
        //                 }
        //                 $anggaran[$prod_id][$fase][$jenis] += $sub_nilai;
        //             }
        //         }
        //     }
        // }
        //
        // // arrPrintHijau($anggaran);
        // $vars = array();
        // $vars["data"] = $src_0;
        // $vars["summary"] = $anggaran;
        // $vars["fase_jml"] = $produk_fase_jml;

        return $src_0;
    }

    public function currentStokSupplies()
    {
        $this->load->model("Coms/ComRekeningPembantuSupplies");
        $cc = new ComRekeningPembantuSupplies();
        $cc->addFilter("cabang_id='21'");
        $tempStok = $cc->fetchBalances("1010030010");
        $data = array();
        if (sizeof($tempStok) > 0) {
            foreach ($tempStok as $tmp) {
                $data[$tmp->extern_id] = array(
                    "id" => $tmp->extern_id,
                    "produk_id" => $tmp->extern_id,
                    "nama" => $tmp->extern_nama,
                    "produk_nama" => $tmp->extern_nama,
                    "jml" => $tmp->qty_debet,
                    "harga" => $tmp->harga,
                    "subtotal" => $tmp->debet,
                );
            }
        }
        // arrPrint($data);
        $tempFase = $this->ProdukKomposisiFase();
        $curentBomFase = $tempFase["data"]["1"];
        $wip = array();
        foreach ($curentBomFase as $faseID => $data_0) {
            if (isset($data_0["produk"])) {
                foreach ($data_0["produk"] as $pidData) {
                    $bID = $pidData->produk_dasar_id;
                    // cekMErah($bID);
                    if (isset($data[$bID])) {
                        // $wip["1"][$faseID][$bID]["nama"]=$pidData->produk_dasar_nama;
                        // $wip["1"][$faseID][$bID]["jml"]=$data[$bID]["jml"];
                        // $wip["1"][$faseID][$bID]["nilai"]=$data[$bID]["harga"];
                        // $wip["1"][$faseID][$bID]["sub_total"]=$data[$bID]["subtotal"];

                        $wip["1"][$faseID][$bID] = (object)array(
                            "nama" => $pidData->produk_dasar_nama,
                            "jml" => $data[$bID]["jml"],
                            "nilai" => $data[$bID]["harga"],
                            "sub_total" => $data[$bID]["subtotal"],
                        );
                        // $wip["1"][$faseID]["produk"][$bID]["jml"]=$data[$bID];
                    }
                }
            }
        }
        // arrPrint($wip);
        // matiHEre();
        return $wip;
        // ceklIme($this->db->last_query());
        // arrPrint($tempStok);
    }

    public function biayaFase()
    {
        $tempFase = $this->ProdukKomposisiFase();
        // ceklime($this->db->last_query());
        $curentBomFase = $tempFase["data"]["1"];


        $this->load->model("Mdls/MdlProdukKomposisiFase");
        $ix = new MdlProdukKomposisiFase();
        $ix->addFilter("jenis='target'");
        $tempTarget = $ix->lookUpAll()->result();
        $listHasil = array();
        if (sizeof($tempTarget) > 0) {
            foreach ($tempTarget as $tempTarget_0) {
                $ids[] = $tempTarget_0->produk_dasar_id;
                $listHasil[$tempTarget_0->fase_id] = $tempTarget_0->produk_dasar_id;
            }
        }
        $this->load->model("Coms/ComRekeningPembantuSupplies");
        $cc = new ComRekeningPembantuSupplies();
        $cc->addFilter("cabang_id='21'");
        $cc->addFilter("extern_id in (" . implode(",", $ids) . ")");

        $tempDataMutasi = $this->SuppliesStok();

        // cekMErah($this->db->last_query());
        // arrprint($tempStok);
        // foreach($tempDataMutasi as $tempDataMutasi_0){
        $qty_hasil = array();
        foreach ($ids as $idxx) {
            if (isset($tempDataMutasi[$idxx])) {
                $qty_hasil[$idxx] = $tempDataMutasi[$idxx]->jml;
            }
        }
        // }
        // arrPrint($qty_hasil);
        // matiHere();
        // $qty_hasil = array();
        // foreach($tempStok as $tempStok_0){
        //     // arrprint($tempStok_0);
        //     $qty_hasil[$tempStok_0->extern_id]=$tempStok_0->qty_debet;
        // }
        // cekMerah($this->db->last_query());
        // arrPrintWebs($qty_hasil);
        $wip = array();
        foreach ($curentBomFase as $faseID => $data_0) {
            if (isset($data_0["biaya"])) {
                foreach ($data_0["biaya"] as $pidData) {
                    $bID = $pidData->produk_dasar_id;
                    // cekMErah($bID);
                    if (isset($listHasil[$faseID])) {
                        $trgID = $listHasil[$faseID];
                        // $wip["1"][$faseID][$bID]["nama"]=$pidData->produk_dasar_nama;
                        // $wip["1"][$faseID][$bID]["jml"]=$data[$bID]["jml"];
                        // $wip["1"][$faseID][$bID]["nilai"]=$data[$bID]["harga"];
                        // $wip["1"][$faseID][$bID]["sub_total"]=$data[$bID]["subtotal"];

                        $wip["1"][$faseID][$bID] = (object)array(
                            "nama" => $pidData->produk_dasar_nama,
                            "jml" => $qty_hasil[$trgID],
                            "nilai" => $pidData->harga,
                            "sub_total" => isset($qty_hasil[$trgID]) ? $qty_hasil[$trgID] * $pidData->harga : 0,
                        );
                        // $wip["1"][$faseID]["produk"][$bID]["jml"]=$data[$bID];
                    }
                }
            }

        }
        // arrprint($wip);
        // matiHEre();
        return $wip;
        // cekmerah();
    }

    public function hasilProduksi()
    {

    }


    public function cekBom()
    {
        // arrPrintKuning(url_segment());
        $produk_id = url_segment(4);
        $this->setProdukId($produk_id);

        // cekHijau($produk_id);
        $src = $this->Efisiensi();
        // arrPrintKuning($src);

        $src_produks = $this->ProdukRakitan();
        // cekLime($this->db->last_query());
        $produk_nama = $src_produks[$produk_id]->nama;
        // arrPrintKuning($src_produks);
        $src_komposisi = $this->ProdukKomposisi();
        // arrPrint($src_komposisi);
        // showLast_query("orange");
        // matiHere();
        $komposisi_data = $src_komposisi["data"][$produk_id];
        $komposisi_summ = $src_komposisi["summary"][$produk_id];

        /*----termasuk data dari hasil produksi masing2 fase-*/
        $produk_boms = array_merge($komposisi_data["biaya"], $komposisi_data["produk"]);
        foreach ($produk_boms as $produk_bom) {
            $sub_total = $produk_bom->jml * $produk_bom->nilai;
            $produk_bom_total["sub_total"] = $sub_total;

            $gabungan_data = (array)$produk_bom + $produk_bom_total;

            $produk_bom_data[] = (object)$gabungan_data;

        }
        foreach ($komposisi_data["biaya"] as $komposisi_bom) {
            $sub_total = $komposisi_bom->jml * $komposisi_bom->nilai;
            $produk_bom_total["sub_total"] = $sub_total;
            $gabungan_data = (array)$komposisi_bom + $produk_bom_total;
            $komposisi_data_biaya_0[] = (object)$gabungan_data;
        }

        // arrPrint($komposisi_data_biaya_0);
        foreach ($komposisi_data["produk"] as $komposisi_bom) {
            $sub_total = $komposisi_bom->jml * $komposisi_bom->nilai;
            $produk_bom_total["sub_total"] = $sub_total;

            $gabungan_data = (array)$komposisi_bom + $produk_bom_total;

            $komposisi_data_produk_0[] = (object)$gabungan_data;
        }
        // arrPrintKuning($komposisi_data);
        // arrPrintKuning($produk_boms);

        $src_komposisi_fase = $this->ProdukKomposisiFase();

        ceklime($this->db->last_query());
        $fase_jml = $src_komposisi_fase["fase_jml"];
        // $fase_boms = $src_komposisi_fase["summary"];
        $fase_boms = $src_komposisi_fase["data"];
        // arrPrint($src_komposisi_fase);
        // arrPrintWebs($fase_boms);
        // foreach ($fase_boms as $fase_bom) {
        //     // arrPrint($fase_bom);
        //     $fjml = $fase_bom->jml;
        //     $fnilai = $fase_bom->nilai;
        //     $fsubtotal= $fjml * $fnilai;
        //
        //
        // }
        // arrPrintPink($src_komposisi_fase["fase_jml"]);
        // arrPrintPink($src_komposisi_fase["summary"]);

        //region builder komposisi utama dari produk per fase
        // arrPrintPink($src_komposisi_fase);
        // matiHEre();


        //endregion

        $src_biaya = $this->ProdukPreBiaya();
        // showLast_query("merah");
        // arrPrintPink($src_biaya);

        $src_supplies = $this->SuppliesStok();
        showLast_query("biru");
        // arrPrintWebs($src_supplies);

        $src_supplies_out = $this->SuppliesStokOut();
        showLast_query("biru");
        // arrPrintWebs($src_supplies);

        $curentStopWp = $this->currentStokSupplies();
        cekLime($this->db->last_query());
        // arrPrintKuning($curentStopWp);
        $curentBiaya = $this->biayaFase();
        // arrprint($curentBiaya);
        $data_produks = array();
        foreach ($src_produks as $prod_id => $src_produk) {
            $data_komposisi_sum = isset($komposisi_summ[$prod_id]) ? $komposisi_summ[$prod_id] : array();

            $data_produks[$prod_id] = (object)($data_komposisi_sum + (array)$src_produk);
        }
        // arrPrintWebs($data_produks);

        $arrHeaders = array(
            // "nama"   => array(
            //     "label"      => "produk",
            //     // "rowspan"    => "2",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "nomer",
            //     "topth"      => true,
            //     // "top_parent" => "bom",
            // ),
            "produk" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),
            "biaya" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "bom",
            ),

            "cek" => array(
                "label" => "bahan baku",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            ),
            "biaya_1" => array(
                "label" => "biaya",
                "format" => "formatField_he_format",
                "format_key" => "harga",
                "attr" => "class='text-right bg-warning'",
                "attr_head" => "class='text-right'",
                "summary" => true,
                "top_parent" => "fase 1",
            )
        );
        asort($fase_jml);
        $judul_lap = "$produk_nama";
        $data = array(
            // "mode"             => "cekBomManual",
            "mode" => "cekBomManualx",
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title" => "Laporan produksi " . $judul_lap,
            "subTitle" => "Raw data pembelian",
            "modul_path" => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr" => "582",
            "data_id" => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout" => isset($_GET['layout']) ? $_GET['layout'] : true,
            // "master_data" => $masterData,
            "arrHeaders" => $arrHeaders,
            // // navigasi
            // "url"         => base_url(uri_string()) . "$strGet",
            // "strGet"      => $strGet,
            // "date1"       => $date1,
            // "date2"       => $date2,
            "tbl_id" => "produksi",
            "produk_nama" => $produk_nama,
            "produk_bom" => $produk_bom_data,
            "produk_bom_biaya" => $komposisi_data_biaya_0,
            "produk_bom_bahan" => $komposisi_data_produk_0,
            "produk_bom_fase" => $fase_boms,
            "produks" => $data_produks,
            "produk_fase" => end($fase_jml),
            "produk_biaya" => $src_biaya,
            "produk_masuk" => $src_supplies,
            "produk_keluar" => $src_supplies_out,
            "date_min" => 1,
            "wip" => $curentStopWp,
            "biaya_fase" => $curentBiaya,
            "date_max" => dtimeNow('Y-m-d'),
            "loader_div" => "sum_satu",
            // "sum_satu"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/produkoutstandingblnproduk" . "$strGet",
            // "sum_tiga"     => base_url() . "laporan/Crm/produkoutstandingblntransaksi" . "$strGet",
        );
        $this->load->view("produksi", $data);
    }
}