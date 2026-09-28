<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(
    //pendapatan lain lain
    "742" => array(
        "icon" => "fa fa-money",
        "label" => "pendapatan lain-lain",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pendapatan lain-lain",
                "actionLabel" => "save",
                "source" => "",
                "target" => "742",
                "userGroup" => "c_finance",
                "stateLabel" => "save",
                "stateColor" => "#dd3300",
                "stateCaption" => "entry by",
            ),

        ),
        "template" => "template/transaksi_nopihak.html",
        //        "template" => "application/template/transaksi.html",
        "selectorModel" => "MdlPendapatanLainLain",
        "selectorSrcModel" => "MdlPendapatanLainLain",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama pendapatan",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectPendapatan/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer" => "receipt number",

            "harga" => "pendapatan lain-lain",
            "pphps4_2" => "pph ps4(2)",
            "kas_nilai" => "kas diterima",
            "oleh_nama" => "person",
            "cash_account__label" => "cash account",
            "description" => "catatan",

        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
            "harga_bulat" => "harga_bulat",
            "harga_desimal" => "harga_desimal",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartSumFields" => array(
            1 => array(

                "harga" => "Total",
                "pphps4_2" => "<r class=\"meta\">Pph ps 4(2) 20%</r>",
                // "pphps4_2_bulat" => "<r class=\"meta\">Pph ps 4(2) 20%</r>",
                "kas_nilai" => "<r class=\"meta\">kas</r> Diterima",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "receiptElements" => array(
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
//                "showNull" => true,
//                "nullSrc" => "balance",
//                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
//                "pairedModel" => array(
//                    "mdlName" => "ComLockerValue",
//                    "mdlMethod" => "fetchBalances",
//                    "mdlFilter" => array(
//                        "cabang_id" => "placeID",
//                        "state" => ".active",
//                    ),
//                    "key" => "produk_id",
//                    //                    "rekening" => "kas",// kolom jenis di locker
//                    "rekening" => array(
//                        "kas",
//                    ),
//                    "fieldID" => "nilai",
//                    "fieldLabel" => "saldo",
//                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
//            "dummyElement" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "auto-validation",
//                "mdlName" => "MdlDummyElement",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1),
//            ),
        ),
        "relativeElements" => array(),
        "additionalRows" => array(
//            "dummyElement" => array(
//                "yes" => array(
//                    "pphps4_2" => array(
//                        "label" => "pph ps 4(2) 20%",
//                        "defaultValue" => "pphps4_2",
//                        "keyupAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                    "kas_nilai" => array(
//                        "label" => "kas diterima",
//                        "defaultValue" => "kas_nilai",
//                        "maxValue" => "new_sisa",
//                        "minValue" => "new_sisa",
//                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
//                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
//                        //                        "hideRow" => "true",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                ),
//            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeOptions" => array(),
        "allowedMainEdit" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "742e",
                "label" => "EDIT pendapatan lain-lain",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "742rj",
                "label" => "REJECT pendapatan lain-lain",
            ),
        ),
    ),

    //--------- ke atas sudah modul ---------------------------
);