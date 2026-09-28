<?php


class ToolSalesOrder extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
    }

    function index()
    {

    }


    //region non akunting penjualan
    public function nonAkuntingAllSales()
    {
        header("refresh:1");


        //region load model component
        $this->load->model("Coms/ComRekeningTransaksiSales");
        $this->load->model("Coms/ComRekeningTransaksiSalesPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanTransaksiPembantu");

        $this->load->model("Coms/ComRekeningTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");


        $this->load->model("MdlTransaksi");
        //endregion

        $tahun = "2022";
        $jenisMaster = "582";
        $divID = "18";
        $arrJenis = array(
            "582spo",
            "582so",
//            "582pkd",
            "582spd"
        );

        //---------------
//        $tr = new MdlTransaksi();
//        $tr->addFilter("div_id='$divID'");
//        $tr->addFilter("jenis_master='$jenisMaster'");
//        $tr->addFilter("jenis='582spo'");
////        $tr->addFilter("oleh_id='65'");
//        $tr->addFilter("non_akunting='0'");
//        $tr->addFilter("year(dtime)>='$tahun'");
//        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
//        $this->db->limit(1);
//        $tmpHist = $tr->lookupAll()->result();
//        $master_iddd = $tmpHist[0]->id;
        //---------------


        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
//        $tr->addFilter("jenis_master='$jenisMaster'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
        $tr->addFilter("id_master='$master_iddd'");
//        $tr->addFilter("id_master>0");
//        $tr->addFilter("non_akunting_2='0'");
//        $tr->addFilter("non_akunting=0");
        $tr->addFilter("year(dtime)>='$tahun'");
        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));
//        cekHitam("trID: " . ($tmpHist[0]->id));
//        arrPrintPink($tmpHist);
//        mati_disini();
//

        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }

            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            cekHitam(":: trID: $trID, masterID: $masterID, jenis: $jenisTransaksi, masterJenis: $masterJenis ::");
//            mati_disini(__LINE__);
            //----------------------------
            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //----------------------------

            // registry main dan items
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");
            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $main_reg = blobDecode($regSpec->main);
                $items_reg = blobDecode($regSpec->items);
                if (!is_array($main_reg)) {
                    cekHere("main bukan array");
                    $main_reg = blobDecode($main_reg);
                }
                if (!is_array($items_reg)) {
                    cekHere("items bukan array");
                    $items_reg = blobDecode($items_reg);
                }
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => $main_reg,
                    "items" => $items_reg,
                );
            }

            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }
            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",
            );
            $items = array();
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
                }
            }

            switch ($jenisTransaksi) {
                case "582spo":
                    $sourceMain = array(
                        "loop" => array(
                            "582spo" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer_top",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "transaksi_id" => "id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "582spo" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "582so" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer_top",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "transaksi_id" => "id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "582so" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;
                case "582so":
                    $sourceMain = array(
                        "loop" => array(
                            "582so" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "582so" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "582spd" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "582spd" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;
//                case "582pkd":
//                    $sourceMain = array(
//                        "loop" => array(
//                            "582pkd" => "m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "harga_nppn" => "m_nett2",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                        ),
//                    );
//                    $sourceDetail = array(
//                        "loop" => array(
//                            "582pkd" => "sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "harga_nppn" => "nett2",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".reguler",
//                        ),
//                    );
//                    break;
                case "582spd":
                    $sourceMain = array(
                        "loop" => array(
                            "582spd" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "582spd" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "582spo" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "582spo" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                            "update_lap" => false,
                        ),
                    );
                    break;


                default:

                    mati_disini(":: $jenisTransaksi ::");

                    break;
            }
            //------------------------------------

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            $gateSourceKonsolidasian = array();
            $gateSourceSales = array();
            $gateSourceCabang = array();
            $gateSourceCabangSales = array();

            //------------------------------------
            foreach ($sourceMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateSource['loop'][$key] = $newVal_result;
                }
            }
            foreach ($sourceMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateSource['static']['transaksi_id'] = $mainData['id'];
            $gateSource['static']['fulldate'] = $mainData['fulldate'];
            $gateSource['static']['dtime'] = $mainData['dtime'];
            //------------------------------------
            foreach ($targetMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateTarget['loop'][$key] = $newVal_result;
                }
            }
            foreach ($targetMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateTarget['static']['transaksi_id'] = $mainData['id'];
            $gateTarget['static']['fulldate'] = $mainData['fulldate'];
            $gateTarget['static']['dtime'] = $mainData['dtime'];
            //------------------------------------
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            foreach ($items as $ii => $iiSpec) {
                foreach ($sourceDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];

                foreach ($targetDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];

            }
            //------------------------------------

            $gateSourceKonsolidasian = $gateSource;
            $gateSourceDetailKonsolidasian = $gateSourceDetail;

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian

//            $arrResetKonsolidasian = array(
//                "cabang_id",
//                "cabang_nama",
//                "gudang_id",
//                "gudang_nama",
//                "seller_id",
//                "seller_nama",
//                "extern_id",
//                "extern_nama",
//            );
//            foreach ($arrResetKonsolidasian as $reset){
//                // master konsolidasian
//                $gateSourceKonsolidasian["static"][$reset] = 0;
//                // detail konsolidasian
//                foreach ($gateSourceDetailKonsolidasian as $pid => $iSpec){
//
//                }
//            }
//            arrPrintPink($gateSourceDetailKonsolidasian);
//
//mati_disini(__LINE__);
                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region sales cabang
                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman transaksi
                $rt = New ComRekeningTransaksiSalesSalesmanTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region MASTER
                $rt = New ComRekeningTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();
                //endregion

                //region DETAIL
                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


            $tr = new MdlTransaksi();
            $updateData = array(
                "non_akunting_2" => "1",
//                "non_akunting" => "1",
            );
            $updateWhere = array(
                "id" => $trID
            );
            $tr->updateData($updateWhere, $updateData);
            showLast_query("orange");

        }
        else {
            cekMerah("-- HABIS --");

            $tr = new MdlTransaksi();
            $updateData = array(
//                "non_akunting_2" => "1",
                "non_akunting" => "1",
            );
            $updateWhere = array(
                "id" => $master_iddd
            );
            $tr->updateData($updateWhere, $updateData);
            showLast_query("orange");

        }


        mati_disini(" OHOOOO belon comit @" . __LINE__);

        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function nonAkuntingAllSalesReject()
    {
        header("refresh:1");

        $this->load->model("Coms/ComRekeningTransaksiSales");
        $this->load->model("Coms/ComRekeningTransaksiSalesPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesmanPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanTransaksiPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        //

        $this->load->model("MdlTransaksi");

        $tahun = "2022";
        $jenisMaster = "582";
        $divID = "18";
        $arrJenis = array("582spo", "582so");

        //---------------
        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
        $tr->addFilter("jenis_master='$jenisMaster'");
        $tr->addFilter("jenis='582spo'");
//        $tr->addFilter("oleh_id='65'");
        $tr->addFilter("non_akunting='0'");
        $tr->addFilter("year(dtime)>='$tahun'");
        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        $master_iddd = $tmpHist[0]->id;

//        $trIDs = array();
//        foreach($tmpHist as $tmpHistSpec){
//            $trIDs[] = $tmpHistSpec->id;
//        }
//        arrPrintHijau($trIDs);
//        mati_disini(__LINE__ . " || $master_iddd");
        //---------------

        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
        $tr->addFilter("jenis_master='$jenisMaster'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
        $tr->addFilter("id_master='$master_iddd'");
//        $tr->addFilter("id_master>0");
        $tr->addFilter("non_akunting_reject=0");
        $tr->addFilter("trash_4=1");
        $tr->addFilter("year(dtime)>='$tahun'");
        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));


        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }
            cekHitam(":: " . $tmpHist[0]->id);
            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            //----------------------------
            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //----------------------------

            // registry main dan items
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");
            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $main_reg = blobDecode($regSpec->main);
                $items_reg = blobDecode($regSpec->items);
                if (!is_array($main_reg)) {
                    cekHere("main bukan array");
                    $main_reg = blobDecode($main_reg);
                }
                if (!is_array($items_reg)) {
                    cekHere("items bukan array");
                    $items_reg = blobDecode($items_reg);
                }
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => $main_reg,
                    "items" => $items_reg,
                );
            }
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }
            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",
                "cancel_dtime" => "cancel_dtime",
                "cancel_name" => "cancel_name",
                "cancel_id" => "cancel_id",
            );
            $items = array();
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
                }
            }

            switch ($jenisTransaksi) {
                case "582spo":
                    $sourceMain = array(
                        "loop" => array(
                            "582so" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "transaksi_id" => "id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".1",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "582spo" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".1",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "582so" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".1",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "582spo" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".1",
                        ),
                    );
                    break;
                case "582so":
                    $sourceMain = array(
                        "loop" => array(
                            "582spd" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".2",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "582so" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".2",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "582spd" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",

                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".2",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "582so" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".rejected",
                            "step_reject" => ".2",
                        ),
                    );
                    break;
//                case "582pkd":
//                    $sourceMain = array(
//                        "loop" => array(
//                            "582spd" => "m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                            "transaksi_tipe" => ".rejected",
//                            "step_reject" => ".3",
//                        ),
//                    );
//                    $targetMain = array(
//                        "loop" => array(
//                            "582pkd" => "-m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                            "transaksi_tipe" => ".rejected",
//                            "step_reject" => ".3",
//                        ),
//                    );
//                    $sourceDetail = array(
//                        "loop" => array(
//                            "582spd" => "sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".rejected",
//                            "step_reject" => ".3",
//                        ),
//                    );
//                    $targetDetail = array(
//                        "loop" => array(
//                            "582pkd" => "-sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".rejected",
//                            "step_reject" => ".3",
//                        ),
//                    );
//                    break;

                default:

                    mati_disini(":: $jenisTransaksi ::");

                    break;
            }
            //------------------------------------

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            foreach ($sourceMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateSource['loop'][$key] = $newVal_result;
                }
            }
            foreach ($sourceMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateSource['static']['transaksi_id'] = $mainData['id'];
            $gateSource['static']['fulldate'] = $mainData['fulldate'];
            $gateSource['static']['dtime'] = $mainData['dtime'];
            $gateSource['static']['dtime_reject'] = $mainData['cancel_dtime'];
            $gateSource['static']['oleh_id_reject'] = $mainData['cancel_name'];
            $gateSource['static']['oleh_nama_reject'] = $mainData['cancel_id'];

            foreach ($targetMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateTarget['loop'][$key] = $newVal_result;
                }
            }
            foreach ($targetMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateTarget['static']['transaksi_id'] = $mainData['id'];
            $gateTarget['static']['fulldate'] = $mainData['fulldate'];
            $gateTarget['static']['dtime'] = $mainData['dtime'];
            $gateTarget['static']['dtime_reject'] = $mainData['cancel_dtime'];
            $gateTarget['static']['oleh_id_reject'] = $mainData['cancel_name'];
            $gateTarget['static']['oleh_nama_reject'] = $mainData['cancel_id'];
            //------------------------------------
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            foreach ($items as $ii => $iiSpec) {
                foreach ($sourceDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                $gateSourceDetail[$ii]['static']['dtime_reject'] = $mainData['cancel_dtime'];
                $gateSourceDetail[$ii]['static']['oleh_id_reject'] = $mainData['cancel_name'];
                $gateSourceDetail[$ii]['static']['oleh_nama_reject'] = $mainData['cancel_id'];

                foreach ($targetDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                $gateTargetDetail[$ii]['static']['dtime_reject'] = $mainData['cancel_dtime'];
                $gateTargetDetail[$ii]['static']['oleh_id_reject'] = $mainData['cancel_name'];
                $gateTargetDetail[$ii]['static']['oleh_nama_reject'] = $mainData['cancel_id'];
            }
            //------------------------------------

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian

//            $arrResetKonsolidasian = array(
//                "cabang_id",
//                "cabang_nama",
//                "gudang_id",
//                "gudang_nama",
//                "seller_id",
//                "seller_nama",
//                "extern_id",
//                "extern_nama",
//            );
//            foreach ($arrResetKonsolidasian as $reset){
//                // master konsolidasian
//                $gateSourceKonsolidasian["static"][$reset] = 0;
//                // detail konsolidasian
//                foreach ($gateSourceDetailKonsolidasian as $pid => $iSpec){
//
//                }
//            }
//            arrPrintPink($gateSourceDetailKonsolidasian);
//
//mati_disini(__LINE__);
                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region sales cabang
                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                $rt = New ComRekeningTransaksiSalesSalesmanTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region MASTER
                $rt = New ComRekeningTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();
                //endregion

                //region DETAIL
                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


            $tr = new MdlTransaksi();
            $updateData = array(
                "non_akunting_reject" => "1",
            );
            $updateWhere = array(
                "id" => $trID
            );
            $tr->updateData($updateWhere, $updateData);
            showLast_query("orange");

        }
        else {
            cekMerah("-- HABIS --");

            $tr = new MdlTransaksi();
            $updateData = array(
//                "non_akunting_2" => "1",
                "non_akunting" => "1",
            );
            $updateWhere = array(
                "id" => $master_iddd
            );
            $tr->updateData($updateWhere, $updateData);
            showLast_query("orange");

        }


//        mati_disini(" OHOOOO ");

        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function nonAkuntingAllSalesExport()
    {
        header("refresh:2");

        $this->load->model("Coms/ComRekeningTransaksiSales");
        $this->load->model("Coms/ComRekeningTransaksiSalesPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesmanPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        //

        $this->load->model("MdlTransaksi");

        $tahun = "2022";
        $jenisMaster = "382";
        $divID = "18";
        $arrJenis = array("382spo", "382so", "382spd");

        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
        $tr->addFilter("jenis_master='$jenisMaster'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
//        $tr->addFilter("id_master=131884");
        $tr->addFilter("id_master>0");
        $tr->addFilter("non_akunting=0");
        $tr->addFilter("year(dtime)>='$tahun'");
        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));
//        arrPrintPink($tmpHist);
//mati_disini();


        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }
            cekHitam(":: " . $tmpHist[0]->id);
            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            //----------------------------
            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //----------------------------

            // registry main dan items
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");
            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $main_reg = blobDecode($regSpec->main);
                $items_reg = blobDecode($regSpec->items);
                if (!is_array($main_reg)) {
                    cekHere("main bukan array");
                    $main_reg = blobDecode($main_reg);
                }
                if (!is_array($items_reg)) {
                    cekHere("items bukan array");
                    $items_reg = blobDecode($items_reg);
                }
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => $main_reg,
                    "items" => $items_reg,
                );
            }
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }
            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",
            );
            $items = array();
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
                }
            }

            switch ($jenisTransaksi) {
                case "382spo":
                    $sourceMain = array(
                        "loop" => array(
                            "382spo" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "transaksi_id" => "id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "382so" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "382spo" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "382so" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;
                case "382so":
                    $sourceMain = array(
                        "loop" => array(
                            "382so" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "382spd" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "382so" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "382spd" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;
                case "382spd":
                    $sourceMain = array(
                        "loop" => array(
                            "382spd" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "382spo" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "382spd" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "382spo" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                            "update_lap" => false,
                        ),
                    );
                    break;
                default:

                    mati_disini(":: $jenisTransaksi ::");

                    break;
            }
            //------------------------------------
//            arrPrint($trDatas);
//            arrPrint($items);

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            foreach ($sourceMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateSource['loop'][$key] = $newVal_result;
                }
            }
            foreach ($sourceMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateSource['static']['transaksi_id'] = $mainData['id'];
            $gateSource['static']['fulldate'] = $mainData['fulldate'];
            $gateSource['static']['dtime'] = $mainData['dtime'];

            foreach ($targetMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateTarget['loop'][$key] = $newVal_result;
                }
            }
            foreach ($targetMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateTarget['static']['transaksi_id'] = $mainData['id'];
            $gateTarget['static']['fulldate'] = $mainData['fulldate'];
            $gateTarget['static']['dtime'] = $mainData['dtime'];
            //------------------------------------
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            foreach ($items as $ii => $iiSpec) {
                foreach ($sourceDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];

                foreach ($targetDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];
            }
            //------------------------------------

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian

//            $arrResetKonsolidasian = array(
//                "cabang_id",
//                "cabang_nama",
//                "gudang_id",
//                "gudang_nama",
//                "seller_id",
//                "seller_nama",
//                "extern_id",
//                "extern_nama",
//            );
//            foreach ($arrResetKonsolidasian as $reset){
//                // master konsolidasian
//                $gateSourceKonsolidasian["static"][$reset] = 0;
//                // detail konsolidasian
//                foreach ($gateSourceDetailKonsolidasian as $pid => $iSpec){
//
//                }
//            }
//            arrPrintPink($gateSourceDetailKonsolidasian);
//
//mati_disini(__LINE__);
                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region sales cabang
                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region salesman cabang
                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region MASTER
                $rt = New ComRekeningTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();
                //endregion

                //region DETAIL
                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


            $tr = new MdlTransaksi();
            $updateData = array(
                "non_akunting" => "1",
            );
            $updateWhere = array(
                "id" => $trID
            );
            $tr->updateData($updateWhere, $updateData);
            showLast_query("orange");

        }


        mati_disini(" OHOOOO belom comit @" . __LINE__);

        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function nonAkuntingAllSalesProject()
    {
        header("refresh:2");

        $this->load->model("Coms/ComRekeningTransaksiSales");
        $this->load->model("Coms/ComRekeningTransaksiSalesPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesmanPembantu");
        //
        $this->load->model("Coms/ComRekeningTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        //

        $this->load->model("MdlTransaksi");

        $tahun = "2022";
        $jenisMaster = "588";
        $divID = "18";
        $arrJenis = array(
            "588spo",
            "588so",
            "7499",
        );

        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
//        $tr->addFilter("jenis_master='$jenisMaster'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
//        $tr->addFilter("id_master=131884");
        $tr->addFilter("id_master>0");
        $tr->addFilter("non_akunting=0");
//        $tr->addFilter("year(dtime)>='$tahun'");
        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));
//        arrPrintPink($tmpHist);
//mati_disini();


        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }
            cekHitam(":: " . $tmpHist[0]->id);
            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            //----------------------------
            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //----------------------------


            //----------------------------
            // registry main dan items
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");

            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => blobDecode($regSpec->main),
                    "items" => blobDecode($regSpec->items),
                );
            }
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }


            if ($jenisTransaksi == "7499") {
                //-----
                $referenceID_approve = $trDatas["m_refID"];
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id='$referenceID_approve'");
                $trTmp = $tr->lookupAll()->result();
                $trDatas["m_masterTopID"] = $trTmp[0]->id_master;
                cekHere(__LINE__);
                showLast_query("kuning");
                cekHere($trDatas["m_masterTopID"]);
                //-----
            }


            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",
                "m_masterTopID" => "m_masterTopID",
            );
            $items = array();
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
                }
            }

            switch ($jenisTransaksi) {
                case "588spo":
                    $sourceMain = array(
                        "loop" => array(
                            "588spo" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "transaksi_id" => "id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "588so" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "588spo" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "588so" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;
                case "588so":
                    $sourceMain = array(
                        "loop" => array(
                            "588so" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "7499" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "588so" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "7499" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;
                case "7499":
                    $sourceMain = array(
                        "loop" => array(
                            "7499" => "m_penjualan",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_penjualan",
                            "harga_bruto" => "m_penjualan",
                            "ppn_nilai" => "m_grand_ppn",
                            "harga_netto" => "m_penjualan",
                            "harga_nppn" => "m_piutang_dagang",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "m_masterTopID",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "749" => "-m_penjualan",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => "m_penjualan",
                            "harga_bruto" => "m_penjualan",
                            "ppn_nilai" => "m_grand_ppn",
                            "harga_netto" => "m_penjualan",
                            "harga_nppn" => "m_piutang_dagang",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "m_masterTopID",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "7499" => "nilai_bayar",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "nilai_bayar",
                            "harga_bruto" => "nilai_bayar",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nilai_bayar",
                            "harga_nppn" => "nilai_bayar",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "project_id",
                            "extern_nama" => "project_nama",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "m_masterTopID",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "749" => "-nilai_bayar",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "nilai_bayar",
                            "harga_bruto" => "nilai_bayar",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nilai_bayar",
                            "harga_nppn" => "nilai_bayar",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "project_id",
                            "extern_nama" => "project_nama",
                            "extern2_id" => "id_master",
                            "extern2_nama" => ".0",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "m_masterTopID",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".reguler",
                        ),
                    );
                    break;

//                case "582pkd":
//                    $sourceMain = array(
//                        "loop" => array(
//                            "582pkd" => "m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "harga_nppn" => "m_nett2",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                        ),
//                    );
//                    $targetMain = array(
//                        "loop" => array(
//                            "582spd" => "-m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "harga_nppn" => "m_nett2",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                        ),
//                    );
//                    $sourceDetail = array(
//                        "loop" => array(
//                            "582pkd" => "sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "harga_nppn" => "nett2",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".reguler",
//                        ),
//                    );
//                    $targetDetail = array(
//                        "loop" => array(
//                            "582spd" => "-sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "harga_nppn" => "nett2",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".reguler",
//                        ),
//                    );
//                    break;

//                case "582spd":
//                    $sourceMain = array(
//                        "loop" => array(
//                            "582spd" => "m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "harga_nppn" => "m_nett2",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                        ),
//                    );
//                    $targetMain = array(
//                        "loop" => array(
//                            "582spo" => "-m_nett1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "m_placeID",
//                            "cabang_nama" => "m_placeName",
//                            "gudang_id" => "m_gudangID",
//                            "gudang_nama" => "m_gudangName",
//                            "rekening_nama" => "m_sourceJenisLabel",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "m_harga",
//                            "harga_bruto" => "m_harga",
//                            "ppn_nilai" => "m_ppn",
//                            "harga_netto" => "m_nett1",
//                            "harga_nppn" => "m_nett2",
//                            "diskon_nilai" => "m_disc",
//                            "premi_nilai" => "m_premi",
//                            "ongkir_nilai" => "m_shipping_service",
//                            "extern_id" => "id_master",
//                            "extern_nama" => "nomer",
//                            "satuan" => "satuan",
////                            "oleh_id" => "m_olehID",
////                            "oleh_nama" => "m_olehName",
//                            "seller_id" => "m_sellerID",
//                            "seller_nama" => "m_sellerName",
//                            "customer_id" => "m_pihakID",
//                            "customer_nama" => "m_pihakName",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            "jenis" => "jenis",
//                            "step_current" => "step_number",
//                            "step_number" => "step_current",
//                            "next_step_num" => "next_step_num",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "rel_target_jenis" => ".0",
//                        ),
//                    );
//                    $sourceDetail = array(
//                        "loop" => array(
//                            "582spd" => "sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "harga_nppn" => "nett2",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".reguler",
//                        ),
//                    );
//                    $targetDetail = array(
//                        "loop" => array(
//                            "582spo" => "-sub_nett1",
//                        ),
//                        "static" => array(
//                            "rekening_nama" => "sourceJenisLabel",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => "harga",
//                            "harga_bruto" => "harga",
//                            "ppn_nilai" => "ppn",
//                            "harga_netto" => "nett1",
//                            "harga_nppn" => "nett2",
//                            "diskon_nilai" => "disc",
//                            "premi_nilai" => "premi",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "id_master",
//                            "extern2_nama" => ".0",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
//                            "seller_id" => "sellerID",
//                            "seller_nama" => "sellerName",
//                            "customer_id" => "pihakID",
//                            "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                            "master_id" => "id_master",
//                            "master_jenis" => "jenis_master",
//                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                            //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                            "_step_1_nomer" => "_step_1_nomer",
//                            "_step_1_olehName" => "_step_1_olehName",
//                            "_step_2_nomer" => "_step_2_nomer",
//                            "_step_2_olehName" => "_step_2_olehName",
//                            "_step_3_nomer" => "_step_3_nomer",
//                            "_step_3_olehName" => "_step_3_olehName",
//                            "_step_4_nomer" => "_step_4_nomer",
//                            "_step_4_olehName" => "_step_4_olehName",
//                            "_step_5_nomer" => "_step_5_nomer",
//                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".reguler",
//                            "update_lap" => false,
//                        ),
//                    );
//                    break;

                default:

                    mati_disini(":: $jenisTransaksi :: belum didefine source dan target model COM-nya");

                    break;
            }
            //------------------------------------
//            arrPrint($trDatas);
//            arrPrint($items);

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            foreach ($sourceMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateSource['loop'][$key] = $newVal_result;
                }
            }
            foreach ($sourceMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateSource['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateSource['static']['transaksi_id'] = $mainData['id'];
            $gateSource['static']['fulldate'] = $mainData['fulldate'];
            $gateSource['static']['dtime'] = $mainData['dtime'];

            foreach ($targetMain['loop'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['loop'][$key] = "-" . $newVal_result;
                }
                else {
                    $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                    $gateTarget['loop'][$key] = $newVal_result;
                }
            }
            foreach ($targetMain['static'] as $key => $val) {
                if (substr($val, 0, 1) == "-") {
                    $newVal = str_replace("-", "", $val);
                    $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                    $gateTarget['static'][$key] = "-" . $newVal_result;
                }
                else {
                    if (substr($val, 0, 1) == ".") {
                        $newVal = str_replace(".", "", $val);
                        $newVal_result = $newVal;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['static'][$key] = $newVal_result;
                    }
                }
            }
            $gateTarget['static']['transaksi_id'] = $mainData['id'];
            $gateTarget['static']['fulldate'] = $mainData['fulldate'];
            $gateTarget['static']['dtime'] = $mainData['dtime'];
            //------------------------------------
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            foreach ($items as $ii => $iiSpec) {
                foreach ($sourceDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];

                foreach ($targetDetail['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                        $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetDetail['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                        $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];
            }
            //------------------------------------

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian

//            $arrResetKonsolidasian = array(
//                "cabang_id",
//                "cabang_nama",
//                "gudang_id",
//                "gudang_nama",
//                "seller_id",
//                "seller_nama",
//                "extern_id",
//                "extern_nama",
//            );
//            foreach ($arrResetKonsolidasian as $reset){
//                // master konsolidasian
//                $gateSourceKonsolidasian["static"][$reset] = 0;
//                // detail konsolidasian
//                foreach ($gateSourceDetailKonsolidasian as $pid => $iSpec){
//
//                }
//            }
//            arrPrintPink($gateSourceDetailKonsolidasian);
//
//mati_disini(__LINE__);
                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSales();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region sales cabang
                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabang();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region salesman cabang
                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesman();
                $rt->pair($gateTarget);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region MASTER
                $rt = New ComRekeningTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();
                //endregion

                //region DETAIL
                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


            $tr = new MdlTransaksi();
            $updateData = array(
                "non_akunting" => "1",
            );
            $updateWhere = array(
                "id" => $trID
            );
            $tr->updateData($updateWhere, $updateData);
            showLast_query("orange");

        }

//        if($jenisTransaksi == "7499"){
//            mati_disini(" OHOOOO belon comit @" . __LINE__);
//        }
//        mati_disini(" OHOOOO belon comit @" . __LINE__);

        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }


    // menjalankan non akunting, setelah cekSo
    public function nonAkuntingAllSalesNew()
    {
        header("refresh:2");
//        $stopCommit = true;
        $stopCommit = false;


        $starttime = microtime(true);

        //region load model component
        $this->load->model("Coms/ComRekeningTransaksiSales");
        $this->load->model("Coms/ComRekeningTransaksiSalesPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesCabangSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiSalesSalesmanTransaksiPembantu");

        $this->load->model("Coms/ComRekeningTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiPembantu");


        $this->load->model("MdlTransaksi");
        //endregion

        $tahun = "2022";
        $jenisMaster = "582";
        $divID = "18";
        $arrJenis = array(
            "582spo",
            "582so",
//            "582pkd",
            "582spd",
            //---
            "382spo",
            "382so",
//            "382pkd",
            "382spd",
            //---
            "1982g",
            "588spo",
            "588so",
            "7499",
        );
        //--------------------
        $tbl = "z_tabel_timeline";
        $this->db->select("id, transaksi_id, jenis, dtime, tipe");
        $this->db->order_by('dtime', 'ASC');
        $arrayWhere = array(
            "status" => "0",
//            "seller_id" => "182",
        );
        $this->db->where($arrayWhere);
        $this->db->where_in("jenis", $arrJenis);
        $this->db->limit(1);
        $result = $this->db->get($tbl)->result();
        showLast_query("kuning");
        arrPrintKuning($result);
        if (sizeof($result) > 0) {
            $tblID = $result[0]->id;
            $transaksiID = $result[0]->transaksi_id;
            $transaksiTipeTimeline = $result[0]->tipe;
        }
        else {
            mati_disini("--- HABIS ---");
        }
        //--------------------


        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
//        $tr->addFilter("jenis_master='$jenisMaster'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
        $tr->addFilter("id='$transaksiID'");
//        $tr->addFilter("id_master='$master_iddd'");
//        $tr->addFilter("id_master>0");
//        $tr->addFilter("non_akunting_2='0'");
//        $tr->addFilter("non_akunting=0");
//        $tr->addFilter("year(dtime)>='$tahun'");
//        $tr->setSortBy(array("kolom" => "id", "mode" => "ASC"));
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));
//        cekHitam("trID: " . ($tmpHist[0]->id));
//        arrPrintPink($tmpHist);
//        mati_disini();
//

        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            //region data transaksi
            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }

            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            cekHitam(":: trID: $trID, masterID: $masterID, jenis: $jenisTransaksi, masterJenis: $masterJenis ::");
//            mati_disini(__LINE__);
            $cancel_dtime = $tmpHist[0]->cancel_dtime;
            $cancel_dtime_ex = explode(" ", $tmpHist[0]->cancel_dtime);
            $cancel_fulldate = $cancel_dtime_ex[0];


            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //endregion


            //region data seller id dan nama dari id_master
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$masterID'");
            $trTmpp = $tr->lookupAll()->result();
            $sellerData = array(
                "seller_id" => $trTmpp[0]->oleh_id,
                "seller_name" => $trTmpp[0]->oleh_nama,
                "seller_nama" => $trTmpp[0]->oleh_nama,
                "sellerID" => $trTmpp[0]->oleh_id,
                "sellerName" => $trTmpp[0]->oleh_nama,
                "sellerNama" => $trTmpp[0]->oleh_nama,
            );
            foreach ($sellerData as $kdata => $vdata) {
                $trDatas[$kdata] = $vdata;
            }
            //endregion


            //region transaksi data registry
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");
            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $main_reg = blobDecode($regSpec->main);
                $items_reg = blobDecode($regSpec->items);
                if (!is_array($main_reg)) {
                    cekHere("main bukan array");
                    $main_reg = blobDecode($main_reg);
                }
                if (!is_array($items_reg)) {
                    cekHere("items bukan array");
                    $items_reg = blobDecode($items_reg);
                }
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => $main_reg,
                    "items" => $items_reg,
                );
            }
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }
            foreach ($sellerData as $kdata => $vdata) {
                $trDatas["m_" . $kdata] = $vdata;
            }

            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",

                "seller_id" => "seller_id",
                "seller_name" => "seller_name",
                "seller_nama" => "seller_nama",
                "sellerID" => "sellerID",
                "sellerName" => "sellerName",
                "sellerNama" => "sellerNama",
            );
            $items = array();
            $total_qty = 0;
            $total_jml = 0;
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
//                    arrPrintKuning($mSpec);
                    $total_qty += $mSpec["qty"];
                    $total_jml += $mSpec["jml"];
                }
            }
            $trDatas["m_total_qty"] = $total_qty;
            $trDatas["m_total_jml"] = $total_jml;
            $trDatas["total_qty"] = $total_qty;
            $trDatas["total_jml"] = $total_jml;
            //endregion


            switch ($jenisTransaksi) {
                case "1982g":
                    // $masterID diganti dengan master id 582spo
                    $refID_so = $trDatas["m_transaksiDatas"];
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id='$refID_so'");
                    $refTrTmp = $tr->lookupAll()->result();
                    $masterID = $refTrTmp[0]->id_master;

                    //region data seller id dan nama dari id_master
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id_master='$masterID'");
                    $trTmpp = $tr->lookupAll()->result();
                    showLast_query("ungu");
                    $sellerData = array(
                        "seller_id" => $trTmpp[0]->oleh_id,
                        "seller_name" => $trTmpp[0]->oleh_nama,
                        "seller_nama" => $trTmpp[0]->oleh_nama,
                        "sellerID" => $trTmpp[0]->oleh_id,
                        "sellerName" => $trTmpp[0]->oleh_nama,
                        "sellerNama" => $trTmpp[0]->oleh_nama,
                        "id_master" => $trTmpp[0]->id,
                        "nomer_top" => $trTmpp[0]->nomer,
                    );
                    foreach ($sellerData as $kdata => $vdata) {
                        $trDatas[$kdata] = $vdata;
                        $trDatas["m_" . $kdata] = $vdata;
                    }
                    //endregion


                    $addItems = array(
                        "id_master" => "id_master",
                        "_stepCode_placeID" => "_stepCode_placeID",
                        "_stepCode_olehID" => "_stepCode_olehID",
                        "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                        "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                        "_stepCode_customerID" => "_stepCode_customerID",
                        "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                        "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                        "_stepCode" => "_stepCode",
                        "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                        "_stepCode_supplierID" => "_stepCode_supplierID",
                        "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                        "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                        "_step_1_nomer" => "_step_1_nomer",
                        "_step_1_olehName" => "_step_1_olehName",
                        "_step_2_nomer" => "_step_2_nomer",
                        "_step_2_olehName" => "_step_2_olehName",
                        "_step_3_nomer" => "_step_3_nomer",
                        "_step_3_olehName" => "_step_3_olehName",
                        "_step_4_nomer" => "_step_4_nomer",
                        "_step_4_olehName" => "_step_4_olehName",
                        "_step_5_nomer" => "_step_5_nomer",
                        "_step_5_olehName" => "_step_5_olehName",

                        "seller_id" => "seller_id",
                        "seller_name" => "seller_name",
                        "seller_nama" => "seller_nama",
                        "sellerID" => "sellerID",
                        "sellerName" => "sellerName",
                        "sellerNama" => "sellerNama",

                        "id_master" => "id_master",
                        "nomer_top" => "nomer_top",
                    );
//                    $items = array();
                    foreach ($trRegResult as $trid => $regSpec) {
                        foreach ($regSpec['items'] as $mkey => $mSpec) {
                            $items[$mkey] = $mSpec;
                            foreach ($addItems as $akey => $aval) {
                                $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                            }
                        }
                    }


                    break;
                case "7499":
                    $referenceID_so_approve = $trDatas["m_refID"];
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id='$referenceID_so_approve'");
                    $trTmpp_app = $tr->lookupAll()->result();
                    $masterID_spo = $trTmpp_app[0]->id_master;

                    $tr = new MdlTransaksi();
                    $tr->addFilter("id='$masterID_spo'");
                    $trTmpp = $tr->lookupAll()->result();
                    showLast_query("biru");
                    $sellerData = array(
                        "seller_id" => $trTmpp[0]->oleh_id,
                        "seller_name" => $trTmpp[0]->oleh_nama,
                        "seller_nama" => $trTmpp[0]->oleh_nama,
                        "sellerID" => $trTmpp[0]->oleh_id,
                        "sellerName" => $trTmpp[0]->oleh_nama,
                        "sellerNama" => $trTmpp[0]->oleh_nama,
                        "id_master" => $trTmpp[0]->id,
                        "nomer_top" => $trTmpp[0]->nomer,
                        "masterTopID" => $trTmpp[0]->id,
                    );
                    foreach ($sellerData as $kdata => $vdata) {
                        $trDatas[$kdata] = $vdata;
                        $trDatas["m_" . $kdata] = $vdata;
                    }

                    $addItems = array(
                        "id_master" => "id_master",
                        "_stepCode_placeID" => "_stepCode_placeID",
                        "_stepCode_olehID" => "_stepCode_olehID",
                        "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                        "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                        "_stepCode_customerID" => "_stepCode_customerID",
                        "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                        "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                        "_stepCode" => "_stepCode",
                        "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                        "_stepCode_supplierID" => "_stepCode_supplierID",
                        "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                        "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                        "_step_1_nomer" => "_step_1_nomer",
                        "_step_1_olehName" => "_step_1_olehName",
                        "_step_2_nomer" => "_step_2_nomer",
                        "_step_2_olehName" => "_step_2_olehName",
                        "_step_3_nomer" => "_step_3_nomer",
                        "_step_3_olehName" => "_step_3_olehName",
                        "_step_4_nomer" => "_step_4_nomer",
                        "_step_4_olehName" => "_step_4_olehName",
                        "_step_5_nomer" => "_step_5_nomer",
                        "_step_5_olehName" => "_step_5_olehName",

                        "seller_id" => "seller_id",
                        "seller_name" => "seller_name",
                        "seller_nama" => "seller_nama",
                        "sellerID" => "sellerID",
                        "sellerName" => "sellerName",
                        "sellerNama" => "sellerNama",
                        "id_master" => "id_master",
                        "nomer_top" => "nomer_top",
                        "m_masterTopID" => "m_masterTopID",
                    );
                    foreach ($trRegResult as $trid => $regSpec) {
                        foreach ($regSpec['items'] as $mkey => $mSpec) {
                            $items[$mkey] = $mSpec;
                            foreach ($addItems as $akey => $aval) {
                                $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                            }
                        }
                    }

                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->setJointSelectFields("transaksi_id, main, items");
                    $tr->addFilter("transaksi_id='$masterID_spo'");
                    $trReg = $tr->lookupDataRegistries()->result();
                    $total_qty = 0;
                    $total_jml = 0;
                    foreach ($trReg as $regSpec) {
                        $main_reg = blobDecode($regSpec->main);
                        $items_reg = blobDecode($regSpec->items);
                        if (!is_array($main_reg)) {
                            cekHere("main bukan array");
                            $main_reg = blobDecode($main_reg);
                        }
                        if (!is_array($items_reg)) {
                            cekHere("items bukan array");
                            $items_reg = blobDecode($items_reg);
                        }
                        foreach ($items_reg as $items_reg_spec) {
                            $total_qty += $items_reg_spec["qty"];
                            $total_jml += $items_reg_spec["jml"];
                        }
                    }
                    $trDatas["m_total_qty"] = $total_qty;
                    $trDatas["m_total_jml"] = $total_jml;
                    $trDatas["total_qty"] = $total_qty;
                    $trDatas["total_jml"] = $total_jml;

                    break;
            }


