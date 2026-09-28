<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/7/2018
 * Time: 10:31 AM
 */
class Tester extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("MdlTransaksi");
    }

    public function cekHarga()
    {
        $this->load->model("MdlHarga");
        $h = new MdlHarga();
        $h->addFilter("produk_id='100'");
        $tmp = $h->lookupAll();
        print_r($tmp);
    }

    public function cekGudang()
    {
        $this->load->model("MdlGudang");
        $h = new MdlGudang();
//        $h->addFilter("produk_id='100'");
        $tmp = $h->lookupAll()->result();
        print_r($tmp);
    }

    public function cekStok()
    {
        $this->load->model("MdlStockCache");
        $o = new MdlStockCache();
        $o->addFilter("cabang_id='-1'");
        $tmp = $o->lookupAll()->result();
        print_r($tmp);
//        print_r($this->db->last_query());

    }

    public function cekLedger()
    {
        $this->load->model("ComLedger");
        $l = new ComLedger();
//        $l->addFilter("periode='harian'");
//        $l->addFilter("periode='bulanan'");
//        $l->addFilter("periode='tahunan'");
        $l->addFilter("periode='forever'");
//        $date = date("Y-m-d");
        $tmp = $l->getLastEntries2();


        arrPrint($tmp);
//        mati_disini();

        $arrHasilLajur = $l->getLajurBalance($tmp);
        arrPrint($arrHasilLajur);

        $arrHasilNeraca = $l->getNeracaBalance($tmp);
        arrPrint($arrHasilNeraca);

        $arrLabaRugi = $l->getLabaRugi($tmp);
        arrPrint($arrLabaRugi);


        if (is_null($arrHasilLajur)) {
            mati_disini("UN-BALANCE... heheeh");
        }
        else {
//            arrPrint($arrHasilLajur);
            cekBiru("BALANCE LAJUR...");
        }

        if (is_null($arrHasilNeraca)) {
            mati_disini("UN-BALANCE... heheeh");
        }
        else {
//            arrPrint($arrHasilNeraca);
            cekBiru("BALANCE NERACA...");
        }
    }

    function testRek()
    {
        $this->load->model('MdlTransaksi');
        $this->load->model('ComJurnal');
        $this->load->model('ComRekening');
        $this->load->model('ComRekeningPembantuHutangSuppliers');
        $this->load->model('ComRekeningPembantuBahan');
//        $this->load->model('ComRekeningPembantuKas');
        $this->load->model('ComRekeningPembantuPPhSuppliers');

        $cj = New ComJurnal();
        $cr = New ComRekening();
        $crps = New ComRekeningPembantuHutangSuppliers();
        $crpb = New ComRekeningPembantuBahan();
        $crpph = New ComRekeningPembantuPPhSuppliers();
//        $crpk = New ComRekeningPembantuKas();


        $transaksi_id = "1";
        $transaksi_jenis = "466";
        $transaksi_no = "1234-5678-9";
        $cabang_id = "0";

        $pembelian = 1;
        if ($pembelian == 1) {
            $array_in_jurnal = array(
                "loop" => array(
//                "kas" => "-10000",
                    "hutang dagang" => "9000",
                    "persediaan supplies" => "10000",
                    "hutang pph" => "1000",
                ),
                "static" => array(
                    "jenis" => $transaksi_jenis,
                    "j_jenis" => "pembelian",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "cabang_id" => $cabang_id,
                    "dtime" => date("Y-m-d H:i:s"),
                ),
            );
            $array_in_rek_umum = array(
                "loop" => array(
//                "kas" => "-10000",
                    "hutang dagang" => "9000",
                    "persediaan supplies" => "10000",
                    "hutang pph" => "1000",
//                    "ppn in" => "1000",
                ),
                "static" => array(
                    "jenis" => $transaksi_jenis,
                    "j_jenis" => "pembelian",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $array_in_suppliers = array(
                "loop" => array(
                    "hutang dagang" => "9000",
                    //                "kas" => "-10000",
                ),
                "static" => array(
                    "jenis" => "467",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "supplier_id" => "2587",
                    "supplier_nama" => "",
                    //                "jenis_id" => "2",
                    //                "jenis_nama" => "0533880000",
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $array_in_pph = array(
                "loop" => array(
                    "hutang pph" => "1000",
                    //                    "ppn in" => "1000",
                ),
                "static" => array(
                    "jenis" => "467",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "wajib_pajak_id" => "2587",
                    "wajib_pajak_nama" => "",
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $array_in_kas = array(
                "loop" => array(
//                "hutang dagang" => "9000",
                    "kas" => "-9000",
                ),
                "static" => array(
                    "jenis" => "467",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    //                "supplier_id" => "2587",
                    //                "supplier_nama" => "",
                    "jenis_id" => "2",
                    "jenis_nama" => "0533880000",
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $arrBahan = array(
                "41" => array(
                    "qtt" => "5",
                    "harga" => "1000",
                    "pph" => "100",
                    "nama" => "karton",
                ),
                "45" => array(
                    "qtt" => "10",
                    "harga" => "500",
                    "pph" => "50",
                    "nama" => "plastik",
                ),
            );
            $arrTransaksi = array(
                "jenis" => "$transaksi_jenis",
                "dtime" => date("Y-m-d H:i:s"),
                "oleh_id" => "1",
                "oleh_nama" => "saya",
                "transaksi_nilai" => "10000",
                "diskon_nilai" => "0",
                "ppn_nilai" => "1000",
                "transaksi_net" => "9000",
                "sinkron" => "1",
                "cabang_id" => "$cabang_id",
                "cabang_nama" => "",
                //                "jenis" => "$transaksi_jenis",
            );
        }
        else {
            $array_in_jurnal = array(
                "loop" => array(
                    "hutang dagang" => "-9000",
                    "persediaan supplies" => "-10000",
                    "hutang pph" => "-1000",
                ),
                "static" => array(
                    "jenis" => $transaksi_jenis,
                    "j_jenis" => "pembelian",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "cabang_id" => $cabang_id,
                    "dtime" => date("Y-m-d H:i:s"),
                ),
            );
            $array_in_rek_umum = array(
                "loop" => array(
                    "hutang dagang" => "-9000",
                    "persediaan supplies" => "-10000",
                    "hutang pph" => "-1000",
                ),
                "static" => array(
                    "jenis" => $transaksi_jenis,
                    "j_jenis" => "pembelian",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $array_in_suppliers = array(
                "loop" => array(
                    "hutang dagang" => "-9000",
                ),
                "static" => array(
                    "jenis" => "467",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "supplier_id" => "2587",
                    "supplier_nama" => "",
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $array_in_pph = array(
                "loop" => array(
                    "hutang pph" => "-1000",
                    //                    "ppn in" => "1000",
                ),
                "static" => array(
                    "jenis" => "467",
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "wajib_pajak_id" => "2587",
                    "wajib_pajak_nama" => "",
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $arrBahan = array(
                "41" => array(
                    "qtt" => "-5",
                    "harga" => "1000",
                    "nama" => "karton",
                ),
                "45" => array(
                    "qtt" => "-10",
                    "harga" => "500",
                    "nama" => "plastik",
                ),
            );
            $arrTransaksi = array(
                "jenis" => "$transaksi_jenis",
                "dtime" => date("Y-m-d H:i:s"),
                "oleh_id" => "1",
                "oleh_nama" => "saya",
                "transaksi_nilai" => "10000",
                "diskon_nilai" => "0",
                "ppn_nilai" => "1000",
                "transaksi_net" => "9000",
                "sinkron" => "1",
                "cabang_id" => "$cabang_id",
                "cabang_nama" => "",
                //                "jenis" => "$transaksi_jenis",
            );
        }


        $this->db->trans_begin();


        cekMerah("tulis TRANSAKSI MAIN & TRANSAKSI CHILDS");

        //  region transaksi
        $tr = new MdlTransaksi();
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $transaksi_id = $tr->writeMainEntries($arrTransaksi);
        //  endregion transaksi

        //  region transaksi childs
        foreach ($arrBahan as $p_id => $p_data) {
            $tr_data = array(
                "produk_jenis" => "bahan",
                "produk_id" => $p_id,
                "produk_nama" => $p_data['nama'],
                "produk_ord_jml" => $p_data['qtt'],
                "produk_ord_hrg" => $p_data['harga'],
                "ppn" => $p_data['pph'],
            );
//            $tr->writeDetailEntries($transaksi_id, $tr_data);
        }
        //  endregion transaksi childs


        $paramsUp = array(
            "status" => "1",
            "transaksi_nilai" => "11000",
            "transaksi_net" => "10000",
            "diskon_nilai" => "1000",
        );
        $addParams = array(
            "trash" => "0",
        );

        $tr->writeMainEntriesUpdate($transaksi_id, $paramsUp, $addParams);
//mati_disini(":: $transaksi_id ::");


        //  region jurnal
        $cj->pair($array_in_jurnal);
        $cj->exec();
        //  endregion jurnal
        cekMerah("JURNAL OKE...");
//        mati_disini();

        //  region mutasi rekening umum
        $cr->pair($array_in_rek_umum);
        $cr->exec();
        //  endregion mutasi rekening umum
        cekMerah("CACHE dan MUTASI REKENING UMUM OKE...");
//        mati_disini();

        //  region mutasi rekening pembantu hutang suppliers
        $crps->pair($array_in_suppliers);
        $crps->exec();
        //  endregion mutasi rekening pembantu hutang suppliers
        cekMerah("CACHE dan MUTASI REKENING PEMBANTU PIHAK OKE...");
//        mati_disini();

        //  region rekening pph
        $crpph->pair($array_in_pph);
        $crpph->exec();
        //  endregion rekening pph
        cekMerah("CACHE dan MUTASI REKENING PEMBANTU PPh OKE...");
//        mati_disini();

        //  region mutasi rekening pembantu bahan
        foreach ($arrBahan as $p_id => $arrBahan_data) {
            $array_in_bahan = array(
                "loop" => array(
                    "persediaan supplies" => array(
                        "produk_id" => $p_id,
                        "produk_nama" => $arrBahan_data['nama'],
                        "produk_qtt" => $arrBahan_data['qtt'],
                        "produk_nilai" => $arrBahan_data['harga'] * $arrBahan_data['qtt'],
                    ),
                ),
                "static" => array(
                    "jenis" => $transaksi_jenis,
                    "transaksi_id" => $transaksi_id,
                    "transaksi_no" => $transaksi_no,
                    "cabang_id" => $cabang_id,
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
            $crpb->pair($array_in_bahan);
            $crpb->exec();
        }
        //  endregion mutasi rekening pembantu bahan
        cekMerah("CACHE dan MUTASI REKENING PEMBANTU BARANG OKE...");


        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
    }

    public function multiExplode()
    {
        $input = "angka1+angka2-angka3";

//        $output = preg_split( "/(\+|\-)/", $input );
//        print_r($output);

        $tmp1 = explode("+", $input);
        if (sizeof($tmp1) > 0) {
//            echo "$input mengandung plus<br>";
            foreach ($tmp1 as $input2) {
                $tmp2 = explode("-", $input2);
                if (sizeof($tmp2) > 0) {
//                    echo "$input2 mengandung minus<br>";
                    foreach ($tmp2 as $input3) {
//                        echo "$input3<br>";
                    }
                }
            }
        }


    }

    function evalStr()
    {
        $this->load->library("FieldCalculator");
        $Cal = new FieldCalculator();

//        echo  $Cal->calculate('5+7'); // 12
//        echo $Cal->calculate('(5+9)*5'); // 70
        $n = 6;
        echo $Cal->calculate("6+9-2-$n");
    }


    public function buildRek()
    {

//        $arrRekening["supplier"] = array(
//            "master",
//            "detail_master",
//            "detail_cache",
//        );
//
//        $arrSourceTable = array(
//            "master" => array( // untuk rekening mutasi
//                "prefix" => "rek_",
//                "source" => "template_rekening_master",
//            ),
//            "detail_master" => array( // untuk rekening pembantu mutasi dan cache
//                "prefix" => "rek_pembantu_",
//                "source" => "template_rekening_detail",
//            ),
//            "detail_cache" => array( // untuk rekening pembantu mutasi dan cache
//                "prefix" => "rek_cache_pembantu_",
//                "source" => "template_rekening_detail",
//            ),
//        );
//
//        foreach ($arrRekening as $rek => $arrRek) {
//            if (sizeof($arrRek)) {
//                foreach ($arrRek as $row) {
//                    $table_build_name = $arrSourceTable[$row]["prefix"] . "$rek";
//                    $table_build_source = $arrSourceTable[$row]["source"];
//
//
//                    $result = $this->db->query("SHOW TABLES LIKE '" . $table_build_name . "'")->result();
//                    if (sizeof($result) == 0) {
//
//                        $q = "create table $table_build_name like $table_build_source";
//                        $result_c = $this->db->query($q);
//                        if (sizeof($result_c) > 0) {
//                            cekHere(__LINE__ . "CONGRATULATION, TABEL BERHASIL DIBUAT.... [$table_build_name]");
//                        } else {
//                            cekHere(__LINE__ . "TABEL STILL EXIST.... [$table_build_name]");
//                        }
//                    } else {
//                        cekHere(__LINE__ . " TABEL STILL EXIST.... [$table_build_name]");
//                    }
//                }
//            }
//        }

        $this->load->model("ComRekening");
        $this->load->model("ComRekeningPembantuHutangSupplier");
        $this->load->model("ComRekeningPembantuSupplies");
        $cr = New ComRekening;
        $cp = New ComRekeningPembantuHutangSupplier;
        $cs = New ComRekeningPembantuSupplies;

        $arrParams = array(
            "comName" => "Rekening",
            "loop" => array(
                "persediaan supplies" => "bruto",
                "hutang dagang" => "nett",
                "ppn in" => "ppn",
            ),
            "static" => array(
                "cabang_id" => "placeID",

            ),
        );
        cekHitam("buat table rekening umum");
        $cr->buildTables($arrParams);

//        $arrParams2 = array(
//            "comName" => "RekeningPembantuProduk",
//            "loop" => array(
//                "persediaan produk" => "sub_harga"
//            ),
//            "static" => array(
//                "cabang_id" => "placeID",
//                "produk_id" => "id",
//                "produk_qty" => "qty",
//                "produk_nama" => "name",
//                "produk_nilai" => "harga",
//            ),
//        );
//cekHitam("buat table rekening pembantu");
//        $cp->buildTables($arrParams2);


        $this->db->trans_begin();


        $array_in_rek_umum = array(
            "loop" => array(
                "hutang dagang" => "11000",
                "persediaan supplies" => "10000",
                "ppn in" => "1000",
            ),
            "static" => array(
                "jenis" => 466,
                "j_jenis" => "pembelian",
                "transaksi_id" => 9999,
                "transaksi_no" => 88888,
                "cabang_id" => "-1",
                "tgl" => date('d'),
                "bln" => date('m'),
                "thn" => date('Y'),
                "dtime" => date('Y-m-d H:i:s'),
            ),
        );
        //  region mutasi rekening umum
        $cr->pair($array_in_rek_umum);
        $cr->exec();
        //  endregion mutasi rekening umum


        //  region mutasi rekening pembantu bahan
        $arrBahan = array(
            "41" => array(
                "qtt" => "5",
                "harga" => "1000",
                "pph" => "100",
                "nama" => "karton",
            ),
            "45" => array(
                "qtt" => "10",
                "harga" => "500",
                "pph" => "50",
                "nama" => "plastik",
            ),
        );

        $array_in_bahan = array();
        foreach ($arrBahan as $p_id => $arrBahan_data) {
            $array_in_bahan[] = array(
                "loop" => array(
                    "persediaan supplies" => $arrBahan_data['harga'],
                ),
                "static" => array(
                    "produk_id" => $p_id,
                    "produk_nama" => $arrBahan_data['nama'],
                    "extern_id" => $p_id,
                    "extern_nama" => $arrBahan_data['nama'],
                    "produk_qty" => $arrBahan_data['qtt'],
                    "produk_nilai" => $arrBahan_data['harga'] * $arrBahan_data['qtt'],
                    "jenis" => 466,
                    "transaksi_id" => 9999,
                    "transaksi_no" => 88888,
                    "cabang_id" => "-1",
                    "tgl" => date('d'),
                    "bln" => date('m'),
                    "thn" => date('Y'),
                    "dtime" => date('Y-m-d H:i:s'),
                ),
            );
        }
        $cs->pair($array_in_bahan);
        $cs->exec();
        //  endregion mutasi rekening pembantu bahan


        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
    }

    public function testDP()
    {
        $this->load->model('MdlTransaksi');
        $this->load->model('ComJurnal');
        $this->load->model('ComRekening');
        $this->load->model('ComRekeningPembantuCustomer');
        $this->load->model('ComRekeningPembantuKas');

        $t = new MdlTransaksi();
        $j = New ComJurnal();
        $r = New ComRekening();
        $c = New ComRekeningPembantuCustomer();
        $k = New ComRekeningPembantuKas();


        $transaksi_jenis = "582so";
        $cabang_id = "-1";
        $extern_id = "6";
        $extern_id2 = "5";

        $penjualan = "10000";
        $piutang = "11000";
        $ppn = "1000";
        $kas_dp = "5000";
        $dp = ($kas_dp * 100) / 110;
        $ppn_dp = $dp / 10;

        $this->db->trans_begin();

        $arrTransaksi = array(
            "jenis" => "$transaksi_jenis",
            "dtime" => date("Y-m-d H:i:s"),
            "oleh_id" => "1",
            "oleh_nama" => "saya",
            "transaksi_nilai" => $penjualan,
//            "diskon_nilai"    => "0",
//            "ppn_nilai"       => $ppn,
//            "transaksi_net"   => $piutang,
            "sinkron" => "1",
            "cabang_id" => $cabang_id,
            "cabang_nama" => "",
            //                "jenis" => "$transaksi_jenis",
        );
        $transaksi_id = $t->writeMainEntries($arrTransaksi);

        $params = array();
        $params[] = array(
            "transaksi_id" => "$transaksi_id",
            "key" => "adv_nett",
            "value" => $kas_dp,
        );
        $params[] = array(
            "transaksi_id" => "$transaksi_id",
            "key" => "adv_ppn",
            "value" => $ppn_dp,
        );
        $params[] = array(
            "transaksi_id" => "$transaksi_id",
            "key" => "adv_hutang",
            "value" => $dp,
        );
        $params[] = array(
            "transaksi_id" => "$transaksi_id",
            "key" => "adv_ppn_sisa",
            "value" => $ppn_dp,
        );
        $params[] = array(
            "transaksi_id" => "$transaksi_id",
            "key" => "adv_hutang_sisa",
            "value" => $dp,
        );
        arrPrint($params);
        foreach ($params as $detailParams) {

            $t->writeMainValues($transaksi_id, $detailParams);

        }

//        mati_disini();

        $arrDP = array(
            "loop" => array(
                "hutang ke konsumen" => $dp,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
                "extern_id" => $extern_id,
            ),
        );
        $arrKas = array(
            "loop" => array(
                "kas" => $kas_dp,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
                "extern_id" => $extern_id2,
            ),
        );
        $arrPPn = array(
            "loop" => array(
                "ppn out" => $ppn_dp,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
                "extern_id" => $extern_id,
            ),
        );
        $arrJurnal = array(
            "loop" => array(
                "ppn out" => $ppn_dp,
                "hutang ke konsumen" => $dp,
                "kas" => $kas_dp,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
            ),
        );
        cekHere("HAHAHA");
        $j->pair($arrJurnal);
        $j->exec();
        cekHere("jurnal oke");
        $r->pair($arrJurnal);
        $r->exec();
        cekHere("rekening oke");
        $c->pair($arrDP);
        $c->exec();
        $c->pair($arrPPn);
        $c->exec();
        $k->pair($arrKas);
        $k->exec();


        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
    }

    public function testKirim()
    {

        $this->load->model('MdlTransaksi');
        $this->load->model('ComJurnal');
        $this->load->model('ComRekening');
        $this->load->model('ComRekeningPembantuCustomer');
        $this->load->model('ComRekeningPembantuKas');

        $t = new MdlTransaksi();
        $j = New ComJurnal();
        $r = New ComRekening();
        $c = New ComRekeningPembantuCustomer();
        $k = New ComRekeningPembantuKas();


        $transaksi_id_ref = 213;
        $transaksi_jenis = "582spd";
        $cabang_id = "-1";
        $extern_id = "6"; // konsumen
        $extern_id2 = "5"; // bank

//        $penjualan = "10000";
//        $piutang = "11000";
//        $ppn = "1000";
        $penjualan = "5000";
        $piutang = "5500";
        $ppn = "500";


        $mainValues = $t->lookupMainValuesByTransID($transaksi_id_ref)->result();
        $arrTmp = array();
        foreach ($mainValues as $iSpec) {
            $arrTmp[$iSpec->transaksi_id][$iSpec->key] = $iSpec->value;
        }
        arrPrint($arrTmp);
        $adv_hutang_sisa = $arrTmp[$transaksi_id_ref]['adv_hutang_sisa']; // hutang ke konsumen, kuota
        $adv_ppn_sisa = $arrTmp[$transaksi_id_ref]['adv_ppn_sisa']; // ppn out terbayar, kuota

        if ($adv_ppn_sisa > 0) {
            if ($adv_ppn_sisa >= $ppn) {
                cekHitam("ATAS");
                $new_adv_ppn_sisa = $adv_ppn_sisa - $ppn;
                $ppn_out = $ppn > $adv_ppn_sisa ? $ppn - $adv_ppn_sisa : 0;
            }
            else {
                cekHitam("BAWAH");
                $new_adv_ppn_sisa = 0;
                $ppn_out = $ppn - $adv_ppn_sisa;
            }
            $arrTmp[$transaksi_id_ref]['adv_ppn_sisa'] = $new_adv_ppn_sisa;
        }
        else {
            $new_adv_ppn_sisa = 0;
            $ppn_out = $ppn;
            $arrTmp[$transaksi_id_ref]['adv_ppn_sisa'] = $new_adv_ppn_sisa;
        }
        if ($adv_hutang_sisa > 0) {
            if ($adv_hutang_sisa >= $penjualan) {
                $new_adv_hutang_sisa = $adv_hutang_sisa - $penjualan;
                $hutang_ke_konsumen = $penjualan;
                $piutang = 0;
            }
            else {
                $new_adv_hutang_sisa = 0;
                $hutang_ke_konsumen = $adv_hutang_sisa;
                $piutang = ($penjualan - $adv_hutang_sisa) + $ppn_out;
            }
            $arrTmp[$transaksi_id_ref]['adv_hutang_sisa'] = $new_adv_hutang_sisa;
        }
        else {
            $new_adv_hutang_sisa = 0;
            $hutang_ke_konsumen = 0;
            $piutang = $penjualan + $ppn_out;
            $arrTmp[$transaksi_id_ref]['adv_hutang_sisa'] = $new_adv_hutang_sisa;
        }

        cekMerah("penjualan: $penjualan");
        cekMerah("hutang ke konsumen: $hutang_ke_konsumen");
        cekMerah("ppn out: $ppn_out");
        cekMerah("piutang: $piutang");
        cekMerah("NEW hutang ke konsumen: $new_adv_hutang_sisa");
        cekMerah("NEW ppn out: $new_adv_ppn_sisa");
        arrPrint($arrTmp);

        $this->db->trans_begin();

        $arrTransaksi = array(
            "jenis" => "$transaksi_jenis",
            "dtime" => date("Y-m-d H:i:s"),
            "oleh_id" => "1",
            "oleh_nama" => "saya",
            "transaksi_nilai" => $penjualan,
//            "diskon_nilai"    => "0",
//            "ppn_nilai"       => $ppn,
//            "transaksi_net"   => $piutang,
            "sinkron" => "1",
            "cabang_id" => $cabang_id,
            "cabang_nama" => "",
            //                "jenis" => "$transaksi_jenis",
        );
        $transaksi_id = $t->writeMainEntries($arrTransaksi);

        foreach ($arrTmp as $tr_id => $iSpec) {
            foreach ($iSpec as $key => $value) {
                $iSpecDetail = array(
                    "key" => $key,
                    "value" => $value,
                );
                $where = array(
                    "transaksi_id" => "$tr_id",
                    "key" => $key,
                );
                $t->setFilters(array());
                $t->setTableName($t->getTableNames()['mainValues']);
                $t->updateData($where, $iSpecDetail);
                cekBiru($this->db->last_query());
            }
        }

        $arrDP = array(
            "loop" => array(
                "hutang ke konsumen" => $hutang_ke_konsumen,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
                "extern_id" => $extern_id,
            ),
        );
        $arrPPn = array(
            "loop" => array(
                "ppn out" => $ppn_out,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
                "extern_id" => $extern_id,
            ),
        );
        $arrJurnal = array(
            "loop" => array(
                "ppn out" => $ppn_out,
                "hutang ke konsumen" => -$hutang_ke_konsumen,
                "piutang dagang" => $piutang,
                "penjualan" => $penjualan,
            ),
            "static" => array(
                "cabang_id" => $cabang_id,
                "transaksi_id" => $transaksi_id,
            ),
        );
        cekHere("HAHAHA");


        $j->pair($arrJurnal);
        $j->exec();
        cekHere("jurnal oke");
//        mati_disini();
        $r->pair($arrJurnal);
        $r->exec();
        cekHere("rekening oke");
        $c->pair($arrDP);
        $c->exec();
        cekHere("hutang ke konsumen");
        $c->pair($arrPPn);
        $c->exec();
        cekHere("ppn");


        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
    }

    public function testReturnTableName()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("ComRekening");
        $this->load->model("ComRekeningPembantuProduk");

        $com = new ComRekeningPembantuProduk();
//        $com=new ComRekening();


        $anu = heReturnTableName($com->getTableNameMaster(), array("persediaan"));
        arrprint($anu);
    }


    public function konverter()
    {

        $arrKonverter = array(
            //harga jual produk
            array(
                "additional" => array(
                    "jenis_value" => "jual",
                    "jenis" => "produk",
                ),
                "tabel" => array(
                    "harga" => "price",
                ),
                "kolom" => array(
                    "produk_id" => "produk_id",
                    "cabang_id" => "cabang_id",
                    "status" => "status",
                    "author" => "oleh_id",
                    "author_nama" => "oleh_nama",
                    "harga_baru" => "nilai",
                ),
            ),
            //harga beli produk
            array(
                "additional" => array(
                    "jenis_value" => "hpp",
                    "jenis" => "produk",
                ),
                "tabel" => array(
                    "harga" => "price",
                ),
                "kolom" => array(
                    "produk_id" => "produk_id",
                    "cabang_id" => "cabang_id",
                    "status" => "status",
                    "author" => "oleh_id",
                    "author_nama" => "oleh_nama",
                    "hpp_baru" => "nilai",
                ),
            ),
            //harga beli supplies
            array(
                "additional" => array(
                    "jenis_value" => "hpp",
                    "jenis" => "supplies",
                ),
                "tabel" => array(
                    "harga_supplies" => "price",
                ),
                "kolom" => array(
                    "produk_id" => "produk_id",
                    "cabang_id" => "cabang_id",
                    "status" => "status",
                    "author" => "oleh_id",
                    "author_nama" => "oleh_nama",
                    "hpp_baru" => "nilai",
                ),
            ),
            //template alamat, customer
            array(
                "filter" => array(
                    "jenis" => "kirim",
                ),
                "additional" => array(
                    "extern_type" => "customer",
                    "jenis" => "shipment",
                ),
                "tabel" => array(
                    "tpl_alamat" => "address",
                ),
                "kolom" => array(
                    "per_customers_id" => "extern_id",
                    "per_customers_nama" => "extern_name",
                    "alias" => "alias",
                    "email" => "email",
                    "tlp" => "tlp",
                    "tlp_2" => "tlp_2",
                    "tlp_3" => "tlp_3",
                    "alamat" => "alamat",
                    "kelurahan" => "kelurahan",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi" => "propinsi",
                    "kodepos" => "kodepos",
                    "status" => "status",
                    "trash" => "trash",
                ),
            ),
            //template bill, customer
            array(
                "filter" => array(
                    "jenis" => "kirim",
                ),
                "additional" => array(
                    "extern_type" => "customer",
                    "jenis" => "bill",
                ),
                "tabel" => array(
                    "tpl_alamat" => "address",
                ),
                "kolom" => array(
                    "per_customers_id" => "extern_id",
                    "per_customers_nama" => "extern_name",
                    "alias" => "alias",
                    "email" => "email",
                    "tlp" => "tlp",
                    "tlp_2" => "tlp_2",
                    "tlp_3" => "tlp_3",
                    "alamat" => "alamat",
                    "kelurahan" => "kelurahan",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi" => "propinsi",
                    "kodepos" => "kodepos",
                    "status" => "status",
                    "trash" => "trash",
                ),
            ),
            //template alamat, warehouse
            array(
                "filter" => array(
                    "jenis" => "terima",
                ),
                "additional" => array(
                    "extern_type" => "supplier",
                    "jenis" => "shipment",
                ),
                "tabel" => array(
                    "tpl_alamat" => "address",
                ),
                "kolom" => array(
                    "per_customers_id" => "extern_id",
                    "per_customers_nama" => "extern_name",
                    "alias" => "alias",
                    "email" => "email",
                    "tlp" => "tlp",
                    "tlp_2" => "tlp_2",
                    "tlp_3" => "tlp_3",
                    "alamat" => "alamat",
                    "kelurahan" => "kelurahan",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi" => "propinsi",
                    "kodepos" => "kodepos",
                    "status" => "status",
                    "trash" => "trash",
                ),
            ),
            //
        );
//        cekMerah("EXECUTE...");


        $this->db->trans_begin();


        if (sizeof($arrKonverter) > 0) {
            foreach ($arrKonverter as $k => $kSpec) {
                foreach ($kSpec['tabel'] as $fromTabel => $toTabel) {
                    $tmp = array();


                    cekBiru("ambil dari tabel $fromTabel");
                    if (array_key_exists("filter", $kSpec)) {
                        $this->db->where($kSpec['filter']);
                    }
                    $tmp = $this->db->get($fromTabel)->result();
                    cekMerah($this->db->last_query());

                    if (sizeof($tmp) > 0) {
                        $hasil = array();
                        foreach ($kSpec['kolom'] as $fromKolom => $toKolom) {
                            foreach ($tmp as $e => $eSpec) {
                                $hasil[$e][$toKolom] = $eSpec->$fromKolom;
                                if (isset($kSpec['additional']) && sizeof($kSpec['additional']) > 0) {
                                    foreach ($kSpec['additional'] as $key => $val) {
                                        $hasil[$e][$key] = $val;
                                    }
                                }
                            }
                        }

                        cekMerah("tulis ke tabel $toTabel");
                        if (sizeof($hasil) > 0) {
//                                $this->db->truncate($toTabel);

                            $no = 0;
                            $insertID = array();
                            foreach ($hasil as $hSpec) {
                                $insertID[] = $this->db->insert($toTabel, $hSpec);

                                $no++;
                                cekKuning("$no :: " . $this->db->last_query());
                            }
                            if (sizeof($insertID) == 0) {
                                mati_disini("konverter no $k, GAGAL menulis ke tabel $toTabel");
                            }
                        }

                    }
                    else {
                        cekHitam("konverter no $k, tabel $fromTabel KOSONG, tidak menulis ke tabel $toTabel");
                    }
                }
            }
        }

        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

    }

    public function testAccounting()
    {

        $this->load->model("Coms/ComLockerValue");
        $ca = New ComLockerValue();
        cekHitam("testing postprocessor ComLockerValue");

        $this->db->trans_start();

        $master_id = "17";
        $static = array(
            "static" => array(
                "produk_id" => "6",
                "nilai" => "10000000",
                "cabang_id" => "-1",
                "gudang_id" => "0",
                "jenis" => "hutang ke konsumen",
//                "jenis_locker" => "value",
                "transaksi_no" => "582spo.1.1",
                "transaksi_id" => $master_id,
                "state" => "active",
                "oleh_id" => "0",
            ),
        );
        $ca->pair($static);
        $ca->exec();

        mati_disini();
        $this->db->trans_commit();
    }

    public function posisiRek()
    {
        echo detectRekDefaultPosition("kas");
    }

    public function tesStaticMdl()
    {
        $this->load->model('Mdls/MdlPaymentMethod');
        $o = New MdlPaymentMethod();
        $tmp = $o->lookupAll()->result();
        arrprint($tmp);
    }

    public function testPre()
    {

        $arrTest[] = array(
            "static" => array(
                "extern_id" => "209",
                "produk_qty" => "2",
                "cabang_id" => "-1",
                "gudang_id" => "-1",
            ),
        );
        $arrTest[] = array(
            "static" => array(
                "extern_id" => "195",
                "produk_qty" => "2",
                "cabang_id" => "-1",
                "gudang_id" => "-1",
            ),
        );

        $arrResultParams = array(
            "items" => array(
                "harga" => "hpp",
                "hpp" => "hpp",
            ),
            "items2_sum" => array(
                "harga" => "hpp",
                "hpp" => "hpp",
            ),
        );
        $this->db->trans_begin();


        $this->load->model('Preprocs/PreFifoAverageSuppliesAssembly');
        $n = New PreFifoAverageSuppliesAssembly($arrResultParams);
        $n->pair(0, $arrTest);


        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

    }

    public function normalizePrices()
    {
        $prices = array(
            "hpp" => 12000,
            "jual" => 16000,
            "disc" => 5,
        );
//        $this->load->helper("he_prices");
        $nPrices = normalizePrices("produk", $prices);
        arrprint($nPrices);
    }

    public function getSaldo()
    {

        $externID = "1";

        $this->load->model("Coms/ComRekeningPembantuKas");
        $m = New ComRekeningPembantuKas();
        $m->addFilter("extern_id='$externID'");
        $m->addFilter("periode='forever'");
        $m->addFilter("cabang_id='-1'");
        $arrHasil = $m->fetchBalances("kas");

        cekHitam($this->db->last_query());
        arrPrint($arrHasil);
    }

    public function getStock()
    {

        $rekening = "persediaan produk";

        $this->load->model("Coms/ComRekeningPembantuProduk");
        $m = New ComRekeningPembantuProduk();
        $m->addFilter("periode='forever'");
        $m->addFilter("cabang_id='-1'");
        $m->addFilter("gudang_id='-1'");
        $arrHasil = $m->fetchBalances($rekening);

        cekHitam($this->db->last_query());
        arrPrint($arrHasil);

        $result = array();
        foreach ($arrHasil as $eSpec) {

            $result[$eSpec->id] = $eSpec->qty_debet;
        }

        arrPrint($result);
    }

    function cekSourceTCode()
    {
        $this->load->helper("he_stepping");
        $toTest = "585";
        cekkuning("source dari $toTest adalah:");
        var_dump(heGetOriginTCode($toTest));
        cekkuning(heGetOriginTCode($toTest));
    }

    function injectFulldate()
    {
        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("fulldate is null");
        $tmp = $tr->lookupMainTransaksi()->result();

        if (sizeof($tmp) > 0) {
            $arrUpdate = array();
            foreach ($tmp as $tSpec) {
                $arrUpdate[$tSpec->id] = $tSpec->dtime;
            }
        }
//        arrPrint($arrUpdate);
        $this->db->trans_start();

        foreach ($arrUpdate as $id => $dtime) {
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array("id" => $id);
            $data = array("fulldate" => $dtime);
            $tr->updateData($where, $data);
            cekHere($this->db->last_query());
        }

        mati_disini();
        $this->db->trans_commit();
    }


    function canceled()
    {

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
//        $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number='0'");
        $tr->addFilter("valid_qty>0");

        $arrFilterJoined = array(
            "cabang_id" => "25",
            "gudang_id" => "-250",
        );
        $tmpHist = $tr->lookupUndoneEntries_joined($arrFilterJoined)->result();
//        $tmpHist = $tr->lookupUndoneEntries_joined($this->session->login['cabang_id'], $this->session->login['gudang_id'])->result();
        cekHitam($this->db->last_query());
//arrPrint($tmpHist);
        $trIDs = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $tmpSpec) {
                $trIDs[] = $tmpSpec->transaksi_id;
            }
        }
        arrPrint($trIDs);

        $this->db->trans_start();


        if (sizeof($trIDs) > 0) {
            foreach ($trIDs as $trID) {
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(
                    array(
                        "id" => $trID
                    ), array(
                        "trash_4" => 1,

                    )
                ) or die("Failed to update tr next-state!");
                cekBiru($this->db->last_query());
            }
        }


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }

    function hitung()
    {

        $this->load->helper("he_angka");

        $arrData = array();
        $arrQty = array(
            "174" => 2,
            "173" => 6,
        );
        $arrData[] = array(
            "berat" => "333880",
            "volume" => "1512000000",
            "subberat" => 333880 * 2,
            "subvolume" => 1512000000 * 2,
            "sub_lebar_gross" => 1440 * 2,
            "sub_panjang_gross" => 500 * 2,
            "sub_tinggi_gross" => 2100 * 2,
        );
//        $arrData[] = array(
//            "berat" => "45000",
//            "volume" => "101195100",
//            "subberat" => "45000",
//            "subvolume" => "101195100",
//        );
//        $arrData[] = array(
//            "berat" => "59000",
//            "volume" => "162229500",
//            "subberat" => "118000",
//            "subvolume" => "324459000",
//        );

        $total_subvolume = 0;
        $total_subberat = 0;
        foreach ($arrData as $data) {
            $result_volume = conv_mmc_mc($data['volume']);
            $result_berat = conv_g_kg($data['berat']);
            $result_subvolume = conv_mmc_mc($data['subvolume']);
            $result_subberat = conv_g_kg($data['subberat']);

            $total_subvolume += $result_subvolume;
            $total_subberat += $result_subberat;

            cekHitam();
            cekHere("::volume -> $result_volume");
            cekHere("::berat -> $result_berat");
            cekHere("::sub volume new -> $result_subvolume");
            cekHere("::sub berat new -> $result_subberat");

            cekHere("::sub_berat_gross -> " . $data["subberat"]);
            cekHere("::sub_volum_gross -> " . $data["subvolume"]);
            cekHere("::sub_lebar_gross -> " . $data["sub_lebar_gross"]);
            cekHere("::sub_panjang_gross  -> " . $data["sub_panjang_gross"]);
            cekHere("::sub_tinggi_gross  -> " . $data["sub_tinggi_gross"]);
        }
        cekHitam();
        cekMerah("total subvolume -> $total_subvolume");
        cekMerah("total subberat -> $total_subberat");
    }

    function patchLockerKas()
    {
        $this->load->model("Coms/ComRekeningPembantuKas");
        $this->load->model("Mdls/" . "MdlCabang");

        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        $this->db->trans_start();

        if (sizeof($arrCabangs) > 0) {
            foreach ($arrCabangs as $cbID => $cbNama) {

                $m = New ComRekeningPembantuKas();
                $m->addFilter("periode='forever'");
                $m->addFilter("cabang_id='$cbID'");
                $arrHasil = $m->fetchBalances("kas");
                cekHitam("cabang $cbNama<br>" . $this->db->last_query());
//                arrPrint($arrHasil);
                if (sizeof($arrHasil) > 0) {
                    foreach ($arrHasil as $hSpec) {
                        $static['static'] = array(
                            "cabang_id" => $hSpec->cabang_id,
                            "gudang_id" => "0",
                            "state" => "active",
                            "jenis" => "kas",
                            "produk_id" => $hSpec->extern_id,
                            "nama" => $hSpec->extern_nama,
                            "nilai" => $hSpec->debet,
                            "transaksi_id" => "0",
                            "oleh_id" => "0",
                        );

                        $this->load->model("Coms/ComLockerValue");
                        $cm = New ComLockerValue();
                        $cm->pair($static);
                        $cm->exec();
                    }
                }
            }
        }


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }

    function patchItemMutasiProduk()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");

        $rekName = "persediaan produk";

        $cm = New ComRekeningPembantuProduk();
        $tmp = $cm->fetchMoves2($rekName);

        $this->db->trans_start();

        $arrResult = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $k => $tSpec) {
                $harga = 0;
                $harga_avg = 0;
                if (!isset($lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id])) {
                    $lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id] = 0;
                }

                if ($tSpec->debet > 0) {
                    $harga = $tSpec->debet / $tSpec->qty_debet;
                }
                elseif ($tSpec->kredit > 0) {
                    $harga = $tSpec->kredit / $tSpec->qty_kredit;
                }

                if ($tSpec->qty_kredit_akhir > 0) {
                    $harga_avg = $tSpec->kredit_akhir / $tSpec->qty_kredit_akhir;
                }
                elseif ($tSpec->qty_debet_akhir > 0) {
                    $harga_avg = $tSpec->debet_akhir / $tSpec->qty_debet_akhir;
                }
//                if($harga_avg == 0){
//                    $harga_avg = $lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id];
//                }
                $lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id] = $harga_avg;
                $arrResult[$tSpec->id] = array(
                    "harga" => $harga,
                    "harga_avg" => $harga_avg,
                );

            }
        }
//        arrPrint($arrResult);


        if (sizeof($arrResult) > 0) {
            foreach ($arrResult as $id => $vSpec) {
                $cm = New ComRekeningPembantuProduk();
                $tableNames = heReturnTableName($cm->getTableNameMaster(), array($rekName));
//                arrPrint($tableNames);
                $cm->setFilters(array());
                $cm->setTableName($tableNames[$rekName]['mutasi']);
                $dupState = $cm->updateData(array(
                    "id" => $id,
                ), $vSpec) or die("Failed to update tr next-state!");
                cekBiru($this->db->last_query());
            }
        }


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }

    function patchItemMutasiSupplies()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuSupplies");

        $rekName = "persediaan supplies";

        $cm = New ComRekeningPembantuSupplies();
        $tmp = $cm->fetchMoves2($rekName);

        $this->db->trans_start();

        $arrResult = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $k => $tSpec) {
                $harga = 0;
                $harga_avg = 0;
                if (!isset($lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id])) {
                    $lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id] = 0;
                }

                if ($tSpec->debet > 0) {
                    $harga = $tSpec->debet / $tSpec->qty_debet;
                }
                elseif ($tSpec->kredit > 0) {
                    $harga = $tSpec->kredit / $tSpec->qty_kredit;
                }

                if ($tSpec->qty_kredit_akhir > 0) {
                    $harga_avg = $tSpec->kredit_akhir / $tSpec->qty_kredit_akhir;
                }
                elseif ($tSpec->qty_debet_akhir > 0) {
                    $harga_avg = $tSpec->debet_akhir / $tSpec->qty_debet_akhir;
                }
//                if($harga_avg == 0){
//                    $harga_avg = $lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id];
//                }
                $lastAvg[$tSpec->extern_id][$tSpec->cabang_id][$tSpec->gudang_id] = $harga_avg;
                $arrResult[$tSpec->id] = array(
                    "harga" => $harga,
                    "harga_avg" => $harga_avg,
                );

            }
        }
//        arrPrint($arrResult);


        if (sizeof($arrResult) > 0) {
            foreach ($arrResult as $id => $vSpec) {
                $cm = New ComRekeningPembantuSupplies();
                $tableNames = heReturnTableName($cm->getTableNameMaster(), array($rekName));
//                arrPrint($tableNames);
//                cekHitam($tableNames[$rekName]['mutasi']);
                $cm->setFilters(array());
                $cm->setTableName($tableNames[$rekName]['mutasi']);
                $dupState = $cm->updateData(array(
                    "id" => $id,
                ), $vSpec) or die("Failed to update tr next-state!");
                cekBiru($this->db->last_query());
            }
        }


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }

    function cekPayment()
    {

        $trMdl = "MdlTransaksi";
        $cmMdl = "ComRekeningPembantuCustomer";
        $rek = "piutang dagang";
        $cabID = "1";
        $jenisTrTarget = "749";

        $this->load->model("Coms/" . $cmMdl);
        $this->load->model($trMdl);


        // ambil saldo rekening pembantu customer lokal

        $cm = New $cmMdl();
        $cm->addFilter("cabang_id='$cabID'");
        $tmp = $cm->fetchBalances($rek);
//        cekHere($this->db->last_query());
        $arrExternValue = array();
        $arrExternName = array();
        foreach ($tmp as $tmpSpec) {
//            if($tmpSpec->debet > 0){

            $arrExternValue[$tmpSpec->extern_id] = $tmpSpec->debet;
            $arrExternName[$tmpSpec->extern_id] = $tmpSpec->extern_nama;
//            }
        }
//        arrprint($arrCustValue);
//        arrprint($arrExternName);
//        cekHere(count($arrCustValue));


        // ambil data payment source piutang dagang lokal
        $tr = New $trMdl();
        $tr->setFilters(array());
        $tr->addFilter("cabang_id='$cabID'");
        $pyTmp = $tr->lookupPaymentSrcByJenis($jenisTrTarget)->result();
        cekMerah($this->db->last_query());
//        arrPrint($pyTmp);
        $pyExternValue = array();
        foreach ($pyTmp as $pySpec) {
            if (!isset($pyExternValue[$pySpec->extern_id])) {
                $pyExternValue[$pySpec->extern_id] = 0;
            }
            $pyExternValue[$pySpec->extern_id] += $pySpec->sisa;
        }
//        arrPrint($pyCustValue);


        if (sizeof($arrExternValue) > 0) {
            $str = "";
            $str .= "<table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>ID</th>";
            $str .= "<th>nama</th>";
            $str .= "<th>rek</th>";
            $str .= "<th>pySrc</th>";
            $str .= "<th>selisih</th>";
            $str .= "</tr>";

            $rekValueTotal = 0;
            $pyValueTotal = 0;
            foreach ($arrExternValue as $externID => $rekValue) {
                $nama = isset($arrExternName[$externID]) ? $arrExternName[$externID] : "-";
                $pyValue = isset($pyExternValue[$externID]) ? $pyExternValue[$externID] * 1 : "0";
                $rekValue = $rekValue * 1;
                $pyValueSelisih = ($rekValue - $pyValue) * 1;

                $rekValueTotal += $rekValue;
                $pyValueTotal += $pyValue;

                $bgcolor = round($rekValue, 2) != round($pyValue, 2) ? "background-color:yellow;" : "";

                $str .= "<tr style='$bgcolor'>";
                $str .= "<td>$externID</td>";
                $str .= "<td>$nama</td>";
                $str .= "<td style='text-align: right;'>$rekValue</td>";
                $str .= "<td style='text-align: right;'>$pyValue</td>";
                $str .= "<td style='text-align: right;'>$pyValueSelisih</td>";
                $str .= "</tr>";
            }
            $str .= "<tr style='font-weight: bold;'>";
            $str .= "<td>-</td>";
            $str .= "<td>-</td>";
            $str .= "<td style='text-align: right;'>$rekValueTotal</td>";
            $str .= "<td style='text-align: right;'>$pyValueTotal</td>";
            $str .= "<td>-</td>";
            $str .= "</tr>";
            $str .= "</table>";
            echo $str;
        }

    }

    function genPaymentSourceJasa()
    {

//        $jenisTr = "463";
//        $target_jenisTr = "462";

        $jenisTr = "462";
        $target_jenisTr = "115";
        $this->load->model("MdlTransaksi");

        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenisTr'");
        $tr->addFilter("link_id='0'");
        $tmp = $tr->lookupAll()->result();


        $this->db->trans_start();

        if (sizeof($tmp) > 0) {
            $trIDs = array();
            foreach ($tmp as $tmpSpec) {
                $trIDs[] = $tmpSpec->id;
//                break;
            }

            //==== ambil data dari registry main
            $reg = New MdlTransaksi();
            $reg->setTableName($reg->getTableNames()['registry']);
            $reg->setFilters(array());
            $reg->addFilter("trash='0'");
            $reg->addFilter("param='main'");
            $reg->addFilter("transaksi_id in (" . implode(",", $trIDs) . ")");
            $reg->setSortBy(array("kolom" => "id", "mode" => "asc"));
            $tmpReg = $reg->lookupAll()->result();


            $regResult = array();
            foreach ($tmpReg as $tmpRegSpec) {
                $regDecode = blobDecode($tmpRegSpec->values);
                $regResult[$tmpRegSpec->transaksi_id] = array(
                    "dpp" => $regDecode['harga_disc'],
                    "ppn" => $regDecode['ppn'],
//                    "dpp" => $regDecode['extern_nilai2'], numpang nggo setor pph23 ke negara
//                    "ppn" => $regDecode['ppn'],
                );
            }
//            arrPrint($regResult);
            foreach ($regResult as $trID => $rSpec) {
                $tru = New MdlTransaksi();
                $tru->setFilters(array());
                $tru->setTableName($tru->getTableNames()['paymentSrc']);
                $tru->updateData(
                    array(
                        "transaksi_id" => $trID,
                        "jenis" => $jenisTr,
                        "target_jenis" => $target_jenisTr,

                    ), array(
                        "extern_nilai2" => $rSpec['dpp'],
                        "ppn" => $rSpec['ppn'],
                    )
                );
                cekKuning($this->db->last_query());
            }


        }


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<span style='font-size: 20px;'>DONE</span>");
    }

    function genExternData()
    {
        $jenisTr = "462";
        $target_jenisTr = "115";
        $this->load->model("MdlTransaksi");

        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenisTr'");
        $tr->addFilter("link_id='0'");
        $tmp = $tr->lookupAll()->result();


        $this->db->trans_start();

        if (sizeof($tmp) > 0) {
            $trIDs = array();
            foreach ($tmp as $tmpSpec) {
                $trIDs[] = $tmpSpec->id;
//                break;
            }

            //==== ambil data dari registry main
            $reg = New MdlTransaksi();
            $reg->setTableName($reg->getTableNames()['registry']);
            $reg->setFilters(array());
            $reg->addFilter("trash='0'");
            $reg->addFilter("param='main'");
            $reg->addFilter("transaksi_id in (" . implode(",", $trIDs) . ")");
            $reg->setSortBy(array("kolom" => "id", "mode" => "asc"));
            $tmpReg = $reg->lookupAll()->result();

            $regResult = array();
            foreach ($tmpReg as $tmpRegSpec) {
                $regDecode = blobDecode($tmpRegSpec->values);
                $regResult[$tmpRegSpec->transaksi_id] = array(
                    "extern_id" => $regDecode['pihakID'],
                    "extern_nama" => $regDecode['pihakName'],
                );
            }
//            arrPrint($regResult);
//            matiHere();
            foreach ($regResult as $trID => $rSpec) {
                $tru = New MdlTransaksi();
                $tru->setFilters(array());
                $tru->setTableName($tru->getTableNames()['paymentSrc']);
                $tru->updateData(
                    array(
                        "transaksi_id" => $trID,
                        "jenis" => $jenisTr,
                        "target_jenis" => $target_jenisTr,

                    ), array(
                        "extern_id" => $rSpec['extern_id'],
                        "extern_nama" => $rSpec['extern_nama'],
                    )
                );
                cekKuning($this->db->last_query());
            }


        }


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<span style='font-size: 20px;'>DONE</span>");
    }

    function cekFifoAvg()
    {

        $this->load->model("Mdls/MdlFifoAverage");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlFifoSupplies");

        $jenis = "produk";
        $cabangID = "1";
        $gudangID = "-10";

        $f = New MdlFifoAverage();
        $f->addFilter("jenis='$jenis'");
        $f->addFilter("gudang_id='$gudangID'");
        $f->addFilter("cabang_id='$cabangID'");
        $tmp = $f->lookupAll()->result();
//        cekHere(count($tmp));

        $arrTmp = array();
        $arrTmpDobel = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
//                arrPrint($tmpSpec);
//                break;
                $arrTmp[$tmpSpec->produk_id][] = array(
                    "pID" => $tmpSpec->produk_id,
                    "nama" => $tmpSpec->nama,
                    "qty" => $tmpSpec->jml,
                    "avg" => $tmpSpec->hpp,
                    "value" => $tmpSpec->jml_nilai,
                );
            }

            foreach ($arrTmp as $pID => $spec) {
                if (sizeof($spec) > 1) {
                    $arrTmpDobel[$pID] = $spec;
                }
            }
//            arrPrint($arrTmpDobel);
        }


        $fo = New MdlFifoProdukJadi();
//        $fo->addFilter("jenis='$jenis'");
        $fo->addFilter("gudang_id='$gudangID'");
        $fo->addFilter("cabang_id='$cabangID'");
        $fo->addFilter("unit>0");
        $tmp = $fo->lookupAll()->result();
        $arrTmpAkm = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
                if (!isset($arrTmpAkm[$tmpSpec->produk_id]['jml'])) {
                    $arrTmpAkm[$tmpSpec->produk_id]['jml'] = 0;
                }
                if (!isset($arrTmpAkm[$tmpSpec->produk_id]['jml_nilai'])) {
                    $arrTmpAkm[$tmpSpec->produk_id]['jml_nilai'] = 0;
                }
                $arrTmpAkm[$tmpSpec->produk_id]['jml'] += $tmpSpec->unit;
                $arrTmpAkm[$tmpSpec->produk_id]['jml_nilai'] += $tmpSpec->jml_nilai;
            }
            foreach ($arrTmpAkm as $pID => $spec) {
                $avg = $spec['jml_nilai'] / $spec['jml'];

                $arrTmpAkm[$pID]['avg'] = $avg;
            }
            arrPrint($arrTmpAkm);
        }


        $this->db->trans_start();


        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<span style='font-size: 20px;'>DONE</span>");

    }

    function cekSetoran()
    {
        $this->load->model("MdlTransaksi");

        $cab = "1";

        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='759r'");
        $tr->addFilter("cabang_id='$cab'");
        $tr->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $tmp = $tr->lookupAll()->result();
        $arrTrIDs = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
                $arrTrIDs[] = $tmpSpec->id;
            }
        }
        if (sizeof($arrTrIDs) > 0) {
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("param in ('main', 'item')");
            $tr->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $tr->setTableName($tr->getTableNames()['registry']);
            $tr->setSortBy(array("kolom" => "id", "mode" => "asc"));
            $tmpReg = $tr->lookupAll()->result();
//            cekHere($this->db->last_query());
//            arrPrint($tmpReg);
            $resultReg = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regSpec) {
                    $resultReg[$regSpec->transaksi_id][$regSpec->param] = blobDecode($regSpec->values);
                }
            }
            if (sizeof($resultReg) > 0) {
//                arrPrint($resultReg);
                $setoran = array();
                foreach ($resultReg as $trID => $regSpec) {

                    $detail = "";
                    if (sizeof($regSpec['items']) > 0) {
                        foreach ($regSpec['items'] as $dSpec) {
                            $detail .= $dSpec['nama'] . " :: ";
                            $detail .= $dSpec['nilai_bayar'] . "<br>";

                            if (!isset($akumulasi[$trID])) {
                                $akumulasi[$trID] = 0;
                            }
                            $akumulasi[$trID] += $dSpec['nilai_bayar'];
                        }
                    }

                    $setoran[$trID] = array(
                        "nomor" => $regSpec['main']['nomer'],
                        "dtime" => $regSpec['main']['dtime'],
                        "main_nilai" => $regSpec['main']['nilai_entry'],
                        "detail_nilai" => $detail,
                    );
                }
            }
            if (sizeof($setoran) > 0) {

                $str = "<table rules='all' style='border:1px solid black;'>";
                $str .= "<tr>";
                $str .= "<th>trID</th>";
                $str .= "<th>date time</th>";
                $str .= "<th>nomor setoran</th>";
                $str .= "<th>main setoran</th>";
                $str .= "<th>detail setoran</th>";
                $str .= "<th>detail akumulasi</th>";
                $str .= "</tr>";
                foreach ($setoran as $trID => $spec) {

                    $bgcolor = "";
                    if ($spec['main_nilai'] != $akumulasi[$trID]) {
                        $bgcolor = "background-color:yellow;";
                    }

                    $str .= "<tr style='height: 35px;vertical-align: top;$bgcolor'>";
                    $str .= "<td> " . $trID . " </td>";
                    $str .= "<td> " . $spec['dtime'] . " </td>";
                    $str .= "<td> " . $spec['nomor'] . " </td>";
                    $str .= "<td> " . $spec['main_nilai'] . " </td>";
                    $str .= "<td> " . $spec['detail_nilai'] . " </td>";
                    $str .= "<td> " . $akumulasi[$trID] . " </td>";
                    $str .= "</tr>";
                }
                $str .= "</table>";
                echo $str;
            }
        }
    }

    function patchValueCancel()
    {

        $jenis = "9911";
        $this->load->model("MdlTransaksi");


        $arrTrCode = array(
            "462" => "nilai_entry",
            "463" => "nett",
            "444" => "harga",
            "119" => "harga",
            "2119" => "nilai_entry",
            "113" => "ppn",
            "118" => "harga",
            "117" => "harga",
            "1771" => "nilai_entry",
            "9983" => "harga_disc",
            "9982" => "harga_disc",
            "9984" => "harga_disc",
            "652" => "nilai_entry",
            "758" => "nilai_entry",
            "475" => "nilai_entry",
            "477" => "nilai_entry",
            "3585" => "nilai_entry",
            "1118" => "hpp",
            "762" => "hpp",
            "743" => "harga",
        );


        $tr = New MdlTransaksi();
        $tr->addFilter("jenis=$jenis");
        $trTmp = $tr->lookupAll()->result();
//        cekHere($this->db->last_query());
        if (sizeof($trTmp) > 0) {
            $trIDs = array();
            foreach ($trTmp as $tmpSpec) {
                $trIDs[] = $tmpSpec->id;
            }
            if (sizeof($trIDs) > 0) {
                $reg = New MdlTransaksi();
                $reg->setFilters(array());
                $reg->addFilter("param='main'");
                $reg->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
                $regTmp = $reg->lookupRegistries()->result();
//                cekHere($this->db->last_query());

                $regMain = array();
                foreach ($regTmp as $regSpec) {
//                    arrPrint($regSpec);
                    $regMain[$regSpec->transaksi_id] = blobDecode($regSpec->values);
                }
//                arrPrint($regMain);

                $arrUpdateData = array();
                foreach ($regMain as $trID => $spec) {

                    $jenisExtern = $spec['pihakExternID'];
                    $value = isset($arrTrCode[$jenisExtern]) ? $spec[$arrTrCode[$jenisExtern]] : 0;
                    $hasil = array(
                        "transaksi_nilai" => $value,
                    );
                    $arrUpdateData[$trID] = $hasil;
                }
                arrPrint($arrUpdateData);


//                $this->db->trans_start();
//
//
//
//                if(sizeof($arrUpdateData) > 0){
//                    foreach ($arrUpdateData as $trID => $updateData){
//
//                    }
//                }
//
//
//                mati_disini();
//                $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            }
        }
        else {
            cekMerah("TIDAK ADA DATA");
        }

    }

    function cekBiaya()
    {

        $this->load->model("MdlTransaksi");

        $jenisTr = "463";
        $sinkron = 0;
        $trash4 = 0;
        $header = array(
            "dtime" => "tanggal",
            "suppliers_id" => "vendor ID",
            "suppliers_nama" => "vendor Nama",
            "nomer" => "nomer SRN",
            "harga" => "harga",
            "ppn" => "ppn",
            "tagihan" => "subtotal",
        );

        $mdl = New MdlTransaksi();
        $mdl->setFilters(array());
        $mdl->addFilter("sinkron=$sinkron");
        $mdl->addFilter("jenis=$jenisTr");
        $mdl->addFilter("trash_4=$trash4");
        $trTmp = $mdl->lookupAll()->result();

        if (sizeof($trTmp) > 0) {
            $arrTrID = array();
            foreach ($trTmp as $spec) {
                $arrTrID[] = $spec->id;
            }

            $mdlReg = New MdlTransaksi();
            $mdlReg->setFilters(array());
            $mdlReg->addFilter("param='main'");
            $mdlReg->addFilter("transaksi_id in ('" . implode("','", $arrTrID) . "')");
            $regTmp = $mdlReg->lookupRegistries()->result();
            $arrReg = array();
            foreach ($regTmp as $spec) {
                $arrReg[$spec->transaksi_id] = blobDecode($spec->values);
            }
//            arrPrint($arrReg);


            $arrDatas = array();
            foreach ($trTmp as $spec) {
                $tmp = array();
                foreach ($header as $key => $val) {
                    $tmp[$key] = isset($spec->$key) ? $spec->$key : $arrReg[$spec->id][$key];
                }

                $arrDatas[$spec->id] = $tmp;
            }


//            arrPrint($arrDatas);
        }


        if (sizeof($arrDatas) > 0) {
            $str = "<table rules='all' style='border: 1px solid black;width:100%;'>";
            $str .= "<tr>";
            foreach ($header as $key => $val) {
                $str .= "<td>$val</td>";
            }
            $str .= "</tr>";
            foreach ($arrDatas as $trID => $dSpec) {
                $str .= "<tr>";
                foreach ($header as $key => $val) {
                    $str .= "<td>" . formatField($key, $dSpec[$key]) . "</td>";
                    if (is_numeric($dSpec[$key])) {
                        if (!isset($total[$key])) {
                            $total[$key] = 0;
                        }
                        $total[$key] += $dSpec[$key];
                    }
                }
                $str .= "</tr>";
            }
            $str .= "<tr>";
            foreach ($header as $key => $val) {
                if (isset($total[$key])) {
                    $str .= "<td>" . formatField($key, $total[$key]) . "</td>";
                }
                else {
                    $str .= "<td></td>";
                }
            }
            $str .= "</tr>";
            $str .= "</table>";
            echo $str;
        }
    }

    function kirimemail()
    {
        $this->load->library("SmtpMailer");
        // $mail = new MdlMailNotif();
        $mail = new SmtpMailer();
        $nama = "thomas";
        $email = "namakamoe@gmail.com";
        $strEmail = dtimeNow() . " cuman test boss";
        $mail->setSubject("Reset Password");
        $mail->setAddressTo(array($nama => $email));
        $mail->setAddressFrom(array($_SERVER['HTTP_HOST'] => "mgkcore@gmail.com"));
        $cek = $mail->kirim_email($strEmail);
    }

    //-----------------------------------------
    function tesKonversi()
    {
        $cCode = "_TR_" . "1339";
        $itemsTarget = array(
            "907" => array(
                "id" => "907",
                "jml" => "1",
                "qty" => "1",
                "qty_spec" => "2",
                "nama" => "KABEL EXTRANA 3 X 2,5 2 M",
                "name" => "KABEL EXTRANA 3 X 2,5 2 M",
            ),
            "911" => array(
                "id" => "911",
                "jml" => "1",
                "qty" => "1",
                "qty_spec" => "1",
                "nama" => "KABEL EXTRANA 3 X 2,5 1 M",
                "name" => "KABEL EXTRANA 3 X 2,5 1 M",
            ),
        );
        $items = $_SESSION[$cCode]["items"];
        foreach ($items as $pid => $specItems) {
            $_SESSION[$cCode]["items"][$pid]["qty_spec"] = "3";
            $_SESSION[$cCode]["items"][$pid]["hpp"] = "45000";
            $_SESSION[$cCode]["items"][$pid]["harga"] = "45000";
            $_SESSION[$cCode]["items"][$pid]["sub_hpp"] = "45000";
            $_SESSION[$cCode]["items"][$pid]["sub_harga"] = "45000";
        }


        foreach ($_SESSION[$cCode]["items4"] as $pid => $subSpec) {
            foreach ($itemsTarget as $subpid => $subspec) {
                $_SESSION[$cCode]["items4"][$pid][$subpid] = $_SESSION[$cCode]["items"][$pid];
                foreach ($subspec as $k => $v) {
                    $_SESSION[$cCode]["items4"][$pid][$subpid][$k] = $v;
                }
            }
        }


        $this->db->trans_begin();

        $this->load->model("Preprocs/PreProdukKonversiHitung");
        $pp = New PreProdukKonversiHitung();
        $preData = array(
            "static" => array(
                "cabang_id" => $_SESSION[$cCode]["main"]["placeID"],
                "gudang_id" => $_SESSION[$cCode]["main"]["gudangID"],
                "jenisTr" => $_SESSION[$cCode]["main"]["jenisTrMaster"],
                "target" => "items4_sum",
            ),
        );
        $pp->pair("", $preData);
        $pp->exec();
//        arrPrintPink($_SESSION[$cCode]["items4"]);


        $this->load->model("Preprocs/PreProdukKonversi");
        $pp = New PreProdukKonversi();
        $preData = array(
            "static" => array(
                "cabang_id" => $_SESSION[$cCode]["main"]["placeID"],
                "gudang_id" => $_SESSION[$cCode]["main"]["gudangID"],
                "jenisTr" => $_SESSION[$cCode]["main"]["jenisTrMaster"],
                "target" => "items4_sum",
            ),
        );
        $pp->pair("", $preData);
        $pp->exec();
        arrPrintPink($_SESSION[$cCode]["items4_sum"]);


        $this->load->model("Coms/ComRekeningPembantuProduk");
        $pp = New ComRekeningPembantuProduk();
        foreach ($_SESSION[$cCode]["items"] as $pid => $spec) {
            $comData[$pid] = array(
                "loop" => array(
                    "1010030030" => -$spec["sub_hpp"],// persediaan produk
                ),
                "static" => array(
                    "cabang_id" => $spec["placeID"],
                    "extern_id" => $spec["id"],
                    "extern_nama" => $spec["nama"],
                    "produk_qty" => -$spec["qty"],
                    "produk_nilai" => $spec["hpp"],
                    "gudang_id" => $spec["gudangID"],
                    "jenis" => $spec["jenisTr"],
                    "transaksi_no" => $spec["nomer"],
                ),
            );
        }
        $pp->pair($comData);
        $pp->exec();
//mati_disini(__LINE__);

        $this->load->model("Coms/ComRekeningPembantuProduk");
        $pp = New ComRekeningPembantuProduk();
        foreach ($_SESSION[$cCode]["items4_sum"] as $pid => $spec) {
            $comDatas[$pid] = array(
                "loop" => array(
                    "1010030030" => $spec["hpp_spec_qty"],// persediaan produk
                ),
                "static" => array(
                    "cabang_id" => $spec["placeID"],
                    "extern_id" => $spec["id"],
                    "extern_nama" => $spec["nama"],
                    "produk_qty" => $spec["jml"],
                    "produk_nilai" => $spec["hpp_spec"],
                    "gudang_id" => $spec["gudangID"],
                    "jenis" => $spec["jenisTr"],
                    "transaksi_no" => $spec["nomer"],
                ),
            );
        }
        $pp->pair($comDatas);
        $pp->exec();


        mati_disini("SETOOPPP.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }


    }

    function cekPembatalanPL()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $rekening = "1010030030";
        $cpp = New ComRekeningPembantuProduk();
        $cps = New ComRekeningPembantuProdukPerSerial();

        $transaksiIDs = array(
            96236,
            95132,
            89294,
            89262,
            84498,
            80466,
            78359,
            78355,
            77289,
            76609,
            75251,
            75085,
            74233,
            73069,
            68447,
            65939,
            59205,
            59203,
            59095,
            56419,
            56397,
            56395,
            55342,
            51468,
            41003,
            37178,
            29731,
            29549,
            7045,

        );
        $transaksiIDs = array(
            98368,
            79067,
            73128,
            73071,
            55428,
            39204,
            31081,
            23527,
            21208,
            8850,
            //---
//            81082,
        );

        $dataMutasiProduk = array();
        $dataMutasiSerial = array();
        //-------------------------------
        $cpp->addFilter("transaksi_id in ('" . implode("','", $transaksiIDs) . "')");
        $tmp = $cpp->fetchMoves2($rekening);
        showLast_query("biru");
        foreach ($tmp as $spec) {
            $dataMutasiProduk[$spec->transaksi_id][] = $spec;
        }
        //-------------------------------
        $cps->addFilter("transaksi_id in ('" . implode("','", $transaksiIDs) . "')");
        $tmp = $cps->fetchMoves2($rekening);
        showLast_query("biru");
        foreach ($tmp as $spec) {
            $dataMutasiSerial[$spec->transaksi_id][] = $spec;
        }
        //-------------------------------


        cekHitam("HAHAHA, tidak ada di mutasi produk");
        foreach ($transaksiIDs as $trid) {
            if (!isset($dataMutasiProduk[$trid])) {
                cekKuning("$trid");
            }
        }

//        cekHitam("HAHAHA, tidak ada di mutasi serial");
//        foreach ($transaksiIDs as $trid) {
//            if (!isset($dataMutasiSerial[$trid])) {
//                cekPink("$trid");
//            }
//        }
//

    }

    public function run_cliTransaksi()
    {
//        header("refresh:2");
//        mati_disini();
        $this->load->helper("he_mass_table");
        $startDate = dtimeNow();


        $getTrID = (isset($_GET['tr_id']) && ($_GET['tr_id'] > 0)) ? $_GET['tr_id'] : 0;
        $addJudul = "";

        $tr = New MdlTransaksi();
        $tr->setSortBy(
            array(
                "kolom" => "id",
                "mode" => "ASC",
            )
        );
        $this->db->limit(1);
//        $tr->addFilter("cli='0'");
        $tr->addFilter("id='51468'");
        // bila ada trID dari URL, maka ini adalah cek manual, tidak boleh close commit !!!
        if ($getTrID > 0) {
            $tr->addFilter("id='$getTrID'");

            $addJudul = "<br>cek manual";
        }
        $trTmp = $tr->lookupAll()->result();
        cekHere($this->db->last_query() . "<br>" . sizeof($trTmp));

        if (sizeof($trTmp) > 0) {
            $trID_cli = $trTmp[0]->id;
            $trTmpCabangID = $trTmp[0]->cabang_id;
            $kolom = array(
                "trID" => "id",
                "jenisTr" => "jenis",
                "jenisTrMaster" => "jenis_master",
                "jenisTrTop" => "jenis_top",
                "nomer" => "nomer",
                "nomerTop" => "nomer_top",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "stepNumber" => "step_number",
                "indexRegistry" => "indexing_registry",
                "olehID" => "oleh_id",
                "olehNama" => "oleh_nama",
            );

            $arrKolomTrans = array();
            foreach ($kolom as $key => $val) {
                $arrKolomTrans[$key] = isset($trTmp[0]->$val) ? $trTmp[0]->$val : NULL;
            }

            $reg = New MdlTransaksi();
            $key = "indexRegistry";
            $index_reg = blobDecode($arrKolomTrans[$key]);
            $reg->setFilters(array());
//            $reg->addFilter("id in ('" . implode("','", $index_reg) . "')");
            $reg->addFilter("transaksi_id='" . $trTmp[0]->id . "'");
            $regTmp = $reg->lookupDataRegistries()->result();
            $registryGates = array();
            foreach ($regTmp as $regSpec) {
                foreach ($regSpec as $key_reg => $val_reg) {
                    if ($key_reg != "transaksi_id") {
                        $registryGates[$key_reg] = blobDecode($val_reg);
                    }
                }
            }

//cekHitam(":: cetak REGISTRY ::");
            arrPrintWebs($registryGates["items8_sum"]);
//mati_disini();
//            arrprint($arrKolomTrans);
//            arrPrint($registryGates["items"]);
//             mati_disini();
            $jenisTr = $arrKolomTrans['jenisTr'];
            $jenisTrMaster = $arrKolomTrans['jenisTrMaster'];
            $fulldate = $arrKolomTrans['fulldate'];
            $dtime = $arrKolomTrans['dtime'];
            $stepNumber = $arrKolomTrans['stepNumber'];
            $insertNum = $tmpNomorNota = $arrKolomTrans['nomer'];
            $olehNama = $arrKolomTrans['olehNama'];
            $insertID = $transaksiID = $arrKolomTrans['trID'];
            /*---------------------- jenismaster untuk gerbang utama masuk modul, jenisTr adalah targetnya */
            /*------end*/
            $configCore = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiCore");
            $configUi = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiUi");
            $configLayout = loadConfigModulJenis_he_misc($jenisTrMaster, "coTransaksiLayout");

            cekHitam(":: jenisTrMaster-> $jenisTrMaster :: jenisTr-> $jenisTr ::");


            //region BUILD TABEL DATABASE OTOMATIS
            $cliComponent = "components";
            $buildTablesDetail = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
//arrPrintWebs($buildTablesDetail);
            if (sizeof($buildTablesDetail) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
//arrPrintWebs($buildTablesDetail_specs);
                    $buildTablesDetail_specs_result = $buildTablesDetail_specs;
                    $srcGateName = $buildTablesDetail_specs['srcGateName'];
                    $srcRawGateName = $buildTablesDetail_specs['srcRawGateName'];
//                    cekHitam(__LINE__ . ":: $srcGateName");
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                        foreach ($registryGates[$srcGateName] as $itemSpec) {

//                            arrPrintWebs($itemSpec);
                            $mdlName = $buildTablesDetail_specs['comName'];
//                            cekBiru("== $srcGateName == $mdlName ==");
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }

//cekBiru("== $mdlName ==");
                            if (isset($buildTablesDetail_specs['loop'])) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
//cekKuning(":: $key => $val ::");
                                    unset($buildTablesDetail_specs_result['loop']);
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                    }
                                    $buildTablesDetail_specs_result['loop'][$key] = $val;
//                                cekHitam("LINE: " . __LINE__ . " ::sini bukan??  akan build tabel detail $key");
                                }
                            }

//arrPrintWebs($buildTablesDetail_specs_result['loop']);
//                        cekHere($mdlName . " == " . $srcGateName);
                            $mdlName = "Com" . $mdlName;
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
//                                cekMerah(":: $mdlName ::");
//                                arrPrintWebs($buildTablesDetail_specs_result);
                                    $m->buildTables($buildTablesDetail_specs_result);
                                }
                            }
                        }

                    }
                    else {
//                        cekHere("TESTSTST");
                    }
                }
            }
            else {
                cekMerah(":: TIDAK ADA CONFIG cliComponent");
            }
            //endregion


            $this->db->trans_start();


            //region ----------subcomponents by cli
            //<editor-fold desc="----------subcomponents by cli">
            $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
            $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
            $validateSubComponent = $this->config->item('heTransaksi_validateComponentDetail') != null ? $this->config->item('heTransaksi_validateComponentDetail') : array();

            $componentGate['detail'] = array();
            $componentConfig['master'] = array();
            $componentConfig['detail'] = array();
            if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                $iterator = isset($registryGates['revert']['jurnal']['detail']) ? $registryGates['revert']['jurnal']['detail'] : array();
                $revertedTarget = $registryGates['main']['pihakExternID'];
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($configCore[$cliComponent][$jenisTr]['detail']) ? $configCore[$cliComponent][$jenisTr]['detail'] : array();
                $componentConfig['detail'] = $iterator;
                $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();

                $revertedTarget = "";

            }

            //----------------
            $iterator = array(
                array(
                    "comName" => "RekeningPembantuProduk",
                    "loop" => array(
                        "1010030030" => "sub_hpp",// persediaan produk
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "jml",
                        "produk_nilai" => "hpp",
                        "gudang_id" => "gudangID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                        "kategori_id" => "kategori_id",//ini untuk skip jika produk jasa
                        "keterangan" => "",
                    ),
                    "srcGateName" => "items",// barang dari reguler
                    "srcRawGateName" => "items",// barang dari reguler
                ),
            );
            //----------------
