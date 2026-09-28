<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(

    "749" => array(
        "icon" => "fa fa-money",
        "label" => "A/R Receipt",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "A/R Receipt",
                "actionLabel" => "process receivement",
                "source" => "",
                "target" => "749",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "paymentConfig" => true,
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            // "transaksi_nilai_sisa>.0",
            "transaksi_nilai_sisa>.1000",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "dtime" => "date",
            "customerName" => "customer",
            "selectedType_konsumen" => "tipe customer",
            "marketplaceName" => "marketplace",
            "nomer" => "settlement number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "paymentMethod" => "cara pembayaran",
            "cash_account__label" => "akun kas/bank",
            "cash_account__merchant" => "merchant",
            "tagihan" => "tagihan",
            "nilai_entry" => "diterima",
            "credit_note_dipakai" => "credit note<br>(from return)",
            "uang_muka_dipakai" => "uang muka (dp tanpa PPN)",
            "uangMukaPpnTerbayar" => "uang muka (PPN)",
            "nilai_biaya" => "(biaya support)",
            "nilai_bayar" => "total pembayaran",
            "deposit_konsumen" => "kelebihan bayar<br>deposit konsumen",
            "pendapatan_lain_lain" => "kelebihan bayar<br>pendapatan lain-lain",
            "description" => "catatan",
            "keterangan" => "keterangan",
        ),
        "shortHistoryFieldsReplacer" => array(
            1 => array(
                "kolomCek" => "paymentMethod",
                "paymentMethod" => "cashless",
                "kolom" => array(
                    "cash_account__label" => "cash_account_nama",
                    "cash_account__merchant" => "cash_account__label",
                ),
            ),
        ),

        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nomer",
                "format" => "nomer",
            ),
            "extern_nama" => "konsumen",
            "tagihan" => "tagihan",
            "nilai_bayar" => "diterima",
            "new_sisa" => "sisa",
        ),
        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "nilai_entry" => "diterima",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                "details" => "nama",
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "nomer_top" => "nomer inv",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "ppn" => "ppn",
//            "ppn_nilai_dibayar" => "ppn",
            "dpp_ppn" => "dpp_ppn",
//            "dpp_nilai" => "dpp_ppn",
            "nomer_top" => "nomer_top",

            "marketplaceID" => "extern3_id",
            "marketplaceNama" => "extern3_nama",
            "marketplaceName" => "extern3_nama",
            "tipe_penjualan" => "extern4_id",
            "tipe_penjualan_id" => "extern4_id",
            "tipe_penjualan_nama" => "extern4_nama",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "extern3_id" => "extern3_id",
            "extern3_nama" => "extern3_nama",
            "extern4_id" => "extern4_id",
            "extern4_nama" => "extern4_nama",
            "reference_so_id" => "reference_so_id",
            "reference_so_nomer" => "reference_so_nomer",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa tagihan",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "total amount",
                //                "credit_note_dipakai" => "customer credit amount",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain receivable (from list)",
                //                "sisa-nilai_bayar" => "remain receivable (from list)",
            ),
        ),

        //untuk settlement format paymentsource denga extrnBlobValue
        "connectSettlement" => array(
            "modul" => "settlement",
            "target" => "749",
            "targetPayment" => "759",
            "btnLabel" => "Settlement",
        ),

        "settlementFields" => array(
            "dtime" => "tanggal",
//            "fulldate"        => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "invoice",
            "trash_4" => "status",
            "oleh_nama" => "kasir",
            "transaksi_nilai" => "nilai",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    "parent" => "parent ID",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "kelebihanBayar" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Kelebihan Bayar",
                "mdlName" => "MdlStaticMethodLebihBayar",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    //                    0 => "ReComMethodLebihBayar",
                    1 => "ReComMethodLebihBayar",
                    2 => "ReComMethodLebihBayar",
                ),
                "noValidate" => true,
                "noPrefetch" => true,
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.customer",
                    "sisa>.0",
                ),
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuCustomerDetail",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "extern_id" => "pihakID",
                        "extern2_id" => ".2010050050",//point
                        "cabang_id" => "cabangID",
                        "periode" => ".forever",
                    ),
                    "key" => "extern_id",
                    "rekening" => "2010050",
                    "fieldID" => "kredit",
                    "fieldLabel" => "saldoUangMuka",
                ),