//arrPrintHijau($trDatas);
//arrPrintPink($items);
//cekHere("ref approve: $referenceID_so_approve, spo: $masterID_spo");
//cekHere("total qty: $total_qty, total jml: $total_jml");
//mati_disini(__LINE__);

            switch ($jenisTransaksi) {
                // lokal
                case "582spo":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "582spo" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "582spo" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "582so" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "582so" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "582so" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "582so" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "582spo" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
//                                "transaksi_tipe" => ".reguler",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "582spo" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".reguler",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                    }
                    break;
                case "582so":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "582so" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "582so" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "582spd" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "582spd" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "582spd" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
//                                "transaksi_tipe" => ".reguler",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "582spd" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".reguler",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "582so" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
//                                "transaksi_tipe" => ".reguler",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "582so" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".reguler",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                    }
                    break;
                case "582spd":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "582spd" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "582spd" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "582spo" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "582spo" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "update_lap" => false,
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
//                        $sourceMain = array(
//                            "loop" => array(
//                                "582spo" => "m_nett1",
//                            ),
//                            "static" => array(
//                                "cabang_id" => "m_placeID",
//                                "cabang_nama" => "m_placeName",
//                                "gudang_id" => "m_gudangID",
//                                "gudang_nama" => "m_gudangName",
//                                "rekening_nama" => "m_sourceJenisLabel",
//                                "produk_qty" => "qty",
//                                "produk_nilai" => "m_harga",
//                                "harga_bruto" => "m_harga",
//                                "ppn_nilai" => "m_ppn",
//                                "harga_netto" => "m_nett1",
//                                "harga_nppn" => "m_nett2",
//                                "diskon_nilai" => "m_disc",
//                                "premi_nilai" => "m_premi",
//                                "ongkir_nilai" => "m_shipping_service",
//                                "extern_id" => "id_master",
//                                "extern_nama" => "nomer",
//                                "satuan" => "satuan",
//                                "oleh_id" => "m_olehID",
//                                "oleh_nama" => "m_olehName",
//                                "seller_id" => "m_sellerID",
//                                "seller_nama" => "m_sellerName",
//                                "customer_id" => "m_pihakID",
//                                "customer_nama" => "m_pihakName",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                "jenis" => "jenis",
//                                "step_current" => "step_number",
//                                "step_number" => "step_current",
//                                "next_step_num" => "next_step_num",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
//                                "rel_target_jenis" => ".0",
////                                "transaksi_tipe" => ".reguler",
//                                "transaksi_tipe" => ".rejected",
//                                "step_reject" => ".3",
//                            ),
//                        );
//                        $sourceDetail = array(
//                            "loop" => array(
//                                "582spo" => "sub_nett1",
//                            ),
//                            "static" => array(
//                                "rekening_nama" => "sourceJenisLabel",
//                                "cabang_id" => "placeID",
//                                "gudang_id" => "gudangID",
//                                "gudang_nama" => "gudangName",
//                                "cabang_nama" => "placeName",
//                                "produk_qty" => "qty",
//                                "produk_nilai" => "harga",
//                                "harga_bruto" => "harga",
//                                "ppn_nilai" => "ppn",
//                                "harga_netto" => "nett1",
//                                "harga_nppn" => "nett2",
//                                "diskon_nilai" => "disc",
//                                "premi_nilai" => "premi",
//                                "extern_id" => "id",
//                                "extern_nama" => "name",
//                                "extern2_id" => "id_master",
//                                "extern2_nama" => ".0",
//                                "produk_kode" => "code",
//                                "produk_part" => "no_part",
//                                "produk_label" => "label",
//                                "produk_jenis" => "jenis",
//                                "produk_satuan" => "satuan",
//                                "satuan" => "satuan",
//                                "oleh_id" => "olehID",
//                                "oleh_nama" => "olehName",
//                                "seller_id" => "sellerID",
//                                "seller_nama" => "sellerName",
//                                "customer_id" => "pihakID",
//                                "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
////                                "transaksi_tipe" => ".reguler",
//                                "transaksi_tipe" => ".rejected",
//                                "step_reject" => ".3",
//                            ),
//                        );
//                        $targetMain = array(
//                            "loop" => array(
//                                "582spd" => "-m_nett1",
//                            ),
//                            "static" => array(
//                                "cabang_id" => "m_placeID",
//                                "cabang_nama" => "m_placeName",
//                                "gudang_id" => "m_gudangID",
//                                "gudang_nama" => "m_gudangName",
//                                "rekening_nama" => "m_sourceJenisLabel",
//                                "produk_qty" => "qty",
//                                "produk_nilai" => "m_harga",
//                                "harga_bruto" => "m_harga",
//                                "ppn_nilai" => "m_ppn",
//                                "harga_netto" => "m_nett1",
//                                "harga_nppn" => "m_nett2",
//                                "diskon_nilai" => "m_disc",
//                                "premi_nilai" => "m_premi",
//                                "ongkir_nilai" => "m_shipping_service",
//                                "extern_id" => "id_master",
//                                "extern_nama" => "nomer",
//                                "satuan" => "satuan",
//                                "oleh_id" => "m_olehID",
//                                "oleh_nama" => "m_olehName",
//                                "seller_id" => "m_sellerID",
//                                "seller_nama" => "m_sellerName",
//                                "customer_id" => "m_pihakID",
//                                "customer_nama" => "m_pihakName",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                "jenis" => "jenis",
//                                "step_current" => "step_number",
//                                "step_number" => "step_current",
//                                "next_step_num" => "next_step_num",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
//                                "rel_target_jenis" => ".0",
////                                "transaksi_tipe" => ".reguler",
//                                "transaksi_tipe" => ".rejected",
//                                "step_reject" => ".3",
//                            ),
//                        );
//                        $targetDetail = array(
//                            "loop" => array(
//                                "582spd" => "-sub_nett1",
//                            ),
//                            "static" => array(
//                                "rekening_nama" => "sourceJenisLabel",
//                                "cabang_id" => "placeID",
//                                "gudang_id" => "gudangID",
//                                "gudang_nama" => "gudangName",
//                                "cabang_nama" => "placeName",
//                                "produk_qty" => "-qty",
//                                "produk_nilai" => "harga",
//                                "harga_bruto" => "harga",
//                                "ppn_nilai" => "ppn",
//                                "harga_netto" => "nett1",
//                                "harga_nppn" => "nett2",
//                                "diskon_nilai" => "disc",
//                                "premi_nilai" => "premi",
//                                "extern_id" => "id",
//                                "extern_nama" => "name",
//                                "extern2_id" => "id_master",
//                                "extern2_nama" => ".0",
//                                "produk_kode" => "code",
//                                "produk_part" => "no_part",
//                                "produk_label" => "label",
//                                "produk_jenis" => "jenis",
//                                "produk_satuan" => "satuan",
//                                "satuan" => "satuan",
//                                "oleh_id" => "olehID",
//                                "oleh_nama" => "olehName",
//                                "seller_id" => "sellerID",
//                                "seller_nama" => "sellerName",
//                                "customer_id" => "pihakID",
//                                "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
////                                "transaksi_tipe" => ".reguler",
//                                "update_lap" => false,
//                                "transaksi_tipe" => ".rejected",
//                                "step_reject" => ".3",
//                            ),
//                        );
                        $sourceMain = array();
                        $sourceDetail = array();
                        $targetMain = array();
                        $targetDetail = array();
                    }
                    break;


                // export
                case "382spo":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "382spo" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "382so" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_step" => ".order",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "382spo" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "382so" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "382so" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