//arrPrintKuning($iterator);
//arrPrintWebs($registryGates["items9_sum"]);
//mati_disini(__LINE__);
            $subComModel = array();
            if (sizeof($iterator) > 0) {
//                arrPrintKuning($iterator);
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;

                $arrRekeningLoop = array();

//                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
//                    $filterNeeded = true;
//                }
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName_orig = $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                    $srcRawGateName = $tComSpec['srcRawGateName'];

                    echo "sub-component: $comName, $srcGateName, initializing values <br>";

                    $tmpOutParams[$cCtr] = array();
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {

                        foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                            $comName = $comName_orig;
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                $tComSpec['comName'] = $comName;
                                $iterator[$cCtr]['comName'] = $comName;
                            }
//                        $subComModel[$comName] = $comName;
                            $filterNeeded = false;
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }


                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $registryGates[$srcGateName][$id][$key], $key);
                                    }

                                    $subComModel[$key] = $comName;

                                    $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);

                                    if (strlen($key) > 1) {
                                        $subParams['loop'][$key] = $realValue;
                                    }
                                    else {
                                        $subParams['loop'] = array();
                                    }

                                    // =================== =================== ===================
                                    if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                        $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                    }
                                    $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                    if ($realValue != 0) {
                                        cekUngu(":: cetak loop $key => $realValue ::");
                                    }

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);

                                            // =================== =================== ===================
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $registryGates[$srcGateName][$id], $registryGates[$srcGateName][$id], 0);
//                                    $subParams['static'][$key] = $realValue;
                                    $subParams['static'][$key] = trim($realValue);
