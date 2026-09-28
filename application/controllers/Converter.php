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
        echo ($this->router->method) . "<hr>";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukRek?debuger=1'> ";
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
        $cabang_id = "-1";
        $gudang_id = "-1";
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produkKoloms = array(
            "kode" => "kode_brg",
            "nama" => "nama_brg",
            "merek_nama" => "merk",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "keterangan" => "keterangan",
            "limit" => "qtylimit",
            "folders_kode" => "kode_klp",
        );

        $dbProds = $pr->lookupAll()->result();
        $dbProdDatas = array();
        foreach ($dbProds as $dbProd) {
            $dbProdDatas[$dbProd->kode] = $dbProd;
        }

        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        // $datas = $this->xlsx->reader($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);

        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region#1 menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }

        $koloms = $headers[$data_header];

        // arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        // arrPrintWebs($sheet);
        $datas = array();
        $rows = array();
        foreach ($sheet as $row) {

            if ($numrow >= $data_start) {
                // arrPrint($row);
                // matiHere("hop");
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            // arrPrintPink($datas);
            // matiHere();
            $numrow++;
        }

        // arrPrint($koloms);
        // arrPrint($sheet);
        // arrPrintPink($datas);
        // arrPrintPink($dbProdDatas);
        // matiHere(__LINE__);
        $persediaan_total = 0;
        $tmpResultEx = array();
        $tmpLains = array();
        $xl_qty = 0;
        foreach ($datas as $k => $dataSpec) {
            // arrPrintPink($dataSpec);
            $xl_kode = $dataSpec['kode_brg'];
            // $xl_qty = $dataSpec['qtyt_ak'] > 0 ? $dataSpec['qtyt_ak'] : 0;
            $xl_qty = $dataSpec['qtyg_ak'] > 0 ? $dataSpec['qtyg_ak'] : 0;
            // $xl_qty = $dataSpec['qty_total'] > 0 ? $dataSpec['qty_total'] : 0;
            // $xl_qty = $dataSpec['qtyk_ak'] > 0 ? $dataSpec['qtyk_ak'] : 0;

            $xl_harga = $dataSpec['hpp'];

            $dbProdData = isset($dbProdDatas[$xl_kode]) ? $dbProdDatas[$xl_kode] : "";

            $prod_id = isset($dbProdData->id) ? $dbProdData->id : 0;
            // $rows["prod_id"] = $prod_id->id;

            $nilai = $xl_harga * $xl_qty;
            // $nilai =  $dataSpec['qty'];
            // cekHijau("$xl_kode: $xl_qty **  $prod_id");
            // matiHere(__LINE__ . " $xl_kode");
            if (($prod_id > 0) && ($xl_qty > 0)) {
                // if ( ($xl_qty > 0) ) {
                if ($nilai < 1) {
                    matiDisini("@" . __LINE__ . " -------------------- hpp $xl_kode dr file excel tidak terdeteksi loh -----------------");
                }
                // if (isset($dataSpec['p_id']) && $dataSpec['p_id'] > 0) {
                $tmp = array(
                    // "id"          => $prod_id,
                    "produk_id" => $prod_id,
                    "produk_kode" => $dbProdData->kode,
                    "produk_nama" => $dbProdData->nama,
                    "unit_af" => $xl_qty,
                    "nilai_af" => $nilai,
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx[$prod_id] = (object)$tmp;
                // }
            }
            else {
                // if($xl_qty > 0){

                $tmpLains[$xl_kode] = $xl_qty;
                // }
            }

            $persediaan_total += $nilai;
        }
        $arrResultEx = $tmpResultEx;

        $xl_jml_data = sizeof($sheet);
        $new_jml_data = sizeof($datas);
        $jml_yg_diimport = sizeof($tmpResultEx);
        $jml_dlm_db = sizeof($dbProdDatas);
        cekHere("file: $name <br>jml_data excel = $xl_jml_data akan dimasukan : $new_jml_data yg diimport : $jml_yg_diimport jml data dB: $jml_dlm_db");

        if ($cek == 1) {
            cekOrange("-------------------------------memasukan data-----------------------------------");
        }
        else {
            // arrPrintPink($tmpLains);
            // cekBiru($tmpLains);
            // arrPrint($datas);
            // cekHijau($persediaan_total);
            // arrPrint($dbProdDatas);
            arrPrintWebs($arrResultEx);
            mati_disini(":: $persediaan_total ::");
        }


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
                        // "jenis"    => "467",
                        // "transaksi_id"    => "-2",
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
        $tmpCache_2 = array(
            "id" => 4,
            "rekening" => "modal",
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
        $tmp[] = (object)$tmpCache_2;
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

        // mati_disini(__LINE__);


        $this->db->trans_begin();

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
            //            arrPrint($arrRekCache);
            cekHitam("ComRekening @" . __LINE__);
            arrPrint($arrRekCache);
            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
            cekLime(__liNE__);
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
        //        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            cekHitam("ComRekeningPembantu @" . __LINE__);
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
        // mati_disini(__LINE__);
        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            cekHitam("ComFifo Fisik @" . __LINE__);
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
        // mati_disini(__LINE__);
        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            cekHitam("ComFifo Average @" . __LINE__);
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
            cekHitam("ComLocker @" . __LINE__);
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
        cekMerah("-- DONE - SUDAH COMMIT--");
    }

    /* -----------------
     * EXECUTOR VERSI LAMA
     * -----------------*/
    public function __importProdukRek($arrSource = array())
    {
        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "-1";
        $gudang_id = "-1";
        $this->load->model("Mdls/MdlProduk");
        //        $this->load->model("MdlTransaksi");
        $pr = new MdlProduk();
        $produkKoloms = array(
            "kode" => "kode_brg",
            "nama" => "nama_brg",
            "merek_nama" => "merk",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "keterangan" => "keterangan",
            "limit" => "qtylimit",
            "folders_kode" => "kode_klp",
        );

        $dbProds = $pr->lookupAll()->result();
        $dbProdDatas = array();
        foreach ($dbProds as $dbProd) {
            $dbProdDatas[$dbProd->kode] = $dbProd;
        }

        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        // $datas = $this->xlsx->reader($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);

        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region#1 menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }

        $koloms = $headers[$data_header];

        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        $rows = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                // matiHere("hop");
                foreach ($koloms as $kolom => $kalias) {
                    $xlsValue = strval($row[$kolom]);
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrint($koloms);
        // arrPrintPink($datas);
        // arrPrintPink($dbProdDatas);
        // matiHere();

        //        arrPrint($datas);
        //        matiHere();
        $persediaan_total = 0;
        $persediaan_total_x = 0;
        $tmpResultEx = array();
        $tmpResultEx_ = array();
        $tmpResultEx__ = array();
        $tmpResultEx___ = array();
        $tmpResultEx____ = array();

        $dbProdDataO = array();
        $listProdukMinus = array();
        $dataNotebook = array();
        foreach ($datas as $k => $dataSpec) {

            $xl_kode = $dataSpec['kode_brg'];

            $xl_qty = isset($dataSpec['qtyt_ak']) ? $dataSpec['qtyt_ak'] : 0;
            $x2_qty = isset($dataSpec['qtyg_ak']) ? $dataSpec['qtyg_ak'] : 0;

            //            $xl_harga = isset($dataSpec['hargarata']) && $dataSpec['hargarata'] > 0 ?  $dataSpec['hargarata'] : $dataSpec['hargapokok'];
            $xl_harga = $dataSpec['hargapokok'];

            $dbProdData = $dbProdDatas[$xl_kode];

            $prod_id = isset($dbProdData->id) ? $dbProdData->id : 0;
            // $rows["prod_id"] = $prod_id->id;
            $dbProdDataO[$prod_id] = $dbProdData;
            $dataNotebook[$xl_kode] = $dbProdData;
            //            $dbProdDataO[$prod_id] = $dbProdData

            //ambil dari dua colom
            $nilai = ($xl_qty + $x2_qty) > 0 ? ($xl_qty + $x2_qty) * $xl_harga : 0;

            // $nilai =  $dataSpec['qty'];
            // cekHijau("$xl_kode: $prod_id");
            // matiHere(__LINE__);
            if (($prod_id > 0) && (($xl_qty + $x2_qty) > 0)) {

                $tmp = array(
                    // "id"          => $prod_id,
                    "produk_id" => $prod_id,
                    "produk_nama" => trim($dbProdData->nama),
                    "kode_barang" => $xl_kode,
                    "unit_af" => ($xl_qty + $x2_qty),
                    "nilai_af" => $nilai,
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx[$prod_id] = (object)$tmp;

            }
            else {

                $tmp = array(
                    // "id"          => $prod_id,
                    "produk_id" => $prod_id,
                    "produk_nama" => trim($dbProdData->nama),
                    "unit_af" => ($xl_qty + $x2_qty),
                    "nilai_af" => $nilai,
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx_[$prod_id] = (object)$tmp;

            }

            if ($prod_id == 0) {
                $tmpss = array(
                    // "id"          => $prod_id,
                    "produk_id" => $prod_id,
                    "produk_nama" => trim($dbProdData->nama),
                    "unit_af" => ($xl_qty + $x2_qty),
                    "nilai_af" => $nilai,
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx__[$xl_kode] = (object)$tmpss;
            }

            if (($prod_id == 0) && (($xl_qty + $x2_qty) > 0)) {
                $tmpss = array(
                    // "id"          => $prod_id,
                    "produk_id" => $prod_id,
                    "produk_nama" => trim($dbProdData->nama),
                    "unit_af" => ($xl_qty + $x2_qty),
                    "nilai_af" => $nilai,
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx___[$xl_kode] = (object)$tmpss;
            }

            if ((($xl_qty + $x2_qty) < 0)) {

                $nilai_x = ($xl_qty + $x2_qty) < 0 ? (($xl_qty + $x2_qty)) * $xl_harga : 0;

                $tmpssx = array(
                    // "id"          => $prod_id,
                    "produk_id" => $prod_id,
                    "produk_nama" => trim($dbProdData->nama),
                    "unit_af" => ($xl_qty + $x2_qty),
                    "unit_harga" => $xl_harga,
                    "nilai_af" => $nilai_x,
                    "rekening" => "persediaan produk",
                    "cabang_id" => $cabang_id,
                );
                $tmpResultEx____[$xl_kode] = (object)$tmpssx;


                $arrPrdMinus = array(
                    "kode" => $xl_kode,
                    "produk_id" => $prod_id,
                    "nama" => trim($dbProdData->nama),
                    "merek_nama" => trim($dbProdData->merek_nama),
                    "no_part" => trim($dbProdData->no_part),
                    "satuan" => trim($dbProdData->satuan),
                    "unit_af" => ($xl_qty + $x2_qty),
                    "unit_harga" => $xl_harga,
                    "nilai_af" => $nilai_x
                );

                $listProdukMinus[$xl_kode] = (object)$arrPrdMinus;
                $persediaan_total_x += $nilai_x;
            }

            $persediaan_total += $nilai;

        }

        $dbProdDataX = array();
        $dbProdDataXO = array();
        foreach ($dbProdDatas as $prd_kode => $dataSpecs) {

            if (!isset($dataNotebook[$prd_kode])) {
                $dbProdDataX[$prd_kode] = $dataSpecs;
            }
            else {
                $dbProdDataXO[$prd_kode] = $dataSpecs;
            }

        }

        $arrResultEx = $tmpResultEx;
        $arrResultEx_ = $tmpResultEx_;
        $arrResultEx__ = $tmpResultEx__;
        $arrResultEx___ = $tmpResultEx___;
        $arrResultEx____ = $tmpResultEx____;

        // cekHijau($persediaan_total);
        //         arrPrint( $dbProdDataX );

        //        arrPrint($arrResultEx__);
        //        arrPrintWebs($arrResultEx___);
        //        arrPrint($listProdukMinus);
        $gakAdanama = array();
        if (sizeof($dbProdDataX) > 0) {
            foreach ($dbProdDataX as $produk_kode => $produk_data) {
                if (trim($produk_data->nama)) {
                    cekUngu('"\'' . $produk_data->kode . '";' . $produk_data->nama . ';"\'' . $produk_data->merek_nama . '";"\'' . $produk_data->no_part . '";"' . $produk_data->satuan . '";');
                }
                else {
                    $gakAdanama[$produk_kode] = $produk_data;
                }
            }
        }

        arrPrint($gakAdanama);

        //        if( sizeof($listProdukMinus)>0 ){
        //            foreach($listProdukMinus as $produk_kode => $produk_data ){
        //                cekOrange( '"\''.$produk_data->kode . '";' . $produk_data->nama . ';"\'' . $produk_data->merek_nama . '";"\'' . $produk_data->no_part . '";"' . $produk_data->satuan . '";"' . $produk_data->unit_harga . '";"' . $produk_data->unit_af . '";"' . $produk_data->nilai_af . '";');
        //            }
        //        }
        //        arrPrint($dbProdDataX);
        //        arrPrintWebs($dbProdDataXO);
        //        arrPrintWebs($arrResultEx____);

        cekUngu("EXCEL: " . sizeof($datas) . " Items");
        cekOrange("DATABASE: " . sizeof($dbProdDatas) . " Items");
        cekHijau("EXCEL VS DATABASE: " . sizeof($dbProdDataO) . " Items");
        cekMerah("YG TIDAK ADA DI EXCEL: " . sizeof($dbProdDataX) . " Items");

        // mati_disini(":: $persediaan_total ::");


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
        $tmpCache_2 = array(
            "id" => 4,
            "rekening" => "modal",
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
        $tmp[] = (object)$tmpCache_2;
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

        // mati_disini(__LINE__);


        $this->db->trans_begin();

        //<editor-fold desc="Transaksi inisisasi persediaan">
        //<editor-fold desc="main arrays">
        $this->jenisTr = $jenisTr = "466";
        $cCode = "_TR_" . $this->jenisTr;
        $main_arrays["nilai"] = $persediaan_total;
        $main_arrays["olehID"] = "2";
        $main_arrays["olehName"] = "holding_";
        $main_arrays["placeID"] = "-1";
        $main_arrays["placeName"] = "pusat";
        $main_arrays["cabangID"] = "-1";
        $main_arrays["cabangName"] = "pusat";
        $main_arrays["gudangID"] = "-1";
        $main_arrays["gudangName"] = "default center warehouse";
        $main_arrays["jenisTr"] = "583";
        $main_arrays["jenisTrMaster"] = "583";
        $main_arrays["jenisTrTop"] = "583";
        $main_arrays["jenisTrName"] = "distribution";
        $main_arrays["stepNumber"] = "1";
        $main_arrays["stepCode"] = "583";
        $main_arrays["dtime"] = "2020-09-25 14:16:28";
        $main_arrays["fulldate"] = "2020-09-25";
        $main_arrays["description"] = "";
        $main_arrays["divID"] = "4";
        $main_arrays["divName"] = "default";
        $main_arrays["supplierID"] = "10";
        $main_arrays["supplierName"] = "PT ASIAN BEARINDO PRIMA";
        $main_arrays["divName"] = "default";
        $main_arrays["jenis"] = "583";
        $main_arrays["transaksi_jenis"] = "583";
        $main_arrays["next_step_code"] = "";
        $main_arrays["next_group_code"] = "";
        $main_arrays["step_number"] = "1";
        $main_arrays["step_current"] = "0";
        $main_arrays["longitude"] = "";
        $main_arrays["lattitude"] = "";
        $main_arrays["accuracy"] = "";
        $main_arrays["harga"] = "0";
        $main_arrays["subtotal"] = "0";
        $main_arrays["discount_persen"] = "0";
        $main_arrays["discount_qty"] = "0";
        $main_arrays["stok_all"] = "1512";
        $main_arrays["stok_cabang"] = "0";
        $main_arrays["code"] = "39.002";
        $main_arrays["stok"] = "1511";
        $main_arrays["new_sisa"] = "0";
        $main_arrays["pihakID"] = "1";
        $main_arrays["pihakName"] = "OUTLET TERMINAL";
        $main_arrays["pihakName2"] = "OUTLET TERMINAL";
        $main_arrays["pihakDisc"] = "";
        $main_arrays["cabang2ID"] = "1";
        $main_arrays["cabang2Name"] = "OUTLET TERMINAL";
        $main_arrays["place2ID"] = "1";
        $main_arrays["place2Name"] = "OUTLET TERMINAL";
        $main_arrays["branchDetails"] = "1";
        $main_arrays["branchDetails__label"] = "OUTLET TERMINAL";
        $main_arrays["branchDetails__nama"] = "OUTLET TERMINAL";
        $main_arrays["gudang2ID"] = "-10";
        $main_arrays["gudang2ID__label"] = "default warehouse at branch #1";
        $main_arrays["gudang2ID__name"] = "default warehouse at branch #1";
        //</editor-fold>

        $_SESSION[$cCode]['main'] = $main_arrays;
        cekBiru("DARI STOK PLUS: " . number_format($persediaan_total));
        cekPink("DARI STOK MINUS: " . number_format($persediaan_total_x));
        cekPink("ITEMS STOK MINUS: " . sizeof($listProdukMinus));
        //        cekHijau( number_format(($persediaan_total*1) + ($persediaan_total_x*-1)) );

        cekHitam("cek line:: " . __LINE__);
        //
        //region penomoran receipt
        //<editor-fold desc="==========penomoran">
        $this->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType("transaksi");

        $counterForNumber = array($this->config->item('heTransaksi_core')[$this->jenisTr]['formatNota']);
        if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'])) {
            die(__LINE__ . " Used number should be registered in 'counters' config as well");
        }
        echo "<div style='background:#ff7766;'>";
        foreach ($counterForNumber as $i => $cRawParams) {
            $cParams = explode("|", $cRawParams);
            $cValues = array();
            foreach ($cParams as $param) {
                $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
            }
            $cRawValues = implode("|", $cValues[$i]);
            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

        }
        echo "</div style='background:#ff7766;'>";

        $stepNumber = 1;

        $tmpNomorNota = $paramSpec['paramString'];


        if (isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2])) {
            $nextProp = array(
                "num" => 2,
                "code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['target'],
                "label" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['label'],
                "groupID" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['userGroup'],
            );
        }
        else {
            $nextProp = array(
                "num" => 0,
                "code" => "",
                "label" => "",
                "groupID" => "",
            );
        }

        //</editor-fold>
        //endregion
        //
        //region dynamic counters
        // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
        $cn = new CustomCounter("transaksi");
        $cn->setType("transaksi");
        $configCustomParams = $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'];
        $configCustomParams[] = "stepCode";
        //arrPrint($configCustomParams);
        if (sizeof($configCustomParams) > 0) {
            $cContent = array();
            foreach ($configCustomParams as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                switch ($paramSpec['id']) {
                    case 0: //===counter type is new
                        $paramKeyRaw = print_r($cParams, true);
                        $paramValuesRaw = print_r($cValues[$i], true);
                        $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                        break;
                    default: //===counter to be updated
                        $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                        break;
                }
                //echo "<hr>";
            }
        }
        $appliedCounters = base64_encode(serialize($cContent));
        $appliedCounters_inText = print_r($cContent, true);
        //mati_disini();

        //region addition on master
        $addValues = array(
            'counters' => $appliedCounters,
            'counters_intext' => $appliedCounters_inText,
            'nomer' => $tmpNomorNota,
            'dtime' => date("Y-m-d H:i:s"),
            'fulldate' => date("Y-m-d"),
            "step_avail" => sizeof($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps']),
            "step_number" => 1,
            "step_current" => 1,
            "next_step_num" => $nextProp['num'],
            "next_step_code" => $nextProp['code'],
            "next_step_label" => $nextProp['label'],
            "next_group_code" => $nextProp['groupID'],
            "tail_number" => 1,
            "tail_code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],


        );
        foreach ($addValues as $key => $val) {
            $_SESSION[$cCode]['tableIn_master'][$key] = $val;
        }
        //endregion

        //region addition on detail
        $addSubValues = array(
            "sub_step_number" => 1,
            "sub_step_current" => 1,
            "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']),
            "next_substep_num" => $nextProp['num'],
            "next_substep_code" => $nextProp['code'],
            "next_substep_label" => $nextProp['label'],
            "next_subgroup_code" => $nextProp['groupID'],
            "sub_tail_number" => 1,
            "sub_tail_code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],


        );
        foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
            foreach ($addSubValues as $key => $val) {
                $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
            }
        }
        //endregion
        // </editor-fold>
        //endregion

        //

        //<editor-fold desc="Description">
        if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {

            $_SESSION[$cCode]['tableIn_master']['status_4'] = 11;
            $_SESSION[$cCode]['tableIn_master']['trash_4'] = 0;


            $tr = new MdlTransaksi();
            $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
            showLast_query("lime");
            $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $_SESSION[$cCode]['tableIn_master']);
            $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
            $_SESSION[$cCode]['main']['nomer'] = $insertNum;
            if ($insertID < 1) {
                die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
            }
            //==transaksi_id dan nomor nota diinject kan ke gate utama
            $injectors = array(
                "transaksi_id" => $insertID,
                "nomer" => $tmpNomorNota,
            );
            $arrInjectorsTarget = array(
                "items",
                "items2_sum",
                "rsltItems",
            );
            foreach ($injectors as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
                foreach ($arrInjectorsTarget as $target) {
                    if (isset($_SESSION[$cCode][$target])) {
                        foreach ($_SESSION[$cCode][$target] as $xid => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                            if (isset($_SESSION[$cCode][$target][$id])) {
                                $_SESSION[$cCode][$target][$id][$key] = $val;
                            }
                        }
                    }
                }
            }

            //===signature
            $dwsign = $tr->writeSignature($insertID, array(
                "nomer" => $_SESSION[$cCode]['main']['nomer'],
                "step_number" => 1,
                "step_code" => $this->jenisTr,
                "step_name" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'],
                "group_code" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'],
                "transaksi_id" => $insertID,
            )) or die("Failed to write signature");

            $idHis = array(
                $stepNumber => array(
                    "step" => $stepNumber,
                    "trID" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "counters" => $appliedCounters,
                    "counters_intext" => $appliedCounters_inText,
                ),
            );
            $idHis_blob = blobEncode($idHis);
            $idHis_intext = print_r($idHis, true);
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $insertID), array(
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],

                //===references
                "id_master" => $insertID,
                "id_top" => $insertID,
                "ids_prev" => "",
                "ids_prev_intext" => "",
                "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                "nomers_prev" => "",
                "nomers_prev_intext" => "",
                "jenises_prev" => "",
                "jenises_prev_intext" => "",
                "ids_his" => $idHis_blob,
                "ids_his_intext" => $idHis_intext,

            )) or die("Failed to update tr next-state!");
            cekHijau($this->db->last_query());

            $addValues = array(
                //===references
                "id_master" => $insertID,
                "id_top" => $insertID,
                "ids_prev" => "",
                "ids_prev_intext" => "",
                "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                "nomers_prev" => "",
                "nomers_prev_intext" => "",
                "jenises_prev" => "",
                "jenises_prev_intext" => "",
                "ids_his" => $idHis_blob,
                "ids_his_intext" => $idHis_intext,
            );
            foreach ($addValues as $key => $val) {
                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
            }

        }
        if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
            $inserMainValues = array();
            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
                foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                    if (isset($_SESSION[$cCode]['tableIn_master_values'][$key])) {
                        $inserMainValues[] = $tr->writeMainValues($insertID, array(
                            "key" => $key,
                            "value" => $_SESSION[$cCode]['tableIn_master_values'][$key],
                        ));
                    }
                }
            }

            if (sizeof($inserMainValues) > 0) {
                $arrBlob = blobEncode($inserMainValues);
                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
            }

        }
        if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
            $inserMainValues = array();
            foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                $inserMainValues[] = $tr->writeMainValues($insertID, array("key" => $key,
                    "value" => $val
                ));
            }

            if (sizeof($inserMainValues) > 0) {
                $arrBlob = blobEncode($inserMainValues);
                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
            }
        }
        if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
            foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                $tr->writeMainValues($insertID, array("key" => $key,
                    "value" => $val
                ));
                //                    cekkuning("making a clone for input key $key / $val");
                //                    $tmpTableIn=$_SESSION[$cCode]['tableIn_master'];
                //                    $replacers=array(
                //                        "nomer"=>$_SESSION[$cCode]['tableIn_master']['nomer']."_$key",
                //                    );
                //                    foreach($replacers as $key=>$val){
                //                        $tmpTableIn[$key]=$val;
                //                    }
                //                    $subInputInsertID = $tr->writeMainEntries($tmpTableIn);
            }
        }
        if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
            foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                $tr->writeMainFields($insertID, array("key" => $key,
                    "value" => $val
                ));
            }
        }
        if (isset($_SESSION[$cCode]['main_applets']) && sizeof($_SESSION[$cCode]['main_applets']) > 0) {
            foreach ($_SESSION[$cCode]['main_applets'] as $amdl => $aSpec) {
                $tr->writeMainApplets($insertID, array(
                    "mdl_name" => $amdl,
                    "key" => $aSpec['key'],
                    "label" => $aSpec['labelValue'],
                    "description" => $aSpec['description'],
                ));
            }
        }
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                $tr->writeMainElements($insertID, array(
                    "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                    "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                    "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                    "name" => $aSpec['name'],
                    "label" => $aSpec['label'],
                    "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                    "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                ));


                //==nebeng bikin inputLabels
                $currentValue = "";
                switch ($aSpec['elementType']) {
                    case "dataModel":
                        $currentValue = $aSpec['key'];
                        break;
                    case "dataField":
                        $currentValue = $aSpec['value'];
                        break;
                }
                if (array_key_exists($elName, $relOptionConfigs)) {
                    //					cekhijau("$eName terdaftar pada relInputs");


                    if (isset($relOptionConfigs[$elName][$currentValue])) {
                        if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                            foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                $inputLabels[$oValueName] = $oValSpec['label'];
                                if (isset($oValSpec['auth'])) {
                                    if (isset($oValSpec['auth']['groupID'])) {
                                        $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                    }
                                }
                            }
                        }
                    }
                    else {
                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                    }

                }

            }
        }
        if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {

            $insertIDs = array();
            $insertDeIDs = array();
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                if ($insertDetailID < 1) {
                    die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                }
                else {
                    $insertIDs[] = $insertDetailID;
                    $insertDeIDs[$insertID][] = $insertDetailID;
                }
                if ($epID != 999) {
                    $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                    if ($insertEpID < 1) {
                        die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertEpID;
                        $insertDeIDs[$epID][] = $insertEpID;
                    }
                }
                cekUngu($this->db->last_query());
            }


            if (sizeof($insertIDs) == 0) {
                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
            }
            else {
                $indexing_details = array();
                foreach ($insertDeIDs as $key => $numb) {
                    $indexing_details[$key] = $numb;
                }

                foreach ($indexing_details as $k => $arrID) {
                    $arrBlob = blobEncode($arrID);
                    $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                    cekOrange($this->db->last_query());
                }
            }
        }
        if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
            $insertIDs = array();
            foreach ($_SESSION[$cCode]['tableIn_detail2'] as $dSpec) {
                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                if ($epID != 999) {
                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                }
                cekUngu($this->db->last_query());
            }
        }
        if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
            $insertIDs = array();
            foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                if ($epID != 999) {
                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                }
            }
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) && sizeof($_SESSION[$cCode]['tableIn_detail_rsltItems']) > 0) {
            $insertIDs = array();
            foreach ($_SESSION[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                if ($epID != 999) {
                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                }
                cekUngu($this->db->last_query());
            }
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {

            $insertIDs = array();
            foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                        if (isset($_SESSION[$cCode]['tableIn_detail'][$pID])) {
                            $insertIDs[$pID][] = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));

                        }
                    }
                }
            }

            if (sizeof($insertIDs) > 0) {
                $arrBlob = blobEncode($insertIDs);
                $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
            }

        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
            foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                        $insertIDs[] = $tr->writeDetailValues($insertID, array(
                            "produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                            "produk_id" => $pID,
                            "key" => $key,
                            "value" => $dSpec[$src],
                        ));
                    }
                }
            }
        }
        //</editor-fold>

        //</editor-fold>
        matiHere(__LINE__);

        //<editor-fold desc="ComRekening">
        if (sizeof($arrRekCache) > 0) {
            //            arrPrint($arrRekCache);
            cekHitam("ComRekening @" . __LINE__);
            arrPrint($arrRekCache);
            foreach ($arrRekCache as $rSpec) {
                $modelName = "Com" . $rSpec["comName"];
                $modelLoad = "Coms/" . $modelName;
                $this->load->model($modelLoad);
                $cr = New $modelName;

                $cr->pair($rSpec);
                $cr->exec();
            }
            cekLime(__liNE__);
        }
        else {
            cekHitam("tidak pair rekening besar");
        }
        //        mati_disini("DONE...");
        //</editor-fold>

        //<editor-fold desc="ComRekeningPembantu Nilai">
        if (sizeof($arrRekPembantu) > 0) {
            cekHitam("ComRekeningPembantu @" . __LINE__);
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
        // mati_disini(__LINE__);
        //<editor-fold desc="ComFifo Fisik">
        if (sizeof($arrFifoItems) > 0) {
            cekHitam("ComFifo Fisik @" . __LINE__);
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
        // mati_disini(__LINE__);
        //<editor-fold desc="ComFifo Average">
        if (sizeof($arrFifoItemsAvg) > 0) {
            cekHitam("ComFifo Average @" . __LINE__);
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
            cekHitam("ComLocker @" . __LINE__);
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
        cekMerah("-- DONE - SUDAH COMMIT--");
    }

    // -------------------------------------
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
    // ------------------------------------------------------------------STOP-

    /* ------------------------------------------------------------------START-
     * IMPORTER DATA
     * ---------------------------------*/
    public function formCustomer()
    {
        $segment_4 = $this->uri->segment(3);
        $seg_4 = isset($segment_4) ? "/$segment_4" : "/none";
        echo ($this->router->method) . " <a class='pull-right' href='" . download_tpl('customer') . "' target='result'><i class='fa fa-download'></i> download template excel*</a><hr>";
        // echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukData/$seg_4' target='result'> ";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importCustomerDataPre/$seg_4' target='result'> ";
        echo "<div class='form-group'>";
        echo "<label>file input</label>";
        // echo "<button type='button' class='btn btn-danger'><i class='fa fa-download'></i> </button>";
        echo "<input type='file' name='fileExcel' required>";
        echo "<hr>";
        echo "<p>reader xlsx</p>";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
        echo "<input type='submit' name='submit_file' value='submit file' class='btn btn-primary'>";
        echo "</div>";

        echo "</form>";
    }

    /* ----------------------------------------------------
     * IPORDER DATA PRODUK
     * dan data pembantu produk
     * -----------------------------------------------------*/
    public function importCustomerData()
    {
        $segment_3 = $this->uri->segment(3);
        $toko_id = my_toko_id();
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlCustomer");
        $mainObj = $pr = new MdlCustomer();
        $produkKoloms = array(
            "nama" => "nama",
            "email" => "email",
            // "merk"       => "merek_nama",
            // "no_part"    => "no_part",
            // "satuan"     => "satuan",
            // "keterangan" => "keterangan",
            // "qtylimit"   => "limit",
            "phone" => "tlp_1",
            "alamat" => "alamat_1",
            "kabupaten/kota" => "kabupaten",
            "propinsi" => "propinsi",
            "Indonesia" => "country",
            "deposit" => "deposit",
            "loyalti point" => "loyalti_point",
            "total item" => "total_item",
            "dtime in" => "dtime_in",
            "top item" => "top_item",
            "level id" => "level_id",
            "mid" => "mid",
            "gender" => "gender",
            "total transaction" => "total_transaction",
        );

        $this->load->model("Mdls/MdlFolderProduk");
        $fp = new MdlFolderProduk();
        $fpKoloms = array(
            // "nama" => "kategori",
            // "toko_id" => $toko_id,
        );

        $this->load->model("Mdls/MdlSatuan");
        $st = new MdlSatuan();
        $satuanKoloms = array(
            // "nama" => "satuan",
            // "kode" => "satuan",
        );

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $merekKoloms = array(// "merek" => "nama"
        );

        $this->load->model("Mdls/MdlLokasiIndex");
        $lk = new MdlLokasiIndex();
        $lokasiKoloms = array(
            // "nama" => "kode_lkst",
            // "nama" => "kode_lksg",
        );

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext . "<hr> gantilah dengan file xlsx") : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = array_filter($headers[$data_header]);

        arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // arrPrintPink($datas);
        cekBiru("file: $name <br>dt-excel: " . sizeof($sheet) . " dt-ok: " . sizeof($datas));
        // arrPrintPink($datas);
        // mati_disini(__LINE__);
        $this->db->trans_begin();
        foreach ($datas as $data) {
            $newMainDatas = array();
            $newMerekDatas = array();
            $newSatuanDatas = array();
            $newFolderDatas = array();
            /* --------------------------------
            * insert folder
            * ---------------------------------*/
            if (sizeof($fpKoloms) > 0) {
                foreach ($fpKoloms as $kolDb => $kolXls) {
                    $newFolderDatas[$kolDb] = $data[$kolXls];
                }
                $newFolderDatas['toko_id'] = $toko_id;
                // $newFolderDatas['nama'] = $toko_id;
                $folderCondites = $newFolderDatas;
                $folderDb = $fp->lookupByCondition($folderCondites)->result();
                if (is_array($folderDb) && sizeof($folderDb) > 0) {
                    showLast_query("lime");
                    $folder_id = $folderDb[0]->id;
                }
                else {
                    $folder_id = $fp->addData($newFolderDatas);
                    showLast_query("orange");
                }
            }

            /* --------------------------------
             * insert merek
             * ---------------------------------*/
            if (sizeof($merekKoloms) > 0) {
                foreach ($merekKoloms as $kolXls => $kolDb) {
                    $newMerekDatas[$kolDb] = $data[$kolXls];
                }
                $merekCondites = $newMerekDatas;
                $merekDb = $mr->lookupByCondition($merekCondites)->result();
                if (is_array($merekDb) && sizeof($merekDb) > 0) {
                    showLast_query("lime");
                    $merek_id = $merekDb[0]->id;
                }
                else {
                    $merek_id = $mr->addData($newMerekDatas);
                    showLast_query("orange");
                }
            }

            /* --------------------------------
             * insert satuan
             * ---------------------------------*/
            if (sizeof($satuanKoloms) > 0) {
                foreach ($satuanKoloms as $kolDb => $kolXls) {
                    $newSatuanDatas[$kolDb] = $data[$kolXls];
                }
                $satuanCondites = $newSatuanDatas;
                $satuanDb = $st->lookupByCondition($satuanCondites)->result();
                if (is_array($satuanDb) && sizeof($satuanDb) > 0) {
                    showLast_query("lime");
                    $satuan_id = $satuanDb[0]->id;

                }
                else {
                    $satuan_id = $st->addData($newSatuanDatas);
                    showLast_query("orange");
                }
            }

            /* --------------------------------
             * insert lokasi
             * ---------------------------------*/
            if (sizeof($lokasiKoloms) > 0) {
                foreach ($lokasiKoloms as $kolDb => $kolXls) {
                    $newLokasiDatas[$kolDb] = $data[$kolXls];
                }
                $newLokasiDatas['cabang_id'] = '1';
                $lokasiCondites = $newLokasiDatas;
                $lokasiDb = $lk->lookupByCondition($lokasiCondites)->result();
                if (is_array($lokasiDb) && sizeof($lokasiDb) > 0) {
                    showLast_query("merah");
                    $lokasi_id = $lokasiDb[0]->id;

                }
                else {
                    $lokasi_id = $lk->addData($newLokasiDatas);
                    showLast_query("here");
                }
            }
            // matiHere($merek_id);
            /* --------------------------
             * insert main data
             * --------------------*/

            // $newMainDatas["jenis"] = "item";
            // $newMainDatas["folder_id"] = $folder_id;
            // $newMainDatas["merek_id"] = $merek_id;
            $newMainDatas["toko_id"] = $toko_id;
            foreach ($produkKoloms as $kolXls => $kolDb) {
                $newMainDatas[$kolDb] = isset($data[$kolXls]) ? $data[$kolXls] : "";
            }
            // arrPrint($newMainDatas);
            // matiHere(__LINE__);
            /* --------------------------------
            * insert main data (produk)
            * ---------------------------------*/
            // foreach ($produkKoloms as $kolXls => $kolDb) {
            //     $newDatas[$kolDb] = $data[$kolXls];
            // }

            $dataCondites["nama"] = $data["nama"];
            $dataCondites["toko_id"] = $toko_id;
            $dataDb = $pr->lookupByCondition($dataCondites)->result();
            if (is_array($dataDb) && sizeof($dataDb) > 0) {
                // $satuan_id = $satuanDb[0]->id;
                showLast_query("lime");
                $pr->updateData($dataCondites, $newMainDatas);
                showLast_query("kuning");
            }
            else {
                cekBiru("insert");
                // $satuan_id = $mr->addData($newSatuanDatas);
                $mainInsertId = $pr->addData($newMainDatas);
                showLast_query("orange");

                /* -------------------------------------
                 * tested pada auto COA yg pakai aaproval masuk di do
                 * -------------------------------------*/
                if (method_exists($mainObj, "getConnectingData")) {
                    $nama = ucwords($newMainDatas['nama']);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();

                    cekBiru($negara . " $extern_tipe");
                    $connectings = $mainObj->getConnectingData();
                    // arrPrintPink($connectings);
                    foreach ($connectings as $model => $param_connectings) {
                        foreach ($param_connectings as $p_key => $param_connecting) {


                            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                            $this->load->model($param_connecting['path'] . "/$model");
                            $connObj = new $model();
                            // $strHead_code = isset($param_connecting['staticOptions'][$extern_tipe]) ? $param_connecting['staticOptions'][$extern_tipe] : matiHere("parameter");
                            if (isset($param_connecting['staticOptions'])) {

                                $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'][$extern_tipe] : $param_connecting['staticOptions'];
                            }
                            else {
                                matiHere("static optionnya tolong dikasih");
                            }
                            $datas = array();

                            foreach ($fields as $field => $cfParams) {

                                if (isset($cfParams['var_main'])) {
                                    $cNilai = $$cfParams['var_main'];
                                }
                                else {
                                    $cNilai = $cfParams['str'];
                                }

                                $datas[$field] = $cNilai;
                            }

                            // arrPrint($datas);
                            // cekLime();
                            /* -------------------------------------------------
                             * menulis ke table connecting
                             * -------------------------------------------------*/
                            $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $toko_id, $datas);
                            showLast_query("merah");

                            /* -------------------------------------------------
                             * ngupdate ke data utama
                             * -------------------------------------------------*/
                            if (isset($param_connecting['updateMain'])) {

                                foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                    $mainCondites[$key] = $$condite;
                                }
                                foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                    $mainUpdate[$key] = $$val;
                                }

                                $mainObj->updateData($mainCondites, $mainUpdate);
                                showLast_query("orange");
                            }

                            cekHitam($lastInset_code);
                        }
                    }


                    // arrPrint($connecting);
                }
            }
        }

        // cekHitam($segment_3);
        // $segment_3 = 'save';
        /* ----------------------------------------
         * marking cp_supplies
         * ----------------------------------------*/
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());
        $cp->updateDataPreparation("customer");

        // ---------------------------------------
        // matiHere("belom comit");
        if ($segment_3 == "none") {
            matiDisini("belom commit " . __LINE__);
        }
        else {

            $this->db->trans_commit();

            $alertDone = array(
                "type" => "success",
                "html" => "data PRODUK berhasil diupload ke system",
            );
            echo swalAlert($alertDone);
            topReload();
            die();
            // matiDisini("selesai sudah comit");
        }
    }

    public function importCustomerDataPre()
    {
        $segment_3 = $this->uri->segment(3);
        $toko_id = my_toko_id();
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlCustomer");
        $mainObj = $pr = new MdlCustomer();
        $produkKoloms = array(
            "nama" => "nama",
            "email" => "email",
            // "merk"       => "merek_nama",
            // "no_part"    => "no_part",
            // "satuan"     => "satuan",
            // "keterangan" => "keterangan",
            // "qtylimit"   => "limit",
            "phone" => "tlp_1",
            "alamat" => "alamat_1",
        );

        $this->load->model("Mdls/MdlFolderProduk");
        $fp = new MdlFolderProduk();
        $fpKoloms = array(
            // "nama" => "kategori",
            // "toko_id" => $toko_id,
        );

        $this->load->model("Mdls/MdlSatuan");
        $st = new MdlSatuan();
        $satuanKoloms = array(
            // "nama" => "satuan",
            // "kode" => "satuan",
        );

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $merekKoloms = array(// "merk" => "nama"
        );

        $this->load->model("Mdls/MdlLokasiIndex");
        $lk = new MdlLokasiIndex();
        $lokasiKoloms = array(
            // "nama" => "kode_lkst",
            // "nama" => "kode_lksg",
        );

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext . "<hr> gantilah dengan file xlsx") : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

        arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // arrPrintPink($datas);
        // cekBiru("file: $name <br>dt-excel: " . sizeof($sheet) . " dt-ok: " . sizeof($datas));
        $jml_data_excel = sizeof($datas);
        // arrPrintPink($datas);
        $var_html = "";
        // $showan = modalDialogBtn('heheh','act');
        $var_html .= "Jumlah data yang terbaca $jml_data_excel";
        $var_html .= "<p>klik OK apa bila anda sudah yakin data yang diupload semua benar</p>";
        $var_html .= "untuk melihat hasil pembacaan <span class=\'btn btn-link\' id=\'cek_excel_data\'> klik lihat data</span>";

        // arrPrintPink($koloms);
        // arrPrintPink($datas);
        //<editor-fold desc="hasil pembacaan data excel ">
        $excelShow = "<table rules=\'all\' border=\'1\' cellpadding=\'5\' bordercolor=\'#ddd\'>";
        $excelShow .= "<tr>";
        $excelShow .= "<th>no</th>";
        foreach ($koloms as $kolom => $kalias) {
            $excelShow .= "<th>$kalias</th>";
        }
        $excelShow .= "</tr>";

        $no = 0;
        foreach ($datas as $data) {
            $no++;

            $excelShow .= "<tr>";
            $excelShow .= "<td>$no</td>";
            foreach ($koloms as $kolom => $kalias) {

                $xl_value = str_replace("'", "", $data[$kalias]);
                $xlsValue = $xl_value;
                $format_td = is_numeric($xl_value) ? "align=\'right\'" : "";

                $excelShow .= "<td $format_td>$xlsValue</td>";
            }
            $excelShow .= "</tr>";
        }
        $excelShow .= "<table>";
        //</editor-fold>

        $alerts = array(
            "type" => "'info'",
            "html" => "'$var_html'",
            // "showCloseButton" => true,
            "showCancelButton" => true,
            "onOpen" => "function(){ top.$('span#cek_excel_data').on('click', function(){ top.BootstrapDialog.show({ title:'Excel Reader', message: '" . $excelShow . "', onshown: function(){ top.$('.modal-backdrop.in').css('z-index',2040); top.$('.modal.in').css('z-index',2540) }  });  } )  }"
        );

        $action_2 = "Converter/importCustomerData/save";
        echo swalAlertSubmit_2($alerts, $action_2);
        die();
        mati_disini(__LINE__);

    }
    // ------------------------------------------------------------------STOP-

    /* ------------------------------------------------------------------START-
     * IMPORTER DATA
     * ---------------------------------*/
    public function formProduk()
    {
        $segment_4 = $this->uri->segment(3);
        $seg_4 = isset($segment_4) ? "/$segment_4" : "/none";
        echo ($this->router->method) . " <a class='pull-right' href='" . download_tpl('produk') . "' target='result'><i class='fa fa-download'></i> download template excel</a><hr>";
        // echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukData/$seg_4' target='result'> ";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukDataPre/$seg_4' target='result'> ";
        echo "<div class='form-group'>";
        echo "<label>file input</label>";
        // echo "<button type='button' class='btn btn-danger'><i class='fa fa-download'></i> </button>";
        echo "<input type='file' name='fileExcel' required>";
        echo "<hr>";
        echo "<p>reader xlsx</p>";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
        echo "<input type='submit' name='submit_file' value='submit file' class='btn btn-primary'>";
        echo "</div>";

        echo "</form>";
        echo "<script>";
        echo "$('form[target=result]').submit(function(event) {
                    swal('CHECKING EXCEL...','sedang mengecek data dari Excel<br>Please Wait...<br><br>LAMA PENGECEKAN TERGANTUNG DARI BANYAK PRODUK PADA EXCEL ANDA.', 'info')
                    swal.showLoading()
              });";

        echo "</script>";
    }

    /* ----------------------------------------------------
     * IMPORTER DATA PRODUK
     * dan data pembantu produk
     * -----------------------------------------------------*/
    public function importProdukData()
    {
        $segment_3 = $this->uri->segment(3);
        $toko_id = my_toko_id();

        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlProduk");
        $mainObj = $pr = new MdlProduk();

        $produkKoloms = array(
            "sku (kode produk)" => "kode",
            "barcode" => "barcode",
            "description" => "deskripsi",
            "nama produk" => "nama",
            // "merk"       => "merek_nama",
            // "no_part"    => "no_part",
            // "satuan"     => "satuan",
            // "keterangan" => "keterangan",
            // "qtylimit"   => "limit",
            "kategori" => "folder_nama",
            "satuan" => "satuan",
            "warna" => "warna",
            "kemasan" => "kemasan",
        );

        $this->load->model("Mdls/MdlFolderProduk");
        $fp = new MdlFolderProduk();
        $fpKoloms = array(
            "nama" => "kategori",
        );

        $this->load->model("Mdls/MdlSatuan");
        $st = new MdlSatuan();
        $satuanKoloms = array(
            "nama" => "satuan",
        );

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $merekKoloms = array(
            "merek" => "nama"
        );

        $this->load->model("Mdls/MdlLokasiIndex");
        $lk = new MdlLokasiIndex();
        $lokasiKoloms = array(
            // "nama" => "kode_lkst",
            // "nama" => "kode_lksg",
        );

//        $this->load->model("Mdls/MdlProdukSatuanRelasi");
//        $sp = new MdlProdukSatuanRelasi();
        $satuanRelasi = array(
//            ""=> "",
        );

        $this->load->model("Mdls/MdlSupplier");
        $sp = new MdlSupplier();
        $vendorKoloms = array(
//            "nama" => "kategori",
        );

        $this->load->model("Mdls/MdlHargaProdukPerSupplier");
        $prs = new MdlHargaProdukPerSupplier();
        $vendorPriceKoloms = array(
//            "nilai" => "price list",
        );

        $this->load->model("Mdls/MdlHargaProduk");
        $h = new MdlHargaProduk();
        $hargaKoloms = array(
            "hpp" => "price list",
            "hpp_nppn" => "price list",
            "jual" => "price list",
            "jual_nppn" => "price list",
        );

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "-1";
        $gudang_id = "-10";

        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext . "<hr> gantilah dengan file xlsx") : "";

        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 2;
        $data_start = 3;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = array_filter($headers[$data_header]);
