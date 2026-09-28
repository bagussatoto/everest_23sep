<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

//include_once "Bs_37.php";


class Bi
{


    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

        $this->konfKolomProduks = array(
            "id",
            "kode",
            "nama",
            "harga_jual",
            "limit",
        );

    }

    public function calc_stok($toko_id, $periode_omset = 60, $periode_buffer = 14, $indek = 100)
    {
        $CI =& get_instance();

        $periode = $jml_hari_penjualan = $periode_omset;
        $buffer_d = $periode_buffer;
        // $indek = 100;
        $indeks = $indek / 100;
        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));

        // cekHijau("$prev_date $stop_date ");

        $CI->load->model("Mdls/MdlReportSql");
        $rp = new MdlReportSql();
        $condites = array(
            "date(dtime)>=" => $prev_date,
            "date(dtime)<=" => $stop_date,
            // "year(dtime)" => "2021",
        );
        $CI->db->where($condites);
        $rp->setTokoId($toko_id);
        $penjus = $rp->callpengunaanProdukAll();
//         showLast_query("lime");
        // cekBiru(sizeof($xx));
        // arrPrint($penjus);
//mati_disini();
        $penjualans = array();
        foreach ($penjus as $spek_penjus) {
            $penjualans[$spek_penjus->subject_id] = $spek_penjus;
        }


        $CI->load->model("Mdls/MdlLockerStock");
        $sd = new MdlLockerStock();

        $stop_date_sd = date('Y-m-d', strtotime($dtimeNow));
        $prev_date_sd = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));

        $condites = array(
            "date(last_dtime)>=" => $prev_date_sd,
            "date(last_dtime)<=" => $stop_date_sd,
        );
        $CI->db->where($condites);
        $sd->setTokoId($toko_id);
        $lockerSold = $sd->lookupAll()->result();
//        $lastDbLockerStock = $this->db->last_query();

        $arrPenggunaanBahanByTime = array();
        if (sizeof($lockerSold) > 0) {
            foreach ($lockerSold as $ky => $dSold) {
                $fulldate = date('Y-m-d', strtotime($dSold->last_dtime));
                $arrPenggunaanBahanByTime[$dSold->produk_id]['nama'] = $dSold->nama;
                $state = $dSold->state;
                if (!isset($arrPenggunaanBahanByTime[$dSold->produk_id][$state][$fulldate])) {
                    $arrPenggunaanBahanByTime[$dSold->produk_id][$state][$fulldate] = 0;
                }
                $arrPenggunaanBahanByTime[$dSold->produk_id][$state][$fulldate] += $dSold->jumlah;
            }
        }

        $CI->load->model("Mdls/MdlBi");
        $bi = new MdlBi();
        $bi->setTokoId($toko_id);
        $tmpStok = $bi->getStokProdukNowAll();
        $realStok = $tmpStok['mains'];
        // showLast_query("here");
        // arrPrint($realStok);

        $CI->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        // if (show_debuger() == 1) {
        //     $this->db->limit(10);
        // }
        $proCondites = array(
            "toko_id" => $toko_id,
            "konversi" => 0,
        );
        $CI->db->where($proCondites);
        $tmpPr = $pr->lookupAll();
        // showLast_query("kuning");
        $produks = $tmpPr->result();
        // arrPrint($produks);

        $hasil = array();
        foreach ($produks as $produk) {
            $produk_id = $produk->id;
            $produk_nama = $produk->nama;
            $stok_limit = $produk->stok_limit != null ? $produk->stok_limit : 0;
            $moq = $produk->moq != null && $produk->moq * 1 > 0 ? $produk->moq : 1;
            $produk_satuan = $produk->satuan;
            $unit_af = isset($penjualans[$produk_id]->unit_af) ? $penjualans[$produk_id]->unit_af : 0;
            $unit_now = isset($realStok[$produk_id]['qty_debet']) ? $realStok[$produk_id]['qty_debet'] : 0;

//            $periode_jual_per_bahan = count($arrPenggunaanBahanByTime[$produk_id]['sold']);
//            $periode = $periode_jual_per_bahan >= $periode ? $periode : $periode_jual_per_bahan;
            /* ----------------------------------------------------------------------------------
             * AI formulas
             * ----------------------------------------------------------------------------------*/
            $avg = $unit_af > 0 ? $unit_af / $periode : 0;
            //BUFFER : daily avg x set hari buffer
            $buffer = $avg * $buffer_d;
            // IDEAL STOCK : {(dayly avg x set hari ideal stock) x index} + buffer
            $ideal = $avg * $buffer_d * $indeks + $buffer;
            // ORDER QTY : ideal stock - available stock
            $order = $ideal - $unit_now;

            // MOQ handler
            if ($moq * 1 > 0 && $order > 0) {
                $orderb4 = $order;
                $sisa_order = fmod($order, $moq);
                $order_ = $order - $sisa_order;
                $order = $order_ + $moq;
            }

            /* ----------------------------------------------------------------------------------
             * Ai output
             * ----------------------------------------------------------------------------------*/
            $speks['id'] = $produk_id;
            $speks['nama'] = $produk_nama;
            $speks['stok_out'] = $unit_af;
            $speks['stok_out_avg'] = $avg;
            $speks['stok_real'] = $unit_now;
            $speks['stok_buffer'] = $buffer;
            $speks['stok_ideal'] = $ideal;
            $speks['stok_order'] = $order;
            $speks['stok_satuan'] = $produk_satuan;
            $speks['stok_limit'] = $stok_limit;
            $speks['moq'] = $moq;

            $hasil[] = $speks;
        }
        // arrPrintPink($hasil);
        $params['periode_omset'] = $periode;
        $params['periode_stok'] = $buffer_d;
        $params['index'] = $indek;

        $result = array();
        $result["params"] = $params;
        $result["row"] = sizeof($hasil);
        $result["datas"] = $hasil;