//                                cekKuning("STATIC: $key diisi dengan $realValue");
                                }
                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {

                                    $jenis = $registryGates['main']['jenis'];
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                    }
                                }
                                $subParams['static']["fulldate"] = $fulldate;
                                $subParams['static']["dtime"] = $dtime;
                                $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                //------
                                $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                //------
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            if (sizeof($subParams) > 0) {
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    if (empty($subParams['loop']) && $loopRequire == true) {
                                        unset($tmpOutParams[$cCtr]);
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                }
//                arrPrintKuning($tmpOutParams);
                $it = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                        foreach ($registryGates[$srcGateName] as $id => $dSpec) {
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//
//
                            }
                        }
                    }
                    else {
                        $comName = NULL;
                    }
                    cekHere("::::: $comName :::::");


                    echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                    if ($comName != NULL) {
//cekHere(":: $comName ::");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }

                cekMerah("HAHAHA");
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    // region baca jurnal rekening besar
                    $jn = New ComJurnal();
                    $jn->addFilter("transaksi_id='$transaksiID'");
                    $jnTmp = $jn->lookupAll()->result();
//                    arrPrint($jnTmp);
                    $arrJurnal = array();
                    if (sizeof($jnTmp) > 0) {
                        foreach ($jnTmp as $ii => $spec) {
                            $defPosition = detectRekDefaultPosition($spec->rekening);
                            switch ($defPosition) {
                                case "debet":
                                    $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->debet > 0 ? $spec->debet : $spec->kredit * -1;
                                    break;
                                case "kredit":
                                    $arrJurnal[$spec->cabang_id][$spec->rekening] = $spec->kredit > 0 ? $spec->kredit : $spec->debet * -1;
                                    break;
                                default:
                                    mati_disini("tidak menemukan default posisi rekening...");
                                    break;
                            }
                        }
                    }
                    // endregion

                    cekHere("cetak array jurnal");
                    arrPrint($arrJurnal);

                    cekHere("cetak rek loop");
                    arrPrint($arrRekeningLoop);


                    if (sizeof($arrJurnal) > 0) {
                        if (sizeof($arrRekeningLoop) > 0) {
                            foreach ($arrRekeningLoop as $cabang_id => $loopSpec) {
                                foreach ($loopSpec as $rekening => $rekValue) {
                                    if (array_key_exists($rekening, $arrJurnal[$cabang_id])) {
                                        if (floor($rekValue) != floor($arrJurnal[$cabang_id][$rekening])) {
                                            mati_disini("nilai $rekening, jurnal: " . floor($arrJurnal[$cabang_id][$rekening]) . ", akumulasi pembantu: " . floor($rekValue));
                                        }
                                        else {
                                            cekHijau(":: COCOK ::");
                                        }
                                    }
                                }
                            }
                        }
                    }


                }


                // validasi rekening besar vs rekening pembantu
                validateBalancesComparison($trTmpCabangID, $componentGate, $componentConfig, "detail", $transaksiID, $tmpNomorNota);

            }
            else {
                cekMerah("subcomponents [detail] is not set");
            }
            //endregion
