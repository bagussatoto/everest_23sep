<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/2/2019
 * Time: 7:42 PM
 */
$config['menuIcon'] = array(
    "transaksi" => array(
        "466" => "fa fa-icon",
    ),
    "data" => array(
        "MdlCustomer" => "fa fa-icon",
    ),
);

// region untuk menu other
$config['availMenu'] = array(
    "rl" => array(
        "label" => "profit & loss report",
        "icon" => "fa fa-line-chart",
        "target" => "Rugilaba/viewPL",
        //        "target" => "Rugilaba/viewPL2",
    ),
    "nrc" => array(
        "label" => "balance",
        "icon" => "fa fa-balance-scale",
        "target" => "Neraca/viewNeraca",
    ),
    "bls" => array(
        "label" => "trial balance",
        "icon" => "fa fa-balance-scale",
        "target" => "Neraca/viewBalanceSheet",
    ),
    "bls_bulanan" => array(
        "label" => "mutasi rekening (bulanan)",
        "icon" => "fa fa-fighter-jet",
        "target" => "Neraca/viewBalanceSheetBulanan",
    ),
    "rl_con" => array(
        "label" => "consolidated profit & loss report (monthly)",
        "icon" => "fa fa-line-chart",
//        "target" => "Rugilaba/viewPL_consolidated",
        "target" => "Rugilaba/viewRlBulanan",
    ),
    "nrc_con" => array(
        "label" => "consolidated balance (monthly)",
        "icon" => "fa fa-balance-scale",
        "target" => "Neraca/viewNeraca_consolidated",
    ),
    "rl_con_tahunan" => array(
        "label" => "consolidated profit & loss report (yearly)",
        "icon" => "fa fa-line-chart",
        "target" => "Rugilaba/viewPL_consolidatedTahunan",
    ),
    "nrc_con_tahunan" => array(
        "label" => "consolidated balance (yearly)",
        "icon" => "fa fa-balance-scale",
        "target" => "Neraca/viewNeraca_consolidatedTahunan",
    ),
//    "rl_realtime" => array(
//        "label" => "realtime profit & loss report",
//        "icon" => "fa fa-line-chart",
//        "target" => "Rugilaba/viewPLRealtime",
//    ),
//    "nrc_realtime" => array(
//        "label" => "realtime balance",
//        "icon" => "fa fa-balance-scale",
//        "target" => "Neraca/viewNeracaRealtime",
//    ),
    "rl_ytd" => array(
        "label" => "profit & loss report (year to date)",
        "icon" => "fa fa-line-chart",
        "target" => "Rugilaba/viewPLYearToDate",
    ),
    "nrc_ytd" => array(
        "label" => "balance (year to date)",
        "icon" => "fa fa-balance-scale",
        "target" => "Neraca/viewNeracaYearToDate",
    ),
    "rl_con_ytd" => array(
        "label" => "consolidated profit & loss report (year to date)",
        "icon" => "fa fa-line-chart",
        "target" => "Rugilaba/viewPLYearToDate_consolidated",
    ),
    "nrc_con_ytd" => array(
        "label" => "consolidated balance (year to date)",
        "icon" => "fa fa-balance-scale",
        "target" => "Neraca/viewNeracaYearToDate_consolidated",
    ),

    "rl_branch_con" => array(
        "label" => "consolidated profit & loss report branch (monthly)",
        "icon" => "fa fa-line-chart",
        "target" => "Rugilaba/viewPL_consolidated",
    ),

    "jurnal" => array(
        "label" => "jurnal",
        "icon" => "fa fa-calculator",
        "target" => "Neraca/viewJurnal",
    ),
    "rst" => array(
        "label" => "Resetor Transaksi",
        "icon" => "fa  fa-trash-o",
        "target" => "ResetorTransaksi/view",
    ),
    "katalog_produk_aktif" => array(
        "label" => "product stock active",
        "icon" => "fa fa-heart",
        "target" => "Katalog/viewProdukAktif",
    ),
    "katalog_produk" => array(
        "label" => "product catalog",
        "icon" => "fa fa-star",
        "target" => "Katalog/viewProduk",
    ),
    "katalog_supplies" => array(
        "label" => "supplies catalog",
        "icon" => "fa fa-sun-o",
        "target" => "Katalog/viewSupplies",
    ),
    "katalog_produk_produksi" => array(
        "label" => "produk hasil produksi",
        "icon" => "fa fa-star",
        "target" => "Katalog/viewProdukPabrik",
    ),
    "persediaan_produk" => array(
        "label" => "product inventory (detail)",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
    ),
    "persediaan_produk_rakitan" => array(
        "label" => "product assembled inventory ",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk rakitan",
    ),
    "persediaan_produk_gudang" => array(
        "label" => "product inventories",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_t1/RekeningPembantuProduk/persediaan_produk",
    ),
    "movement_produk" => array(
        "label" => "product movements",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovement/fg",
    ),
    "movement_produk_group" => array(
        "label" => "group product movements ",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovementGroup/fg",
    ),
    "data_produk" => array(
        "label" => "product datas",
        "icon" => "fa fa-diamond",
        // "target" => "Ledger/viewMovement/fg",
        "target" => "Katalog/view/Produk",
    ),
    "movement_rakitan" => array(
        "label" => "asamble movements",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovement/rk",
    ),
    "movement_rakitan_group" => array(
        "label" => "group asamble movements",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovementGroup/rk",
    ),
    "movement_supplies" => array(
        "label" => "supplies movements",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovement/sp",
    ),
    "movement_supplies_group" => array(
        "label" => "group supplies movements (bahan baku)",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovementGroup/sp",
    ),
    "movement_supplies_proses_group" => array(
        "label" => "group supplies movements (produksi)",
        "icon" => "fa fa-fighter-jet",
        "target" => "Ledger/viewMovementGroup/sp_proses",
    ),
    "persediaan_supplies" => array(
        "label" => "supplies inventory",
        "icon" => "fa fa-folder",
        // "target" => "Stok/viewStocks/Supplies/RekeningPembantuSupplies/persediaan supplies",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplies/persediaan supplies",
    ),
    "harga_produk" => array(
        "label" => "product prices",
        "icon" => "fa fa-sort-numeric-asc",
        "target" => "Spread/index/produk/cabang/hargaProduk",
    ),
    "harga_supplies" => array(
        "label" => "supply prices",
        "icon" => "fa fa-sort-numeric-asc",
        "target" => "Spread/index/supplies/cabang/hargaSupplies",
    ),
    "harga_rakitan" => array(
        "label" => "assembled product prices",
        "icon" => "fa fa-sort-numeric-asc",
        "target" => "Spread/index/produkRakitan/cabang/hargaProdukRakitan",
    ),
    "harga_paket" => array(
        "label" => "package product prices",
        "icon" => "fa fa-sort-numeric-asc",
        "target" => "Spread/index/produkPaket/cabang/hargaProdukPaket",
    ),
    "harga_komposit" => array(
        "label" => "product composite prices",
        "icon" => "fa fa-sort-numeric-asc",
        "target" => "Spread/index/produkKomposit/cabang/hargaProdukKomposit",
    ),
    "harga_vendor" => array(
        "label" => "vendor prices",
        "icon" => "fa fa-sort-numeric-asc",
        "target" => "Spread/index/produkSupplier/suppliers/hargaProdukPerSupplier",
    ),
    "st_locker" => array(
        "label" => "active stocks",
        "icon" => "fa fa-folder",
        "target" => "StockLocker/viewCurrentLockers",
    ),
    "profile" => array(
        "label" => "my profile",
        "icon" => "fa fa-user",
        "target" => "Data/myProfile/User",
    ),
    "overdue_release" => array(
        "label" => "Otorisasi Overdue",
        "icon" => "fa fa-user",
        "target" => "OverDue_releaser/View",
    ),
    "depresiasi" => array(
        "label" => "Asset Management",
        "icon" => "fa fa-sort-alpha-desc",
        "target" => "SetupDepresiasi/view/Assets",
    ),
    "loaninterest" => array(
        "label" => "Setup Loan Interest",
        "icon" => "fa fa-money",
        "target" => "SetupLoanInterest/view/",
    ),
    "bungapihak3" => array(
        "label" => "Setup Bunga Pihak 3",
        "icon" => "fa fa-money",
        "target" => "SetupBungaPihak3/view/",
    ),
    "stok_opname_rakitan" => array(
        "label" => "stok opname rakitan",
        "icon" => "fa fa-sort-alpha-desc",
        "target" => "Opname/index/ProdukRakitan",
    ),
    "stok_opname" => array(
        "label" => "stok opname produk",
        "icon" => "fa fa-sort-alpha-desc",
        "target" => "Opname/index/Produk",
    ),
    "stok_opname_supplies" => array(
        "label" => "stok opname supplies",
        "icon" => "fa fa-sort-alpha-desc",
        "target" => "Opname/index/Supplies",
    ),

    "efisiensi_biaya" => array(
        "label" => "Efisiensi Biaya",
        "icon" => "fa fa-fighter-jet",
        "target" => "Neraca/viewEfisiensiBiaya/bom",
    ),

    // tool untuk checking.....................
    "checker_transaksi" => array(
        "label" => "checker transaksi",
        "icon" => "fa fa-folder",
//        "target" => "Tool/viewCheckerTransaksi",
        "target" => "Tool/viewTransaksi",
    ),
    "checker_sum_transaksi" => array(
        "label" => "checker summary transaksi",
        "icon" => "fa fa-folder",
        "target" => "Tool/viewCheckerSumTransaksi",
    ),
    "checker_produk" => array(
        "label" => "checker product",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedProduk",
    ),
    "checker_supplies" => array(
        "label" => "checker supplies",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedSupplies",
    ),
    "checker_supplies_proses" => array(
        "label" => "checker supplies dalam proses",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedSuppliesProses",
    ),
    "checker_kas" => array(
        "label" => "checker cash",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedKas",
    ),
    "checker_rekening_koran" => array(
        "label" => "checker rekening koran",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedRekeningKoran",
    ),
    "checker_uang_muka" => array(
        "label" => "checker uang muka",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedUangMuka",
    ),
    "checker_uang_muka_valas" => array(
        "label" => "checker uang muka valas",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedUangMukaValas",
    ),
    "checker_stock_valas" => array(
        "label" => "checker stock valas",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewUnsyncedValas",
    ),
    "checker_hutang_dagang" => array(
        "label" => "checker hutang dagang",
        "icon" => "fa fa-numeric-asc",
        "target" => "Tool/viewUnsyncedHutangDagang",
    ),
    "checker_piutang_dagang" => array(
        "label" => "checker piutang dagang",
        "icon" => "fa fa-numeric-asc",
        "target" => "Tool/viewUnsyncedPiutangDagang",
    ),
    "encode" => array(
        "label" => "encode to blob",
        "icon" => "fa fa-archive",
        "target" => "Tool/encodeToArray",
    ),
    "decode" => array(
        "label" => "decode from blob",
        "icon" => "fa fa-archive",
        "target" => "Tool/decodeFromArray",
    ),
    "registry" => array(
        "label" => "show registry",
        "icon" => "fa fa-archive",
        "target" => "Tool/showRegistry",
    ),
    "rekening_koran" => array(
        "label" => "Realisasi Rekening koran",
        "icon" => "fa fa-archive",
        "target" => "RekeningKoran/index",),

    "cli" => array(
        "label" => "checker cli transaksi",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewCliTransaksi",
    ),
    "cli_time" => array(
        "label" => "checker cli time",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewCliTransaksiTime",
    ),
    "checker_fifo_produk" => array(
        "label" => "checker fifo produk",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewFifoProduk",
    ),
    "checker_fifo_supplies" => array(
        "label" => "checker fifo supplies",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewFifoSupplies",
    ),
    "employee" => array(
        "label" => "checker employee",
        "icon" => "fa fa-home",
        "target" => "Tool/viewEmployee",
    ),
    "cli_penjualan" => array(
        "label" => "cli penjualan",
        "icon" => "fa fa-home",
        "target" => "Tool/viewCheckerReportToMongo",
    ),

    // https://san.mayagrahakencana.com/Tool/viewTransaksi_ui
    "activity_log" => array(
        "label" => "My Activity log",
        "icon" => "fa fa-archive",
        "target" => "ActivityLog/viewLog",
    ),
    "grouping_menu" => array(
        "label" => "grouping menu",
        "icon" => "fa fa-trash-o",
        "target" => "Tool/viewTransaksi_ui",
    ),

);

