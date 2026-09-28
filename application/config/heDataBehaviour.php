<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* --------------------------------------------------------------------------------
 * data group ada di:
 * $config['userGroup']
 * --------------------------------------------------------------------------------*/

$config['heDataBehaviour'] = array(
    "MdlCompany" => array(
        "restriction" => true,
        "allowedRestriction" => array("root"),
        "label" => "Company profile",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array(),
        "creatorAdmins" => array("c_data", "c_holding"),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("c_data"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlDiskonKelompok" => array(
        "label" => "DATA KELOMPOK DISKON",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("c_data"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    "MdlCabang" => array(
        "label" => "Branch",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array("root", "c_holding"),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "cabang_id>" => "0"
            ),
            "grouping" => "cabang_id",
            "selecteds" => array(
                "cabang_id",
                "id"
            ),
            "data_strukture" => array(
                "cabang_id" => "id",
            ),
        ),
    ),
    //    "MdlDiv" => array(
    //        "label" => "Division",
    //        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
    //        "creators" => array("root"),
    //        //        "creatorAdmins" => array("root"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("root"),
    //        "updaterAdmins" => array("root"),
    //        "deleters" => array(),
    //        "deleterAdmins" => array("root"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    "MdlSupplier" => array(
        "label" => "supplier",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "creators" => array("c_data", "c_holding", "c_purchasing"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "c_purchasing"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "suppliers_id>" => "0"
            ),
            "grouping" => "suppliers_id",
            "selecteds" => array(
                "suppliers_id",
                "id"
            ),
            "data_strukture" => array(
                "suppliers_id" => "id",
            ),
        ),
    ),
    "MdlCustomer" => array(
        "label" => "Customer",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_finance", "o_finance", "o_seller", "o_seller_spv"),
        "creators" => array("c_data", "c_holding", "o_seller", "o_seller_entry", "kasir"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "o_seller", "o_finance"),
        // "updaterAdmins" => array("c_data","c_holding", "o_seller_spv"),
        "updaterAdmins" => array(),
        // "deleters" => array("c_holding", "o_seller", "o_seller_entry", "o_seller_spv","c_data"),
        // "deleterAdmins" => array("c_data","dataadmin", "c_holding", "o_seller_spv"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_finance", "o_seller", "o_seller_entry", "o_finance"),
        //        "customAccessFields" => array(
        //            "c_holding" => array("nama", "email", "alamat_1", "npwp"),
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "customers_id>" => "0"
            ),
            "grouping" => "customers_id",
            "selecteds" => array(
                "customers_id",
                "id"
            ),
            "data_strukture" => array(
                "customers_id" => "id",
            ),
        ),
    ),
    "MdlCustomerExport" => array(
        "label" => "Customer Export (internatiaonal)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_finance", "o_finance", "o_seller", "o_seller_spv"),
        "creators" => array("c_data", "c_holding", "o_seller", "o_seller_entry", "kasir"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "o_seller"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "o_seller", "o_seller_entry", "o_seller_spv"),
        "deleterAdmins" => array("c_data", "dataadmin", "c_holding", "o_seller_spv"),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_finance", "o_seller", "o_seller_entry", "o_finance"),
        //        "customAccessFields" => array(
        //            "c_holding" => array("nama", "email", "alamat_1", "npwp"),
        //        ),
    ),

    //    "MdlPreCustomer" => array(
    //        "label" => "calon Customer",
    //        "viewers" => array("c_data", "c_holding", "c_owner", "o_seller", "o_seller_spv", "c_finance", "o_finance"),
    //        "creators" => array("c_holding", "o_seller", "o_seller_entry", "kasir"),
    //        "creatorAdmins" => array(),//perlu otorisasi
    //        "updaters" => array("c_holding", "c_data", "o_seller_spv"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding", "o_seller", "o_seller_entry", "o_seller_spv"),
    //        "deleterAdmins" => array("dataadmin", "c_holding", "o_seller_spv"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner", "o_seller", "o_seller_entry"),
    //        //        "customAccessFields" => array(
    //        //            "c_holding" => array("nama", "email", "alamat_1", "npwp"),
    //        //        ),
    //    ),
    "MdlProduk" => array(
        "restriction" => false,
        "label" => "produk",
        "sublabel" => "(tanpa produk rakitan)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "creators" => array("c_data", "c_holding", "c_purchasing"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "c_purchasing"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "rel_deleters" => array(
            "dirModel" => "Coms/",
            "baseModel" => "ComRekeningPembantuProduk",
            "condites" => array(
                "periode" => "forever"
            ),
            "grouping" => "extern_id",
            "selecteds" => array(
                "extern_id",
                "sum(qty_debet) as 'sum_qty_debet'"
            ),
            "data_strukture" => array(
                "extern_id" => "sum_qty_debet",
            ),
            //----------------
            "dirModelLocker" => "Mdls/",
            "baseModelLocker" => "MdlLockerStock",
            "conditesLocker" => array(
                "state" => "active",
                "jenis" => "produk"
            ),
            "groupingLocker" => "produk_id",
            "selectedsLocker" => array(
                "produk_id",
                "sum(jumlah) as 'sum_jumlah'"
            ),
            "data_strukture_locker" => array(
                "produk_id" => "sum_jumlah",
            ),
        ),
        "rel_editors" => array(
            "dirModel" => "Coms/",
            "baseModel" => "ComRekeningPembantuProduk",
            "condites" => array(
                "periode" => "forever"
            ),
            "grouping" => "extern_id",
            "selecteds" => array(
                "extern_id",
                "sum(qty_debet) as 'sum_qty_debet'"
            ),
            "data_strukture" => array(
                "extern_id" => "sum_qty_debet",
            ),
            //----------------
            "dirModelLocker" => "Mdls/",
            "baseModelLocker" => "MdlLockerStock",
            "conditesLocker" => array(
                "state" => "active",
                "jenis" => "produk"
            ),
            "groupingLocker" => "produk_id",
            "selectedsLocker" => array(
                "produk_id",
                "sum(jumlah) as 'sum_jumlah'"
            ),
            "data_strukture_locker" => array(
                "produk_id" => "sum_jumlah",
            ),
        ),
        "rel_info" => array(
            "methode" => "viewProdukInfo",
            // "dirModel" => "Coms/",
            // "baseModel" => "ComRekeningPembantuProduk",
            // "condites" => array(
            //     "periode" => "forever"
            // ),
            // "grouping" => "extern_id",
            // "selecteds" => array(
            //     "extern_id",
            //     "sum(qty_debet) as 'sum_qty_debet'"
            // ),
            // "data_strukture" => array(
            //     "extern_id" => "sum_qty_debet",
            // ),
            // //----------------
            // "dirModelLocker" => "Mdls/",
            // "baseModelLocker" => "MdlLockerStock",
            // "conditesLocker" => array(
            //     "state" => "active",
            //     "jenis" => "produk"
            // ),
            // "groupingLocker" => "produk_id",
            // "selectedsLocker" => array(
            //     "produk_id",
            //     "sum(jumlah) as 'sum_jumlah'"
            // ),
            // "data_strukture_locker" => array(
            //     "produk_id" => "sum_jumlah",
            // ),
        ),
    ),
    "MdlProdukBekas" => array(
        "restriction" => false,
        "label" => "produk bekas",
        "sublabel" => "",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "creators" => array("c_data", "c_holding", "c_purchasing"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing"),
        "updaterAdmins" => array("c_data", "c_holding", "c_purchasing","purchasing_spv"),
        "deleters" => array("c_data", "c_holding", "c_purchasing"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "rel_deleters" => array(
            "dirModel" => "Coms/",
            "baseModel" => "ComRekeningPembantuProduk",
            "condites" => array(
                "periode" => "forever"
            ),
            "grouping" => "extern_id",
            "selecteds" => array(
                "extern_id",
                "sum(qty_debet) as 'sum_qty_debet'"
            ),
            "data_strukture" => array(
                "extern_id" => "sum_qty_debet",
            ),
        ),
        "rel_editors" => array(
            "dirModel" => "Coms/",
            "baseModel" => "ComRekeningPembantuProduk",
            "condites" => array(
                "periode" => "forever"
            ),
            "grouping" => "extern_id",
            "selecteds" => array(
                "extern_id",
                "sum(qty_debet) as 'sum_qty_debet'"
            ),
            "data_strukture" => array(
                "extern_id" => "sum_qty_debet",
            ),
        ),
    ),
//    "MdlProduk_Paket_Project" => array(
//        "label" => "PRODUK PAKET",
//        "sublabel" => "KOMPOSISI PRODUK",
//        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing", "o_project"),
//        "creators" => array("c_data", "c_holding", "c_purchasing", "o_project"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_data", "c_holding", "c_purchasing", "o_project"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_data", "c_holding", "c_purchasing"),
//        "deleterAdmins" => array(),
//        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
//    ),
//    "MdlProdukForProject" => array(
//        "label" => "produk project",
//        "sublabel" => "(tanpa produk regular)",
//        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing", "o_project"),
//        "creators" => array("c_data", "c_holding", "c_purchasing", "o_project"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_data", "c_holding", "c_purchasing", "o_project"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_data", "c_holding", "c_purchasing"),
//        "deleterAdmins" => array(),
//        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
//    ),

    //    "MdlProdukVarian" => array(
    //        "restriction"=>false,
    //        "label" => "Varian Produk",
    //        "viewers" => array("o_holding"),
    //        "creators" => array("o_holding","c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("o_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("o_holding"),
    //        "deleterAdmins" => array("o_holding"),
    //        "historyViewers" => array("o_holding"),
    //
    //    ),
    //    "MdlProdukProject" => array(
    //        "label" => "project",
    //        "sublabel" => "project",
    //        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing", "o_seller_spv"),
    //        "creators" => array("c_holding", "c_purchasing", "o_project"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding", "c_purchasing", "o_project"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding", "c_purchasing", "o_project"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
    //    ),

    "MdlSupplies" => array(
        "restriction" => false,
        "label" => "Supplies & equipment",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "creators" => array("c_holding", "c_purchasing"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_purchasing"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding", "c_purchasing"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_purchasing"),
        "rel_deleters" => array(
            "dirModel" => "Coms/",
            "baseModel" => "ComRekeningPembantuSupplies",
            "condites" => array(
                "periode" => "forever"
            ),
            "grouping" => "extern_id",
            "selecteds" => array(
                "extern_id",
                "sum(qty_debet) as 'sum_qty_debet'"
            ),
            "data_strukture" => array(
                "extern_id" => "sum_qty_debet",
            ),
        ),
    ),

    "MdlPpv" => array(
        "restriction" => true,//true ->tidak boleh dilihat di menus eting hak akse, false kebalikannya
        "allowedRestriction" => array("root"),//hanya akun in yang diijinkan untuk buka
        "label" => "PPV Index",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array(),
        "creatorAdmins" => array("c_data", "c_holding"),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("c_data"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlGudang" => array(
        "label" => "Warehouse",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    //    "MdlProdukRakitan" => array(
    //        "label" => "603 Assembled Product",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //        "rel_deleters" => array(
    //            "dirModel" => "Coms/",
    //            "baseModel" => "ComRekeningPembantuProduk",
    //            "condites" => array(
    //                "periode" => "forever"
    //            ),
    //            "grouping" => "extern_id",
    //            "selecteds" => array(
    //                "extern_id",
    //                "sum(qty_debet) as 'sum_qty_debet'"
    //            ),
    //            "data_strukture" => array(
    //                "extern_id" => "sum_qty_debet",
    //            ),
    //        ),
    //    ),
//    "MdlProdukPaket" => array(
//        "label" => "produk paket",
//        "viewers" => array("c_data", "c_holding", "c_owner"),
//        "creators" => array("c_holding"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding", "c_data"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_holding"),
//        "deleterAdmins" => array(),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),

//    "MdlProdukPaket" => array(
//        "label" => "produk paket",
//        "viewers" => array("c_data", "c_holding", "c_owner"),
//        "creators" => array("c_holding"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding", "c_data"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_holding"),
//        "deleterAdmins" => array(),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),
//
    "MdlProdukKomposit" => array(
        "label" => "produk paket penjualan",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_holding", "c_data"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_data"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    //    "MdlProdukRakitanBiaya" => array(
    //        "label" => "602 Standart Cost by Product",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),

    //    "MdlProdukRakitanPreBiaya" => array(
    //        "label" => "601 Product Cost Define",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding", "p_produksi", "p_produksi_spv"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
//        "MdlProdukRakitanBiayaPaket" => array(
//            "label" => "602 Standart Cost Paket",
//            "viewers" => array("c_data", "c_holding", "c_owner", "o_project"),
//            "creators" => array("c_holding", "p_produksi", "p_produksi_spv", "o_project"),
//            "creatorAdmins" => array(),
//            "updaters" => array("c_holding", "p_produksi", "p_produksi_spv", "o_project"),
//            "updaterAdmins" => array(),
//            "deleters" => array("c_holding", "p_produksi", "p_produksi_spv"),
//            "deleterAdmins" => array("c_holding"),
//            "historyViewers" => array("c_data", "c_holding", "c_owner"),
//        ),
//        "MdlProdukRakitanPreBiayaPaket" => array(
//            "label" => "601 Product Cost Define",
//            "viewers" => array("c_data", "c_holding", "c_owner", "o_project"),
//            "creators" => array("c_holding", "p_produksi", "p_produksi_spv", "o_project"),
//            "creatorAdmins" => array(),
//            "updaters" => array("c_holding", "p_produksi", "p_produksi_spv", "o_project"),
//            "updaterAdmins" => array(),
//            "deleters" => array("c_holding", "p_produksi", "p_produksi_spv"),
//            "deleterAdmins" => array("c_holding"),
//            "historyViewers" => array("c_data", "c_holding", "c_owner"),
//        ),
    //    "MdlProdukPaket" => array(
    //        "label" => "Product Package",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    "MdlReseller" => array(
        "label" => "Reseller",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "c_data", "root"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "oleh_id>" => "0"
            ),
            "grouping" => "oleh_id",
            "selecteds" => array(
                "oleh_id",
                "id"
            ),
            "data_strukture" => array(
                "oleh_id" => "id",
            ),
        ),
    ),
    "MdlEmployee" => array(
        "label" => "Employee",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "c_data", "root"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "oleh_id>" => "0"
            ),
            "grouping" => "oleh_id",
            "selecteds" => array(
                "oleh_id",
                "id"
            ),
            "data_strukture" => array(
                "oleh_id" => "id",
            ),
        ),
    ),
    "MdlEmployee__shadow" => array(
        "restriction" => true,//true ->tidak boleh dilihat di menus eting hak akse, false kebalikannya
        "allowedRestriction" => array("root"),//hanya akun in yang diijinkan untuk buka
        "label" => "Employee (shadow)",
        "viewers" => array("root"),
        "creators" => array("root"),
        "creatorAdmins" => array(),
        "updaters" => array("root"),
        "updaterAdmins" => array(),
        "deleters" => array(),
        "deleterAdmins" => array(),
        "historyViewers" => array("root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
    ),
    "MdlEmployeeCabang" => array(
        "label" => "Branch Employee",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "oleh_id>" => "0"
            ),
            "grouping" => "oleh_id",
            "selecteds" => array(
                "oleh_id",
                "id"
            ),
            "data_strukture" => array(
                "oleh_id" => "id",
            ),
        ),
    ),
    "MdlEmployeeFreelanceCabang" => array(
        "label" => "Branch Employee freelance",
        "viewers" => array("c_data", "c_holding", "c_owner", "root", "o_seller", "o_seller_spv", "o_finance", "o_finance_spv"),
        "creators" => array("c_data","c_holding", "c_owner", "root", "o_seller", "o_seller_spv", "o_finance", "o_finance_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data","c_holding", "root", "o_seller", "o_seller_spv", "o_finance", "o_finance_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root"),
        "deleterAdmins" => array("c_data", "root", "o_seller", "o_seller_spv", "o_finance", "o_finance_spv"),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
    ),

    "MdlEmployeeCabang__shadow" => array(
        "label" => "Branch Employee (shadow)",
        "viewers" => array("root"),
        "creators" => array("root"),
        "creatorAdmins" => array(),
        "updaters" => array("root"),
        "updaterAdmins" => array(),
        "deleters" => array(),
        "deleterAdmins" => array(),
        "historyViewers" => array("root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
    ),
    "MdlEmployeeGudang" => array(
        "label" => "Warehouse Employee",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root", "c_data"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root"),
        "deleterAdmins" => array("c_data", "root"),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
    ),
    "MdlEmployeeGudang_shadow" => array(
        "label" => "Warehouse Employee Shadow",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root", "c_data"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root"),
        "deleterAdmins" => array("c_data", "root"),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
    ),