//            mati_disini(__LINE__);

            //region subcomponent subdetail multi dimensional array/2 level array
            /*
             * contoh items6,items2 dll
             */
            $componentGate['sub_detail'] = array();
            $componentConfig['sub_detail'] = array();
            if (isset($configCore['relativeComponets']) && $configCore['relativeComponets'] == true) {
                $iterator = isset($registryGates['revert']['jurnal']['sub_detail']) ? $registryGates['revert']['jurnal']['sub_detail'] : array();
                $revertedTarget = $registryGates['main']['pihakExternID'];
                $componentConfig['sub_detail'] = $iterator;
                $componentConfig['master'] = isset($registryGates['revert']['jurnal']['master']) ? $registryGates['revert']['jurnal']['master'] : array();
//                cekHItam("masuk atas");
            }
            else {
                $iterator = isset($configCore[$cliComponent][$jenisTr]['sub_detail']) ? $configCore[$cliComponent][$jenisTr]['sub_detail'] : array();
                $componentConfig['sub_detail'] = $iterator;
                $componentConfig['master'] = isset($configCore[$cliComponent][$jenisTr]['master']) ? $configCore[$cliComponent][$jenisTr]['master'] : array();
                $revertedTarget = "";
//                cekHItam("masuk bawah");
            }
//            arrPrintKuning($iterator);
//            cekHitam("[$jenisTr]");
//            arrPrintKuning($configCore[$cliComponent][$jenisTr]);
//            mati_disini(__LINE__);

            $subComModel = array();
            if (sizeof($iterator) > 0) {
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;

                $arrRekeningLoop = array();

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName_orig = $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $loopRequire = isset($tComSpec['loopRequire']) ? $tComSpec['loopRequire'] : false;
                    $srcRawGateName = $tComSpec['srcRawGateName'];

                    echo "sub-component: $comName, $srcGateName, initializing values <br>";

                    $tmpOutParams[$cCtr] = array();
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {

                        foreach ($registryGates[$srcGateName] as $id => $ddSpec) {
                            foreach ($ddSpec as $dID => $dSpec) {
                                $comName = $comName_orig;
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$comName], $comName);
                                    $tComSpec['comName'] = $comName;
                                    $iterator[$cCtr]['comName'] = $comName;
                                }
                                $filterNeeded = false;
                                $mdlName = "Com" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }


                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $registryGates[$srcGateName][$id][$dID][$key], $key);
                                        }

                                        $subComModel[$key] = $comName;

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id][$dID], $registryGates[$srcGateName][$id][$dID], 0);

                                        if (strlen($key) > 1) {
                                            $subParams['loop'][$key] = $realValue;
                                        }
                                        else {
                                            $subParams['loop'] = array();
                                        }

                                        // =================== =================== ===================
                                        if (!isset($arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key])) {
                                            $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] = 0;
                                        }
                                        $arrRekeningLoop[$dSpec[$tComSpec['static']['cabang_id']]][$key] += $realValue;
                                        if ($realValue != 0) {
                                            cekUngu(":: cetak loop $key => $realValue ::");
                                        }

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);

                                                // =================== =================== ===================
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $registryGates[$srcGateName][$id][$dID], $registryGates[$srcGateName][$id][$dID], 0);
                                        $subParams['static'][$key] = $realValue;
