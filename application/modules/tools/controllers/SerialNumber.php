<?php

/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class SerialNumber extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }


    public function ReaktivasiDpembelian()
    {
        $this->load->model("Mdls/MdlDiskonPembelian");
        $mo = new MdlDiskonPembelian();
        $kolom_target_id = "outdoor_id";
        $kolom_target = "outdoor_barcode";

        // $this->load->model("Mdls/MdlModelIndoor_1");
        // $mo = new MdlModelIndoor_1();
        // $kolom_target_id = "indoor_id_1";
        // $kolom_target = "indoor_barcode_1";

        $mo->setFilters(array());
        // $this->db->order_by('last_update','desc');
        $this->db->order_by('id desc,last_update desc');
        $mo_src = $mo->lookupAll()->result();
        showLast_query("kuning");
        // arrPrintKuning($mo_src);
        $dt_mo = array();
        foreach ($mo_src as $item) {
            $id = $item->id;
            $per_supplier_diskon_id = $item->per_supplier_diskon_id;
            $produk_id = $item->produk_id;
            $last_update = $item->last_update;

            $speks['id'] = $id;
            $speks['last_update'] = $item->last_update;
            $speks['trash'] = $item->trash;
            $dt_mo[$produk_id][$per_supplier_diskon_id][] = $speks;
        }

        // arrPrintHijau($dt_mo);
        foreach ($dt_mo as $item_1) {
            foreach ($item_1 as $item_2) {
                $xxs = $item_2[0];
                $trash_2 = $xxs['trash'];
                $id_2 = $xxs['id'];

                if ($trash_2 == 1) {

                    arrPrintKuning($xxs);
                    $mo->updateData(array("id" => $id_2, "trash" => 1), array("trash" => 0));
                    showLast_query("pink");
                }

            }
        }


        matiHere(__LINE__);
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $pr_src = $pr->lookupAll()->result();
        $fields = $pr->getFields();

        foreach ($pr_src as $itemParams) {
            // arrPrintPink($itemParams);
            $produk_id = $itemParams->id;
            // $target_id = $outdoor_id = $itemParams->outdoor_id;
            $target_id = $outdoor_id = $itemParams->$kolom_target_id;
            $kolom_target_db = $itemParams->$kolom_target;

            // cekHere("$produk_id $outdoor_id");
            $value_target = $dt_mo[$target_id];
            // if ($target_id > 0 && $kolom_target_db == null) {
            //
            //     $pr->updateData(array("$kolom_target_id" => $target_id, "$kolom_target" => null), array("$kolom_target" => $value_target));
            //     showLast_query("kuning");
            // }
        }


    }

    public function index()
    {
        matiHere("typo");
    }

    public function SyncSerial()
    {
        $tbl_1 = "produk_per_serialnumber";
        $condites = array(
            "trash"        => "0",
            "transaksi_id" => "53691",

        );
        $this->db->where($condites);
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("kuning", count($produkData));

        foreach ($produkData as $produkDatum) {
            // arrPrint($produkDatum);
            $produk_serial_number_2 = $produkDatum['produk_serial_number_2'];
            // cekHere($produk_serial_number_2);

            $snActive[] = $produk_serial_number_2;
        }
        // arrPrint($snActive);

    // --------------------------------------------------------------------------
        $tbl_1 = "_rek_pembantu_produk_perserial_cache";
        $condites = array(
            // "trash"        => "0",
            "transaksi_id" => "53691",

        );
        $this->db->where($condites);
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $produkDatum) {
            // arrPrint($produkDatum);
            $produk_serial_number_2 = $produkDatum['extern_nama'];
            // cekHere($produk_serial_number_2);

            $snActiveCache[] = $produk_serial_number_2;
        }
        // cekHere(count($snActiveCache));
        // arrPrint($snActiveCache);

        $snSampah = array_diff($snActiveCache, $snActive);
        cekHijau("sampah:" . count($snSampah));
        // arrPrintHijau($snSampah);

        $this->db->trans_start();
        $this->db->where_in('extern_nama', $snSampah);
        $this->db->delete($tbl_1);
        showLast_query("pink");


        $tbl_2 = "__rek_pembantu_produk_perserial__1010030030";
        $this->db->where_in('extern_nama', $snSampah);
        $this->db->delete($tbl_2);
        showLast_query("kuning");

        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHitam("<h1>BERHASIL</h1>");

    }
}