//                                "transaksi_tipe" => ".rejected",
                                "transaksi_tipe" => ".reguler",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "382spo" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "382so" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".rejected",
                                "transaksi_tipe" => ".reguler",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "382spo" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                    }
                    break;
                case "382so":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "382so" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "382spd" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_step" => ".order",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "382so" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "382spd" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "382spd" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "382so" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "382spd" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "382so" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                    }
                    break;
                case "382spd":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "382spd" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "382spo" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer_top",
                                "satuan" => "satuan",
//                            "oleh_id" => "m_olehID",
//                            "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_step" => ".order",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "382spd" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "382spo" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_step" => ".order",
                                "update_lap" => false,
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
//                        $sourceMain = array(
//                            "loop" => array(
//                                "382spo" => "m_nett1",
//                            ),
//                            "static" => array(
//                                "cabang_id" => "m_placeID",
//                                "cabang_nama" => "m_placeName",
//                                "gudang_id" => "m_gudangID",
//                                "gudang_nama" => "m_gudangName",
//                                "rekening_nama" => "m_sourceJenisLabel",
//                                "produk_qty" => "qty",
//                                "produk_nilai" => "m_harga",
//                                "harga_bruto" => "m_harga",
//                                "ppn_nilai" => "m_ppn",
//                                "harga_netto" => "m_nett1",
//                                "harga_nppn" => "m_nett2",
//                                "diskon_nilai" => "m_disc",
//                                "premi_nilai" => "m_premi",
//                                "ongkir_nilai" => "m_shipping_service",
//                                "extern_id" => "id_master",
//                                "extern_nama" => "nomer",
//                                "satuan" => "satuan",
//                                "oleh_id" => "m_olehID",
//                                "oleh_nama" => "m_olehName",
//                                "seller_id" => "m_sellerID",
//                                "seller_nama" => "m_sellerName",
//                                "customer_id" => "m_pihakID",
//                                "customer_nama" => "m_pihakName",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                "jenis" => "jenis",
//                                "step_current" => "step_number",
//                                "step_number" => "step_current",
//                                "next_step_num" => "next_step_num",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
//                                "rel_target_jenis" => ".0",
//                            ),
//                        );
//                        $targetMain = array(
//                            "loop" => array(
//                                "382spd" => "-m_nett1",
//                            ),
//                            "static" => array(
//                                "cabang_id" => "m_placeID",
//                                "cabang_nama" => "m_placeName",
//                                "gudang_id" => "m_gudangID",
//                                "gudang_nama" => "m_gudangName",
//                                "rekening_nama" => "m_sourceJenisLabel",
//                                "produk_qty" => "qty",
//                                "produk_nilai" => "m_harga",
//                                "harga_bruto" => "m_harga",
//                                "ppn_nilai" => "m_ppn",
//                                "harga_netto" => "m_nett1",
//                                "harga_nppn" => "m_nett2",
//                                "diskon_nilai" => "m_disc",
//                                "premi_nilai" => "m_premi",
//                                "ongkir_nilai" => "m_shipping_service",
//                                "extern_id" => "id_master",
//                                "extern_nama" => "nomer",
//                                "satuan" => "satuan",
////                            "oleh_id" => "m_olehID",
////                            "oleh_nama" => "m_olehName",
//                                "seller_id" => "m_sellerID",
//                                "seller_nama" => "m_sellerName",
//                                "customer_id" => "m_pihakID",
//                                "customer_nama" => "m_pihakName",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                "jenis" => "jenis",
//                                "step_current" => "step_number",
//                                "step_number" => "step_current",
//                                "next_step_num" => "next_step_num",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
//                                "rel_target_jenis" => ".0",
//                            ),
//                        );
//                        $sourceDetail = array(
//                            "loop" => array(
//                                "382spo" => "sub_nett1",
//                            ),
//                            "static" => array(
//                                "rekening_nama" => "sourceJenisLabel",
//                                "cabang_id" => "placeID",
//                                "gudang_id" => "gudangID",
//                                "gudang_nama" => "gudangName",
//                                "cabang_nama" => "placeName",
//                                "produk_qty" => "qty",
//                                "produk_nilai" => "harga",
//                                "harga_bruto" => "harga",
//                                "ppn_nilai" => "ppn",
//                                "harga_netto" => "nett1",
//                                "harga_nppn" => "nett2",
//                                "diskon_nilai" => "disc",
//                                "premi_nilai" => "premi",
//                                "extern_id" => "id",
//                                "extern_nama" => "name",
//                                "extern2_id" => "id_master",
//                                "extern2_nama" => ".0",
//                                "produk_kode" => "code",
//                                "produk_part" => "no_part",
//                                "produk_label" => "label",
//                                "produk_jenis" => "jenis",
//                                "produk_satuan" => "satuan",
//                                "satuan" => "satuan",
//                                "oleh_id" => "olehID",
//                                "oleh_nama" => "olehName",
//                                "seller_id" => "sellerID",
//                                "seller_nama" => "sellerName",
//                                "customer_id" => "pihakID",
//                                "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".reguler",
//                            ),
//                        );
//                        $targetDetail = array(
//                            "loop" => array(
//                                "382spd" => "-sub_nett1",
//                            ),
//                            "static" => array(
//                                "rekening_nama" => "sourceJenisLabel",
//                                "cabang_id" => "placeID",
//                                "gudang_id" => "gudangID",
//                                "gudang_nama" => "gudangName",
//                                "cabang_nama" => "placeName",
//                                "produk_qty" => "-qty",
//                                "produk_nilai" => "harga",
//                                "harga_bruto" => "harga",
//                                "ppn_nilai" => "ppn",
//                                "harga_netto" => "nett1",
//                                "harga_nppn" => "nett2",
//                                "diskon_nilai" => "disc",
//                                "premi_nilai" => "premi",
//                                "extern_id" => "id",
//                                "extern_nama" => "name",
//                                "extern2_id" => "id_master",
//                                "extern2_nama" => ".0",
//                                "produk_kode" => "code",
//                                "produk_part" => "no_part",
//                                "produk_label" => "label",
//                                "produk_jenis" => "jenis",
//                                "produk_satuan" => "satuan",
//                                "satuan" => "satuan",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
//                                "seller_id" => "sellerID",
//                                "seller_nama" => "sellerName",
//                                "customer_id" => "pihakID",
//                                "customer_nama" => "pihakName",
////                            "transaksi_id" => "transaksi_id",
//                                "master_id" => "id_master",
//                                "master_jenis" => "jenis_master",
//                                //------
//                                "_stepCode_placeID" => "_stepCode_placeID",
//                                "_stepCode_olehID" => "_stepCode_olehID",
//                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                                "_stepCode_customerID" => "_stepCode_customerID",
//                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                                "_stepCode" => "_stepCode",
//                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                                "_stepCode_supplierID" => "_stepCode_supplierID",
//                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
//                                //------
////                "dtime_order" => "dtime",
////                "dtime_kirim" => "shippingDate",
////                "dtime_terima" => "",
//                                "_step_1_nomer" => "_step_1_nomer",
//                                "_step_1_olehName" => "_step_1_olehName",
//                                "_step_2_nomer" => "_step_2_nomer",
//                                "_step_2_olehName" => "_step_2_olehName",
//                                "_step_3_nomer" => "_step_3_nomer",
//                                "_step_3_olehName" => "_step_3_olehName",
//                                "_step_4_nomer" => "_step_4_nomer",
//                                "_step_4_olehName" => "_step_4_olehName",
//                                "_step_5_nomer" => "_step_5_nomer",
//                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".reguler",
//                                "update_lap" => false,
//                            ),
//                        );
                        $sourceMain = array();
                        $targetMain = array();
                        $sourceDetail = array();
                        $targetDetail = array();
                    }
                    break;

                // closed
                case "1982g":
                    $sourceMain = array(
                        "loop" => array(
                            "582spd" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "m_total_qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer_top",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".closed",
//                            "transaksi_step" => ".kirim",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "582spd" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => "nomer_top",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".closed",
//                            "transaksi_step" => ".kirim",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "582spo" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "-m_total_qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer_top",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".closed",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "582spo" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => "nomer_top",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                            "master_id" => "id_master",
                            "master_jenis" => "jenis_master",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".closed",
                            "update_lap" => false,
                        ),
                    );
                    break;

                // project
                case "588spo":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "588spo" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "588so" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "588spo" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "588so" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "588so" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
//                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "588spo" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "588so" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
//                                "transaksi_tipe" => ".rejected",
                                "transaksi_tipe" => ".reguler",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "588spo" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                    }
                    break;
                case "588so":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "588so" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "7499" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "588so" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "7499" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "7499" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "588so" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "7499" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "588so" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                    }
                    break;
                case "7499":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "7499" => "m_penjualan",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_penjualan",
                                "harga_bruto" => "m_penjualan",
                                "ppn_nilai" => "m_grand_ppn",
                                "harga_netto" => "m_penjualan",
                                "harga_nppn" => "m_piutang_dagang",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "m_masterTopID",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "588spo" => "-m_penjualan",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_penjualan",
                                "harga_bruto" => "m_penjualan",
                                "ppn_nilai" => "m_grand_ppn",
                                "harga_netto" => "m_penjualan",
                                "harga_nppn" => "m_piutang_dagang",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "id_master",
                                "extern_nama" => "nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "m_masterTopID",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "7499" => "nilai_bayar",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "nilai_bayar",
                                "harga_bruto" => "nilai_bayar",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nilai_bayar",
                                "harga_nppn" => "nilai_bayar",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "project_id",
                                "extern_nama" => "project_nama",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "m_masterTopID",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "588spo" => "-nilai_bayar",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "nilai_bayar",
                                "harga_bruto" => "nilai_bayar",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nilai_bayar",
                                "harga_nppn" => "nilai_bayar",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "project_id",
                                "extern_nama" => "project_nama",
                                "extern2_id" => "id_master",
                                "extern2_nama" => ".0",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "m_masterTopID",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    break;


                default:

                    mati_disini(":: $jenisTransaksi ::");

                    break;
            }
            //------------------------------------

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            $gateSourceKonsolidasian = array();
            $gateSourceSales = array();
            $gateSourceCabang = array();
            $gateSourceCabangSales = array();
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            //------------------------------------
            if (sizeof($sourceMain) > 0) {
                foreach ($sourceMain['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateSource['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceMain['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateSource['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSource['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                            $gateSource['static'][$key] = $newVal_result;
                        }
                    }
                }
                if ($transaksiTipeTimeline == "reguler") {
                    $gateSource['static']['transaksi_id'] = $mainData['id'];
                    $gateSource['static']['fulldate'] = $mainData['fulldate'];
                    $gateSource['static']['dtime'] = $mainData['dtime'];
                }
                if ($transaksiTipeTimeline == "reject") {
                    $gateSource['static']['transaksi_id'] = $mainData['id'];
                    $gateSource['static']['fulldate'] = $cancel_fulldate;
                    $gateSource['static']['dtime'] = $cancel_dtime;
                }
            }
            //------------------------------------
            if (sizeof($targetMain) > 0) {
                foreach ($targetMain['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateTarget['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetMain['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateTarget['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTarget['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                            $gateTarget['static'][$key] = $newVal_result;
                        }
                    }
                }
                if ($transaksiTipeTimeline == "reguler") {
                    $gateTarget['static']['transaksi_id'] = $mainData['id'];
                    $gateTarget['static']['fulldate'] = $mainData['fulldate'];
                    $gateTarget['static']['dtime'] = $mainData['dtime'];
                }
                if ($transaksiTipeTimeline == "reject") {
                    $gateTarget['static']['transaksi_id'] = $mainData['id'];
                    $gateTarget['static']['fulldate'] = $cancel_fulldate;
                    $gateTarget['static']['dtime'] = $cancel_dtime;
                }
            }
            //------------------------------------
            foreach ($items as $ii => $iiSpec) {
                if (sizeof($sourceDetail) > 0) {
                    foreach ($sourceDetail['loop'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                        }
                    }
                    foreach ($sourceDetail['static'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                        }
                        else {
                            if (substr($val, 0, 1) == ".") {
                                $newVal = str_replace(".", "", $val);
                                $newVal_result = $newVal;
                                $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                            }
                            else {
                                $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                                $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                            }
                        }
                    }
                    if ($transaksiTipeTimeline == "reguler") {
                        $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                        $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                        $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                        $gateSourceDetail[$ii]['static']['fulldate'] = $cancel_fulldate;
                        $gateSourceDetail[$ii]['static']['dtime'] = $cancel_dtime;
                    }
                }
                if (sizeof($targetDetail) > 0) {
                    foreach ($targetDetail['loop'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                        }
                    }
                    foreach ($targetDetail['static'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                        }
                        else {
                            if (substr($val, 0, 1) == ".") {
                                $newVal = str_replace(".", "", $val);
                                $newVal_result = $newVal;
                                $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                            }
                            else {
                                $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                                $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                            }
                        }
                    }
                    if ($transaksiTipeTimeline == "reguler") {
                        $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                        $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                        $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                        $gateTargetDetail[$ii]['static']['fulldate'] = $cancel_fulldate;
                        $gateTargetDetail[$ii]['static']['dtime'] = $cancel_dtime;
                    }
                }

            }
            //------------------------------------


            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSales();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {
                    $rt = New ComRekeningTransaksiSales();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {
                    $rt = New ComRekeningTransaksiSalesPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region sales cabang
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesCabang();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabang();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabangPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabangPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesSalesman();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesman();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesmanPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                if (sizeof($gateSource) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabangSalesman();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabangSalesman();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesCabangSalesmanPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                if (sizeof($gateSource) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesmanTransaksi();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesmanTransaksi();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesmanTransaksiPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesSalesmanTransaksiPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }


            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                //region MASTER
                $rt = New ComRekeningTransaksi();
                $rt->pair($gateSource);
                $rt->exec();

                $rt = New ComRekeningTransaksi();
                $rt->pair($gateTarget);
                $rt->exec();
                //endregion

                //region DETAIL
                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateSourceDetail);
                $rt->exec();

                $rt = New ComRekeningTransaksiPembantu();
                $rt->pair($gateTargetDetail);
                $rt->exec();
                //endregion
            }


//            $tr = new MdlTransaksi();
//            $updateData = array(
//                "non_akunting_2" => "1",
////                "non_akunting" => "1",
//            );
//            $updateWhere = array(
//                "id" => $trID
//            );
//            $tr->updateData($updateWhere, $updateData);
//            showLast_query("orange");


            $cts = New ComRekeningTransaksiSales();
            $cts->addFilter("transaksi_id='$transaksiID'");
            $ctsTmp = $cts->lookupAll()->result();
            if (sizeof($ctsTmp) == 0) {
                mati_disini("transaksi tidak masuk setelah cli dijalankan. silahkan diperiksa lagi. " . __LINE__);
            }

            $ctss = New ComRekeningTransaksiSalesSalesman();
            $ctss->addFilter("transaksi_id='$transaksiID'");
            $ctssTmp = $ctss->lookupAll()->result();
            if (sizeof($ctssTmp) == 0) {
                mati_disini("transaksi tidak masuk setelah cli dijalankan. silahkan diperiksa lagi. " . __LINE__);
            }


            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;

            // sudah selesai running maka status diupdate menjadi: 1
            $tbl = "z_tabel_timeline";
            $updateData = array(
                "status" => "1",
                "waktu" => $val,
            );
            $updateWhere = array(
                "id" => $tblID
            );
            $this->db->where($updateWhere);
            $this->db->update($tbl, $updateData);
            showLast_query("orange");


        }
        else {
            cekMerah("-- HABIS --");

//            $tr = new MdlTransaksi();
//            $updateData = array(
////                "non_akunting_2" => "1",
//                "non_akunting" => "1",
//            );
//            $updateWhere = array(
//                "id" => $master_iddd
//            );
//            $tr->updateData($updateWhere, $updateData);
//            showLast_query("orange");

        }

//        if($jenisTransaksi == "1982g"){
//
//        }
//

        if ($stopCommit == true) {

            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function nonAkuntingAllSalesBatal()
    {
//        $stopCommit = true;
        $stopCommit = false;

//        header("refresh:1");
        $starttime = microtime(true);

        //region load model component
        $this->load->model("Coms/ComRekeningTransaksiSalesBatal");
        $this->load->model("Coms/ComRekeningTransaksiSalesBatalPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesBatalCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesBatalCabangPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesBatalSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesBatalSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesBatalCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesBatalCabangSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesBatalSalesmanTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiSalesBatalSalesmanTransaksiPembantu");

//        $this->load->model("Coms/ComRekeningTransaksi");
//        $this->load->model("Coms/ComRekeningTransaksiPembantu");


        $this->load->model("MdlTransaksi");
        //endregion

        $tahun = "2022";
        $jenisMaster = "582";
        $divID = "18";
        $arrJenis = array(
            "9912",
        );
        //--------------------
        $tbl = "z_tabel_timeline";
        $this->db->select("id, transaksi_id, jenis, dtime, tipe");
        $this->db->order_by('dtime', 'ASC');
        $arrayWhere = array(
            "status" => "0",
            "jenis" => "9912",
        );
        $this->db->where($arrayWhere);
        $this->db->limit(1);
        $result = $this->db->get($tbl)->result();
        showLast_query("kuning");
//        arrPrintKuning($result);
        if (sizeof($result) > 0) {
            $tblID = $result[0]->id;
            $transaksiID = $result[0]->transaksi_id;
            $transaksiTipeTimeline = $result[0]->tipe;
        }
        else {
            mati_disini("--- HABIS ---");
        }
        //--------------------


        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
        $tr->addFilter("id='$transaksiID'");
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));


        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            //region data transaksi
            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }

            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            cekHitam(":: trID: $trID, masterID: $masterID, jenis: $jenisTransaksi, masterJenis: $masterJenis ::");
//            mati_disini(__LINE__);


            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //endregion


            //region data seller id dan nama dari id_master
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$masterID'");
            $trTmpp = $tr->lookupAll()->result();
            $sellerData = array(
                "seller_id" => $trTmpp[0]->oleh_id,
                "seller_name" => $trTmpp[0]->oleh_nama,
                "seller_nama" => $trTmpp[0]->oleh_nama,
                "sellerID" => $trTmpp[0]->oleh_id,
                "sellerName" => $trTmpp[0]->oleh_nama,
                "sellerNama" => $trTmpp[0]->oleh_nama,
            );
            foreach ($sellerData as $kdata => $vdata) {
                $trDatas[$kdata] = $vdata;
            }
            //endregion


            //region transaksi data registry
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");
            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $main_reg = blobDecode($regSpec->main);
                $items_reg = blobDecode($regSpec->items);
                if (!is_array($main_reg)) {
                    cekHere("main bukan array");
                    $main_reg = blobDecode($main_reg);
                }
                if (!is_array($items_reg)) {
                    cekHere("items bukan array");
                    $items_reg = blobDecode($items_reg);
                }
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => $main_reg,
                    "items" => $items_reg,
                );
            }
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }
            foreach ($sellerData as $kdata => $vdata) {
                $trDatas["m_" . $kdata] = $vdata;
            }

            //endregion