//        echo "<pre>";
//        print_r($koloms);
//        echo "<pre>";
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        cekBiru("file: $name <br>dt-excel: " . sizeof($sheet) . " dt-ok: " . sizeof($datas));

        if (ob_get_level() == 0) ob_start();

        echo "\n<script> localStorage.time_mulai_upload = new Date() </script>";

        ob_flush();
        flush();

        $this->db->trans_begin();

        $ids = array();
        $resultUpload = array();
        $produkVendorData = array();
        $totalDatas = count($datas);
        $processed = 0;

//        echo "<pre>";
//        print_r($datas);
//        echo "<pre>";

        foreach ($datas as $xc =>$data) {
            $newMainDatas   = array();
            $newMerekDatas  = array();
            $newSatuanDatas = array();
            $newFolderDatas = array();
            $newVendorDatas = array();

            /* --------------------------------
            * insert folder
            * ---------------------------------*/
            if(sizeof($fpKoloms)>0){
                foreach ($fpKoloms as $kolDb => $kolXls) {
                    $newFolderDatas[$kolDb] = $data[$kolXls];
                }
                $newFolderDatas['toko_id'] = $toko_id;
                $folderCondites = $newFolderDatas;
                $folderDb = $fp->lookupByCondition($folderCondites)->result();
                if (is_array($folderDb) && sizeof($folderDb) > 0) {
                    $folder_id = $folderDb[0]->id;
                }
                else {
                    $folder_id = $fp->addData($newFolderDatas);
                }
            }

            /* --------------------------------
             * insert merek
             * ---------------------------------*/
            if(sizeof($merekKoloms)>0){
                foreach ($merekKoloms as $kolXls => $kolDb) {
                    $newMerekDatas[$kolDb] = $data[$kolXls];
                }
                $merekCondites = $newMerekDatas;
                $merekDb = $mr->lookupByCondition($merekCondites)->result();
                if (is_array($merekDb) && sizeof($merekDb) > 0) {
                    $merek_id = $merekDb[0]->id;
                }
                else {
                    $merek_id = $mr->addData($newMerekDatas);
                }
            }

            /* --------------------------------
             * insert satuan
             * ---------------------------------*/
            if(sizeof($satuanKoloms)>0){
                foreach ($satuanKoloms as $kolDb => $kolXls) {
                    $newSatuanDatas[$kolDb] = $data[$kolXls];
                }
                $satuanCondites = $newSatuanDatas;
                $satuanDb = $st->lookupByCondition($satuanCondites)->result();
                if (is_array($satuanDb) && sizeof($satuanDb) > 0) {
                    $satuan_id = $satuanDb[0]->id;
                }
                else {
                    $satuan_id = $st->addData($newSatuanDatas);
                }
            }

            /* --------------------------------
             * insert lokasi
             * ---------------------------------*/
            if(sizeof($lokasiKoloms)>0){
                foreach ($lokasiKoloms as $kolDb => $kolXls) {
                    $newLokasiDatas[$kolDb] = $data[$kolXls];
                }
                $newLokasiDatas['cabang_id'] = '1';
                $lokasiCondites = $newLokasiDatas;
                $lokasiDb = $lk->lookupByCondition($lokasiCondites)->result();
                if (is_array($lokasiDb) && sizeof($lokasiDb) > 0) {
                    $lokasi_id = $lokasiDb[0]->id;
                }
                else {
                    $lokasi_id = $lk->addData($newLokasiDatas);
                }
            }

            /* --------------------------------
             * insert vendor
             * ---------------------------------*/
            if(sizeof($vendorKoloms)>0){
                foreach ($vendorKoloms as $kolDb => $kolXls) {
                    $newVendorDatas[$kolDb] = $data[$kolXls];
                }
                $newVendorDatas['cabang_id'] = my_cabang_id();
                $newVendorDatas['toko_id'] = my_toko_id();
                $vendorCondites = $newVendorDatas;
                $vendorDb = $sp->lookupByCondition($vendorCondites)->result();

                if (is_array($vendorDb) && sizeof($vendorDb) > 0) {
                    $vendor_id = $vendorDb[0]->id;
                }
                else {
                    $vendor_id = $sp->addData($newVendorDatas);

                    /* -------------------------------------
                     * tested pada auto COA yg pakai aaproval masuk di do
                     * -------------------------------------*/
                    if (method_exists($sp, "getConnectingData")) {
                        $nama = ucwords($data["nama produk"]);
                        $negara = isset($data['country']) ? $data['country'] : "";
                        $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                        $mainInsertId = $vendor_id;
                        $my_name = my_name();
                        $connectings = $sp->getConnectingData();
                        foreach ($connectings as $model => $param_connectings) {
                            foreach ($param_connectings as $p_key => $param_connecting) {
                                $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                                $this->load->model($param_connecting['path'] . "/$model");
                                $connObj = new $model();
                                if (isset($param_connecting['staticOptions'])) {
                                    $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'][$extern_tipe] : $param_connecting['staticOptions'];
                                }
                                else {
                                    matiHere("static optionnya tolong dikasih");
                                }
                                $dataCoa = array();

                                foreach ($fields as $field => $cfParams) {
                                    if (isset($cfParams['var_main'])) {
                                        $cNilai = $$cfParams['var_main'];
                                    }
                                    else {
                                        $cNilai = $cfParams['str'];
                                    }
                                    $dataCoa[$field] = $cNilai;
                                }

                                /* -------------------------------------------------
                                 * menulis ke table connecting
                                 * -------------------------------------------------*/
                                $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, my_toko_id(), $dataCoa);

                                /* -------------------------------------------------
                                 * ngupdate ke data utama
                                 * -------------------------------------------------*/
                                if (isset($param_connecting['updateMain'])) {
                                    foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                        $mainCondites[$key] = $$condite;
                                    }
                                    foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                        $mainUpdate[$key] = $$val;
                                    }
                                    $sp->updateData($mainCondites, $mainUpdate);
                                }
                            }
                        }
                    }
                }

            }

            /* --------------------------------
             * cek relasi satuan
             * ---------------------------------*/
            if(sizeof($satuanRelasi)>0){
                //kolom relasi
                $kolomRelasi = array(
                    "satuan2"   => "satuan_nama",
                    "s2 qty"    => "qty",
                    "s2 barcode/sku" => "barcode",
                );

            }

            /* --------------------------
             * insert main data
             * --------------------*/

            $newMainDatas["jenis"]      = "item";
            $newMainDatas["folder_id"]  = $folder_id;
            $newMainDatas["toko_id"]    = $toko_id;
            $newMainDatas["satuan_id"]  = $satuan_id;
            $newMainDatas["merek_id"]   = $merek_id;
            $newMainDatas['premi_jual'] = isset($data['premi jual']) ? $data['premi jual'] : 0;
            $newMainDatas['tipe_pajak'] = isset($data['pajak']) ? ( $data['pajak']=="Y" ? 1 : 0 ) : "";

            $newMainDatas['weight_kg'] = isset($data['weight_kg']) ? $data['weight_kg']*1 : 0;
            $newMainDatas['katalog_url'] = isset($data['katalog_url']) ? $data['katalog_url'] : "";

            $mustBeFloat = array("barcode");

            foreach ($produkKoloms as $kolXls => $kolDb) {
                if(isset($data[$kolXls])){
//                    $str = sprintf("%d", $data[$kolXls]);
//                    $newMainDatas[$kolDb] = in_array($kolXls,$mustBeFloat) ? preg_replace('/[^0-9]/', '', $str) : $data[$kolXls];
                    $newMainDatas[$kolDb] = (string)$data[$kolXls];
                }
                else{
                    $newMainDatas[$kolDb] = "";
                }
            }

            /* --------------------------------
             * insert main data (produk)
             * ---------------------------------*/
