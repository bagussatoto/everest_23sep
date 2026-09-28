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

$config['groupMenu'] = array(//master grup
    "jurnal" => array(
        "label" => "jurnal",
        "icon" => "fa fa-book",
        "availMenu" => array(
            /*
            * jika akan di pindah menu tinggal di cut satu array, ke master grup avail_menu
            */
            "jurnal" => array(
                "label" => "jurnal transaksi",
                "icon" => "fa fa-calculator",
                "target" => "Neraca/viewJurnal",
            ),
        ),
    ),
    "rekening" => array(
        "label" => "rekening",
        "icon" => "fa fa-line-chart",
        "availMenu" => array(
            /*
 * jika akan di pindah menu tinggal di cut satu array, ke master grup avail_menu
 */
            // AKTIVA
            "kas" => array(
                "label" => "Kas",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuKas/1010010010",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuKas/1010010010",
            ),
            "valas" => array(
                "label" => "Valas",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuValas/1010010020",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuValas/1010010020",
            ),
            "uang_muka_dibayar" => array(
                "label" => "Uang Muka Tanpa PPN<br>(berelasi PO)",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuUangMuka/1010050010",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuUangMuka/1010050010",
            ),
            "uang_muka_dibayar_norelasi" => array(
                "label" => "Uang Muka Tanpa PPN<br>(tidak berelasi PO)",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuUangMuka/1010050040",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuUangMuka/1010050040",
            ),
            "piutang_aktiva_tetap_cabang" => array(
                "label" => "Piutang Aktiva Tetap Cabang",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/piutang aktiva tetap cabang",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuAntarcabang/1010060020",
            ),
            "piutang_cabang" => array(
                "label" => "Piutang Internal",
                "icon" => "fa fa-folder",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuAntarcabang/1010060010",
            ),
            "piutang_biaya_cabang" => array(
                "label" => "Piutang Biaya Cabang",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/piutang biaya cabang",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuAntarcabang/1010060040",
            ),
            "piutang_pembelian" => array(// kredit note hasil klaim diskonnn
                "label" => "Credit Note",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCreditNote/1010010030",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuCreditNote/1010010030",
            ),
            "piutang_supplier" => array(
                "label" => "Klaim kepada supplier",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuPiutangSupplierMain/1010020030",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuPiutangSupplierMain/1010020030",
            ),
            //            "aktiva_tetap" => array(
            //                "label" => "Aset Tetap",
            //                "icon" => "fa fa-folder",
            //                "target" => "Ledger/viewBalances_l1/RekeningPembantuAktivaTetap/aktiva tetap",
            //            ),
            "piutang_dagang" => array(
                "label" => "Piutang Penjualan Dalam Negeri",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomer/1010020010",
            ),
            "piutang_dagang_project" => array(
                "label" => "Piutang Usaha Project",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomer/1010020080",
            ),

            // persediaan
            "logam_mulia" => array(
                "label" => "logam mulia dan permata",
                "icon" => "fa fa-folder",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuLogamMulia/1010025010",
            ),
            "persediaan_produk" => array(
                "label" => "product inventory",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuAktivaBerwujud/1010030030",
                //                "target" => "Ledger/viewBalances_periode/RekeningPembantuProduk/1010030030",
            ),
            "persediaan_aktiva" => array(
                "label" => "aktiva",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
                "target" => "Katalog/viewAktivaDetail",
                //                "target" => "Ledger/viewBalances_periode/RekeningPembantuProduk/1010030030",
            ),
            //             "persediaan_produk_rakitan" => array(
            //                 "label" => "product assembled inventory ",
            //                 "icon" => "fa fa-folder",
            // //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk rakitan",
            // //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/1010030070",
            //                 "target" => "Ledger/viewBalances_periode/RekeningPembantuProduk/1010030070",
            //             ),
            // "persediaan_supplies"            => array(
            //     "label"  => "supplies inventory",
            //     "icon"   => "fa fa-folder",
            //     // "target" => "Stok/viewStocks/Supplies/RekeningPembantuSupplies/persediaan supplies",
            //     //                "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplies/persediaan supplies",
            //     //                "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplies/1010030010",
            //     "target" => "Ledger/viewBalances_periode/RekeningPembantuSupplies/1010030010",
            // ),

            // HUTANG
            "hutang_dagang" => array(
                "label" => "Hutang Dagang",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/2010010",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuSupplier/2010010",
            ),
            "hutang_biaya" => array(
                "label" => "Hutang Biaya",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/2010040",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuSupplier/2010040",
            ),
            "hutang_ke_konsumen" => array(
                "label" => "Hutang ke Konsumen",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomer/hutang ke konsumen",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomer/2010050",
            ),
            "um_konsumen_noppn" => array(
                "label" => "Uang Muka Konsumen Tanpa PPN",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomerDetail/2010050?ext2_id=2010050050&main_ext2_id=2010050050&blob_ext=czoyOToiIFVhbmcgTXVrYSBLb25zdW1lbiBUYW5wYSBQcG4iOw==",
            ),
            "um_konsumen_relasi_so" => array(
                "label" => "Uang Muka Konsumen Penjualan Tunai",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomerDetail/2010050?ext2_id=2010050010&main_ext2_id=2010050010&blob_ext=czoxOToiIFVhbmcgTXVrYSBLb25zdW1lbiI7",
            ),
            "um_konsumen_relasi_so_project" => array(
                "label" => "Uang Muka Konsumen (PPN)",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomerDetail/2010050?ext2_id=2010050060&main_ext2_id=2010050060&blob_ext=czoxOToiIFVhbmcgTXVrYSBLb25zdW1lbiI7",
            ),
            "hutang_valas_ke_konsumen" => array(
                "label" => "Hutang Valas ke Konsumen",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomerValas/hutang valas ke konsumen",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuCustomerValas/2010100",
            ),
            "hutang_aktiva_tetap" => array(
                "label" => "Hutang Aset Tetap",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuSupplier/2010030",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuSupplier/2010030",
            ),
            "hutang_bank" => array(
                "label" => "Hutang Bank",
                "icon" => "fa fa-folder",
                                "target" => "Ledger/viewBalances_l1/RekeningPembantuBank/2020020",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuBank/2020020",
            ),
            "hutang_gaji" => array(
                "label" => "Hutang Gaji",
                "icon" => "fa fa-folder",
                                "target" => "Ledger/viewBalances_l1/RekeningPembantuAntarcabang/2010080",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuAntarcabang/2010080",
            ),
            "hutang_ke_pemegang_saham" => array(
                "label" => "Hutang ke Pemegang Saham",
                "icon" => "fa fa-folder",
                                "target" => "Ledger/viewBalances_l1/RekeningPembantuHutangSaham/2020010",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuHutangSaham/2020010",
            ),
            "hutang_biaya_bunga" => array(
                "label" => "Hutang Biaya Bunga",
                "icon" => "fa fa-folder",
                                "target" => "Ledger/viewBalances_l1/RekeningPembantuHutangBiayaBunga/2010070",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuHutangBiayaBunga/2010070",
            ),
            "hutang_pph23" => array(
                "label" => "Hutang PPh 23",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuPph/2030030",
//                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuPph/2030030",
            ),
            //            "hutang_lain_ppv" => array(
            //                "label" => "Hutang Lain PPV",
            //                "icon" => "fa fa-folder",
            //                //        "target" => "Ledger/viewBalances_l1/RekeningPembantuPph/hutang pph23",
            //                "target" => "Ledger/viewMoveDetails_1/Rekening/hutang lain ppv",
            //            ),
            // BIAYA - BIAYA

            "penjualan" => array(
                "label" => "Penjualan",
                "icon" => "fa fa-folder",
                "target" => "Ledger/viewMoveDetails_1/Rekening/4010",
            ),
            "biaya_bunga" => array(
                "label" => "Biaya Bunga",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuLoanItem/biaya bunga",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuLoanItem/6060",
            ),
            "biaya_umum" => array(
                "label" => "Biaya Umum",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaUmum/biaya umum",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuBiayaUmum/6030",
            ),
            "biaya_usaha" => array(
                "label" => "Biaya Usaha",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaUsaha/biaya usaha",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuBiayaUsaha/6010",
            ),
            "biaya_produksi" => array(
                "label" => "Biaya Produksi",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaProduksi/biaya produksi",
                "target" => "Ledger/viewBalances_l1/RekeningPembantuBiayaProduksi/6020",
            ),
            //            "persediaan_produk_gudang" => array(
            //                "label" => "product inventories",
            //                "icon" => "fa fa-folder",
            ////                "target" => "Ledger/viewBalances_t1/RekeningPembantuProduk/persediaan_produk",
            //                "target" => "Ledger/viewBalances_t1/RekeningPembantuProduk/1010030030",
            //            ),
            // movement
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
            // "movement_rakitan"               => array(
            //     "label"  => "asamble movements",
            //     "icon"   => "fa fa-fighter-jet",
            //     "target" => "Ledger/viewMovement/rk",
            // ),
            // "movement_rakitan_group"         => array(
            //     "label"  => "group asamble movements",
            //     "icon"   => "fa fa-fighter-jet",
            //     "target" => "Ledger/viewMovementGroup/rk",
            // ),
            // "movement_supplies"              => array(
            //     "label"  => "supplies movements",
            //     "icon"   => "fa fa-fighter-jet",
            //     "target" => "Ledger/viewMovement/sp",
            // ),
            // "movement_supplies_group"        => array(
            //     "label"  => "group supplies movements (bahan baku)",
            //     "icon"   => "fa fa-fighter-jet",
            //     "target" => "Ledger/viewMovementGroup/sp",
            // ),
            // "movement_supplies_proses_group" => array(
            //     "label"  => "group supplies movements (produksi)",
            //     "icon"   => "fa fa-fighter-jet",
            //     "target" => "Ledger/viewMovementGroup/sp_proses",
            // ),
            "rekening_koran" => array(
                "label" => "Realisasi Rekening koran",
                "icon" => "fa fa-archive",
                "target" => "RekeningKoran/index",
            ),
            "efisiensi_biaya" => array(
                "label" => "Efisiensi Biaya",
                "icon" => "fa fa-fighter-jet",
                "target" => "Neraca/viewEfisiensiBiaya/bom",
            ),
        ),
    ),
    "rekeningsaldo" => array(
        "label" => "neraca lajur",
        "icon" => "fa fa-balance-scale",
        // "target" => "Neraca/viewBalanceSheet",
        "availMenu" => array(
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
            //
        ),
    ),
    "persediaan" => array(
        "label" => "persediaan ",
        "icon" => "fa fa-balance-scale",
        "availMenu" => array(
            "persediaan_aktiva" => array(
                "label" => "aktiva",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
                "target" => "Katalog/viewAktivaDetail",
                //                "target" => "Ledger/viewBalances_periode/RekeningPembantuProduk/1010030030",
            ),
            "persediaan_produk" => array(
                "label" => "product inventory(berdasarkan mutasi)",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuProduk/1010030030",
                //                "target" => "Ledger/viewBalances_periode/RekeningPembantuProduk/1010030030",
            ),
            "persediaan_produk_per_tanggal" => array(
                "label" => "persediaan per tanggal",
                "icon" => "fa fa-folder",
                "target" => "LedgerV2/viewBalances_periode_2/RekeningPembantuProduk/1010030030",
            ),
            "katalog_produk" => array(
                "label" => "product catalog (semua cabang)",
                "icon" => "fa fa-star",
                "target" => "Katalog/viewProduk",
            ),
            "persediaan_supplies" => array(
                "label" => "supplies",
                "icon" => "fa fa-folder",
                //                "target" => "Ledger/viewBalances_l1/RekeningPembantuProduk/persediaan produk",
                "target" => "LedgerV2/viewBalances_l1/RekeningPembantuSupplies/1010030010",
                //                "target" => "Ledger/viewBalances_periode/RekeningPembantuProduk/1010030030",
            ),
        ),
    ),
    "rugilaba" => array(
        "label" => "rugi laba",
        "icon" => "fa fa-bar-chart",
        "availMenu" => array(
            //            "rl" => array(
            //                "label" => "p/l monthly",
            //                "icon" => "fa fa-line-chart",
            //                "target" => "Rugilaba/viewPL",
            //                //        "target" => "Rugilaba/viewPL2",
            //            ),
            //            "rl_y" => array(
            //                "label" => "p/l yearly",
            //                "icon" => "fa fa-line-chart",
            //                "target" => "Rugilaba/viewPLTahunan",
            //                //        "target" => "Rugilaba/viewPL2",
            //            ),
            //            "rl_mtd" => array(
            //                "label" => "p/l mtd",
            //                "icon" => "fa fa-line-chart",
            //                "target" => "Rugilaba/viewPLMonthToDate",
            //            ),
            //            "rl_ytd" => array(
            //                "label" => "p/l ytd",
            //                "icon" => "fa fa-line-chart",
            //                "target" => "Rugilaba/viewPLYearToDate",
            //            ),
        ),
    ),
    "rugilabacabang" => array(
        "label" => "rugi laba cabang",
        "icon" => "fa fa-bar-chart",
        "tanpaCbNama" => true,
        "availMenu" => array(
            "rl_cab_mtd" => array(
                "label" => "p/l MTD all branch",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLCabang/mtd",
            ),
            "rl_cab_ytd" => array(
                "label" => "p/l YTD all branch",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLCabang/ytd",
            ),
        ),
    ),
    "rugilabacabangkonsolidasi" => array(
        "label" => "rugi laba cabang konsolidasi",
        "icon" => "fa fa-bar-chart",
        "tanpaCbNama" => true,
        "availMenu" => array(
            "rl_branch_con" => array(
                // "label"  => "p/l consolidated all branch (monthly)",
                "label" => "p/l monthly all branch (consolidated)",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPL_consolidated",
            ),
            "rl_con_tahunan" => array(
                "label" => "p/l yearly all branch (consolidated)",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPL_consolidatedTahunan",
            ),
            "rl_con_mtd" => array(
                // "label"  => "p/l consolidated all branch (ytd)",
                "label" => "p/l MTD all branch (consolidated)",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLConsolidated/mtd",
            ),
            "rl_con_ytd" => array(
                // "label"  => "p/l consolidated all branch (ytd)",
                "label" => "p/l YTD all branch (consolidated)",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLConsolidatedNew/ytd",
                //                "target" => "Rugilaba/viewPLConsolidated/ytd",
                // "target" => "Rugilaba/viewPLYearToDate_consolidated",
            ),
        ),
    ),
    "rugilabaconsolidasi" => array(
        "label" => "rugi laba konsolidasi",
        "icon" => "fa fa-bar-chart",
        "availMenu" => array(
            "rl_con" => array(
                "label" => "p/l monthly (consolidated)",
                "icon" => "fa fa-line-chart",
                //        "target" => "Rugilaba/viewPL_consolidated",
                "target" => "Rugilaba/viewRlBulanan",
            ),
            "rl_con_y" => array(
                "label" => "p/l yearly (consolidated)",
                "icon" => "fa fa-line-chart",
                //        "target" => "Rugilaba/viewPL_consolidated",
                "target" => "Rugilaba/viewRlTahunan",
            ),
        ),
    ),
    "neraca" => array(
        "label" => "neraca",
        "icon" => "fa fa-balance-scale",
        "availMenu" => array(
            //            "nrc" => array(
            //                "label" => "balance monthly",
            //                "icon" => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeraca",
            //            ),
            //            "nrc_ytd" => array(
            //                "label" => "balance ytd",
            //                "icon" => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeracaYearToDate",
            //            ),
            //            "nrc_con" => array(
            //                "label" => "balance monthly (consolidated)",
            //                "icon" => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeraca_consolidated",
            //            ),
            //            "nrc_con_tahunan" => array(
            //                "label" => "balance yearly (consolidated)",
            //                "icon" => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeraca_consolidatedTahunan",
            //            ),
            //            "nrc_con_ytd" => array(
            //                "label" => "balance ytd (consolidated)",
            //                "icon" => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeracaYearToDate_consolidated",
            //            ),

            //            "efisiensi_biaya" => array(
            //                "label"  => "Efisiensi Biaya",
            //                "icon"   => "fa fa-fighter-jet",
            //                "target" => "Neraca/viewEfisiensiBiaya/bom",
            //            ),
        ),
    ),
    "cashflow" => array(
        "label" => "cashflow konsolidasi",
        "icon" => "fa fa-money",
        "availMenu" => array(
            "cashflow_bln" => array(
                "label" => "cashflow monthly (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Neraca/viewCashflow",
            ),
            "cashflow_thn" => array(
                "label" => "cashflow yearly (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Neraca/viewCashflowTahunan",
            ),
            //            "nrc_ytd"         => array(
            //                "label"  => "balance ytd",
            //                "icon"   => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeracaYearToDate",
            //            ),
            //            "nrc_con"         => array(
            //                "label"  => "balance monthly (consolidated)",
            //                "icon"   => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeraca_consolidated",
            //            ),
            //            "nrc_con_tahunan" => array(
            //                "label"  => "balance yearly (consolidated)",
            //                "icon"   => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeraca_consolidatedTahunan",
            //            ),
            //            "nrc_con_ytd"     => array(
            //                "label"  => "balance ytd (consolidated)",
            //                "icon"   => "fa fa-balance-scale",
            //                "target" => "Neraca/viewNeracaYearToDate_consolidated",
            //            ),
            //            "efisiensi_biaya" => array(
            //                "label"  => "Efisiensi Biaya",
            //                "icon"   => "fa fa-fighter-jet",
            //                "target" => "Neraca/viewEfisiensiBiaya/bom",
            //            ),
        ),
    ),
    "laporankeuangankonsolidasiinternal" => array(
        "label" => "laporan keuangan<br>konsolidasi internal",
        "icon" => "fa fa-money",
        "tanpaCbNama" => true,
        "availMenu" => array(
            "rlconinternalmtd" => array(
                "label" => "p/l mtd (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Rugilaba/viewPLConsolidated/mtd",
            ),
            "rlconinternalbulanan" => array(
                "label" => "p/l monthly (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Rugilaba/viewPL_consolidated",
            ),
            "rlconinternaltahunan" => array(
                "label" => "p/l yearly (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Rugilaba/viewPL_consolidatedTahunan",
            ),
            "rlconinternalytd" => array(
                "label" => "p/l year to date (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Rugilaba/viewPLConsolidatedNew",
            ),
            //----------------------------------------
            "neracakonsolidasiinternalbulanan" => array(
                "label" => "balance monthly (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Neraca/viewNeraca_consolidated",
            ),
            "neracakonsolidasiinternaltahunan" => array(
                "label" => "balance yearly (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Neraca/viewNeraca_consolidatedTahunan",
            ),
            "neracakonsolidasiinternalytd" => array(
                "label" => "balance year to date (consolidated)",
                "icon" => "fa fa-money",
                "target" => "Neraca/viewNeracaYearToDate_consolidated",
            ),

        ),
    ),
    "laporankeuangan" => array(
        "label" => "laporan keuangan",
        "icon" => "fa fa-money",
        //        "tanpaCbNama" => false,
        "availMenu" => array(
            //            "laporankeuangan_bln" => array(
            //                "label" => "konsolidasi bulanan",
            //                "icon" => "fa fa-money",
            //                "target" => "Keuangan/laporanKeuanganMonthly",
            //            ),
            //            "laporankeuangan_thn" => array(
            //                "label" => "tahunan",
            //                "icon" => "fa fa-money",
            //                "target" => "laporankeuangan/Keuangan/laporanKeuanganYearly",
            //            ),
            //            "laporankeuangan_triwulan" => array(
            //                "label" => "triwulan",
            //                "icon" => "fa fa-money",
            //                "target" => "laporankeuangan/Keuangan/laporanKeuanganTriwulan",
            //            ),
            //            "laporankeuangan_ttm" => array(
            //                "label" => "ttm",
            //                "icon" => "fa fa-money",
            //                "target" => "laporankeuangan/Keuangan/laporanKeuanganTtm",
            //            ),
            //            "laporankeuangan_ytd" => array(
            //                "label" => "ytd",
            //                "icon" => "fa fa-money",
            //                "target" => "laporankeuangan/Keuangan/laporanKeuanganYtd",
            //            ),

            "rl" => array(
                "label" => "p/l monthly",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPL",
                //        "target" => "Rugilaba/viewPL2",
            ),
            "rl_y" => array(
                "label" => "p/l yearly",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLTahunan",
                //        "target" => "Rugilaba/viewPL2",
            ),
            "rl_mtd" => array(
                "label" => "p/l mtd",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLMonthToDate",
            ),
            "rl_ytd" => array(
                "label" => "p/l ytd",
                "icon" => "fa fa-line-chart",
                "target" => "Rugilaba/viewPLYearToDate",
            ),
            "nrc" => array(
                "label" => "balance monthly",
                "icon" => "fa fa-balance-scale",
                "target" => "Neraca/viewNeraca",
            ),
            "nrc_ytd" => array(
                "label" => "balance ytd",
                "icon" => "fa fa-balance-scale",
                "target" => "Neraca/viewNeracaYearToDate",
            ),
        ),
    ),
    "laporankeuangankompilasi" => array(
        "label" => "laporan keuangan cabang kompilasi",
        "icon" => "fa fa-money",
        "tanpaCbNama" => true,
        "availMenu" => array(
            //            "laporankeuangan_bln" => array(
            //                "label" => "konsolidasi bulanan",
            //                "icon" => "fa fa-money",
            //                "target" => "Keuangan/laporanKeuanganMonthly",
            //            ),
            "laporankeuangankompilasi_thn" => array(
                "label" => "kompilasi tahunan",
                "icon" => "fa fa-money",
                "target" => "Keuangan/laporanKeuanganKompilasiYearly",
            ),
            "laporankeuangankompilasi_triwulan" => array(
                "label" => "kompilasi triwulan",
                "icon" => "fa fa-money",
                "target" => "Keuangan/laporanKeuanganKompilasiTriwulan",
            ),
            "laporankeuangankompilasi_ttm" => array(
                "label" => "kompilasi ttm",
                "icon" => "fa fa-money",
                "target" => "Keuangan/laporanKeuanganKompilasiTtm",
            ),
            "laporankeuangankompilasi_ytd" => array(
                "label" => "kompilasi ytd",
                "icon" => "fa fa-money",
                "target" => "Keuangan/laporanKeuanganKompilasiYtd",
            ),
        ),
    ),
    "laporankeuangankonsolidasi" => array(
        "label" => "laporan keuangan<br>konsolidasi eksternal",
        "icon" => "fa fa-money",
        "tanpaCbNama" => true,
        "availMenu" => array(
            //            "laporankeuangan_bln" => array(
            //                "label" => "konsolidasi bulanan",
            //                "icon" => "fa fa-money",
            //                "target" => "Keuangan/laporanKeuanganMonthly",
            //            ),
            "laporankeuangankonsolidasi_thn" => array(
                "label" => "konsolidasi tahunan",
                "icon" => "fa fa-money",
                "target" => "laporankeuangan/Keuangan/laporanKeuanganKonsolidasiYearly",
            ),
            "laporankeuangankonsolidasi_triwulan" => array(
                "label" => "konsolidasi triwulan",
                "icon" => "fa fa-money",
                "target" => "laporankeuangan/Keuangan/laporanKeuanganKonsolidasiTriwulan",
            ),
            "laporankeuangankonsolidasi_ttm" => array(
                "label" => "konsolidasi ttm",
                "icon" => "fa fa-money",
                "target" => "laporankeuangan/Keuangan/laporanKeuanganKonsolidasiTtm",
            ),
            "laporankeuangankonsolidasi_ytd" => array(
                "label" => "konsolidasi ytd",
                "icon" => "fa fa-money",
                "target" => "laporankeuangan/Keuangan/laporanKeuanganKonsolidasiYtd",
            ),
        ),
    ),
    "reporting" => array(
        "label" => "reporting",
        "icon" => "fa fa-signal",
        "availMenu" => array(
            //----------------------pembelian--------------------
            // "pembelianProduk" => array(
            //     "label" => "pembelian produk",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "laporan/PembelianPeriode/perindekshow/cekRow",
            // ),
            "pembelianSumSupplierProduk" => array(
                "label" => "sumary pembelian supplier",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/PembelianPeriode/viewbl/cekSumRow",
                "transaksi" => array(
                    "466"
                ),
            ),
            "pembelian" => array(
                "label" => "pembelian",
                "icon" => "fa fa-calendar-check-o",
                // "target" => "ActivityReport/viewPembelian",
                "target" => "laporan/Pembelian/viewbulananper/supplier",
            ),

            // "pembelianFgAll" => array(
            //     "label" => "Purchasing FG",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewPurchasingFgAll",
            // ),

            // "produkPoMonthly" => array(
            //     "label" => "Laporan FG purchase order lokal (bulanan)",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewPurchaseOrderMonthly/produk",
            // ),
            // "produkImportPoMonthly" => array(
            //     "label" => "Laporan FG purchase order import (bulanan)",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewPurchaseOrderMonthly/produkImport",
            // ),
            // "suppliesPoMonthly" => array(
            //     "label" => "Laporan Supplies purchase order (bulanan)",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewPurchaseOrderMonthly/supplies",
            // ),
            //------------------------------------------
            // "pembelian_produk" => array(
            //     "label" => "aktifitas pembelian produk",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Pembelian/produk",
            // ),
            // "prepenjualanAll"         => array(
            //     "label"  => "Pre Sales",
            //     "icon"   => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewPreSalesAll",
            // ),
            // "penjualan_produk_pre" => array(
            //     "label" => "pre sales order",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Penjualan/preso",
            // ),
            // "penjualan_produk_pre_so" => array(
            //     "label" => "sales pre order",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Penjualan/produksoindek",
            // ),
            // "penjualan_produk_so" => array(
            //     "label" => "sales order ",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Penjualan/perindekshow/soindek",
            // ),
            // dimatikan dulu tgl 01-02-2023, karena isinya tidak keluar
            //            "penjualan_produk" => array(
            //                "label" => "sales bruto",
            //                "icon" => "fa fa-calculator",
            //                "target" => "laporan/Penjualan/viewepenjualan",
            //            ),
            //             "penjualan_produk_perseller" => array(
            //                 "label" => "sales report",
            //                 "icon" => "fa fa-calculator",
            //                 "target" => "laporan/PenjualanCompare/viewepenjualan",
            //             ),
            //             "my_penjualan_produk" => array(
            //                 "label" => "aktifitas penjualan saya",
            //                 "icon" => "fa fa-calculator",
            //                 "target" => "laporan/Penjualan/viewmypenjualan",
            //             ),
            // ----------------------------------------
            // "procurement" => array(
            //     "label" => "outstanding order pembelian",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Procurement/vieweoutstanding",
            // ),
            // "procurement_netto" => array(
            //     "label" => "netto order pembelian",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Procurement/produkordernetto",
            // ),
            // "crm" => array(
            //     "label" => "Aktifitas SO",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Crm/produk",
            // ),
            // "crm_outstanding" => array(
            //     "label" => "Outstanding order penjualan",
            //     "icon" => "fa fa-calculator",
            //     "target" => "laporan/Crm/produkoutstanding",
            // ),
            // "crm_outstanding_bln" => array(
            //     // "label" => "Outstanding order penjualan Bulanan",
            //     "label" => "sales Outstanding bulanan",
            //     "icon" => "fa fa-calculator",
            //     // "target" => "laporan/Crm/vieweoutstanding",
            //     "target" => "laporan/Outstanding/vieweoutstanding",
            // ),
            // "crm_my_outstanding_bln" => array(
            //     // "label" => "Outstanding order penjualan Saya",
            //     "label" => "my sales Outstanding",
            //     "icon" => "fa fa-calculator",
            //     // "target" => "laporan/Crm/viewemyoutstanding",
            //     "target" => "laporan/Outstanding/viewemyoutstanding",
            // ),
            // "crm_outstanding_thn" => array(
            //     // "label" => "Outstanding order Tahunan",
            //     "label" => "sales Outstanding Tahunan",
            //     "icon" => "fa fa-calculator",
            //     // "target" => "laporan/Crm/vieweoutstanding",
            //     "target" => "laporan/Outstanding/vieweoutstandingthn",
            // ),
            // "crm_my_outstanding_thn" => array(
            //     "label" => "Outstanding order Saya tahunan",
            //     "icon" => "fa fa-calculator",
            //     // "target" => "laporan/Crm/viewemyoutstanding",
            //     "target" => "laporan/Outstanding/viewmyoutstandingthn",
            // ),
            // "status_persediaan_produk" => array(
            //     "label" => "status persediaan",
            //     "icon" => "fa fa-desktop",
            //     "target" => "laporan/Persediaan/produk",
            // ),
            //------------------------------------------

            /*--------------------------------penjualan----------------*/
            "penjualanhr" => array(
                "label" => "penjualan versi packinglist",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/PenjualanPeriode/perindekshow/cekSumRow",
            ),
            "invoicebln" => array(
                "label" => "penjualan versi invoice",
                "icon" => "fa fa-calendar-check-o",
                // "target" => "laporan/Invoice/perindekshow/cekSumRow/4822/4822",
                "target" => "laporan/Invoice/perindekshow/cekRow/4822/4822",
            ),
            /* -------------------------------------------------
             * penjualan tunai belum dikoreksi sudah diacc 21/7/2024
             * -------------------------------------------------*/
            "penjualantunai" => array(
                "label" => "penjualan tunai",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/PenjualanPeriode/perindekshow/cekPenjualanTunai",
                "transaksi" => array(
                    "4464",
                ),
            ),
            "penjualanmutasi" => array(
                "label" => "penjualan mutasi konsumen",
                "icon" => "fa fa-calendar-check-o",
                "target" => "penjualan/Mutasi/view/5822/showData",
                "transaksi" => array(
                    "5822",
                ),
            ),

            /*---------------------------------------kas masuk-------------------*/
            // "penerimaankas" => array(
            //     //                "label" => "penerimaan AR (kas)",
            //     "label"  => "summary Penerimaan Kas ",
            //     "icon"   => "fa fa-calendar-check-o",
            //     // "target" => "laporan/PenerimaanPeriode/perindekshow/cekSumRow",
            //     "target" => "laporan/PenerimaanPeriode/view",
            // ),
            // "penerimaanhr"   => array(
            //     "label"  => "penerimaan kas ",
            //     "icon"   => "fa fa-calendar-check-o",
            //     "target" => "laporan/PenerimaanPeriode/perindekshow/cekRow/",
            // ),
            //            "penerimaankasraw" => array(
            ////                "label" => "penerimaan AR (kas) timeline",
            //                "label" => "History Penerimaan Kas (timeline)",
            //                "icon" => "fa fa-calendar-check-o",
            //                "target" => "laporan/PenerimaanPeriode/perindekshow/cekRow",
            //            ),
            "penerimaanbl" => array(
                "label" => "summary penerimaan kas ",
                // "icon"   => "fa fa-calendar-check-o",
                "icon" => "fa fa-money",
                "target" => "laporan/Kas/viewhr/cekSumRowIn",
                "transaksi" => array("4464"),
            ),
            "penerimaanraw" => array(
                "label" => "penerimaan kas",
                // "icon"   => "fa fa-calendar-check-o",
                "icon" => "fa fa-money",
                "transaksi" => array("758"),
                "target" => "laporan/Kas/viewbl/cekRowIn",
            ),
            /*---------------------------------------kas masuk bank-------------------*/
            "penerimaankas_rekening_cek" => array(
                "label" => "ceking penerimaan kas bank",
                // "icon"   => "fa fa-calendar-check-o",
                "icon" => "fa fa-money",
                "transaksi" => array("4464"),
                "target" => "laporan/Kas/viewBlKas/cekRekeningBar",
            ),
            "penerimaankas_rekening" => array(
                "label" => "penerimaan kas bank",
                // "icon"   => "fa fa-calendar-check-o",
                "icon" => "fa fa-money",
                "transaksi" => array("758"),
                "target" => "laporan/Kas/viewBlKas/cekRekening",
            ),
            "mutasikas_bank" => array(
                "label" => "mutasi kas bank",
                // "icon"   => "fa fa-calendar-check-o",
                "icon" => "fa fa-money",
                "transaksi" => array("758"),
                "target" => "laporan/Kas/viewMutasiKas/Ledger/viewMoveDetailsKas",
            ),
            /*-----------------------------------------piutang-----------------------*/
            "piutangraw" => array(
                "label" => "umur piutang (AR)",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Piutang/perindekshow/cekRow/5822/5822spd",
                "transaksi" => array("749"),
            ),
            "pph23raw" => array(
                "label" => "rekap potongan pph23",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Taxes/perindekshow/cekSumRow/462/462",
                "transaksi" => array("462"),
            ),
            "pph23sum_persupplier" => array(
                "label" => "rekap by supplier potongan pph23",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Taxes/perindekshowpph/cekTransaksi/462/462",
                "transaksi" => array("462"),
            ),
            "ppnsum_persupplier" => array(
                "label" => "rekap ppn masukan",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Taxes/perindekshow/viewRealisasi/",
                "transaksi" => array("111"),
            ),
            /*-----------------------------------------hutang-----------------------*/
            "hutanghr" => array(
                "label" => "summary hutang (AP)",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Hutang/viewhr/cekSumRow",
            ),
            "hutangsupp" => array(
                "label" => "summary hutang supplier",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Hutang/viewhr/cekSumMutasi",
            ),
            /*---------------------------------------kas keluar-------------------*/
            "pengeluaranbl" => array(
                "label" => "summary pengeluaran kas ",
                "icon" => "fa fa-money",
                "target" => "laporan/Kas/viewhr/cekSumRowOt",
            ),
            "pengeluaranraw" => array(
                "label" => "pengeluaran kas",
                "icon" => "fa fa-money",
                "transaksi" => array("489"),
                "target" => "laporan/Kas/viewbl/cekRowOt",
            ),
            "hutangsum_konsumen" => array(
                "label" => "hutang ke konsumen (uang muka/lebih bayar/CN pembatalan)",
                "icon" => "fa fa-money",
                "transaksi" => array("4467"),
                "target" => "laporan/HutangKeKonsumen/perindekshow/cekSumRow",
            ),
            "ppnkeluaran" => array(
                "label" => "Ppn Keluaran Belum Ada Faktur",
                "icon" => "fa fa-money",
                "target" => "/laporan/Taxes/viewppnkeluaran/Ledger/viewMoveDetailsPPN/Rekening/2030060",
                "transaksi" => array("111", "114", "110"),
            ),
            /*---------------------------------------realisasi diskon pembelian supplier--------------------*/
            "realisasi_diskon" => array(
                "label" => "Realisasi Diskon",
                "icon" => "fa fa-money",
                "target" => "laporan/RealisasiDiskon/viewbulananper/cekbulanan/supplier",
                // "transaksi" => array("111", "114", "110"),
            ),
            /*-----------------------biaya----------*/
            "biaya" => array(
                "label" => "Biaya-biaya",
                "icon" => "fa fa-calendar-check-o",
                "target" => "laporan/Biaya/viewbulananper/cekbulanan",
                "transaksi" => array("1675", "1677", "7762"),
            ),
            /*-----------------------------------stok opname----------*/
            "opname" => array(
                "label" => "Stok Opname",
                "icon" => "fa fa-calendar-check-o",
                //        "target" => "ActivityReport/viewSalesAll",
                "target" => "laporan/Opname/viewbl/cekRow",
            ),

            //            "penjualanAll" => array(
            //                "label" => "Sales Report",
            //                "icon" => "fa fa-calendar-check-o",
            //                "target" => "penjualan/ActivityReport/viewSalesMonthly",
            //            ),

            // "invoicing" => array(
            //     "label" => "log invoice",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewInvoice/bl",
            // ),
            // tidak pakai rekening non akunting, jadi di nonaktifkan, diganti pakai versi nonakunting...
            //            "soMonthly"               => array(
            //                "label"  => "Laporan sales order bulanan",
            //                "icon"   => "fa fa-calendar-check-o",
            //                // "target" => "ActivityReport/viewMonthly/582/monthly",
            //                //        "target" => "ActivityReport/viewSalesOrderMonthly",
            //                "target" => "ActivityReport/viewSalesOrderMonthlySql",
            //            ),
            //            "soMonthly"    => array(
            //                "label"  => "Laporan sales order bulanan",
            //                "icon"   => "fa fa-calendar-check-o",
            //                "target" => "laporan/Penjualan/so",
            //            ),
            // -----------------------------------------
            // "daily" => array(
            //     "label" => "Daily",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewDaily/582/daily",
            // ),
            // "weekly" => array(
            //     "label" => "Weekly",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewWeekly/582/weekly",
            // ),
            "monthly" => array(
                "label" => "aktivitas penjualan bulanan",
                "icon" => "fa fa-calendar-check-o",
                // "target" => "ActivityReport/viewMonthly/582/monthly",
                // "target" => "penjualan/ActivityReport/viewMonthly/582", // down @8/juli/2023
                "target" => "laporan/Penjualan/viewpenjualanbulananper/seller",
            ),
            // "monthlykategori" => array(
            //     "label" => "aktivitas penjualan bulanan per kategori",
            //     "icon" => "fa fa-calendar-check-o",
            //     // "target" => "ActivityReport/viewMonthly/582/monthly",
            //     // "target" => "penjualan/ActivityReport/viewMonthly/582", // down @8/juli/2023
            //     "target" => "laporan/Penjualan/viewpenjualanbulananperkategori/kategori",
            // ),
            // // "graphMonthly" => array(
            // //     "label" => "Graph report",
            // //     "icon" => "fa fa-calendar-check-o",
            // //     // "target" => "ActivityReport/viewMonthly/582/monthly",
            // //     "target" => "Bi/viewGraphSales",
            // // ),
            // "perMonth" => array(
            //     "label" => "Month",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewMonth/582",
            // ),
            // // -----------------------------------------
            // //    "realisasiSo" => array(
            // //        "label" => "Realization of sales v.1",
            // //        "icon" => "fa fa-calendar-check-o",
            // //        "target" => "ActivityReport/viewSalesRealization/",
            // //    ),
            // //    "realisasiSo2" => array(
            // //        "label" => "Realization of sales",
            // //        "icon" => "fa fa-calendar-check-o",
            // //        "target" => "ActivityReport/viewSalesRealizations/cabang",
            // //    ),
            // "realisasiSo" => array(
            //     "label" => "Sales performance",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewSalesRel",
            // ),
            // // "biOrder" => array(
            // //     "label" => "Bi Stock",
            // //     "icon" => "fa fa-calendar-check-o",
            // //     // "target" => "Bi/viewProdukBi", // data life
            // //     "target" => "Bi/viewProdukSales",
            // //     // data tarikan lain db
            // // ),
            // "582spo" => array(
            //     "label" => "Sales Pre Order",
            //     "icon" => "fa fa-hand-pointer-o",
            //     "target" => "ActivityReport/viewHistory/582/582spo",
            // ),
            // "582so" => array(
            //     "label" => "Sales Order",
            //     "icon" => "fa fa-hand-peace-o",
            //     "target" => "ActivityReport/viewHistory/582/582so",
            // ),
            // "582pkd" => array(
            //     "label" => "Pre Packing List",
            //     "icon" => "fa fa-hand-rock-o",
            //     "target" => "ActivityReport/viewHistory/582/582pkd",
            // ),
            // "582spd" => array(
            //     "label" => "Packing List",
            //     "icon" => "fa fa-hand-spock-o",
            //     "target" => "ActivityReport/viewHistory/582/582spd",
            // ),
            // "582" => array(
            //     "label" => "Invoicing",
            //     "icon" => "fa fa-hand-paper-o",
            //     "target" => "ActivityReport/viewHistory/582/582",
            // ),
            // // "mong_report" => array(
            // //     "label" => "Laporan penjualan komparasi",
            // //     "icon" => "fa fa-archive",
            // //     "target" => "Report/viewYear",
            // // ),
            // // "mong_report_saya" => array(
            // //     "label" => "Laporan penjualan saya",
            // //     "icon" => "fa fa-archive",
            // //     "target" => "Report/viewReport",
            // // ),
            // "outstanding_consolidate" => array(
            //     "label" => "Sales order Outstanding ",
            //     "icon" => "fa fa-archive",
            //     "target" => "Report/salesKonsolidate",
            // ),
            // "penjualan" => array(
            //     "label" => "My Sales",
            //     "icon" => "fa fa-calendar-check-o",
            //     "target" => "ActivityReport/viewSales/monthly",
            // ),
            // "efisiensi_biaya" => array(
            //     "label" => "Efisiensi Biaya",
            //     "icon" => "fa fa-fighter-jet",
            //     "target" => "Neraca/viewEfisiensiBiaya/bom",
            // ),
            "biOrder" => array(
                "label" => "setting stok limit produk",
                "icon" => "fa fa-calendar-check-o",
                "target" => "addons/Bi/showStokLimit",
                // data tarikan lain db
            ),
        ),
    ),
    "otorisasi" => array(
        "label" => "otorisasi",
        "icon" => "fa fa-legal",
        "availMenu" => array(
            // "overdue_release" => array(
            //     "label" => "Otorisasi Overdue",
            //     "icon" => "fa fa-user",
            //     "target" => "OverDue_releaser/View",
            // ),
            // "depresiasi" => array(
            //     "label" => "Asset Management",
            //     "icon" => "fa fa-sort-alpha-desc",
            //     "target" => "SetupDepresiasi/view/Assets",
            // ),
            // "loaninterest" => array(
            //     "label" => "Setup Loan Interest",
            //     "icon" => "fa fa-money",
            //     "target" => "SetupLoanInterest/view/",
            // ),
            // "bungapihak3" => array(
            //     "label" => "Setup Bunga Pihak 3",
            //     "icon" => "fa fa-money",
            //     "target" => "SetupBungaPihak3/view/",
            // ),
            // "acc_request_diskon_penjualan" => array(
            //     "label" => "Permintaan diskon penjualan",
            //     "icon" => "fa fa-heartbeat",
            //     "target" => "Tool/viewDiskonPenjualan",
            // ),
            // "settlement" => array(
            //     "label" => "settlement",
            //     "icon" => "fa fa-calendar-check-o",
            //     // "target" => "Bi/viewProdukBi", // data life
            //     "target" => "Report/viewSettlement",
            // ),
            // "produksi" => array(
            //     "label" => "Setting Produksi",
            //     "icon" => "fa fa-sort-alpha-desc",
            //     "target" => "ToolManufactur/manufacture",
            // ),
        ),
    ),
    "dataproduk" => array(
        "label" => "data produk",
        "icon" => "fa fa-database",
        "availMenu" => array(
            // "data_produk" => array(
            //     "label" => "product datas",
            //     "icon" => "fa fa-diamond",
            //     // "target" => "Ledger/viewMovement/fg",
            //     "target" => "Katalog/view/Produk",
            // ),
            /* ----------------------------------------------------------------------
             * dimatikan supaya tidak membingungkan pemirsah, sudah diwakili produk katalog
             * ----------------------------------------------------------------------*/
            // "katalog_produk_aktif"    => array(
            //     "label"  => "product stock active",
            //     "icon"   => "fa fa-heart",
            //     "target" => "Katalog/viewProdukAktif",
            // ),
            // "katalog_produk" => array(
            //     "label"  => "product catalog (semua cabang)",
            //     "icon"   => "fa fa-star",
            //     "target" => "Katalog/viewProduk",
            // ),
            // "katalog_supplies" => array(
            //     "label" => "supplies catalog",
            //     "icon" => "fa fa-sun-o",
            //     "target" => "Katalog/viewSupplies",
            // ),
            // "katalog_produk_produksi" => array(
            //     "label" => "produk hasil produksi",
            //     "icon" => "fa fa-star",
            //     "target" => "Katalog/viewProdukPabrik",
            // ),

        ),
    ),
    "editor" => array(
        "label" => "editor",
        "icon" => "fa fa-pencil",
        "availMenu" => array(
            //            "harga_produk"   => array(
            //                "label"  => "product prices",
            //                "icon"   => "fa fa-sort-numeric-asc",
            //                "target" => "Spread/index/produk/cabang/hargaProduk",
            //            ),
            // "harga_supplies" => array(
            //     "label" => "supply prices",
            //     "icon" => "fa fa-sort-numeric-asc",
            //     "target" => "Spread/index/supplies/cabang/hargaSupplies",
            // ),
            // "harga_rakitan" => array(
            //     "label" => "assembled product prices",
            //     "icon" => "fa fa-sort-numeric-asc",
            //     "target" => "Spread/index/produkRakitan/cabang/hargaProdukRakitan",
            // ),
            // "harga_paket" => array(
            //     "label" => "package product prices",
            //     "icon" => "fa fa-sort-numeric-asc",
            //     "target" => "Spread/index/produkPaket/cabang/hargaProdukPaket",
            // ),
            // "harga_komposit" => array(
            //     "label" => "product composite prices",
            //     "icon" => "fa fa-sort-numeric-asc",
            //     "target" => "Spread/index/produkKomposit/cabang/hargaProdukKomposit",
            // ),
            // "harga_vendor" => array(
            //     "label" => "vendor prices",
            //     "icon" => "fa fa-sort-numeric-asc",
            //     "target" => "Spread/index/produkSupplier/suppliers/hargaProdukPerSupplier",
            // ),
            "setting_diskon" => array(
                "label" => "setting diskon",
                "icon" => "fa fa-sort-numeric-asc",
                "target" => "diskon/Setting/index",
            ),
            "depresiasi" => array(
                "label" => "Asset Management",
                "icon" => "fa fa-sort-alpha-desc",
                "target" => "SetupDepresiasi/view/Assets",
            ),
            "api_integration" => array(
                "label" => "API Integration",
                "icon" => "fa fa-gear",
                "target" => "api/APIntegration/index",
            ),
        ),
    ),
    "opname" => array(
        "label" => "stok opname",
        "icon" => "fa fa-cogs",
        "availMenu" => array(
            "stok_opname_rakitan" => array(
                "label" => "stok opname rakitan",
                "icon" => "fa fa-sort-alpha-desc",
                // "target" => "Opname/index/ProdukRakitan",
                "target" => "opname/Opname/index/ProdukRakitan",
            ),
            "stok_opname" => array(
                "label" => "stok opname produk",
                "icon" => "fa fa-sort-alpha-desc",
                // "target" => "Opname/index/Produk",
                "target" => "opname/Opname/index/Produk",
            ),
            "stok_opname_supplies" => array(
                "label" => "stok opname supplies",
                "icon" => "fa fa-sort-alpha-desc",
                // "target" => "Opname/index/Supplies",
                "target" => "opname/Opname/index/Supplies",
            ),
        ),

    ),
    "utility" => array(
        "label" => "utility",
        "icon" => "fa fa-cogs",
        "availMenu" => array(
            /*
 * jika akan di pindah menu tinggal di cut satu array, ke master grup
 */
            "profile" => array(
                "label" => "my profile",
                "icon" => "fa fa-user",
                "target" => "Data/myProfile/User",
            ),
            "barcode_print" => array(
                "label" => "Print barcode",
                "icon" => "fa fa-print text-red",
                "target" => "addons/BarcodePrinter/searching",
            ),
            "sku_print" => array(
                "label" => "Print SKU",
                "icon" => "fa fa-print text-red",
                "target" => "addons/SkuPrinter/searching",
            ),
            "qr_print" => array(
                "label" => "Print QR",
                "icon" => "fa fa-print text-red",
                "target" => "addons/Qr/searching",
            ),
            "qr_scaner" => array(
                "label" => "QR scaner",
                "icon" => "fa fa-qrcode text-red",
                "target" => "addons/Qr/scaner",
            ),
            "activity_log" => array(
                "label" => "My Activity log",
                "icon" => "fa fa-archive",
                "target" => "ActivityLog/viewLog",
            ),
            "st_locker" => array(
                "label" => "active stocks",
                "icon" => "fa fa-folder",
                "target" => "StockLocker/viewCurrentLockers",
            ),
            "sn_tracking" => array(
                "label" => "serial tracking",
                "icon" => "fa fa-qrcode",
                "target" => "addons/Qr/sntracking",
            ),
            "master_setting" => array(
                "label" => "Master Setting",
                "icon" => "fa fa-sliders text-aqua",
                "target" => "Setting/index",
            ),
        ),

    ),
    "tool" => array( //member grup
        "label" => "tool ceker",
        "icon" => "fa fa-gear",
        "availMenu" => array(
            /*
             * jika akan di pindah menu tinggal di cut satu array, ke master grup
             */
            "checker_price" => array(
                "label" => "checker price (harga)",
                "icon" => "fa fa-trash-o",
                "target" => "Tool/viewProdukPrice",
            ),
            "rst" => array(
                "label" => "Resetor Transaksi",
                "icon" => "fa  fa-trash-o",
                "target" => "ResetorTransaksi/view",
            ),
            "st_locker" => array(
                "label" => "active stocks",
                "icon" => "fa fa-folder",
                "target" => "StockLocker/viewCurrentLockers",
            ),
            "lap_pembelian" => array(
                "label" => "show lap pembelian",
                "icon" => "fa fa-archive",
                "target" => "Tool/viewPembelianProgres",
            ),
            "lap_penjualan" => array(
                "label" => "show lap penjualan",
                "icon" => "fa fa-archive",
                "target" => "Tool/viewPenjualanProgres",
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
            "checker_hutang_ke_konsumen" => array(
                "label" => "checker hutang ke konsumen",
                "icon" => "fa fa-numeric-asc",
                "target" => "Tool/cekPaymentSource",
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
            "employee" => array(
                "label" => "checker employee",
                "icon" => "fa fa-home",
                "target" => "Tool/viewEmployee",
            ),
            "cli_invoice" => array(
                "label" => "checker cli invoice",
                "icon" => "fa fa-trash-o",
                "target" => "Tool/viewCliInvoice",
            ),
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
            "cli_penjualan" => array(
                "label" => "cli penjualan",
                "icon" => "fa fa-home",
                "target" => "Tool/viewCheckerReportToMongo",
            ),
            "grouping_menu" => array(
                "label" => "grouping menu",
                "icon" => "fa fa-trash-o",
                "target" => "Tool/viewTransaksi_ui",
            ),
            "coa" => array(
                "label" => "cart of account",
                "icon" => "fa fa-trash-o",
                "target" => "akunting/Coa/coa",
            ),
        ),
    ),
);

/* ------------------------------------------------------------------------------
 * 21/11/2022 * group sales tidak berhak atas movement produk group
 *
 * ------------------------------------------------------------------------------*/
$config['menu'] = array(
    "c_laporan" => array(
        // "rl",
        // "rl_y",
        // "rl_mtd",
        // "rl_ytd",
        // "nrc",
        // "nrc_ytd",
        "laporankeuangankonsolidasi_thn"
    ),
    "root" => array(
        "rst",
        "api_integration",
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
        "master_setting",
    ),
    "c_holding" => array(
        "api_integration",
        "hutangsupp",
        "penerimaankas_rekening_cek",
        "opname",
        "biaya",
        "realisasi_diskon",
        "pembelianProduk",
        "pembelianSumSupplierProduk",
        "penjualanmutasi",
        "penjualantunai",
        "penjualanhr",
        "invoicebln",
        // "penerimaankas",
        "penerimaanhr",
        // "penerimaankasraw",
        "penerimaankas_rekening",
        "mutasikas_bank",
        "penerimaanbl",
        "penerimaanraw",
        "pengeluaranraw",
        "piutangraw",
        "ppnsum_persupplier",
        "ppnkeluaran",
        "pph23raw",
        "pph23sum_persupplier",
        "hutanghr",
        "pengeluaranbl",
        "biOrder",
        "outstanding_consolidate",
        "mong_report",
        // "invoicing",
        // "monthly",
        // "monthlykategori",
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
        // --------------
        //        "rl",
        //        "rl_y",
        //        "rl_mtd",
        //        "rl_ytd",
        //        "rl_con",
        //        "rl_con_y",
        //        "rl_con_mtd",
        //        "rl_con_ytd",
        //        "rl_con_tahunan",
        //        "rl_branch_con",
        //        "rl_cab_mtd",
        //        "rl_cab_ytd",
        // ----------
        //        "nrc",
        //        "nrc_ytd",
        //        "nrc_con",
        //        "nrc_con_ytd",
        //        "nrc_con_tahunan",

        //        "rl_realtime",
        //        "nrc_realtime",
        //        "cashflow_bln",
        //        "cashflow_thn",
        //        "laporankeuangan_bln",
        //        "laporankeuangan_thn",

        //--------------
        "neracakonsolidasiinternalbulanan",
        "neracakonsolidasiinternaltahunan",
        "neracakonsolidasiinternalytd",
        //--------------
        "rlconinternalmtd",
        "rlconinternalbulanan",
        "rlconinternaltahunan",
        "rlconinternalytd",
        //--------------
        "laporankeuangan_thn",
        //        "laporankeuangan_triwulan",
        //        "laporankeuangan_ttm",
        "laporankeuangan_ytd",
        //--------------
        //        "laporankeuangankompilasi_thn",
        //        "laporankeuangankompilasi_triwulan",
        //        "laporankeuangankompilasi_ttm",
        //        "laporankeuangankompilasi_ytd",
        //--------------
        "laporankeuangankonsolidasi_thn",
        //        "laporankeuangankonsolidasi_triwulan",
        //        "laporankeuangankonsolidasi_ttm",
        "laporankeuangankonsolidasi_ytd",
        //--------------


        "bls",
        // "bls_bulanan",//dimatiin bulanan unbalance
        // ----------------
        "stok",
        "harga_produk",
        "harga_supplies",
        "harga_rakitan",
        "harga_paket",
        "harga_komposit",
        "harga_vendor",
        "setting_diskon",

        "katalog_supplies",
        "katalog_produk",
        "katalog_produk_produksi",
        "depresiasi",
        "loaninterest",
        "bungapihak3",
        // "stok_opname",
        // "stok_opname_supplies",
        "jurnal",
        // ---------LAPORAN----
        "crm",
        "crm_outstanding",
        "crm_outstanding_bln",
        "crm_outstanding_thn",
        "procurement",
        "penjualan_produk_pre_so",
        // "penjualan_produk_pre",
        "penjualan_produk_so",
        "pembelian_produk",
        "penjualan_produk",
        "penjualan_produk_perseller",
        "status_persediaan_produk",
        // -----
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
        //    rekening
        "kas",
        "valas",
        "uang_muka_dibayar",
        "uang_muka_dibayar_norelasi",
        "piutang_cabang",
        "piutang_pembelian",
        "piutang_supplier",
        "piutang_biaya_cabang",
        "piutang_aktiva_tetap_cabang",
        "persediaan_aktiva",
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        "persediaan_supplies",
        "biaya_bunga",
        "hutang_dagang",
        "hutang_biaya",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
        "um_konsumen_noppn",
        "hutang_aktiva_tetap",
        "hutang_gaji",
        "hutang_bank",
        "hutang_ke_pemegang_saham",
        "hutang_biaya_bunga",
        "hutang_pph23",
        "hutang_lain_ppv",
        "movement_produk_group",
        "movement_supplies_group",
        "persediaan_supplies",
        //        "movement_supplies",
        //        "movement_produk",
        //        "movement_rakitan_group",
        "persediaan_produk_gudang",
        "rekening_koran",
        "barcode_print",
        "sku_print",
        "qr_print",
        "master_setting",
        // "qr_scaner",
        //
        //        "produksi",
    ),
    "c_data" => array(
        "setting_diskon",
    ),
    "c_purchasing" => array(
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        //        "movement_supplies",
        //        "movement_produk",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
    ),
    "c_gudang" => array(
        'persediaan_aktiva',
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        //        "movement_supplies",
        //        "movement_produk",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
    ),
    "c_gudang_spv" => array(
        'persediaan_aktiva',
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        "movement_produk_group",
        "movement_supplies_group",
        "persediaan_produk_gudang",
    ),
    "c_finance_spv" => array(
        "rl",
        "rl_y",
        "rl_ytd",

        "nrc",
        "nrc_ytd",
        //        "cashflow_bln",
        //        "cashflow_thn",
        //        "laporankeuangan_bln",
        //        "laporankeuangan_thn",

        //--------------
        "biaya",
        "hutanghr",
        "laporankeuangan_thn",
        //        "laporankeuangan_triwulan",
        //        "laporankeuangan_ttm",
        "laporankeuangan_ytd",
        //--------------
        //        "laporankeuangankompilasi_thn",
        //        "laporankeuangankompilasi_triwulan",
        //        "laporankeuangankompilasi_ttm",
        //        "laporankeuangankompilasi_ytd",
        //--------------
        //        "laporankeuangankonsolidasi_thn",
        //        "laporankeuangankonsolidasi_triwulan",
        //        "laporankeuangankonsolidasi_ttm",
        //        "laporankeuangankonsolidasi_ytd",
        //--------------

        "rekening_koran",
        "kas",
        "valas",
        "uang_muka_dibayar",
        "uang_muka_dibayar_norelasi",
        "penerimaankas_rekening",
        "piutang_cabang",
        "piutang_pembelian",
        "piutang_supplier",
        "piutang_biaya_cabang",
        "piutang_aktiva_tetap_cabang",
        "logam_mulia",
        "persediaan_produk",
        "persediaan_supplies",
        "biaya_bunga",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
        "hutang_dagang",
        "hutang_biaya",
        "hutang_aktiva_tetap",
        "um_konsumen_noppn",
        "hutang_gaji",
        "hutang_bank",
        "hutang_ke_pemegang_saham",
        "hutang_biaya_bunga",
        "hutang_pph23",
        "hutang_lain_ppv",

    ),
    "c_finance" => array(
        "rl",
        "rl_y",
        "rl_ytd",

        "nrc",
        "nrc_ytd",
        //        "cashflow_bln",
        //        "cashflow_thn",
        //        "laporankeuangan_bln",
        //        "laporankeuangan_thn",

        //--------------
        "biaya",
        "hutanghr",
        "laporankeuangan_thn",
        //        "laporankeuangan_triwulan",
        //        "laporankeuangan_ttm",
        "laporankeuangan_ytd",
        //--------------
        //        "laporankeuangankompilasi_thn",
        //        "laporankeuangankompilasi_triwulan",
        //        "laporankeuangankompilasi_ttm",
        //        "laporankeuangankompilasi_ytd",
        //--------------
        //        "laporankeuangankonsolidasi_thn",
        //        "laporankeuangankonsolidasi_triwulan",
        //        "laporankeuangankonsolidasi_ttm",
        //        "laporankeuangankonsolidasi_ytd",
        //--------------

        "rekening_koran",
        "kas",
        "valas",
        "uang_muka_dibayar",
        "uang_muka_dibayar_norelasi",
        "penerimaankas_rekening",
        "piutang_cabang",
        "piutang_pembelian",
        "piutang_supplier",
        "piutang_biaya_cabang",
        "piutang_aktiva_tetap_cabang",
        "logam_mulia",
        "persediaan_produk",
        "persediaan_supplies",
        "biaya_bunga",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
        "hutang_dagang",
        "hutang_biaya",
        "hutang_aktiva_tetap",
        "um_konsumen_noppn",
        "hutang_gaji",
        "hutang_bank",
        "hutang_ke_pemegang_saham",
        "hutang_biaya_bunga",
        "hutang_pph23",
        "hutang_lain_ppv",
        "penerimaankas_rekening",
    ),
    "w_gudang" => array(
        "persediaan_supplies",
        "persediaan_produk",
        //        "movement_supplies",
        //        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        // "stok_opname_rakitan",
        // "stok_opname",
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
        "stok_opname_supplies",
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
        "stok_opname_supplies",
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
        "stok_opname_supplies",
        //        "produksi",
        "sku_print",
        "qr_print",
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
        "stok_opname_supplies",
        //        "produksi",
    ),
    "o_finance" => array(
        // "penerimaankas",
        "penerimaankas_rekening_cek",
        "penerimaanhr",
        "penerimaanbl",
        "penerimaanraw",
        // "pengeluaranraw",
        // "pengeluaranbl",
        "invoicing",
        "realisasiSo",
        "soMonthly",
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        //        "movement_supplies",
        //        "movement_produk",
        //        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "overdue_release",
        "hutangsum_konsumen",
        //        "rl",
        //        "nrc",
        // "jurnal",
        "bls",
        // "bls_bulanan",//dimatiin bulanan unbalance
        "depresiasi",
        "loaninterest",
        "bungapihak3",
        //        "rl_realtime",
        //        "nrc_realtime",
        "rl",
        "nrc",
        "rl_ytd",
        "nrc_ytd",
        "kas",
        "valas",
        "persediaan_produk",
        "persediaan_supplies",
        "piutang_dagang",
        "piutang_dagang_project",
//        "hutang_ke_konsumen",
        "um_konsumen_noppn",
        "um_konsumen_relasi_so",
        "um_konsumen_relasi_so_project",
        "hutang_valas_ke_konsumen",
        "biaya_umum",
        "biaya_usaha",
        "biaya_produksi",
        "efisiensi_biaya",
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
        "efisiensi_biaya",
        "mutasikas_bank",
    ),
    "o_kasir" => array(
        "persediaan_supplies",
        "persediaan_produk",
        //        "movement_supplies",
        //        "movement_produk",
        // "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
    ),
    "o_gudang" => array(
        "persediaan_aktiva",
        'persediaan_aktiva',
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        //        "movement_supplies",
        //        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
        // "stok_opname_rakitan",
        // "stok_opname",
        // "stok_opname_supplies",
        "harga_supplies",
        "realisasiSo",
    ),
    "o_gudang_spv" => array(
        'persediaan_aktiva',
        "persediaan_supplies",
        "persediaan_produk",
        "persediaan_produk_per_tanggal",
        //        "movement_supplies",
        //        "movement_produk",
        "movement_rakitan_group",
        "movement_supplies_group",
        "movement_produk_group",
        "katalog_supplies",
        "katalog_produk",
        "harga_supplies",
        "realisasiSo",
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
        "persediaan_aktiva",
        "persediaan_produk",
        "penjualan",
        "katalog_produk",
        // "movement_produk_group", // movement
        // "overdue_release",
        "mong_report_saya",
        "crm_my_outstanding_bln",
        "my_penjualan_produk",
        "penjualanhr",
        "penjualantunai",
        "invoicebln",
        // "penerimaankas",
        // "penerimaanbl",
        // "penerimaanraw",
        // "piutangraw",
    ),
    "o_seller_spv" => array(

        "katalog_produk",
        //        "movement_produk",
        // "movement_produk_group",
        "persediaan_aktiva",
        "persediaan_produk",
        "persediaan_produk_gudang",
        "invoicing",
        // "monthly",
        "penjualanAll",

        "mong_report",
        "mong_report_saya",
        "realisasiSo",
        "soMonthly",

        "penjualantunai",
        "penjualanhr",
        "invoicebln",
        // "penerimaanhr",
    ),
    "c_special" => array(
        // "penjualan_produk_pre",

        "api_integration",
        "penjualan_produk_so",
        "penjualan_produk",
        "outstanding_consolidate",
        "monthly",
    ),
    "c_katalog" => array(
        "katalog_produk"
    ),
    "c_audit" => array(),
    "o_audit" => array(
        "penerimaankas_rekening_cek"
    ),
    "*" => array(//===every-one
        "activity_log",
        "profile",
        // "katalog_produk_aktif",
        "data_produk",
        "qr_scaner",
        "sn_tracking",
        // "logout",
    ),
);

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

/*==============================================*/
$config['onMenuTool'] = array(
    "*" => array(
        "piutangstatus",
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
        "checker_hutang_ke_konsumen",
        "checker_rekening_koran",
        "checker_fifo_produk",
        "checker_fifo_supplies",

        "encode",
        "decode",
        "registry",
        "cli_invoice",
        "cli",
        "cli_time",
        "employee",
        "coa",
        "grouping_menu",
    ),
);