//                                cekKuning("STATIC: $key diisi dengan $realValue");
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {

                                        $jenis = $registryGates['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    $subParams['static']["keterangan"] = $configUi['steps'][$stepNumber]['label'] . " nomor " . $tmpNomorNota . " oleh " . $olehNama;
                                    //------
                                    $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                    $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                    $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                    $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                    $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                    $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                    //------
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }
                                if (sizeof($subParams) > 0) {
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && !empty($subParams['loop'])) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        if (empty($subParams['loop']) && $loopRequire == true) {
                                            unset($tmpOutParams[$cCtr]);
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                }

                            }
                            $componentGate['sub_detail'][$cCtr] = $subParams;
                        }


                    }

                }
                $it = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($registryGates[$srcGateName]) && sizeof($registryGates[$srcGateName]) > 0) {
                        foreach ($registryGates[$srcGateName] as $id => $ddSpec) {
                            foreach ($ddSpec as $ixx => $dSpec) {
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $registryGates[$srcGateName][$id][$ixx][$comName], $comName);
//                            $tComSpec['comName'] = $comName;
//                            $iterator[$cCtr]['comName'] = $comName;
//
//
                                }
                            }

                        }
                    }
                    else {
                        $comName = NULL;
                    }
                    cekHere("::::: $comName :::::");


                    echo __LINE__ . " sub $cCtr component #$it: $comName, sending values**** <br>";

                    if ($comName != NULL) {
//                        arrprintWebs($tmpOutParams);
                        cekHere(":: $comName ::");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        if (isset($tmpOutParams[$cCtr]) && sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $jenisTrMaster . "/" . __FUNCTION__ . "/" . __LINE__);
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }
                // validasi rekening besar vs rekening pembantu
                validateBalancesComparison($trTmpCabangID, $componentGate, $componentConfig, "detail", $transaksiID, $tmpNomorNota);

            }
            else {
                cekMerah("subcomponents [sub_detail] is not set");
            }
            //endregion

            //---VALIDASI QTY/JML, OUT/IN
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                if (sizeof($validateSubComponent) > 0) {
//                cekKuning($validateSubComponent);
//                cekPink($subComModel);
                    if (sizeof($subComModel) > 0) {

                        if (isset($validateSubComponent['enabled']) && ($validateSubComponent['enabled'] == true)) {

                            $arrRekeningItems = array();
                            if (!in_array($jenisTrMaster, $validateSubComponent['jenisTrException'])) {

                                $qtyValidate = false;
                                foreach ($subComModel as $rek => $subCom) {
                                    if (in_array($subCom, $validateSubComponent['subComponent']['detail'])) {
                                        $subComs = "Com" . $subCom;
                                        $this->load->model("Coms/" . $subComs);
                                        $md = New $subComs();
                                        $tbl = $md->getTableNameMaster()['mutasi'];
                                        $tblName = "_" . $tbl . "__" . str_replace(" ", "_", $rek);
                                        $md->setTableName($tblName);
                                        $md->addFilter("transaksi_id='$transaksiID'");
                                        $mdTmp = $md->lookupAll()->result();
                                        showLast_query("biru");
//                                cekHijau($mdTmp);
                                        if (sizeof($mdTmp) > 0) {
                                            foreach ($mdTmp as $mdSpec) {
                                                $arrRekeningItems[$mdSpec->extern_id]['nama'] = $mdSpec->extern_nama;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_debet'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_debet'] += $mdSpec->qty_debet;

                                                if (!isset($arrRekeningItems[$mdSpec->extern_id]['jml_kredit'])) {
                                                    $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] = 0;
                                                }
                                                $arrRekeningItems[$mdSpec->extern_id]['jml_kredit'] += $mdSpec->qty_kredit;
                                            }
                                        }

                                        $qtyValidate = true;
                                    }
                                }


                                $arrRequestItems = array();
                                if (isset($registryGates['items']) && (sizeof($registryGates['items']) > 0)) {
                                    foreach ($registryGates['items'] as $pID => $iSpec) {
                                        $arrRequestItems[$pID]['nama'] = $iSpec['nama'];
                                        $arrRequestItems[$pID]['jml'] = $iSpec['jml'];

                                    }
                                }

                                if (count($arrRequestItems) != count($arrRekeningItems)) {
                                    // STOP
                                    $msg = "Jumlah item request " . sizeof($arrRequestItems) . " tidak sama dengan jumlah masuk rekening " . sizeof($arrRekeningItems) . " line " . __LINE__;
                                    mati_disini($msg);
                                }
                                else {
                                    cekHijau("request " . count($arrRequestItems) . ", rekening " . count($arrRekeningItems));
                                    foreach ($arrRequestItems as $pID => $spec) {
                                        $req_nama = $spec['nama'];
                                        $req_jml = $spec['jml'];
                                        $rek_jml = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : $arrRekeningItems[$pID]['jml_kredit'];
                                        $rek_jml_debet = (isset($arrRekeningItems[$pID]['jml_debet']) && ($arrRekeningItems[$pID]['jml_debet'] > 0)) ? $arrRekeningItems[$pID]['jml_debet'] : 0;
                                        $rek_jml_kredit = (isset($arrRekeningItems[$pID]['jml_kredit']) && ($arrRekeningItems[$pID]['jml_kredit'] > 0)) ? $arrRekeningItems[$pID]['jml_kredit'] : 0;

                                        if (in_array($jenisTrMaster, $validateSubComponent['dobleValidate'])) {
                                            cekBiru("cek request vs rekDebet dan request vs reqKredit");
                                            // request vs rek qty debet
                                            if ($req_jml != $rek_jml_debet) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_debet";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyDebet $rek_jml_debet");
                                            }
                                            // request vs rek qty kredit
                                            if ($req_jml != $rek_jml_kredit) {
                                                // STOP
                                                $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml_kredit";
                                                mati_disini($msg);
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening qtyKredit $rek_jml_kredit");
                                            }
                                        }
                                        else {
                                            cekBiru("cek request vs rekJml");
                                            if ($req_jml != $rek_jml) {
                                                if ($qtyValidate == true) {
                                                    // STOP
                                                    $msg = "$req_nama, jumlah request $req_jml tidak sama dengan jumlah masuk rekening $rek_jml";
                                                    mati_disini($msg);
                                                }
                                            }
                                            else {
                                                // LANJUT
                                                cekHijau("$req_nama, request $req_jml, rekening $rek_jml");
                                            }

                                        }

                                    }
                                }


                            }
                            else {
                                cekPink2(":: $jenisTrMaster masuk exception ::");
                            }
                        }
                    }
                }

            }

            //region update status sudah dirunning by cli
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $transaksiID,
            );
            $updateData = array(
                "cli" => 1,
            );
            $tr->updateData($where, $updateData);
            cekHere($this->db->last_query());
            //endregion

            $stopDate = dtimeNow();


            cekHitam("--- MULAI VALIDATOR ---");
            $this->load->library("Validator");
            $vdt = New Validator();