//                "key" => "id",
                "key" => "extern_id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
                    "extern_label2" => "tipe",
                    "saldoUangMuka" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "noPrefetch" => true,
                // "pairMethod" => array(
                //     "recom" => "ReComUangMuka",
                //     "calculate" => array(
                //         "source" => "sisa",
                //         "target" => "uang_muka_dipakai",
                //         "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                //     ),
                // ),
            ),
            "uangMukaPpn" => array(
                "elementType" => "dataModel",
//                "inputType" => "combo",
                "inputType" => "multicheckboxform", //mode baru
                "label" => "Deposit (Uang muka dengan PPN)",
                "mdlName" => "MdlPaymentSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "target_jenis=.04467",
                    "project_id=.0",
                    "extern2_id=@refPymSrcIDs",
                    "sisa>.0",
                ),
                "multi" => true,
                "mainSesKey" => "refPymSrcIDs",
                "itemsSesKey" => "refPymSrcIDs",
                "mainTargetKey" => "uangMukaPpnTerbayar",
                "key" => "id",
                "keyLock" => "id",
                "labelSrc" => "sisa/extern2_nama",
                "usedFields" => array(
                    "id" => "mid",
                    "extern_nama" => "konsumen",
                    "target_jenis" => "trg",
                    "transaksi_id" => "trxID",
                    "extern_id" => "pihakID",
                    "extern2_id" => "ref",
                    "extern2_nama" => "No SO",
                    "sisa" => "saldo digunakan",
                    "ppn" => "ppn",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
//            "cash_account" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "cash account",
//                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "jenis2=.1",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "account number",
//                    "alias" => "holder alias",
//
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCashAccountJenis",
//                    "calculate" => array(
//                        "source" => "cash_account",
//                        "filter" => array(
//                            "cabang_id=placeID",
//                        ),
//                        "result" => array(
//                            "nilai_setoran_tunai" => "nilai_entry",
//                        ),
//                    ),
//                ),
//            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note(deposit) return",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "label=.piutang dagang",
                    "sisa>.0",
                    "trash=.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer name",
                    "sisa" => "avail credit",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "autoSelect" => false,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
            ),
            "pointKonsumen" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "point konsumen",
                "mdlName" => "MdlCustomerAll",
                "mdlFilter" => array(
                    "id=pihakID",
                ),
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuCustomerDetail",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "extern_id" => "pihakID",
                        "extern2_id" => ".2010050030",//point
                        "cabang_id" => "cabangID",
                        "periode" => ".forever",
                    ),
                    "key" => "extern_id",
                    "rekening" => "2010050",
                    "fieldID" => "qty_kredit",
                    "fieldLabel" => "saldo",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "customer name",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "autoSelect" => true,
                "noPrefetch" => false,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//                ),
            ),
            "pointSetting" => array(
                "hiddenSelect" => true,
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "nilai tukar point",
                "mdlName" => "MdlDiskonCustomer",
                "mdlFilter" => array(
                    "tipe=.point",
                    "jenis=.point",
                ),
                "key" => "id",
                "labelSrc" => "nilai",
                "usedFields" => array(
                    "nilai" => "nilai",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "autoSelect" => false,
                "noPrefetch" => false,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
            ),
            //--------
            "uangMukaProject" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "uang muka project",
                "mdlName" => "MdlCustomerUangMukaProject",
                "mdlFilter" => array(
                    "cabang_id=cabangID",
                    "extern_id=projectID",
                    "extern2_id=pihakID",
                    "extern3_id=.2010050080",// hutang ke konsumen project, projectID
                ),
                "key" => "kredit",
                "labelSrc" => "kredit",
                "usedFields" => array(
                    "extern_nama" => "konsumen",
                    "kredit" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "piutangUsahaProject" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "hiddenBox" => true,
                "label" => "piutang usaha project",
                "mdlName" => "MdlCustomerPiutangUsahaProject",
                "mdlFilter" => array(
                    "cabang_id=cabangID",
                    "extern_id=pihakID",
                    "extern2_id=projectID",
                    "extern3_id=.0",// piutang usaha project, projectID
                ),
                "key" => "debet",
                "labelSrc" => "debet",
                "usedFields" => array(
                    "extern_nama" => "konsumen",
                    "debet" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "piutangKontijensiProject" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "hiddenBox" => true,
                "label" => "piutang kontijensi project",
                "mdlName" => "MdlCustomerPiutangKontijensiProject",
                "mdlFilter" => array(
                    "cabang_id=cabangID",
                    "extern_id=pihakID",
                    "extern2_id=projectID",
                    "extern3_id=.0",// piutang usaha project, projectID
                ),
                "key" => "debet",
                "labelSrc" => "debet",
                "usedFields" => array(
                    "extern_nama" => "konsumen",
                    "debet" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            //--------
            "dummyElement" => array(
                "elementType" => "dataModel",
                "hiddenBox" => true,
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "labelSrcFields" => array(
                            "folders_nama", "nama", "alias",
                        ),
                        "usedFields" => array(
                            "nama" => "account number",
                            "alias" => "holder alias",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
                         * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
                         */

//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "labelSrcFields" => array(
                            "folders_nama", "nama", "alias",
                        ),
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID",
//                            "biaya_persen" => "biaya(%)",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "labelSrcFields" => array(
                            "folders_nama", "nama", "alias",
                        ),
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "allowedSaldo" => array(
            "enabled" => true,
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
            "selectedType_konsumen" => "Tipe konsumen",

        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shopingCartAddTax" => array(
            "fields" => array(
                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
            ),
            "inputType" => "checkbox",
            "selector" => "_selectorPihak/selectTax",
            "srcGateValue" => "nilai_sisa",
            // "srcGateValue"=>"sisa",

        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_biaya" => "amount value",
                "uangMukaPpnTerbayar" => "uang muka (PPN)",
                "uang_muka_dipakai" => "uang muka",
                "pph23" => "Pph Ps 23 Dibayar Dimuka",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account" => "cash account belum dipilih",
            ),
            array(
                "lebih_bayar" => "kelebihan bayar nol (0)",
                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
            ),
        ),
        "shopingCartPaymentValidator" => array(
            "nilai_entry" => array(
                "label" => "payment belum diisi",
            ),
            "cash_account" => array(
                "label" => "cash account belum dipilih",
            ),
            "creditAmount" => array(
                "label" => "credit note (from return) belum dipilih",
            ),
        ),
        "shopingCartPairedPaymentValidator" => array(
            "nilai_entry" => array(
                "key" => "cash_account",
                "label" => "akun kas belum dipilih.",
            ),
        ),
        "shopingCartPaymentMethodValidator" => array(
            "nilai_entry" => array(
                "label" => "Payment Method wajib dipilih.",
            ),
        ),
        "shopingCartPaymentMethodGateValidator" => array(
            "paymentMethod" => array(
                "cashless" => array(
                    "validate_biaya" => "berikan tic (biaya suport) sebagai validasi bahwa biaya sudah dicek dan sesuai",
                ),
                "transfer" => array(
                    "validate_biaya" => "berikan tic (biaya suport) sebagai validasi bahwa biaya sudah dicek dan sesuai",
                ),
            ),
        ),
        "shopingcartAddTicbox" => array(
            "nilai_biaya" => array(
                "label" => "berikan tic (biaya suport) sebagai validasi bahwa biaya sudah dicek dan sesuai",
                "name" => "validate_biaya",
                "methode" => "_processPihak/selectBiaya/749",
            ),
        ),
        "shoppingCartLoadReference" => true,
        "shoppingCartReferenceFields" => array(
            "fulldate" => "date",
            "nomer" => array(
                "label" => "nomer pengiriman",
                "link_detail" => true,
            ),
            "nomer_top" => "nomer inv",
            "nett1" => "bruto",
            "diskon_kategori_unit" => "nilai diskon unit",
            "nett1_bulat" => "dpp",
            "ppn_out_bulat" => "ppn",
            "tagihan" => "tagihan",
//            "refValue" => "returned",
            "terbayar" => "dibayar",
//            "diskon" => "discount",
            "sisa" => "sisa",
            "extern3_nama" => "marketplace",
            "project_nama" => "project",
            "payment_source_keterangan" => "keterangan",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_id" => "ID",
            "extern_nama" => "customer",
            "qty_nota" => "jumlah<br>invoice",
            "tenor" => "lama kredit<br>(tenor)",
            "limit" => "kredit limit",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
//            "diskon" => "discount",
            "sisa" => "sisa",
            // "a_sisa" => "return",
            // "a_saldo" => "saldo",
            "uang_muka_tanpa_ppn" => "Deposit (Uang Muka<br>tanpa PPN)",
            "reference_oleh_nama" => "pic",
        ),
        "shoppingCartReferenceRegistry" => array(
            "key" => "main",
            "fields" => array(
                "nett1" => "bruto",
                "diskon_kategori_unit" => "nilai diskon unit",
                "nett1_bulat" => "dpp",
                "ppn_out_bulat" => "ppn",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_round" => array(
                        "label" => "value of invoice",
                        "defaultValue" => "nilai_round",
                        "maxValue" => "nilai_round",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                    "credit_amount" => array(
                        "label" => "credit note(deposit) return",
//                        "defaultValue" => "credit_note_dipakai",
                        "defaultValue" => "0",
                        "maxValue" => "creditAmount__sisa",
                        "saldo" => "creditAmount__sisa",
//                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "point_konsumen_qtt" => array(
                        "label" => "jumlah point konsumen",
                        "defaultValue" => "0",
                        "maxValue" => "pointKonsumen__saldo",
                        "saldo" => "pointKonsumen__saldo",
//                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "point_konsumen_nilai" => array(
                        "label" => "nilai point konsumen",
                        "defaultValue" => "point_konsumen_nilai_dipakai",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "ppn_nilai_dibayar" => array(
                        "label" => "(ppn dibayar bendahara negara)",
                        "defaultValue" => "ppn_nilai_dibayar",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph22_nilai" => array(
                        "label" => "(pph 22 dibayar dimuka)",
                        "defaultValue" => "pph22_nilai",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                    "nilai_biaya" => array(
                        "label" => "(biaya suport/biaya merchant)",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",

                        //                        "keyupAction" =>
                        //                            "
                        //                                var gt=document.getElementById('harus_bayar').value;
                        //                                var bayar=this.value;
                        //                                var add_value=document.getElementById('lebih_bayar');
                        //                                var nilai=(parseFloat(bayar)-parseFloat(gt));
                        //
                        //                                if(parseFloat(nilai)>=0){
                        //                                    lebih_bayar.value=parseFloat(nilai);
                        //                                }
                        //                                else{
                        //                                    lebih_bayar.value=parseFloat(0);
                        //                                }
                        //                            ",

                        "addPoints" => array(1,),
                    ),

                    "harus_bayar" => array(
                        "label" => "total invoice(netto)",
                        "defaultValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "maxValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "minValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    // "nilai_round" => array(
                    //     "label" => "total invoice(netto)",
                    //     "defaultValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "maxValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "minValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "keyPressAction" => "",
                    //     'disabled' => "disabled",
                    //     "addPoints" => array(1,),
                    // ),

                    "pph23" => array(
                        "label" => "potongan pph23",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => "0",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        "disabled" => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                        "hideRow" => false,
                        "defaultView" => "none",
                    ),
                    "dp_nilai" => array(
                        "label" => "DP",
                        "defaultValue" => "dp_nilai",
                        "maxValue" => "",
                        "disabled" => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                        "hideRow" => true,
                        "defaultView" => "none",
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "Uang Muka/Titipan (Non PPN)",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => "0",
//                        "maxValue" => "uangMuka__saldoUangMuka",
                        "maxValue" => "uangMuka__saldoUangMukaround",
                        "saldo" => "uangMuka__saldoUangMuka",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uangMukaPpnTerbayar" => array(
                        "label" => "uang muka (PPN)<div class='hidden' id='saldoRelasiSO'>Total saldo dari SO Lain: 0</div><div id='linkViewLepasRelasi' class='text-link hidden' onclick=\"top.callViewLepasRelasi()\"><u><i>mau lepas relasi dari SO lain?</i></u></div>",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => "0",
                        "disabled" => "disabled",
//                        "saldo" => "uangMuka__saldoUangMuka",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa_before_entry" => array(
                        "label" => "netto",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => "new_sisa_before_entry",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => false,
                    ),
                    "nilai_entry" => array(
                        "label" => "uang diterima",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => ".0",
                        // "keyupAction" => "
                        //    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                        // ",
                        "keyupAction" =>
                            "
                                var gt=removeCommas(document.getElementById('nilai_round').value);
                                var bayar=removeCommas(this.value);
                                var add_value=document.getElementById('lebih_bayar');
                                var nilai=parseFloat(gt)-(parseFloat(bayar));
                                var nilai_biaya=removeCommas(document.getElementById('nilai_biaya').value);
                                var harus_bayar=parseFloat(gt)-(parseFloat(nilai_biaya));
                                if(parseFloat(nilai_biaya)>0){
                                    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                                }
                                if(parseFloat(nilai)>=0){
                                    add_value.value=addCommas(parseFloat(nilai));
                                }
                                else{
                                    add_value.value=parseFloat(0);
                                }
                            ",
                        "addPoints" => array(1,),
                    ),
                    "new_sisa" => array(
                        "label" => "sisa harus dibayar",
                        "addBackground" => "bg-pink-light",
                        "defaultValue" => ".0",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                        "option" => array(
                            "source" => "customerDetails__parent",
                            "key" => "customerDetails__parent_cek",
                            "key_value" => "nilai_dijadikan_credit_note",
                            "label" => "Konsumen ini juga sebagai Supplier. Jadikan Sisa Piutang menjadi CreditNote?",
                        ),
                    ),
                    "lebih_bayar" => array(
                        "label" => "lebih bayar",
                        "defaultValue" => ".0",
                        //                        "maxValue" => "new_sisa",
                        //                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                    ),
                    "nilai_dijadikan_credit_note" => array(
                        "label" => "nilai dijadikan credit note",
                        "defaultValue" => "nilai_dijadikan_credit_note",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "kelebihanBayar" => true,
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        "viewDescriptionNote" => true,
        "selectorNotaLocker" => array(
            "enabled" => true,
        ),
        "pembayaranBerurutan" => false,// true: pelunasan nota urut dari tertua, false: pelunasan nota bebas
        "allowedSaldo" => array(
            "enabled" => true,

        ),
        "pairDepositKonsumen" => array(
            "uang_muka_tanpa_ppn" => array(
                "comName" => "ComRekeningPembantuCustomerDetail",
                "rekening" => "2010050",
                "sub_rekening" => "2010050050",
                "saldo" => "kredit",
                "label" => "Deposit/Uang Muka tanpa PPN",
                "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/",
            ),
        ),
        "shopingCartPaymentItemsValidator" => array(
            "nilai_bayar" => array(
//                "source" => "nilai_dipakai_hutang_dagang", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Jumlah Pembayaran salah karena tercatat 0 untuk pembayaran nota {nota}. Silahkan refresh halaman ini atau hubungi admin.", //
                "label2" => "Jumlah Pembayaran salah karena tercatat 0 untuk pembayaran nota yang dipilih. Silahkan refresh halaman ini atau hubungi admin.", //
            ),
        ),
        "shopingCartPaymentItemsColor" => array(
            "enabled" => true,
            "colorCode" => array(
                0 => array(
                    "label" => "Lunas",
                    "color" => "lightgreen",// lunas, hijau
                ),
                1 => array(
                    "label" => "Dibayar, Belum Lunas",
                    "color" => "yellow",// belum lunas, kuning
                ),
                2 => array(
                    "label" => "Tidak Dibayar",
                    "color" => "red",// tidak terbayar, merah, wajib dihapus
                ),
            ),
        ),
        //----DIBAWAH INI KEPERLUAN MARKETPLACE
        "groupTipePenjualan" => array(
            "enabled" => true,
            "groupKey" => "extern4_id",
            "groupExternKeyID" => "extern3_id",
            "groupExternKeyNama" => "extern3_nama",
        ),
        "shoppingCartReferenceExternFieldsMarketplace" => array(
            "extern3_id" => "ID",
            "extern3_nama" => "Marketplace",
            "qty_nota" => "jumlah<br>invoice",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
            "sisa" => "sisa",
        ),
        "shoppingCartReferenceFieldsMarketplace" => array(
            "fulldate" => "date",
            "nomer" => array(
                "label" => "nomer pengiriman",
                "link_detail" => true,
            ),
            "nomer_top" => "nomer inv",
            "extern_nama" => "konsumen",
            "nett1" => "bruto",
//            "diskon_kategori_unit" => "nilai diskon unit",
            "nett1_bulat" => "dpp",
            "ppn_out_bulat" => "ppn",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
            "sisa" => "sisa",
//            "extern3_nama" => "marketplace",
            "payment_source_keterangan" => "keterangan",
        ),
        "receiptElementsDeleter" => array(
            "enabled" => true,
//            "tipe_penjualan" => 1,// marketplace
//            "element" => array(
//                "customerDetails",
//                "kelebihanBayar",
//                "uangMuka",
//                "creditAmount",
//                "pointKonsumen",
//                "pointSetting",
//                "uangMukaProject",
//            ),
            "tipe_penjualan" => array(
                0 => array(// penjualan reguler
                    "uangMukaProject",
                    "piutangUsahaProject",
                    "piutangKontijensiProject",
                ),
                1 => array(
                    "customerDetails",
                    "kelebihanBayar",
                    "uangMuka",
                    "creditAmount",
                    "pointKonsumen",
                    "pointSetting",
                    "uangMukaProject",
                    "piutangUsahaProject",
                    "piutangKontijensiProject",
                    "uangMukaPpn",
                ),
                11 => array(
                    "uangMuka",
                    "creditAmount",
                    "pointKonsumen",
                    "pointSetting",
                ),
            ),
        ),
        "receiptElementsReplacer" => array(
            "enabled" => true,
            "tipe_penjualan" => 1,// marketplace
            "element" => array(
                "paymentMethod" => array(
                    "elementType" => "dataModel",
                    "inputType" => "radio",
                    "label" => "payment method",
                    "mdlName" => "MdlPaymentMethod2",
                    "mdlFilter" => array(
                        "non_tunai=.1",
                    ),
                    "key" => "id",
                    "labelSrc" => "name",
                    "usedFields" => array(
                        "name" => "Name",
                        "note" => "Note",
                    ),
                    "editPoints" => array(1),
                ),
            ),
        ),
        "cloneGateItems" => array(
            "nilai_bayar_marketplace" => "nilai_bayar",
        ),
        "shoppingCartFieldsMarketplace" => array(
            1 => array(
                "nama" => "item name",
                "nomer_top" => "nomer inv",
                "extern_nama" => "konsumen",
                "jml" => "qty",
            ),

        ),
        "additionalRowsDeleter" => array(
            "enabled" => true,
//            "tipe_penjualan" => 1,// marketplace
//            "additionalRows" => array(
//                "uang_muka_dipakai",
//                "credit_amount",
//                "point_konsumen_qtt",
//                "point_konsumen_nilai",
//                "ppn_nilai_dibayar",
//                "pph22_nilai",
//                "nilai_dijadikan_credit_note",
//                "lebih_bayar",
//            ),
            "tipe_penjualan" => array(
                0 => array(// penjualan reguler
                    "dp_nilai",
                ),
                1 => array(// penjualan marketplace
                    "uang_muka_dipakai",
                    "credit_amount",
                    "point_konsumen_qtt",
                    "point_konsumen_nilai",
                    "ppn_nilai_dibayar",
                    "pph22_nilai",
                    "nilai_dijadikan_credit_note",
                    "lebih_bayar",
                    "dp_nilai",
                    "uangMukaPpnTerbayar",
                ),
                11 => array(// penjualan project
                    "uang_muka_dipakai",
                    "credit_amount",
                    "point_konsumen_qtt",
                    "point_konsumen_nilai",
                    "ppn_nilai_dibayar",
                    "pph22_nilai",
                    "nilai_dijadikan_credit_note",
                    "lebih_bayar",
                ),
            ),
        ),
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "dpp_final" => "Dasar Pengenaan pajak",
                    "dpp_pengganti" => "DPP pengganti(DPP Lain)",
                    "ppn_final" => "Ppn",
                    "dpp_nppn_final" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur",
//                    "skip_faktur" => "belum ada faktur",
                ),
                "addFields" => array(//                    "ppn_sudah_faktur" => "ppn_final",
                ),
                "editableFields" => array(
                    "dpp_final" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
//                    "skip_faktur" => "checkbox",
                ),
                "editProcess" => "_processPihak/addTaxData",
                "gateTarget" => "items6_sum",
                "show" => true,
                "showKey" => "tipe_penjualan",// hanya marketplace
            ),
        ),
        "efakturValidator" => array(
            1 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn_sudah_faktur",
                ),
                "gateSource" => "items6_sum",
            ),
        ),
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "projectValidate" => array(
            "enabled" => true,
            "mode" => array("uangmuka", "project"),
            "keyCek" => "projectID",
            "label" => "Anda memilih 2/lebih PROJECT yang berbeda untuk ditindaklanjuti. Silahkan dikoreksi.",
        ),
        "modeLabelPenerimaan" => array(
            "uangmuka" => "Anda memilih tagihan Project dan akan dijadikan Uang Muka Project.",
            "project" => "Anda memilih tagihan Project dan akan dijadikan Invoice.",
        ),
        "connectTo" => "110",
        "connectoValidate" => array(
            1 => "ppn_keluaran_inv",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "showDetailUangMuka" => array(
            "project" => array(
                "enabled" => true,
                "mdlName" => "MdlPaymentSource",
                "mdlFilter" => array(
                    "cabang_id=cabangID",
                    "extern_id=pihakID",
                    "project_id=projectID",
                    "label=.uang muka konsumen",
                    "sisa>.100",
                ),
                "gateTarget" => "items8_sum",
                "headers" => array(
                    "dtime" => "tanggal",
                    "nomer" => "nomer",
                    "isi" => "isi",
                    "sisa" => "nilai uang muka",
                    "oleh_nama" => "pic",
                ),
                "label" => "Daftar Termin yang diterima sebagai Uang Muka/Titipan",
            ),
        ),
    ),

    //penerimaan piutang jsa kirim
    "2749" => array(
        "icon" => "fa fa-money",
        "label" => "shipping service A/R receivement",
        "place" => "branch",//=> "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "A/R shipping service",
                "actionLabel" => "process receivement",
                "source" => "",
                "target" => "2749",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582spo",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,

        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "tagihan" => "amount remains to pay",
                //                "ppn" => "vat",
                //                "nett2" => "total",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_note" => array(
                    //                        "label" => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue" => "(sisa-creditAmount-creditValue)",
                        "minValue" => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('harus_bayar').value);}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //                    "new_sisa" => array(
                    //                        "label"=>"remain receivable (from list)",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        "minValue" => "",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        "shoppingCartLoadReference" => true,
    ),
    "1749" => array(
        "icon" => "fa fa-money",
        "label" => "international A/R receivement",
        "place" => "branch",//=> "center",
        "paymentConfig" => true,
        "steps" => array(
            1 => array(
                "label" => "A/R receivement",
                "actionLabel" => "process receivement",
                "source" => "",
                "target" => "1749",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.382",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "tagihan_valas" => "tagihan_valas",
            "terbayar_valas" => "terbayar_valas",
            "sisa_valas" => "sisa_valas",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa_valas" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa_valas",

        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_in",
                "mdlFilter" => array(
                    "currency_id=valasDetails",
//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "alias",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "nama" => "account",
                    "currency" => "currency",
                ),
                "editPoints" => array(1,),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod3",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "lc" => array(
                    "lc_account" => array(
                        "elementType" => "dataField",
                        "inputType" => "text",
                        "label" => "LC Number",
                        //                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "tt" => array(
                    "tt_account" => array(
                        "elementType" => "dataField",
                        "inputType" => "text",
                        "label" => "TT Notes",
                        //                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),

            ),
            "credit_account" => array(
                "visa_card" => array(
                    "card_number" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "card number",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "valid trough",
                        "inputType" => "date",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "card_name" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "name on card",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),


                ),
                "master_card" => array(
                    "card_number" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "card number",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "valid trough",
                        "inputType" => "date",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "card_name" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "name on card",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                ),

            ),

        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        //        "shoppingCartSumFields" => array(
        //            1 => array(
        ////                "dics_valas" => "disc",
        ////                "nett2_valas" => "total",
        //            ),
        //        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa_valas",
                    //                        "maxValue" => "sisa_valas",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_note" => array(
                    //                        "label" => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa_valas-creditAmount-creditValue)",
                        "maxValue" => "(sisa_valas-creditAmount-creditValue)",
                        "minValue" => "(sisa_valas-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('harus_bayar').value);}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "expense req. number",
            //            "nomer_top" => "receipt ref.",
            //            "refNum"    => "return ref.",
            "fulldate" => "date",
            "valas_nama" => "currency",
            "tagihan_valas" => "due amount valas",
            //            "refValue"  => "returned",
            "terbayar_valas" => "paid valas",
            "diskon" => "discount valas",
            "sisa_valas" => "due remain valas",


        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
            "tagihan_valas" => "due amount valas",
            "terbayar_valas" => "paid valas",
            "diskon_valas" => "discount valas",
            "sisa_valas" => "due remain valas",
        ),
        "exchangeValidate" => array( // validasi untuk gerbang items
            "enabled" => true,
//            "key" => array(
//                // source => target (items)
//                "valas_id" => "valas_id",
//            ),
            "label" => "mata uang asing terdeteksi tidak sama, silahkan pilih yang sesuai.",
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
    ),
    //penerimaan pembayaran piutang penjualan jasa
    /*tidak punya coConfigLayout*/
    "1784" => array(
        "icon" => "fa fa-money",
        "label" => "A/R Receipt service sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "A/R Receipt service",
                "actionLabel" => "process receivement",
                "source" => "",
                "target" => "1784",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "paymentConfig" => true,
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",

            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "ppn_sisa" => "ppn_sisa",
            "pph_23" => "pph_23",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "ppn_sisa" => "ppn",
                //                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                //                "pph_23_net" =>"pph 23",
                //                "pph_23" =>"pph 23**",
                "pph_23_net" => "pph 23*",
                "creditAmount" => "customer credit amount",
                "nilai_entry2" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                "new_sisa" => "remain receivable (from list)",
                //                "sisa-nilai_bayar" => "remain receivable (from list)",
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "dateFaktur" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "tanggal faktur pph 23",
                "inputType" => "date",
                "defaultValue" => "",
                "editPoints" => array(1, 2, 3),
                "noValidate" => true,
            ),
            "eFaktur" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "nomer faktur pph 23",
                "inputType" => "text",
                "defaultValue" => "",
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3),
                "noValidate" => true,
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit amount (from return)",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "target_jenis=jenisTr",
                    "label=.piutang dagang",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "nomer/sisa",
                "usedFields" => array(
                    "extern_nama" => "customer name",
                    "transaksi_id" => "return ID",
                    "nomer" => "return number",
                    "sisa" => "avail credit",
                    "jenis" => "jenis",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "autoSelect" => false,
                "noPrefetch" => true,
            ),

            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
            //            "eFaktur" => "customer name",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "credit_amount" => array(
                        "label" => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    //                    "credit_note" => array(
                    //                        "label" => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay**",
                        "defaultValue" => "(sisa-creditAmount-creditValue-pph_23)",
                        "maxValue" => "(sisa-creditAmount-creditValue-pph_23)",
                        "minValue" => "(sisa-creditAmount-creditValue-pph_23)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "connectTo" => "116",
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang

        ),
        "jQueryCustom" => "
            <script>
                console.log('test 1784');
            </script>
        ",
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
    ),
    //penerimaan project, penerbitan termin
    "7499" => array(
        "icon" => "fa fa-money",
        "label" => "A/R Receipt Project",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "A/R Receipt Project",
                "actionLabel" => "process receivement",
                "source" => "",
                "target" => "7499",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "paymentConfig" => true,
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.588so",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "sisa" => "tagihan",
            "nilai_biaya" => "biaya usaha",
            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
            "credit_note_dipakai" => "credit note<br>(from return)",
            "uang_muka_dipakai" => "uang muka",
            "nilai_entry" => "CASH RECEIVED",
            //            "new_sisa"            => "sisa tagihan",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                "details" => "nama",
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "total amount",
                //                "credit_note_dipakai" => "customer credit amount",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain receivable (from list)",
                //                "sisa-nilai_bayar" => "remain receivable (from list)",
            ),
        ),


        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "kelebihanBayar" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "balance",
                "mdlName" => "MdlStaticMethodLebihBayar",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    //                    0 => "ReComMethodLebihBayar",
                    1 => "ReComMethodLebihBayar",
                    2 => "ReComMethodLebihBayar",
                ),
                "noValidate" => true,
                "noPrefetch" => true,
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "extern_label2=.customer",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
                    "extern_label2" => "tipe",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComUangMuka",
                    "calculate" => array(
                        "source" => "sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
                    ),
                ),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "jenis2=.1",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashAccountJenis",
                    "calculate" => array(
                        "source" => "cash_account",
                        "filter" => array(
                            "cabang_id=placeID",
                        ),
                        "result" => array(
                            "nilai_setoran_tunai" => "nilai_entry",
                        ),
                    ),
                ),
            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit amount (from return)",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    //                    "target_jenis=jenisTr",
                    "label=.piutang dagang",
                    "sisa>.0",
                    "trash=.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer name",
                    //                    "transaksi_id" => "return ID",
                    //                    "nomer" => "return number",
                    "sisa" => "avail credit",
                    //                    "jenis" => "jenis",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "autoSelect" => false,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComCreditNote",
                    "calculate" => array(
                        "source" => "creditAmount", // credit_note
                        "target" => "credit_note_dipakai",
                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        //        "pairRegistries"                        => array(
        //            "main",
        //        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
            "selectedType_konsumen" => "Tipe konsumen",

        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
        "shopingCartAddTax" => array(
            "fields" => array(
                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
            ),
            "inputType" => "checkbox",
            "selector" => "_selectorPihak/selectTax",
            "srcGateValue" => "nilai_sisa",
            // "srcGateValue"=>"sisa",

        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_biaya" => "amount value",
                "uang_muka_dipakai" => "uang muka",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account" => "cash account belum dipilih",
            ),
            array(
                "lebih_bayar" => "kelebihan bayar nol (0)",
                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
            ),
        ),
        "shopingCartPaymentValidator" => array(

            "nilai_entry" => array(
                "label" => "payment belum diisi",
            ),
            "cash_account" => array(
                "label" => "cash account belum dipilih",
            ),
            "creditAmount" => array(
                "label" => "credit note (from return) belum dipilih",
            ),


        ),
        "shopingCartPairedPaymentValidator" => array(
            "nilai_entry" => array(
                "key" => "cash_account",
                "label" => "cash account belum dipilih.",
            ),
        ),
        //        "shopingCartPaymentComparisonValidator" => array(
        //            array(
        //                "source" => "nilai_dipakai_piutang_dagang", // hutang dagang
        //                "target" => "nilai_bayar", // payment source
        //                "label"  => "Pastikan penggunaan Kas, Uang Muka, Credit Note (from return) sudah sesuai untuk pelunasan Invoice ini.", //
        //            ),
        //        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_round" => array(
                        "label" => "value of invoice",
                        "defaultValue" => "nilai_round",
                        "maxValue" => "nilai_round",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "Uang Muka",
                        "defaultValue" => "0",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_amount" => array(
                        "label" => "credit note(deposit)",
                        "defaultValue" => "credit_note_dipakai",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "ppn_nilai_dibayar" => array(
                        "label" => "(ppn dibayar bendahara negara)",
                        "defaultValue" => "ppn_nilai_dibayar",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph22_nilai" => array(
                        "label" => "(pph 22 dibayar dimuka)",
                        "defaultValue" => "pph22_nilai",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_biaya" => array(
                        "label" => "(biaya suport)",
                        "defaultValue" => ".0",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",

                        //                        "keyupAction" =>
                        //                            "
                        //                                var gt=document.getElementById('harus_bayar').value;
                        //                                var bayar=this.value;
                        //                                var add_value=document.getElementById('lebih_bayar');
                        //                                var nilai=(parseFloat(bayar)-parseFloat(gt));
                        //
                        //                                if(parseFloat(nilai)>=0){
                        //                                    lebih_bayar.value=parseFloat(nilai);
                        //                                }
                        //                                else{
                        //                                    lebih_bayar.value=parseFloat(0);
                        //                                }
                        //                            ",

                        "addPoints" => array(1,),
                    ),

                    "harus_bayar" => array(
                        "label" => "total invoice(netto)",
                        "defaultValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "maxValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "minValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    // "nilai_round" => array(
                    //     "label" => "total invoice(netto)",
                    //     "defaultValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "maxValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "minValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "keyPressAction" => "",
                    //     'disabled' => "disabled",
                    //     "addPoints" => array(1,),
                    // ),

                    "nilai_entry" => array(
                        "label" => "cash received",
                        "defaultValue" => ".0",
                        //                        "keyupAction" => "
                        //    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                        //                            ",

                        "keyupAction" =>
                            "
                                var gt=removeCommas(document.getElementById('nilai_round').value);
                                var bayar=removeCommas(this.value);
                                var add_value=document.getElementById('lebih_bayar');
                                var nilai=parseFloat(gt)-(parseFloat(bayar));
                                var nilai_biaya=removeCommas(document.getElementById('nilai_biaya').value);
                                var harus_bayar=parseFloat(gt)-(parseFloat(nilai_biaya));
                                if(parseFloat(nilai_biaya)>0){
                                    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                                }
                                
                                if(parseFloat(nilai)>=0){

                                    add_value.value=addCommas(parseFloat(nilai));
                                }
                                else{
                                    add_value.value=parseFloat(0);
                                }
                            ",

                        "addPoints" => array(1,),
                    ),


                    "new_sisa" => array(
                        "label" => "balance",
                        "defaultValue" => ".0",
                        //                        "maxValue" => "new_sisa",
                        //                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                    ),
                    "lebih_bayar" => array(
                        "label" => "lebih bayar",
                        "defaultValue" => ".0",
                        //                        "maxValue" => "new_sisa",
                        //                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                    ),
                ),

            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "kelebihanBayar" => true,
        "previewCtr" => "Create",
    ),

    //penerimaan retensi project
//    "7488__" => array(
//        "icon" => "fa fa-money",
//        "label" => "A/R Receipt Project (retensi)",
//        "place" => "branch",//=> "center",
//        "steps" => array(
//            1 => array(
//                "label" => "A/R Receipt Project (retensi)",
//                "actionLabel" => "process receivement",
//                "source" => "",
//                "target" => "7488",
//                "userGroup" => "o_finance",
//                "stateLabel" => "completed",
//                "stateColor" => "#009900",
//                "stateCaption" => "confirmed by",
//            ),
//        ),
//        "paymentConfig" => true,
//        "template" => "template/transaksi_payment.html",
//        "selectorModel" => "MdlNota",
//        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jenis=.588so",
//            "transaksi_nilai_sisa>.0",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nomer",
//        ),
//        "selectorViewedFields" => array(
//            "nomer",
//            "dtime",
//        ),
//
//        "selectorProcessor" => "_processSelectNota/select",
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlCustomer",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "customer",
//        "pihakProcessor" => "_processPihak/select",
//        "shortHistoryFields" => array(
//            //            "jenis_label"    => "activity",
//            "dtime" => "date",
//            "customers_nama" => "customer",
//            "nomer" => "receipt number",
//            "details" => "detail",
//            "oleh_nama" => "person",
//            "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
//            "nilai_entry" => "CASH RECEIVED",
//            //            "new_sisa"            => "sisa tagihan",
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "customers_nama" => "customer",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "nilai_entry" => "amount",
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                //                "review_details" => "id",
//                "print_label" => "nomer",
//            ),
//        ),
//        "extHistoryFields2" => array(
//            1 => array(
//                "details" => "nama",
//            ),
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "item name",
//                "jml" => "qty",
//            ),
//
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nomer",
//            "tagihan" => "tagihan",
//            "terbayar" => "terbayar",
//            "sisa" => "sisa",
//
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "sisa" => "due remain",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(),
//        "shoppingCartAmountValue" => array(
//            1 => "sisa",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                //                "sisa" => "total amount",
//                //                "credit_note_dipakai" => "customer credit amount",
//                //                "nilai_entry" => "paid using cash account",
//                //                "nilai_bayar" => "total amount of payment",
//                //                "new_sisa" => "remain receivable (from list)",
//                //                "sisa-nilai_bayar" => "remain receivable (from list)",
//            ),
//        ),
//
//
//        "pairRegistries" => array(
//            "main", "items"
//        ),
//        "shoppingCartAvoidRemove" => true,
//        "shoppingCartAvoidRemoveAll_items" => false,
//        "tagihanSrc" => "harus_bayar",
//        "receiptElements" => array(
//            "customerDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "customer details",
//                // "mdlName"     => "MdlCustomer_and_pre",
//                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "alamat_1" => "alamat",
//                    "alamat_2" => "alamat",
//                    "kelurahan" => "Kel",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "Prop",
//                    "tlp" => "Tlp",
//                    "tlp_1" => "Tlp",
//                    "tlp_2" => "Handphone",
//                    "npwp" => "NPWP",
//                    "no_ktp" => "nik",
//                    "nik" => "NIK",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
//            "kelebihanBayar" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "balance",
//                "mdlName" => "MdlStaticMethodLebihBayar",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "method",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    //                    0 => "ReComMethodLebihBayar",
//                    1 => "ReComMethodLebihBayar",
//                    2 => "ReComMethodLebihBayar",
//                ),
//                "noValidate" => true,
//                "noPrefetch" => true,
//            ),
////            "uangMuka" => array(
////                "elementType" => "dataModel",
////                "inputType" => "radio",
////                "label" => "Uang Muka (DP tanpa PPN)",
////                "mdlName" => "MdlCustomerUangMuka",
////                "mdlFilter" => array(
////                    "extern_id=pihakID",
////                    "cabang_id=cabangID",
////                    "extern_label2=.customer",
////                    "sisa>.0",
////                ),
////                "key" => "id",
////                "labelSrc" => "sisa",
////                "usedFields" => array(
////                    "extern_nama" => "customer",
////                    "extern_id" => "pihakID",
////                    "extern_label2" => "tipe",
////                ),
////                "editPoints" => array(1,),
////                "noValidate" => true,
////                "noPrefetch" => true,
////                "pairMethod" => array(
////                    "recom" => "ReComUangMuka",
////                    "calculate" => array(
////                        "source" => "sisa",
////                        "target" => "uang_muka_dipakai",
////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
////                    ),
////                ),
////            ),
//            "cash_account" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "cash account",
//                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "jenis2=.1",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "labelSrcFields" => array(
//                    "folders_nama", "nama", "alias",
//                ),
//                "usedFields" => array(
//                    "nama" => "account number",
//                    "alias" => "holder alias",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCashAccountJenis",
//                    "calculate" => array(
//                        "source" => "cash_account",
//                        "filter" => array(
//                            "cabang_id=placeID",
//                        ),
//                        "result" => array(
//                            "nilai_setoran_tunai" => "nilai_entry",
//                        ),
//                    ),
//                ),
//            ),
////            "creditAmount" => array(
////                "elementType" => "dataModel",
////                "inputType" => "radio",
////                "label" => "credit amount (from return)",
////                "mdlName" => "MdlPaymentAntiSource",
////                "mdlFilter" => array(
////                    "extern_id=pihakID",
////                    "cabang_id=cabangID",
////                    //                    "target_jenis=jenisTr",
////                    "label=.piutang dagang",
////                    "sisa>.0",
////                    "trash=.0",
////                ),
////                "key" => "sisa",
////                "labelSrc" => "sisa",
////                "usedFields" => array(
////                    "extern_nama" => "customer name",
////                    //                    "transaksi_id" => "return ID",
////                    //                    "nomer" => "return number",
////                    "sisa" => "avail credit",
////                    //                    "jenis" => "jenis",
////                ),
////                "editPoints" => array(1,),
////                "noValidate" => true,
////                "autoSelect" => false,
////                "noPrefetch" => true,
////                "pairMethod" => array(
////                    "recom" => "ReComCreditNote",
////                    "calculate" => array(
////                        "source" => "creditAmount", // credit_note
////                        "target" => "credit_note_dipakai",
////                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
////                    ),
////
////                    //                    "customer" => "ReComDiscCustomer",
////                ),
////            ),
//            "dummyElement" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "auto-validation",
//                "mdlName" => "MdlDummyElement",
//                //                "mdlFilter"   => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//        ),
//        "pairMakers" => array(
//            1 => array(
//                "stock" => array(
//                    "helperName" => "he_cek_saldo_kas",
//                    "functionName" => "cekStockSaldoKas",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        //                        "gudang_id" => "gudangID",
//                    ),
//                ),
//            ),
//        ),
//        //        "pairRegistries"                        => array(
//        //            "main",
//        //        ),
//        "mainValueInjectors" => array(
//            "amount" => "sisa",
//            "creditAmount" => "creditAmount",
//            "harus_bayar" => "harus_bayar",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "customer ID",
//            "pihakName" => "customer name",
//            "selectedType_konsumen" => "Tipe konsumen",
//
//        ),
//        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
//        ),
//        "shopingCartAddTax" => array(
//            "fields" => array(
//                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "nilai_sisa",
//            // "srcGateValue"=>"sisa",
//
//        ),
//        "shoppingCartUnionValidators" => array(
//            array(
//                "creditAmount" => "credit amount",
//                "nilai_entry" => "amount value",
//                "nilai_biaya" => "amount value",
//                "uang_muka_dipakai" => "uang muka",
//            ),
//        ),
//        "shopingCartUnionComparison" => array(
//            array(
//                "nilai_entry" => "payment belum diisi",
//                "cash_account" => "cash account belum dipilih",
//            ),
//            array(
//                "lebih_bayar" => "kelebihan bayar nol (0)",
//                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
//            ),
//        ),
//        "shopingCartPaymentValidator" => array(
//
//            "nilai_entry" => array(
//                "label" => "payment belum diisi",
//            ),
//            "cash_account" => array(
//                "label" => "cash account belum dipilih",
//            ),
//            "creditAmount" => array(
//                "label" => "credit note (from return) belum dipilih",
//            ),
//
//
//        ),
//        "shopingCartPairedPaymentValidator" => array(
//            "nilai_entry" => array(
//                "key" => "cash_account",
//                "label" => "cash account belum dipilih.",
//            ),
//        ),
//        //        "shopingCartPaymentComparisonValidator" => array(
//        //            array(
//        //                "source" => "nilai_dipakai_piutang_dagang", // hutang dagang
//        //                "target" => "nilai_bayar", // payment source
//        //                "label"  => "Pastikan penggunaan Kas, Uang Muka, Credit Note (from return) sudah sesuai untuk pelunasan Invoice ini.", //
//        //            ),
//        //        ),
//        "shoppingCartReferenceFields" => array(
//            "nomer" => "sales number",
//            "nomer_top" => "sales ref.",
//            "refNum" => "return ref.",
//            "fulldate" => "date",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
//            "terbayar" => "paid",
//            "diskon" => "discount",
//            "sisa" => "due remain",
//        ),
//        "shoppingCartReferenceExternFields" => array(
//            "extern_nama" => "customer",
//            "tagihan" => "due amount",
//            "terbayar" => "paid",
//            "diskon" => "discount",
//            "sisa" => "due remain",
//        ),
//        "additionalRows" => array(
//            "dummyElement" => array(
//                "yes" => array(
//                    "nilai_round" => array(
//                        "label" => "value of invoice",
//                        "defaultValue" => "nilai_round",
//                        "maxValue" => "nilai_round",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "uang_muka_dipakai" => array(
//                        "label" => "Uang Muka",
//                        "defaultValue" => "0",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "credit_amount" => array(
//                        "label" => "credit note(deposit)",
//                        "defaultValue" => "credit_note_dipakai",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "ppn_nilai_dibayar" => array(
//                        "label" => "(ppn dibayar bendahara negara)",
//                        "defaultValue" => "ppn_nilai_dibayar",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "pph22_nilai" => array(
//                        "label" => "(pph 22 dibayar dimuka)",
//                        "defaultValue" => "pph22_nilai",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "nilai_biaya" => array(
//                        "label" => "(biaya suport)",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
//                            ",
//
//                        //                        "keyupAction" =>
//                        //                            "
//                        //                                var gt=document.getElementById('harus_bayar').value;
//                        //                                var bayar=this.value;
//                        //                                var add_value=document.getElementById('lebih_bayar');
//                        //                                var nilai=(parseFloat(bayar)-parseFloat(gt));
//                        //
//                        //                                if(parseFloat(nilai)>=0){
//                        //                                    lebih_bayar.value=parseFloat(nilai);
//                        //                                }
//                        //                                else{
//                        //                                    lebih_bayar.value=parseFloat(0);
//                        //                                }
//                        //                            ",
//
//                        "addPoints" => array(1,),
//                    ),
//
//                    "harus_bayar" => array(
//                        "label" => "total invoice(netto)",
//                        "defaultValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
//                        "maxValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
//                        "minValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        'hideRow' => "true",
//                    ),
//                    // "nilai_round" => array(
//                    //     "label" => "total invoice(netto)",
//                    //     "defaultValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
//                    //     "maxValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
//                    //     "minValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
//                    //     "keyPressAction" => "",
//                    //     'disabled' => "disabled",
//                    //     "addPoints" => array(1,),
//                    // ),
//
//                    "nilai_entry" => array(
//                        "label" => "cash received",
//                        "defaultValue" => ".0",
//                        //                        "keyupAction" => "
//                        //    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
//                        //                            ",
//
//                        "keyupAction" =>
//                            "
//                                var gt=removeCommas(document.getElementById('nilai_round').value);
//                                var bayar=removeCommas(this.value);
//                                var add_value=document.getElementById('lebih_bayar');
//                                var nilai=parseFloat(gt)-(parseFloat(bayar));
//                                var nilai_biaya=removeCommas(document.getElementById('nilai_biaya').value);
//                                var harus_bayar=parseFloat(gt)-(parseFloat(nilai_biaya));
//                                if(parseFloat(nilai_biaya)>0){
//                                    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
//                                }
//
//                                if(parseFloat(nilai)>=0){
//
//                                    add_value.value=addCommas(parseFloat(nilai));
//                                }
//                                else{
//                                    add_value.value=parseFloat(0);
//                                }
//                            ",
//
//                        "addPoints" => array(1,),
//                    ),
//
//
//                    "new_sisa" => array(
//                        "label" => "balance",
//                        "defaultValue" => ".0",
//                        //                        "maxValue" => "new_sisa",
//                        //                        "minValue" => "new_sisa",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        //                        "hideRow" =>true,
//                    ),
//                    "lebih_bayar" => array(
//                        "label" => "lebih bayar",
//                        "defaultValue" => ".0",
//                        //                        "maxValue" => "new_sisa",
//                        //                        "minValue" => "new_sisa",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        //                        "hideRow" =>true,
//                    ),
//                ),
//
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "customers_nama",
//            "title" => "customer",
//        ),
//        "dueDateReader" => true,
//        "kelebihanBayar" => true,
//        "previewCtr" => "Create",
//    ),

    "7488" => array(
        "icon" => "fa fa-shield",
        "label" => "penerimaan pembayaran retensi project",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "penerimaan pembayaran retensi project",
                "actionLabel" => "terima retensi project",
                "source" => "",
                "target" => "7488",
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.582",
            // "transaksi_nilai_sisa>.0",
            "transaksi_nilai_sisa>.1000",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "dtime" => "date",
            "customerName" => "customer",
            "selectedType_konsumen" => "tipe customer",
            "marketplaceName" => "marketplace",
            "nomer" => "settlement number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "paymentMethod" => "cara pembayaran",
            "cash_account__label" => "akun kas/bank",
            "cash_account__merchant" => "merchant",
            "tagihan" => "tagihan",
            "nilai_entry" => "diterima",
            "credit_note_dipakai" => "credit note<br>(from return)",
            "uang_muka_dipakai" => "uang muka (dp tanpa ppn)",
            "nilai_biaya" => "(biaya support)",
            "nilai_bayar" => "total pembayaran",
            "deposit_konsumen" => "kelebihan bayar<br>deposit konsumen",
            "pendapatan_lain_lain" => "kelebihan bayar<br>pendapatan lain-lain",
            "description" => "catatan",
            "keterangan" => "keterangan",
        ),
        "shortHistoryFieldsReplacer" => array(
            1 => array(
                "kolomCek" => "paymentMethod",
                "paymentMethod" => "cashless",
                "kolom" => array(
                    "cash_account__label" => "cash_account_nama",
                    "cash_account__merchant" => "cash_account__label",
                ),
            ),
        ),

        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nomer",
                "format" => "nomer",
            ),
            "extern_nama" => "konsumen",
            "tagihan" => "tagihan",
            "nilai_bayar" => "diterima",
            "new_sisa" => "sisa",
        ),
        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
            "nilai_entry" => "diterima",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                "details" => "nama",
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "nomer_top" => "nomer inv",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "ppn" => "ppn",
//            "ppn_nilai_dibayar" => "ppn",
            "dpp_ppn" => "dpp_ppn",