$config['menu'] = array(
    "root" => array(
        "rst",


        "checker_transaksi",
        "checker_produk",
        "checker_supplies",
        "checker_kas",
        "checker_hutang_dagang",
        "checker_piutang_dagang",

        "encode",
        "decode",
        "registry",
        "grouping_menu",
    ),
    "c_holding" => array(
//        "mong_report",
        "rl",
        "nrc",
        "rl_ytd",
        "nrc_ytd",
        "rl_con",
        "nrc_con",
        "rl_con_ytd",
        "nrc_con_ytd",
        "rl_con_tahunan",
        "nrc_con_tahunan",

        "rl_branch_con",

//        "rl_realtime",
//        "nrc_realtime",
        "bls",
        "bls_bulanan",
        "stok",
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
//        "movement_rakitan_group",
        "movement_produk_group",
        "movement_supplies_group",
        "persediaan_produk_gudang",
        "harga_produk",
        "harga_supplies",
        "harga_rakitan",
        "harga_paket",
        "harga_komposit",
        "harga_vendor",
        "stok_opname",
        "katalog_supplies",
        "katalog_produk",
        "katalog_produk_produksi",
        "stok_opname",
        "depresiasi",
        "loaninterest",
        "bungapihak3",

        "stok_opname_supplies",
        "jurnal",
        "rekening_koran"
    ),
    "c_purchasing" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
    ),
    "c_gudang_spv" => array(
        "persediaan_produk",
        "movement_produk_group",
        "movement_supplies_group",
        "persediaan_produk_gudang",
    ),
    "c_finance" => array(
        "rl",
        "nrc",
        "rl_ytd",
        "nrc_ytd",
        "rekening_koran",
    ),

    "w_gudang" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "stok_opname_rakitan",
        "stok_opname",
    ),
    "w_gudang_spv" => array(
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_gudang",
//        "movement_supplies",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "stok_opname_rakitan",
        "stok_opname",
    ),

    "p_gudang" => array(
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_rakitan",
//        "movement_supplies",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_produk_group",
        "movement_supplies_group",
        "movement_supplies_proses_group",
        "katalog_supplies",
        "katalog_produk_produksi",
//        "katalog_produk",
        "stok_opname_rakitan",
        "stok_opname",

    ),
    "p_gudang_spv" => array(
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_rakitan",
//        "movement_supplies",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_produk_group",
        "movement_supplies_group",
        "movement_supplies_proses_group",
        "katalog_supplies",
        "katalog_produk_produksi",
//        "katalog_produk",
        "stok_opname_rakitan",
        "stok_opname",
    ),
    "p_produksi" => array(
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_rakitan",
//        "movement_supplies",
//        "movement_produk",
//        "movement_rakitan",

        "movement_rakitan_group",
        "movement_produk_group",
        "movement_supplies_group",
        "movement_supplies_proses_group",
        "katalog_supplies",
        "katalog_produk_produksi",
//        "katalog_produk",
        "stok_opname_rakitan",
        "stok_opname",
    ),
    "p_produksi_spv" => array(
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_rakitan",
//        "movement_supplies",
//        "movement_produk",

        "movement_rakitan_group",
        "movement_produk_group",
        "movement_supplies_group",
        "movement_supplies_proses_group",
        "katalog_supplies",
        "katalog_produk_produksi",
//        "katalog_produk",
        "bls",
        "efisiensi_biaya",
        "harga_supplies",
        "stok_opname_rakitan",
        "stok_opname",
    ),

    "o_finance" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