//arrPrint($trDatas);
            switch ($jenisTransaksi) {
                case "9912":
                    // $refID_so dari transaksi yang dibatalkan
                    $reference_id_xx = $trDatas["reference_id"];
                    $m_jenisTr_reference = $trDatas["m_jenisTr_reference"];
                    $refID_so = $trDatas["m_transaksi"];
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id='$refID_so'");
                    $refTrTmp = $tr->lookupAll()->result();
                    $masterID = $refTrTmp[0]->id_master; // masterID transaksi yang dibatalkan (582spo)

                    //region data seller id dan nama dari id_master
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id_master='$masterID'");
                    $trTmpp = $tr->lookupAll()->result();
                    showLast_query("ungu");
                    $sellerData = array(
                        "seller_id" => $trTmpp[0]->oleh_id,
                        "seller_name" => $trTmpp[0]->oleh_nama,
                        "seller_nama" => $trTmpp[0]->oleh_nama,
                        "sellerID" => $trTmpp[0]->oleh_id,
                        "sellerName" => $trTmpp[0]->oleh_nama,
                        "sellerNama" => $trTmpp[0]->oleh_nama,
                        "id_master" => $trTmpp[0]->id,
                        "nomer_top" => $trTmpp[0]->nomer,
                    );
                    foreach ($sellerData as $kdata => $vdata) {
                        $trDatas[$kdata] = $vdata;
                        $trDatas["m_" . $kdata] = $vdata;
                    }
                    //endregion

                    cekHere("REFERENCE 9112: $m_jenisTr_reference");
                    switch ($m_jenisTr_reference) {
                        case "382spd":
                        case "582spd":
                            $tr = new MdlTransaksi();
                            $tr->addFilter("id='$reference_id_xx'");
                            $trTmpp = $tr->lookupAll()->result();
                            $trDatas["reference_id_top"] = ($trDatas["reference_id_top"] == 0) ? $trTmpp[0]->id_top : $trDatas["reference_id_top"];
                            $trDatas["reference_nomer_top"] = ($trDatas["reference_nomer_top"] == NULL) ? $trTmpp[0]->id_top : $trDatas["nomer_top"];
                            $trDatas["reference_jenis_top"] = ($trDatas["reference_jenis_top"] == NULL) ? $trTmpp[0]->id_top : $trDatas["jenis_top"];
                            break;
                        case "7499":
                            $trDatas["m_nett1"] = $trDatas["m_penjualan"];
                            $trReffID = $trDatas["m_refID"];
                            $tr = new MdlTransaksi();
                            $tr->addFilter("id='$trReffID'");
                            $trTmpp = $tr->lookupAll()->result();
                            $masterID_project = $trTmpp[0]->id_master; // masterID transaksi yang dibatalkan (588spo)

                            $tr = new MdlTransaksi();
                            $tr->addFilter("id='$masterID_project'");
                            $trTmppp = $tr->lookupAll()->result();
                            $sellerData = array(
                                "seller_id" => $trTmppp[0]->oleh_id,
                                "seller_name" => $trTmppp[0]->oleh_nama,
                                "seller_nama" => $trTmppp[0]->oleh_nama,
                                "sellerID" => $trTmppp[0]->oleh_id,
                                "sellerName" => $trTmppp[0]->oleh_nama,
                                "sellerNama" => $trTmppp[0]->oleh_nama,
                                "id_master" => $trTmppp[0]->id,
                                "nomer_top" => $trTmppp[0]->nomer,
                            );
                            foreach ($sellerData as $kdata => $vdata) {
                                $trDatas[$kdata] = $vdata;
                                $trDatas["m_" . $kdata] = $vdata;
                            }

                            foreach ($trRegResult as $trid => $regSpec) {
                                foreach ($regSpec['items'] as $mkey => $mSpec) {
                                    $trRegResult[$trid]['items'][$mkey]["nett1"] = $mSpec["nilai_bayar"];
                                    $trRegResult[$trid]['items'][$mkey]["sub_nett1"] = $mSpec["nilai_bayar"];
                                }
                            }

                            $trDatas["reference_id_top"] = ($trDatas["reference_id_top"] == NULL) ? $trTmpp[0]->id_top : $trDatas["reference_id_top"];
                            $trDatas["reference_nomer_top"] = ($trDatas["reference_nomer_top"] == NULL) ? $trTmpp[0]->id_top : $trDatas["nomer_top"];
                            $trDatas["reference_jenis_top"] = ($trDatas["reference_jenis_top"] == NULL) ? $trTmpp[0]->id_top : $trDatas["jenis_top"];
                            break;
                        default:
                            break;
                    }

                    break;
            }
//arrPrintKuning($trRegResult);
//arrPrintKuning($trDatas);
//mati_disini("STOP>>>");
            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",

                "seller_id" => "seller_id",
                "seller_name" => "seller_name",
                "seller_nama" => "seller_nama",
                "sellerID" => "sellerID",
                "sellerName" => "sellerName",
                "sellerNama" => "sellerNama",
                "nomer_top" => "nomer_top",
                "reference_id_top" => "reference_id_top",
                "reference_jenis" => "reference_jenis",

            );
            $items = array();
            $total_qty = 0;
            $total_jml = 0;
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
                    $total_qty += $mSpec["qty"];
                    $total_jml += $mSpec["jml"];
                }
            }
            $trDatas["m_total_qty"] = $total_qty;
            $trDatas["m_total_jml"] = $total_jml;
            $trDatas["total_qty"] = $total_qty;
            $trDatas["total_jml"] = $total_jml;

//            arrPrint($trDatas);
//            arrPrintHijau($items);
//            mati_disini();
            switch ($jenisTransaksi) {
                // pembatalan
                case "9912":
                    $sourceMain = array(
                        "loop" => array(
                            "9912" => "m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "m_total_qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer_top",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "reference_id_top",//master_id, id_master
                            "master_jenis" => "reference_jenis",//jenis_master
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
                            "transaksi_tipe" => ".batal",
                        ),
                    );
                    $targetMain = array(
                        "loop" => array(
                            "9912" => "-m_nett1",
                        ),
                        "static" => array(
                            "cabang_id" => "m_placeID",
                            "cabang_nama" => "m_placeName",
                            "gudang_id" => "m_gudangID",
                            "gudang_nama" => "m_gudangName",
                            "rekening_nama" => "m_sourceJenisLabel",
                            "produk_qty" => "-m_total_qty",
                            "produk_nilai" => "m_harga",
                            "harga_bruto" => "m_harga",
                            "ppn_nilai" => "m_ppn",
                            "harga_netto" => "m_nett1",
                            "harga_nppn" => "m_nett2",
                            "diskon_nilai" => "m_disc",
                            "premi_nilai" => "m_premi",
                            "ongkir_nilai" => "m_shipping_service",
                            "extern_id" => "id_master",
                            "extern_nama" => "nomer_top",
                            "satuan" => "satuan",
                            "oleh_id" => "m_olehID",
                            "oleh_nama" => "m_olehName",
                            "seller_id" => "m_sellerID",
                            "seller_nama" => "m_sellerName",
                            "customer_id" => "m_pihakID",
                            "customer_nama" => "m_pihakName",
                            "master_id" => "reference_id_top",//master_id, id_master
                            "master_jenis" => "reference_jenis",//jenis_master
                            "jenis" => "jenis",
                            "step_current" => "step_number",
                            "step_number" => "step_current",
                            "next_step_num" => "next_step_num",
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "rel_target_jenis" => ".0",
//                            "transaksi_tipe" => ".batal",
                        ),
                    );
                    $sourceDetail = array(
                        "loop" => array(
                            "9912" => "sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => "nomer_top",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
                            //                            "transaksi_id" => "transaksi_id",
                            "master_id" => "reference_id_top",//id_master
                            "master_jenis" => "reference_jenis",//jenis_master
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
                            "transaksi_tipe" => ".batal",
                        ),
                    );
                    $targetDetail = array(
                        "loop" => array(
                            "9912" => "-sub_nett1",
                        ),
                        "static" => array(
                            "rekening_nama" => "sourceJenisLabel",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga",
                            "harga_bruto" => "harga",
                            "ppn_nilai" => "ppn",
                            "harga_netto" => "nett1",
                            "harga_nppn" => "nett2",
                            "diskon_nilai" => "disc",
                            "premi_nilai" => "premi",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "id_master",
                            "extern2_nama" => "nomer_top",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "seller_id" => "sellerID",
                            "seller_nama" => "sellerName",
                            "customer_id" => "pihakID",
                            "customer_nama" => "pihakName",
                            //                            "transaksi_id" => "transaksi_id",
                            "master_id" => "reference_id_top",//id_master
                            "master_jenis" => "reference_jenis",//jenis_master
                            //------
                            "_stepCode_placeID" => "_stepCode_placeID",
                            "_stepCode_olehID" => "_stepCode_olehID",
                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                            "_stepCode_customerID" => "_stepCode_customerID",
                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                            "_stepCode" => "_stepCode",
                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                            "_stepCode_supplierID" => "_stepCode_supplierID",
                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                            "_step_1_nomer" => "_step_1_nomer",
                            "_step_1_olehName" => "_step_1_olehName",
                            "_step_2_nomer" => "_step_2_nomer",
                            "_step_2_olehName" => "_step_2_olehName",
                            "_step_3_nomer" => "_step_3_nomer",
                            "_step_3_olehName" => "_step_3_olehName",
                            "_step_4_nomer" => "_step_4_nomer",
                            "_step_4_olehName" => "_step_4_olehName",
                            "_step_5_nomer" => "_step_5_nomer",
                            "_step_5_olehName" => "_step_5_olehName",
//                            "transaksi_tipe" => ".batal",
                        ),
                    );
                    break;


                default:

                    mati_disini(":: $jenisTransaksi ::");

                    break;
            }
            //------------------------------------