//            "dpp_nilai" => "dpp_ppn",
            "nomer_top" => "nomer_top",

            "marketplaceID" => "extern3_id",
            "marketplaceNama" => "extern3_nama",
            "marketplaceName" => "extern3_nama",
            "tipe_penjualan" => "extern4_id",
            "tipe_penjualan_id" => "extern4_id",
            "tipe_penjualan_nama" => "extern4_nama",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "extern3_id" => "extern3_id",
            "extern3_nama" => "extern3_nama",
            "extern4_id" => "extern4_id",
            "extern4_nama" => "extern4_nama",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa tagihan",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "sisa" => "total amount",
                //                "credit_note_dipakai" => "customer credit amount",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain receivable (from list)",
                //                "sisa-nilai_bayar" => "remain receivable (from list)",
            ),
        ),

        //untuk settlement format paymentsource denga extrnBlobValue
        "connectSettlement" => array(
            "modul" => "settlement",
            "target" => "749",
            "targetPayment" => "759",
            "btnLabel" => "Settlement",
        ),

        "settlementFields" => array(
            "dtime" => "tanggal",
//            "fulldate"        => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "invoice",
            "trash_4" => "status",
            "oleh_nama" => "kasir",
            "transaksi_nilai" => "nilai",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    "parent" => "parent ID",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "kelebihanBayar" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Kelebihan Bayar",
                "mdlName" => "MdlStaticMethodLebihBayar",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    //                    0 => "ReComMethodLebihBayar",
                    1 => "ReComMethodLebihBayar",
                    2 => "ReComMethodLebihBayar",
                ),
                "noValidate" => true,
                "noPrefetch" => true,
            ),