//            $vdt->validateMasterDetail($trID_cli, $componentConfig['master'], $componentConfig['detail']);


            if ($getTrID > 0) {
                mati_disini("...cek MANUAL cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
            }


            cekHijau("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
            mati_disini("...tes cli transaksi... rekening pembantu masuk disini (component detail)<br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));


            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        }
        else {
            $stopDate = dtimeNow();
            cekMerah(":: TIDAK ADA yang perlu di-CLI-kan ::
                    <br>start: $startDate<br>stop: $stopDate<br>butuh waktu: " . timeDiff($startDate, $stopDate));
        }

    }

    function patchSerial()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $rekening = "1010030030";
        $cpp = New ComRekeningPembantuProduk();
        $cps = New ComRekeningPembantuProdukPerSerial();
        $mps = New MdlProdukPerSerialNumber();

        $transaksiIDs = array(
            2180,
        );


        $hasil = array();
        $dataMutasiProduk = array();
        $dataMutasiSerial = array();
        $dataProdukPerSerial = array(
            "produk_serial_number_2" => "extern_nama",
            "produk_sku" => "extern2_nama",
//            "produk_sku_label" => "",
            "produk_id" => "produk_id",
            "produk_nama" => "produk_nama",
            "produk_sku_part_nama" => "extern2_nama",
//            "status" => 1,
//            "trash" => 0,
            "cabang_id" => "cabang_id",
            "gudang_id" => "gudang_id",
            "dtime" => "dtime",
            "dtime_last" => "dtime",
//            "oleh_id" => "",
//            "oleh_nama" => "",
            "transaksi_id" => "transaksi_id",
            "transaksi_no" => "transaksi_no",
            "fulldate" => "fulldate",
//            "supplier_id" => "",
//            "supplier_nama" => "",
        );

        $cps->addFilter("qty_debet>0");
        $cps->addFilter("transaksi_id in ('" . implode("','", $transaksiIDs) . "')");
        $tmp = $cps->fetchMoves2($rekening);
        showLast_query("biru");
        foreach ($tmp as $spec) {
            $dataMutasiSerial[$spec->transaksi_id][] = $spec;
        }
        //-------------------------------


        cekHitam("HAHAHA, tidak ada di mutasi produk");
        foreach ($transaksiIDs as $trid) {
            if (isset($dataMutasiSerial[$trid])) {

                foreach ($dataMutasiSerial[$trid] as $ii => $spec) {
                    $serial = $spec->extern_nama;
                    $serial_count = strlen($serial);
                    $serial_count_1 = $serial_count-2;
                    $label = substr($serial, $serial_count_1, 2);
                    //----
                    $serial_count_2 = $serial_count_1-4;
                    $count = substr($serial, 25, 4);
                    //----
                    cekHere("[$serial_count][$serial_count_1][$label][$count] [$serial]");
                    $arrII = array();
                    foreach ($dataProdukPerSerial as $key => $val) {
                        $arrII[$key] = $spec->$val;
                    }
                    $arrII["status"] = 1;
                    $arrII["trash"] = 0;
                    $arrII["produk_sku_label"] = $label;
                    $arrII["count"] = $count;
                    $hasil[$ii] = $arrII;
//                    break;
                }
            }
        }
//        arrPrintHijau($hasil);
//        mati_disini(__LINE__);
//        cekHitam("HAHAHA, tidak ada di mutasi serial");
//        foreach ($transaksiIDs as $trid) {
//            if (!isset($dataMutasiSerial[$trid])) {
//                cekPink("$trid");
//            }
//        }
//

        $this->db->trans_start();

        foreach ($hasil as $hasilxx){

            $mps->setFilters(array());
            $mps->addData($hasilxx);
            showLast_query("hijau");
        }

        mati_disini("...tes cli transaksi");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>SELESAI</h3>");
    }

}