//arrPrintPink($trDatas);
//mati_disini();

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            $gateSourceKonsolidasian = array();
            $gateSourceSales = array();
            $gateSourceCabang = array();
            $gateSourceCabangSales = array();
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            //------------------------------------
            if (sizeof($sourceMain) > 0) {
                foreach ($sourceMain['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateSource['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceMain['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateSource['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSource['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                            $gateSource['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateSource['static']['transaksi_id'] = $mainData['id'];
                $gateSource['static']['fulldate'] = $mainData['fulldate'];
                $gateSource['static']['dtime'] = $mainData['dtime'];
            }
            //------------------------------------
            if (sizeof($targetMain) > 0) {
                foreach ($targetMain['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateTarget['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetMain['static'] as $key => $val) {
//                    cekKuning(":: $key => $val ::");
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateTarget['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTarget['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                            $gateTarget['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateTarget['static']['transaksi_id'] = $mainData['id'];
                $gateTarget['static']['fulldate'] = $mainData['fulldate'];
                $gateTarget['static']['dtime'] = $mainData['dtime'];
            }
            //------------------------------------
            foreach ($items as $ii => $iiSpec) {
                if (sizeof($sourceDetail) > 0) {
                    foreach ($sourceDetail['loop'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                        }
                    }
                    foreach ($sourceDetail['static'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                        }
                        else {
                            if (substr($val, 0, 1) == ".") {
                                $newVal = str_replace(".", "", $val);
                                $newVal_result = $newVal;
                                $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                            }
                            else {
                                $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                                $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                            }
                        }
                    }
                    $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                    $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                    $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                }
                if (sizeof($targetDetail) > 0) {
                    foreach ($targetDetail['loop'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                        }
                    }
                    foreach ($targetDetail['static'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                        }
                        else {
                            if (substr($val, 0, 1) == ".") {
                                $newVal = str_replace(".", "", $val);
                                $newVal_result = $newVal;
                                $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                            }
                            else {
                                $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                                $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                            }
                        }
                    }
                    $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                    $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                    $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                }

            }
            //------------------------------------

//arrPrintHijau($gateSource);
//            $gateSourceKonsolidasian = $gateSource;
//            $gateSourceDetailKonsolidasian = $gateSourceDetail;
//mati_disini(__LINE__);

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesBatal();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {
                    $rt = New ComRekeningTransaksiSalesBatal();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {
                    $rt = New ComRekeningTransaksiSalesBatalPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region sales cabang
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesBatalCabang();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabang();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabangPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabangPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesBatalSalesman();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesman();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesmanPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesmanPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                if (sizeof($gateSource) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabangSalesman();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabangSalesman();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabangSalesmanPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalCabangSalesmanPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                if (sizeof($gateSource) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesmanTransaksi();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesmanTransaksi();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesmanTransaksiPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesBatalSalesmanTransaksiPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }


            $cts = New ComRekeningTransaksiSalesBatal();
            $cts->addFilter("transaksi_id='$transaksiID'");
            $ctsTmp = $cts->lookupAll()->result();
            if (sizeof($ctsTmp) == 0) {
                mati_disini("transaksi tidak masuk setelah cli dijalankan. silahkan diperiksa lagi. " . __LINE__);
            }

            $ctss = New ComRekeningTransaksiSalesBatalSalesman();
            $ctss->addFilter("transaksi_id='$transaksiID'");
            $ctssTmp = $ctss->lookupAll()->result();
            if (sizeof($ctssTmp) == 0) {
                mati_disini("transaksi tidak masuk setelah cli dijalankan. silahkan diperiksa lagi. " . __LINE__);
            }


            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;

            // sudah selesai running maka status diupdate menjadi: 1
            $tbl = "z_tabel_timeline";
            $updateData = array(
                "status" => "1",
                "waktu" => $val,
            );
            $updateWhere = array(
                "id" => $tblID
            );
            $this->db->where($updateWhere);
            $this->db->update($tbl, $updateData);
            showLast_query("orange");
        }
        else {
            cekMerah("-- HABIS --");

//            $tr = new MdlTransaksi();
//            $updateData = array(
////                "non_akunting_2" => "1",
//                "non_akunting" => "1",
//            );
//            $updateWhere = array(
//                "id" => $master_iddd
//            );
//            $tr->updateData($updateWhere, $updateData);
//            showLast_query("orange");

        }


        if ($stopCommit == true) {

            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function nonAkuntingAllSalesReturn()
    {
//        $stopCommit = true;
        $stopCommit = false;

        header("refresh:1");
        $starttime = microtime(true);

        //region load model component
        $this->load->model("Coms/ComRekeningTransaksiSalesReturn");
        $this->load->model("Coms/ComRekeningTransaksiSalesReturnPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesReturnCabang");
        $this->load->model("Coms/ComRekeningTransaksiSalesReturnCabangPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesReturnSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesReturnSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesReturnCabangSalesman");
        $this->load->model("Coms/ComRekeningTransaksiSalesReturnCabangSalesmanPembantu");

        $this->load->model("Coms/ComRekeningTransaksiSalesReturnSalesmanTransaksi");
        $this->load->model("Coms/ComRekeningTransaksiSalesReturnSalesmanTransaksiPembantu");

//        $this->load->model("Coms/ComRekeningTransaksi");
//        $this->load->model("Coms/ComRekeningTransaksiPembantu");


        $this->load->model("MdlTransaksi");
        //endregion

        $tahun = "2022";
        $jenisMaster = "582";
        $divID = "18";
        $arrJenis = array(
            "982r",
            "982g",
            "982",
        );
        //--------------------
        $tbl = "z_tabel_timeline";
        $this->db->select("id, transaksi_id, jenis, dtime, tipe");
        $this->db->order_by('dtime', 'ASC');
        $arrayWhere = array(
            "status" => "0",

        );
        $this->db->where($arrayWhere);
        $this->db->where_in("jenis", $arrJenis);
        $this->db->limit(1);
        $result = $this->db->get($tbl)->result();
        showLast_query("kuning");
        arrPrintKuning($result);
        if (sizeof($result) > 0) {
            $tblID = $result[0]->id;
            $transaksiID = $result[0]->transaksi_id;
            $transaksiTipeTimeline = $result[0]->tipe;
        }
        else {
            mati_disini("--- HABIS ---");
        }
        //--------------------


        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='$divID'");
        $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
        $tr->addFilter("id='$transaksiID'");
        $this->db->limit(1);
        $tmpHist = $tr->lookupAll()->result();
        showLast_query("biru");
        cekHitam(sizeof($tmpHist));


        $this->db->trans_start();


        $trDatas = array();
        if (sizeof($tmpHist) > 0) {

            //region data transaksi
            foreach ($tmpHist[0] as $key => $val) {
                $trDatas[$key] = $val;
            }

            $trID = $tmpHist[0]->id;
            $masterID = $tmpHist[0]->id_master;
            $masterJenis = $tmpHist[0]->jenis_master;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $counters = $tmpHist[0]->counters;
            cekHitam(":: trID: $trID, masterID: $masterID, jenis: $jenisTransaksi, masterJenis: $masterJenis ::");
//            mati_disini(__LINE__);


            $ids_his = $tmpHist[0]->ids_his != NULL ? blobDecode($tmpHist[0]->ids_his) : array();
            $ids_his_data = array();
            if (sizeof($ids_his) > 0) {
                foreach ($ids_his as $step => $hisSpec) {
                    $aa = "_step_" . $step . "_nomer";
                    $bb = "_step_" . $step . "_olehID";
                    $cc = "_step_" . $step . "_olehName";
                    $trDatas[$aa] = $hisSpec['nomer'];
                    $trDatas[$bb] = $hisSpec['olehID'];
                    $trDatas[$cc] = $hisSpec['olehName'];
                }
            }
            $cContent = blobDecode($tmpHist[0]->counters);
            $arrIndexVal = array();
            foreach ($cContent as $counterKey => $counterData) {
                $counterIndexKey = "_" . str_replace("|", "_", $counterKey);
                foreach ($counterData as $key => $index_conter) {
//                    $arrIndexVal[$counterIndexKey] = $index_conter;
                    $trDatas[$counterIndexKey] = $index_conter;
                }
            }
            //endregion


            //region data seller id dan nama dari id_master
            $tr = new MdlTransaksi();
            $tr->addFilter("id_master='$masterID'");
            $trTmpp = $tr->lookupAll()->result();
            $sellerData = array(
                "seller_id" => $trTmpp[0]->oleh_id,
                "seller_name" => $trTmpp[0]->oleh_nama,
                "seller_nama" => $trTmpp[0]->oleh_nama,
                "sellerID" => $trTmpp[0]->oleh_id,
                "sellerName" => $trTmpp[0]->oleh_nama,
                "sellerNama" => $trTmpp[0]->oleh_nama,
            );
            foreach ($sellerData as $kdata => $vdata) {
                $trDatas[$kdata] = $vdata;
            }
            //endregion


            //region transaksi data registry
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main, items");
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            showLast_query("kuning");
            $trRegResult = array();
            foreach ($trReg as $regSpec) {
                $main_reg = blobDecode($regSpec->main);
                $items_reg = blobDecode($regSpec->items);
                if (!is_array($main_reg)) {
                    cekHere("main bukan array");
                    $main_reg = blobDecode($main_reg);
                }
                if (!is_array($items_reg)) {
                    cekHere("items bukan array");
                    $items_reg = blobDecode($items_reg);
                }
                $trRegResult[$regSpec->transaksi_id] = array(
                    "main" => $main_reg,
                    "items" => $items_reg,
                );
            }
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['main'] as $mkey => $mval) {
                    $trDatas["m_" . $mkey] = $mval;
                }
            }
            foreach ($sellerData as $kdata => $vdata) {
                $trDatas["m_" . $kdata] = $vdata;
            }


            //endregion


            switch ($jenisTransaksi) {
                case "982r":
                case "982g":
                case "982":
//                    arrPrintKuning($trDatas);
                    // $refID_so dari transaksi yang dibatalkan
//                    $refID_so = $trDatas["m_transaksi"];
                    $refID_so = $trDatas["m_referenceID"];
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id='$refID_so'");
                    $refTrTmp = $tr->lookupAll()->result();
                    showLast_query("orange");
                    $masterID = $refTrTmp[0]->id_master; // masterID transaksi yang dibatalkan (582spo)

                    //region data seller id dan nama dari id_master
                    $tr = new MdlTransaksi();
                    $tr->addFilter("id_master='$masterID'");
                    $trTmpp = $tr->lookupAll()->result();
//                    showLast_query("ungu");
//                    mati_disini();
                    $sellerData = array(
                        "seller_id" => $trTmpp[0]->oleh_id,
                        "seller_name" => $trTmpp[0]->oleh_nama,
                        "seller_nama" => $trTmpp[0]->oleh_nama,
                        "sellerID" => $trTmpp[0]->oleh_id,
                        "sellerName" => $trTmpp[0]->oleh_nama,
                        "sellerNama" => $trTmpp[0]->oleh_nama,
                        "id_master" => $trTmpp[0]->id,
                        "master_nomer" => $trTmpp[0]->nomer,
                    );
                    foreach ($sellerData as $kdata => $vdata) {
                        $trDatas[$kdata] = $vdata;
                        $trDatas["m_" . $kdata] = $vdata;
                        $trDatas["rtn_" . $kdata] = $vdata;
                    }
                    //endregion
                    break;
            }
//            arrPrintKuning($trDatas);
//            mati_disini();
            $addItems = array(
                "id_master" => "id_master",
                "_stepCode_placeID" => "_stepCode_placeID",
                "_stepCode_olehID" => "_stepCode_olehID",
                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                "_stepCode_customerID" => "_stepCode_customerID",
                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                "_stepCode" => "_stepCode",
                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                "_stepCode_supplierID" => "_stepCode_supplierID",
                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                "_step_1_nomer" => "_step_1_nomer",
                "_step_1_olehName" => "_step_1_olehName",
                "_step_2_nomer" => "_step_2_nomer",
                "_step_2_olehName" => "_step_2_olehName",
                "_step_3_nomer" => "_step_3_nomer",
                "_step_3_olehName" => "_step_3_olehName",
                "_step_4_nomer" => "_step_4_nomer",
                "_step_4_olehName" => "_step_4_olehName",
                "_step_5_nomer" => "_step_5_nomer",
                "_step_5_olehName" => "_step_5_olehName",

                "seller_id" => "seller_id",
                "seller_name" => "seller_name",
                "seller_nama" => "seller_nama",
                "sellerID" => "sellerID",
                "sellerName" => "sellerName",
                "sellerNama" => "sellerNama",

                "rtn_id_master" => "rtn_id_master",
                "rtn_master_nomer" => "rtn_master_nomer",
            );
            $items = array();
            $total_qty = 0;
            $total_jml = 0;
            foreach ($trRegResult as $trid => $regSpec) {
                foreach ($regSpec['items'] as $mkey => $mSpec) {
                    $items[$mkey] = $mSpec;
                    foreach ($addItems as $akey => $aval) {
                        $items[$mkey][$akey] = isset($trDatas[$aval]) ? $trDatas[$aval] : "";
                    }
                    $total_qty += $mSpec["qty"];
                    $total_jml += $mSpec["jml"];
                }
            }
            $trDatas["m_total_qty"] = $total_qty;
            $trDatas["m_total_jml"] = $total_jml;
            $trDatas["total_qty"] = $total_qty;
            $trDatas["total_jml"] = $total_jml;

            switch ($jenisTransaksi) {
                // return penjualan
                case "982r":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "982r" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "982g" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "982r" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "982g" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "982g" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
//                                "transaksi_step" => ".order",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "982r" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "982g" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "982r" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".1",
                            ),
                        );
                    }
                    break;
                case "982g":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "982g" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "982" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "982g" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".order",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "982" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array(
                            "loop" => array(
                                "982" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "982g" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "982" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "982g" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".rejected",
                                "step_reject" => ".2",
                            ),
                        );
                    }
                    break;
                case "982":
                    if ($transaksiTipeTimeline == "reguler") {
                        $sourceMain = array(
                            "loop" => array(
                                "982" => "m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "transaksi_id" => "id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetMain = array(
                            "loop" => array(
                                "982r" => "-m_nett1",
                            ),
                            "static" => array(
                                "cabang_id" => "m_placeID",
                                "cabang_nama" => "m_placeName",
                                "gudang_id" => "m_gudangID",
                                "gudang_nama" => "m_gudangName",
                                "rekening_nama" => "m_sourceJenisLabel",
                                "produk_qty" => "-m_total_qty",
                                "produk_nilai" => "m_harga",
                                "harga_bruto" => "m_harga",
                                "ppn_nilai" => "m_ppn",
                                "harga_netto" => "m_nett1",
                                "harga_nppn" => "m_nett2",
                                "diskon_nilai" => "m_disc",
                                "premi_nilai" => "m_premi",
                                "ongkir_nilai" => "m_shipping_service",
                                "extern_id" => "rtn_id_master",
                                "extern_nama" => "rtn_master_nomer",
                                "satuan" => "satuan",
                                "oleh_id" => "m_olehID",
                                "oleh_nama" => "m_olehName",
                                "seller_id" => "m_sellerID",
                                "seller_nama" => "m_sellerName",
                                "customer_id" => "m_pihakID",
                                "customer_nama" => "m_pihakName",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                "jenis" => "jenis",
                                "step_current" => "step_number",
                                "step_number" => "step_current",
                                "next_step_num" => "next_step_num",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "rel_target_jenis" => ".0",
                                "transaksi_tipe" => ".reguler",
                                "update_lap" => false,
                            ),
                        );
                        $sourceDetail = array(
                            "loop" => array(
                                "982" => "sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                                //------
//                "dtime_order" => "dtime",
//                "dtime_kirim" => "shippingDate",
//                "dtime_terima" => "",
                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "transaksi_step" => ".kirim",
                            ),
                        );
                        $targetDetail = array(
                            "loop" => array(
                                "982r" => "-sub_nett1",
                            ),
                            "static" => array(
                                "rekening_nama" => "sourceJenisLabel",
                                "cabang_id" => "placeID",
                                "gudang_id" => "gudangID",
                                "gudang_nama" => "gudangName",
                                "cabang_nama" => "placeName",
                                "produk_qty" => "-qty",
                                "produk_nilai" => "harga",
                                "harga_bruto" => "harga",
                                "ppn_nilai" => "ppn",
                                "harga_netto" => "nett1",
                                "harga_nppn" => "nett2",
                                "diskon_nilai" => "disc",
                                "premi_nilai" => "premi",
                                "extern_id" => "id",
                                "extern_nama" => "name",
                                "extern2_id" => "rtn_id_master",
                                "extern2_nama" => "rtn_master_nomer",
                                "produk_kode" => "code",
                                "produk_part" => "no_part",
                                "produk_label" => "label",
                                "produk_jenis" => "jenis",
                                "produk_satuan" => "satuan",
                                "satuan" => "satuan",
                                "oleh_id" => "olehID",
                                "oleh_nama" => "olehName",
                                "seller_id" => "sellerID",
                                "seller_nama" => "sellerName",
                                "customer_id" => "pihakID",
                                "customer_nama" => "pihakName",
//                            "transaksi_id" => "transaksi_id",
                                "master_id" => "rtn_id_master",
                                "master_jenis" => "jenis_master",
                                //------
                                "_stepCode_placeID" => "_stepCode_placeID",
                                "_stepCode_olehID" => "_stepCode_olehID",
                                "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
                                "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
                                "_stepCode_customerID" => "_stepCode_customerID",
                                "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
                                "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
                                "_stepCode" => "_stepCode",
                                "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
                                "_stepCode_supplierID" => "_stepCode_supplierID",
                                "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
                                "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",

                                "_step_1_nomer" => "_step_1_nomer",
                                "_step_1_olehName" => "_step_1_olehName",
                                "_step_2_nomer" => "_step_2_nomer",
                                "_step_2_olehName" => "_step_2_olehName",
                                "_step_3_nomer" => "_step_3_nomer",
                                "_step_3_olehName" => "_step_3_olehName",
                                "_step_4_nomer" => "_step_4_nomer",
                                "_step_4_olehName" => "_step_4_olehName",
                                "_step_5_nomer" => "_step_5_nomer",
                                "_step_5_olehName" => "_step_5_olehName",
                                "transaksi_tipe" => ".reguler",
                                "update_lap" => false,
                            ),
                        );
                    }
                    if ($transaksiTipeTimeline == "reject") {
                        $sourceMain = array();
                        $sourceDetail = array();
                        $targetMain = array();
                        $targetDetail = array();
                    }
                    break;

                default:

                    mati_disini(":: $jenisTransaksi ::");

                    break;
            }
            //------------------------------------

            $mainData = $trDatas;
            $gateSource = array();
            $gateTarget = array();
            $gateSourceKonsolidasian = array();
            $gateSourceSales = array();
            $gateSourceCabang = array();
            $gateSourceCabangSales = array();
            $gateSourceDetail = array();
            $gateTargetDetail = array();
            //------------------------------------
            if (sizeof($sourceMain) > 0) {
                foreach ($sourceMain['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateSource['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateSource['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($sourceMain['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateSource['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateSource['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                            $gateSource['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateSource['static']['transaksi_id'] = $mainData['id'];
                $gateSource['static']['fulldate'] = $mainData['fulldate'];
                $gateSource['static']['dtime'] = $mainData['dtime'];
            }
            //------------------------------------
            if (sizeof($targetMain) > 0) {
                foreach ($targetMain['loop'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateTarget['loop'][$key] = "-" . $newVal_result;
                    }
                    else {
                        $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                        $gateTarget['loop'][$key] = $newVal_result;
                    }
                }
                foreach ($targetMain['static'] as $key => $val) {
                    if (substr($val, 0, 1) == "-") {
                        $newVal = str_replace("-", "", $val);
                        $newVal_result = isset($mainData[$newVal]) ? $mainData[$newVal] : 0;
                        $gateTarget['static'][$key] = "-" . $newVal_result;
                    }
                    else {
                        if (substr($val, 0, 1) == ".") {
                            $newVal = str_replace(".", "", $val);
                            $newVal_result = $newVal;
                            $gateTarget['static'][$key] = $newVal_result;
                        }
                        else {
                            $newVal_result = isset($mainData[$val]) ? $mainData[$val] : 0;
                            $gateTarget['static'][$key] = $newVal_result;
                        }
                    }
                }
                $gateTarget['static']['transaksi_id'] = $mainData['id'];
                $gateTarget['static']['fulldate'] = $mainData['fulldate'];
                $gateTarget['static']['dtime'] = $mainData['dtime'];
            }
            //------------------------------------
            foreach ($items as $ii => $iiSpec) {
                if (sizeof($sourceDetail) > 0) {
                    foreach ($sourceDetail['loop'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateSourceDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateSourceDetail[$ii]['loop'][$key] = $newVal_result;
                        }
                    }
                    foreach ($sourceDetail['static'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateSourceDetail[$ii]['static'][$key] = "-" . $newVal_result;
                        }
                        else {
                            if (substr($val, 0, 1) == ".") {
                                $newVal = str_replace(".", "", $val);
                                $newVal_result = $newVal;
                                $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                            }
                            else {
                                $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                                $gateSourceDetail[$ii]['static'][$key] = $newVal_result;
                            }
                        }
                    }
                    $gateSourceDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                    $gateSourceDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                    $gateSourceDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                }
                if (sizeof($targetDetail) > 0) {
                    foreach ($targetDetail['loop'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateTargetDetail[$ii]['loop'][$key] = "-" . $newVal_result;
                        }
                        else {
                            $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                            $gateTargetDetail[$ii]['loop'][$key] = $newVal_result;
                        }
                    }
                    foreach ($targetDetail['static'] as $key => $val) {
                        if (substr($val, 0, 1) == "-") {
                            $newVal = str_replace("-", "", $val);
                            $newVal_result = isset($iiSpec[$newVal]) ? $iiSpec[$newVal] : 0;
                            $gateTargetDetail[$ii]['static'][$key] = "-" . $newVal_result;
                        }
                        else {
                            if (substr($val, 0, 1) == ".") {
                                $newVal = str_replace(".", "", $val);
                                $newVal_result = $newVal;
                                $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                            }
                            else {
                                $newVal_result = isset($iiSpec[$val]) ? $iiSpec[$val] : 0;
                                $gateTargetDetail[$ii]['static'][$key] = $newVal_result;
                            }
                        }
                    }
                    $gateTargetDetail[$ii]['static']['transaksi_id'] = $mainData['id'];
                    $gateTargetDetail[$ii]['static']['fulldate'] = $mainData['fulldate'];
                    $gateTargetDetail[$ii]['static']['dtime'] = $mainData['dtime'];
                }

            }
            //------------------------------------


            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region konsolidasian
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesReturn();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {
                    $rt = New ComRekeningTransaksiSalesReturn();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {
                    $rt = New ComRekeningTransaksiSalesReturnPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region sales cabang
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesReturnCabang();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabang();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabangPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabangPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman
                if (sizeof($gateSource) > 0) {
                    $rt = New ComRekeningTransaksiSalesReturnSalesman();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesman();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesmanPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesmanPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                if (sizeof($gateSource) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabangSalesman();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabangSalesman();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabangSalesmanPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnCabangSalesmanPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                //region salesman cabang
                if (sizeof($gateSource) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesmanTransaksi();
                    $rt->pair($gateSource);
                    $rt->exec();
                }
                if (sizeof($gateTarget) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesmanTransaksi();
                    $rt->pair($gateTarget);
                    $rt->exec();
                }
                if (sizeof($gateSourceDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesmanTransaksiPembantu();
                    $rt->pair($gateSourceDetail);
                    $rt->exec();
                }
                if (sizeof($gateTargetDetail) > 0) {

                    $rt = New ComRekeningTransaksiSalesReturnSalesmanTransaksiPembantu();
                    $rt->pair($gateTargetDetail);
                    $rt->exec();
                }
                //endregion
            }


            $cts = New ComRekeningTransaksiSalesReturn();
            $cts->addFilter("transaksi_id='$transaksiID'");
            $ctsTmp = $cts->lookupAll()->result();
            if (sizeof($ctsTmp) == 0) {
                mati_disini("transaksi tidak masuk setelah cli dijalankan. silahkan diperiksa lagi. " . __LINE__);
            }

            $ctss = New ComRekeningTransaksiSalesReturnSalesman();
            $ctss->addFilter("transaksi_id='$transaksiID'");
            $ctssTmp = $ctss->lookupAll()->result();
            if (sizeof($ctssTmp) == 0) {
                mati_disini("transaksi tidak masuk setelah cli dijalankan. silahkan diperiksa lagi. " . __LINE__);
            }


            $endtime = microtime(true); // Bottom of page
            $val = $endtime - $starttime;

            // sudah selesai running maka status diupdate menjadi: 1
            $tbl = "z_tabel_timeline";
            $updateData = array(
                "status" => "1",
                "waktu" => $val,
            );
            $updateWhere = array(
                "id" => $tblID
            );
            $this->db->where($updateWhere);
            $this->db->update($tbl, $updateData);
            showLast_query("orange");
        }
        else {
            cekMerah("-- HABIS --");

//            $tr = new MdlTransaksi();
//            $updateData = array(
////                "non_akunting_2" => "1",
//                "non_akunting" => "1",
//            );
//            $updateWhere = array(
//                "id" => $master_iddd
//            );
//            $tr->updateData($updateWhere, $updateData);
//            showLast_query("orange");

        }


        if ($stopCommit == true) {

            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }


    // memasukkan ke tabel z_transaksi_timeline
    public function cekSo()
    {
//        header("refresh:300");
//        $stopCommit = true;
        $stopCommit = false;


        $this->load->model("MdlTransaksi");
        $arrJenis = array(
            "582so",
            "582spd",
            "382so",
            "382spd",
//            "1982g",
            "588spo",
            "588so",
            "7499",
        );
        $arrJenisAll = array(
            "582spo",
            "582so",
            "582spd",
            "382spo",
            "382so",
            "382spd",
            "1982g",
            //---
            "588spo",
            "588so",
            "7499",
        );
        $arrReferenceJenisBatal = array(
            "582spd", "7499"
        );
        $tahun_lalu = "2022";
        $tahun = "2023";


        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            $tr = New MdlTransaksi();
            $tr->addFilter("jenis in ('" . implode("','", $arrJenis) . "')");
            $tr->addFilter("year(dtime)='$tahun'");
            $trTmp = $tr->lookupAll()->result();
            cekHere("mencari 582so dan 582spd di tahun 2022 dengan 582spo tahun 2021");
//        showLast_query("biru");


            //region 582spo, 582so, 582spd, 382spo, 382so, 382spd tahun 2021
            $arrTransIDs = array();
            $arrTopIDs = array();
            foreach ($trTmp as $trTmpSpec) {
                $arrTopIDs[] = $trTmpSpec->id_master;
            }

            $tr = New MdlTransaksi();
            $tr->addFilter("year(dtime)='$tahun_lalu'");
//        $tr->addFilter("id_master in ('" . implode("','", $arrTopIDs) . "')");
//        $this->db->where_in("jenis", $arrJenisAll);
            $this->db->where("jenis='582spo'");
//        $this->db->where("jenis in ('582spo', '1982g')");
            $trTmp = $tr->lookupAll()->result();
//        showLast_query("hijau");

            $arrMasterIDs_lalu = array();
            $arrData_lalu = array();
            foreach ($trTmp as $trTmpSpec) {
                $master_id = $trTmpSpec->id_master;
                $jenis = $trTmpSpec->jenis;
                $trash_4 = $trTmpSpec->trash_4;
                $cancel_dtime = $trTmpSpec->cancel_dtime;
                $arrData_lalu[$master_id] = $master_id;
//            if (in_array($jenis, $arrJenisAll)) {
//                $arrMasterIDs_lalu[] = array(
//                    "id" => $trTmpSpec->id,
//                    "jenis" => $trTmpSpec->jenis,
//                    "dtime" => $trTmpSpec->dtime,
//                    "tipe" => "reguler",
//                );
//
//                if (($trash_4 == 1) && ($cancel_dtime != NULL)) {
//                    $arrMasterIDs_lalu[] = array(
//                        "id" => $trTmpSpec->id,
//                        "jenis" => $trTmpSpec->jenis,
//                        "dtime" => $trTmpSpec->cancel_dtime,
//                        "tipe" => "reject",
//                    );
//                }
//            }
            }

            $tr = New MdlTransaksi();
            $tr->addFilter("year(dtime)='$tahun_lalu'");
            $tr->addFilter("id_master in ('" . implode("','", $arrData_lalu) . "')");
//        $this->db->where_in("jenis", $arrJenisAll);
            $trTmp = $tr->lookupAll()->result();
            showLast_query("hijau");
            foreach ($trTmp as $trTmpSpec) {
                $master_id = $trTmpSpec->id_master;
                $jenis = $trTmpSpec->jenis;
                $trash_4 = $trTmpSpec->trash_4;
                $cancel_dtime = $trTmpSpec->cancel_dtime;

                $arrTransIDs[$trTmpSpec->id] = $trTmpSpec->id;

                if (in_array($jenis, $arrJenisAll)) {
//                    $arrMasterIDs_lalu[] = array(
//                        "master_id" => $master_id,
//                        "id" => $trTmpSpec->id,
//                        "jenis" => $trTmpSpec->jenis,
//                        "dtime" => $trTmpSpec->dtime,
//                        "tipe" => "reguler",
//                    );

                    if (($trash_4 == 1) && ($cancel_dtime != NULL)) {
                        $cancel_dtime_ex = explode("-", $cancel_dtime);
                        $cancel_dtime_ex_thn = $cancel_dtime_ex[0];
                        if ($cancel_dtime_ex_thn == $tahun) {
                            $arrMasterIDs_lalu[] = array(
                                "master_id" => $master_id,
                                "id" => $trTmpSpec->id,
                                "jenis" => $trTmpSpec->jenis,
                                "dtime" => $trTmpSpec->cancel_dtime,
                                "tipe" => "reject",
                            );
                        }
                    }
                }
            }
            //endregion

        }
//arrprintPink($arrMasterIDs_lalu);
//mati_disini();

        //region 582spo, 582so, 582spd, 382spo, 382so, 382spd TAHUN 2022
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis in ('" . implode("','", $arrJenisAll) . "')");
        $tr->addFilter("year(dtime)='$tahun'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        foreach ($trTmp as $trTmpSpec) {
            $master_id = $trTmpSpec->id_master;
            $jenis = $trTmpSpec->jenis;
            $trash_4 = $trTmpSpec->trash_4;
            $cancel_dtime = $trTmpSpec->cancel_dtime;
            $arrTransIDs[$trTmpSpec->id] = $trTmpSpec->id;
            if (in_array($jenis, $arrJenisAll)) {
                $arrMasterIDs_lalu[] = array(
                    "master_id" => $master_id,
                    "id" => $trTmpSpec->id,
                    "jenis" => $trTmpSpec->jenis,
                    "dtime" => $trTmpSpec->dtime,
                    "tipe" => "reguler",
                );

                if (($trash_4 == 1) && ($cancel_dtime != NULL)) {
                    $arrMasterIDs_lalu[] = array(
                        "master_id" => $master_id,
                        "id" => $trTmpSpec->id,
                        "jenis" => $trTmpSpec->jenis,
                        "dtime" => $trTmpSpec->cancel_dtime,
                        "tipe" => "reject",
                    );
                }
            }
        }
        //endregion


        // region fullfill
        $arrTrFullfill = array();
        $arrTrIDsFullfill = array();
        $arrTrIDsFullfill_ref = array();
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='1982g'");
        $trTmp = $tr->lookupAll()->result();
        foreach ($trTmp as $trTmpSpec) {
            $arrTrFullfill[$trTmpSpec->id] = array(
                "dtime" => $trTmpSpec->dtime,
            );
            $arrTrIDsFullfill[$trTmpSpec->id] = $trTmpSpec->id;
        }
        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields("transaksi_id, main");
        $tr->addFilter("transaksi_id in ('" . implode("','", $arrTrIDsFullfill) . "')");
        $trReg = $tr->lookupDataRegistries()->result();
//        arrPrintWebs($trReg);
        foreach ($trReg as $regSpec) {
            $main = blobDecode($regSpec->main);
            if (!is_array($main)) {
                $main = blobDecode($main);
            }
            $ref_so = isset($main['transaksiDatas']) ? $main['transaksiDatas'] : 0;
            $arrTrIDsFullfill_ref[$regSpec->transaksi_id] = $ref_so;
        }
//arrPrintKuning($arrTrIDsFullfill_ref);

//        foreach ($arrTrIDsFullfill_ref as $tr_fullfill => $ref_soa) {
//            if (in_array($ref_soa, $arrTransIDs)) {
//                $arrMasterIDs_lalu[] = array(
//                    "master_id" => $ref_soa,
//                    "id" => $tr_fullfill,
//                    "jenis" => "1982g",
//                    "dtime" => $arrTrFullfill[$tr_fullfill]["dtime"],
//                    "tipe" => "reguler",
//                );
//            }
//        }

        // endregion fullfill

//arrPrintPink($arrMasterIDs_lalu);
//mati_disini(__LINE__);

        //region return penjualan
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis_master='982'");
//        $tr->addFilter("year(dtime)='2022'");
        $tr->addFilter("year(dtime)='" . date("Y") . "'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        $arrDataReturn = array();
        $arrDataReturnIds = array();
        foreach ($trTmp as $trTmpSpec) {
            $arrDataReturn[$trTmpSpec->id] = array(
                "dtime" => $trTmpSpec->dtime,
                "id" => $trTmpSpec->id,
                "jenis" => $trTmpSpec->jenis,
            );
            $arrDataReturnIds[$trTmpSpec->id] = $trTmpSpec->id;

        }
        $tr->setFilters(array());
        $tr->setJointSelectFields("transaksi_id, main");
        $tr->addFilter("transaksi_id in ('" . implode("','", $arrDataReturnIds) . "')");
        $trReg = $tr->lookupDataRegistries()->result();
        showLast_query("kuning");
        $trRegResult = array();
        foreach ($trReg as $regSpec) {
            $main_reg = blobDecode($regSpec->main);
            if (!is_array($main_reg)) {
                cekHere("main bukan array");
                $main_reg = blobDecode($main_reg);
            }
            $ref_582spd = isset($main_reg['referenceID']) ? $main_reg['referenceID'] : 0;
            $trRegResult[$regSpec->transaksi_id] = $ref_582spd;
        }
        $tr = New MdlTransaksi();
        $tr->addFilter("id in ('" . implode("','", $trRegResult) . "')");
        $trTmp = $tr->lookupAll()->result();
        $arrMasterId_582spd = array();
        foreach ($trTmp as $trTmpSpec) {
            $arrMasterId_582spd[$trTmpSpec->id] = $trTmpSpec->id_master;
        }

        $arrSellerId_582spd = array();
        foreach ($arrMasterId_582spd as $_582spdid => $_582spo) {
            $tr = New MdlTransaksi();
            $tr->addFilter("id=$_582spo");
            $trTmp = $tr->lookupAll()->result();
            $arrSellerId_582spd[$_582spdid] = array(
                "seller_id" => $trTmp[0]->oleh_id,
                "seller_nama" => $trTmp[0]->oleh_nama,
                "master_id" => $_582spo,
            );
        }
        $arrSellerId_return = array();
        foreach ($trRegResult as $retId => $spd) {
            $arrSellerId_return[$retId] = isset($arrSellerId_582spd[$spd]) ? $arrSellerId_582spd[$spd] : array();
        }
//        arrPrint($trRegResult);
//        arrPrintWebs($arrMasterId_582spd);
        //endregion


        // region pembatalan 582spd
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis_master='9912'");
//        $tr->addFilter("year(dtime)='2022'");
        $tr->addFilter("year(dtime)='" . date("Y") . "'");
//        $tr->addFilter("reference_jenis='582spd'");
        $tr->addFilter("reference_jenis in ('" . implode("','", $arrReferenceJenisBatal) . "')");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        $arrDataBatal = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $spec) {
                $master_id = $spec->reference_id_top;
                $tr = New MdlTransaksi();
                $tr->addFilter("id=$master_id");
                $tmpp = $tr->lookupAll()->result();
                $seller_id = $tmpp[0]->oleh_id;
                $seller_nama = $tmpp[0]->oleh_nama;

                $arrDataBatal[$spec->id] = array(
                    "id" => $spec->id,
                    "dtime" => $spec->dtime,
                    "jenis" => $spec->jenis,
                    "seller_id" => $seller_id,
                    "seller_nama" => $seller_nama,
                    "master_id" => $master_id,
                );
            }
        }
        // endregion pembatalan 582spd


        $this->db->trans_start();


        $no_i = 0;
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            foreach ($arrMasterIDs_lalu as $spec) {
//            arrPrintKuning($spec);
                $data = array(
                    "status" => 0,
                    "dtime" => $spec['dtime'],
                    "transaksi_id" => $spec['id'],
                    "master_id" => $spec['master_id'],
                    "jenis" => $spec['jenis'],
                    "tipe" => $spec['tipe'],

                );
                $tbl = "z_tabel_timeline";
//                $this->db->insert($tbl, $data);
//            showLast_query("hijau");
                $arrayWhere = array(
                    "dtime" => $spec['dtime'],
                    "transaksi_id" => $spec['id'],
                    "master_id" => $spec['master_id'],
                    "jenis" => $spec['jenis'],
                    "tipe" => $spec['tipe'],
                );
//                    $this->db->select();
                $this->db->where($arrayWhere);
                $getResult = $this->db->get($tbl);
//                showLast_query("biru");
//                cekHere(sizeof($getResult->result()));
                //----
                if (sizeof($getResult->result()) == 0) {
                    $no_i++;
//                    cekHere("insert baru");
                    $this->db->insert($tbl, $data);
                    showLast_query("hijau");
                    cekHijau($no_i);
                }
                else {
//                    cekKuning("tidak insert penjualan");
                }
            }
        }
        else {
            cekMerah("penjualan kosong");
        }


        // RETURN PENJUALAN
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if (sizeof($arrDataReturn) > 0) {
                foreach ($arrDataReturn as $trid_return => $spec) {
                    $data = array(
                        "status" => 0,
                        "dtime" => $spec['dtime'],
                        "transaksi_id" => $spec['id'],
                        "master_id" => $arrSellerId_return[$trid_return]["master_id"],
                        "jenis" => $spec['jenis'],
                        "tipe" => "reguler",
                        "seller_id" => $arrSellerId_return[$trid_return]["seller_id"],
                        "seller_nama" => $arrSellerId_return[$trid_return]["seller_nama"],
                    );
//                    arrPrintHijau($data);
                    $tbl = "z_tabel_timeline";
//                    $this->db->insert($tbl, $data);
//----
                    $arrayWhere = array(
                        "dtime" => $spec['dtime'],
                        "transaksi_id" => $spec['id'],
                        "master_id" => $arrSellerId_return[$trid_return]["master_id"],
                        "jenis" => $spec['jenis'],
                        "tipe" => "reguler",
                    );
//                    $this->db->select();
                    $this->db->where($arrayWhere);
                    $getResult = $this->db->get($tbl);
//                    showLast_query("biru");
                    //----
                    if (sizeof($getResult->result()) == 0) {
//                        cekHere("insert baru");
                        $this->db->insert($tbl, $data);
                        showLast_query("kuning");

                    }
                    else {
                        cekKuning("tidak insert return penjualan");
                    }

                }
            }
        }
        else {
            cekMerah("return penjualan kosong");
        }


        // PEMBATALAN
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if (sizeof($arrDataBatal) > 0) {
                foreach ($arrDataBatal as $trid_return => $spec) {
                    $data = array(
                        "status" => 0,
                        "dtime" => $spec['dtime'],
                        "transaksi_id" => $spec['id'],
                        "master_id" => $spec["master_id"],
                        "jenis" => $spec['jenis'],
                        "tipe" => "reguler",
                        "seller_id" => $spec["seller_id"],
                        "seller_nama" => $spec["seller_nama"],
                    );
//                    arrPrintHijau($data);
                    $tbl = "z_tabel_timeline";

                    //----
                    $arrayWhere = array(
                        "dtime" => $spec['dtime'],
                        "transaksi_id" => $spec['id'],
                        "master_id" => $spec["master_id"],
                        "jenis" => $spec['jenis'],
                        "tipe" => "reguler",
                    );
//                    $this->db->select();
                    $this->db->where($arrayWhere);
                    $getResult = $this->db->get($tbl);
//                    showLast_query("biru");
                    //----
                    if (sizeof($getResult->result()) == 0) {
//                        cekHere("insert baru");
                        $this->db->insert($tbl, $data);
                        showLast_query("merah");

                    }
                    else {
//                        cekKuning("tidak insert pembatalan");
                    }

                }
            }
        }
        else {
            cekMerah("pembatalan kosong");
        }


        if ($stopCommit == true) {

            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");
    }

    public function cekSoUpdate()
    {
        $this->load->model("MdlTransaksi");


        $tbl = "z_tabel_timeline";
        $this->db->select("id, transaksi_id, jenis, dtime, tipe, seller_id, master_id");
        $this->db->order_by('dtime', 'ASC');
        $arrayWhere = array(
            "seller_id" => "0"
        );
        $this->db->where($arrayWhere);
        $this->db->limit(500);
        $result = $this->db->get($tbl)->result();
        showLast_query("kuning");
        $transaksiIDs = array();
        foreach ($result as $spec) {
            $transaksiIDs[] = $spec->master_id;
        }


        $this->db->trans_start();


        //-------------------------
        $tr = new MdlTransaksi();
        $tr->addFilter("id in ('" . implode("','", $transaksiIDs) . "')");
        $tmpHist = $tr->lookupAll()->result();
        foreach ($tmpHist as $tmpHistSpec) {
            $oleh_id = $tmpHistSpec->oleh_id;
            $oleh_nama = $tmpHistSpec->oleh_nama;
            $id = $tmpHistSpec->id;
            $id_master = $tmpHistSpec->id_master;

            $tr = new MdlTransaksi();
            $tr->addFilter("id=$id_master");
            $subTmpHist = $tr->lookupAll()->result();
            $oleh_id = $subTmpHist[0]->oleh_id;
            $oleh_nama = $subTmpHist[0]->oleh_nama;


            $tbl = "z_tabel_timeline";
            $updateData = array(
                "seller_id" => $oleh_id,
                "seller_nama" => $oleh_nama,
            );
            $updateWhere = array(
                "master_id" => $id
            );
            $this->db->where($updateWhere);
            $this->db->update($tbl, $updateData);
            showLast_query("kuning");
        }


        mati_disini(" OHOOOO belon comit @" . __LINE__);

        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    //endregion


    public function generateCounters()
    {
        header("refresh:1");


        $this->load->model("MdlTransaksi");
        $this->load->model("CustomCounter");
        $jenisTr = "";
//        $trID = "12745";
        $jenisMaster = "466";
        $company = 100;// contoh company id 100
//        $modul = "110";// contoh modul id 110
//        $subModul = "111";// contoh submodul id 111

        //---------------------------------------------

//        $tr = New MdlTransaksi();
//        $tr->addFilter("id_master>'0'");
//        $trTmpAll = $tr->lookupAll()->result();
//        $arrTotalData = array();
//        $arrWaitData = array();
//        $arrDoneData = array();
//        foreach ($trTmpAll as $allSpec) {
//            $arrTotalData[] = 1;
//            if ($allSpec->gen_counter == "0") {
//                $arrWaitData[] = 1;
//            }
//            if ($allSpec->gen_counter == "1") {
//                $arrDoneData[] = 1;
//            }
//        }
//        $totalTransaksi = sizeof($arrTotalData);
//        $totalWaitTransaksi = sizeof($arrWaitData);
//        $totalDoneTransaksi = sizeof($arrDoneData);
//        $persenDone = ($totalDoneTransaksi / $totalTransaksi) * 100;
//        $persenWait = ($totalWaitTransaksi / $totalTransaksi) * 100;
//        cekUngu("TOTAL: $totalTransaksi, WAITING: $totalWaitTransaksi, DONE: $totalDoneTransaksi");
//        cekUngu("WAITING: " . number_format($persenWait, "2", ",", ".") . ", DONE: " . number_format($persenDone, "2", ",", ".") . "");

        //---------------------------------------------


        // tabel transaksi
        $tr = New MdlTransaksi();
        $tr->addFilter("id_master>'0'");
        $tr->addFilter("gen_counter='0'");
        $tr->setSortBy(array("mode" => "ASC", "kolom" => "id"));
        $this->db->limit(1);
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");


        if (sizeof($trTmp) > 0) {
//            arrPrint($trTmp);
            cekHitam("transaksi ID: " . $trTmp[0]->id . " || date: " . $trTmp[0]->dtime);
//            cekHere($this->masterConfigUi[$trTmp[0]->jenis_master]);
//mati_disini();
            $transaksiID = $trTmp[0]->id;
            $transaksiNomer = $trTmp[0]->nomer;
            $transaksiJenis = $trTmp[0]->jenis;
            $supplierID = $trTmp[0]->suppliers_id;
            $supplierName = $trTmp[0]->suppliers_nama;
            $customerID = $trTmp[0]->customers_id;
            $customerName = $trTmp[0]->customers_nama;
            $olehID = $trTmp[0]->oleh_id;
            $olehName = $trTmp[0]->oleh_nama;
            $cabangID = $trTmp[0]->cabang_id;
            $cabangName = $trTmp[0]->cabang_nama;
            $cabang2ID = $trTmp[0]->cabang2_id;
            $cabang2Name = $trTmp[0]->cabang2_nama;
            $gudangID = $trTmp[0]->gudang_id;
            $gudangName = $trTmp[0]->gudang_nama;
            $gudang2ID = $trTmp[0]->gudang2_id;
            $gudang2Name = $trTmp[0]->gudang2_nama;

            //---------------------------------------
            $tr = New MdlTransaksi();
            $detail = $tr->lookupDetailTransaksiNoJenis($transaksiID)->result();
//            arrPrintPink($detail);
            $arrCoaCodeTransaksiData = array();
            if (sizeof($detail) > 0) {
                foreach ($detail as $detailSpec) {

                    switch ($detailSpec->produk_jenis) {
                        case "produk":
                            $arrCoaCodeTransaksiData[$detailSpec->produk_id] = "";
                            break;
                        case "supplies":
                            $arrCoaCodeTransaksiData[$detailSpec->produk_id] = "";
                            break;
                    }
                }
            }
            //---------------------------------------


//            mati_disini();
            foreach ($trTmp[0] as $key => $val) {
                $$key = $val;
            }
            $arrTmpTr = (array)$trTmp[0];

            //region tabel registry
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id='$transaksiID'");
            $tr->setJointSelectFields(array("transaksi_id", "main", "items", "items2_sum", "items3", "items3_sum"));
            $trReg = $tr->lookupDataRegistries()->result();

            $regMain = blobDecode($trReg[0]->main);
            $regItems = blobDecode($trReg[0]->items);
            $regItems2_sum = blobDecode($trReg[0]->items2_sum);
            if (!is_array($regMain)) {
                $regMain = blobDecode($regMain);
            }
            if (!is_array($regItems)) {
                $regItems = blobDecode($regItems);
            }
            if (!is_array($regItems2_sum)) {
                $regItems2_sum = blobDecode($regItems2_sum);
            }
            //endregion

            foreach ($regMain as $mkey => $mval) {
                $$mkey = $mval;
            }
//
//            $counterDefineValues = array();
//            $counterDefineValuesItems = array();
//            $counterDefine = array(
//
////                "company",                           // 1
////                "company|jenis",                     // 2
////                "company|suppliers_id",              // 3
////                "company|customers_id",              // 4
////                "company|oleh_id",                   // 5
////                "company|seller_id",                   // 5
////                "company|cabang_id",                 // 6
////                "company|cabang2_id",                // 7
////                "company|gudang_id",                 // 8
////                "company|gudang2_id",                // 9
////                "company|jenis|suppliers_id",        // 10
////                "company|jenis|customers_id",        // 11
////                "company|jenis|oleh_id",             // 12
////                "company|jenis|seller_id",             // 13
////                "company|jenis|cabang_id",           // 14
////                "company|jenis|cabang2_id",          // 15
////                "company|jenis|gudang_id",           // 16
////                "company|jenis|gudang2_id",          // 17
////                "company|jenis|cabang_id|cabang2_id", // 18
////                "company|jenis|cabang_id|customers_id", // 18
////                "company|jenis|cabang_id|oleh_id",      // 19
////                "company|jenis|cabang_id|seller_id",    // 20
////                "company|jenis|cabang_id|gudang_id",    // 21
////                "company|jenis|cabang_id|gudang2_id",   // 22
////                "company|jenis|cabang_id|suppliers_id",   // 23
//                //-----------------------
//
////                "company",                           // 1
////                "company|jenisTr",                     // 2
////                "company|jenisTrMaster",                     // 2
////                "company|stepCode",                     // 2
////                "company|supplierID",              // 3
////                "company|customerID",              // 4
////                "company|olehID",                   // 5
////                "company|sellerID",                   // 5
////                "company|cabangID",                 // 6
////                "company|cabang2ID",                // 7
////                "company|gudangID",                 // 8
////                "company|gudang2ID",                // 9
////                "company|modul",                // 9
////                "company|subModul",                // 9
//
////                "company|jenisTr|jenisTrMaster",        // 10
////                "company|jenisTr|stepCode",        // 10
////                "company|jenisTr|supplierID",        // 10
////                "company|jenisTr|customerID",        // 11
////                "company|jenisTr|olehID",             // 12
////                "company|jenisTr|sellerID",             // 13
////                "company|jenisTr|cabangID",           // 14
////                "company|jenisTr|cabang2ID",          // 15
////                "company|jenisTr|gudangID",           // 16
////                "company|jenisTr|gudang2ID",          // 17
////                "company|jenisTr|modul",          // 17
////                "company|jenisTr|subModul",          // 17
////
////                "company|jenisTr|cabangID|jenisTrMaster", // 18
////                "company|jenisTr|cabangID|stepCode", // 18
////                "company|jenisTr|cabangID|cabang2ID", // 18
////                "company|jenisTr|cabangID|customerID", // 18
////                "company|jenisTr|cabangID|olehID",      // 19
////                "company|jenisTr|cabangID|sellerID",    // 20
////                "company|jenisTr|cabangID|gudangID",    // 21
////                "company|jenisTr|cabangID|gudang2ID",   // 22
////                "company|jenisTr|cabangID|supplierID",   // 23
////                "company|jenisTr|cabangID|modul",   // 23
////                "company|jenisTr|cabangID|subModul",   // 23
////
////                "company|jenisTr|cabangID|stepCode|cabang2ID", // 18
////                "company|jenisTr|cabangID|stepCode|customerID", // 18
////                "company|jenisTr|cabangID|stepCode|olehID",      // 19
////                "company|jenisTr|cabangID|stepCode|sellerID",    // 20
////                "company|jenisTr|cabangID|stepCode|gudangID",    // 21
////                "company|jenisTr|cabangID|stepCode|gudang2ID",   // 22
////                "company|jenisTr|cabangID|stepCode|supplierID",   // 23
////                "company|jenisTr|cabangID|stepCode|modul",   // 23
////                "company|jenisTr|cabangID|stepCode|subModul",   // 23
//
//                "company",                           // 1
//                "company|jenisTr",                     // 2
//                "company|jenisTrMaster",                     // 2
//                "company|stepCode",                     // 2
//                "company|supplierID",              // 3
//                "company|customerID",              // 4
//                "company|olehID",                   // 5
//                "company|sellerID",                   // 5
//                "company|cabangID",                 // 6
//                "company|cabang2ID",                // 7
//                "company|gudangID",                 // 8
//                "company|gudang2ID",                // 9
//                "company|modul",                // 9
//                "company|subModul",                // 9
//
//                "company|cabangID|jenisTr",                     // 2
//                "company|cabangID|jenisTrMaster",                     // 2
//                "company|cabangID|stepCode",                     // 2
//                "company|cabangID|supplierID",              // 3
//                "company|cabangID|customerID",              // 4
//                "company|cabangID|olehID",                   // 5
//                "company|cabangID|sellerID",                   // 5
//                "company|cabangID|cabangID",                 // 6
//                "company|cabangID|cabang2ID",                // 7
//                "company|cabangID|gudangID",                 // 8
//                "company|cabangID|gudang2ID",                // 9
//                "company|cabangID|modul",                // 9
//                "company|cabangID|subModul",                // 9
//
//                "company|cabangID|modul|jenisTr",                     // 2
//                "company|cabangID|modul|jenisTrMaster",                     // 2
//                "company|cabangID|modul|stepCode",                     // 2
//                "company|cabangID|modul|supplierID",              // 3
//                "company|cabangID|modul|customerID",              // 4
//                "company|cabangID|modul|olehID",                   // 5
//                "company|cabangID|modul|sellerID",                   // 5
//                "company|cabangID|modul|cabangID",                 // 6
//                "company|cabangID|modul|cabang2ID",                // 7
//                "company|cabangID|modul|gudangID",                 // 8
//                "company|cabangID|modul|gudang2ID",                // 9
//                "company|cabangID|modul|subModul",                // 9
//
//                "company|cabangID|modul|subModul|jenisTr",                     // 2
//                "company|cabangID|modul|subModul|jenisTrMaster",                     // 2
//                "company|cabangID|modul|subModul|stepCode",                     // 2
//                "company|cabangID|modul|subModul|supplierID",              // 3
//                "company|cabangID|modul|subModul|customerID",              // 4
//                "company|cabangID|modul|subModul|olehID",                   // 5
//                "company|cabangID|modul|subModul|sellerID",                   // 5
//                "company|cabangID|modul|subModul|cabangID",                 // 6
//                "company|cabangID|modul|subModul|cabang2ID",                // 7
//                "company|cabangID|modul|subModul|gudangID",                 // 8
//                "company|cabangID|modul|subModul|gudang2ID",                // 9
//
//                "company|cabangID|modul|subModul|jenisTr|jenisTrMaster",                     // 2
//                "company|cabangID|modul|subModul|jenisTr|stepCode",                     // 2
//                "company|cabangID|modul|subModul|jenisTr|supplierID",              // 3
//                "company|cabangID|modul|subModul|jenisTr|customerID",              // 4
//                "company|cabangID|modul|subModul|jenisTr|olehID",                   // 5
//                "company|cabangID|modul|subModul|jenisTr|sellerID",                   // 5
//                "company|cabangID|modul|subModul|jenisTr|cabangID",                 // 6
//                "company|cabangID|modul|subModul|jenisTr|cabang2ID",                // 7
//                "company|cabangID|modul|subModul|jenisTr|gudangID",                 // 8
//                "company|cabangID|modul|subModul|jenisTr|gudang2ID",                // 9
//
//                "company|cabangID|modul|subModul|jenisTr|stepCode|jenisTrMaster",                     // 2
//                "company|cabangID|modul|subModul|jenisTr|stepCode|supplierID",              // 3
//                "company|cabangID|modul|subModul|jenisTr|stepCode|customerID",              // 4
//                "company|cabangID|modul|subModul|jenisTr|stepCode|olehID",                   // 5
//                "company|cabangID|modul|subModul|jenisTr|stepCode|sellerID",                   // 5
//                "company|cabangID|modul|subModul|jenisTr|stepCode|cabangID",                 // 6
//                "company|cabangID|modul|subModul|jenisTr|stepCode|cabang2ID",                // 7
//                "company|cabangID|modul|subModul|jenisTr|stepCode|gudangID",                 // 8
//                "company|cabangID|modul|subModul|jenisTr|stepCode|gudang2ID",                // 9
//            );
//            // mesti bawa produk, rakitan, supplies, biaya
//            $counterDefineItems = array(
//                "company|item_id",           // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|jenis",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|cabang_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                "company|item_id|jenis|cabang_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//                //-----------------------
//
//            );
//
//            foreach ($counterDefine as $spec) {
//                $spec_ex = explode("|", $spec);
//                $hasil = "";
//                foreach ($spec_ex as $skey) {
//                    $skey_val = isset($$skey) ? $$skey : 0;
//                    if ($hasil == "") {
//                        $hasil = "$skey_val";
//                    }
//                    else {
//                        $hasil .= "|$skey_val";
//                    }
//                }
//
//                $ketemu = (strpos("$hasil, $hasil", "|0"));
//                if ($ketemu > 0) {
//
//                }
//                else {
//
//                    $counterDefineValues[$spec] = $hasil;
//                }
//
//            }
//
////            foreach($regItems as $iID => $iSpec){
////                foreach($iSpec as $ikey =>$ival){
////                    $$ikey = $ival;
////                }
////                $item_id = $iSpec['id'];
////
////
////                foreach($counterDefineItems as $spec){
////                    $spec_ex = explode("|", $spec);
////                    $hasil = "";
////                    foreach($spec_ex as $skey){
////                        $skey_val = isset($$skey) ? $$skey : 0;
////                        if($hasil == ""){
////                            $hasil = "$skey_val";
////                        }
////                        else{
////                            $hasil .= "|$skey_val";
////                        }
////                    }
////                    $counterDefineValuesItems[$item_id][$spec] = $hasil;
////                }
////
////
////            }
//
//
//            $arrKolomTabel = array();
//            foreach ($counterDefineValues as $kkey => $vval) {
//                $arrKolomTabel[] = str_replace("|", "_", $kkey);
//            }
//
////            arrPrintWebs($arrKolomTabel);
////            arrPrintWebs($counterDefineValues);
////            arrPrintPink($counterDefineValuesItems);
////            mati_disini();


            $this->db->trans_start();
            $pakai_ini = 0;
            if ($pakai_ini == 1) {

                $counterHasil = array();
                $counterHasil_insert = array();

                foreach ($counterDefineValues as $key => $val) {
                    $cc = New CustomCounter();
                    $urut = $cc->setCounterNumber($key, $val, "master", "0");

                    $counterHasil['main'][] = array(
                        "key" => $key,
                        "value" => $val,
                        "hasil" => $urut,
                    );

                    $kolom = str_replace("|", "_", $key);
                    $counterHasil_insert[$kolom] = $urut;
                }

                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    foreach ($counterDefineValuesItems as $iID => $iSpec) {
                        foreach ($iSpec as $key => $val) {

                            $cc = New CustomCounter();
                            $urut = $cc->setCounterNumber($key, $val, "detail", "produk");// tembah produk dulu

                            $counterHasil['detail'][$iID][] = array(
                                "key" => $key,
                                "value" => $val,
                                "hasil" => $urut,
                            );
                        }
                    }
                }


                $counterHasil_insert['suppliers_id'] = $supplierID;
                $counterHasil_insert['suppliers_nama'] = $supplierName;
                $counterHasil_insert['customers_id'] = $customerID;
                $counterHasil_insert['customers_nama'] = $customerName;
                $counterHasil_insert['oleh_id'] = $olehID;
                $counterHasil_insert['oleh_nama'] = $olehName;
                $counterHasil_insert['cabang_id'] = $cabangID;
                $counterHasil_insert['cabang_nama'] = $cabangName;
                $counterHasil_insert['cabang2_id'] = $cabang2ID;
                $counterHasil_insert['cabang2_nama'] = $cabang2Name;
                $counterHasil_insert['gudang_id'] = $gudangID;
                $counterHasil_insert['gudang_nama'] = $gudangName;
                $counterHasil_insert['gudang2_id'] = $gudang2ID;
                $counterHasil_insert['gudang2_nama'] = $gudang2Name;
                $counterHasil_insert['modul_id'] = $modul;
                $counterHasil_insert['subModul_id'] = $subModul;
                $counterHasil_insert['company_id'] = $company;
                $counterHasil_insert['transaksi_id'] = $trTmp[0]->id;
                $counterHasil_insert['id_master'] = $trTmp[0]->id_master;
                $counterHasil_insert['id_top'] = $trTmp[0]->id_top;
                $counterHasil_insert['jenis_master'] = $trTmp[0]->jenis_master;
                $counterHasil_insert['jenis_top'] = $trTmp[0]->jenis_top;
                $counterHasil_insert['jenis'] = $trTmp[0]->jenis;
                $counterHasil_insert['jenis_label'] = $trTmp[0]->jenis_label;
                $counterHasil_insert['nomer_top'] = $trTmp[0]->nomer_top;
                $counterHasil_insert['nomer'] = $trTmp[0]->nomer;
                $counterHasil_insert['dtime'] = $trTmp[0]->dtime;


            }


            //------------------------------
            cekHitam($arrTmpTr['jenis_master']);
            $cCode = "_TR_" . $arrTmpTr['jenis_master'];
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($cCode);
            $ccn->setJenisTr($arrTmpTr['jenis_master']);
            $ccn->setTransaksiGate($arrTmpTr);
            $ccn->setMainGate($regMain);
            $ccn->setItemsGate($regItems);
            $ccn->setItems2SumGate($regItems2_sum);
            $new_counter = $ccn->getCounterNumber();
            cekHitam("jenistr yang disett dari create " . $arrTmpTr['jenis_master']);

//arrPrintPink($new_counter);
//mati_disini("TEST...");
            //---MENULIS KE TABEL TRANSAKSI COUNTER----------------------------
//            $tbl_target = "z_transaksi_counter";
//            $this->db->insert($tbl_target, $new_counter['main']);
//            showlast_query("kuning");
            //-------------------------------


            $tr = New MdlTransaksi();
//            $data_update = array(
//                "gen_counter" => 1
//            );
//
            $new_counter['main']['gen_counter'] = 1;
            $where = array(
                "id" => $transaksiID
            );
            $tr->updateData($where, $new_counter['main']);
            showLast_query("orange");


//            mati_disini(" OHOOOO ");
            $this->db->trans_complete();

        }
        else {
            cekHijau("<h2>--- HABIS ---</h2>");
        }


    }

    public function resetCountersTransaksi()
    {
        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");

        $cc = New CustomCounter();
        $tabels = $cc->getTableName();
        arrPrintHijau($tabels);
        $cc->setTableNames($tabels["number2"]);
        $cc->addFilter("type='transaksi'");
        $ccTmp = $cc->lookupAll()->result();
        showLast_query("biru");
        cekHere(sizeof($ccTmp));
        $arrHasil = array();
        foreach ($ccTmp as $ccSpec) {
            $p_keys = $ccSpec->p_keys;
            if (substr($p_keys, 0, 16) != "company|rekening") {
                $arrHasil[$ccSpec->id] = $ccSpec->id;
//                break;
            }
        }
//        arrPrintKuning($arrHasil);

        $this->db->trans_start();

        foreach ($arrHasil as $id) {
            $cc = New CustomCounter();
            $tabels = $cc->getTableName();
            $cc->setTableNames($tabels["number2"]);
            $where = array(
                "id" => $id
            );
            $cc->deleteData($where);
            showLast_query("orange");
        }

        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $where = array(
            "gen_counter" => 1
        );
        $data = array(
            "gen_counter" => 0
        );
        $tr->updateData($where, $data);
        showLast_query("orange");


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("-- DONE --");
    }

    public function jejerCounterNota()
    {
        $this->load->model("MdlTransaksi");

        $jenisIn = array(
            "582spo",
            "582so",
            "582pkd",
            "582spd",
            "582"
        );

        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("link_id=0");
        $tr->addFilter("id_master>0");
        $tr->addFilter("jenis in ('" . implode("','", $jenisIn) . "')");
        $tr->addFilter("jejer_counter=0");
        $tr->setSortBy(array("mode" => "ASC", "kolom" => "id"));
        $this->db->limit(1);
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($trTmp) > 0) {
            arrPrintKuning($trTmp);
            $counter_gabungan_last = isset($trTmp[0]->counter_gabungan) ? $trTmp[0]->counter_gabungan : NULL;
            $nomer_nota = $trTmp[0]->nomer;
            $oleh_id = digit_5($trTmp[0]->oleh_id);
            $counter_stepcode = digit_5($trTmp[0]->_company_cabangID_stepCode);
            $counter_stepcode_olehId = digit_5($trTmp[0]->_company_cabangID_modul_subModul_jenisTr_stepCode_olehID);
            //-----
            $nomer_nota_baru = "$nomer_nota-$oleh_id.$counter_stepcode_olehId";
            if ($counter_gabungan_last != NULL) {
                $counter_gabungan = "$counter_gabungan_last-$oleh_id.$counter_stepcode_olehId";
            }
            else {
                $counter_gabungan = "$oleh_id.$counter_stepcode_olehId";
            }
            //-----

            //-----


            cekHijau("nomer baru gabungan: $nomer_nota_baru || $counter_gabungan");


            // 1 -- nota - sellerID|urut

            // 2 -- nota - sellerID|urut - appID|urut

            // 3 -- nota - sellerID|urut - appID|urut - appID_ppl|urut

            // 4 -- nota - sellerID|urut - appID|urut - appID_ppl|urut - appID_pl|urut

            // 5 -- nota - sellerID|urut - appID|urut - appID_ppl|urut - appID_pl|urut - appID_inv|urut


            $this->db->trans_start();


            mati_disini(" OHOOOO ");
            $this->db->trans_complete();
            cekHijau("-- DONE --");

        }

    }

    //------//------//------//------//------//------//------//
    public function generateSaldoEfisiensi()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $fdate = "2022-01-01";
        $rek_2 = "efisiensi biaya";
        $tabel_baru = "__rek_master__efisiensi_biaya";
        $cabangIDs = array("25");
        $cabangID = "25";
        $this->db->where(array("fulldate<" => "$fdate"));
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        cekHere(sizeof($crResult));
//        arrPrintHijau($crResult);
        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $cabang_id = $crResultSpec->cabang_id;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
            $date_harian = $fulldate;
            $date_bulanan = "$thn-$bln";
            $date_tahunan = "$thn";
            $date_forever = "1";

            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                // region forever
                if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever])) {
                    $arrCacheRekening['forever'][$cabang_id][$date_forever] = array();
                }
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["bln"] = $bln;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["thn"] = $thn;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["periode"] = "forever";
                if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_debet"])) {
                    $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_debet"] = 0;
                }
                if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_kredit"])) {
                    $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_kredit"] = 0;
                }
                if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_debet_periode"])) {
                    $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] = 0;
                }
                if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"])) {
                    $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] = 0;
                }
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_debet"] += $crResultSpec->debet;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_kredit"] += $crResultSpec->kredit;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] += $crResultSpec->debet;
                $arrCacheRekening['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] += $crResultSpec->kredit;


                // endregion forever
            }

            // region tahunan
            if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan])) {
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan] = array();
            }
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
            if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"])) {
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"])) {
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"])) {
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"])) {
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion tahunan

            // region bulanan
            if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan])) {
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan] = array();
            }
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
            if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"])) {
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"])) {
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"])) {
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"])) {
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion bulanan

            // region harian
            if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian])) {
                $arrCacheRekening['harian'][$cabang_id][$date_harian] = array();
            }
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening['harian'][$cabang_id][$date_harian]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening['harian'][$cabang_id][$date_harian]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["bln"] = $bln;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["thn"] = $thn;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["periode"] = "harian";
            if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_debet"])) {
                $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_kredit"])) {
                $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_debet_periode"])) {
                $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"])) {
                $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion harian

        }