//            "uangMuka" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Uang Muka (DP tanpa PPN)",
//                "mdlName" => "MdlCustomerUangMuka",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                    "cabang_id=cabangID",
//                    "extern_label2=.customer",
//                    "sisa>.0",
//                ),
//                "pairedModel" => array(
//                    "mdlName" => "ComRekeningPembantuCustomerDetail",
//                    "mdlMethod" => "fetchBalances",
//                    "mdlFilter" => array(
//                        "extern_id" => "pihakID",
//                        "extern2_id" => ".2010050050",//point
//                        "cabang_id" => "cabangID",
//                        "periode" => ".forever",
//                    ),
//                    "key" => "extern_id",
//                    "rekening" => "2010050",
//                    "fieldID" => "kredit",
//                    "fieldLabel" => "saldoUangMuka",
//                ),
////                "key" => "id",
//                "key" => "extern_id",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer",
//                    "extern_id" => "pihakID",
//                    "extern_label2" => "tipe",
//                    "saldoUangMuka" => "saldo",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
////                "noPrefetch" => true,
//                // "pairMethod" => array(
//                //     "recom" => "ReComUangMuka",
//                //     "calculate" => array(
//                //         "source" => "sisa",
//                //         "target" => "uang_muka_dipakai",
//                //         "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
//                //     ),
//                // ),
//            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
//            "cash_account" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "cash account",
//                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "jenis2=.1",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "account number",
//                    "alias" => "holder alias",
//
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCashAccountJenis",
//                    "calculate" => array(
//                        "source" => "cash_account",
//                        "filter" => array(
//                            "cabang_id=placeID",
//                        ),
//                        "result" => array(
//                            "nilai_setoran_tunai" => "nilai_entry",
//                        ),
//                    ),
//                ),
//            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note(deposit) return",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "label=.piutang dagang",
                    "sisa>.0",
                    "trash=.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer name",
                    "sisa" => "avail credit",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
