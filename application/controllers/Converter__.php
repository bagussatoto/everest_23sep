<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Converter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->library('Excel');
        $this->load->library('PHPExcel');
        $this->xlsx = new PHPExcel_Reader_Excel2007();
        // $this->xlsx = new Excel();
    }


    public function fetchData()
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
            //valas
            array(
                "additional" => array(
//                    "jenis_value" => "jual",
//                    "jenis" => "produk",
                ),
                "tabel" => array(
                    "valas" => "currency",
                ),
                "kolom" => array(
//                    "satuan" => "satuan",
                    "nama" => "nama",
                    "status" => "status",
                    "trash" => "trash",
                    "nilai_idr" => "exchange",
                ),
            ),
            //produk connceted to supplier
            array(
                "additional" => array(),
                "tabel" => array(
                    "produk_cache_supplier" => "produk_per_supplier",
                ),
                "kolom" => array(
                    "data_id" => "produk_id",
                    "suppliers_id" => "suppliers_id",
                    "status" => "status",
                    "trash" => "trash",
                    "cabang_id" => "cabang_id",
                ),
            ),
        );
        foreach ($arrKonverter as $k => $kSpec) {
            foreach ($kSpec['tabel'] as $fromTabel => $toTabel) {
                cekHitam("mengosongkan tabel $toTabel");
                $this->db->truncate($toTabel);
            }
        }
        if (sizeof($arrKonverter) > 0) {
            foreach ($arrKonverter as $k => $kSpec) {
                foreach ($kSpec['tabel'] as $fromTabel => $toTabel) {
                    $tmp = array();
                    cekBiru("ambil dari tabel $fromTabel");

                    if (array_key_exists("filter", $kSpec)) {
                        $this->db->where($kSpec['filter']);
                    }
                    $tmp = $this->db->get($fromTabel)->result();
                    cekMerah(__LINE__ . " -- " . $this->db->last_query());

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

                        if (sizeof($hasil) > 0) {
                            $no = 0;
                            $insertID = array();
                            foreach ($hasil as $hSpec) {
                                $insertID[] = $this->db->insert($toTabel, $hSpec);

                                $no++;
//                                cekKuning("$no :: " . $this->db->last_query());
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


        $fromTabel = "settings";
        $toTabel = "address";
        $additional = array(
            "extern_type" => "company",
            "jenis" => "profile",
            "status" => "1",
        );
        $filter = array(
            "jenis" => "users",
            "trash" => "0",
        );
        $this->db->where($filter);
        $tmp = $this->db->get($fromTabel)->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cpTmp) {
                $rsTmp[$cpTmp->untuk] = $cpTmp->nilai;
            }
            $kolom = array(
                "per_customers_id" => "extern_id",
                "per_customers_nama" => "extern_name",
                "nama" => "alias",
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
                "npwp" => "npwp",
            );
            foreach ($kolom as $fromKolom => $toKolom) {
                $cphasil[$toKolom] = isset($rsTmp[$fromKolom]) ? $rsTmp[$fromKolom] : "";
                if (isset($additional) && sizeof($additional) > 0) {
                    foreach ($additional as $key => $val) {
                        $cphasil[$key] = $val;
                    }
                }
            }
            if (sizeof($hasil) > 0) {
                $cpinsertID[] = $this->db->insert($toTabel, $cphasil);
                if (sizeof($cpinsertID) == 0) {
                    mati_disini("konverter company profile, GAGAL menulis ke tabel $toTabel");
                }
            }
        }
    }

    public function fetchRekening()
    {
        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        //<editor-fold desc="rekening pembantu (bukan barang)">
        $pairRekeningPembantu = array(
            "kas" => array(
                "comName" => "RekeningPembantuKas",
                "tabel" => "rek_cache_pembantu_kas",
                "externId" => "jenis_id",
                "externNama" => "jenis_nama",
            ),

            "piutang dagang" => array(
                "comName" => "RekeningPembantuCustomer",
                "tabel" => "rek_cache_pembantu_piutang_customer",
                "externId" => "customer_id",
                "externNama" => "customer_nama",
            ),
            "piutang valas" => array(
                "comName" => "RekeningPembantuCustomerValas",
                "tabel" => "rek_cache_pembantu_piutang_valas_customer",
                "externId" => "customer_id",
                "externNama" => "customer_nama",
            ),

            "hutang dagang" => array(
                "comName" => "RekeningPembantuSupplier",
                "tabel" => "rek_cache_pembantu_hutang_supplier",
                "externId" => "supplier_id",
                "externNama" => "supplier_nama",
            ),
            "hutang ke konsumen" => array(
                "comName" => "RekeningPembantuCustomer",
                "tabel" => "rek_cache_pembantu_hutang_ke_konsumen",
                "externId" => "customers_id",
                "externNama" => "customers_nama",
            ),
            "hutang biaya" => array(
                "comName" => "RekeningPembantuSupplier",
                "tabel" => "rek_cache_pembantu_hutang_biaya",
                "externId" => "produk_id",
                "externNama" => "produk_nama",
            ),
            "hutang valas ke konsumen" => array(
                "comName" => "RekeningPembantuCustomerValas",
                "tabel" => "rek_cache_pembantu_hutang_valas_ke_konsumen",
                "externId" => "customers_id",
                "externNama" => "customers_nama",
            ),

        );

        $no = 0;
        $arrRekPembantu = array();
        foreach ($pairRekeningPembantu as $rek => $pSpec) {
            $arrFilter = array(
                "periode='forever'",
            );
            foreach ($arrFilter as $filter) {
                $this->db->where($filter);
            }
            $tmpPembantu = $this->db->get($pSpec["tabel"])->result();


            $loop = array();
            $static = array();
            if (sizeof($tmpPembantu) > 0) {
                foreach ($tmpPembantu as $tpSpec) {
                    $no++;

                    $arrRekPembantu[$no]["comName"] = $pSpec["comName"];
                    $loop[$rek] = $tpSpec->nilai_af;
                    $static = array(
                        "extern_id" => $tpSpec->$pSpec["externId"],
                        "extern_nama" => $tpSpec->$pSpec["externNama"],
                        "cabang_id" => $cabang_id,
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,

                        "qty" => $tpSpec->unit_af,
                        "extern2_id" => 0,
                        "extern2_nama" => 0,
                    );
                    $arrRekPembantu[$no]["loop"] = $loop;
                    $arrRekPembantu[$no]["static"] = $static;
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening pembantu items">
        $pairRekeningPembantuItems = array(
            "persediaan produk" => array(
                "comName" => "RekeningPembantuProduk",
                "comFifo" => "FifoProdukJadi",
                "comFifoAvg" => "FifoAverage",
                "comLocker" => "LockerStock",

                "tabel" => "rek_cache_pembantu_produk",
                "externId" => "produk_id",
                "externNama" => "produk_nama",
                "jenis" => "produk",
                "lockerState" => "active",
                "gudangId" => $gudang_id,
            ),
            "persediaan supplies" => array(
                "comName" => "RekeningPembantuSupplies",
                "comFifo" => "FifoSupplies",
                "comFifoAvg" => "FifoAverage",
                "comLocker" => "LockerStockSupplies",

                "tabel" => "rek_cache_pembantu_produk_supplies",
                "externId" => "produk_id",
                "externNama" => "produk_nama",
                "jenis" => "supplies",
                "lockerState" => "active",
                "gudangId" => $gudang_id,
            ),
        );
        $pairRekeningPembantuEfisiensiItems = array(
            "efisiensi operasional" => array(
                "comName" => "RekeningPembantuEfisiensi",

                "tabel" => "rek_cache_pembantu_efisiensi_produk",
                "externId" => "produk_id",
                "externNama" => "produk_nama",
                "jenis" => "produk",
                "lockerState" => "active",
                "gudangId" => $gudang_id,
            ),
        );

        $no = 0;
        $arrFifoItems = array();
        $arrFifoItemsAvg = array();
        $arrLockerItems = array();
        $arrRekPembantuItems = array();
        foreach ($pairRekeningPembantuItems as $rek => $pSpec) {
            $arrFilter = array(
                "periode='forever'",
            );
            foreach ($arrFilter as $filter) {
                $this->db->where($filter);
            }
            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();

            $loop = array();
            $static = array();
            if (sizeof($tmpPembantuItems) > 0) {
                foreach ($tmpPembantuItems as $tpSpec) {
                    $no++;

                    $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;

                    //<editor-fold desc="rek_pembantu">
                    $loop[$rek] = $tpSpec->nilai_af;
                    $static = array(
                        "extern_id" => $tpSpec->$pSpec["externId"],
                        "extern_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "produk_qty" => $tpSpec->unit_af,
                        "produk_nilai" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["loop"] = $loop;
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["static"] = $static;
                    //</editor-fold>


                    //<editor-fold desc="fifo fisik">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "produk_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "unit" => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrFifoItems[$pSpec["comFifo"]][$no]["loop"] = $loop;
                    $arrFifoItems[$pSpec["comFifo"]][$no]["static"] = $static;
                    //</editor-fold>


                    //<editor-fold desc="fifo average">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama" => $tpSpec->$pSpec["externNama"],
                        "jml" => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp" => $nilai_item,
                        "jenis" => $pSpec["jenis"],
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["loop"] = $loop;
                    $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["static"] = $static;
                    //</editor-fold>


                    //<editor-fold desc="locker items">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama" => $tpSpec->$pSpec["externNama"],
                        "jumlah" => $tpSpec->unit_af,
                        "jenis" => $pSpec["jenis"],
                        "state" => $pSpec["lockerState"],
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrLockerItems[$pSpec["comLocker"]][$no]["loop"] = $loop;
                    $arrLockerItems[$pSpec["comLocker"]][$no]["static"] = $static;
                    //</editor-fold>
                }
            }
        }

        $arrRekPembantuEfisiensiItems = array();
        foreach ($pairRekeningPembantuEfisiensiItems as $rek => $pSpec) {
            $arrFilter = array(
                "periode='forever'",
            );
            foreach ($arrFilter as $filter) {
                $this->db->where($filter);
            }
            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();


            $loop = array();
            $static = array();
            if (sizeof($tmpPembantuItems) > 0) {
                foreach ($tmpPembantuItems as $tpSpec) {
                    $no++;

                    $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;


                    //<editor-fold desc="rek_pembantu">
                    $loop[$rek] = $tpSpec->nilai_af;
                    $static = array(
                        "extern_id" => $tpSpec->$pSpec["externId"],
                        "extern_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "produk_qty" => $tpSpec->unit_af,
                        "produk_nilai" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["loop"] = $loop;
                    $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["static"] = $static;
                    //</editor-fold>
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening besar">
        $arrRekeningAlias = array(
            "hutang dagang ke pusat" => "hutang ke pusat",
            "r/l lain lain" => "rugilaba lain lain",
        );

        $fromTabel = "rek_cache";
        $arrFilter = array(
            "periode='forever'",
        );
        foreach ($arrFilter as $filter) {
            $this->db->where($filter);
        }
        $tmp = $this->db->get($fromTabel)->result();

        $arrRekCache = array();
        $arrAkunting = array();
        if (sizeof($tmp) > 0) {
            $loop = array();
            $static = array();
//            $arrRekCache[0]["comName"] = "Rekening";
            foreach ($tmp as $rSpec) {
                $rek_nama = array_key_exists($rSpec->rekening, $arrRekeningAlias) ? $arrRekeningAlias[$rSpec->rekening] : $rSpec->rekening;

                if (!isset($arrRekCache[$rSpec->cabang_id]["comName"])) {
                    $arrRekCache[$rSpec->cabang_id]["comName"] = "Rekening";
                }
                if (!isset($arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama])) {
                    $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = 0;
                }
                $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = abs($rSpec->after_saldo);
                $arrRekCache[$rSpec->cabang_id]["static"]["cabang_id"] = $cabang_id;
                $arrRekCache[$rSpec->cabang_id]["static"]["fulldate"] = $fulldateNow;
                $arrRekCache[$rSpec->cabang_id]["static"]["dtime"] = $dtimeNow;
            }

            $statics = array(
                "cabang_id" => $cabang_id,
                "fulldate" => $fulldateNow,
                "dtime" => $fulldateNow,
            );
            $arrAkunting[1]["comName"] = "RugiLaba";
            $arrAkunting[1]["loop"] = array();
            $arrAkunting[1]["static"] = $statics;

            $arrAkunting[2]["comName"] = "Neraca";
            $arrAkunting[2]["loop"] = array();
            $arrAkunting[2]["static"] = $statics;
        }
        //</editor-fold>


//        $this->db->trans_begin();

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
//            arrPrint($arrRekCache);

            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
//        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            foreach ($arrRekPembantu as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            foreach ($arrFifoItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            foreach ($arrFifoItemsAvg as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComLocker">
        if (sizeof($arrLockerItems) > 0) {
            foreach ($arrLockerItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuItems">
        if (sizeof($arrRekPembantuItems) > 0) {
            foreach ($arrRekPembantuItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuEfisiensiItems">
        if (sizeof($arrRekPembantuEfisiensiItems) > 0) {
            foreach ($arrRekPembantuEfisiensiItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu efisiensi produk");
        }
        //</editor-fold>

//        $arrAkunting = array();
        cekUngu("menjalankan AKUNTING RUGILABA dan NERACA");
        //<editor-fold desc="AKUNTING, RUGILABA, NERACA">
        if (sizeof($arrAkunting) > 0) {
            $this->load->model("Coms/ComJurnal");
            foreach ($arrAkunting as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
        //</editor-fold>

        validateAllBalances();


        cekUngu("done, AKUNTING RUGILABA dan NERACA");
//        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI");


    }

    public function fetchPaymentSource()
    {
        $arrToTabel = array(
            "transaksi_payment_source",
            "transaksi_payment_antisource",
        );
        foreach ($arrToTabel as $toTabel) {
            $this->db->truncate($toTabel);
        }


        $arrSource = array(
            "fg" => array(
                "jenisSrc" => "467",
                "filter" => array(
                    "status=0",
                    "trash=0",
                ),
                "inject" => array(
                    "jenisTr_new" => ".467",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "suppliers_id",
                    "pihakName" => "suppliers_nama",
                    "extLabel" => "",
                    "nilai_credit" => "transaksi_net",
                    "nilai_cash" => "transaksi_net",
                ),
            ),
            "sp" => array(
                "jenisSrc" => "461",
                "filter" => array(
                    "status=0",
                    "trash=0",
                    "tipe is null",
                ),
                "inject" => array(
                    "jenisTr_new" => ".461",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "suppliers_id",
                    "pihakName" => "suppliers_nama",
                    "extLabel" => "",
                    "nilai_credit" => "transaksi_net",
                    "nilai_cash" => "transaksi_net",
                ),
            ),
            "js" => array(
                "jenisSrc" => "461",
                "filter" => array(
                    "status=0",
                    "trash=0",
                    "tipe=1",
                ),
                "inject" => array(
                    "jenisTr_new" => ".463",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "suppliers_id",
                    "pihakName" => "suppliers_nama",
                    "extLabel" => "",
                    "nilai_credit" => "transaksi_net",
                    "nilai_cash" => "transaksi_net",
                ),
            ),
            "pnj" => array(
                "jenisSrc" => "582",
                "filter" => array(
                    "status=0",
                    "trash=0",
                ),
                "inject" => array(
                    "jenisTr_new" => ".582",
                    "olehID" => "oleh_id",
                    "olehName" => "oleh_nama",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "customers_id",
                    "pihakName" => "customers_nama",
                    "extLabel" => "",
                    "nilai_credit" => "transaksi_net-deposit_nilai_in",
                    "nilai_cash" => "transaksi_net-deposit_nilai_in",
                ),
            ),
            "cia" => array(
                "jenisSrc" => "582r",
                "filter" => array(
                    "status=0",
//                    "trash=0",
                    "pembayaran_tunai=1",
                    "setor_status=0",
                ),
                "inject" => array(
                    "jenisTr_new" => ".582_",
                    "olehID" => "oleh_id",
                    "olehName" => "oleh_nama",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "customers_id",
                    "pihakName" => "customers_nama",
                    "extLabel" => "",
                    "nilai_cia" => "transaksi_net",
                ),
            ),
            "dp_in" => array(
                "jenisSrc" => "582r",
                "filter" => array(
                    "status=0",
//                    "trash=0",
                    "pembayaran_tunai=0",
                    "deposit_nilai_in>0",
                    "setor_status=0",
                ),
                "inject" => array(
                    "jenisTr_new" => ".582_",
                    "olehID" => "oleh_id",
                    "olehName" => "oleh_nama",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "customers_id",
                    "pihakName" => "customers_nama",
                    "extLabel" => "",
                    "dp" => "deposit_nilai_in",
//                    "nilai_cash" => "transaksi_net",
                ),
            ),
        );
        $arrAntiSource = array(
            "rpnj" => array(
                "jenisSrc" => "982",
                "filter" => array(
                    "status=0",
                    "trash=0",
                    "trash2=0",
//                    "pembayaran='piutang'",
                ),
                "inject" => array(
                    "jenisTr_new" => ".982",
                    "olehID" => "oleh_id",
                    "olehName" => "oleh_nama",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "customers_id",
                    "pihakName" => "customers_nama",
                    "extLabel" => "",
                    "nilai_credit" => "transaksi_net",
                    "nilai_cash" => "transaksi_net",
                    "tagihan" => "transaksi_net",
                ),
            ),
        );
        $arrSourceValas = array(
            "vls" => array(
                "jenisSrc" => "382",
                "filter" => array(
                    "status=0",
                    "trash=0",
//                    "tipe is null",
                ),
                "inject" => array(
                    "jenisTr_new" => ".382",
                    "olehID" => "oleh_id",
                    "olehName" => "oleh_nama",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "customers_id",
                    "pihakName" => "customers_nama",
                    "extLabel" => "",
                    "tagihan" => "transaksi_nilai",
                    "valasDetails" => "valas_id",
                    "valasDetails__nama" => "valas_nama",
                    "valasDetails__exchange" => "valas_nilai",
                    "nett2_valas" => "transaksi_nilai/valas_nilai",
                    "grand_total_valas" => "transaksi_nilai/valas_nilai",
                ),
            ),
        );

        $mainTransaksi = array();
        $mainAntiTransaksi = array();
        $mainTransaksiValas = array();


        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        if (sizeof($arrSource) > 0) {
            foreach ($arrSource as $k => $sSpec) {
                $tr->setFilters(array());
                if (isset($sSpec['jenisSrc'])) {
                    $tr->addFilter("jenis='" . $sSpec['jenisSrc'] . "'");
                }
                if (isset($sSpec['filter']) && sizeof($sSpec['filter'])) {
                    foreach ($sSpec['filter'] as $f) {
                        $tr->addFilter("$f");
                    }
                }
                $tmp = $tr->lookupAll()->result();

                // injector key dan value, mirip gerbang value itu lho....
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $i => $tmpSpec) {
                        if (isset($sSpec['inject'])) {
                            foreach ($sSpec['inject'] as $key => $val) {
                                $tmp[$i]->$key = makeValue($val, (array)$tmpSpec, (array)$tmpSpec, 0);
                            }
                        }
                    }
                }
                $mainTransaksi[$k] = $tmp;
            }
        }

        if (sizeof($arrAntiSource) > 0) {
            $tr = new MdlTransaksi();
            if (sizeof($arrAntiSource) > 0) {
                foreach ($arrAntiSource as $k => $sSpec) {
                    $tr->setFilters(array());
                    if (isset($sSpec['jenisSrc'])) {
                        $tr->addFilter("jenis='" . $sSpec['jenisSrc'] . "'");
                    }
                    if (isset($sSpec['filter']) && sizeof($sSpec['filter'])) {
                        foreach ($sSpec['filter'] as $f) {
                            $tr->addFilter("$f");
                        }
                    }

                    $tmp = $tr->lookupAll()->result();
//                    cekBiru($this->db->last_query());
//                    arrPrint($tmp);
                    // injector key dan value, mirip gerbang value itu lho....
                    if (sizeof($tmp) > 0) {
                        foreach ($tmp as $i => $tmpSpec) {
                            if (isset($sSpec['inject'])) {
                                foreach ($sSpec['inject'] as $key => $val) {
//                                    $tmp[$i]->$key = isset($tmpSpec->$val) ? $tmpSpec->$val : $val;
                                    $tmp[$i]->$key = makeValue($val, (array)$tmpSpec, (array)$tmpSpec, 0);
                                }
                            }
                        }
                    }
                    $mainAntiTransaksi[$k] = $tmp;
                }
            }
        }

        if (sizeof($arrSourceValas) > 0) {

            $this->load->model("Mdls/MdlCurrency");
            $cur = new MdlCurrency();
            $curResult = $cur->lookupAll()->result();
            $curResultValas = array();
            foreach ($curResult as $curResultSpec) {
                $curResultValas[$curResultSpec->nama] = $curResultSpec->id;
            }

            $tr = new MdlTransaksi();
            if (sizeof($arrSourceValas) > 0) {
                foreach ($arrSourceValas as $k => $sSpec) {
                    $tr->setFilters(array());
                    if (isset($sSpec['jenisSrc'])) {
                        $tr->addFilter("jenis='" . $sSpec['jenisSrc'] . "'");
                    }
                    if (isset($sSpec['filter']) && sizeof($sSpec['filter'])) {
                        foreach ($sSpec['filter'] as $f) {
                            $tr->addFilter("$f");
                        }
                    }
                    $tmp = $tr->lookupAll()->result();


                    // injector key dan value, mirip gerbang value itu lho....
                    if (sizeof($tmp) > 0) {
                        foreach ($tmp as $i => $tmpSpec) {
                            if (array_key_exists($tmpSpec->valas_nama, $curResultValas)) {
                                $tmpSpec->valas_id = $curResultValas[$tmpSpec->valas_nama];
                            }
                            if (isset($sSpec['inject'])) {
                                foreach ($sSpec['inject'] as $key => $val) {
                                    $tmp[$i]->$key = makeValue($val, (array)$tmpSpec, (array)$tmpSpec, 0);
                                }
                            }
                        }
                    }
                    $mainTransaksiValas[$k] = $tmp;
                }
            }
        }


        $this->load->model("MdlTransaksi");


        if (sizeof($mainTransaksi) > 0) {
            foreach ($mainTransaksi as $jSpec) {
                $no = 0;
                foreach ($jSpec as $mSpec) {

                    $insertID = $mSpec->id;
                    $stepCode_old = $mSpec->jenis;
                    $stepCode = $mSpec->jenisTr_new;
                    $paymentSources = $this->config->item("payment_source");
//                    cekHere("[$insertID] :: $stepCode :: $stepCode_old ::");
//                    arrPrint($paymentSources);
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $no++;
                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {

                                $valueSrc = $paymentSrcConfig['valueSrc'];
//                                cekHitam("$valueSrc **");
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentSrc($insertID, array(
                                        "jenis" => $stepCode,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],

                                        "extern_id" => $mSpec->$externSrc['id'],
                                        "extern_nama" => $mSpec->$externSrc['nama'],
                                        "nomer" => $mSpec->nomer,

                                        "label" => $paymentSrcConfig['label'],

                                        "tagihan" => $mSpec->$valueSrc,
                                        "terbayar" => 0,

                                        "sisa" => $mSpec->$valueSrc,
                                        "cabang_id" => $mSpec->placeID,
                                        "cabang_nama" => $mSpec->placeName,

                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),

                                        "valas_id" => (isset($externSrc['valasId']) && isset($mSpec->$externSrc['valasId'])) ? $mSpec->$externSrc['valasId'] : '',
                                        "valas_nama" => (isset($externSrc['valasLabel']) && isset($mSpec->$externSrc['valasLabel'])) ? $mSpec->$externSrc['valasLabel'] : '',
                                        "valas_nilai" => (isset($externSrc['valasValue']) && isset($mSpec->$externSrc['valasValue'])) ? $mSpec->$externSrc['valasValue'] : '',
                                        "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($mSpec->$externSrc['valasTagihan'])) ? $mSpec->$externSrc['valasTagihan'] : '',
                                        "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($mSpec->$externSrc['valasTerbayar'])) ? $mSpec->$externSrc['valasTerbayar'] : '',
                                        "sisa_valas" => (isset($externSrc['valasSisa']) && isset($mSpec->$externSrc['valasSisa'])) ? $mSpec->$externSrc['valasSisa'] : '',
                                    )
                                );
                                cekOrange($this->db->last_query());
//                                cekHere(" update paymebnt source line ".__LINE__);
                            }
                        }
//                        cekBiru("[$no] [trID: $insertID] - OLD Code: $stepCode_old, NEW Code: $stepCode, DONE...");
                    }
                    else {
                        cekBiru("TIDAK melakukan building payment source...");
                    }
                }
            }
        }

        if (sizeof($mainAntiTransaksi) > 0) {
            foreach ($mainAntiTransaksi as $jSpec) {
                $no = 0;
                foreach ($jSpec as $mSpec) {
                    $insertID = $mSpec->id;
                    $stepCode_old = $mSpec->jenis;
                    $stepCode = $mSpec->jenisTr_new;
                    $paymentSources = $this->config->item("payment_antiSource");
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $no++;

                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {

                                $valueSrc = $paymentSrcConfig['valueSrc'];
//                                cekHitam("$valueSrc **");
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentAntiSrc($insertID, array(
                                        "jenis" => $stepCode,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],

                                        "extern_id" => $mSpec->$externSrc['id'],
                                        "extern_nama" => $mSpec->$externSrc['nama'],
                                        "nomer" => $mSpec->nomer,

                                        "label" => $paymentSrcConfig['label'],

                                        "tagihan" => $mSpec->$valueSrc,
                                        "terbayar" => 0,

                                        "sisa" => $mSpec->$valueSrc,
                                        "cabang_id" => $mSpec->placeID,
                                        "cabang_nama" => $mSpec->placeName,

                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),

                                        "valas_id" => (isset($externSrc['valasId']) && isset($mSpec->$externSrc['valasId'])) ? $mSpec->$externSrc['valasId'] : '',
                                        "valas_nama" => (isset($externSrc['valasLabel']) && isset($mSpec->$externSrc['valasLabel'])) ? $mSpec->$externSrc['valasLabel'] : '',
                                        "valas_nilai" => (isset($externSrc['valasValue']) && isset($mSpec->$externSrc['valasValue'])) ? $mSpec->$externSrc['valasValue'] : '',
                                        "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($mSpec->$externSrc['valasTagihan'])) ? $mSpec->$externSrc['valasTagihan'] : '',
                                        "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($mSpec->$externSrc['valasTerbayar'])) ? $mSpec->$externSrc['valasTerbayar'] : '',
                                        "sisa_valas" => (isset($externSrc['valasSisa']) && isset($mSpec->$externSrc['valasSisa'])) ? $mSpec->$externSrc['valasSisa'] : '',
                                    )
                                );
                                cekBiru($this->db->last_query());
//                                cekHere(" update paymebnt source line ".__LINE__);
                            }
                        }
//                        cekOrange("[$no] [trID: $insertID] - OLD Code: $stepCode_old, NEW Code: $stepCode, DONE...");
                    }
                    else {
                        cekBiru("TIDAK melakukan building payment source...");
                    }
                }
            }
        }

        if (sizeof($mainTransaksiValas) > 0) {
            foreach ($mainTransaksiValas as $jSpec) {
                $no = 0;
                foreach ($jSpec as $mSpec) {
                    $insertID = $mSpec->id;
                    $stepCode_old = $mSpec->jenis;
                    $stepCode = $mSpec->jenisTr_new;
                    $paymentSources = $this->config->item("payment_source");
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $no++;

                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];
                                arrPrint($externSrc);
                                $tr->writePaymentSrc($insertID,
                                    array(
                                        "jenis" => $stepCode,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],

                                        "extern_id" => $mSpec->$externSrc['id'],
                                        "extern_nama" => $mSpec->$externSrc['nama'],
                                        "nomer" => $mSpec->nomer,

                                        "label" => $paymentSrcConfig['label'],

                                        "tagihan" => $mSpec->$valueSrc,
                                        "terbayar" => 0,

                                        "sisa" => $mSpec->$valueSrc,
                                        "cabang_id" => $mSpec->placeID,
                                        "cabang_nama" => $mSpec->placeName,

                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),

                                        "valas_id" => (isset($externSrc['valasId']) && isset($mSpec->$externSrc['valasId'])) ? $mSpec->$externSrc['valasId'] : '',
                                        "valas_nama" => (isset($externSrc['valasLabel']) && isset($mSpec->$externSrc['valasLabel'])) ? $mSpec->$externSrc['valasLabel'] : '',
                                        "valas_nilai" => (isset($externSrc['valasValue']) && isset($mSpec->$externSrc['valasValue'])) ? $mSpec->$externSrc['valasValue'] : '',
                                        "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($mSpec->$externSrc['valasTagihan'])) ? $mSpec->$externSrc['valasTagihan'] : '',
                                        "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($mSpec->$externSrc['valasTerbayar'])) ? $mSpec->$externSrc['valasTerbayar'] : '',
                                        "sisa_valas" => (isset($externSrc['valasSisa']) && isset($mSpec->$externSrc['valasSisa'])) ? $mSpec->$externSrc['valasSisa'] : '',
                                    )
                                );
                                cekMerah($this->db->last_query());
//                                cekHere(" update paymebnt source line ".__LINE__);
                            }
                        }
//                        cekBiru("[$no] [trID: $insertID] - OLD Code: $stepCode_old, NEW Code: $stepCode, DONE...");
                    }
                    else {
                        cekBiru("TIDAK melakukan building payment source...");
                    }
                }
            }
        }


    }


    public function runConvert()
    {

        mati_disini(":: TIDAK RUN_CONVERT, HAHAHAAH");


        $this->db->trans_begin();


//        $this->fetchData();
        $this->fetchPaymentSource();
        $this->fetchRekening();


        cekMerah("DONE :: " . get_class($this));
        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

    }

    public function runCek()
    {


        $targetJenis = "";
        if (isset($_GET['u']) && ($_GET['u'] == 1)) {
            $title = "Payment SRC Piutang vs Rekening Piutang Dagang";
            $targetJenis = "target_jenis='749'";
            $rekening = "piutang dagang";
            $model2 = "ComRekeningPembantuCustomer";
        }
        elseif (isset($_GET['u']) && ($_GET['u'] == 2)) {
            $title = "Payment SRC Hutang vs Rekening Hutang Dagang (FG)";
            $targetJenis = "target_jenis='489'";
            $rekening = "hutang dagang";
            $model2 = "ComRekeningPembantuSupplier";
        }
        elseif (isset($_GET['u']) && ($_GET['u'] == 3)) {
            $title = "Payment SRC Hutang vs Rekening Hutang Dagang (SUPPLIES)";
            $targetJenis = "target_jenis='487'";
            $rekening = "hutang dagang";
            $model2 = "ComRekeningPembantuSupplier";
        }
        elseif (isset($_GET['u']) && ($_GET['u'] == 4)) {
            $title = "Payment SRC Hutang Biaya vs Rekening Hutang Biaya";
            $targetJenis = "target_jenis='462'";
            $rekening = "hutang biaya";
            $model2 = "ComRekeningPembantuSupplier";
        }
        else {
            $title = "Payment SRC Piutang vs Rekening Piutang Dagang";
            $targetJenis = "target_jenis='749'";
            $rekening = "piutang dagang";
            $model2 = "ComRekeningPembantuCustomer";
        }

        $model = "MdlPaymentSource";
        $this->load->model("Mdls/" . $model);
        $this->load->model("Coms/" . $model2);
        $model3 = "MdlPaymentAntiSource";
        $this->load->model("Mdls/" . $model3);


//cekHere(":: $targetJenis :: $rekening ::");

        $pysFilter = array(
            "sisa>0",
            $targetJenis
        );
        $pys = new $model();
        $pys->setFilters(array());
        foreach ($pysFilter as $f) {
            $pys->addFilter("$f");
        }
        $arrPymSrc = $pys->lookupAll()->result();
        if (sizeof($arrPymSrc) > 0) {
            foreach ($arrPymSrc as $arrPymSrcSpec) {
                $externID = $arrPymSrcSpec->extern_id;
                $externSisa = $arrPymSrcSpec->sisa;

                if (!isset($arrPymResult[$externID])) {
                    $arrPymResult[$externID] = 0;
                }
                $arrPymResult[$externID] += $externSisa;
            }
        }


        $pysAntiFilter = array(
            "sisa>0",
            $targetJenis
        );
        $pysAnti = new $model3();
        $pysAnti->setFilters(array());
        foreach ($pysAntiFilter as $f) {
            $pysAnti->addFilter("$f");
        }
        $arrPymAntiSrc = $pysAnti->lookupAll()->result();
        if (sizeof($arrPymAntiSrc) > 0) {
            foreach ($arrPymAntiSrc as $arrPymAntiSrcSpec) {
                $externID = $arrPymAntiSrcSpec->extern_id;
                $externSisa = $arrPymAntiSrcSpec->sisa;

                if (!isset($arrPymAntiResult[$externID])) {
                    $arrPymAntiResult[$externID] = 0;
                }
                $arrPymAntiResult[$externID] += $externSisa;
            }
        }


        $rekFilter = array(
//            "target_jenis='749'",
//            "sisa>.0",
        );
        $rek = new $model2();
        $rek->setFilters(array());
        foreach ($rekFilter as $f) {
            $rek->addFilter("$f");
        }
        $arrRekSrc = $rek->fetchBalances($rekening);
        if (sizeof($arrRekSrc) > 0) {
            foreach ($arrRekSrc as $arrRekSrcSpec) {
                $externID = $arrRekSrcSpec->extern_id;
                $externNama = $arrRekSrcSpec->extern_nama;
                $externSaldoDebet = $arrRekSrcSpec->debet;
                $externSaldoKredit = $arrRekSrcSpec->kredit;
                $externSisaSrc = isset($arrPymResult[$externID]) ? $arrPymResult[$externID] : 0;
                $externSisaAntiSrc = isset($arrPymAntiResult[$externID]) ? $arrPymAntiResult[$externID] : 0;
                $externSisa = $externSisaSrc - $externSisaAntiSrc;
                if (!isset($arrResult[$externID])) {
                    $arrResult[$externID] = array(
                        "id" => 0,
                        "nama" => "-",
                        "paymentSrc" => 0,
                        "rekSrc" => 0,
                    );
                }
                $arrResult[$externID] = array(
                    "id" => $externID,
                    "nama" => $externNama,
                    "paymentSrc" => $externSisa,
                    "rekSrc debet" => $externSaldoDebet,
                    "rekSrc kredit" => $externSaldoKredit,
                );


//                if ($externSisa != ($externSaldoDebet + $externSaldoKredit)) {
                if (!isset($arrResultDiff[$externID])) {
                    $arrResultDiff[$externID] = array(
                        "id" => 0,
                        "nama" => "-",
                        "paymentSrc" => 0,
                        "paymentAntiSrc" => 0,
                        "rekSrc debet" => 0,
                        "rekSrc kredit" => 0,
                        "selisih" => 0,
                    );
                }
                $arrResultDiff[$externID] = array(
                    "id" => $externID,
                    "nama" => $externNama,
                    "paymentSrc" => isset($arrPymResult[$externID]) ? $arrPymResult[$externID] : 0,
                    "paymentAntiSrc" => isset($arrPymAntiResult[$externID]) ? $arrPymAntiResult[$externID] : 0,
                    "rekSrc debet" => $externSaldoDebet,
                    "rekSrc kredit" => $externSaldoKredit,
                    "selisih" => $externSisa - ($externSaldoDebet + $externSaldoKredit),
                );

                if (!isset($arrResultTotalDiff["paymentSrc"])) {
                    $arrResultTotalDiff["paymentSrc"] = 0;
                }
                if (!isset($arrResultTotalDiff["paymentAntiSrc"])) {
                    $arrResultTotalDiff["paymentAntiSrc"] = 0;
                }
                if (!isset($arrResultTotalDiff["rekSrc debet"])) {
                    $arrResultTotalDiff["rekSrc debet"] = 0;
                }
                if (!isset($arrResultTotalDiff["rekSrc kredit"])) {
                    $arrResultTotalDiff["rekSrc kredit"] = 0;
                }
                if (!isset($arrResultTotalDiff["selisih"])) {
                    $arrResultTotalDiff["selisih"] = 0;
                }
                $arrResultTotalDiff["paymentSrc"] += isset($arrPymResult[$externID]) ? $arrPymResult[$externID] : 0;
                $arrResultTotalDiff["paymentAntiSrc"] += isset($arrPymAntiResult[$externID]) ? $arrPymAntiResult[$externID] : 0;
                $arrResultTotalDiff["rekSrc debet"] += $externSaldoDebet;
                $arrResultTotalDiff["rekSrc kredit"] += $externSaldoKredit;
                $arrResultTotalDiff["selisih"] += $externSisa - ($externSaldoDebet + $externSaldoKredit);
//                }
            }
        }


        $str = "";
        if (sizeof($arrResultDiff) > 0) {
            $str .= "<div><h2>$title</h2></div>";
            $str .= "<table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            foreach ($arrResultDiff as $arrResultDiffSpec) {
                $strTD = "";
                foreach ($arrResultDiffSpec as $key => $val) {
                    $strTD .= "<td>";
                    $strTD .= $key;
                    $strTD .= "</td>";
                }
            }
            $str .= $strTD;
            $str .= "</tr>";

            foreach ($arrResultDiff as $arrResultDiffSpec) {
                $str .= "<tr>";
                foreach ($arrResultDiffSpec as $key => $val) {
                    if (is_numeric($val)) {
                        $str .= "<td style='text-align: right;'>";
                        if ($key != "id") {
                            $str .= number_format("$val", "10", ",", ".");
                        }
                        else {
                            $str .= $val;
                        }
                        $str .= "</td>";
                    }
                    else {
                        $str .= "<td style='text-align: left;'>";
                        $str .= $val;
                        $str .= "</td>";
                    }
                }
                $str .= "</tr>";
            }

            $str .= "<tr>";
            $str .= "<td style='text-align: right;' colspan='2'></td>";
            foreach ($arrResultTotalDiff as $key => $val) {
                if (is_numeric($val)) {
                    $str .= "<td style='text-align:right;font-weight:bold;'>";
                    if ($key != "id") {
                        $str .= number_format("$val", "10", ",", ".");
                    }
                    else {
                        $str .= $val;
                    }
                    $str .= "</td>";
                }
            }
            $str .= "</tr>";
            $str .= "</table>";
        }

        echo $str;
    }


    /* ----------------------------------------------------
     * IPORDER persediaan
     * dan data pembantu produk
     * -----------------------------------------------------*/
    public function form()
    {
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukRek/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function formSupplies()
    {
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importSuppliesRek/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function formPiutang()
    {
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importPiutangRek/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function formHutangDagang()
    {
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importHutangDagangRek/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function importProdukRek($arrSource = array())
    {
        /* ------------------------------
         * cek 1 untuk ngesave
         * cek 0 untuk keperluan ceking doang
         * ----------------------------------*/
        $cek = 0;
        // $cek = 1;
        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        $datas = $this->xlsx->reader($tmp);

        $persediaan_total = 0;
        foreach ($datas as $k => $dataSpec) {
            if (isset($dataSpec['p_id']) && $dataSpec['p_id'] > 0) {
                $tmp = array(
                    "id" => $k,
                    "produk_id" => $dataSpec['p_id'],
                    "produk_nama" => $dataSpec['produk_nama'],
                    "unit_af" => $dataSpec['qty'],
                    "nilai_af" => $dataSpec['amount'],
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx[$k] = (object)$tmp;
            }
            $persediaan_total += $dataSpec['amount'];
        }
        $arrResultEx = $tmpResultEx;
//        arrPrint($arrResultEx);
//        mati_disini(":: $persediaan_total ::");


        //<editor-fold desc="rekening pembantu (bukan barang)">
        $pairRekeningPembantu = array(

//            "kas" => array(
//                "comName" => "RekeningPembantuKas",
//                "tabel" => "rek_cache_pembantu_kas",
//                "externId" => "jenis_id",
//                "externNama" => "jenis_nama",
//            ),

//            "piutang dagang" => array(
//                "comName" => "RekeningPembantuCustomer",
//                "tabel" => "rek_cache_pembantu_piutang_customer",
//                "externId" => "customer_id",
//                "externNama" => "customer_nama",
//            ),

//            "piutang valas" => array(
//                "comName" => "RekeningPembantuCustomerValas",
//                "tabel" => "rek_cache_pembantu_piutang_valas_customer",
//                "externId" => "customer_id",
//                "externNama" => "customer_nama",
//            ),

//            "hutang dagang" => array(
//                "comName" => "RekeningPembantuSupplier",
//                "tabel" => "rek_cache_pembantu_hutang_supplier",
//                "externId" => "supplier_id",
//                "externNama" => "supplier_nama",
//            ),
//
//            "hutang ke konsumen" => array(
//                "comName" => "RekeningPembantuCustomer",
//                "tabel" => "rek_cache_pembantu_hutang_ke_konsumen",
//                "externId" => "customers_id",
//                "externNama" => "customers_nama",
//            ),

//            "hutang biaya" => array(
//                "comName" => "RekeningPembantuSupplier",
//                "tabel" => "rek_cache_pembantu_hutang_biaya",
//                "externId" => "produk_id",
//                "externNama" => "produk_nama",
//            ),
//            "hutang valas ke konsumen" => array(
//                "comName" => "RekeningPembantuCustomerValas",
//                "tabel" => "rek_cache_pembantu_hutang_valas_ke_konsumen",
//                "externId" => "customers_id",
//                "externNama" => "customers_nama",
//            ),

        );

        $no = 0;
        $arrRekPembantu = array();
        if (sizeof($pairRekeningPembantu) > 0) {

            foreach ($pairRekeningPembantu as $rek => $pSpec) {
//            $arrFilter = array(
//                "periode='forever'",
//            );
//            foreach ($arrFilter as $filter) {
//                $this->db->where($filter);
//            }
//            $tmpPembantu = $this->db->get($pSpec["tabel"])->result();

                $tmpPembantu = array();
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantu) > 0) {
                    foreach ($tmpPembantu as $tpSpec) {
                        $no++;

                        $arrRekPembantu[$no]["comName"] = $pSpec["comName"];
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "cabang_id" => $cabang_id,
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,

                            "qty" => $tpSpec->unit_af,
                            "extern2_id" => 0,
                            "extern2_nama" => 0,
                        );
                        $arrRekPembantu[$no]["loop"] = $loop;
                        $arrRekPembantu[$no]["static"] = $static;
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening pembantu items">
        $pairRekeningPembantuItems = array(
            "persediaan produk" => array(
                "comName" => "RekeningPembantuProduk",
                "comFifo" => "FifoProdukJadi",
                "comFifoAvg" => "FifoAverage",
                "comLocker" => "LockerStock",

                "tabel" => "rek_cache_pembantu_produk",
                "externId" => "produk_id",
                "externNama" => "produk_nama",
                "jenis" => "produk",
                "lockerState" => "active",
                "gudangId" => $gudang_id,
            ),
//            "persediaan supplies" => array(
//                "comName" => "RekeningPembantuSupplies",
//                "comFifo" => "FifoSupplies",
//                "comFifoAvg" => "FifoAverage",
//                "comLocker" => "LockerStockSupplies",
//
//                "tabel" => "rek_cache_pembantu_produk_supplies",
//                "externId" => "produk_id",
//                "externNama" => "produk_nama",
//                "jenis" => "supplies",
//                "lockerState" => "active",
//                "gudangId" => $gudang_id,
//            ),
        );
        $pairRekeningPembantuEfisiensiItems = array(
//            "efisiensi operasional" => array(
//                "comName" => "RekeningPembantuEfisiensi",
//
//                "tabel" => "rek_cache_pembantu_efisiensi_produk",
//                "externId" => "produk_id",
//                "externNama" => "produk_nama",
//                "jenis" => "produk",
//                "lockerState" => "active",
//                "gudangId" => $gudang_id,
//            ),
        );

        $no = 0;
        $arrFifoItems = array();
        $arrFifoItemsAvg = array();
        $arrLockerItems = array();
        $arrRekPembantuItems = array();
        foreach ($pairRekeningPembantuItems as $rek => $pSpec) {
//            $arrFilter = array(
//                "periode='forever'",
//            );
//            foreach ($arrFilter as $filter) {
//                $this->db->where($filter);
//            }
//            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();
//            arrPrint($tmpPembantuItems);
//            mati_disini();
            //  pembaca file excell....................................................


            $tmpPembantuItems = $arrResultEx;
            $loop = array();
            $static = array();
            if (sizeof($tmpPembantuItems) > 0) {
                foreach ($tmpPembantuItems as $tpSpec) {
                    $no++;

                    $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;

                    //<editor-fold desc="rek_pembantu">
                    $loop[$rek] = $tpSpec->nilai_af;
                    $static = array(
                        "extern_id" => $tpSpec->$pSpec["externId"],
                        "extern_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "produk_qty" => $tpSpec->unit_af,
                        "produk_nilai" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["loop"] = $loop;
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="fifo fisik">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "produk_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "unit" => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrFifoItems[$pSpec["comFifo"]][$no]["loop"] = $loop;
                    $arrFifoItems[$pSpec["comFifo"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="fifo average">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama" => $tpSpec->$pSpec["externNama"],
                        "jml" => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp" => $nilai_item,
                        "jenis" => $pSpec["jenis"],
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["loop"] = $loop;
                    $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="locker items">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama" => $tpSpec->$pSpec["externNama"],
                        "jumlah" => $tpSpec->unit_af,
                        "jenis" => $pSpec["jenis"],
                        "state" => $pSpec["lockerState"],
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrLockerItems[$pSpec["comLocker"]][$no]["loop"] = $loop;
                    $arrLockerItems[$pSpec["comLocker"]][$no]["static"] = $static;
                    //</editor-fold>
                }
            }
        }

        $arrRekPembantuEfisiensiItems = array();
        if (sizeof($pairRekeningPembantuEfisiensiItems) > 0) {
            foreach ($pairRekeningPembantuEfisiensiItems as $rek => $pSpec) {
//            $arrFilter = array(
//                "periode='forever'",
//            );
//            foreach ($arrFilter as $filter) {
//                $this->db->where($filter);
//            }
//            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();

                $tmpPembantuItems = array();
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantuItems) > 0) {
                    foreach ($tmpPembantuItems as $tpSpec) {
                        $no++;

                        $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;


                        //<editor-fold desc="rek_pembantu">
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "produk_qty" => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["loop"] = $loop;
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["static"] = $static;
                        //</editor-fold>
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening besar">
        $arrRekeningAlias = array(
            "hutang dagang ke pusat" => "hutang ke pusat",
            "r/l lain lain" => "rugilaba lain lain",
        );

//        $fromTabel = "rek_cache";
//        $arrFilter = array(
//            "periode='forever'",
//        );
//        foreach ($arrFilter as $filter) {
//            $this->db->where($filter);
//        }
//        $tmp = $this->db->get($fromTabel)->result();
        $tmpCache = array(
            "id" => 4,
            "rekening" => "persediaan produk",
            "periode" => "forever",
            "debet_saldo" => 0,
            "kredit_saldo" => 0,
            "after_saldo" => $persediaan_total,
            "keterangan" => "stok produk awal",
//            "tgl" => 16,
//            "bln" => 1,
//            "thn" => 2019,
            "dtime" => $dtimeNow,
            "cabang_id" => $cabang_id,
        );
        $tmp = array();
        $tmp[] = (object)$tmpCache;
//arrPrint($tmp);
//mati_disini();
//        $tmp = array();
        $arrRekCache = array();
        $arrAkunting = array();
        if (sizeof($tmp) > 0) {
            $loop = array();
            $static = array();
//            $arrRekCache[0]["comName"] = "Rekening";
            foreach ($tmp as $rSpec) {
                $rek_nama = array_key_exists($rSpec->rekening, $arrRekeningAlias) ? $arrRekeningAlias[$rSpec->rekening] : $rSpec->rekening;

                if (!isset($arrRekCache[$rSpec->cabang_id]["comName"])) {
                    $arrRekCache[$rSpec->cabang_id]["comName"] = "Rekening";
                }
                if (!isset($arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama])) {
                    $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = 0;
                }
                $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = abs($rSpec->after_saldo);
                $arrRekCache[$rSpec->cabang_id]["static"]["cabang_id"] = $cabang_id;
                $arrRekCache[$rSpec->cabang_id]["static"]["fulldate"] = $fulldateNow;
                $arrRekCache[$rSpec->cabang_id]["static"]["dtime"] = $dtimeNow;
            }

//            $statics = array(
//                "cabang_id" => $cabang_id,
//                "fulldate" => $fulldateNow,
//                "dtime" => $fulldateNow,
//            );
//            $arrAkunting[1]["comName"] = "RugiLaba";
//            $arrAkunting[1]["loop"] = array();
//            $arrAkunting[1]["static"] = $statics;
//
//            $arrAkunting[2]["comName"] = "Neraca";
//            $arrAkunting[2]["loop"] = array();
//            $arrAkunting[2]["static"] = $statics;
        }
        //</editor-fold>

//arrPrint($arrRekPembantuItems);
//arrPrint($arrFifoItems);
//arrPrint($arrFifoItemsAvg);
//arrPrint($arrLockerItems);
//arrPrint($arrRekPembantu);
//arrPrint($arrRekPembantuEfisiensiItems);
//        arrPrint($arrRekCache);

//        mati_disini();


        $this->db->trans_begin();

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
//            arrPrint($arrRekCache);

            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
//        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            foreach ($arrRekPembantu as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
//                $cr->pair($rSpec);
//                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            foreach ($arrFifoItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            foreach ($arrFifoItemsAvg as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComLocker">
        if (sizeof($arrLockerItems) > 0) {
            foreach ($arrLockerItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuItems">
        if (sizeof($arrRekPembantuItems) > 0) {
            foreach ($arrRekPembantuItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuEfisiensiItems">
        if (sizeof($arrRekPembantuEfisiensiItems) > 0) {
            foreach ($arrRekPembantuEfisiensiItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
//                cekBiru(":: masuk pair: $modelName");
//                $cr->pair($rSpec);
//                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu efisiensi produk");
        }
        //</editor-fold>


        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }

    }

    public function importSuppliesRek($arrSource = array())
    {
        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmpFiles = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        $datas = $this->xlsx->reader($tmpFiles);

        // mati_disini(arrPrint($datas));

        $persediaan_total = 0;
        foreach ($datas as $k => $dataSpec) {
            if (isset($dataSpec['p_id']) && $dataSpec['p_id'] > 0) {
                $tmp = array(
                    "id" => $k,
                    "produk_id" => $dataSpec['p_id'],
                    "produk_nama" => $dataSpec['produk_nama'],
                    "unit_af" => $dataSpec['qty'],
                    "nilai_af" => $dataSpec['value'],
                    "rekening" => "persediaan supplies",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx[$k] = (object)$tmp;
            }
            $persediaan_total += $dataSpec['value'];
        }
        $arrResultEx = $tmpResultEx;
        //        arrPrint($arrResultEx);
        //        mati_disini(":: $persediaan_total ::");


        //<editor-fold desc="rekening pembantu (bukan barang)">
        $pairRekeningPembantu = array(

            //            "kas" => array(
            //                "comName" => "RekeningPembantuKas",
            //                "tabel" => "rek_cache_pembantu_kas",
            //                "externId" => "jenis_id",
            //                "externNama" => "jenis_nama",
            //            ),

            //            "piutang dagang" => array(
            //                "comName" => "RekeningPembantuCustomer",
            //                "tabel" => "rek_cache_pembantu_piutang_customer",
            //                "externId" => "customer_id",
            //                "externNama" => "customer_nama",
            //            ),

            //            "piutang valas" => array(
            //                "comName" => "RekeningPembantuCustomerValas",
            //                "tabel" => "rek_cache_pembantu_piutang_valas_customer",
            //                "externId" => "customer_id",
            //                "externNama" => "customer_nama",
            //            ),

            //            "hutang dagang" => array(
            //                "comName" => "RekeningPembantuSupplier",
            //                "tabel" => "rek_cache_pembantu_hutang_supplier",
            //                "externId" => "supplier_id",
            //                "externNama" => "supplier_nama",
            //            ),
            //
            //            "hutang ke konsumen" => array(
            //                "comName" => "RekeningPembantuCustomer",
            //                "tabel" => "rek_cache_pembantu_hutang_ke_konsumen",
            //                "externId" => "customers_id",
            //                "externNama" => "customers_nama",
            //            ),

            //            "hutang biaya" => array(
            //                "comName" => "RekeningPembantuSupplier",
            //                "tabel" => "rek_cache_pembantu_hutang_biaya",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //            ),
            //            "hutang valas ke konsumen" => array(
            //                "comName" => "RekeningPembantuCustomerValas",
            //                "tabel" => "rek_cache_pembantu_hutang_valas_ke_konsumen",
            //                "externId" => "customers_id",
            //                "externNama" => "customers_nama",
            //            ),

        );

        $no = 0;
        $arrRekPembantu = array();
        if (sizeof($pairRekeningPembantu) > 0) {

            foreach ($pairRekeningPembantu as $rek => $pSpec) {
                //            $arrFilter = array(
                //                "periode='forever'",
                //            );
                //            foreach ($arrFilter as $filter) {
                //                $this->db->where($filter);
                //            }
                //            $tmpPembantu = $this->db->get($pSpec["tabel"])->result();

                $tmpPembantu = array();
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantu) > 0) {
                    foreach ($tmpPembantu as $tpSpec) {
                        $no++;

                        $arrRekPembantu[$no]["comName"] = $pSpec["comName"];
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "cabang_id" => $cabang_id,
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,

                            "qty" => $tpSpec->unit_af,
                            "extern2_id" => 0,
                            "extern2_nama" => 0,
                        );
                        $arrRekPembantu[$no]["loop"] = $loop;
                        $arrRekPembantu[$no]["static"] = $static;
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening pembantu items">
        $pairRekeningPembantuItems = array(
            "persediaan supplies" => array(
                "comName" => "RekeningPembantuSupplies",
                "comFifo" => "FifoSupplies",
                "comFifoAvg" => "FifoAverage",
                "comLocker" => "LockerStockSupplies",

                // "tabel" => "rek_cache_pembantu_supplies",
                "externId" => "produk_id",
                "externNama" => "produk_nama",
                "jenis" => "supplies",
                "lockerState" => "active",
                "gudangId" => $gudang_id,
            ),
            //            "persediaan supplies" => array(
            //                "comName" => "RekeningPembantuSupplies",
            //                "comFifo" => "FifoSupplies",
            //                "comFifoAvg" => "FifoAverage",
            //                "comLocker" => "LockerStockSupplies",
            //
            //                "tabel" => "rek_cache_pembantu_produk_supplies",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //                "jenis" => "supplies",
            //                "lockerState" => "active",
            //                "gudangId" => $gudang_id,
            //            ),
        );
        $pairRekeningPembantuEfisiensiItems = array(
            //            "efisiensi operasional" => array(
            //                "comName" => "RekeningPembantuEfisiensi",
            //
            //                "tabel" => "rek_cache_pembantu_efisiensi_produk",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //                "jenis" => "produk",
            //                "lockerState" => "active",
            //                "gudangId" => $gudang_id,
            //            ),
        );

        $no = 0;
        $arrFifoItems = array();
        $arrFifoItemsAvg = array();
        $arrLockerItems = array();
        $arrRekPembantuItems = array();
        foreach ($pairRekeningPembantuItems as $rek => $pSpec) {
            //            $arrFilter = array(
            //                "periode='forever'",
            //            );
            //            foreach ($arrFilter as $filter) {
            //                $this->db->where($filter);
            //            }
            //            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();
            //            arrPrint($tmpPembantuItems);
            //            mati_disini();
            //  pembaca file excell....................................................


            $tmpPembantuItems = $arrResultEx;
            $loop = array();
            $static = array();
            if (sizeof($tmpPembantuItems) > 0) {
                foreach ($tmpPembantuItems as $tpSpec) {
                    $no++;

                    $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;

                    //<editor-fold desc="rek_pembantu">
                    $loop[$rek] = $tpSpec->nilai_af;
                    $static = array(
                        "extern_id" => $tpSpec->$pSpec["externId"],
                        "extern_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "produk_qty" => $tpSpec->unit_af,
                        "produk_nilai" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["loop"] = $loop;
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="fifo fisik">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "produk_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate" => $fulldateNow,
                        "dtime" => $dtimeNow,
                        "unit" => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp" => $nilai_item,
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrFifoItems[$pSpec["comFifo"]][$no]["loop"] = $loop;
                    $arrFifoItems[$pSpec["comFifo"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="fifo average">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama" => $tpSpec->$pSpec["externNama"],
                        "jml" => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp" => $nilai_item,
                        "jenis" => $pSpec["jenis"],
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["loop"] = $loop;
                    $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="locker items">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama" => $tpSpec->$pSpec["externNama"],
                        "jumlah" => $tpSpec->unit_af,
                        "jenis" => $pSpec["jenis"],
                        "state" => $pSpec["lockerState"],
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                    );
                    $arrLockerItems[$pSpec["comLocker"]][$no]["loop"] = $loop;
                    $arrLockerItems[$pSpec["comLocker"]][$no]["static"] = $static;
                    //</editor-fold>
                }
            }
        }

        $arrRekPembantuEfisiensiItems = array();
        if (sizeof($pairRekeningPembantuEfisiensiItems) > 0) {
            foreach ($pairRekeningPembantuEfisiensiItems as $rek => $pSpec) {
                //            $arrFilter = array(
                //                "periode='forever'",
                //            );
                //            foreach ($arrFilter as $filter) {
                //                $this->db->where($filter);
                //            }
                //            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();

                $tmpPembantuItems = array();
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantuItems) > 0) {
                    foreach ($tmpPembantuItems as $tpSpec) {
                        $no++;

                        $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;


                        //<editor-fold desc="rek_pembantu">
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "produk_qty" => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["loop"] = $loop;
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["static"] = $static;
                        //</editor-fold>
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening besar">
        $arrRekeningAlias = array(
            "hutang dagang ke pusat" => "hutang ke pusat",
            "r/l lain lain" => "rugilaba lain lain",
        );

        //        $fromTabel = "rek_cache";
        //        $arrFilter = array(
        //            "periode='forever'",
        //        );
        //        foreach ($arrFilter as $filter) {
        //            $this->db->where($filter);
        //        }
        //        $tmp = $this->db->get($fromTabel)->result();
        $tmpCache = array(
            "id" => 4,
            "rekening" => "persediaan supplies",
            "periode" => "forever",
            "debet_saldo" => 0,
            "kredit_saldo" => 0,
            "after_saldo" => $persediaan_total,
            "keterangan" => "stok produk awal",
            //            "tgl" => 16,
            //            "bln" => 1,
            //            "thn" => 2019,
            "dtime" => $dtimeNow,
            "cabang_id" => $cabang_id,
        );
        $tmp = array();
        $tmp[] = (object)$tmpCache;
        //arrPrint($tmp);
        //mati_disini();
        //        $tmp = array();
        $arrRekCache = array();
        $arrAkunting = array();
        if (sizeof($tmp) > 0) {
            $loop = array();
            $static = array();
            //            $arrRekCache[0]["comName"] = "Rekening";
            foreach ($tmp as $rSpec) {
                $rek_nama = array_key_exists($rSpec->rekening, $arrRekeningAlias) ? $arrRekeningAlias[$rSpec->rekening] : $rSpec->rekening;

                if (!isset($arrRekCache[$rSpec->cabang_id]["comName"])) {
                    $arrRekCache[$rSpec->cabang_id]["comName"] = "Rekening";
                }
                if (!isset($arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama])) {
                    $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = 0;
                }
                $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = abs($rSpec->after_saldo);
                $arrRekCache[$rSpec->cabang_id]["static"]["cabang_id"] = $cabang_id;
                $arrRekCache[$rSpec->cabang_id]["static"]["fulldate"] = $fulldateNow;
                $arrRekCache[$rSpec->cabang_id]["static"]["dtime"] = $dtimeNow;
            }

            //            $statics = array(
            //                "cabang_id" => $cabang_id,
            //                "fulldate" => $fulldateNow,
            //                "dtime" => $fulldateNow,
            //            );
            //            $arrAkunting[1]["comName"] = "RugiLaba";
            //            $arrAkunting[1]["loop"] = array();
            //            $arrAkunting[1]["static"] = $statics;
            //
            //            $arrAkunting[2]["comName"] = "Neraca";
            //            $arrAkunting[2]["loop"] = array();
            //            $arrAkunting[2]["static"] = $statics;
        }
        //</editor-fold>

        //arrPrint($arrRekPembantuItems);
        //arrPrint($arrFifoItems);
        //arrPrint($arrFifoItemsAvg);
        //arrPrint($arrLockerItems);
        //arrPrint($arrRekPembantu);
        //arrPrint($arrRekPembantuEfisiensiItems);
        //        arrPrint($arrRekCache);

        //        mati_disini();


        $this->db->trans_begin();

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
            //            arrPrint($arrRekCache);

            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
        //        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            foreach ($arrRekPembantu as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                //                $cr->pair($rSpec);
                //                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            foreach ($arrFifoItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            foreach ($arrFifoItemsAvg as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComLocker">
        if (sizeof($arrLockerItems) > 0) {
            foreach ($arrLockerItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuItems">
        if (sizeof($arrRekPembantuItems) > 0) {
            foreach ($arrRekPembantuItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuEfisiensiItems">
        if (sizeof($arrRekPembantuEfisiensiItems) > 0) {
            foreach ($arrRekPembantuEfisiensiItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                //                $cr->pair($rSpec);
                //                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu efisiensi produk");
        }
        //</editor-fold>


        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
        cekHijau("<h1>done</h1>");

    }

    public function importPiutangRek($arrSource = array())
    {
        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmpFiles = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        $datas = $this->xlsx->reader($tmpFiles);

        // mati_disini(arrPrint($datas));

        $persediaan_total = 0;
        foreach ($datas as $k => $dataSpec) {
            if (isset($dataSpec['p_id']) && $dataSpec['p_id'] > 0) {
                $tmp = array(
                    "id" => $k,
                    "customer_id" => $dataSpec['p_id'],
                    "customer_nama" => $dataSpec['produk_nama'],
                    "unit_af" => $dataSpec['qty'],
                    "nilai_af" => $dataSpec['value'],
                    "rekening" => "piutang dagang",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx[$k] = (object)$tmp;
            }
            $persediaan_total += $dataSpec['value'];
        }
        $arrResultEx = $tmpResultEx;
        //        arrPrint($arrResultEx);
        //        mati_disini(":: $persediaan_total ::");


        //<editor-fold desc="rekening pembantu (bukan barang)">
        $pairRekeningPembantu = array(

            //            "kas" => array(
            //                "comName" => "RekeningPembantuKas",
            //                "tabel" => "rek_cache_pembantu_kas",
            //                "externId" => "jenis_id",
            //                "externNama" => "jenis_nama",
            //            ),

            "piutang dagang" => array(
                "comName" => "RekeningPembantuCustomer",
                "tabel" => "rek_cache_pembantu_piutang_customer",
                "externId" => "customer_id",
                "externNama" => "customer_nama",
            ),

            //            "piutang valas" => array(
            //                "comName" => "RekeningPembantuCustomerValas",
            //                "tabel" => "rek_cache_pembantu_piutang_valas_customer",
            //                "externId" => "customer_id",
            //                "externNama" => "customer_nama",
            //            ),

            //            "hutang dagang" => array(
            //                "comName" => "RekeningPembantuSupplier",
            //                "tabel" => "rek_cache_pembantu_hutang_supplier",
            //                "externId" => "supplier_id",
            //                "externNama" => "supplier_nama",
            //            ),
            //
            //            "hutang ke konsumen" => array(
            //                "comName" => "RekeningPembantuCustomer",
            //                "tabel" => "rek_cache_pembantu_hutang_ke_konsumen",
            //                "externId" => "customers_id",
            //                "externNama" => "customers_nama",
            //            ),

            //            "hutang biaya" => array(
            //                "comName" => "RekeningPembantuSupplier",
            //                "tabel" => "rek_cache_pembantu_hutang_biaya",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //            ),
            //            "hutang valas ke konsumen" => array(
            //                "comName" => "RekeningPembantuCustomerValas",
            //                "tabel" => "rek_cache_pembantu_hutang_valas_ke_konsumen",
            //                "externId" => "customers_id",
            //                "externNama" => "customers_nama",
            //            ),

        );

        $no = 0;
        $arrRekPembantu = array();
        if (sizeof($pairRekeningPembantu) > 0) {

            foreach ($pairRekeningPembantu as $rek => $pSpec) {

                $tmpPembantu = $arrResultEx;
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantu) > 0) {
                    foreach ($tmpPembantu as $tpSpec) {
                        $no++;

                        $arrRekPembantu[$no]["comName"] = $pSpec["comName"];
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "cabang_id" => $cabang_id,
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,

                            "qty" => $tpSpec->unit_af,
                            "extern2_id" => 0,
                            "extern2_nama" => 0,
                        );
                        $arrRekPembantu[$no]["loop"] = $loop;
                        $arrRekPembantu[$no]["static"] = $static;
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening pembantu items">
        $pairRekeningPembantuItems = array(
//            "persediaan supplies" => array(
//                "comName" => "RekeningPembantuSupplies",
//                "comFifo" => "FifoSupplies",
//                "comFifoAvg" => "FifoAverage",
//                "comLocker" => "LockerStockSupplies",
//
//                // "tabel" => "rek_cache_pembantu_supplies",
//                "externId" => "produk_id",
//                "externNama" => "produk_nama",
//                "jenis" => "supplies",
//                "lockerState" => "active",
//                "gudangId" => $gudang_id,
//            ),

            //            "persediaan supplies" => array(
            //                "comName" => "RekeningPembantuSupplies",
            //                "comFifo" => "FifoSupplies",
            //                "comFifoAvg" => "FifoAverage",
            //                "comLocker" => "LockerStockSupplies",
            //
            //                "tabel" => "rek_cache_pembantu_produk_supplies",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //                "jenis" => "supplies",
            //                "lockerState" => "active",
            //                "gudangId" => $gudang_id,
            //            ),
        );
        $pairRekeningPembantuEfisiensiItems = array(
            //            "efisiensi operasional" => array(
            //                "comName" => "RekeningPembantuEfisiensi",
            //
            //                "tabel" => "rek_cache_pembantu_efisiensi_produk",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //                "jenis" => "produk",
            //                "lockerState" => "active",
            //                "gudangId" => $gudang_id,
            //            ),
        );

        $no = 0;
        $arrFifoItems = array();
        $arrFifoItemsAvg = array();
        $arrLockerItems = array();
        $arrRekPembantuItems = array();
        if (sizeof($pairRekeningPembantuItems) > 0) {
            foreach ($pairRekeningPembantuItems as $rek => $pSpec) {
                //            $arrFilter = array(
                //                "periode='forever'",
                //            );
                //            foreach ($arrFilter as $filter) {
                //                $this->db->where($filter);
                //            }
                //            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();
                //            arrPrint($tmpPembantuItems);
                //            mati_disini();
                //  pembaca file excell....................................................


                $tmpPembantuItems = $arrResultEx;
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantuItems) > 0) {
                    foreach ($tmpPembantuItems as $tpSpec) {
                        $no++;

                        $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;

                        //<editor-fold desc="rek_pembantu">
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "produk_qty" => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrRekPembantuItems[$pSpec["comName"]][$no]["loop"] = $loop;
                        $arrRekPembantuItems[$pSpec["comName"]][$no]["static"] = $static;
                        //</editor-fold>

                        //<editor-fold desc="fifo fisik">
                        $loop = array();
                        $static = array(
                            "produk_id" => $tpSpec->$pSpec["externId"],
                            "produk_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "unit" => $tpSpec->unit_af,
                            "jml_nilai" => $tpSpec->nilai_af,
                            "hpp" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrFifoItems[$pSpec["comFifo"]][$no]["loop"] = $loop;
                        $arrFifoItems[$pSpec["comFifo"]][$no]["static"] = $static;
                        //</editor-fold>

                        //<editor-fold desc="fifo average">
                        $loop = array();
                        $static = array(
                            "produk_id" => $tpSpec->$pSpec["externId"],
                            "nama" => $tpSpec->$pSpec["externNama"],
                            "jml" => $tpSpec->unit_af,
                            "jml_nilai" => $tpSpec->nilai_af,
                            "hpp" => $nilai_item,
                            "jenis" => $pSpec["jenis"],
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["loop"] = $loop;
                        $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["static"] = $static;
                        //</editor-fold>

                        //<editor-fold desc="locker items">
                        $loop = array();
                        $static = array(
                            "produk_id" => $tpSpec->$pSpec["externId"],
                            "nama" => $tpSpec->$pSpec["externNama"],
                            "jumlah" => $tpSpec->unit_af,
                            "jenis" => $pSpec["jenis"],
                            "state" => $pSpec["lockerState"],
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrLockerItems[$pSpec["comLocker"]][$no]["loop"] = $loop;
                        $arrLockerItems[$pSpec["comLocker"]][$no]["static"] = $static;
                        //</editor-fold>
                    }
                }
            }
        }

        $arrRekPembantuEfisiensiItems = array();
        if (sizeof($pairRekeningPembantuEfisiensiItems) > 0) {
            foreach ($pairRekeningPembantuEfisiensiItems as $rek => $pSpec) {
                //            $arrFilter = array(
                //                "periode='forever'",
                //            );
                //            foreach ($arrFilter as $filter) {
                //                $this->db->where($filter);
                //            }
                //            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();

                $tmpPembantuItems = array();
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantuItems) > 0) {
                    foreach ($tmpPembantuItems as $tpSpec) {
                        $no++;

                        $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;


                        //<editor-fold desc="rek_pembantu">
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "produk_qty" => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["loop"] = $loop;
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["static"] = $static;
                        //</editor-fold>
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening besar">
        $arrRekeningAlias = array(
            "hutang dagang ke pusat" => "hutang ke pusat",
            "r/l lain lain" => "rugilaba lain lain",
        );


        $tmpCache = array(
            "id" => 4,
            "rekening" => "piutang dagang",
            "periode" => "forever",
            "debet_saldo" => 0,
            "kredit_saldo" => 0,
            "after_saldo" => $persediaan_total,
            "keterangan" => "stok produk awal",
            //            "tgl" => 16,
            //            "bln" => 1,
            //            "thn" => 2019,
            "dtime" => $dtimeNow,
            "cabang_id" => $cabang_id,
        );
        $tmp = array();
        $tmp[] = (object)$tmpCache;

        $arrRekCache = array();
        $arrAkunting = array();
        if (sizeof($tmp) > 0) {
            $loop = array();
            $static = array();
            //            $arrRekCache[0]["comName"] = "Rekening";
            foreach ($tmp as $rSpec) {
                $rek_nama = array_key_exists($rSpec->rekening, $arrRekeningAlias) ? $arrRekeningAlias[$rSpec->rekening] : $rSpec->rekening;

                if (!isset($arrRekCache[$rSpec->cabang_id]["comName"])) {
                    $arrRekCache[$rSpec->cabang_id]["comName"] = "Rekening";
                }
                if (!isset($arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama])) {
                    $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = 0;
                }
                $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = abs($rSpec->after_saldo);
                $arrRekCache[$rSpec->cabang_id]["static"]["cabang_id"] = $cabang_id;
                $arrRekCache[$rSpec->cabang_id]["static"]["fulldate"] = $fulldateNow;
                $arrRekCache[$rSpec->cabang_id]["static"]["dtime"] = $dtimeNow;
            }

            //            $statics = array(
            //                "cabang_id" => $cabang_id,
            //                "fulldate" => $fulldateNow,
            //                "dtime" => $fulldateNow,
            //            );
            //            $arrAkunting[1]["comName"] = "RugiLaba";
            //            $arrAkunting[1]["loop"] = array();
            //            $arrAkunting[1]["static"] = $statics;
            //
            //            $arrAkunting[2]["comName"] = "Neraca";
            //            $arrAkunting[2]["loop"] = array();
            //            $arrAkunting[2]["static"] = $statics;
        }
        //</editor-fold>

        //arrPrint($arrRekPembantuItems);
        //arrPrint($arrFifoItems);
        //arrPrint($arrFifoItemsAvg);
        //arrPrint($arrLockerItems);
        //arrPrint($arrRekPembantu);
        //arrPrint($arrRekPembantuEfisiensiItems);
        //        arrPrint($arrRekCache);

        //        mati_disini();


        $this->db->trans_begin();

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
            //            arrPrint($arrRekCache);

            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
        //        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            foreach ($arrRekPembantu as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                //                $cr->pair($rSpec);
                //                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            foreach ($arrFifoItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            foreach ($arrFifoItemsAvg as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComLocker">
        if (sizeof($arrLockerItems) > 0) {
            foreach ($arrLockerItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuItems">
        if (sizeof($arrRekPembantuItems) > 0) {
            foreach ($arrRekPembantuItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuEfisiensiItems">
        if (sizeof($arrRekPembantuEfisiensiItems) > 0) {
            foreach ($arrRekPembantuEfisiensiItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                //                $cr->pair($rSpec);
                //                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu efisiensi produk");
        }
        //</editor-fold>


        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
        cekHijau("<h1>done</h1>");

    }

    public function importHutangDagangRek($arrSource = array())
    {
        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmpFiles = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        $datas = $this->xlsx->reader($tmpFiles);

        // mati_disini(arrPrint($datas));

        $persediaan_total = 0;
        foreach ($datas as $k => $dataSpec) {
            if (isset($dataSpec['p_id']) && $dataSpec['p_id'] > 0) {
                $tmp = array(
                    "id" => $k,
                    "supplier_id" => $dataSpec['p_id'],
                    "supplier_nama" => $dataSpec['produk_nama'],
                    "unit_af" => $dataSpec['qty'],
                    "nilai_af" => $dataSpec['value'],
                    "rekening" => "hutang dagang",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx[$k] = (object)$tmp;
            }
            $persediaan_total += $dataSpec['value'];
        }
        $arrResultEx = $tmpResultEx;
        //        arrPrint($arrResultEx);
        //        mati_disini(":: $persediaan_total ::");


        //<editor-fold desc="rekening pembantu (bukan barang)">
        $pairRekeningPembantu = array(

            //            "kas" => array(
            //                "comName" => "RekeningPembantuKas",
            //                "tabel" => "rek_cache_pembantu_kas",
            //                "externId" => "jenis_id",
            //                "externNama" => "jenis_nama",
            //            ),

            //            "piutang dagang" => array(
            //                "comName" => "RekeningPembantuCustomer",
            //                "tabel" => "rek_cache_pembantu_piutang_customer",
            //                "externId" => "customer_id",
            //                "externNama" => "customer_nama",
            //            ),

            //            "piutang valas" => array(
            //                "comName" => "RekeningPembantuCustomerValas",
            //                "tabel" => "rek_cache_pembantu_piutang_valas_customer",
            //                "externId" => "customer_id",
            //                "externNama" => "customer_nama",
            //            ),

            "hutang dagang" => array(
                "comName" => "RekeningPembantuSupplier",
                "tabel" => "rek_cache_pembantu_hutang_supplier",
                "externId" => "supplier_id",
                "externNama" => "supplier_nama",
            ),

            //            "hutang ke konsumen" => array(
            //                "comName" => "RekeningPembantuCustomer",
            //                "tabel" => "rek_cache_pembantu_hutang_ke_konsumen",
            //                "externId" => "customers_id",
            //                "externNama" => "customers_nama",
            //            ),

            //            "hutang biaya" => array(
            //                "comName" => "RekeningPembantuSupplier",
            //                "tabel" => "rek_cache_pembantu_hutang_biaya",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //            ),
            //            "hutang valas ke konsumen" => array(
            //                "comName" => "RekeningPembantuCustomerValas",
            //                "tabel" => "rek_cache_pembantu_hutang_valas_ke_konsumen",
            //                "externId" => "customers_id",
            //                "externNama" => "customers_nama",
            //            ),

        );

        $no = 0;
        $arrRekPembantu = array();
        if (sizeof($pairRekeningPembantu) > 0) {

            foreach ($pairRekeningPembantu as $rek => $pSpec) {

                $tmpPembantu = $arrResultEx;
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantu) > 0) {
                    foreach ($tmpPembantu as $tpSpec) {
                        $no++;

                        $arrRekPembantu[$no]["comName"] = $pSpec["comName"];
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "cabang_id" => $cabang_id,
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,

                            "qty" => $tpSpec->unit_af,
                            "extern2_id" => 0,
                            "extern2_nama" => 0,
                        );
                        $arrRekPembantu[$no]["loop"] = $loop;
                        $arrRekPembantu[$no]["static"] = $static;
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening pembantu items">
        $pairRekeningPembantuItems = array(
//            "persediaan supplies" => array(
//                "comName" => "RekeningPembantuSupplies",
//                "comFifo" => "FifoSupplies",
//                "comFifoAvg" => "FifoAverage",
//                "comLocker" => "LockerStockSupplies",
//
//                // "tabel" => "rek_cache_pembantu_supplies",
//                "externId" => "produk_id",
//                "externNama" => "produk_nama",
//                "jenis" => "supplies",
//                "lockerState" => "active",
//                "gudangId" => $gudang_id,
//            ),

            //            "persediaan supplies" => array(
            //                "comName" => "RekeningPembantuSupplies",
            //                "comFifo" => "FifoSupplies",
            //                "comFifoAvg" => "FifoAverage",
            //                "comLocker" => "LockerStockSupplies",
            //
            //                "tabel" => "rek_cache_pembantu_produk_supplies",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //                "jenis" => "supplies",
            //                "lockerState" => "active",
            //                "gudangId" => $gudang_id,
            //            ),
        );
        $pairRekeningPembantuEfisiensiItems = array(
            //            "efisiensi operasional" => array(
            //                "comName" => "RekeningPembantuEfisiensi",
            //
            //                "tabel" => "rek_cache_pembantu_efisiensi_produk",
            //                "externId" => "produk_id",
            //                "externNama" => "produk_nama",
            //                "jenis" => "produk",
            //                "lockerState" => "active",
            //                "gudangId" => $gudang_id,
            //            ),
        );

        $no = 0;
        $arrFifoItems = array();
        $arrFifoItemsAvg = array();
        $arrLockerItems = array();
        $arrRekPembantuItems = array();
        if (sizeof($pairRekeningPembantuItems) > 0) {
            foreach ($pairRekeningPembantuItems as $rek => $pSpec) {
                //            $arrFilter = array(
                //                "periode='forever'",
                //            );
                //            foreach ($arrFilter as $filter) {
                //                $this->db->where($filter);
                //            }
                //            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();
                //            arrPrint($tmpPembantuItems);
                //            mati_disini();
                //  pembaca file excell....................................................


                $tmpPembantuItems = $arrResultEx;
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantuItems) > 0) {
                    foreach ($tmpPembantuItems as $tpSpec) {
                        $no++;

                        $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;

                        //<editor-fold desc="rek_pembantu">
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "produk_qty" => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrRekPembantuItems[$pSpec["comName"]][$no]["loop"] = $loop;
                        $arrRekPembantuItems[$pSpec["comName"]][$no]["static"] = $static;
                        //</editor-fold>

                        //<editor-fold desc="fifo fisik">
                        $loop = array();
                        $static = array(
                            "produk_id" => $tpSpec->$pSpec["externId"],
                            "produk_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "unit" => $tpSpec->unit_af,
                            "jml_nilai" => $tpSpec->nilai_af,
                            "hpp" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrFifoItems[$pSpec["comFifo"]][$no]["loop"] = $loop;
                        $arrFifoItems[$pSpec["comFifo"]][$no]["static"] = $static;
                        //</editor-fold>

                        //<editor-fold desc="fifo average">
                        $loop = array();
                        $static = array(
                            "produk_id" => $tpSpec->$pSpec["externId"],
                            "nama" => $tpSpec->$pSpec["externNama"],
                            "jml" => $tpSpec->unit_af,
                            "jml_nilai" => $tpSpec->nilai_af,
                            "hpp" => $nilai_item,
                            "jenis" => $pSpec["jenis"],
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["loop"] = $loop;
                        $arrFifoItemsAvg[$pSpec["comFifoAvg"]][$no]["static"] = $static;
                        //</editor-fold>

                        //<editor-fold desc="locker items">
                        $loop = array();
                        $static = array(
                            "produk_id" => $tpSpec->$pSpec["externId"],
                            "nama" => $tpSpec->$pSpec["externNama"],
                            "jumlah" => $tpSpec->unit_af,
                            "jenis" => $pSpec["jenis"],
                            "state" => $pSpec["lockerState"],
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrLockerItems[$pSpec["comLocker"]][$no]["loop"] = $loop;
                        $arrLockerItems[$pSpec["comLocker"]][$no]["static"] = $static;
                        //</editor-fold>
                    }
                }
            }
        }

        $arrRekPembantuEfisiensiItems = array();
        if (sizeof($pairRekeningPembantuEfisiensiItems) > 0) {
            foreach ($pairRekeningPembantuEfisiensiItems as $rek => $pSpec) {
                //            $arrFilter = array(
                //                "periode='forever'",
                //            );
                //            foreach ($arrFilter as $filter) {
                //                $this->db->where($filter);
                //            }
                //            $tmpPembantuItems = $this->db->get($pSpec["tabel"])->result();

                $tmpPembantuItems = array();
                $loop = array();
                $static = array();
                if (sizeof($tmpPembantuItems) > 0) {
                    foreach ($tmpPembantuItems as $tpSpec) {
                        $no++;

                        $nilai_item = $tpSpec->unit_af > 0 ? ($tpSpec->nilai_af / $tpSpec->unit_af) : 0;


                        //<editor-fold desc="rek_pembantu">
                        $loop[$rek] = $tpSpec->nilai_af;
                        $static = array(
                            "extern_id" => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "fulldate" => $fulldateNow,
                            "dtime" => $dtimeNow,
                            "produk_qty" => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                        );
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["loop"] = $loop;
                        $arrRekPembantuEfisiensiItems[$pSpec["comName"]][$no]["static"] = $static;
                        //</editor-fold>
                    }
                }
            }
        }
        //</editor-fold>

        //<editor-fold desc="rekening besar">
        $arrRekeningAlias = array(
            "hutang dagang ke pusat" => "hutang ke pusat",
            "r/l lain lain" => "rugilaba lain lain",
        );


        $tmpCache = array(
            "id" => 4,
            "rekening" => "hutang dagang",
            "periode" => "forever",
            "debet_saldo" => 0,
            "kredit_saldo" => 0,
            "after_saldo" => $persediaan_total,
            "keterangan" => "stok produk awal",
            //            "tgl" => 16,
            //            "bln" => 1,
            //            "thn" => 2019,
            "dtime" => $dtimeNow,
            "cabang_id" => $cabang_id,
        );
        $tmp = array();
        $tmp[] = (object)$tmpCache;

        $arrRekCache = array();
        $arrAkunting = array();
        if (sizeof($tmp) > 0) {
            $loop = array();
            $static = array();
            //            $arrRekCache[0]["comName"] = "Rekening";
            foreach ($tmp as $rSpec) {
                $rek_nama = array_key_exists($rSpec->rekening, $arrRekeningAlias) ? $arrRekeningAlias[$rSpec->rekening] : $rSpec->rekening;

                if (!isset($arrRekCache[$rSpec->cabang_id]["comName"])) {
                    $arrRekCache[$rSpec->cabang_id]["comName"] = "Rekening";
                }
                if (!isset($arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama])) {
                    $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = 0;
                }
                $arrRekCache[$rSpec->cabang_id]["loop"][$rek_nama] = abs($rSpec->after_saldo);
                $arrRekCache[$rSpec->cabang_id]["static"]["cabang_id"] = $cabang_id;
                $arrRekCache[$rSpec->cabang_id]["static"]["fulldate"] = $fulldateNow;
                $arrRekCache[$rSpec->cabang_id]["static"]["dtime"] = $dtimeNow;
            }

            //            $statics = array(
            //                "cabang_id" => $cabang_id,
            //                "fulldate" => $fulldateNow,
            //                "dtime" => $fulldateNow,
            //            );
            //            $arrAkunting[1]["comName"] = "RugiLaba";
            //            $arrAkunting[1]["loop"] = array();
            //            $arrAkunting[1]["static"] = $statics;
            //
            //            $arrAkunting[2]["comName"] = "Neraca";
            //            $arrAkunting[2]["loop"] = array();
            //            $arrAkunting[2]["static"] = $statics;
        }
        //</editor-fold>

        //arrPrint($arrRekPembantuItems);
        //arrPrint($arrFifoItems);
        //arrPrint($arrFifoItemsAvg);
        //arrPrint($arrLockerItems);
        //arrPrint($arrRekPembantu);
        //arrPrint($arrRekPembantuEfisiensiItems);
        //        arrPrint($arrRekCache);


        $this->db->trans_begin();

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
            //            arrPrint($arrRekCache);

            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
        //        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            foreach ($arrRekPembantu as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                //                $cr->pair($rSpec);
                //                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            foreach ($arrFifoItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            foreach ($arrFifoItemsAvg as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComLocker">
        if (sizeof($arrLockerItems) > 0) {
            foreach ($arrLockerItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuItems">
        if (sizeof($arrRekPembantuItems) > 0) {
            foreach ($arrRekPembantuItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                $cr->pair($rSpec);
                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu");
        }
        //</editor-fold>

        //<editor-fold desc="ComPembantuEfisiensiItems">
        if (sizeof($arrRekPembantuEfisiensiItems) > 0) {
            foreach ($arrRekPembantuEfisiensiItems as $comName => $rSpec) {
                $modelName = "Com" . $comName;
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;
                //                cekBiru(":: masuk pair: $modelName");
                //                $cr->pair($rSpec);
                //                $cr->exec();
            }
        }
        else {
            cekHitam("tidak pair rekening pembantu efisiensi produk");
        }
        //</editor-fold>


        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI  BELUM DICOMMIT");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
        cekHijau("<h1>done</h1>");

    }


    public function genPaymentSource()
    {

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $arrSource = array(
            "fg" => array(
                "jenisSrc" => "467",
                "filter" => array(
                    "status=0",
                    "trash=0",
                ),
                "inject" => array(
                    "jenisTr_new" => ".467",
                    "placeID" => "cabang_id",
                    "placeName" => "cabang_nama",
                    "pihakID" => "extern_id",
                    "pihakName" => "extern_nama",
                    "extLabel" => "",
                    "nilai_credit" => "kredit",
                    "nilai_cash" => "kredit",
                ),
            ),
        );
        $tableName = "_rek_pembantu_supplier_cache";
        $arrFilters = array(
            "rekening" => "hutang dagang",
            "periode" => "forever",
            "kredit>" => "0",
        );
        $this->db->where($arrFilters);
        $tmp = $this->db->get($tableName)->result();

        $tableName = "__rek_pembantu_supplier__hutang_dagang";
        $arrFilters = array(
            "rekening" => "hutang dagang",
            "jenis=" => "999_1",

        );
        $this->db->where(array());
        $this->db->where($arrFilters);
        $tmpM = $this->db->get($tableName)->result();
        $trIDs = array();
        foreach ($tmpM as $mSpec){
            $trIDs[$mSpec->extern_id] = $mSpec->transaksi_id;
        }





        $tr->setFilters(array());
        $tr->addFilter("label='hutang dagang'");
        $tr->addFilter("sisa>0");
        $tbls = $tr->getTableNames();

        $tr->setTableName($tbls['paymentSrc']);
        $rsltPym = $tr->lookupAll()->result();
        $extID = array();
        foreach ($rsltPym as $rsltSpec) {
            $extID[$rsltSpec->extern_id] = $rsltSpec->extern_id;
        }
//cekBiru($this->db->last_query());
//arrPrint($extID);
//arrPrint($tmp);
//mati_disini();
        if (sizeof($arrSource) > 0) {
            foreach ($arrSource as $k => $sSpec) {
                // injector key dan value, mirip gerbang value itu lho....
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $i => $tmpSpec) {

                        if (isset($sSpec['inject'])) {
                            foreach ($sSpec['inject'] as $key => $val) {
                                $tmp[$i]->$key = makeValue($val, (array)$tmpSpec, (array)$tmpSpec, 0);
                            }
                        }

                    }
                }
                $mainTransaksi[$k] = $tmp;
            }
        }
//arrPrint($mainTransaksi);
//mati_disini();


        $this->db->trans_begin();

//        $mainTransaksi = array();
        if (sizeof($mainTransaksi) > 0) {
            $no = 0;
            foreach ($mainTransaksi as $jSpec) {
                foreach ($jSpec as $mSpec) {
                    $insertID = $mSpec->id;
                    $stepCode_old = $mSpec->jenis;
                    $stepCode = $mSpec->jenisTr_new;
                    $paymentSources = $this->config->item("payment_source");

                    $no++;
                    if (array_key_exists($stepCode, $paymentSources)) {
                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];

                                if(!in_array($mSpec->$externSrc['id'], $extID)){
                                    $insertID_n = isset($trIDs[$mSpec->$externSrc['id']]) ? $trIDs[$mSpec->$externSrc['id']] : $insertID;
                                    $tr->writePaymentSrc($insertID_n, array(
                                            "jenis" => $stepCode,
                                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],

                                            "extern_id" => $mSpec->$externSrc['id'],
                                            "extern_nama" => $mSpec->$externSrc['nama'],
                                            "nomer" => isset($mSpec->nomer) ? $mSpec->nomer : "999." . $no,

                                            "label" => $paymentSrcConfig['label'],

                                            "tagihan" => $mSpec->$valueSrc,
                                            "terbayar" => 0,

                                            "sisa" => $mSpec->$valueSrc,
                                            "cabang_id" => $mSpec->placeID,
                                            "cabang_nama" => $mSpec->placeName,

                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "dtime" => date("Y-m-d H:i:s"),
                                            "fulldate" => date("Y-m-d"),

                                            "valas_id" => (isset($externSrc['valasId']) && isset($mSpec->$externSrc['valasId'])) ? $mSpec->$externSrc['valasId'] : '',
                                            "valas_nama" => (isset($externSrc['valasLabel']) && isset($mSpec->$externSrc['valasLabel'])) ? $mSpec->$externSrc['valasLabel'] : '',
                                            "valas_nilai" => (isset($externSrc['valasValue']) && isset($mSpec->$externSrc['valasValue'])) ? $mSpec->$externSrc['valasValue'] : '',
                                            "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($mSpec->$externSrc['valasTagihan'])) ? $mSpec->$externSrc['valasTagihan'] : '',
                                            "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($mSpec->$externSrc['valasTerbayar'])) ? $mSpec->$externSrc['valasTerbayar'] : '',
                                            "sisa_valas" => (isset($externSrc['valasSisa']) && isset($mSpec->$externSrc['valasSisa'])) ? $mSpec->$externSrc['valasSisa'] : '',
                                        )
                                    );
                                    cekOrange($this->db->last_query());
//                                cekHere(" update paymebnt source line ".__LINE__);
                                }


                            }
                        }
//                        cekBiru("[$no] [trID: $insertID] - OLD Code: $stepCode_old, NEW Code: $stepCode, DONE...");
                    }
                    else {
                        cekBiru("TIDAK melakukan building payment source...");
                    }
                }
            }
        }


        cekMerah("DONE :: " . get_class($this));
        mati_disini("CILUKBAAA.... TESTING LAGI... HI HI HI");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }
        else {
            $this->db->trans_commit();
        }
    }
}
