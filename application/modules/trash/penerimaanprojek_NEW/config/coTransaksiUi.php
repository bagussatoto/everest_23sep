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
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
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
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
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
            "qty_nota" => "jumlah<br>invoice",
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
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di cabang {cabang_nama}",
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
                    "nama" => "name",
                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
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
                    "nama" => "name",
                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
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
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
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
    //penerimaan project
    "7499_ORI" => array(
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
            "jenis=.588",
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
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
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
            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Uang Muka (DP tanpa PPN)",
                "mdlName" => "MdlCustomerUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "customer",
                    "extern_id" => "pihakID",
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
//            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
//            "refValue" => "returned",
            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
//            "diskon" => "discount",
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
    "7488__batal" => array(
        "icon" => "fa fa-money",
        "label" => "A/R Receipt Project (Retensi)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "A/R Receipt Project (Retensi)",
                "actionLabel" => "process receivement",
                "source" => "",
                "target" => "7488",
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
//                    "sisa>.0",
//                ),
//                "key" => "id",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer",
//                    "extern_id" => "pihakID",
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
//            array(
//                "lebih_bayar" => "kelebihan bayar nol (0)",
//                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
//            ),
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
//            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
//            "refValue" => "returned",
            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
//            "diskon" => "discount",
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


    "7499" => array(
        "icon" => "fa fa-money",
        "label" => "Termin",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Termin",
                "actionLabel" => "process",
                "source" => "",
                "target" => "7499",
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
            "jenis=.588st",
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
            "projectName" => "project name",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "pic",
//            "cash_account__label" => "account",
//            "sisa" => "tagihan",
//            "nilai_biaya" => "biaya usaha",
//            "ppn_nilai_dibayar" => "PPN DIBAYAR BENDAHARA NEGARA",
//            "pph22_nilai" => "PPH 22 DIBAYAR DIMUKA",
//            "credit_note_dipakai" => "credit note<br>(from return)",
//            "uang_muka_dipakai" => "uang muka",
            "nilai_entry" => "nilai",
            "ppn" => "ppn",
            "new_net3" => "grand total",
            "description" => "catatan",
        ),
        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "projectName" => "project name",
            "nomer" => "receipt number",
            "oleh_nama" => "pic",
            "nilai_entry" => "nilai",
            "ppn" => "ppn",
            "termin_nppn" => "grand total",
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
        "componentsAss" => array(
            "model" => "MdlTransaksi",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Sales Ref.",
                "project_nama" => "project label",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "project_nama" => "project name",
                "nama" => "item name",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFields3" => array(
            1 => array(
                "project_nama" => "project name",
                "nama" => "item name",
                "jml" => "qty",
            ),
        ),
//        "shoppingCartFields4" => array(
//            1 => array(
//                "project_nama" => "project name",
//                "nama" => "item name",
//                "jml" => "qty",
//            ),
//        ),
        "shoppingCartFields5" => array(
            1 => array(
                "produk_nama" => "nama WO",
//                "no_spk" => "No-SPK",
                "nilai_sub_fase" => "nilai/WO<br>(Excl.PPN)",
                "persen_sub" => "Bobot/WO<br>(%)",
                "bahan_baku" => "bahan evaluasi realisasi",
//                "progress_percent" => "progress (%)",
//                "debet" => "invoiced",
//                "sisa" => "sisa",
                "checklistTarget" => "pilihan",
            ),
        ),
        "detailsPrintFields5" => array(
            1 => array(
                "produk_nama" => "pekerjaan",
//                "no_spk" => "No-SPK",
//                "nilai_sub_fase" => "harga (Rp)",
                "bahan_baku" => "bahan baku",
//                "progress_percent" => "progress (%)",
//                "persen_sub" => "Bobot<br>WO (%)",
//                "checklistTarget" => "pilihan",
            ),
        ),

        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    //                    "nomer" => "INV",
                    "dpp_final" => "Dasar Pengenaan pajak",
                    "ppn_final" => "Ppn(11%)",
                    "nilai_entry" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                    "skip_faktur" => "belum ada faktur",
                ),
                "editableFields" => array(
                    //                    "dpp_ppn" => "number",
                    //                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "skip_faktur" => "checkbox",
                ),
                "editProcess" => "_processPihak/addTaxData"
            ),
        ),

        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "project_nama" => "project_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "terbayar" => "sudah dibayar",
                "sisa" => "Sisa Nilai Termin",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "sisa" => "project remain",
            ),
        ),
        "shoppingCartNumFields3" => array(
            1 => array(
                "sisa" => "project remain",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                // "sisa" => "total amount",
                // "credit_note_dipakai" => "customer credit amount",
                // "nilai_entry" => "paid using cash account",
                // "nilai_bayar" => "total amount of payment",
                // "new_sisa" => "remain receivable (from list)",
                // "sisa-nilai_bayar" => "remain receivable (from list)",
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
//                    "sisa>.0",
//                ),
//                "key" => "id",
//                "labelSrc" => "sisa",
//                "usedFields" => array(
//                    "extern_nama" => "customer",
//                    "extern_id" => "pihakID",
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
        ),
        //        "pairRegistries"                        => array(
        //            "main",
        //        ),

        "viewDescriptionNote" => true,

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
        "shopingCartAddTax" => array(
//            "fields" => array(
//                "reguler" => "reguler", "bendahara_negara" => "bendahara negara"
//            ),
//            "inputType" => "checkbox",
//            "selector" => "_selectorPihak/selectTax",
//            "srcGateValue" => "nilai_sisa",
        ),
        "shoppingCartUnionValidators" => array(
            array(
//                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
//                "nilai_biaya" => "amount value",
//                "uang_muka_dipakai" => "uang muka",
            ),
        ),
        "shopingCartUnionComparison" => array(
//            array(
//                "nilai_entry" => "payment belum diisi",
//                "cash_account" => "cash account belum dipilih",
//            ),
//            array(
//                "lebih_bayar" => "kelebihan bayar nol (0)",
//                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
//            ),
        ),
        "shopingCartPaymentValidator" => array(
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
        ),
        "shopingCartPairedPaymentValidator" => array(
//            "nilai_entry" => array(
//                "key" => "cash_account",
//                "label" => "cash account belum dipilih.",
//            ),
        ),

        //        "shopingCartPaymentComparisonValidator" => array(
        //            array(
        //                "source" => "nilai_dipakai_piutang_dagang", // hutang dagang
        //                "target" => "nilai_bayar", // payment source
        //                "label"  => "Pastikan penggunaan Kas, Uang Muka, Credit Note (from return) sudah sesuai untuk pelunasan Invoice ini.", //
        //            ),
        //        ),

        "shoppingCartReferenceFields" => array(
            "nomer_top" => "sales number",
            "nomer" => "sales approval number",
            "project_nama" => "project label",
            "extern_nilai2" => "Nilai Project",
            "tagihan" => "Nilai Termin",
            "uang_muka" => "Nilai Uang Muka",
            "garansi" => "Nilai Garansi",
            "termin" => "Info Termin",
            "terbayar" => "sudah terbayar",
            "sisa" => "belum bayar",
        ),
        "shoppingCartReferenceExternFields" => array(
            //"extern_id" => "ID",
            "extern_nama" => "customer",
            "tagihan" => "project amount",
            "terbayar" => "sudah dibayar",
//            "diskon" => "discount",
            "sisa" => "remain",
        ),
        "shoppingCartReferenceExternDuedateFields" => false,
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
                        "label" => "termin",
                        "defaultValue" => ".0",
//                        "keyupAction" => "if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){
//                                                this.value=addCommas(document.getElementById('harus_bayar').value);
//                                          }",
                        "keyupAction" => "",
                        "addPoints" => array(1),
                    ),
                    "ppn" => array(
                        "label" => "ppn",
                        "defaultValue" => ".0",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
//                        "hideRow" => true,
                    ),
                    "new_net3" => array(
                        "label" => "total akan di tagihkan",
                        "defaultValue" => ".0",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
//                        "hideRow" => true,
                    ),
                    "new_sisa" => array(
                        "label" => "sisa belum terbayar",
                        "defaultValue" => ".0",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                        "hideRow" => true,
                    ),
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
        "dueDateReader" => false,
        "kelebihanBayar" => false,
        "previewCtr" => "Create",
        "connectTo" => "110",
        "connectoValidate" => array(
            2 => "ppn",
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
    ),
);