//                "autoSelect" => false,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
            ),
            "pointKonsumen" => array(
                "elementType" => "dataModel",
                "hiddenSelect" => true,
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "point konsumen",
                "mdlName" => "MdlCustomerAll",
                "mdlFilter" => array(
                    "id=pihakID",
                ),
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuCustomerDetail",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "extern_id" => "pihakID",
                        "extern2_id" => ".2010050030",//point
                        "cabang_id" => "cabangID",
                        "periode" => ".forever",
                    ),
                    "key" => "extern_id",
                    "rekening" => "2010050",
                    "fieldID" => "qty_kredit",
                    "fieldLabel" => "saldo",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "customer name",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "autoSelect" => true,
                "noPrefetch" => false,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//                ),
            ),
            "pointSetting" => array(
                "hiddenSelect" => true,
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "nilai tukar point",
                "mdlName" => "MdlDiskonCustomer",
                "mdlFilter" => array(
                    "tipe=.point",
                    "jenis=.point",
                ),
                "key" => "id",
                "labelSrc" => "nilai",
                "usedFields" => array(
                    "nilai" => "nilai",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "autoSelect" => false,
                "noPrefetch" => false,
//                "pairMethod" => array(
//                    "recom" => "ReComCreditNote",
//                    "calculate" => array(
//                        "source" => "creditAmount", // credit_note
//                        "target" => "credit_note_dipakai",
//                        "pair_source" => "nilai_round",//sunbe sumber yang dibandingkan /// nilai_sisa
//                    ),
//
//                    //                    "customer" => "ReComDiscCustomer",
//                ),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "hiddenBox" => true,
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "labelSrcFields" => array(
                            "folders_nama", "nama", "alias",
                        ),
                        "usedFields" => array(
                            "nama" => "account number",
                            "alias" => "holder alias",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
                         * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
                         */

//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "labelSrcFields" => array(
                            "folders_nama", "nama", "alias",
                        ),
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID",
//                            "biaya_persen" => "biaya(%)",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "labelSrcFields" => array(
                            "folders_nama", "nama", "alias",
                        ),
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),

        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