//arrPrintHijau($arrCacheRekening['forever']);
//arrPrintPink($arrCacheRekening['tahunan']);

        $this->db->trans_start();

        foreach ($arrCacheRekening as $periode => $cacheSpec) {
            foreach ($cacheSpec as $cabang_id => $sSpec) {
                foreach ($sSpec as $subSpec) {

//                    arrPrintPink($subSpec);
                    $tgl = $subSpec['tgl'];
                    $bln = $subSpec['bln'];
                    $thn = $subSpec['thn'];
                    $rekening = $subSpec['rekening'];

                    $rc = New ComRekening();
                    $rc->setFilters(array());
                    switch ($periode) {
                        case "harian":
                            $rc->addFilter("tgl='$tgl'");
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "bulanan":
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "tahunan":
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "forever":

                            break;
                    }
                    $rc->addFilter("rekening='$rekening'");
                    $rc->addFilter("cabang_id='$cabang_id'");
                    $rc->addFilter("periode='$periode'");
                    $result = $rc->lookUpAll()->result();
                    showLast_query("biru");
                    if (sizeof($result) == 0) {
                        // insert baru
                        $anu = $rc->addData($subSpec);
                        showLast_query("hijau");
                    }
                    else {
                        // update yang sudah ada
                        $subSpecAdd = array(
                            "saldo_debet" => $subSpec['saldo_debet'],
                            "saldo_kredit" => $subSpec['saldo_kredit'],
                            "saldo_debet_periode" => $subSpec['saldo_debet_periode'],
                            "saldo_kredit_periode" => $subSpec['saldo_kredit_periode'],
                        );
                        $tbl_id = $result[0]->id;
                        $where = array("id" => $tbl_id);
                        $anu = $rc->updateData($where, $subSpecAdd);
                        showLast_query("orange");
                    }

                }
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");


    }

    public function generateSaldoEfisiensiPembantu()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuEfisiensiBiayaMain");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $fdate = "2022-01-01";
        $rek_2 = "efisiensi biaya";
        $tabel_baru = "__rek_pembantu_efisiensi__efisiensi_biaya";
        $tabel_cache_baru = "_rek_pembantu_efisiensi_cache";
        $cabangIDs = array("25");
        $cabangID = "25";
//        $this->db->where(array("fulldate<" => "$fdate"));
        $this->db->order_by("id", "ASC");
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        cekHere(sizeof($crResult));

        //------------
        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $extern_id = $crResultSpec->extern_id;
            $extern_nama = $crResultSpec->extern_nama;
            $cabang_id = $crResultSpec->cabang_id;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
            $date_harian = $fulldate;
            $date_bulanan = "$thn-$bln";
            $date_tahunan = "$thn";
            $date_forever = "1";

            // region forever
            if (!isset($arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever])) {
                $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever] = array();
            }
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["extern_id"] = $crResultSpec->extern_id;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["extern_nama"] = $crResultSpec->extern_nama;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["bln"] = $bln;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["thn"] = $thn;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["periode"] = "forever";
            if (!isset($arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_debet"])) {
                $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_kredit"])) {
                $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"])) {
                $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"])) {
                $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion forever

            // region tahunan
            if (!isset($arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan])) {
                $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan] = array();
            }
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["extern_id"] = $crResultSpec->extern_id;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["extern_nama"] = $crResultSpec->extern_nama;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
            if (!isset($arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"])) {
                $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"])) {
                $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"])) {
                $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"])) {
                $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion tahunan

            // region bulanan
            if (!isset($arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan])) {
                $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan] = array();
            }
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["extern_id"] = $crResultSpec->extern_id;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["extern_nama"] = $crResultSpec->extern_nama;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
            if (!isset($arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"])) {
                $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"])) {
                $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"])) {
                $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"])) {
                $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion bulanan

            // region harian
            if (!isset($arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian])) {
                $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian] = array();
            }
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["extern_id"] = $crResultSpec->extern_id;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["extern_nama"] = $crResultSpec->extern_nama;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["bln"] = $bln;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["thn"] = $thn;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["periode"] = "harian";
            if (!isset($arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_debet"])) {
                $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_debet"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_kredit"])) {
                $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_kredit"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"])) {
                $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] = 0;
            }
            if (!isset($arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"])) {
                $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] = 0;
            }
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_debet"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_kredit"] += $crResultSpec->kredit;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] += $crResultSpec->debet;
            $arrCacheRekening[$extern_id]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] += $crResultSpec->kredit;
            // endregion harian