//            $dataCondites["kemasan"] = $data["kemasan"];
            $dataCondites = array();

//            if($data["barcode"] && strlen($data["barcode"])>5 ){
//                $dataCondites["barcode"] = $data["barcode"];
//                $dataCondites["barcode1"] = $data["barcode"]*1;
//                $dataCondites["barcode2"] = number_format($data["barcode"],0,'','');
//                $dataCondites["barcode3"] = number_format($data["barcode"],2,'','');



//            if( $data["barcode"] ){
//
//            }
//            }
//            else{
//                $dataCondites["nama"] = $data["nama produk"];
//            }

//            $dataCondites["toko_id"] = $toko_id;

//            $dataDb = $pr->lookupByCondition($dataCondites)->result();
            $dataDb = array();

//            echo "<pre>";
//            print_r($dataDb);
//            print_r($dataCondites);
//            print_r($newMainDatas['barcode']);
//            print_r($this->db->last_query());
//            echo "</pre>";

//            if($processed==10){
//                matiHere();
//            }

            if (is_array($dataDb) && sizeof($dataDb) > 0) {
                $mainInsertId = $dataDb[0]->id;
                $pr->updateData(array("id"=>$mainInsertId), $newMainDatas);
                if(!isset($resultUpload['update'])){
                    $resultUpload['update'] = 0;
                }
                $resultUpload['update'] += 1;
            }
            else {
                $mainInsertId = $pr->addData($newMainDatas);
                if(!isset($resultUpload['insert'])){
                    $resultUpload['insert'] = 0;
                }
                $resultUpload['insert'] += 1;
                /* -------------------------------------
                 * tested pada auto COA yg pakai aaproval masuk di do
                 * -------------------------------------*/
                if (method_exists($mainObj, "getConnectingData")) {
                    $nama = ucwords($data["nama produk"]);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();
                    $connectings = $mainObj->getConnectingData();
                    foreach ($connectings as $model => $param_connectings) {
                        foreach ($param_connectings as $p_key => $param_connecting) {
                            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                            $this->load->model($param_connecting['path'] . "/$model");
                            $connObj = new $model();
                            if (isset($param_connecting['staticOptions'])) {
                                $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'][$extern_tipe] : $param_connecting['staticOptions'];
                            }
                            else {
                                matiHere("static optionnya tolong dikasih");
                            }

                            $dataCoa = array();
                            foreach ($fields as $field => $cfParams) {
                                if (isset($cfParams['var_main'])) {
                                    $cNilai = $$cfParams['var_main'];
                                }
                                else {
                                    $cNilai = $cfParams['str'];
                                }
                                $dataCoa[$field] = $cNilai;
                            }

                            /* -------------------------------------------------
                             * menulis ke table connecting
                             * -------------------------------------------------*/
                            $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $toko_id, $dataCoa);

                            /* -------------------------------------------------
                             * ngupdate ke data utama
                             * -------------------------------------------------*/
                            if (isset($param_connecting['updateMain'])) {
                                foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                    $mainCondites[$key] = $$condite;
                                }
                                foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                    $mainUpdate[$key] = $$val;
                                }
                                $mainObj->updateData($mainCondites, $mainUpdate);
                            }
                        }
                    }
                }
            }

            $produkVendorData[$mainInsertId] =array(
                "supplier_id" => $vendor_id,
                "supplier_nama" => $data["vendor"],
                "harga_list" => isset($data["harga list"]) ? $data["harga list"] :0,
                "produk_nama" => isset($data["nama produk"]) ? $data["nama produk"] : 0,
                "harga_beli" => isset($data["harga beli"]) ? $data["harga beli"]:0,
                "harga_jual" => isset($data["harga jual"]) ? $data["harga jual"]:0,
                "toko_id" => $toko_id,
                "cabang_id" => my_cabang_id(),
            );

            if(isset($data["harga beli"]) && $data["harga beli"] >0){
                $priceData[$mainInsertId]["hpp"] = array(
                    "suppliers_id"=>$vendor_id,
                    "produk_id"=>$mainInsertId,
                    "jenis"=>"produk",
                    "jenis_value"=>"hpp",
                    "nilai"=>$data["harga beli"],
                    "oleh_id"=>my_id(),
                    "oleh_nama"=>my_name(),
                );
            }
            if(isset($data["harga jual"]) && $data["harga jual"] >0){
                $priceData[$mainInsertId]["jual"] = array(
                    "suppliers_id"=>$vendor_id,
                    "produk_id"=>$mainInsertId,
                    "jenis"=>"produk",
                    "jenis_value"=>"jual",
                    "nilai"=>isset($data["harga jual"]) ? $data["harga jual"]:0,
                    "oleh_id"=>my_id(),
                    "oleh_nama"=>my_name(),
                );
            }
            if(isset($data["harga list"]) && $data["harga list"] >0){
                $priceData[$mainInsertId]["harga_list"] = array(
                    "suppliers_id"=>$vendor_id,
                    "produk_id"=>$mainInsertId,
                    "jenis"=>"produk",
                    "jenis_value"=>"harga_list",
                    "nilai"=>isset($data["harga list"]) ? $data["harga list"]:0,
                    "oleh_id"=>my_id(),
                    "oleh_nama"=>my_name(),
                );
            }