//            "selectedType_konsumen" => "Tipe konsumen",

        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),
//        "shopingCartAddTax" => array(
//            "fields" => array(
//                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "nilai_sisa",
//            // "srcGateValue"=>"sisa",
//
//        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_biaya" => "amount value",
                "uang_muka_dipakai" => "uang muka",
                "pph23" => "Pph Ps 23 Dibayar Dimuka",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account" => "cash account belum dipilih",
            ),
            array(
                "lebih_bayar" => "kelebihan bayar nol (0)",
                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
            ),
        ),
        "shopingCartPaymentValidator" => array(
            "nilai_entry" => array(
                "label" => "payment belum diisi",
            ),
            "cash_account" => array(
                "label" => "cash account belum dipilih",
            ),
            "creditAmount" => array(
                "label" => "credit note (from return) belum dipilih",
            ),
        ),
        "shopingCartPairedPaymentValidator" => array(
            "nilai_entry" => array(
                "key" => "cash_account",
                "label" => "akun kas belum dipilih.",
            ),
        ),
        "shopingCartPaymentMethodValidator" => array(
            "nilai_entry" => array(
                "label" => "Payment Method wajib dipilih.",
            ),
        ),
        "shopingCartPaymentMethodGateValidator" => array(
//            "paymentMethod" => array(
//                "cashless" => array(
//                    "validate_biaya" => "berikan tic (biaya suport) sebagai validasi bahwa biaya sudah dicek dan sesuai",
//                ),
//                "transfer" => array(
//                    "validate_biaya" => "berikan tic (biaya suport) sebagai validasi bahwa biaya sudah dicek dan sesuai",
//                ),
//            ),
        ),
        "shopingcartAddTicbox" => array(
//            "nilai_biaya" => array(
//                "label" => "berikan tic (biaya suport) sebagai validasi bahwa biaya sudah dicek dan sesuai",
//                "name" => "validate_biaya",
//                "methode" => "_processPihak/selectBiaya/7488",
//            ),
        ),