//        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "overdue_release",
        //        "rl",
        //        "nrc",
        // "jurnal",
        "bls",
        "bls_bulanan",
        "depresiasi",
        "loaninterest",
        "bungapihak3",
//        "rl_realtime",
//        "nrc_realtime",
        "rl",
        "nrc",
        "rl_ytd",
        "nrc_ytd",
    ),
    "o_finance_spv" => array(
        // "overdue_release",
//        "rl_realtime",
//        "nrc_realtime",
        "rl",
        "nrc",
        "rl_ytd",
        "nrc_ytd",
//        "movement_rakitan_group",
        "movement_produk_group",
        "movement_supplies_group",
        "jurnal",
    ),
    "o_kasir" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
    ),
    "o_gudang" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
        "stok_opname_rakitan",
        "stok_opname",
        "stok_opname_supplies",
        "harga_supplies",
    ),
    "o_gudang_spv" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_supplies",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
        "harga_supplies",
    ),
    "o_gudang_out" => array(
        "persediaan_supplies",
        "persediaan_produk",
//        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "harga_supplies",
    ),
    "o_seller" => array(
        "katalog_produk",
        "movement_produk_group",
        // "overdue_release",
    ),
    "o_seller_spv" => array(
        "katalog_produk",
//        "movement_produk",
        "movement_produk_group",
        "persediaan_produk_gudang",
    ),

    "*" => array(//===every-one
        "activity_log",
        "profile",
        "katalog_produk_aktif",
        "data_produk",
        "logout",
    ),
);
// endregion untuk menu other