//            if(isset($data["harga list"]) && $data["harga list"] >0){
//                $priceData[$mainInsertId]["harga_list"] = array(
//                    "suppliers_id"=>$vendor_id,
//                    "produk_id"=>$mainInsertId,
//                    "jenis"=>"produk",
//                    "jenis_value"=>"harga_list",
//                    "nilai"=>isset($data["harga list"]) ? $data["harga list"]:0,
//                    "oleh_id"=>my_id(),
//                    "oleh_nama"=>my_name(),
//                );
//            }

            $processing = $totalDatas-$processed;

            echo "\n<script>top.writeProgress('PROSES UPLOAD: $processing/$totalDatas (".number_format((((($processing/$totalDatas)*100)-100)*-1))."%)<br>".strtoupper($data["nama produk"])."');</script>";
            ob_flush();
            flush();

            $processed++;
        }

        $resultVendorData=array();
        $resultVendorData['update'] = 0;
        $vendorProcess=0;
        //region create relasi vendor produk
        if(sizeof($produkVendorData)>0){
            $this->load->model("Mdls/MdlProdukPerSupplier");
            $ps = new MdlProdukPerSupplier();

            $vendorTotalData = count($produkVendorData);
            foreach($produkVendorData as $pid =>$pidData){

                $vendorProcessing = $vendorTotalData-$vendorProcess;
                $resultTxt = "VENDOR PROSES: $vendorProcessing/$vendorTotalData (".number_format((((($vendorProcessing/$vendorTotalData)*100)-100)*-1))."%)";

                //region insert produk persupplier
//                $ps->addFilter("produk_id='$pid'");
//                $ps->addFilter("suppliers_id='".$pidData['supplier_id']."'");
//                $ps->addFilter("produk_per_supplier.toko_id='".$pidData['toko_id']."'");
//                $ps->addFilter("produk_per_supplier.cabang_id='".$pidData['cabang_id']."'");
//                $preval = $ps->lookUpAll()->result();
                $preval = array();

                $resultTxt .= "<br>BACA VENDOR DATA: <br>PID $pid || VENDOR ID: ".$pidData['supplier_id'];
//                echo "\n<script>top.writeProgress('BACA VENDOR DATA: <br>PID $pid || VENDOR ID: ".$pidData['supplier_id']."');</script>";
                ob_flush();
                flush();

                if(sizeof($preval)>0){
                    $resultTxt .= "<br>IGNORING VENDOR DATA: <br>".strtoupper($pidData['supplier_nama']);
                    //no insert
                    if(!isset($resultVendorData['ignore'])){
                        $resultVendorData['ignore']=0;
                    }
                    $resultVendorData['ignore'] += 1;
                }
                else{
                    $resultTxt .= "<br>INSERT VENDOR DATA: <br>".strtoupper($pidData['supplier_nama']);
//                    echo "\n<script>top.writeProgress('BACA VENDOR DATA: <br>PID $pid || VENDOR ID: ".$pidData['supplier_id']."<br>INSERT VENDOR DATA: <br>".strtoupper($pidData['supplier_nama'])."');</script>";
//                    echo "\n<script>top.writeProgress('');</script>";
                    ob_flush();
                    flush();

                    if(isset($pidData['supplier_id']) && $pidData['supplier_id']*1>0){
                        $insertData = array(
                            "produk_id"=>$pid,
                            "produk_nama"=>$pidData['produk_nama'],
                            "suppliers_id"=>$pidData['supplier_id'],
                            "suppliers_nama"=>$pidData['supplier_nama'],
                            "cabang_id"=>$pidData['cabang_id'],
                            "toko_id"=>$pidData['toko_id'],
                        );
                        $ps->addData($insertData);
                    }


                    if(!isset($resultVendorData['insert'])){
                        $resultVendorData['insert']=0;
                    }
                    $resultVendorData['insert'] += 1;
                }
                //endregion


                echo "\n<script>top.writeProgress('$resultTxt');</script>";
                ob_flush();
                flush();

                $vendorProcess++;
            }
        }
        //endregion

        $resultPriceData=array();
        $priceProcess=0;
        //region create relasi harga produk dan vendor
        if(sizeof($priceData)>0){
            $this->load->model("Mdls/MdlHargaProduk");
            $this->load->model("Mdls/MdlHargaProdukPerSupplier");
            $hpr = new MdlHargaProduk();
            $hs = new MdlHargaProdukPerSupplier();

            $totalPriceData = count($priceData);
            foreach($priceData as $pID =>$PIdPrice){

                $processingPrice = $totalPriceData-$priceProcess;
                $resultTxt = "PRICE PROSES: $processingPrice/$totalPriceData (".number_format((((($processingPrice/$totalPriceData)*100)-100)*-1))."%)";

                foreach($PIdPrice as $jenis_values =>$valueDatas){

//                    $hs->addFilter("produk_id='$pID'");
//                    $hs->addFilter("suppliers_id='".$valueDatas["suppliers_id"]."'");
//                    $hs->addFilter("jenis_value='$jenis_values'");
//                    $hs->addFilter("toko_id='".my_toko_id()."'");
//                    $hs->addFilter("cabang_id='".my_cabang_id()."'");
//                    $prevValueprodukSupplier = $hs->lookUpAll()->result();

                    $prevValueprodukSupplier = array();
                    if(sizeof($prevValueprodukSupplier)>0){
                        $resultTxt .= "<br>UPDATE PRICE DATA: <br>".strtoupper($valueDatas["nilai"])." PID: $pID || JENIS VALUE: ".strtoupper($valueDatas["jenis_value"]);
//                        echo "\n<script>top.writeProgress('UPDATE PRICE DATA: <br>".strtoupper($valueDatas["nilai"])." PID: $pID || JENIS VALUE: ".strtoupper($valueDatas["jenis_value"])."');</script>";
                        ob_flush();
                        flush();

                        //update
                        $where = array(
                            "jenis"=>$valueDatas["jenis"],
                            "jenis_value"=>$valueDatas["jenis_value"],
                            "produk_id"=>$pID,
                            "suppliers_id"=>$valueDatas["suppliers_id"],
                            "toko_id"=>my_toko_id(),
                            "cabang_id"=>my_cabang_id(),
                        );
                        $update = array("nilai"=>$valueDatas["nilai"]);
                        $hs->updateData($where,$update);

                        if(!isset($resultPriceData['update'])){
                            $resultPriceData['update']=0;
                        }
                        $resultPriceData['update'] += 1;
                    }
                    else{

                        $resultTxt .= "<br>INSERT PRICE DATA: <br> PID: ".strtoupper($valueDatas["produk_id"])." || VALUE JENIS: ".strtoupper($valueDatas["jenis_value"]);
//                        echo "\n<script>top.writeProgress('INSERT PRICE DATA: <br> PID: ".strtoupper($valueDatas["produk_id"])." || VALUE JENIS: ".strtoupper($valueDatas["jenis_value"])."');</script>";
                        ob_flush();
                        flush();

                        if(isset($valueDatas["suppliers_id"]) && $valueDatas["suppliers_id"]*1> 0 ){
                            //insert
                            $insert = $hs->addData($valueDatas);
                        }

                        $insertPriceProd = $hpr->addData(array(
                            "produk_id"=>$valueDatas["produk_id"],
                            "toko_id"=>my_toko_id(),
                            "cabang_id"=>my_cabang_id(),
                            "jenis"=>$valueDatas["jenis"],
                            "jenis_value"=>$valueDatas["jenis_value"],
                            "nilai"=>$valueDatas["nilai"],
                            "oleh_id"=>$valueDatas["oleh_id"],
                            "oleh_nama"=>$valueDatas["oleh_nama"],
                        ));

                        if(!isset($resultPriceData['insert'])){
                            $resultPriceData['insert']=0;
                        }
                        $resultPriceData['insert'] += 1;
                    }
                }

                echo "\n<script>top.writeProgress('$resultTxt');</script>";
                ob_flush();
                flush();

                $priceProcess++;
            }
        }
        //endregion

        /* ----------------------------------------
         * marking cp_supplies
         * ----------------------------------------*/
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());
        $cp->updateDataPreparation("produk");

