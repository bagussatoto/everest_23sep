<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UploaderXls extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Excel');
        // $this->load->library('PHPExcel');
        $this->xlsx = new PHPExcel_Reader_Excel2007();
        //        $this->xlsx = new Excel();

    }

    public function opname__($arrSource = array())
    {
        $cCode = $_GET['cCode'];
        $jenisTr = str_replace("_TR_", "", $cCode);
        matiHere($jenisTr);
        $selector = "MdlProduk2";
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


        $tmpResultEx = array();
        foreach ($datas as $k => $dataSpec) {
            $xl_id = $dataSpec['pid'];
            $xl_qty = $dataSpec['stok riil'];

            $tmpResultEx[$xl_id] = $xl_qty;

        }
        $arrResultEx = array_filter($tmpResultEx);
        $url = base_url();
        //            "Selectors/_processSelectProduct/selectNoQty/1119?id=34&minValue=0&newQty=";
        arrPRint($url);
        if (sizeof($arrResultEx) > 0) {
            foreach ($arrResultEx as $pID => $stok_riil) {
                //                base_url()."Selectors/_processSelectProduct/selectNoQty/1119?id=$pID&minValue=0&newQty=$stok_riil";
            }
        }
        arrPrint($arrResultEx);
        matiHEre();

        cekMerah("-- DONE - SUDAH COMMIT--");
    }

    public function opname()
    {
        $this->load->library('PHPExcel');
        //        $this->load->model("Mdls/MdlProduk");
        //        $pr = new MdlProduk();
        //
        //        $produkKoloms = array(
        //            "kode_brg"   => "kode",
        //            "nama_brg"   => "nama",
        //            "merk"       => "merek_nama",
        //            "no_part"    => "no_part",
        //            "satuan"     => "satuan",
        //            "keterangan" => "keterangan",
        //            "qtylimit"   => "limit",
        //            "kode_klp"   => "folders_kode",
        //        );
        $this->xlsx = new PHPExcel_Reader_Excel2007();
        $url = base_url();
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
        $koloms = array_filter($headers[$data_header]);
        // arrPrint($koloms);
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
                if($rows['pid']!=''){
                    $keivalues['id'] = $rows['pid'];
                    $keivalues['qty'] = $rows['stok riil'];
                    $datas[] = $keivalues;
                }
            }
            $numrow++;
        }
        // arrPrintPink($datas);
        // matiHere(__LINE__);
        $valuesInject = json_encode($datas);
        echo "<script>localStorage.setItem('items','$valuesInject')</script>";

        //        echo "<script>
        //var arrProduk = JSON.parse(localStorage.getItem('items'))
        //function insertItem(ls_urut, ls_concated){
        //
        //    var dTemp = JSON.parse(ls_concated);
        //    var data = dTemp[ls_urut];
        //    top.$('#result').load('$url/Selectors/_processSelectProduct/selectNoQty/1119?id='+data.id+'&minValue='+data.qty, null, function(){
        //        if(data.qty>1){
        //            changeUnit(ls_urut, ls_concated);
        //        }
        //        else{
        //            rolling(ls_urut, ls_concated);
        //        }
        //    })
        //}
        //
        //function changeUnit(ls_urut, ls_concated){
        //    var dTemp = JSON.parse(ls_concated);
        //    var data = dTemp[ls_urut];
        //    top.$('#result').load('$url/Selectors/_processSelectProduct/selectNoQty/1119?id='+data.id+'&newQty='+data.qty+'&jml='+data.qty, null, function(){
        //        rolling(ls_urut, ls_concated);
        //    })
        //}
        //
        //function rolling(ls_urut, ls_concated){
        //    var dTemp = JSON.parse(ls_concated);
        //    var data = dTemp[ls_urut];
        //    var rl_ls_urut = (ls_urut-1)
        //
        //    if(rl_ls_urut>=0){
        //        setTimeout( function(){ insertItem(rl_ls_urut, ls_concated) }, 500);
        //    }
        //    else{
        //
        //    }
        //}
        //
        //var urut = 1;
        //var concated = [];
        //var arrays = [];
        //
        //top.jQuery.each(arrProduk, function(id,data){
        //    arrays = data
        //    arrays['id'] = id
        //    concated[urut] = arrays
        //    urut++
        //});
        //
        //localStorage.setItem('urut', '');
        //localStorage.setItem('concat', '');
        //localStorage.setItem('urut', (urut-2));
        //localStorage.setItem('concat', JSON.stringify(concated).replace('null,', '') );
        //
        //var ls_urut = localStorage.getItem('urut');
        //var ls_concated = localStorage.getItem('concat');
        //</script>";
        //        arrPrint($valuesInject);
        //        matiHere(__LINE__ . " done");


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
                    "id"          => $k,
                    "produk_id"   => $dataSpec['p_id'],
                    "produk_nama" => $dataSpec['produk_nama'],
                    "unit_af"     => $dataSpec['qty'],
                    "nilai_af"    => $dataSpec['value'],
                    "rekening"    => "persediaan supplies",
                    "cabang_id"   => $cabang_id,
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
                            "extern_id"   => $tpSpec->$pSpec["externId"],
                            "extern_nama" => $tpSpec->$pSpec["externNama"],
                            "cabang_id"   => $cabang_id,
                            "fulldate"    => $fulldateNow,
                            "dtime"       => $dtimeNow,

                            "qty"          => $tpSpec->unit_af,
                            "extern2_id"   => 0,
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
                "comName"     => "RekeningPembantuSupplies",
                "comFifo"     => "FifoSupplies",
                "comFifoAvg"  => "FifoAverage",
                "comLocker"   => "LockerStockSupplies",

                // "tabel" => "rek_cache_pembantu_supplies",
                "externId"    => "produk_id",
                "externNama"  => "produk_nama",
                "jenis"       => "supplies",
                "lockerState" => "active",
                "gudangId"    => $gudang_id,
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
                        "extern_id"    => $tpSpec->$pSpec["externId"],
                        "extern_nama"  => $tpSpec->$pSpec["externNama"],
                        "fulldate"     => $fulldateNow,
                        "dtime"        => $dtimeNow,
                        "produk_qty"   => $tpSpec->unit_af,
                        "produk_nilai" => $nilai_item,
                        "cabang_id"    => $cabang_id,
                        "gudang_id"    => $gudang_id,
                    );
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["loop"] = $loop;
                    $arrRekPembantuItems[$pSpec["comName"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="fifo fisik">
                    $loop = array();
                    $static = array(
                        "produk_id"   => $tpSpec->$pSpec["externId"],
                        "produk_nama" => $tpSpec->$pSpec["externNama"],
                        "fulldate"    => $fulldateNow,
                        "dtime"       => $dtimeNow,
                        "unit"        => $tpSpec->unit_af,
                        "jml_nilai"   => $tpSpec->nilai_af,
                        "hpp"         => $nilai_item,
                        "cabang_id"   => $cabang_id,
                        "gudang_id"   => $gudang_id,
                    );
                    $arrFifoItems[$pSpec["comFifo"]][$no]["loop"] = $loop;
                    $arrFifoItems[$pSpec["comFifo"]][$no]["static"] = $static;
                    //</editor-fold>

                    //<editor-fold desc="fifo average">
                    $loop = array();
                    $static = array(
                        "produk_id" => $tpSpec->$pSpec["externId"],
                        "nama"      => $tpSpec->$pSpec["externNama"],
                        "jml"       => $tpSpec->unit_af,
                        "jml_nilai" => $tpSpec->nilai_af,
                        "hpp"       => $nilai_item,
                        "jenis"     => $pSpec["jenis"],
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
                        "nama"      => $tpSpec->$pSpec["externNama"],
                        "jumlah"    => $tpSpec->unit_af,
                        "jenis"     => $pSpec["jenis"],
                        "state"     => $pSpec["lockerState"],
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
                            "extern_id"    => $tpSpec->$pSpec["externId"],
                            "extern_nama"  => $tpSpec->$pSpec["externNama"],
                            "fulldate"     => $fulldateNow,
                            "dtime"        => $dtimeNow,
                            "produk_qty"   => $tpSpec->unit_af,
                            "produk_nilai" => $nilai_item,
                            "cabang_id"    => $cabang_id,
                            "gudang_id"    => $gudang_id,
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
            "r/l lain lain"          => "rugilaba lain lain",
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
            "id"           => 4,
            "rekening"     => "persediaan supplies",
            "periode"      => "forever",
            "debet_saldo"  => 0,
            "kredit_saldo" => 0,
            "after_saldo"  => $persediaan_total,
            "keterangan"   => "stok produk awal",
            //            "tgl" => 16,
            //            "bln" => 1,
            //            "thn" => 2019,
            "dtime"        => $dtimeNow,
            "cabang_id"    => $cabang_id,
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


}