//        $result["arrPenggunaanBahanByTime"] = $arrPenggunaanBahanByTime;
//        $result["lockerSold"] = $lockerSold;
//        $result["lastDbLockerStock"] = $lastDbLockerStock;

        return $result;
    }

    public function show_produk_harga_hpp($toko_id, $produk_id = "")
    {
        /* ------------------------------
         * mendapatakan data produk
         * ------------------------------*/
        $this->CI->load->model("Mdls/MdlProduk");
        $hp = new MdlProduk();
        $condites = array(
            "toko_id" => $toko_id,
        );
        if (is_array($produk_id)) {
            // $hp_condites = array(
            //     "id" => $produk_id
            // );
            //
            $this->CI->db->where_in('id', $produk_id);
        }
        elseif ($produk_id > 0) {
            $hp_condites = array(
                "id" => $produk_id
            );

            $this->CI->db->where($hp_condites);
        }
        else {
            // kosong
        }
        $koloms = $this->konfKolomProduks;
        $this->CI->db->select($koloms);
        $hrgProdukSrcs = $hp->lookupByCondition($condites)->result();
        // showLast_query("merah");
        // arrPrint($hrgProdukSrcs);
        $hrgProduks = array();
        foreach ($hrgProdukSrcs as $produkSpeks) {
            $hrgProduks[$produkSpeks->id] = $produkSpeks;
        }
        // cekBiru($hrgProduks);

        /* ------------------------------
         * mendapatakan data supplies/bahan baku
         * ------------------------------*/
        $this->CI->load->model("Mdls/MdlProduk");
        $ps = new MdlProduk();
        $ps_koloms = array(
            "id",
            "hpp",
            "kode",
            "nama",
            "satuan",
        );
        $this->CI->db->select($ps_koloms);
        $ps_src = $ps->lookupByCondition($condites)->result();
        // showLast_query("kuning");
        // arrPrintPink($ps_src);
        $psDatas = array();
        foreach ($ps_src as $ps_speks) {
            $psDatas[$ps_speks->id] = $ps_speks;
        }

        /* ------------------------------
         * mendapatakan data produk bahan
         * ------------------------------*/
        $this->CI->load->model("Coms/ComRekeningPembantuProduk");
        $cps = new ComRekeningPembantuProduk();
        $cps_condites = array(
            "toko_id" => $toko_id,
            "periode" => "forever",
        );
        $srcDatas = $cps->lookupByCondition($cps_condites)->result();
        // showLast_query("kuning");
        // arrPrint($srcDatas);
        $psDatas_com = array();
        foreach ($srcDatas as $srcData) {
            $psDatas_com[$srcData->extern_id]['hpp'] = $srcData->harga;
        }

        /* ------------------------------
         * mendapatakan data produk komposisi
         * ------------------------------*/
        $this->CI->load->model("Mdls/MdlProdukKomposisi");
        $pk = new MdlProdukKomposisi();
        // $pk->setTokoId($toko_id);
        // $condites
        $pk_src = $pk->lookupByCondition($condites)->result();
        // showLast_query("orange");
        // arrPrint($pk_src);
        $produkKomposisi = array();
        foreach ($pk_src as $pk_speks) {
            $produk_id = $pk_speks->produk_id;
            $produk_dasar_id = $pk_speks->produk_dasar_id;
            $produk_dasar_hpp = isset($psDatas_com[$produk_dasar_id]['hpp']) ? $psDatas_com[$produk_dasar_id]['hpp'] : 0;

            $dt_komposisi = array(
                "kode" => $psDatas[$produk_dasar_id]->kode,
                "bahan" => $psDatas[$produk_dasar_id]->nama,
                "satuan" => $psDatas[$produk_dasar_id]->satuan,
                "jml_bahan" => $pk_speks->jml,
                "hpp_bahan" => $produk_dasar_hpp * 1,
                "sub_hpp_bahan" => $produk_dasar_hpp * $pk_speks->jml,
            );

            $produkKomposisi[$produk_id][$produk_dasar_id] = (object)$dt_komposisi;
        }

        /* ------------------------------
         * nilai hpp produk
         * ------------------------------*/
        foreach ($produkKomposisi as $prod_id => $itemKomposisi) {
            foreach ($itemKomposisi as $items) {
                if (!isset($produkHpp[$prod_id])) {
                    $produkHpp[$prod_id] = 0;
                }
                $produkHpp[$prod_id] += $items->sub_hpp_bahan;
            }
        }

        // arrPrintWebs($produkHpp);
        // cekBiru($produkKomposisi);

        /* ------------------------------
         * bagian perhitungan-perhitungan
         * ------------------------------*/
        foreach ($hrgProduks as $produk_id => $produkSpks) {

            $hrg_bahan = isset($produkHpp[$produk_id]) ? $produkHpp[$produk_id] : 0;
            $hrg_beli = isset($produkHpp[$produk_id]) ? $hrg_bahan : 0;
            $hrg_jual = isset($produkSpks->harga_jual) ? $produkSpks->harga_jual : 0;
            $selisih_rl = $hrg_jual - $hrg_bahan;
            $selisih_int = $selisih_rl < 0 ? $selisih_rl * -1 : $selisih_rl;
            $status_rl = $selisih_rl <= 0 ? "rugi" : "laba";
            // $margin_rl = $selisih_int > 0 ? $selisih_int / $hrg_jual * 100 : 0;
            $margin_rl = $hrg_jual > 0 ? $selisih_int / $hrg_jual * 100 : 0;
            $margin_beli = $hrg_beli > 0 ? $selisih_int / $hrg_beli * 100 : 0;
            // cekHitam("$margin_rl = $selisih_int > 0 ? $selisih_int / $hrg_jual * 100 : 0;");

            $dt_produk = array(
                "hpp_produk" => $hrg_beli,
                "status_rl" => $status_rl,
                "margin_nilai" => $selisih_rl,
                "margin_jual_persen" => $margin_rl,
                "margin_beli_persen" => $margin_beli,
                "komposisi" => isset($produkKomposisi[$produk_id]) ? $produkKomposisi[$produk_id] : array(),
            );

            $hasils[$produk_id] = (object)((array)$produkSpks + $dt_produk);
        }

        // $hasils['komposisi'] = $produkKomposisi;
//         arrPrintPink($hasils);


        return $hasils;
    }

    public function calc_penjualan($toko_id, $periode_omset = 60, $produk_id = "")
    {
        // $this->CI =& get_instance();

        $periode = $jml_hari_penjualan = $periode_omset;
        // $buffer_d = $periode_buffer;
        // $indek = 100;
        // $indeks = $indek / 100;
        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));

        // cekHijau("$prev_date $stop_date ");

        $this->CI->load->model("Mdls/MdlReportSql");
        $rp = new MdlReportSql();
        $condites = array(
            "date(dtime)>=" => $prev_date,
            "date(dtime)<=" => $stop_date,
            // "year(dtime)" => "2021",
        );
        $this->CI->db->where($condites);
        $rp->setTokoId($toko_id);
        $srcs = $rp->callPenjualanProdukAll();