$config['menuRight'] = array(
    "top" => array(
        "home" => array(
            "status" => "active",
            "title" => "My Profile",
            "label" => "home",
            "icon" => "fa fa-home",
            "target" => "control-sidebar-home-tab",
            "src" => "MdlEmployee",
            "show" => array(
                "nama" => array(
                    "iconcss" => "fa-birthday-cake bg-yellow",
                    "title" => "Name",
                    "deskripsi" => "Akan muncul dlm tial log, maupun signature",
                ),
                "email" => array(
                    "iconcss" => "fa-envelope-o bg-light-blue",
                    "title" => "Email Address",
                    "deskripsi" => "digunakan untuk verifikasi jika Anda lupa password",
                ),
                "tlp_1" => array(
                    "iconcss" => "fa-phone bg-green",
                    "title" => "Phone",
                    "deskripsi" => "digunakan untuk verifikasi account",
                ),
                "password" => array(
                    "iconcss" => "fa-unlock-alt bg-red",
                    "title" => "Password",
                    "deskripsi" => "digunakan untuk verifikasi account",
                ),
            ),
        ),
        "setting" => array(
            "title" => "Recent Activity",
            "label" => "activity",
            "icon" => "fa fa-paw",
            "target" => "control-sidebar-activity-tab",
            "src" => "MdlActivityLog",
        ),
    ),
);