//            break;
        }
//        arrPrintHijau($arrCacheRekening);
        //------------

        $this->db->trans_start();

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            $this->db->order_by("id", "ASC");
            $crResult_cache = $this->db->get($tabel_cache_baru)->result();
            foreach ($crResult_cache as $cacheSpec) {
                $id = $cacheSpec->id;
                $fulldate = $cacheSpec->fulldate;
                $fulldate_ex = explode("-", $fulldate);
                $thn = $fulldate_ex[0];
                $bln = $fulldate_ex[1];
                $tgl = $fulldate_ex[2];

                $data = array(
                    "tgl" => $tgl,
                    "bln" => $bln,
                    "thn" => $thn,
                );
                $this->db->where('id', $id);
                $this->db->update($tabel_cache_baru, $data);
                showLast_query("orange");
//                break;
            }
        }


        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            foreach ($arrCacheRekening as $extern_id => $crSpec) {
                foreach ($crSpec as $periode => $crSubSpec) {
                    foreach ($crSubSpec as $cabang_id => $ssSpec) {
                        foreach ($ssSpec as $subSpec) {
                            $tgl = $subSpec['tgl'];
                            $bln = $subSpec['bln'];
                            $thn = $subSpec['thn'];
                            $rekening = $subSpec['rekening'];


                            $crp = New ComRekeningPembantuEfisiensiBiayaMain();
                            $crp->setFilters(array());
                            switch ($periode) {
                                case "harian":
                                    $crp->addFilter("tgl='$tgl'");
                                    $crp->addFilter("bln='$bln'");
                                    $crp->addFilter("thn='$thn'");
                                    break;
                                case "bulanan":
                                    $crp->addFilter("bln='$bln'");
                                    $crp->addFilter("thn='$thn'");
                                    break;
                                case "tahunan":
                                    $crp->addFilter("thn='$thn'");
                                    break;
                                case "forever":

                                    break;
                            }
                            $crp->addFilter("rekening='$rekening'");
                            $crp->addFilter("cabang_id='$cabang_id'");
                            $crp->addFilter("periode='$periode'");
                            $crp->addFilter("extern_id='$extern_id'");
                            $result = $crp->lookUpAll()->result();
                            showLast_query("biru");
                            if (sizeof($result) == 0) {
                                cekHijau("------");
                                $anu = $crp->addData($subSpec);
                                showLast_query("hijau");
                            }
                            else {
                                $subSpecAdd = array(
                                    "saldo_debet" => $subSpec['saldo_debet'],
                                    "saldo_kredit" => $subSpec['saldo_kredit'],
                                    "saldo_debet_periode" => $subSpec['saldo_debet_periode'],
                                    "saldo_kredit_periode" => $subSpec['saldo_kredit_periode'],
                                );
                                $tbl_id = $result[0]->id;
                                $where = array("id" => $tbl_id);
                                $anu = $crp->updateData($where, $subSpecAdd);
                                showLast_query("orange");
                            }
                        }
                    }
                }
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    //------//------//------//------//------//------//------//

    public function generateSaldo()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $fdate = "2022-01-01";
        $arrRekTbl = array(
            "beban lain lain" => "__rek_master__beban_lain_lain",
            "biaya" => "__rek_master__biaya",
            "biaya bpjs" => "__rek_master__biaya_bpjs",
            "biaya bunga" => "__rek_master__biaya_bunga",
            "biaya gaji" => "__rek_master__biaya_gaji",
            "biaya import" => "__rek_master__biaya_import",
            "biaya lain_lain" => "__rek_master__biaya_lain_lain",
            "biaya pph21" => "__rek_master__biaya_pph21",
            "biaya produksi" => "__rek_master__biaya_produksi",
            "biaya sewa" => "__rek_master__biaya_sewa",
            "biaya supplies" => "__rek_master__biaya_supplies",
            "biaya transfer" => "__rek_master__biaya_transfer",
            "biaya umum" => "__rek_master__biaya_umum",
            "biaya usaha" => "__rek_master__biaya_usaha",
            "bunga dan jasa giro" => "__rek_master__bunga_dan_jasa_giro",
            "delivery cost" => "__rek_master__delivery_cost",
            "direct labor" => "__rek_master__direct_labor",
            "hpp" => "__rek_master__hpp",
            "hpp projek" => "__rek_master__hpp_projek",
            "jasa kirim" => "__rek_master__jasa_kirim",
            "kerugian" => "__rek_master__kerugian",
            "kerugian kurs" => "__rek_master__kerugian_kurs",
            "keutungan kurs" => "__rek_master__keutungan_kurs",
            "laba lain lain" => "__rek_master__laba_lain_lain",
            "laba(rugi) perubahan grade produk" => "__rek_master__laba_rugi__perubahan_grade_produk",
            "laba(rugi) perubahan grade supplies" => "__rek_master__laba_rugi__perubahan_grade_supplies",
            "laba(rugi) selisih adjustment" => "__rek_master__laba_rugi__selisih_adjustment",
            "laba(rugi) selisih fifo return pembelian" => "__rek_master__laba_rugi__selisih_fifo_return_pembelian",
            "laba(rugi) selisih kurs" => "__rek_master__laba_rugi__selisih_kurs",
            "pendapatan" => "__rek_master__pendapatan",
            "pendapatan lain lain" => "__rek_master__pendapatan_lain_lain",
            "penjualan" => "__rek_master__penjualan",
            "penjualan projek" => "__rek_master__penjualan_projek",
            "penjualan valas" => "__rek_master__penjualan_valas",
            "penyusutan kendaraan" => "__rek_master__penyusutan_kendaraan",
            "penyusutan mesin" => "__rek_master__penyusutan_mesin",
            "penyusutan mesin produksi" => "__rek_master__penyusutan_mesin_produksi",
            "penyusutan peralatan kantor" => "__rek_master__penyusutan_peralatan_kantor",
            "penyusutan peralatan produksi" => "__rek_master__penyusutan_peralatan_produksi",
            "penyusutan tanah dan bangunan" => "__rek_master__penyusutan_tanah_dan_bangunan",
            "quality" => "__rek_master__quality",
            "return penjualan" => "__rek_master__return_penjualan",
            "rl_lain_lain" => "__rek_master__rl_lain_lain",
            "rugi laba pembulatan ganjil" => "__rek_master__rugi_laba_pembulatan_ganjil",
            "selisih biaya produksi" => "__rek_master__selisih_biaya_produksi",
            "selisih pembulatan" => "__rek_master__selisih_pembulatan",
            "selisih persediaan karena fifo" => "__rek_master__selisih_persediaan_karena_fifo",
        );

        foreach ($arrRekTbl as $rek => $tbl) {
//            $this->db->where(array("fulldate>=" => "$fdate"));
            $crResult[$rek] = $this->db->get($tbl)->result();
            showLast_query("biru");
            cekHitam(":: $rek => $tbl :: " . sizeof($crResult[$rek]));
        }

        $arrCacheRekening = array();
        foreach ($crResult as $rek => $spec) {
            if (sizeof($spec) > 0) {
                foreach ($spec as $crResultSpec) {
//                    arrPrintKuning($crResultSpec);
                    $rekening = $crResultSpec->rekening;
                    $cabang_id = $crResultSpec->cabang_id;
                    $fulldate = $crResultSpec->fulldate;
                    $fulldate_ex = explode("-", $fulldate);
                    $tgl = $fulldate_ex[2];
                    $bln = $fulldate_ex[1];
                    $thn = $fulldate_ex[0];
                    $date_harian = $fulldate;
                    $date_bulanan = "$thn-$bln";
                    $date_tahunan = "$thn";
                    $date_forever = "1";

                    // region forever
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever] = array();
                    }
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["periode"] = "forever";
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] += $crResultSpec->kredit;


                    // endregion forever

                    // region tahunan
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan] = array();
                    }
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion tahunan

                    // region bulanan
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan] = array();
                    }
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion bulanan

                    // region harian
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian] = array();
                    }
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["periode"] = "harian";
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion harian
                }
            }
            break;
        }
