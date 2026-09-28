<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 9:04 PM
 */


$config['userJenis'] = array(//==untuk keperluan konversi yang lama ke multi
    "admin" => "admin",
    "diskon_oto" => "diskon_oto",
    "finance" => "finance",
    "kasir_in" => "kasir_in",
    "kasir_penj" => "kasir_penj",
    "manager" => "manager",
    "seller" => "seller",
    "spv_gudang" => "spv_gudang",
    "spv_pemb" => "spv_pemb",
    "spv_penj" => "spv_penj",
    "spv_prod" => "spv_prod",
    "superman" => "superman",
    "supplier" => "supplier",
);


$config['userGroup_dev'] = array(
    "root" => "root",
);


$config['userGroup'] = array(
    "c_laporan" => "laporan",
    "c_owner" => "owner",
    "c_holding" => "holding",
    "c_special" => "special",//dimatiin dulu
    "c_finance" => "finance (center)",
    "c_finance_spv" => "finance spv (center)",
    "c_gudang" => "center warehousing",
    "c_gudang_spv" => "center warehousing spv",
    "c_purchasing" => "purchasing",
    "c_purchasing_adm" => "purchasing admin",
    "c_purchasing_spv" => "purchasing spv",

    "c_data" => "data manager",
    "c_audit" => "audit pusat",

//    "c_export" => "sales export",
//    "c_export_spv" => "export spv",
//    "c_seller" => "seller",
//    "c_seller_spv" => "spv seller",
//    "c_seller_entry" => "seller (data entry)",
//    "c_kasir" => "cashier",
//    "c_gudang"        => "warehousing",


//    "o_gudang_spv"    => "branch warehousing spv",
    "c_gudang_out" => "center warehousing (out)",
    "c_katalog" => "view katalog",

//    "superman" => "superman",
);


$config['userGroup_cabang'] = array(
    "o_seller" => "seller",
    "o_seller_spv" => "seller spv",
    "o_seller_entry" => "seller (data entry)",
    "o_kasir" => "branch cashier",
    "o_gudang" => "branch warehousing",
    "o_finance" => "finance (branch)",
    "o_finance_spv" => "finance spv(branch)",
    "o_gudang_spv" => "branch warehousing spv",
    "o_gudang_out" => "branch warehousing (stock confirm)",
    "o_export" => "international seller",
    "o_export_spv" => "international spv",
    "o_project" => "pelaksana project",
    "o_project_spv" => "spv project",
    "o_project_mgr" => "manager project",
    "o_audit" => "audit cabang",

    "p_gudang" => "production warehousing",
    "p_gudang_spv" => "production warehousing spv",
    "p_produksi" => "production",
    "p_produksi_spv" => "production spv",
);


$config['userGroup_gudang'] = array(
    "w_gudang" => "warehousing",
    "w_gudang_spv" => "warehousing spv",
);


$config['userGroup_editElementAllowed'] = array(
    "c_purchasing_spv",
    "c_owner",
    "c_holding",
    "c_finance",
    "c_finance_spv",
    "o_seller_spv",
    "o_finance",
    "o_finance_spv",
    "o_export_spv",
    "o_gudang_spv",
    "w_gudang_spv",
);


$config['userGroup_root'] = array(
    "root" => "system administrator",
);


$config['groupLandingPages'] = array(
    "o_kasir" => "Transaksi/createForm/582",
    "o_seller" => "Transaksi/createForm/582",
    "o_seller_entry" => "Transaksi/createForm/581",
);


$config['validatedPages'] = array(
    "Transaksi/createForm",
    "Transaksi/selectPaymentExternSrc",
    "Ledger/viewBalances_l1",
    "Ledger/viewMoves_l1",
    "Ledger/viewMoves_l2",
);


$config['userGroup_jurnal'] = array(
    "c_finance",
    "c_holding",
    "c_owner",
    "o_finance",
    "o_seller_spv",
);