$config['subMenu'] = array(
    //    "Stok" => array(
    //        "produk" => array(
    //            "label" => " produk",
    //            "icon"   => "fa-diamond",
    //            "target" => "",
    //        ),
    //        "rakitan" => array(
    //            "label" => "produk assembling",
    //            "icon"   => "fa-object-group",
    //            "target" => "rakitan",
    //        ),
    //        "supplies" => array(
    //            "label" => " supplies",
    //            "icon"   => "fa-circle",
    //            "target" => "RekeningPembantuSupplies",
    //        ),
    //
    //        "opname" => array(
    //            "label" => "stok opname",
    //            "icon"   => "fa fa-sort-numeric-asc",
    //            "target" => "opname",
    //        ),
    //    ),
    "Opname" => array(
        "produk" => array(
            "label" => " produk",
            "icon" => "fa-diamond",
            "target" => "",
        ),
        "supplies" => array(
            "label" => " supplies",
            "icon" => "fa-circle",
            "target" => "",
        ),
    ),
);

/*---------------------------------
 * history-reporting menu
 * pengatuarn menu yg tampil pada masing masing group employee $config['onMenuReports']
*------------------------------------*/
$config['availMenuReports'] = array(
    "daily" => array(
        "label" => "Daily",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewDaily/582/daily",
    ),
    "weekly" => array(
        "label" => "Weekly",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewWeekly/582/weekly",
    ),
    "monthly" => array(
        "label" => "Log aktivitas penjualan bulanan",
        "icon" => "fa-calendar-check-o",
        // "target" => "ActivityReport/viewMonthly/582/monthly",
        "target" => "penjualan/ActivityReport/viewMonthly/582",
    ),
    "graphMonthly" => array(
        "label" => "Graph report",
        "icon" => "fa-calendar-check-o",
        // "target" => "ActivityReport/viewMonthly/582/monthly",
        "target" => "Bi/viewGraphSales",
    ),

    "perMonth" => array(
        "label" => "Month",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewMonth/582",
    ),
    // "penjualan" => array(
    //     "label" => "My Sales",
    //     "icon" => "fa-calendar-check-o",
    //     "target" => "ActivityReport/viewSales/monthly",
    // ),

    "prepenjualanAll" => array(
        "label" => "Pre Sales",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPreSalesAll",
    ),
//diganti dengan sales yg baru
//     "penjualanAll" => array(
//         "label" => "Sales",
//         "icon" => "fa-calendar-check-o",
// //        "target" => "ActivityReport/viewSalesAll",
//         "target" => "ActivityReport/viewSalesAllCompared",
//     ),

    "penjualanAll" => array(
        "label" => "Sales Report",
        "icon" => "fa-calendar-check-o",
        "target" => "penjualan/ActivityReport/viewSalesMonthly",
    ),
    //------------------------------------------
    "pembelianSpAll" => array(
        "label" => "Purchasing SP",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPurchasingSpAll",
    ),
    "pembelianFgAll" => array(
        "label" => "Purchasing FG",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPurchasingFgAll",
    ),
    "pembelian" => array(
        "label" => "laporan pembelian",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPembelian",
    ),
    "produkPoMonthly" => array(
        "label" => "Laporan FG purchase order lokal (bulanan)",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPurchaseOrderMonthly/produk",
    ),
    "produkImportPoMonthly" => array(
        "label" => "Laporan FG purchase order import (bulanan)",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPurchaseOrderMonthly/produkImport",
    ),
    "suppliesPoMonthly" => array(
        "label" => "Laporan Supplies purchase order (bulanan)",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewPurchaseOrderMonthly/supplies",
    ),
    //------------------------------------------

    "invoicing" => array(
        "label" => "log invoice",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewInvoice/bl",
    ),
    "soMonthly" => array(
        "label" => "Laporan sales order bulanan",
        "icon" => "fa-calendar-check-o",
        // "target" => "ActivityReport/viewMonthly/582/monthly",
//        "target" => "ActivityReport/viewSalesOrderMonthly",
        "target" => "ActivityReport/viewSalesOrderMonthlySql",
    ),

//    "realisasiSo" => array(
//        "label" => "Realization of sales v.1",
//        "icon" => "fa-calendar-check-o",
//        "target" => "ActivityReport/viewSalesRealization/",
//    ),
//    "realisasiSo2" => array(
//        "label" => "Realization of sales",
//        "icon" => "fa-calendar-check-o",
//        "target" => "ActivityReport/viewSalesRealizations/cabang",
//    ),
    "realisasiSo" => array(
        "label" => "Sales performance",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewSalesRel",
    ),
    "biOrder" => array(
        "label" => "Bi Stock",
        "icon" => "fa-calendar-check-o",
        // "target" => "Bi/viewProdukBi", // data life
        "target" => "Bi/viewProdukSales", // data tarikan lain db
    ),

    "582spo" => array(
        "label" => "Sales Pre Order",
        "icon" => "fa-hand-pointer-o",
        "target" => "ActivityReport/viewHistory/582/582spo",
    ),
    "582so" => array(
        "label" => "Sales Order",
        "icon" => "fa-hand-peace-o",
        "target" => "ActivityReport/viewHistory/582/582so",
    ),
    "582pkd" => array(
        "label" => "Pre Packing List",
        "icon" => "fa-hand-rock-o",
        "target" => "ActivityReport/viewHistory/582/582pkd",
    ),
    "582spd" => array(
        "label" => "Packing List",
        "icon" => "fa-hand-spock-o",
        "target" => "ActivityReport/viewHistory/582/582spd",
    ),
    "582" => array(
        "label" => "Invoicing",
        "icon" => "fa-hand-paper-o",
        "target" => "ActivityReport/viewHistory/582/582",
    ),
    "mong_report" => array(
        "label" => "Laporan penjualan komparasi",
        "icon" => "fa fa-archive",
        "target" => "Report/viewYear",
    ),
    "mong_report_saya" => array(
        "label" => "Laporan penjualan saya",
        "icon" => "fa fa-archive",
        "target" => "Report/viewReport",
    ),
    "outstanding_consolidate" => array(
        "label" => "Sales order Outstanding ",
        "icon" => "fa fa-archive",
        "target" => "Report/salesKonsolidate",
    ),
);