//         showLast_query("lime");
        // // cekBiru(sizeof($xx));
        // arrPrint($hasil);
        $penjualans = array();
        foreach ($srcs as $src) {
            $penjualans[$src->subject_id] = $src;
        }


        /* ------------------------------
        * mendapatakan data produk
        * ------------------------------*/
        $hrgProdukSrcs = $this->show_produk_harga_hpp($toko_id, $produk_id);
//         showLast_query("merah");
        // arrPrint($hrgProdukSrcs);
        $spekProduks = array();
        foreach ($hrgProdukSrcs as $produkSpeks) {
            $spekProduks[$produkSpeks->id] = $produkSpeks;
        }
        // cekBiru($hrgProduks);

        /* ---------------------------------------
         * pairingan data
         * ---------------------------------------*/
        $last_date = "";
        $kolomStatiks = array(
            "unit_af",
            "nilai_af",
            "bl",
            "th",
            // "subject_id",
        );
        $hasil = array();
        $produkLaku = array();
        foreach ($spekProduks as $produk_id => $speks) {

            if (!isset($produkLaku[$produk_id])) {
                $produkLaku[$produk_id] = array();
            }

            $srcSpeks = (array)$speks;

            $unit_af = isset($penjualans[$produk_id]->unit_af) ? $penjualans[$produk_id]->unit_af : 0;
            /* ----------------------------------------------------------------------------------
             * AI formulas
             * ----------------------------------------------------------------------------------*/
            $avg = $unit_af > 0 ? $unit_af / $periode : 0;

            $srcSpeks["avg"] = $avg;
            $srcSpeks["last_laku"] = $last_date;

            // $hasil = array();
            foreach ($kolomStatiks as $kolomStatik) {
                $srcDatas[$kolomStatik] = isset($penjualans[$produk_id]->$kolomStatik) ? $penjualans[$produk_id]->$kolomStatik : 0;

                $hasil[$produk_id] = $srcSpeks + $srcDatas;
                // arrPrintWebs($srcDatas);

                if ($unit_af > 0) {
                    $produkLaku[$produk_id] = $srcSpeks + $srcDatas;
                }

                if ($unit_af == 0) {
                    $produkTidakLaku[$produk_id] = $srcSpeks + $srcDatas;
                }
            }


        }

        // cekBiru($produkLaku);
        // cekKuning($produkTidakLaku);

        $params['periode_omset'] = $periode;
        $params['start_date'] = $prev_date;

        $result = array();
        $result["params"] = $params;
        $result["datas"] = $hasil;
        $result["laku"] = $produkLaku;
        $result["tidak_laku"] = $produkTidakLaku;

        return $result;
    }

    public function show_produk_kategori_terlaris($datas, $str_key)
    {
        $hasils = array();
        foreach ($datas as $produk_id => $data) {
            $main_key = $data->$str_key;
            $main_key_str = $data->kategori_main_nama;

            if ($main_key > 0) {

                $arrLaris[$produk_id] = $data->terjual;

                $newDataLaris[$main_key][$produk_id] = $data->terjual;
                // arsort($arrLaris);
                // $newDataLaris[$main_key] = $arrLaris;
                $newDatas[$main_key][$produk_id] = $data;

                $kategories[$main_key] = $main_key_str;
            }
        }

        $hasils['kategori'] = isset($kategories) ? $kategories : array();
        $hasils['laris'] = isset($newDataLaris) ? $newDataLaris : array();
        $hasils['datas'] = isset($newDatas) ? $newDatas : array();

        return $hasils;
    }
}