//        arrPrint($datas);
//        matiHere();
        // ---------------------------------------
        if ($segment_3 == "none") {
            echo "\n<script>top.writeProgress('FINISHING PROCESS...., <BR>BELUM COMMIT');</script>";
            matiDisini("belom commit " . __LINE__);
        }
        else {

            echo "\n <script> top.writeProgress('FINISHING PROCESS...'); </script>";
            echo "\n <script> localStorage.time_end_upload = new Date(); </script>";

            echo "\n <script> localStorage.last_upload_data = JSON.stringify({price_data:{update: ".(isset($resultPriceData['update'])?$resultPriceData['update']:0).",insert: ".(isset($resultPriceData['insert'])?$resultPriceData['insert']:0)."}, vendor_data:{update: ".$resultVendorData['update'].",insert: ".$resultVendorData['insert'].",ignore: ".$resultVendorData['ignore']."}, time_mulai_upload: localStorage.time_mulai_upload, time_end_upload: localStorage.time_end_upload, produk_data:{update: ".$resultUpload['update'].", insert: ".$resultUpload['insert']."}}) </script>";
            ob_flush();
            flush();

            $this->db->trans_commit();

            $res = !empty($resultUpload) ? (isset($resultUpload['update'])?"<span class='text-bold text-green'>UPDATE: ".$resultUpload['update']."</span>":"<span class='text-bold text-green'>UPDATE: 0</span>") . " DAN ". (isset($resultUpload['insert'])? "<span class='text-bold text-red'>INSERT: ".$resultUpload['insert']."</span>":"<span class='text-bold text-red'>INSERT: 0</span>") : "";
            $alertDone = array(
                "type" => "success",
                "html" => "data PRODUK berhasil diupload ke system<br>$res<br>page akan di refresh dalam 5 detik",
            );
            echo swalAlert($alertDone);
            sleep(5);
            topReload();
            die();
        }

        ob_end_flush();
    }

    public function importProdukDataPre()
    {
        $segment_3 = $this->uri->segment(3);
        $toko_id = my_toko_id();
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlProduk");
        $mainObj = $pr = new MdlProduk();
        $produkKoloms = array(
            "sku (kode produk)" => "kode",
            "nama menu / produk" => "nama",
            // "merk"       => "merek_nama",
            // "no_part"    => "no_part",
            // "satuan"     => "satuan",
            // "keterangan" => "keterangan",
            // "qtylimit"   => "limit",
            "kategori" => "folder_nama",
            "hrg jual" => "harga_jual",
        );

        $this->load->model("Mdls/MdlFolderProduk");
        $fp = new MdlFolderProduk();
        $fpKoloms = array(
            "nama" => "kategori",
            // "toko_id" => $toko_id,
        );

        $this->load->model("Mdls/MdlSatuan");
        $st = new MdlSatuan();
        $satuanKoloms = array(
            // "nama" => "satuan",
            // "kode" => "satuan",
        );

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $merekKoloms = array(// "merk" => "nama"
        );

        $this->load->model("Mdls/MdlLokasiIndex");
        $lk = new MdlLokasiIndex();
        $lokasiKoloms = array(
            // "nama" => "kode_lkst",
            // "nama" => "kode_lksg",
        );

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext . "<hr> gantilah dengan file xlsx") : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 2;
        $data_start = 3;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

//        arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // arrPrintPink($datas);
        // cekBiru("file: $name <br>dt-excel: " . sizeof($sheet) . " dt-ok: " . sizeof($datas));
        $jml_data_excel = sizeof($datas);
        // arrPrintPink($datas);
        $var_html = "";
        // $showan = modalDialogBtn('heheh','act');
        $var_html .= "Jumlah data yang terbaca $jml_data_excel";
        $var_html .= "<p>klik OK apa bila anda sudah yakin data yang diupload semua benar</p>";
        $var_html .= "untuk melihat hasil pembacaan <span class=\'btn btn-link\' id=\'cek_excel_data\'> klik lihat data</span>";

        // arrPrintPink($koloms);
        // arrPrintPink($datas);
        //<editor-fold desc="hasil pembacaan data excel ">
        $excelShow = "<table rules=\'all\' border=\'1\' cellpadding=\'5\' bordercolor=\'#ddd\'>";
        $excelShow .= "<tr>";
        $excelShow .= "<th>no</th>";
        foreach ($koloms as $kolom => $kalias) {
            $excelShow .= "<th>$kalias</th>";
        }
        $excelShow .= "</tr>";

        $no = 0;
        foreach ($datas as $data) {
            $no++;
            $excelShow .= "<tr>";
            $excelShow .= "<td>$no</td>";
            foreach ($koloms as $kolom => $kalias) {
                if($kalias!=""){
                    $xl_value = str_replace("'", "", $data[$kalias]);
                    $xlsValue = str_replace(array("\r\n", "\r", "\n"), "<br>", $xl_value);
                    $format_td = is_numeric($xl_value) ? "align=\'right\'" : "";
                    $excelShow .= "<td $format_td>". trim($xlsValue) ."</td>";
                }
            }
            $excelShow .= "</tr>";
        }
        $excelShow .= "<table>";
        //</editor-fold>

        $alerts = array(
            "type" => "'info'",
            "html" => "'$var_html'",
            "showCancelButton" => true,
            "onOpen" => "function(){ top.$('span#cek_excel_data').on('click', function(){ top.BootstrapDialog.show({ title:'Excel Reader', message: '" . $excelShow . "', onshown: function(){ top.$('.modal-dialog').addClass('modal-xl'); top.$('.modal-backdrop.in').css('z-index',2040); top.$('.modal.in').css('z-index',2540) }  });  } )  }"
        );

        $action_2 = "Converter/importProdukData/save";

        echo swalAlertSubmit_2($alerts, $action_2, "UPLOADING...", "SEDANG MENULIS DATA KE SYSTEM<br>JANGAN TUTUP BROWSER ANDA...<div class=\'clearfix\'>&nbsp;</div> <div id=\'h_wadah_human_time_start\'></div> <div id=\'header_wadah_progress\'></div> <div id=\'wadah_progress\' class=\'text-nowrap\'></div> <div id=\'wadah_human_time_start\'></div> <div id=\'wadah_microtime_start\'></div>");
        die();
        mati_disini(__LINE__);

    }
    // ------------------------------------------------------------------STOP-

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
        foreach ($tmpM as $mSpec) {
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

                                if (!in_array($mSpec->$externSrc['id'], $extID)) {
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

    /* ----------------------------------------------------
     * IPORDER DATA PRODUK
     * dan data pembantu produk
     * -----------------------------------------------------*/
    public function formProdukStok()
    {

        //
        echo ($this->router->method) . "<hr>";
//        echo ($this->router->method) . " <a class='pull-right' href='" . download_tpl('produk') . "' target='result'><i class='fa fa-download'></i> download template excel</a><hr>";


        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukStok/'> ";
        echo "<div class='form-group'>";
        echo "<label>file input</label>";
        // echo "<button type='button' class='btn btn-danger'><i class='fa fa-download'></i> </button>";
        echo "<input type='file' name='fileExcel' required>";
        echo "<hr>";
        echo "<p>reader xlsx</p>";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
        echo "<input type='submit' name='submit_file' value='submit file' class='btn btn-primary'>";
        echo "</div>";

        echo "</form>";
        echo "<script>";
        echo "$('form[target=result]').submit(function(event) {
                    swal('CHECKING EXCEL...','sedang mengecek data dari Excel<br>Please Wait...<br><br>LAMA PENGECEKAN TERGANTUNG DARI BANYAK PRODUK PADA EXCEL ANDA.', 'info')
                    swal.showLoading()
              });";

        echo "</script>";
    }

    public function importProdukStok()
    {
        $this->load->library('PHPExcel');
        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $this->load->model("Mdls/MdlTransaksiPemindahbukuan");
        $pb = new MdlTransaksiPemindahbukuan();
        $condites = array(
            "status" => 1,
        );
        $this->db->where($condites);
        // $this->db->limit(2);
        $srcPbs = $pb->lookupAll()->result();

//        $this->load->model("Mdls/MdlCoa");
//        $cb = new MdlCoa();
//        $condites = array(
//            "is_active" => "1",
//            "toko_id"   => my_toko_id(),
//        );
//        $this->db->where($condites);
//        $cbDatas = $cb->lookupAll()->result();

        $trPindahBuku=array();
        $trPindahBukuTotal=array();
        if(!empty($srcPbs)){
            foreach($srcPbs as $ky => $cbData){
//                $trPindahBuku[$cbData->p_head_code][$cbData->head_code] = $cbData;
//                $trPindahBuku[$cbData->p_head_code] = $cbData;
                $trPindahBuku[$cbData->head_code] = $cbData;
                $trPindahBukuTotal[$cbData->p_head_code][] = $cbData;
            }
        }

//        arrPrint($trPindahBuku);
//        arrPrint($trPindahBukuTotal);

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $tmpProduk = $pr->lookupAll()->result();

        $groupByName = array();
        $groupByCode = array();

        if(!empty($tmpProduk)){
            $n=0;
            foreach($tmpProduk as $k => $dt_produk){
                if(isset($dt_produk->nama) && $dt_produk->nama!=''){
                    $nm_f = str_replace("'", '', strtolower($dt_produk->nama));
                    $groupByName[$nm_f] = $dt_produk;
                }
                if(isset($dt_produk->barcode) && $dt_produk->barcode!=''){
                    $bc_f = str_replace("'", '', strtolower($dt_produk->barcode));
                    $groupByCode[$bc_f] = $dt_produk;
                }
                $n++;
            }
        }

        $this->load->model("Mdls/MdlAccounts");
        $cb = new MdlAccounts();
        $condites = array(
            "is_active" => "1",
            "toko_id"   => my_toko_id(),
        );
        $this->db->where($condites);
        $cbDatas = $cb->lookupAll()->result();

        $mdlCoa=array();
        if(!empty($cbDatas)){
            foreach($cbDatas as $y => $coData){
                $mdlCoa[$coData->head_code] = $coData;
            }
        }

        $produkKoloms = array(
            "kode" => "kode_brg",
            "nama" => "nama_brg",
            "merek_nama" => "merk",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "keterangan" => "keterangan",
            "limit" => "qtylimit",
            "folders_kode" => "kode_klp",
        );

        $produkKoloms = array(
            "kode" => "kode_brg",
            "nama" => "nama_brg",
            "merek_nama" => "merk",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "keterangan" => "keterangan",
            "limit" => "qtylimit",
            "folders_kode" => "kode_klp",

            //DARI EXCEL BOGA
//            [id] => d3901260-7081-11ed-b042-15864ce69a77
//            [name] => Doilies paper segi 6,5 x 13,5 isi 25
//            [category] => Belucih
//            [price] => 4150
//            [is_sellable] => Y
//            [sku] => 89971655411
//            [barcode] => 89971655411
//            [desc] =>
//            [use_outlet_tax] => Y
//            [variant_1] =>
//            [variant_2] =>
//            [variant_3] =>
//            [type] => T
//            [stock_unit] => Pcs
//            [is_stock_tracked] => Y
        );

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

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);
        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

        // arrPrint($sheet);
        // arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    $xlsValue = strval($row[$kolom]);
                    // cekBiru("$kalias: $xlsValue");
                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrintPink($sheet);
        // arrPrint($koloms);
//         arrPrint($headers);