$config['onMenuReports'] = array(
    // "o_seller" => array(
    //     // "perMonth",
    //     // "penjualan",
    //     // "daily",
    //     // "weekly",
    //     // "monthly",
    //     // "582spo",
    //     // "582so",
    //     // "582pkd",
    //     // "582spd",
    //     // "582",
    // ),

    "o_seller_spv" => array(
        "invoicing",
        "monthly",
        "penjualanAll",
        "mong_report",
        "mong_report_saya",
        "realisasiSo",
        "soMonthly",
    ),
    "o_seller" => array(
        // "invoicing",
        // "monthly",
        // "penjualanAll",
        // "mong_report",
        "mong_report_saya",
        // "realisasiSo",
    ),
    "c_holding" => array(
        "outstanding_consolidate",
        "mong_report",
        "invoicing",
        "monthly",
        'soMonthly',
        "graphMonthly",
        "prepenjualanAll",
        "penjualanAll",

        "realisasiSo",
        // "realisasiSo2",
        "pembelian",
//        "produkPoMonthly",
//        "produkImportPoMonthly",
//        "suppliesPoMonthly",
//        "pembelianSpAll",
//        "pembelianFgAll",
        "biOrder",
    ),
    "o_finance" => array(
        "invoicing",
        "realisasiSo",
        "soMonthly",
    ),
    "o_gudang" => array(
        "realisasiSo",
    ),
    "o_gudang_spv" => array(
        "realisasiSo",
    ),

//    "*" => array(
//        "biOrder"
//    ),
//

);
/*=====================================*/