$config['userGroup_blacklist'] = array(
    "c_gudang",
    "c_gudang_spv",
    "o_gudang",
    "o_gudang_spv",
    "o_gudang_out",
//    "c_owner",
//    "o_finance",
//    "o_seller_spv",
);


$config['userPlace_allowed'] = array(
    "center",
    "branch",
);


$config['limitBrach'] = array(
    "MdlCabang" => array(
        "limit" => "8",
        "limitNotif" => "(Limit jumlah cabang sudah tercapai, silahkan Non aktifkan cabang yang sudah tidak digunakan/ hubungi developer jika ingin tambahan limit)"
    ),
);

$config['roleDivision'] = array(

    // ===========================
    // SYSTEM
    // ===========================
    "root"              => "system",
    "superman"          => "system",

    // ===========================
    // MANAGEMENT
    // ===========================
    "c_owner"           => "management",
    "c_holding"         => "management",
    "c_special"         => "management",
    "manager"           => "management",

    // Special Management
    "c_audit"           => "management",
    "c_data"            => "management",

    // ===========================
    // FINANCE
    // ===========================
    "c_finance_spv"     => "finance",
    "c_finance"         => "finance",
    "o_finance_spv"     => "finance",
    "o_finance"         => "finance",

    // ===========================
    // PURCHASING
    // ===========================
    "c_purchasing_spv"  => "purchasing",
    "c_purchasing"      => "purchasing",
    "c_purchasing_adm"  => "purchasing",
    "spv_pemb"          => "purchasing",

    // ===========================
    // GUDANG
    // ===========================
    "c_gudang_spv"      => "gudang",
    "c_gudang"          => "gudang",
    "c_gudang_out"      => "gudang",

    "spv_gudang"        => "gudang",
    "o_gudang_spv"      => "gudang",
    "o_gudang_out"      => "gudang",
    "o_gudang"          => "gudang",

    "w_gudang_spv"      => "gudang",
    "w_gudang"          => "gudang",

    // ===========================
    // SELLING
    // ===========================
    "spv_penj"          => "selling",
    "o_seller_spv"      => "selling",
    "o_seller"          => "selling",
    "o_seller_entry"    => "selling",
    "seller"            => "selling",

    // ===========================
    // PRODUCTION
    // ===========================
    "spv_prod"          => "production",
    "p_produksi_spv"    => "production",
    "p_produksi"        => "production",
    "p_gudang_spv"      => "production",
    "p_gudang"          => "production",

    // ===========================
    // EXPORT
    // ===========================
    "o_export_spv"      => "export",
    "o_export"          => "export",

    // ===========================
    // PROJECT
    // ===========================
    "o_project_mgr"     => "project",
    "o_project_spv"     => "project",

    // ===========================
    // AUDIT
    // ===========================
    "o_audit"           => "audit",

    // ===========================
    // KATALOG & REPORT
    // ===========================
    "c_katalog"         => "katalog",
    "c_laporan"         => "report",

    // ===========================
    // CASHIER
    // ===========================
    "kasir_in"          => "cashier",
    "kasir_penj"        => "cashier",
    "o_kasir"           => "cashier",

    // ===========================
    // OTHERS
    // ===========================
    "diskon_oto"        => "misc",
    "supplier"          => "supplier",
);