//        arrPrintPink($datas);
//        matiHere(__LINE__);

        $this->db->trans_begin();

        $newMainDatas = array();
        foreach ($datas as $data) {
            // matiHere($merek_id);
            /* --------------------------
             * insert main data
             * --------------------*/
            $newMainDatas["jenis"] = "item";

            // $newMainDatas["folders"] = $folder_id;
            // $newMainDatas["merek_id"] = $merek_id;

            // foreach ($produkKoloms as $kolDb => $kolXls) {
            //     $newMainDatas[$kolDb] = $data[$kolXls];
            // }
// arrPrintHijau($data);
//            $pkode = $data['kode'];
            $pkode = str_replace("'", '', $data['barcode']);
//            $pname = strtolower($data['produk']);
            $pname = str_replace("'", '', strtolower($data['produk']));

//            $stok_rill = $data['stok rill'];
            $stok_rill = $data['stok riil'];
            // $harga = $data['harga beli'] * 1 > 0 ? $data['harga beli'] * 1 : 1;
            $harga = $data['harga beli satuan'] * 1 > 0 ? $data['harga beli satuan'] * 1 : 1;

            if (isset($groupByName[$pname])) { //dari MdlProduk
                //            if(isset($groupByCode[$pkode]) || isset($groupByName[$pname])){ //dari MdlProduk
                //            if(isset($groupByCode[$pkode])){ //dari MdlProduk

                //data produk sudah ada,
                $dat            = isset($groupByCode[$pkode]) ? $groupByCode[$pkode] : isset($groupByName[$pname]) ? $groupByName[$pname] : array();
                $coa_code       = $dat->coa_code;
                $dataCoa        = $mdlCoa[$coa_code];
                $dat->harga     = $harga;
                $dat->rill      = $stok_rill;
                $dat->nilai     = $harga*1>0&&$stok_rill*1>0?$harga*$stok_rill:0;

                $posisi = "debet";

                $pbNewData = array(
                    "toko_id"       => my_toko_id(),
                    "p_head_code"   => isset($dataCoa->p_head_code) ? $dataCoa->p_head_code : 0,
                    "dtime"         => date("Y-m-d H:i:s"),
                    "status"        => 1,
                    "harga"         => $dat->harga,
                    "qty"           => $dat->rill,
                    $posisi         => $dat->nilai,
                    "extern_id"     => isset($dataCoa->extern_id)   ? $dataCoa->extern_id : 0,
                    "sub_rekening"  => isset($dataCoa->head_name)   ? $dataCoa->head_name : "",
                    "head_code"     => isset($dataCoa->head_code)   ? $dataCoa->head_code : 0,
                    "parent"        => 1010030,
                    "rekening"      => isset($dataCoa->rekening)    ? $dataCoa->rekening : "",
                    "oleh_nama"     => my_name(),
                    "oleh_id"       => my_id(),
                );

//                arrPrintPink($pbNewData);
// break;
                if( isset($trPindahBuku[$coa_code]) ){
                    //data pindah buku sudah ada
                    $dataCondites = array(
                        "head_code" => $coa_code,
                        "status" => 1,
                    );
                    $pbDelData = array(
                        "status" => 0
                    );
                    $pb->updateData($dataCondites, $pbDelData);
                    showLast_query("merah");

                    $pb->addData($pbNewData);
                    showLast_query("hijau");
                }
                else{
                    //data pindah buku belum ada
                    $pb->addData($pbNewData);
                    showLast_query("hijau");
                }

                //update table transaksi pindah buku


            }
            else{
                //data produk belum ada,


            }

            /* --------------------------------
            * insert main data (produk)
            * ---------------------------------*/

            // foreach ($produkKoloms as $kolXls => $kolDb) {
            //     $newDatas[$kolDb] = $data[$kolXls];
            // }

            // $dataCondites["kode"] = $data["kode_brg"];
            // $dataDb = $pr->lookupByCondition($dataCondites)->result();

            // if (is_array($dataDb) && sizeof($dataDb) > 0) {
            //     showLast_query("lime");
            //     $pr->updateData($dataCondites, $newMainDatas);
            //     showLast_query("kuning");
            // }

            // else {
            //     $pr->addData($newMainDatas);
            //     showLast_query("orange");
            // }

            // matiHere(__LINE__ . " =============== MATI DULU dalam foreach=========== ");
        }


        // matiHere(__LINE__ . " =============== MATI DULU =========== ");
        $this->db->trans_commit();
        cekHitam("selesai dan commit");
    }

    /* ----------------------------------------------------
         * untuk memasukan produk ke shoping cart distribusi
         * ------------------------------------------------------ start off injektor shoping cart distribusi---------------------------------
         * var arrProduk =
            {"2021":{"qty":"2"},"4829":{"qty":"4"},"5017":{"qty":"1"},"5261":{"qty":"53"},"6357":{"qty":"1"},"8601":{"qty":"10"},"9629":{"qty":"1"},"9717":{"qty":"1"},"11665":{"qty":"72"},"13249":{"qty":"1"},"21489":{"qty":"10"},"25405":{"qty":"1"},"25429":{"qty":"10"},"25437":{"qty":"7"},"25477":{"qty":"15"},"25669":{"qty":"15"},"25685":{"qty":"1"},"25993":{"qty":"1"},"26141":{"qty":"5"},"30861":{"qty":"1"},"31009":{"qty":"4"},"32689":{"qty":"3"},"33705":{"qty":"13"},"37699":{"qty":"4"},"37716":{"qty":"2"},"37838":{"qty":"8"},"37850":{"qty":"22"},"37907":{"qty":"1"},"37998":{"qty":"2"},"40595":{"qty":"1"},"40976":{"qty":"63"},"41003":{"qty":"6"},"41004":{"qty":"8"},"41005":{"qty":"7"},"41006":{"qty":"16"},"41007":{"qty":"72"},"41008":{"qty":"10"},"41009":{"qty":"4"},"41010":{"qty":"4"},"41011":{"qty":"19"},"41012":{"qty":"6"},"41013":{"qty":"7"},"41014":{"qty":"5"},"41015":{"qty":"25"},"41016":{"qty":"18"},"41017":{"qty":"18"},"41018":{"qty":"11"},"41019":{"qty":"4"},"41020":{"qty":"75"},"41021":{"qty":"13"},"41022":{"qty":"24"},"41023":{"qty":"17"},"41024":{"qty":"1"},"41025":{"qty":"12"},"41026":{"qty":"20"},"41027":{"qty":"1"},"41028":{"qty":"1"},"41029":{"qty":"4"},"41030":{"qty":"2"},"41031":{"qty":"1"},"41032":{"qty":"1"},"41033":{"qty":"2"},"41034":{"qty":"3"},"41035":{"qty":"4"},"41036":{"qty":"8"},"41037":{"qty":"1"},"41038":{"qty":"1"},"41039":{"qty":"3"},"41040":{"qty":"41"},"41041":{"qty":"3"},"41042":{"qty":"1"},"41043":{"qty":"1"},"41044":{"qty":"2"},"41045":{"qty":"3"},"41046":{"qty":"11"},"41047":{"qty":"1"},"41048":{"qty":"13"},"41049":{"qty":"90"},"41050":{"qty":"6"},"41051":{"qty":"1"},"41052":{"qty":"2"},"41053":{"qty":"10"},"41054":{"qty":"1"},"41055":{"qty":"7"},"41056":{"qty":"9"},"41057":{"qty":"1"},"41058":{"qty":"8"},"41059":{"qty":"4"},"41060":{"qty":"1"},"41061":{"qty":"2"},"41062":{"qty":"22"},"41063":{"qty":"1"},"41064":{"qty":"4"},"41065":{"qty":"3"},"41066":{"qty":"1"},"41067":{"qty":"2"},"41068":{"qty":"1"},"41069":{"qty":"6"},"41070":{"qty":"4"},"41071":{"qty":"1"},"41072":{"qty":"7"},"41073":{"qty":"4"},"41074":{"qty":"2"},"41075":{"qty":"1"},"41076":{"qty":"2"},"41077":{"qty":"3"},"41078":{"qty":"1"},"41079":{"qty":"4"},"41080":{"qty":"6"},"41081":{"qty":"1"},"41082":{"qty":"1"},"41083":{"qty":"2"},"41084":{"qty":"1"},"41085":{"qty":"2"},"41086":{"qty":"2"},"41087":{"qty":"1"},"41088":{"qty":"4"},"41089":{"qty":"10"},"41090":{"qty":"1"},"41091":{"qty":"2"},"41092":{"qty":"1"},"41093":{"qty":"1"},"41094":{"qty":"1"},"41095":{"qty":"1"},"41096":{"qty":"1"},"41097":{"qty":"1"},"41098":{"qty":"1"},"41099":{"qty":"1"},"41100":{"qty":"1"},"41101":{"qty":"1"},"41102":{"qty":"3"},"41103":{"qty":"1"},"41104":{"qty":"16"},"41105":{"qty":"1"},"41106":{"qty":"2"},"41107":{"qty":"13"},"41108":{"qty":"1"},"41109":{"qty":"3"},"41110":{"qty":"3"},"41111":{"qty":"14"},"41112":{"qty":"3"},"41113":{"qty":"10"},"41114":{"qty":"6"},"41115":{"qty":"10"},"41116":{"qty":"7"},"41117":{"qty":"18"},"41118":{"qty":"20"},"41119":{"qty":"2"},"41120":{"qty":"6"},"41121":{"qty":"3"},"41122":{"qty":"1"},"41123":{"qty":"3"},"41124":{"qty":"1"},"41125":{"qty":"1"},"41126":{"qty":"1"},"41127":{"qty":"1"},"41128":{"qty":"1"},"41129":{"qty":"1"},"41130":{"qty":"2"},"41131":{"qty":"1"},"41132":{"qty":"2"},"41133":{"qty":"2"},"41134":{"qty":"3"},"41135":{"qty":"1"},"41136":{"qty":"2"},"41137":{"qty":"1"},"41138":{"qty":"2"},"41139":{"qty":"1"},"41140":{"qty":"7"},"41141":{"qty":"1"},"41142":{"qty":"1"},"41143":{"qty":"1"},"41144":{"qty":"1"},"41145":{"qty":"1"},"41146":{"qty":"1"},"41147":{"qty":"1"},"41148":{"qty":"1"},"41149":{"qty":"2"},"41150":{"qty":"3"},"41151":{"qty":"1"},"41152":{"qty":"5"},"41153":{"qty":"13"},"41154":{"qty":"3"},"41155":{"qty":"3"},"41156":{"qty":"1"},"41157":{"qty":"6"},"41158":{"qty":"2"},"41159":{"qty":"2"},"41160":{"qty":"3"},"41161":{"qty":"4"},"41162":{"qty":"2"},"41163":{"qty":"1"},"41164":{"qty":"1"},"41165":{"qty":"5"},"41166":{"qty":"1"},"41167":{"qty":"1"},"41168":{"qty":"2"},"41169":{"qty":"1"},"41170":{"qty":"1"},"41171":{"qty":"1"},"41172":{"qty":"1"},"41173":{"qty":"1"},"41174":{"qty":"1"},"41175":{"qty":"2"},"41176":{"qty":"1"},"41177":{"qty":"3"},"41178":{"qty":"1"},"41179":{"qty":"1"},"41180":{"qty":"1"},"41181":{"qty":"1"},"41182":{"qty":"1"},"41183":{"qty":"1"},"41184":{"qty":"1"},"41185":{"qty":"4"},"41186":{"qty":"27"},"41187":{"qty":"9"},"41188":{"qty":"2"},"41189":{"qty":"8"},"41190":{"qty":"16"},"41191":{"qty":"1"},"41192":{"qty":"15"},"41193":{"qty":"1"},"41194":{"qty":"10"},"41195":{"qty":"2"},"41196":{"qty":"6"},"41197":{"qty":"4"},"41198":{"qty":"3"},"41199":{"qty":"2"},"41200":{"qty":"2"},"41201":{"qty":"2"},"41202":{"qty":"12"},"41203":{"qty":"5"},"41204":{"qty":"1"},"41205":{"qty":"5"},"41206":{"qty":"78"},"41207":{"qty":"86"},"41208":{"qty":"40"},"41209":{"qty":"1"},"41210":{"qty":"55"},"41211":{"qty":"161"},"41212":{"qty":"35"},"41213":{"qty":"50"},"41214":{"qty":"98"},"41215":{"qty":"55"},"41216":{"qty":"2"},"41217":{"qty":"1"},"41218":{"qty":"2"},"41219":{"qty":"1"},"41220":{"qty":"1"},"41221":{"qty":"2"},"41222":{"qty":"2"},"41223":{"qty":"3"},"41224":{"qty":"7"},"41225":{"qty":"104"},"41226":{"qty":"14"},"41227":{"qty":"8"},"41228":{"qty":"78"},"41229":{"qty":"27"},"41230":{"qty":"18"},"41231":{"qty":"14"},"41232":{"qty":"24"},"41233":{"qty":"2"},"41234":{"qty":"8"},"41235":{"qty":"7"},"41236":{"qty":"3"},"41237":{"qty":"11"},"41238":{"qty":"10"},"41239":{"qty":"3"},"41240":{"qty":"4"},"41241":{"qty":"13"},"41242":{"qty":"2"},"41243":{"qty":"10"},"41244":{"qty":"10"},"41245":{"qty":"10"},"41246":{"qty":"10"},"41247":{"qty":"3"},"41248":{"qty":"2"},"41249":{"qty":"3"},"41250":{"qty":"17"},"41251":{"qty":"2"},"41252":{"qty":"4"},"41253":{"qty":"8"},"41254":{"qty":"1"},"41255":{"qty":"6"},"41256":{"qty":"5"},"41257":{"qty":"11"},"41258":{"qty":"9"},"41259":{"qty":"2"},"41260":{"qty":"14"},"41261":{"qty":"2"},"41262":{"qty":"31"},"41263":{"qty":"1"},"41264":{"qty":"33"},"41265":{"qty":"100"},"41266":{"qty":"91"},"41267":{"qty":"38"},"41268":{"qty":"75"},"41269":{"qty":"458"},"41270":{"qty":"187"},"41271":{"qty":"1"},"41272":{"qty":"215"},"41273":{"qty":"1"},"41274":{"qty":"6"},"41275":{"qty":"10"},"41276":{"qty":"33"},"41277":{"qty":"9"},"41278":{"qty":"8"},"41279":{"qty":"9"},"41280":{"qty":"89"},"41281":{"qty":"149"},"41282":{"qty":"28"},"41283":{"qty":"27"},"41284":{"qty":"74"},"41285":{"qty":"10"},"41286":{"qty":"14"},"41287":{"qty":"15"},"41288":{"qty":"81"},"41289":{"qty":"123"},"41290":{"qty":"114"},"41291":{"qty":"137"},"41292":{"qty":"6"},"41293":{"qty":"2"},"41294":{"qty":"2"},"41295":{"qty":"2"},"41296":{"qty":"1"},"41297":{"qty":"4"},"41298":{"qty":"4"},"41299":{"qty":"18"},"41300":{"qty":"1"},"41301":{"qty":"2"},"41302":{"qty":"2"},"41303":{"qty":"9"},"41304":{"qty":"3"},"41305":{"qty":"8"},"41306":{"qty":"2"},"41307":{"qty":"2"},"41308":{"qty":"2"},"41309":{"qty":"3"},"41310":{"qty":"19"},"41311":{"qty":"10"},"41312":{"qty":"9"},"41313":{"qty":"6"},"41314":{"qty":"24"},"41315":{"qty":"1"},"41316":{"qty":"4"},"41317":{"qty":"5"}}

            function insertItem(ls_urut, ls_concated){
                var dTemp = JSON.parse(ls_concated);
                var data = dTemp[ls_urut];
                $('#result').load('https://teguh.mayagrahakencana.com/Selectors/_processSelectProduct/select/583?id='+data.id+'&minValue='+data.qty, null, function(){
                    if(data.qty>1){
                        changeUnit(ls_urut, ls_concated);
                    }
                    else{
                        rolling(ls_urut, ls_concated);
                    }
                })
            }

            function changeUnit(ls_urut, ls_concated){
                var dTemp = JSON.parse(ls_concated);
                var data = dTemp[ls_urut];
                $('#result').load('https://teguh.mayagrahakencana.com/Selectors/_processSelectProduct/select/583?id='+data.id+'&newQty='+data.qty+'&jml='+data.qty, null, function(){
                    rolling(ls_urut, ls_concated);
                })
            }

            function rolling(ls_urut, ls_concated){
                var dTemp = JSON.parse(ls_concated);
                var data = dTemp[ls_urut];
                var rl_ls_urut = (ls_urut-1)

                if(rl_ls_urut>=0){
                    setTimeout( function(){ insertItem(rl_ls_urut, ls_concated) }, 500);
                }
                else{

                }
            }

            var urut = 1;
            var concated = [];
            var arrays = [];

            jQuery.each(arrProduk, function(id,data){
                arrays = data
                arrays['id'] = id
                concated[urut] = arrays
                urut++
            });

            localStorage.setItem('urut', '');
            localStorage.setItem('concat', '');
            localStorage.setItem('urut', (urut-2));
            localStorage.setItem('concat', JSON.stringify(concated).replace('null,', '') );

            var ls_urut = localStorage.getItem('urut');
            var ls_concated = localStorage.getItem('concat');

            insertItem(ls_urut, ls_concated);

            // -------------------------------------------- end off injektor ------------------------------------------------

            ============VALIDATOR==========

            var ls_concated = localStorage.getItem('concat');
            var dTemp = JSON.parse(ls_concated);

            let timerInterval
            swal({
                title: 'INJECTOR SELESAI',
                html: 'SEDANG MELAKUKAN VALIDASI, TUNGGU SEBENTAR',
                timer: 5000,
                onOpen: () => {
                swal.showLoading()
            },
                onClose: () => {
                    clearInterval(timerInterval)

                    var arr_kode = $('span[keyid=code]');
                    var arrKODE = [];
                    jQuery.each(arr_kode, function(i, b){
                        arrKODE[$(b).attr('noid')] = b
                    });

                    var arr_ui_data = $('input[keyid=jml]');
                    var arrUI = []
                    jQuery.each(arr_ui_data, function(i, b){
                        arrUI[$(b).attr('id_jml')] = b
                    });

                    var isiAlert = "";
                    jQuery.each(dTemp, function(i, data){
                        if( $(arrUI[data.id]).val() !== data.qty ){
                            isiAlert += 'NAMA: ' + $(arrKODE[$(arrUI[data.id]).attr('noid')]).html() + ' -- value tidak sama ===>>> data: ' + data.id + ' || UI: ' + $(arrUI[data.id]).val() + ' <=> CONT: ' + data.qty + '<br>'
                        }
                    })

                    swal({
                        title: 'VALIDASI SELESAI',
                        html: isiAlert
                    });
                }
            }).then( (result) => {  })

            ======== VALIDATOR=============
         *
         * -----------------------------------------------------*/
    public function formProdukInjektor()
    {
        echo ($this->router->method) . "<hr>";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukInjek/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function importProdukInjek()
    {
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();

        $produkKoloms = array(
            "kode_brg" => "kode",
            "nama_brg" => "nama",
            "merk" => "merek_nama",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "keterangan" => "keterangan",
            "qtylimit" => "limit",
            "kode_klp" => "folders_kode",
        );
        $this->xlsx = new PHPExcel_Reader_Excel2007();

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

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

        // arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                // arrPrintWebs($rows);
                if ($rows['qtyt_ak'] > 0) {

                    // $datas[$numrow] = $rows;
                    $datas[$rows['kode_brg']]['qty'] = $rows['qtyt_ak'];
                    $datas[$rows['kode_brg']]['hpp'] = $rows['hpp'];
                    $datas[$rows['kode_brg']]['sub_hpp'] = $rows['hpp'] * $rows['qtyt_ak'];
                    $datas[$rows['kode_brg']]['jual'] = $rows['hargajual'];
                    // $datas[$rows['kode_brg']]['id'] = $rows['qtyt_ak'];
                }
            }
            $numrow++;
        }

        $arrKodeBrg = array_keys($datas);

        $dataCondites["trash"] = 0;
        $this->db->or_where_in("kode", $arrKodeBrg);
        // $dataDb = $pr->lookupByCondition($dataCondites)->result();
        $dataDb = $pr->lookupAll()->result();
        // showLast_query("here");

        foreach ($dataDb as $itemDb) {
            $kode = $itemDb->kode;
            $reqQty = $datas[$kode]['qty'];
            $dataInjek[$itemDb->id]['qty'] = $reqQty;
            // $dataInjek[$itemDb->id]['kode'] = $kode;
            $datas[$kode]['id'] = $itemDb->id;
        }

        $dataInjek_json = json_encode($dataInjek);

        $xl_jml_data = sizeof($sheet);
        $new_jml_data = sizeof($datas);
        // $jml_yg_diimport = sizeof($tmpResultEx);
        $jml_dlm_db = sizeof($dataDb);
        cekHere("file: $name <br>jml_data excel = $xl_jml_data akan dimasukan : $new_jml_data jml data dB: $jml_dlm_db");
        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // cekHijau(sizeof($dataInjek));
        arrPrintPink($dataInjek_json);
        arrPrintWebs(($datas));
        // arrPrintPink($datas);


        matiHere(__LINE__ . " done");

    }

    /* ----------------------------------------------------
     * IMPORTER harga jual dan HPP
     * -----------------------------------------------------*/
    public function formProdukPrice()
    {

        echo ($this->router->method) . "<hr>";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukPrice/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function importProdukPrice()
    {
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlHargaProduk");
        $pr = new MdlProduk();
        $pc = new MdlHargaProduk();

        $produkKoloms = array(
            "kode_brg" => "kode",
            "nama_brg" => "nama",
            "merk" => "merek_nama",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "keterangan" => "keterangan",
            "qtylimit" => "limit",
            "kode_klp" => "folders_kode",
        );
        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        // $cabang_id = "5"; // sparman
        // $cabang_id = "2"; // karang klesem
        // $cabang_id = "1"; // terminal
        $cabang_id = "-1"; // pusat
        // $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];

        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

        // arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                // arrPrintWebs($rows);
                // if ($rows['qtyt_ak'] > 0) {
                if ($rows['harga jual'] > 0) {
                    // if ($rows['hargapokok'] > 0) {

                    // $datas[$numrow] = $rows;
                    $datas[$rows['kode_brg']]['qty'] = $rows['qtyt_ak'];
                    $datas[$rows['kode_brg']]['hpp'] = $rows['hpp'];
                    $datas[$rows['kode_brg']]['jual'] = $rows['harga jual'];
                    $datas[$rows['kode_brg']]['jual_nppn'] = $rows['harga jual'];
                    // $datas[$rows['kode_brg']]['id'] = $rows['qtyt_ak'];
                }
            }
            $numrow++;
        }

        $arrKodeBrg = array_keys($datas);
        cekBiru("$name JMLdATA:: " . sizeof($datas) . " CABANG:: " . $cabang_id);
        $dataCondites["trash"] = 0;
        $this->db->or_where_in("kode", $arrKodeBrg);
        // $dataDb = $pr->lookupByCondition($dataCondites)->result();
        $dataDb = $pr->lookupAll()->result();
        // showLast_query("here");


        $harga_jenis = "produk";
        $harga_jenis_values = array(
            "jual_nppn",
            "jual",
            "hpp",
        );
        $this->db->trans_begin();

        foreach ($dataDb as $itemDb) {
            $produk_id = $itemDb->id;
            $kode = $itemDb->kode;
            $reqQty = $datas[$kode]['qty'];
            // $harga_jual = $datas[$kode]['jual'];
            $dataInjek[$itemDb->id]['qty'] = $reqQty;
            // $dataInjek[$itemDb->id]['kode'] = $kode;
            $datas[$kode]['id'] = $itemDb->id;


            foreach ($harga_jenis_values as $harga_jenis_value) {

                $harga_jual = $datas[$kode][$harga_jenis_value];

                $priceDatas = array(
                    "jenis" => $harga_jenis,
                    "jenis_value" => $harga_jenis_value,
                    "produk_id" => $produk_id,
                    "cabang_id" => $cabang_id,
                    "nilai" => $harga_jual,
                    "oleh_id" => my_id(),
                    "oleh_nama" => my_name(),

                );

                // insert atau update produk pricece
                $condites = array(
                    "jenis" => $harga_jenis,
                    "jenis_value" => $harga_jenis_value,
                    "produk_id" => $produk_id,
                    "cabang_id" => $cabang_id,
                    // "nilai"       => $harga_jual,
                    "trash" => 0,
                );
                $prices = $pc->lookupByCondition($condites)->result();
                showLast_query("lime", sizeof($prices));
                // arrPrint($prices);
                if (sizeof($prices) == 0) {
                    $pc->addData($priceDatas);
                    showLast_query("orange");
                }
                else {
                    $newDatas = array(
                        "trash" => 1,
                    );
                    $condites["nilai !="] = $harga_jual;

                    // $this->db->or_where("nilai",null);
                    $pc->updateData($condites, $newDatas);
                    showLast_query("kuning");

                    // cekBiru($this->db->affected_rows());
                    if ($this->db->affected_rows()) {

                        $pc->addData($priceDatas);
                        showLast_query("orange");
                    }
                }


            }


            // matiHere(__LINE__);
        }

        matiHere("------------------- belom commit " . __LINE__ . " -----------------");
        $this->db->trans_complete();

        // $dataInjek_json = json_encode($dataInjek);
        //
        // $xl_jml_data = sizeof($sheet);
        // $new_jml_data = sizeof($datas);
        // // $jml_yg_diimport = sizeof($tmpResultEx);
        // $jml_dlm_db = sizeof($dataDb);
        // cekHere("file: $name <br>jml_data excel = $xl_jml_data akan dimasukan : $new_jml_data jml data dB: $jml_dlm_db");
        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // cekHijau(sizeof($dataInjek));
        // arrPrintPink($dataInjek_json);
        // arrPrintWebs(($datas));
        // arrPrintPink($datas);


        matiHere(__LINE__ . " done");

    }

    /* --------------------------------------
     * produk sullpier
     * -----------------------------------*/
    public function formSupplier()
    {

        echo ($this->router->method) . "<hr>";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importSupplier/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function importSupplier()
    {
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlSupplier");
        $sp = new MdlSupplier();

        $splKoloms = array(
            "kode_spl" => "kode",
            "nama_spl" => "nama",
            "kota" => "kabupaten",
            "telepon" => "tlp_1",
            "person" => "person_nama",
        );
        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "5"; // sparman
        // $cabang_id = "2"; // karang klesem
        // $cabang_id = "1"; // terminal
        // $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];

        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

        // arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias//$kolom: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                // arrPrintWebs($rows);
                // if ($rows['qtyt_ak'] > 0) {
                if (strlen($rows['kode_spl']) > 0) {
                    // if ($rows['hargapokok'] > 0) {

                    // $datas[$numrow] = $rows;
                    $datas[$rows['kode_spl']] = $rows;
                    // $datas[$rows['kode_spl']]['hpp'] = $rows['hargapokok'];
                    // $datas[$rows['kode_spl']]['jual'] = $rows['hargajual'];
                    // $datas[$rows['kode_brg']]['id'] = $rows['qtyt_ak'];
                }
            }
            $numrow++;
        }
        // arrPrintPink($datas);
        // matiHere(__LINE__);

        $arrKodeBrg = array_keys($datas);
        cekBiru("$name JMLdATA:: " . sizeof($datas));

        $dataCondites["trash"] = 0;
        // $this->db->or_where_in("kode", $arrKodeBrg);
        // $dataDb = $pr->lookupByCondition($dataCondites)->result();
        // $dataDb = $sp->lookupAll()->result();
        // showLast_query("here");

        // matiHere(__LINE__);
        //         $harga_jenis = "produk";
        //         $harga_jenis_values = array(
        //             "jual_nppn", "jual"
        //         );

        $this->db->trans_commit();
        $nomer = 0;
        foreach ($datas as $kodeSpl => $splDatas) {
            $nomer++;
            $spl_nama = $splDatas['nama_spl'];
            $condites = array(
                "nama" => $spl_nama
            );
            $this->db->where($condites);
            $dataDb = $sp->lookupAll()->result();
            showLast_query("here", "#$nomer");

            foreach ($splKoloms as $xlKolom => $dbKolom) {

                $newDatas[$dbKolom] = $splDatas[$xlKolom];
            }

            if (sizeof($dataDb) == 0) {
                $newDatas['employee_type'] = 'supplier';
                $newDatas['status'] = '1';
                $newDatas['div_id'] = '2';
                $newDatas['country'] = 'ID';
                $newDatas['country_name'] = 'IDONESIA';

                $sp->addData($newDatas);
                showLast_query("orange");
            }
            else {

                // $condites["nilai !="] = $harga_jual;
                $sp->updateData($condites, $newDatas);
                showLast_query("kuning");

            }

            // matiHere(__LINE__);
        }

        // matiHere("belom commit " . __LINE__);
        $this->db->trans_complete();


        matiHere(__LINE__ . " done");

    }

    /* --------------------------------------
     * produk sullpier
     * -----------------------------------*/
    public function formProdukSupplier()
    {

        echo ($this->router->method) . "<hr>";
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importProdukSupplier/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function importProdukSupplier()
    {
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlSupplier");
        $sp = new MdlSupplier();
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $pps = new MdlProdukPerSupplier();
        $this->load->model("Mdls/MdlProduk");
        $pro = new MdlProduk();

        $splKoloms = array(
            "kode_spl" => "kode",
            "nama_spl" => "nama",
            "kota" => "kabupaten",
            "telepon" => "tlp_1",
            "person" => "person_nama",
        );
        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        // $cabang_id = "5"; // sparman
        // $cabang_id = "2"; // karang klesem
        // $cabang_id = "1"; // terminal
        $cabang_id = "-1"; // pusatl
        // $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];

        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];

        // arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias//$kolom: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                // arrPrintWebs($rows);
                // if ($rows['qtyt_ak'] > 0) {
                if (strlen($rows['kode_spl']) > 0) {
                    // if ($rows['hargapokok'] > 0) {

                    $datas[$numrow] = $rows;
                    // $datas[$rows['kode_spl']] = $rows;
                    // $datas[$rows['kode_spl']]['hpp'] = $rows['hargapokok'];
                    // $datas[$rows['kode_spl']]['jual'] = $rows['hargajual'];
                    // $datas[$rows['kode_brg']]['id'] = $rows['qtyt_ak'];

                    $kodeBrgs[] = $rows['kode_brg'];
                    $kodeSpls[] = $rows['kode_spl'];
                }
            }
            $numrow++;
        }
        // arrPrintPink($datas);
        // arrPrintPink($kodeBrgs);
        // arrPrintPink($kodeSpls);
        // matiHere(__LINE__);

        //<editor-fold desc="data dB supplier">
        $this->db->where_in("kode", $kodeSpls);
        $dataSplDb = $sp->lookupAll()->result();
        // showLast_query("here");

        // cekBiru($dataSplDb);
        $kolSpl = array(
            "id",
            "kode",
            "nama"
        );
        $splDatas = array();
        foreach ($dataSplDb as $item) {
            foreach ($kolSpl as $kolom) {
                $splDatas[$item->kode][$kolom] = $item->$kolom;
            }
        }
        //</editor-fold>
        // cekBiru($splDatas);

        //<editor-fold desc="data dB produk">
        $this->db->where_in("kode", $kodeBrgs);
        $dataBrgDb = $pro->lookupAll()->result();
        // showLast_query("here");
        $kolBrg = array(
            "id",
            "kode",
            "nama"
        );
        $brgDatas = array();
        foreach ($dataBrgDb as $item) {
            foreach ($kolBrg as $kolom) {
                $brgDatas[$item->kode][$kolom] = $item->$kolom;
            }
        }
        //</editor-fold>
        // cekBiru($brgDatas);

        cekBiru("$name JMLdATA:: " . sizeof($datas));

        $this->db->trans_commit();
        $nomer = 0;
        foreach ($datas as $data) {
            $nomer++;

            $kode_spl = $data['kode_spl'];
            $id_spl = isset($splDatas[$kode_spl]['id']) ? $splDatas[$kode_spl]['id'] : "0";
            $nama_spl = isset($splDatas[$kode_spl]['nama']) ? $splDatas[$kode_spl]['nama'] : "-";
            // cekOrange("$nama_spl");
            $kode_brg = $data['kode_brg'];
            $id_brg = $brgDatas[$kode_brg]['id'];
            $nama_brg = $brgDatas[$kode_brg]['nama'];

            if (($id_spl > 0) && ($id_brg > 0)) {

                $condites = array(
                    "produk_id" => $id_brg,
                    "suppliers_id" => $id_spl,
                );
                // $this->db->where($condites);
                $dataDb = $pps->lookupByCondition($condites)->result();
                showLast_query("here", "#$nomer " . sizeof($dataDb));

                if (sizeof($dataDb) == 0) {
                    $newDatas['produk_id'] = $id_brg;
                    $newDatas['produk_nama'] = $nama_brg;
                    $newDatas['produk_kode'] = $kode_brg;
                    $newDatas['suppliers_id'] = $id_spl;
                    $newDatas['suppliers_kode'] = $kode_spl;
                    $newDatas['suppliers_nama'] = $nama_spl;
                    $newDatas['cabang_id'] = $cabang_id;
                    $newDatas['status'] = '1';

                    $pps->addData($newDatas);
                    showLast_query("orange");
                    // cekHijau();
                }
                else {

                }


            }
            else {
                cekHitam("$kode_spl $nama_spl");
                // $condites = array(
                //     "produk_id" => $id_brg,
                //     "suppliers_id" => $id_spl,
                // );
                // // $this->db->where($condites);
                // $dataDb = $pps->lookupByCondition($condites)->result();
                // showLast_query("hijau", "#$nomer " . sizeof($dataDb));
            }


        }

        // arrPrintPink($datas);
        // matiHere(__LINE__);

        // matiHere("belom commit " . __LINE__);
        $this->db->trans_complete();


        matiHere(__LINE__ . " done");

    }

    //-----------------------------------------------------------------
    /* ------------------------------------------------------------------START-
     * IMPORTER DATA
     * ---------------------------------*/
    public function formPricelist()
    {
        $segment_4 = $this->uri->segment(3);
        $seg_4 = isset($segment_4) ? "/$segment_4" : "/none";
        echo ($this->router->method) . " <a class='pull-right' href='" . base_url() . "Converter/downloadPricelist' target='result'><i class='fa fa-download'></i> download template excel</a><hr>";

        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "Converter/importPricelistPre/$seg_4' target='result'> ";
        echo "<div class='form-group'>";
        echo "<label>file input</label>";

        echo "<input type='file' name='fileExcel' required>";
        echo "<hr>";
        echo "<p>reader xlsx</p>";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
        echo "<input type='submit' name='submit_file' value='submit file' class='btn btn-primary'>";
        echo "</div>";

        echo "</form>";
    }

    /* ----------------------------------------------------
     * IPORDER DATA PRODUK
     * dan data pembantu produk
     * -----------------------------------------------------*/
    public function importPricelist()
    {
        $segment_3 = $this->uri->segment(3);
        $toko_id = my_toko_id();
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlHargaProduk");
        $mainObj = $pr = new MdlProduk();
        $produkKoloms = array(
            "sku (kode produk)" => "kode",
            "nama produk" => "nama",
            // "merk"       => "merek_nama",
            // "no_part"    => "no_part",
            // "satuan"     => "satuan",
            // "keterangan" => "keterangan",
            // "qtylimit"   => "limit",
            "kategori" => "folder_nama",
            "satuan" => "satuan",
        );
        $hargaKolom = array(
            "hpp",
            "jual_1",
            "jual_2",
            "jual_3",
        );

//        $this->load->model("Mdls/MdlFolderProduk");
//        $fp = new MdlFolderProduk();
//        $fpKoloms = array(
//            "nama" => "kategori",
//            // "toko_id" => $toko_id,
//        );
//
//        $this->load->model("Mdls/MdlSatuan");
//        $st = new MdlSatuan();
//        $satuanKoloms = array(
//            "nama" => "satuan",
//            // "kode" => "satuan",
//        );
//
//        $this->load->model("Mdls/MdlMerek");
//        $mr = new MdlMerek();
//        $merekKoloms = array(
//            "merek" => "nama"
//        );
//
//        $this->load->model("Mdls/MdlLokasiIndex");
//        $lk = new MdlLokasiIndex();
//        $lokasiKoloms = array(
//            // "nama" => "kode_lkst",
//            // "nama" => "kode_lksg",
//        );

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext . "<hr> gantilah dengan file xlsx") : "";

        // $datas = $this->xlsx->load($tmp);
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        // $datas = $this->xlsx->reader($tmp);

        // $loadexcel = $this->xlsx->load($tmp);
        // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                foreach ($row as $dkey => $dval) {
                    if ($dval == NULL) {
                        unset($row[$dkey]);
                    }
                }
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = array_filter($headers[$data_header]);

