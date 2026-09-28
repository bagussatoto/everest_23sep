<?php

$config["coTransaksiUi"] = array(
     "7761" => array(
        "icon" => "fa fa-money",
        "label" => "Penerimaan uang muka konsumen",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "settlement uang muka konsumen",
                "actionLabel" => "terima",
                "source" => "",
                "target" => "7761",
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
            //            "jenis_label"    => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "konsumen",
            "nomer" => "nomer",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
            "nilai_entry" => "uang diterima",
//            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
        ),
        "historyFields" => array(
            1 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "receipt number",
                "details" => "detail",
                "oleh_nama" => "person",
                "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
                "nilai_entry" => "CASH RECEIVED",
//            "new_sisa"            => "sisa tagihan",
//            "kelebihanBayar__label" => "kelebihan bayar",
//            "deposit_konsumen" => "deposit konsumen",
//            "pendapatan_lain_lain" => "pendapatan lain-lain",
            ),

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

         //untuk settlement format paymentsource denga extrnBlobValue
         "connectSettlement"       => array(
             "modul" => "settlement",
             "target" => "7761",
             "targetPayment" => "7760",
             "btnLabel" => "Settlement",
         ),
         "settlementFields"        => array(
             "dtime" => "tanggal",
//            "fulldate"        => "tanggal",
             "customers_nama" => "konsumen",
             "nomer" => "invoice",
             "trash_4" => "status",
             "oleh_nama"       => "kasir",
             "transaksi_nilai" => "nilai",
         ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",

//            "volume" => "volume",
//            "berat" => "berat",
//            "lebar" => "lebar",
//            "tinggi" => "tinggi",
//            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                "nett1" => "price(net)",
            ),
        ),
        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//                "produk_ord_jml",
//                "disc_percent",
//                "disc",
//                "nett1",
//            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total amount bruto",
//                "disc" => "disc",
//                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
                "ongkir_ui" => "shipping service",
//                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total amount netto*",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "grand total",
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
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
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
//            ),
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
//                "key" => "id",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer",
//                    "extern_id" => "pihakID",
//                    "extern_label2" => "tipe",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "noPrefetch" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComUangMuka",
//                    "calculate" => array(
//                        "source" => "sisa",
//                        "target" => "uang_muka_dipakai",
//                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
//                    ),
//                ),
//            ),
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
                "defaultValue" => "cash_account",
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
//            "creditAmount" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "credit amount (from return)",
//                "mdlName" => "MdlPaymentAntiSource",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                    "cabang_id=cabangID",
//                    //                    "target_jenis=jenisTr",
//                    "label=.piutang dagang",
//                    "sisa>.0",
//                    "trash=.0",
//                ),
//                "key" => "sisa",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer name",
//                    //                    "transaksi_id" => "return ID",
//                    //                    "nomer" => "return number",
//                    "sisa" => "avail credit",
//                    //                    "jenis" => "jenis",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
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
//            ),
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
//            "selectedType_konsumen" => "Tipe konsumen",
        ),
        "shoppingCartRowNumValidators" => array(//            "nilai_entry" => "amount of payment",
        ),

        "shoppingCartReferenceFields" => array(
            "fulldate" => "tanggal",
            "nomer_top" => "nomer inv",
            "nomer" => "nomer approval",
//            "refNum" => "return ref.",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
//            "qty_nota" => "jumlah<br>invoice",
//            "tagihan" => "due amount",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "nilai",
            // "a_sisa" => "return",
            // "a_saldo" => "saldo",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
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
//                    "new_sisa_before_entry" => array(
//                        "label" => "netto",
//                        "defaultValue" => "new_sisa_before_entry",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                        'hideRow' => false,
//                    ),
                    "nilai_entry" => array(
                        "label" => "cash received",
                        "defaultValue" => "nilai_cash",
//                        "keyupAction" =>
//                            "
//                                var gt=removeCommas(document.getElementById('grand_pembulatan').value);
//                                var bayar=removeCommas(this.value);
//                                var add_value=document.getElementById('lebih_bayar');
//                                var nilai=parseFloat(gt)-(parseFloat(bayar));
//                                var nilai_biaya=removeCommas(document.getElementById('nilai_biaya').value);
//                                var harus_bayar=parseFloat(gt)-(parseFloat(nilai_biaya));
//                                if(parseFloat(nilai)>=0){
//
//                                    add_value.value=addCommas(parseFloat(nilai));
//                                }
//                                else{
//                                    add_value.value=parseFloat(0);
//                                }
//                            ",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('grand_pembulatan').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('grand_pembulatan').value);}

                            ",
                        "addPoints" => array(1,),
                    ),
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
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
        //----
        "shoppingCartLoadReference" => true,
        // ----------------------------------------
        "shoppingCartPerTransaksi" => array(
            "dtime" => array(
                "label" => "tanggal",
                "format" => "formatField",
            ),
            "oleh_nama" => array(
                "label" => "pic",
                "format" => "formatField",
                "link" => "settlement/Settlement/selectPaymentSrc/759/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
            ),
            "extern_nama" => array(
                "label" => "konsumen",
                "format" => "formatField",
            ),
            "nomer_top" => array(
                "label" => "nomer inv",
                "format" => "formatField",
            ),
            "nomer" => array(
                "label" => "nomer approval",
                "format" => "formatField",
            ),
            "sisa" => array(
                "label" => "nilai",
                "format" => "formatField",
            ),
//            "nota_settled" => array(
//                "label" => "urut invoice",
//                "format" => "formatField",
//            ),
//            "jumlah_nota" => array(
//                "label" => "jumlah invoice",
//                "format" => "formatField",
//            ),
//            "extern_nilai5" => array(
//                "label" => "point",
//                "format" => "formatField",
//            ),
//            "extern_nilai6" => array(
//                "label" => "Bruto",
//                "format" => "formatField",
//            ),
//            "diskon" => array(
//                "label" => "Diskon",
//                "format" => "formatField",
//            ),
//            "extern_nilai2" => array(
//                "label" => "Netto",
//                "format" => "formatField",
//            ),
//            "extern_nilai3" => array(
//                "label" => "kelebihan setoran",
//                "format" => "formatField",
//            ),
//            "extern_nilai4" => array(
//                "label" => "kekurangan setoran",
//                "format" => "formatField",
//            ),
            "terima_setoran" => array(
                "label" => "terima",
                "format" => "formatField",
                "link" => "kas/Transaksi/selectPaymentSrc/4464/",
                "linkType" => "modal",
                "modalHeader" => "oleh_nama",
                "linkSeg_4" => "extern_id",
                "tipe" => "button",
            ),

//            "extern_nama" => array(
//                "label" => "konsumen",
//                "link" => "Transaksi/selectPaymentSrc/749/",
//                "linkType" => "modal",
//                "modalHeader" => "extern_nama",
//                "linkSeg_4" => "extern_id",
//                // "modalHeader" => "id",
//            ),

        ),
        "shoppingCartPerTransaksiBtn" => array(
            "btn_satu" => array(
                "label" => "per TRANSAKSI",
                "show_default" => "prog_satu",
            ),
            "btn_dua" => array(
                "label" => "per KONSUMEN",
                // "show_default" => true,
            ),
        ),
        "settlementBtn" => false,
    ),
);