$config['availMenuHistories'] = array(
    "penjualan" => array(
        "label" => "My Sales",
        "icon" => "fa-calendar-check-o",
        "target" => "ActivityReport/viewSales/monthly",
    ),
);

$config['onMenuHistories'] = array(
    "o_seller" => array(
        // "perMonth",
        "penjualan",
        // "daily",
        // "weekly",
        // "monthly",
        // "582spo",
        // "582so",
        // "582pkd",
        // "582spd",
        // "582",
    ),

    "o_seller_spv" => array(// "penjualanAll",
    ),
);

/*==============================================*/
$config['availMenuMutasiRekening'] = array(
    // AKTIVA
    "kas" => array(
        "label" => "Kas",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuKas/kas",
    ),
    "uang_muka_dibayar" => array(
        "label" => "Uang Muka",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuUangMuka/uang muka dibayar",
    ),
    "persediaan_produk" => array(
        "label" => "Persediaan Produk",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
    ),
    "persediaan_produk_rakitan" => array(
        "label" => "Persediaan Produk Rakitan",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk rakitan",
    ),
    "persediaan_supplies" => array(
        "label" => "Persediaan Supplies",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplies/persediaan supplies",
    ),
    "piutang_aktiva_tetap_cabang" => array(
        "label" => "Piutang Aktiva Tetap Cabang",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/piutang aktiva tetap cabang",
    ),
    "piutang_cabang" => array(
        "label" => "Piutang Cabang",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/piutang cabang",
    ),
    "piutang_biaya_cabang" => array(
        "label" => "Piutang Biaya Cabang",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/piutang biaya cabang",
    ),
    "piutang_pembelian" => array(
//        "label"  => "Piutang Pembelian",
        "label" => "Credit Note",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/piutang pembelian",
    ),
    "aktiva_tetap" => array(
        "label" => "Aset Tetap",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuAktivaTetap/aktiva tetap",
    ),


    "piutang_dagang" => array(
        "label" => "Piutang Dagang",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomer/piutang dagang",
    ),
    "hutang_ke_konsumen" => array(
        "label" => "Hutang ke Konsumen",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomer/hutang ke konsumen",
    ),
    "hutang_valas_ke_konsumen" => array(
        "label" => "Hutang Valas ke Konsumen",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomerValas/hutang valas ke konsumen",
    ),

    // HUTANG
    "hutang_dagang" => array(
        "label" => "Hutang Dagang",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/hutang dagang",
    ),
    "hutang_biaya" => array(
        "label" => "Hutang Biaya",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/hutang biaya",
    ),
    "hutang_aktiva_tetap" => array(
        "label" => "Hutang Aset Tetap",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/hutang aktiva tetap",
    ),
    "hutang_bank" => array(
        "label" => "Hutang Bank",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuBank/hutang bank",
    ),
    "hutang_gaji" => array(
        "label" => "Hutang Gaji",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/hutang gaji",
    ),
    "hutang_ke_pemegang_saham" => array(
        "label" => "Hutang ke Pemegang Saham",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuHutangSaham/hutang ke pemegang saham",
    ),
    "hutang_biaya_bunga" => array(
        "label" => "Hutang Biaya Bunga",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuHutangBiayaBunga/hutang biaya bunga",
    ),
    "hutang_pph23" => array(
        "label" => "Hutang PPh 23",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuPph/hutang pph23",
    ),
    "hutang_lain_ppv" => array(
        "label" => "Hutang Lain PPV",
        "icon" => "fa fa-folder",
//        "target" => "Ledger/viewBalances_l1/RekeningPembantuPph/hutang pph23",
        "target" => "Ledger/viewMoveDetails_1/Rekening/hutang lain ppv",
    ),

    // BIAYA - BIAYA
    "biaya_bunga" => array(
        "label" => "Biaya Bunga",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuLoanItem/biaya bunga",
    ),
    "biaya_umum" => array(
        "label" => "Biaya Umum",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaUmum/biaya umum",
    ),
    "biaya_usaha" => array(
        "label" => "Biaya Usaha",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaUsaha/biaya usaha",
    ),
    "biaya_produksi" => array(
        "label" => "Biaya Produksi",
        "icon" => "fa fa-folder",
        "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaProduksi/biaya produksi",
    ),

);