$config['roleHierarchy'] = array(

    // SYSTEM
    "root"              => 1,
    "superman"          => 1,

    // MANAGEMENT
    "c_owner"           => 2,
    "c_holding"         => 2,
    "c_special"         => 2,
    "manager"           => 2,

    // AUDIT (CENTER)
    "c_audit"           => 3,

    // DATA
    "c_data"            => 4,

    // FINANCE (CENTER)
    "c_finance_spv"     => 5,
    "c_finance"         => 6,

    // PURCHASING
    "c_purchasing_spv"  => 7,
    "spv_pemb"          => 7,
    "c_purchasing"      => 8,
    "c_purchasing_adm"  => 8,

    // GUDANG (CENTER)
    "c_gudang_spv"      => 9,
    "spv_gudang"        => 9,
    "c_gudang"          => 10,
    "c_gudang_out"      => 10,

    // SELLING (CENTER)
    "spv_penj"          => 11,

    // PRODUCTION (CENTER)
    "spv_prod"          => 13,

    // KATALOG & REPORT
    "c_katalog"         => 15,
    "c_laporan"         => 15,

    // AUDIT (OUTLET)
    "o_audit"           => 16,

    // PROJECT MANAGEMENT
    "o_project_mgr"     => 17,
    "o_project_spv"     => 17,

    // SELLING (OUTLET)
    "o_seller_spv"      => 11,
    "o_seller"          => 12,
    "o_seller_entry"    => 12,
    "seller"            => 12,

    // FINANCE (OUTLET)
    "o_finance_spv"     => 5,
    "o_finance"         => 6,

    // EXPORT (OUTLET)
    "o_export_spv"      => 19,
    "o_export"          => 19,

    // GUDANG (OUTLET)
    "o_gudang_spv"      => 20,
    "o_gudang_out"      => 20,
    "o_gudang"          => 20,

    // CASHIER
    "kasir_in"          => 21,
    "kasir_penj"        => 21,
    "o_kasir"           => 21,

    // PRODUCTION (OUTLET)
    "p_gudang_spv"      => 13,
    "p_produksi_spv"    => 13,
    "p_gudang"          => 14,
    "p_produksi"        => 14,

    // WAREHOUSE SPECIAL
    "w_gudang_spv"      => 22,
    "w_gudang"          => 22,

    // MISC
    "diskon_oto"        => 23,

    // SUPPLIER
    "supplier"          => 24,
);


$config['divisionLevel'] = array(
    "system"        => 1,
    "management"    => 2,
    "audit"         => 3,
    "data"          => 4,
    "finance"       => 5,
    "purchasing"    => 6,
    "gudang"        => 7,
    "selling"       => 8,
    "production"    => 9,
    "project"       => 10,
    "export"        => 11,
    "katalog"       => 12,
    "report"        => 13,
    "cashier"       => 14,
    "supplier"      => 15,
    "misc"          => 16
);


$config['functionLevel'] = [
    'root'              => 1,
    'superman'          => 2,

    'c_owner'           => 3,
    'c_holding'         => 4,
    'c_special'         => 5,
    'manager'           => 6,
    'c_data'            => 7,

    'c_audit'           => 8,
    'o_audit'           => 9,

    'c_finance_spv'     => 10,
    'o_finance_spv'     => 11,
    'c_finance'         => 12,
    'o_finance'         => 13,
    'kasir_in'          => 14,
    'kasir_penj'        => 15,
    'o_kasir'           => 16,

    'c_purchasing_spv'  => 17,
    'spv_pemb'          => 18,
    'c_purchasing'      => 19,
    'c_purchasing_adm'  => 20,

    'c_gudang_spv'      => 21,
    'spv_gudang'        => 22,
    'o_gudang_spv'      => 23,
    'w_gudang_spv'      => 24,
    'c_gudang'          => 25,
    'c_gudang_out'      => 26,
    'o_gudang'          => 27,
    'o_gudang_out'      => 28,
    'w_gudang'          => 29,

    'spv_penj'          => 30,
    'o_seller_spv'      => 31,
    'seller'            => 32,
    'o_seller'          => 33,
    'o_seller_entry'    => 34,

    'spv_prod'          => 35,
    'p_produksi_spv'    => 36,
    'p_gudang_spv'      => 37,
    'p_produksi'        => 38,
    'p_gudang'          => 39,

    'o_project_mgr'     => 40,
    'o_project_spv'     => 41,
    'o_project'         => 42,

    'o_export_spv'      => 43,
    'o_export'          => 44,

    'c_katalog'         => 45,
    'c_laporan'         => 46,

    'diskon_oto'        => 47,
    'supplier'          => 48,
];