//    "MdlEmployeeGudangFase" => array(
//        "label" => "Warehouse Employee Manufactur",
//        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "creators" => array("c_holding", "c_owner", "root"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding", "root", "c_data"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_data", "root"),
//        "deleterAdmins" => array("c_data", "root"),
//        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "customBlockFields" => array(
//            "readonly" => array("nama_login", "password"),
//            // "disabled" => array("status"),
//        ),
//        //        "dataRelation" => array(
//        //            "log" => array(
//        //                "label" => "My Activity",
//        //                "target" => "Data/view/ActivityLog",
//        //                "srcKey" => "id",
//        //            ),
//        //
//        //
//        //        ),
//    ),
    "MdlEmployeeWorker" => array(
        "label" => "Teknisi Pemasangan",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "oleh_id>" => "0"
            ),
            "grouping" => "oleh_id",
            "selecteds" => array(
                "oleh_id",
                "id"
            ),
            "data_strukture" => array(
                "oleh_id" => "id",
            ),
        ),
    ),
    "MdlEmployeeKirim" => array(
        "label" => "Kurir/Driver",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "root"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root", "c_holding"),
        "deleterAdmins" => array("c_data", "root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "oleh_id>" => "0"
            ),
            "grouping" => "oleh_id",
            "selecteds" => array(
                "oleh_id",
                "id"
            ),
            "data_strukture" => array(
                "oleh_id" => "id",
            ),
        ),
    ),
    "MdlEmployeeSalesman" => array(
        "label" => "Salesman",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_owner", "root"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "root"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "root", "c_holding"),
        "deleterAdmins" => array("c_data", "root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "root"),
        "customBlockFields" => array(
            "readonly" => array("nama_login", "password"),
            // "disabled" => array("status"),
        ),
        //        "dataRelation" => array(
        //            "log" => array(
        //                "label" => "My Activity",
        //                "target" => "Data/view/ActivityLog",
        //                "srcKey" => "id",
        //            ),
        //
        //
        //        ),
        "rel_deleters" => array(
            "dirModel" => "",
            "baseModel" => "MdlTransaksi",
            "condites" => array(
                "oleh_id>" => "0"
            ),
            "grouping" => "oleh_id",
            "selecteds" => array(
                "oleh_id",
                "id"
            ),
            "data_strukture" => array(
                "oleh_id" => "id",
            ),
        ),
    ),

    //    "MdlEmployeeGudang" => array(
    //        "label" => "Warehouse Employee",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding", "c_owner"),
    //        "creatorAdmins" => array("c_data"),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array("c_data"),
    //        "deleters" => array("c_data"),
    //        "deleterAdmins" => array("c_data"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    "MdlUser" => array(
        "label" => "Administrator",
        "viewers" => array("root"),
        "creators" => array("root"),
        "creatorAdmins" => array("root"),
        "updaters" => array("root"),
        "updaterAdmins" => array("root"),
        "deleters" => array("root"),
        "deleterAdmins" => array("root"),
        "historyViewers" => array("root"),
    ),
    "MdlBank" => array(
        "label" => "Bank",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("c_data", "root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlBankAccount_in" => array(
        "label" => "Bank account",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_owner", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlEdc" => array(
        "restriction" => false,
        "label" => "EDC",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_owner", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    //    "MdlBankAccount_out" => array(
    //        "label" => "Bank account (OUT)",
    //        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
    //        "creators" => array("c_data", "c_owner"),
    //        "creatorAdmins" => array("root", "c_holding"),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array("root", "c_holding"),
    //        "deleters" => array(),
    //        "deleterAdmins" => array("root", "c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),

    "MdlCustomerAddress" => array(
        "restriction" => false,
        "label" => "delivery address",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding", "o_seller", "o_seller_entry", "kasir"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "o_seller", "o_seller_entry"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding", "o_seller", "o_seller_entry"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),

    ),

    "MdlCustomerBillAddress" => array(
        "restriction" => false,
        "label" => "billing address",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding", "o_seller_spv", "o_seller", "o_seller_entry"),//o_seller hanya untuk testing
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "o_seller", "o_seller_entry"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "o_seller", "o_seller_entry"),
        //        "deleterAdmins" => array("dataadmin"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),

    ),

    "MdlSupplierAddress" => array(
        "label" => "My shipping address",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),

    ),
    "MdlOnlineCustomer" => array(
        "label" => "Marketplace sudah direlasikan",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),

    ),
    "MdlCustomerTipeOnline" => array(
        "label" => "Marketplace",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),

    ),

    "MdlCourier" => array(
        "restriction" => false,
        "label" => "Courier",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlCurrency" => array(
//        "restriction" => false,
        "label" => "Currency/Valas/Mata Uang Asing",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlSatuan" => array(
        "restriction" => false,
        "label" => "Satuan",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlMerek" => array(
        "label" => "Merek",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlTipe" => array(
        "label" => "tipe produk",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukSubKategori" => array(
        "label" => "sub kategori produk",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukSeries" => array(
        "label" => "produk series",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlPhase" => array(
        "label" => "phase listrik",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

//    "MdlModel" => array(
//        "label" => "model produk",
//        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
//        "creators" => array("c_data","c_holding", "c_purchasing_spv"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding", "c_purchasing_spv"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_data", "c_holding"),
//        "deleterAdmins" => array(),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),

    "MdlModelIndoor_1" => array(
        "label" => "model indoor #1",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    // "MdlModelIndoor_2" => array(
    //     "label"          => "model indoor #2",
    //     "viewers"        => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
    //     "creators"       => array("c_holding", "c_purchasing_spv"),
    //     "creatorAdmins"  => array(),
    //     "updaters"       => array("c_holding", "c_purchasing_spv"),
    //     "updaterAdmins"  => array(),
    //     "deleters"       => array("c_data","c_holding"),
    //     "deleterAdmins"  => array(),
    //     "historyViewers" => array("c_data", "c_holding", "c_owner"),
    // ),
    // "MdlModelIndoor_3" => array(
    //     "label"          => "model indoor #3",
    //     "viewers"        => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
    //     "creators"       => array("c_holding", "c_purchasing_spv"),
    //     "creatorAdmins"  => array(),
    //     "updaters"       => array("c_holding", "c_purchasing_spv"),
    //     "updaterAdmins"  => array(),
    //     "deleters"       => array("c_data","c_holding"),
    //     "deleterAdmins"  => array(),
    //     "historyViewers" => array("c_data", "c_holding", "c_owner"),
    // ),
    // "MdlModelIndoor_4" => array(
    //     "label"          => "model indoor #4",
    //     "viewers"        => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
    //     "creators"       => array("c_holding", "c_purchasing_spv"),
    //     "creatorAdmins"  => array(),
    //     "updaters"       => array("c_holding", "c_purchasing_spv"),
    //     "updaterAdmins"  => array(),
    //     "deleters"       => array("c_data","c_holding"),
    //     "deleterAdmins"  => array(),
    //     "historyViewers" => array("c_data", "c_holding", "c_owner"),
    // ),
    "MdlModelOutdoor" => array(
        "label" => "model outdoor",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlKapasitas" => array(
        "label" => "model kapasitas",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukSize" => array(
        "label" => "model skala (size)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukPart_1" => array(
        "label" => "remot control",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukPart_2" => array(
        "label" => "cover",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukPartKategori" => array(
        "label" => "model skala (size)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukPartJenis" => array(
        "label" => "model skala (size)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukPartUkuran" => array(
        "label" => "model skala (size)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_data", "c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlProdukPerSerialNumber" => array(
        "label" => "Serial number produk",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_purchasing_spv"),
        "creators" => array("c_holding", "c_purchasing_spv"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_purchasing_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),


    //    "MdlHargaProduk" => array(
    //        "label" => "Product price",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array("c_data"),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array("c_data"),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
//    "MdlExpense" => array(
//        "label" => "Expense",
//        "viewers" => array("c_data", "c_holding", "c_owner"),
//        "creators" => array("c_holding"),
//        "creatorAdmins" => array("c_data", "c_holding"),
//        "updaters" => array("c_holding"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_holding"),
//        "deleterAdmins" => array(),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),
    "MdlSewaDetail" => array(
        "label" => "sewa",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding", "c_data", "c_finance"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_data", "c_finance"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
//     "MdlProdukJasa" => array(
//         "label" => "services product",
//         "viewers" => array("c_data", "c_holding", "c_owner"),
////         "viewers" => array(""),
//         "creators" => array("c_holding"),
//         "creatorAdmins" => array("c_data", "c_holding"),
//         "updaters" => array("c_holding"),
//         "updaterAdmins" => array("c_data"),
//         "deleters" => array("c_holding"),
//         "deleterAdmins" => array("c_holding"),
//         "historyViewers" => array("c_data", "c_holding", "c_owner"),
//     ),
    //    "MdlObjekPajak" => array(
    //        "label" => "Objek pajak",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_data", "c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_data", "c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array(),
    //        "deleterAdmins" => array("c_data"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),

    "MdlTos" => array(
        "label" => "Term of service",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array("c_data", "c_holding"),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("c_holding", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlTop" => array(
        "label" => "Term of payment",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array("c_data", "c_holding", "c_owner"),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlCapacity" => array(
        "label" => "Capacity",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    "MdlProdukKategori" => array(
        "label" => "produk Kategori",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlFolderProduk" => array(
        "label" => "produk kategori",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlFolderSupplies" => array(
        "label" => "Supplies kategori",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    //    "MdlFolderProdukRakitan" => array(
    //        "label" => "604 Assembled kategori",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    "MdlFolderProdukPaket" => array(
        "label" => "paket Produk kategori",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array(),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    // "MdlFolderAset" => array(
    //     "label" => "Aset Kategory",
    //     "viewers" => array("c_data", "c_holding", "c_owner"),
    //     "creators" => array("c_data", "c_holding"),
    //     "creatorAdmins" => array("c_holding"),
    //     "updaters" => array(),
    //     "updaterAdmins" => array(),
    //     "deleters" => array(),
    //     "deleterAdmins" => array(),
    //     "historyViewers" => array("c_data", "c_holding", "c_owner"),
    // ),

    //    "MdlFolderPettycash" => array(
    //        "label" => "Pettycash folder",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array("c_data"),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array("c_data"),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array("c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),

    "MdlActivityLog" => array(
        "label" => "activity log",
        "viewers" => array("c_data", "c_holding", "c_owner",),
        "creators" => array(),
        "creatorAdmins" => array(),
        "updaters" => array(),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array(),
    ),
    "MdlProdukPerSupplier" => array(
        "label" => "Connected Product",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlConnectedDiscount" => array(
        "label" => "Connected Discount",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    //    "MdlAddDiscount" => array(
    //        "label" => "Add Discount",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array(),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    //    "MdlProdukKomposisi" => array(
    //        "label" => "product component",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array(),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    //    "MdlDataHistory"=>array(
    //        "label" => "History",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array(),
    //        "creatorAdmins" => array(""),
    //        "updaters" => array(""),
    //        "updaterAdmins" => array(""),
    //        "deleters" => array(""),
    //        "deleterAdmins" => array(""),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //        "MdlChilds" => array(
    //            "HargaProduk" =>"Harga Produk",
    //            "Produk" =>"Produk",
    //            "Customer" =>"Customer",
    //        ),
    //    ),
    "MdlPendapatanLainLain" => array(
        "label" => "Dta Sub Pendapatan",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_data", "c_finance", "c_finance_spv"),
        "creatorAdmins" => array(),
        "updaters" => array(),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),


    "MdlDtaBiayaProject" => array(
        "label" => "DTA Details Biaya Project",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
//    "MdlDtaModal" => array(
//        "label" => "Dta Pemegang Saham",
//        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "creators" => array("c_data", "c_holding"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_data", "c_holding"),
//        "updaterAdmins" => array(),
//        "deleters" => array("c_data", "c_holding"),
//        "deleterAdmins" => array("root", "c_holding"),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),
    "MdlDtaPemegangSaham" => array(
        "label" => "Dta Pemegang Saham",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    "MdlDtaBiayaUmum" => array(
        "label" => "Biaya Umum",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding", "c_data"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_data"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding", "c_data"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlDtaBiayaUsaha" => array(
        "label" => "Biaya Usaha",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding", "c_data"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_data"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "c_data"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    "MdlDtaBiayaProduksiProject" => array(
        "label" => "Jasa/Biaya Project",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_data"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_data"),
        "updaterAdmins" => array(),
//        "updaterAdmins" => array("root", "c_holding"),
        "deleters" => array(),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
//    "MdlDtaBiayaProduksi" => array(
//        "label" => "Jasa/Biaya Project",
//        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "creators" => array("c_holding", "c_data"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding", "c_data"),
//        "updaterAdmins" => array(),
////        "updaterAdmins" => array("root", "c_holding"),
//        "deleters" => array(),
//        "deleterAdmins" => array("root", "c_holding"),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),
//    "MdlDtaAkumPenyusutanAktivaTetap" => array(
//        "label" => "Dta Penyusutan Aktiva Tetap",
//        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "creators" => array("c_holding"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding"),
//        "updaterAdmins" => array("root", "c_holding"),
//        "deleters" => array("c_holding"),
//        "deleterAdmins" => array("root", "c_holding"),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),
    //    "MdlDtaAktivaTakBerwujud" => array(
    //        "label" => "Dta Aktiva Tak Berwujud",
    //        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array("root", "c_holding"),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array("root", "c_holding"),
    //        "deleters" => array(),
    //        "deleterAdmins" => array("root", "c_holding"),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),

//    "MdlDtaPerson2" => array(
//        "label" => "Dta Person",
//        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "creators" => array("c_holding"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding"),
//        "updaterAdmins" => array("root", "c_holding"),
//        "deleters" => array("c_holding"),
//        "deleterAdmins" => array("root", "c_holding"),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),

    "MdlDtaLabaDitahan" => array(
        "label" => "Laba Ditahan",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array("root", "c_holding"),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlDtaBebanLainLain" => array(
        "label" => "Biaya lain lain",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_holding", "c_data"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_data"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding", "c_data"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    //    "MdlBiayaProduksi_prebiaya" => array(
    //        "label" => "Connected Production Expense and Standart Cost",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array("c_holding"),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array("c_holding"),
    //        "deleterAdmins" => array(),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    "MdlAsetBerwujud" => array(
        "label" => "Aset berwujud",
        "viewers" => array("c_data", "c_holding", "c_owner",),
        "creators" => array("c_holding", "c_data", "c_gudang"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array("c_holding", "c_gudang_spv"),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "o_seller", "o_seller_entry"),
        //        "customAccessFields" => array(
        //            "c_holding" => array("nama", "email", "alamat_1", "npwp"),
        //        ),
    ),
    "MdlAsetDetail" => array(
        "label" => "Aset detail",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array(),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    //    "MdlSewaDetail" => array(
    //        "label" => "Sewa detail",
    //        "viewers" => array("c_data", "c_holding", "c_owner"),
    //        "creators" => array(),
    //        "creatorAdmins" => array(),
    //        "updaters" => array("c_holding"),
    //        "updaterAdmins" => array(),
    //        "deleters" => array(),
    //        "deleterAdmins" => array(),
    //        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    //    ),
    "MdlPphPasal" => array(
        "label" => "PPH PS.4(2)",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding",),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlUangMuka" => array(
        "label" => "Uang Muka",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array(),
        "creatorAdmins" => array(),
        "updaters" => array(),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlRekeningKoran" => array(
        "label" => "Rekening koran",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding", "c_finance"),
        "creatorAdmins" => array(),
        "updaters" => array("c_holding", "c_finance_spv"),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlDtaHutangPihak3" => array(
        "label" => "Pihak Lain(Hutang)",
        "viewers" => array("c_data", "c_holding", "c_owner", "c_finance"),
        "creators" => array("c_data", "c_holding", "c_finance"),
        "creatorAdmins" => array(),
        "updaters" => array(),
        "updaterAdmins" => array(),
        "deleters" => array("c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
    "MdlStaticNotes" => array(
        "restriction" => false,
        "label" => "Static note",
        "viewers" => array("c_data", "c_holding", "c_owner"),
        "creators" => array("c_data", "c_holding", "c_finance"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding", "c_finance"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),

    "MdlMenuGroup" => array(
        "restriction" => true,
        "allowedRestriction" => array("root"),
        "label" => "grouping menu",
        "viewers" => array("root",),
        "creators" => array("root",),
        "creatorAdmins" => array(),
        "updaters" => array("root",),
        "updaterAdmins" => array(),
        "deleters" => array("root"),
        "deleterAdmins" => array(),
        "historyViewers" => array("root"),
    ),

    //    "MdlMenuGroupUi" => array(
    //        "label"          => "grouping menu ui",
    //        "viewers"        => array("root"),
    //        "creators"       => array("root"),
    //        "creatorAdmins"  => array(),
    //        "updaters"       => array("root"),
    //        "updaterAdmins"  => array(),
    //        "deleters"       => array("root"),
    //        "deleterAdmins"  => array(),
    //        "historyViewers" => array("root"),
    //    ),
    "MdlDtaLogamMulia" => array(
        "label" => "Logam Mulia dan Permata",
        "viewers" => array("c_data", "c_holding", "c_owner", "root", "c_finance", "c_finance_spv"),
//        "creators" => array("c_data", "c_holding", "c_data", "c_finance", "c_finance_spv"),
        "creators" => array(),
        "creatorAdmins" => array(),
//        "updaters" => array("c_data", "c_holding", "c_data", "c_finance", "c_finance_spv"),
        "updaters" => array(),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding", "c_data", "c_finance", "c_finance_spv"),
        "deleterAdmins" => array(),
        "historyViewers" => array("c_data", "c_holding", "c_owner", "c_finance", "c_finance_spv"),
    ),
    "MdlDtaCreditcard" => array(
        "label" => "Credit Card",
        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
        "creators" => array("c_data", "c_holding"),
        "creatorAdmins" => array(),
        "updaters" => array("c_data", "c_holding"),
        "updaterAdmins" => array(),
        "deleters" => array("c_data", "c_holding"),
        "deleterAdmins" => array("c_data", "root", "c_holding"),
        "historyViewers" => array("c_data", "c_holding", "c_owner"),
    ),
);

$config['dataRelation'] = array(
    "MdlCustomer" => array(
        "MdlCustomerAddress" => array(
            "label" => "delivery address",
            "targetField" => "extern_id",
        ),
        "MdlCustomerBillAddress" => array(
            "label" => "billing address",
            "targetField" => "extern_id",
        ),
        "MdlOnlineCustomer" => array(
            "label" => "Marketplace Sudah direlasikan",
            "targetField" => "customers_id",
            "target" => "id",
            "allowEdit" => true,
        ),
    ),
    "MdlCustomerExport" => array(
        "MdlCustomerAddress" => array(
            "label" => "delivery address",
            "targetField" => "extern_id",
        ),
        "MdlCustomerBillAddress" => array(
            "label" => "billing address",
            "targetField" => "extern_id",
        ),

    ),

    "MdlSupplier" => array(
        "MdlProdukPerSupplier" => array(
            "label" => "produk terelasi",
            "targetField" => "suppliers_id",
            "allowEdit" => true,
            "allowEdit_label" => "remove",
            "allowEdit_notif" => "clik untuk melepas relasi dengan supplier",
            "edit_link" => "EditRelasi?",
        ),

    ),
    // "MdlCabang" => array(
    //     "MdlGudang" => array(
    //         "label" => "connected warehouse",
    //         "targetField" => "cabang_id",
    //     ),
    //
    // ),
    "MdlProduk" => array(
        "MdlConnectedDiscount" => array(
            "label" => "connected discount",
            "targetField" => "produk_id",
        ),
    ),
    "MdlProdukRakitan" => array(
        "MdlProdukVarian" => array(
            "label" => "varian_produk",
            "targetField" => "produk_id",
        ),
    ),

    "MdlProdukRakitanPreBiaya" => array(
        "MdlBiayaProduksi_prebiaya" => array(
            "label" => "connected production expense",
            "targetField" => "pre_biaya_id",
            //            "targetField" => "biayaproduksi_id",
        ),
    ),
);

$config['dataTool'] = array(
    "MdlEmployee" => array(
        "passwordResetor" => array(
            "kolom" => "password",
            "label" => "reset password",
            "icon" => "fa-bolt",
            "target" => "Login/resetorPwdAdmin",
            "srcKey" => "id",
            // "input"   => "readonly",
            "status" => "edit",
            "message" => "Password will be returned to default {def_password}",
        ),
    ),
    "MdlEmployeeCabang" => array(
        "passwordResetor" => array(
            "kolom" => "password",
            "label" => "reset password",
            "icon" => "fa-bolt",
            "target" => "Login/resetorPwdAdmin",
            "srcKey" => "id",
            // "input"   => "readonly",
            "status" => "edit",
            "message" => "Password will be returned to default {def_password}",
        ),
    ),
    "MdlEmployeeKirim" => array(
        "passwordResetor" => array(
            "kolom" => "password",
            "label" => "reset password",
            "icon" => "fa-bolt",
            "target" => "Login/resetorPwdAdmin",
            "srcKey" => "id",
            // "input"   => "readonly",
            "status" => "edit",
            "message" => "Password will be returned to default {def_password}",
        ),
    ),
    "MdlEmployeeFreelanceCabang" => array(
        "passwordResetor" => array(
            "kolom" => "password",
            "label" => "reset password",
            "icon" => "fa-bolt",
            "target" => "Login/resetorPwdAdmin",
            "srcKey" => "id",
            // "input"   => "readonly",
            "status" => "edit",
            "message" => "Password will be returned to default {def_password}",
        ),
    ),
    "MdlReseller" => array(
        "passwordResetor" => array(
            "kolom" => "password",
            "label" => "reset password",
            "icon" => "fa-bolt",
            "target" => "Login/resetorPwdAdmin",
            "srcKey" => "id",
            // "input"   => "readonly",
            "status" => "edit",
            "message" => "Password will be returned to default {def_password}",
        ),
    ),
);

$config['dataExtRelation'] = array(
    "MdlProduk" => array(
        "prices" => array(
            "label" => "product prices",
            "target" => "Spread/index/produk/cabang/hargaProduk?attached=1",
            "srcKey" => "id",
        ),
        "images" => array(
            "label" => "product Images",
            "target" => "Images/index/produk/cabang/imagesProduk?attached=1",
            "srcKey" => "id",
        ),
        "vendor" => array(
            "label" => "connected vendors",
            "target" => "ProdukVendor/index/suppliers/cabang/ProdukPerSupplier?attached=1",
            "srcKey" => "id",
        ),
        "satuan" => array(
            "label" => "relasi multi satuan",
            "target" => "Satuan/index/Produk/ProdukSatuanRelasi?attached=1",
            "srcKey" => "id",
        ),
    ),
//    "MdlProdukProject" => array(
//        "teamWork" => array(
//            "label" => "Anggaran  dan tim kerja",
//            "target" => "Teamwork/edit?attached=1",
//            "srcKey" => "id",
//        ),
//    ),
    "MdlSupplies" => array(
        "prices" => array(
            "label" => "product prices",
            "target" => "Spread/index/supplies/cabang/hargaSupplies?attached=1",
            "srcKey" => "id",
        )
    ),
    "MdlProdukRakitan" => array(
        "prodEditor" => array(
            "label" => "product components",
            "target" => "ProductEditor/edit?attached=1",
            "srcKey" => "produk_id",
        ),
        "images" => array(
            "label" => "product Images",
            "target" => "Images/index/produk/cabang/imagesProduk?attached=1",
            "srcKey" => "id",
        ),
        "prices" => array(
            "label" => "product prices",
            "target" => "Spread/index/produkRakitan/cabang/hargaProdukRakitan?attached=1",
            "srcKey" => "id",
        )
    ),
//    "MdlProduk_Paket_Project" => array(
//        "prodEditor" => array(
//            "label" => "product components",
//            "target" => "ProductEditorPaket/edit?attached=1",
//            "srcKey" => "produk_id",
//        ),
//    ),
//    "MdlProdukPaket" => array(
//        "prodEditor" => array(
//            "label" => "package components",
//            "target" => "ProductPkgEditor/edit?attached=1",
//            "srcKey" => "produk_id",
//        ),
//        "images" => array(
//            "label" => "product Images",
//            "target" => "Images/index/produk/cabang/imagesProduk?attached=1",
//            "srcKey" => "id",
//        ),
//        "prices" => array(
//            "label" => "paket prices",
//            "target" => "Spread/index/produkPaket/cabang/hargaProdukPaket?attached=1",
//            "srcKey" => "id",
//        )
//    ),
    "MdlProdukKomposit" => array(
        "prodEditor" => array(
            "label" => "Product Komposit components",
            "target" => "ProductKompositEditor/edit?attached=1",
            "srcKey" => "produk_id",
        ),
        "images" => array(
            "label" => "product Images",
            "target" => "Images/index/produk/cabang/imagesProduk?attached=1",
            "srcKey" => "id",
        ),
//        "prices" => array(
//            "label" => "produk komposit prices",
//            "target" => "Spread/index/produkKomposit/cabang/hargaProdukKomposit?attached=1",
//            "srcKey" => "id",
//        )
    ),

    "MdlDtaBiayaProduksi" => array(
        "prodEditor" => array(
            "label" => "Detail Komponen Biaya",
            "target" => "DetailsBiayaEditor/edit?attached=1",
            "srcKey" => "produk_id",
        ),
    ),

    "MdlDtaBiayaProduksiProject" => array(
        "prodEditor" => array(
            "label" => "Detail Komponen Biaya",
            "target" => "DetailsBiayaEditor/edit?attached=1",
            "srcKey" => "produk_id",
        ),
    ),
    "MdlEmployee" => array(
        //        "log" => array(
        //            "label" => "my activity",
        //            "target" => "Data/view/ActivityLog",
        //            "srcKey" => "id",
        //
        //        ),
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1",
            "srcKey" => "id",
        ),
        "data" => array(
            "label" => "Hak Akses Master Data",
            "target" => "Addons/DataAccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployee__shadow" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1",
            "srcKey" => "id",
        ),
        "data" => array(
            "label" => "Hak Akses Master Data",
            "target" => "Addons/DataAccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployeeCabang" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
        "data" => array(
            "label" => "Hak Akses Master Data",
            "target" => "Addons/DataAccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployeeFreelanceCabang" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployeeCabang__shadow" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
        "data" => array(
            "label" => "Hak Akses Master Data",
            "target" => "Addons/DataAccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployeeGudang" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeGudang",
            "srcKey" => "id",
        ),
        "data" => array(
            "label" => "Hak Akses Master Data",
            "target" => "Addons/DataAccessRight/edit?attached=1&ctrl=MdlEmployeeCabang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployeeGudang_shadow" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeGudang",
            "srcKey" => "id",
        ),
    ),
    "MdlEmployeeGudangFase" => array(
        "access" => array(
            "label" => "Custom Access rights",
            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeGudangFase",
            "srcKey" => "id",
        ),
    ),
//    "MdlEmployeeKirim" => array(
//        "access" => array(
//            "label" => "Custom Access rights",
//            "target" => "Addons/AccessRight/edit?attached=1&ctrl=MdlEmployeeKirim",
//            "srcKey" => "id",
//        ),
//    ),
    "MdlSupplier" => array(
        "reabate" => array(
            "label" => "rebate",
            "target" => "diskon/Setting/viewSupplierRebate?attached=1",
            "srcKey" => "id",
        ),
    ),
);

$config['dataToXlsx'] = array(
    "Spread" => array(
        "lable" => "download",
        "target" => "/ExcelWriter/harga"
    )
);

$config['dataExtended'] = array(

    "MdlEmployee" => array(
        "access" => "MdlAccessRight",
        "aliasPlace" => "userGroup",
    ),

    "MdlEmployeeCabang" => array(
        "access" => "MdlAccessRight",
        "aliasPlace" => "userGroup_cabang",
    ),
    "MdlEmployeeCabang__shadow" => array(
        "access" => "MdlAccessRight",
        "aliasPlace" => "userGroup_cabang",
    ),
    "MdlEmployeeGudang" => array(
        "access" => "MdlAccessRight",
        "aliasPlace" => "userGroup_gudang",
    ),
    "MdlEmployeeGudang__shadow" => array(
        "access" => "MdlAccessRight",
        "aliasPlace" => "userGroup_gudang",
    ),

);

//$config['data_type'] = array(
//    "produk" => array(
//        "folder",
//        "item",
//    ),
//    "bahan" => array(
//        "folder",
//        "item",
//    ),
//    "bank" => array(
//        "folder",
//        "item",
//    ),
//    "ekspedisi" => array(
//        "folder",
//        "item",
//    ),
//);

$config['dataPostProcessors'] = array(
    //tal matiin dulu
    //    "MdlHargaProduk"=>array(
    //        "PackagePriceUpdater"
    //    ),

    "MdlCustomer" => array(
        "AddressUpdater",
    ),
    "MdlCustomerExport" => array(
        "AddressUpdater",
    ),
    "MdlPreCustomer" => array(
        "AddressUpdater",
    ),


);

$config['conditional'] = array(
    "produk" => "cabang_id",
    "supplies" => "cabang_id",
    "produkRakitan" => "cabang_id",
    "produkPaket" => "cabang_id",
    "produkSupplier" => "suppliers_id",

)

?>