//arrPrintHijau($arrCacheRekening);


        $this->db->trans_start();

        foreach ($arrCacheRekening as $rekening => $cacheSpec) {
            foreach ($cacheSpec as $periode => $subCacheSpec) {
                foreach ($subCacheSpec as $cabang_id => $sSpec) {
                    foreach ($sSpec as $subSpec) {
                        $tgl = $subSpec['tgl'];
                        $bln = $subSpec['bln'];
                        $thn = $subSpec['thn'];

                        $rc = New ComRekening();
                        $rc->setFilters(array());
                        switch ($periode) {
                            case "harian":
                                $rc->addFilter("tgl='$tgl'");
                                $rc->addFilter("bln='$bln'");
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "bulanan":
                                $rc->addFilter("bln='$bln'");
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "tahunan":
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "forever":

                                break;
                        }
//                        $rc->addFilter("rekening='$rekening'");
                        $this->db->where("rekening", $rekening);
                        $rc->addFilter("cabang_id='$cabang_id'");
                        $rc->addFilter("periode='$periode'");
                        $result = $rc->lookUpAll()->result();
                        showLast_query("biru");
                        if (sizeof($result) == 0) {
                            // insert baru
//                            $anu = $rc->addData($subSpec);
//                            showLast_query("hijau");
                        }
                        else {
                            // update yang sudah ada
                            $subSpecAdd = array(
                                "saldo_debet" => $subSpec['saldo_debet'],
                                "saldo_kredit" => $subSpec['saldo_kredit'],
                                "saldo_debet_periode" => $subSpec['saldo_debet_periode'],
                                "saldo_kredit_periode" => $subSpec['saldo_kredit_periode'],
                            );
                            $tbl_id = $result[0]->id;
                            $where = array("id" => $tbl_id);
                            $anu = $rc->updateData($where, $subSpecAdd);
                            showLast_query("orange");
                        }
                    }
                }
            }

        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function generateSaldo2()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $fdate = "2022-01-01";
        $arrRekTbl = array(
            "aktiva belum ditempatkan" => "__rek_master__aktiva_belum_ditempatkan",
            "aktiva tetap" => "__rek_master__aktiva_tetap",
            "akum penyu aktiva tetap" => "__rek_master__akum_penyu_aktiva_tetap",
            "akum penyu kendaraan" => "__rek_master__akum_penyu_kendaraan",
            "akum penyu mesin" => "__rek_master__akum_penyu_mesin",
            "akum penyu mesin produksi" => "__rek_master__akum_penyu_mesin_produksi",
            "akum penyu peralatan kantor" => "__rek_master__akum_penyu_peralatan_kantor",
            "akum penyu peralatan produksi" => "__rek_master__akum_penyu_peralatan_produksi",
            "akum penyu tanah dan bangunan" => "__rek_master__akum_penyu_tanah_dan_bangunan",
            "akum penyusutan aktiva tetap" => "__rek_master__akum_penyusutan_aktiva_tetap",
            "credit note" => "__rek_master__credit_note",
            "hutang aktiva tetap" => "__rek_master__hutang_aktiva_tetap",
            "hutang aktiva tetap pada dc" => "__rek_master__hutang_aktiva_tetap_pada_dc",
            "hutang bank" => "__rek_master__hutang_bank",
            "hutang biaya" => "__rek_master__hutang_biaya",
            "hutang biaya_bunga" => "__rek_master__hutang_biaya_bunga",
            "hutang biaya_ke_pusat" => "__rek_master__hutang_biaya_ke_pusat",
            "hutang bpjs" => "__rek_master__hutang_bpjs",
            "hutang dagang" => "__rek_master__hutang_dagang",
            "hutang gaji" => "__rek_master__hutang_gaji",
            "hutang garansi" => "__rek_master__hutang_garansi",
            "hutang install" => "__rek_master__hutang_install",
            "hutang ke cabang" => "__rek_master__hutang_ke_cabang",
            "hutang ke konsumen" => "__rek_master__hutang_ke_konsumen",
            "hutang ke pemegang saham" => "__rek_master__hutang_ke_pemegang_saham",
            "hutang ke pihak lain" => "__rek_master__hutang_ke_pihak_lain",
            "hutang ke pmg saham" => "__rek_master__hutang_ke_pmg_saham",
            "hutang ke pusat" => "__rek_master__hutang_ke_pusat",
            "hutang lain lain ppv" => "__rek_master__hutang_lain_lain_ppv",
            "hutang lain ppv" => "__rek_master__hutang_lain_ppv",
            "hutang ongkir" => "__rek_master__hutang_ongkir",
            "hutang pph 21" => "__rek_master__hutang_pph_21",
            "hutang pph21" => "__rek_master__hutang_pph21",
            "hutang pph23" => "__rek_master__hutang_pph23",
            "hutang pph29" => "__rek_master__hutang_pph29",
            "hutang pph4 ayat 2" => "__rek_master__hutang_pph4_ayat_2",
            "hutang ppv" => "__rek_master__hutang_ppv",
            "hutang sewa" => "__rek_master__hutang_sewa",
            "hutang valas ke konsumen" => "__rek_master__hutang_valas_ke_konsumen",
            "kas" => "__rek_master__kas",
            "laba ditahan" => "__rek_master__laba_ditahan",
            "laba ditempatkan pusat" => "__rek_master__laba_ditempatkan_pusat",
            "laba ditempatkan pusatt" => "__rek_master__laba_ditempatkan_pusatt",
            "modal" => "__rek_master__modal",
            "persediaan produk" => "__rek_master__persediaan_produk",
            "persediaan produk rakitan" => "__rek_master__persediaan_produk_rakitan",
            "persediaan produk riil" => "__rek_master__persediaan_produk_riil",
            "persediaan supplies" => "__rek_master__persediaan_supplies",
            "persediaan supplies proses" => "__rek_master__persediaan_supplies_proses",
            "persediaan supplies riil" => "__rek_master__persediaan_supplies_riil",
            "pib" => "__rek_master__pib",
            "piutang aktiva tetap cabang" => "__rek_master__piutang_aktiva_tetap_cabang",
            "piutang biaya" => "__rek_master__piutang_biaya",
            "piutang biaya_cabang" => "__rek_master__piutang_biaya_cabang",
            "piutang cabang" => "__rek_master__piutang_cabang",
            "piutang dagang" => "__rek_master__piutang_dagang",
            "piutang ke pusat" => "__rek_master__piutang_ke_pusat",
            "piutang lain" => "__rek_master__piutang_lain",
            "piutang pembelian" => "__rek_master__piutang_pembelian",
            "piutang retensi" => "__rek_master__piutang_retensi",
            "piutang supplier" => "__rek_master__piutang_supplier",
            "piutang valas" => "__rek_master__piutang_valas",
            "pph22" => "__rek_master__pph22",
            "pph22 dibayar dimuka" => "__rek_master__pph22_dibayar_dimuka",
            "pph25" => "__rek_master__pph25",
            "pph25_29" => "__rek_master__pph25_29",
            "pph29" => "__rek_master__pph29",
            "pph4 ayat 2" => "__rek_master__pph4_ayat_2",
            "ppn dibayar bendahara negara" => "__rek_master__ppn_dibayar_bendahara_negara",
            "ppn in" => "__rek_master__ppn_in",
            "ppn in jasa" => "__rek_master__ppn_in_jasa",
            "ppn in realisasi" => "__rek_master__ppn_in_realisasi",
            "ppn out" => "__rek_master__ppn_out",
            "projek cost" => "__rek_master__projek_cost",
            "sewa dibayar dimuka" => "__rek_master__sewa_dibayar_dimuka",
            "uang muka dibayar" => "__rek_master__uang_muka_dibayar",
            "uang muka valas" => "__rek_master__uang_muka_valas",
            "valas" => "__rek_master__valas",
        );

        foreach ($arrRekTbl as $rek => $tbl) {
//            $this->db->where(array("fulldate>="=>"$fdate"));
            $crResult[$rek] = $this->db->get($tbl)->result();
//            showLast_query("biru");
//            cekHitam(":: $rek => $tbl :: " . sizeof($crResult[$rek]));
        }

        $arrCacheRekening = array();
        foreach ($crResult as $rek => $spec) {
            if (sizeof($spec) > 0) {
                foreach ($spec as $crResultSpec) {
//                    arrPrintKuning($crResultSpec);
                    $rekening = $crResultSpec->rekening;
                    $cabang_id = $crResultSpec->cabang_id;
                    $fulldate = $crResultSpec->fulldate;
                    $fulldate_ex = explode("-", $fulldate);
                    $tgl = $fulldate_ex[2];
                    $bln = $fulldate_ex[1];
                    $thn = $fulldate_ex[0];
                    $date_harian = $fulldate;
                    $date_bulanan = "$thn-$bln";
                    $date_tahunan = "$thn";
                    $date_forever = "1";

                    // region forever
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever] = array();
                    }
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["periode"] = "forever";
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] += $crResultSpec->kredit;


                    // endregion forever

                    // region tahunan
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan] = array();
                    }
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion tahunan

                    // region bulanan
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan] = array();
                    }
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion bulanan

                    // region harian
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian] = array();
                    }
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["periode"] = "harian";
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion harian
                }
            }

//            break;
        }
//arrPrintHijau($arrCacheRekening);
//        mati_disini(__LINE__);

        $this->db->trans_start();

        foreach ($arrCacheRekening as $rekening => $cacheSpec) {
            foreach ($cacheSpec as $periode => $subCacheSpec) {
                foreach ($subCacheSpec as $cabang_id => $sSpec) {
                    foreach ($sSpec as $subSpec) {
                        $tgl = $subSpec['tgl'];
                        $bln = $subSpec['bln'];
                        $thn = $subSpec['thn'];

                        $rc = New ComRekening();
                        $rc->setFilters(array());
                        switch ($periode) {
                            case "harian":
                                $rc->addFilter("tgl='$tgl'");
                                $rc->addFilter("bln='$bln'");
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "bulanan":
                                $rc->addFilter("bln='$bln'");
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "tahunan":
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "forever":

                                break;
                        }
//                        $rc->addFilter("rekening='$rekening'");
                        $this->db->where("rekening", $rekening);
                        $rc->addFilter("cabang_id='$cabang_id'");
                        $rc->addFilter("periode='$periode'");
                        $result = $rc->lookUpAll()->result();
                        showLast_query("biru");
                        if (sizeof($result) == 0) {
                            // insert baru
//                            $anu = $rc->addData($subSpec);
//                            showLast_query("hijau");
                        }
                        else {
                            // update yang sudah ada
                            $subSpecAdd = array(
                                "saldo_debet" => $subSpec['saldo_debet'],
                                "saldo_kredit" => $subSpec['saldo_kredit'],
                                "saldo_debet_periode" => $subSpec['saldo_debet_periode'],
                                "saldo_kredit_periode" => $subSpec['saldo_kredit_periode'],
                            );
                            $tbl_id = $result[0]->id;
                            $where = array("id" => $tbl_id);
                            $anu = $rc->updateData($where, $subSpecAdd);
                            showLast_query("orange");
                        }
                    }
                }
            }

        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }
}