//        arrPrint($koloms);
        // matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // arrPrintPink($datas);
        cekBiru("file: $name <br>dt-excel: " . sizeof($sheet) . " dt-ok: " . sizeof($datas));
        // arrPrintPink($datas);
//        mati_disini(__LINE__);
        $this->db->trans_begin();


        $hrInsert = array();
        foreach ($datas as $data) {
//            $newMainDatas = array();
//            $newMerekDatas = array();
//            $newSatuanDatas = array();
//            $newFolderDatas = array();
//            /* --------------------------------
//            * insert folder
//            * ---------------------------------*/
//            if (sizeof($fpKoloms) > 0) {
//                foreach ($fpKoloms as $kolDb => $kolXls) {
//                    $newFolderDatas[$kolDb] = $data[$kolXls];
//                }
//                $newFolderDatas['toko_id'] = $toko_id;
//                // $newFolderDatas['nama'] = $toko_id;
//                $folderCondites = $newFolderDatas;
//                $folderDb = $fp->lookupByCondition($folderCondites)->result();
//                if (is_array($folderDb) && sizeof($folderDb) > 0) {
//                    showLast_query("lime");
//                    $folder_id = $folderDb[0]->id;
//                }
//                else {
//                    $folder_id = $fp->addData($newFolderDatas);
//                    showLast_query("orange");
//                }
//            }
//
//            /* --------------------------------
//             * insert merek
//             * ---------------------------------*/
//            if (sizeof($merekKoloms) > 0) {
//                foreach ($merekKoloms as $kolXls => $kolDb) {
//                    $newMerekDatas[$kolDb] = $data[$kolXls];
//                }
//                $merekCondites = $newMerekDatas;
//                $merekDb = $mr->lookupByCondition($merekCondites)->result();
//                if (is_array($merekDb) && sizeof($merekDb) > 0) {
//                    showLast_query("lime");
//                    $merek_id = $merekDb[0]->id;
//                }
//                else {
//                    $merek_id = $mr->addData($newMerekDatas);
//                    showLast_query("orange");
//                }
//            }
//
//            /* --------------------------------
//             * insert satuan
//             * ---------------------------------*/
//            if (sizeof($satuanKoloms) > 0) {
//                foreach ($satuanKoloms as $kolDb => $kolXls) {
//                    $newSatuanDatas[$kolDb] = $data[$kolXls];
//                }
//                $satuanCondites = $newSatuanDatas;
//                $satuanDb = $st->lookupByCondition($satuanCondites)->result();
//                if (is_array($satuanDb) && sizeof($satuanDb) > 0) {
//                    showLast_query("lime");
//                    $satuan_id = $satuanDb[0]->id;
//
//                }
//                else {
//                    $satuan_id = $st->addData($newSatuanDatas);
//                    showLast_query("orange");
//                }
//            }
//
//            /* --------------------------------
//             * insert lokasi
//             * ---------------------------------*/
//            if (sizeof($lokasiKoloms) > 0) {
//                foreach ($lokasiKoloms as $kolDb => $kolXls) {
//                    $newLokasiDatas[$kolDb] = $data[$kolXls];
//                }
//                $newLokasiDatas['cabang_id'] = '1';
//                $lokasiCondites = $newLokasiDatas;
//                $lokasiDb = $lk->lookupByCondition($lokasiCondites)->result();
//                if (is_array($lokasiDb) && sizeof($lokasiDb) > 0) {
//                    showLast_query("merah");
//                    $lokasi_id = $lokasiDb[0]->id;
//
//                }
//                else {
//                    $lokasi_id = $lk->addData($newLokasiDatas);
//                    showLast_query("here");
//                }
//            }
//            // matiHere($merek_id);
//            /* --------------------------
//             * insert main data
//             * --------------------*/
//
//            $newMainDatas["jenis"] = "item";
//            $newMainDatas["folder_id"] = $folder_id;
//            // $newMainDatas["merek_id"] = $merek_id;
//            $newMainDatas["toko_id"] = $toko_id;
//            foreach ($produkKoloms as $kolXls => $kolDb) {
//                $newMainDatas[$kolDb] = isset($data[$kolXls]) ? $data[$kolXls] : "";
//            }
            // arrPrint($newMainDatas);
            // matiHere(__LINE__);
//            /* --------------------------------
//            * insert main data (produk)
//            * ---------------------------------*/
            // foreach ($produkKoloms as $kolXls => $kolDb) {
            //     $newDatas[$kolDb] = $data[$kolXls];
            // }
//
//            $dataCondites["nama"] = $data["nama produk"];
//            $dataCondites["toko_id"] = $toko_id;
//            $dataDb = $pr->lookupByCondition($dataCondites)->result();
//            if (is_array($dataDb) && sizeof($dataDb) > 0) {
//                // $satuan_id = $satuanDb[0]->id;
//                showLast_query("lime");
//                $pr->updateData($dataCondites, $newMainDatas);
//                showLast_query("kuning");
//            }
//            else {
//                cekBiru("insert");
//                // $satuan_id = $mr->addData($newSatuanDatas);
//                $mainInsertId = $pr->addData($newMainDatas);
//                showLast_query("orange");
//
//                /* -------------------------------------
//                 * tested pada auto COA yg pakai aaproval masuk di do
//                 * -------------------------------------*/
//                if (method_exists($mainObj, "getConnectingData")) {
//                    $nama = ucwords($newMainDatas['nama']);
//                    $negara = isset($data['country']) ? $data['country'] : "";
//                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
//                    $my_name = my_name();
//
//                    cekBiru($negara . " $extern_tipe");
//                    $connectings = $mainObj->getConnectingData();
//                    // arrPrintPink($connectings);
//                    foreach ($connectings as $model => $param_connectings) {
//                        foreach ($param_connectings as $p_key => $param_connecting) {
//
//
//                            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
//                            $this->load->model($param_connecting['path'] . "/$model");
//                            $connObj = new $model();
//                            // $strHead_code = isset($param_connecting['staticOptions'][$extern_tipe]) ? $param_connecting['staticOptions'][$extern_tipe] : matiHere("parameter");
//                            if (isset($param_connecting['staticOptions'])) {
//
//                                $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'][$extern_tipe] : $param_connecting['staticOptions'];
//                            }
//                            else {
//                                matiHere("static optionnya tolong dikasih");
//                            }
//                            $datas = array();
//
//                            foreach ($fields as $field => $cfParams) {
//
//                                if (isset($cfParams['var_main'])) {
//                                    $cNilai = $$cfParams['var_main'];
//                                }
//                                else {
//                                    $cNilai = $cfParams['str'];
//                                }
//
//                                $datas[$field] = $cNilai;
//                            }
//
//                            // arrPrint($datas);
//                            // cekLime();
//                            /* -------------------------------------------------
//                             * menulis ke table connecting
//                             * -------------------------------------------------*/
//                            $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $toko_id, $datas);
//                            showLast_query("merah");
//
//                            /* -------------------------------------------------
//                             * ngupdate ke data utama
//                             * -------------------------------------------------*/
//                            if (isset($param_connecting['updateMain'])) {
//
//                                foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
//                                    $mainCondites[$key] = $$condite;
//                                }
//                                foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
//                                    $mainUpdate[$key] = $$val;
//                                }
//
//                                $mainObj->updateData($mainCondites, $mainUpdate);
//                                showLast_query("orange");
//                            }
//
//                            cekHitam($lastInset_code);
//                        }
//                    }
//
//
//                    // arrPrint($connecting);
//                }
//            }
//            arrPrintPink($data);
            foreach ($hargaKolom as $hKolom){
                $dataEntry = array(
                    "produk_id" => $data['pid'],
                    "jenis_value" => $hKolom,
                    "cabang_id" => $this->session->login['cabang_id'],
                    "toko_id" => $this->session->login['toko_id'],
                    "nilai" => $data[$hKolom],
                    "oleh_id" => $this->session->login['id'],
                    "oleh_nama" => $this->session->login['nama'],
                    "dtime" => date("Y-m-d H:i:s"),
                );
                arrPrintWebs($dataEntry);
                $hr = New MdlHargaProduk();
                $hrInsert[] = $hr->addData($dataEntry);
                showLast_query("kuning");
            }
        }

        // cekHitam($segment_3);
        // $segment_3 = 'save';
        /* ----------------------------------------
         * marking cp_supplies
         * ----------------------------------------*/
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());
        $cp->updateDataPreparation("harga");

        // ---------------------------------------
        matiHere("belom comit");
        if ($segment_3 == "none") {
            matiDisini("belom commit " . __LINE__);
        }
        else {

            $this->db->trans_commit();

            $alertDone = array(
                "type" => "success",
                "html" => "data PRODUK berhasil diupload ke system",
            );
            echo swalAlert($alertDone);
            topReload();
            die();
            // matiDisini("selesai sudah comit");
        }
    }

    public function importPricelistPre()
    {
        $segment_3 = $this->uri->segment(3);
        $toko_id = my_toko_id();
        $this->load->library('PHPExcel');
        $this->load->model("Mdls/MdlProduk");
        $mainObj = $pr = new MdlProduk();
        $produkKoloms = array(
            "sku (kode produk)" => "kode",
            "nama menu / produk" => "nama",
            // "merk"       => "merek_nama",
            // "no_part"    => "no_part",
            // "satuan"     => "satuan",
            // "keterangan" => "keterangan",
            // "qtylimit"   => "limit",
            "kategori" => "folder_nama",
            "hrg jual" => "harga_jual",
        );

        $this->load->model("Mdls/MdlFolderProduk");
        $fp = new MdlFolderProduk();
        $fpKoloms = array(
            "nama" => "kategori",
            // "toko_id" => $toko_id,
        );

        $this->load->model("Mdls/MdlSatuan");
        $st = new MdlSatuan();
        $satuanKoloms = array(
            // "nama" => "satuan",
            // "kode" => "satuan",
        );

        $this->load->model("Mdls/MdlMerek");
        $mr = new MdlMerek();
        $merekKoloms = array(// "merk" => "nama"
        );

        $this->load->model("Mdls/MdlLokasiIndex");
        $lk = new MdlLokasiIndex();
        $lokasiKoloms = array(
            // "nama" => "kode_lkst",
            // "nama" => "kode_lksg",
        );

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $dtimeNow = date("Y-m-d H:i:s");
        $fulldateNow = date("Y-m-d");
        $cabang_id = "1";
        $gudang_id = "-10";


        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext . "<hr> gantilah dengan file xlsx") : "";


        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);

        // regionc config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                foreach ($row as $dkey => $dval) {
                    if ($dval == NULL) {
                        unset($row[$dkey]);
                    }
                }
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $koloms = $headers[$data_header];
//        arrPrintWebs($headers);
//        arrPrint($koloms);
//        matiHere(__LINE__);
        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }

        // arrPrintPink($sheet);
        // arrPrint($koloms);
        // arrPrint($headers[1]);
        // arrPrintPink($datas);
        // cekBiru("file: $name <br>dt-excel: " . sizeof($sheet) . " dt-ok: " . sizeof($datas));
        $jml_data_excel = sizeof($datas);
        // arrPrintPink($datas);
        $var_html = "";
        $var_html .= "Jumlah data yang terbaca $jml_data_excel";
        $var_html .= "<p>klik OK apa bila anda sudah yakin data yang diupload semua benar</p>";
        $var_html .= "untuk melihat hasil pembacaan <span class=\'btn btn-link\' id=\'cek_excel_data\'> klik lihat data</span>";


        //<editor-fold desc="hasil pembacaan data excel ">
        $excelShow = "<table rules=\'all\' border=\'1\' cellpadding=\'5\' bordercolor=\'#ddd\'>";
        $excelShow .= "<tr>";
        $excelShow .= "<th>no</th>";
        foreach ($koloms as $kolom => $kalias) {
            $excelShow .= "<th>$kalias</th>";
        }
        $excelShow .= "</tr>";

        $no = 0;
        foreach ($datas as $data) {
            $no++;

            $excelShow .= "<tr>";
            $excelShow .= "<td>$no</td>";
            foreach ($koloms as $kolom => $kalias) {

                $xl_value = str_replace("'", "", $data[$kalias]);
                $xlsValue = $xl_value;
                $format_td = is_numeric($xl_value) ? "align=\'right\'" : "";

                $excelShow .= "<td $format_td>$xlsValue</td>";
            }
            $excelShow .= "</tr>";
        }
        $excelShow .= "<table>";
        //</editor-fold>