//        "shopingCartPaymentComparisonValidator" => array(
//            array(
//                "source" => "nilai_dipakai_piutang_dagang", // hutang dagang
//                "target" => "nilai_bayar", // payment source
//                "label"  => "Pastikan penggunaan Kas, Uang Muka, Credit Note (from return) sudah sesuai untuk pelunasan Invoice ini.", //
//            ),
//        ),

        "shoppingCartLoadReference" => true,
        "shoppingCartReferenceFields" => array(
            "fulldate" => "date",
            "nomer" => array(
                "label" => "nomer pengiriman",
                "link_detail" => true,
            ),
            "nomer_top" => "nomer inv",
            "nett1" => "bruto",
            "diskon_kategori_unit" => "nilai diskon unit",
            "nett1_bulat" => "dpp",
            "ppn_out_bulat" => "ppn",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
            "sisa" => "sisa",
//            "extern3_nama" => "marketplace",
            "project_nama" => "project",
            "payment_source_keterangan" => "keterangan",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_id" => "ID",
            "extern_nama" => "customer",
            "qty_nota" => "jumlah<br>invoice",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
//            "diskon" => "discount",
            "sisa" => "sisa",
            // "a_sisa" => "return",
            // "a_saldo" => "saldo",
            "uang_muka_tanpa_ppn" => "Deposit (Uang Muka<br>tanpa PPN)",
            "reference_oleh_nama" => "pic",
        ),
        "shoppingCartReferenceRegistry" => array(
            "key" => "main",
            "fields" => array(
                "nett1" => "bruto",
                "diskon_kategori_unit" => "nilai diskon unit",
                "nett1_bulat" => "dpp",
                "ppn_out_bulat" => "ppn",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_round" => array(
                        "label" => "value of invoice",
                        "defaultValue" => "nilai_round",
                        "maxValue" => "nilai_round",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
//                    "uang_muka_dipakai" => array(
//                        "label" => "Uang Muka",
//                        "defaultValue" => "0",
////                        "maxValue" => "uangMuka__saldoUangMuka",
//                        "maxValue" => "uangMuka__saldoUangMukaround",
//                        "saldo" => "uangMuka__saldoUangMuka",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
                    "credit_amount" => array(
                        "label" => "credit note(deposit) return",
//                        "defaultValue" => "credit_note_dipakai",
                        "defaultValue" => "0",
                        "maxValue" => "creditAmount__sisa",
                        "saldo" => "creditAmount__sisa",
//                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
//                    "point_konsumen_qtt" => array(
//                        "label" => "jumlah point konsumen",
//                        "defaultValue" => "0",
//                        "maxValue" => "pointKonsumen__saldo",
//                        "saldo" => "pointKonsumen__saldo",
////                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "point_konsumen_nilai" => array(
//                        "label" => "nilai point konsumen",
//                        "defaultValue" => "point_konsumen_nilai_dipakai",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),

//                    "ppn_nilai_dibayar" => array(
//                        "label" => "(ppn dibayar bendahara negara)",
//                        "defaultValue" => "ppn_nilai_dibayar",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "pph22_nilai" => array(
//                        "label" => "(pph 22 dibayar dimuka)",
//                        "defaultValue" => "pph22_nilai",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        "hideRow" => true,
//                    ),
//                    "nilai_biaya" => array(
//                        "label" => "(biaya suport/biaya merchant)",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
//                            ",
//
//                        //                        "keyupAction" =>
//                        //                            "
//                        //                                var gt=document.getElementById('harus_bayar').value;
//                        //                                var bayar=this.value;
//                        //                                var add_value=document.getElementById('lebih_bayar');
//                        //                                var nilai=(parseFloat(bayar)-parseFloat(gt));
//                        //
//                        //                                if(parseFloat(nilai)>=0){
//                        //                                    lebih_bayar.value=parseFloat(nilai);
//                        //                                }
//                        //                                else{
//                        //                                    lebih_bayar.value=parseFloat(0);
//                        //                                }
//                        //                            ",
//
//                        "addPoints" => array(1,),
//                    ),

                    "harus_bayar" => array(
                        "label" => "total invoice(netto)",
                        "defaultValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "maxValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "minValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    // "nilai_round" => array(
                    //     "label" => "total invoice(netto)",
                    //     "defaultValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "maxValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "minValue" => "(nilai_round-credit_note_dipakai-creditValue-nilai_biaya)",
                    //     "keyPressAction" => "",
                    //     'disabled' => "disabled",
                    //     "addPoints" => array(1,),
                    // ),

                    "new_sisa_before_entry" => array(
                        "label" => "netto",
                        "defaultValue" => "new_sisa_before_entry",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => false,
                    ),
//                    "pph23" => array(
//                        "label" => "potongan pph23",
//                        "defaultValue" => "0",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        "disabled" => "",
//                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                        "hideRow" => false,
////                        "validate_reference" => array(
////                            "src_reference" => "reference_jenis",
////                            "defaultView" => array(
////                                "584" => "show",
////
////                            ),
////                        ),
////                        "validate_value" => array(
////                            "reference_jenis" => "584",
////                        ),
//                        "defaultView" => "none",
//
//                    ),
                    "nilai_entry" => array(
                        "label" => "uang diterima",
                        "defaultValue" => ".0",
                        //                        "keyupAction" => "
                        //    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                        //                            ",

                        "keyupAction" =>
                            "
                                var gt=removeCommas(document.getElementById('nilai_round').value);
                                var bayar=removeCommas(this.value);
                                var add_value=document.getElementById('lebih_bayar');
                                var nilai=parseFloat(gt)-(parseFloat(bayar));
                                var nilai_biaya=removeCommas(document.getElementById('nilai_biaya').value);
                                var harus_bayar=parseFloat(gt)-(parseFloat(nilai_biaya));
                                if(parseFloat(nilai_biaya)>0){
                                    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                                }
                                
                                if(parseFloat(nilai)>=0){

                                    add_value.value=addCommas(parseFloat(nilai));
                                }
                                else{
                                    add_value.value=parseFloat(0);
                                }
                            ",

                        "addPoints" => array(1,),
                    ),


                    "new_sisa" => array(
                        "label" => "sisa harus dibayar",
                        "defaultValue" => ".0",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                        "option" => array(
                            "source" => "customerDetails__parent",
                            "key" => "customerDetails__parent_cek",
                            "key_value" => "nilai_dijadikan_credit_note",
                            "label" => "Konsumen ini juga sebagai Supplier. Jadikan Sisa Piutang menjadi CreditNote?",
                        ),
                    ),
                    "lebih_bayar" => array(
                        "label" => "lebih bayar",
                        "defaultValue" => ".0",
                        //                        "maxValue" => "new_sisa",
                        //                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        //                        "hideRow" =>true,
                    ),

                    "nilai_dijadikan_credit_note" => array(
                        "label" => "nilai dijadikan credit note",
                        "defaultValue" => "nilai_dijadikan_credit_note",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => true,
                    ),
                ),

            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "dueDateReader" => true,
        "kelebihanBayar" => true,
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
        ),
        "viewDescriptionNote" => true,
        "selectorNotaLocker" => array(
            "enabled" => true,
        ),
        "pembayaranBerurutan" => false,// true: pelunasan nota urut dari tertua, false: pelunasan nota bebas
        "allowedSaldo" => array(
            "enabled" => true,

        ),
        //----
        "pairDepositKonsumen" => array(
            "uang_muka_tanpa_ppn" => array(
                "comName" => "ComRekeningPembantuCustomerDetail",
                "rekening" => "2010050",
                "sub_rekening" => "2010050050",
                "saldo" => "kredit",
                "label" => "Deposit/Uang Muka tanpa PPN",
                "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/",
            ),
        ),
        //----
        "shopingCartPaymentItemsValidator" => array(
            "nilai_bayar" => array(
//                "source" => "nilai_dipakai_hutang_dagang", // hutang dagang
                "target" => "nilai_bayar", // payment source
                "label" => "Jumlah Pembayaran salah karena tercatat 0 untuk pembayaran nota {nota}. Silahkan refresh halaman ini atau hubungi admin.", //
                "label2" => "Jumlah Pembayaran salah karena tercatat 0 untuk pembayaran nota yang dipilih. Silahkan refresh halaman ini atau hubungi admin.", //
            ),
        ),
        "shopingCartPaymentItemsColor" => array(
            "enabled" => true,
            "colorCode" => array(
                0 => array(
                    "label" => "Lunas",
                    "color" => "lightgreen",// lunas, hijau
                ),
                1 => array(
                    "label" => "Dibayar, Belum Lunas",
                    "color" => "yellow",// belum lunas, kuning
                ),
                2 => array(
                    "label" => "Tidak Dibayar",
                    "color" => "red",// tidak terbayar, merah, wajib dihapus
                ),
            ),
        ),
        //----DIBAWAH INI KEPERLUAN MARKETPLACE
        "groupTipePenjualan" => array(
            "enabled" => true,
            "groupKey" => "extern4_id",
            "groupExternKeyID" => "extern3_id",
            "groupExternKeyNama" => "extern3_nama",
        ),
        "shoppingCartReferenceExternFieldsMarketplace" => array(
            "extern3_id" => "ID",
            "extern3_nama" => "Marketplace",
            "qty_nota" => "jumlah<br>invoice",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
            "sisa" => "sisa",
        ),
        "shoppingCartReferenceFieldsMarketplace" => array(
            "fulldate" => "date",
            "nomer" => array(
                "label" => "nomer pengiriman",
                "link_detail" => true,
            ),
            "nomer_top" => "nomer inv",
            "extern_nama" => "konsumen",
            "nett1" => "bruto",
//            "diskon_kategori_unit" => "nilai diskon unit",
            "nett1_bulat" => "dpp",
            "ppn_out_bulat" => "ppn",
            "tagihan" => "tagihan",
            "terbayar" => "dibayar",
            "sisa" => "sisa",
//            "extern3_nama" => "marketplace",
            "payment_source_keterangan" => "keterangan",
        ),
        "receiptElementsDeleter" => array(
            "enabled" => true,
            "tipe_penjualan" => 1,// marketplace
            "element" => array(
                "customerDetails",
                "kelebihanBayar",
                "uangMuka",
                "creditAmount",
                "pointKonsumen",
                "pointSetting",
            ),
        ),
        "receiptElementsReplacer" => array(
            "enabled" => true,
            "tipe_penjualan" => 1,// marketplace
            "element" => array(
                "paymentMethod" => array(
                    "elementType" => "dataModel",
                    "inputType" => "radio",
                    "label" => "payment method",
                    "mdlName" => "MdlPaymentMethod2",
                    "mdlFilter" => array(
                        "non_tunai=.1",
                    ),
                    "key" => "id",
                    "labelSrc" => "name",
                    "usedFields" => array(
                        "name" => "Name",
                        "note" => "Note",
                    ),
                    "editPoints" => array(1),
                ),
            ),
        ),
        "cloneGateItems" => array(
            "nilai_bayar_marketplace" => "nilai_bayar",
        ),
        "shoppingCartFieldsMarketplace" => array(
            1 => array(
                "nama" => "item name",
                "nomer_top" => "nomer inv",
                "extern_nama" => "konsumen",
                "jml" => "qty",
            ),

        ),
        "additionalRowsDeleter" => array(
            "enabled" => true,
            "tipe_penjualan" => 1,// marketplace
            "additionalRows" => array(
                "uang_muka_dipakai",
                "credit_amount",
                "point_konsumen_qtt",
                "point_konsumen_nilai",
                "ppn_nilai_dibayar",
                "pph22_nilai",
                "nilai_dijadikan_credit_note",
                "lebih_bayar",
            ),
        ),
    ),
);