$config['onMenuMutasiRekening'] = array(

    //region group hak akses center
    "c_holding" => array(
        "kas",
        "uang_muka_dibayar",
        "piutang_cabang",
        "piutang_pembelian",
        "piutang_biaya_cabang",
        "piutang_aktiva_tetap_cabang",
        "persediaan_produk",
        "persediaan_supplies",
        "biaya_bunga",
        "hutang_dagang",
        "hutang_biaya",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
        "hutang_aktiva_tetap",
        "hutang_gaji",
        "hutang_bank",
        "hutang_ke_pemegang_saham",
        "hutang_biaya_bunga",
        "hutang_pph23",
        "hutang_lain_ppv",
    ),
    "c_finance" => array(
        "kas",
        "uang_muka_dibayar",
        "piutang_cabang",
        "piutang_pembelian",
        "piutang_biaya_cabang",
        "piutang_aktiva_tetap_cabang",
        "persediaan_produk",
        "persediaan_supplies",
        "biaya_bunga",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
        "hutang_dagang",
        "hutang_biaya",
        "hutang_aktiva_tetap",
        "hutang_gaji",
        "hutang_bank",
        "hutang_ke_pemegang_saham",
        "hutang_biaya_bunga",
        "hutang_pph23",
        "hutang_lain_ppv",
    ),
    //endregion

    //region group hak akses cabang
    "o_finance" => array(
        "kas",
        "persediaan_produk",
        "persediaan_supplies",
        "piutang_dagang",
        "hutang_ke_konsumen",
        "hutang_valas_ke_konsumen",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
    ),
    //endregion

    "w_gudang" => array(
        "persediaan_produk",
        "persediaan_supplies",
    ),
    "w_gudang_spv" => array(
        "persediaan_produk",
        "persediaan_supplies",
    ),

    "p_produksi_spv" => array(),
);

/*==============================================*/
$config['onMenuTool'] = array(
    "*" => array(

        "checker_sum_transaksi",
        "checker_transaksi",
        "checker_produk",
        "checker_supplies",
        "checker_supplies_proses",
        "checker_kas",
        "checker_uang_muka",
        "checker_uang_muka_valas",
        "checker_stock_valas",
        "checker_hutang_dagang",
        "checker_piutang_dagang",
        "checker_rekening_koran",
        "checker_fifo_produk",
        "checker_fifo_supplies",

        "encode",
        "decode",
        "registry",
        "cli",
        "cli_time",
        "employee",
    ),
);