//echo $excelShow;
//mati_disini();

        $alerts = array(
            "type" => "'info'",
            "html" => "'$var_html'",
            // "showCloseButton" => true,
            "showCancelButton" => true,
            "onOpen" => "function(){ top.$('span#cek_excel_data').on('click', function(){ top.BootstrapDialog.show({ title:'Excel Reader', message: '" . $excelShow . "', onshown: function(){ top.$('.modal-backdrop.in').css('z-index',2040); top.$('.modal.in').css('z-index',2540) }  });  } )  }"
        );

        $action_2 = "Converter/importPricelist/save";
        echo swalAlertSubmit_2($alerts, $action_2);
        die();
        mati_disini(__LINE__);

    }

    public function downloadPricelist()
    {
        $dateNow = dtimeNow("Y-m-d-H-s");
        $this->load->library('Excel');
        $this->load->model("Mdls/MdlProduk");
        $tokoID = $this->session->login['toko_id'];
        $ex = new Excel();
        $urut = 0;

        $headers_0 = array(
            "id" => array(
                "label" => "pID",
                "type" => "integer",
            ),
            "folder_nama" => array(
                "label" => "kategori",
                "type" => "string",
            ),
            "kode" => array(
                "label" => "kode",
                "type" => "string",
            ),
            "no_part" => array(
                "label" => "nomer part",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "produk",
                "type" => "string",
            ),
            "satuan" => array(
                "label" => "satuan",
                "type" => "string",
            ),
            "warna" => array(
                "label" => "warna",
                "type" => "string",
            ),
            "kemasan" => array(
                "label" => "kemasan",
                "type" => "string",
            ),
        );
        $headers_2 = array(
            "hpp" => array(
                "label" => "hpp",
                "type" => "integer",
            ),
            "jual_1" => array(
                "label" => "jual_1",
                "type" => "integer",
            ),
            "jual_2" => array(
                "label" => "jual_2",
                "type" => "integer",
            ),
            "jual_3" => array(
                "label" => "jual_3",
                "type" => "integer",
            ),
        );
        $headers = $headers_0 + $headers_2;

        $pr = new MdlProduk();
        $pr->addFilter("jenis='item'");
        $pr->addFilter("toko_id='$tokoID'");
        $produkList = $pr->lookupAll()->result();


        foreach ($produkList as $index_0 => $xDetails) {

            $urut++;
            foreach ($headers_0 as $kolom => $header) {
                $code[$kolom] = isset($xDetails->$kolom) ? $xDetails->$kolom : "";
            }
            $code["hpp"] = "";
            $code["jual_1"] = "";
            $code["jual_2"] = "";
            $code["jual_3"] = "";

            $datas[] = (object)$code;
        }

//        arrPrintWebs($datas);
        $ex->setTitleFile("Pricelist $dateNow");
        $ex->setDatas($datas);
        $ex->setHeaders($headers);
        return $ex->writer();
        matiHere(__FILE__ . __LINE__);
    }

    // ----------------------------- stop -----------------------------

    public function index()
    {
        // arrPrint($this->uri->segement_array());
        $ss = $this->uri->segment(3);
        $segment_tambahan = $this->uri->segment(4);

        // formProduk
        $data = array(
            "mode" => "index",
            "title" => "importer data",
            "subTitle" => "",
            "methode" => $ss,
            "segment_tambahan" => isset($segment_tambahan) ? $segment_tambahan : "none",
        );
        $this->load->view("converter", $data);

    }

    public function persiapan_data()
    {
        /* ------------------------------------------------
         * ============= companu profile cek ==============
         * ------------------------------------------------ */
        $this->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());

        $cpSrc = $cp->callDatas();
        $neracaStatus = $cpSrc->neraca_ok;

        $cp_koloms = array(
            "bank_ok" => array(
                "label" => "bank",
                "link" => "Data/add/Bank/bank",
                "link_tipe" => "modal",
            ),
            "bank_account_ok" => array(
                "label" => "bank rekening",
                "link" => "Data/add/BankAccount_in/bank_account",
                "link_tipe" => "modal",
            ),
            "vendor_ok" => array(
                "label" => "vendor",
                "icon" => "fa-truck",
                "link" => "Data/add/Supplier/vendor",
                "link_tipe" => "modal",
            ),
            "produk_ok" => array(
                "label" => "produk",
                "link" => "Converter/index/formProduk"
            ),
            "customer_ok" => array(
                "label" => "konsumen",
                "icon" => "fa-user",
                "link" => "Converter/index/formCustomer",
            ),
            // "stok_ok"         => array(
            //     "label" => "persediaan",
            //     "link"  => "Converter/index/formSuppliesRek",
            // ),

            "stok_ok"         => array(
                "label" => "persediaan",
                "link"  => "Converter/index/formProdukStok",
            ),
            "harga_ok" => array(
                "label" => "price list",
                "link" => "Converter/index/formPricelist",
            ),
            "neraca_ok" => array(
                "label" => "neraca",
                "icon" => "fa-balance-scale",
                // "link" => "Neraca/viewBalanceSheet",
                "link" => "TransaksiPindahBuku/index",
            ),
        );

        $strFree = "";
        $nom = 0;
        $next_on = 1;
        foreach ($cp_koloms as $cp_kolom => $cp_datum) {
            $nom++;
            $ok = $cpSrc->$cp_kolom;
            $badge_done = $ok == 1 ? "badge-green" : "";
            $checked_done = $ok == 1 ? "<div class='text-sm'><i class='fa fa-check-square-o text-green'></i> SUDAH UPLOAD</div>" : "<div class='text-sm'><i class='fa fa-times'></i> BELUM UPLOAD</div>";

            $link_data = isset($cp_datum['link']) ? base_url() . $cp_datum['link'] : "#";

            if ($nom == 1 && $ok == 1) {
                $next_ok = "text-red";
            }
            else {
                $next_ok = "text-red";
            }

            $next_ok = ($nom + $ok) == ($next_on + 1) ? "" : "text-red";
            // if(($nom + $ok) == ($next_on + 1)){
            if ($ok == 0 && (($nom + $ok) == ($next_on + 1))) {
                $text_color = "text-grey";
                $link_data_f = "#";
            }
            else {
                $text_color = "";
                $link_data_f = $link_data;
            }

            $next_on = $nom + $ok;


            // $text_color = $ok == 0 ? "text-grey" : "";

            $cp_label = $cp_datum['label'];
            $cp_icon = isset($cp_datum['icon']) ? $cp_datum['icon'] : 'fa-database';

            if (isset($cp_datum['link_tipe'])) {
                $checked_done = $ok == 1 ? "<div class='text-sm'><i class='fa fa-check-square-o text-green'></i> SUDAH DITAMBAH</div>" : "<div class='text-sm'><i class='fa fa-times'></i> BELUM DITAMBAHKAN</div>";
                $link_modal = modalDialogBtn("Tambah $cp_label", $link_data_f);

                if ($ok == 1) {
                    $strFree .= "<a href='javascript:void(0);' onclick=\"$link_modal\" title='go to $cp_label' data-toggle='tooltip' class='btn btn-app text-uppercase active $next_ok $text_color'>
                        <span class='badge $badge_done'>$nom</span>
                        <i class='fa $cp_icon'></i>$cp_label
                        $checked_done
                        </a>";
                }
                else {
                    $strFree .= "<a href='#' title='go to $cp_label' data-toggle='tooltip' class='btn btn-app text-uppercase active $next_ok $text_color'>
                        <span class='badge $badge_done'>$nom</span>
                        <i class='fa $cp_icon'></i>$cp_label
                        $checked_done
                        </a>";
                }

            }
            else {
                $strFree .= "<a href='$link_data_f' title='go to $cp_label' data-toggle='tooltip' class='btn btn-app text-uppercase active $next_ok $text_color'>
                        <span class='badge $badge_done'>$nom</span>
                        <i class='fa $cp_icon'></i>$cp_label
                        $checked_done
                        </a>";
            }

            // $strFree .= "<a href='$link_data_f' title='go to $cp_label' data-toggle='tooltip' class='btn btn-app text-uppercase active $next_ok $text_color'>
            //             <span class='badge $badge_done'>$nom</span>
            //             <i class='fa $cp_icon'></i>$cp_label
            //             $checked_done
            //             </a>";
            $strFree .= "";
        }

        echo $strFree;
    }

    public function viewTmpNeraca()
    {
        $var = "test";

        echo $var;
    }
}
