<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(


    // purchasing project cost
    "3463" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "service project purchasing",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "SERVICE PROJECT PURCHASE PRE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "3463ro",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "SERVICE PROJECT PURCHASE ORDER",
                "actionLabel" => "approve purchasing and dpp ppn",
                "source" => "3463ro",
                "target" => "3463o",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "SERVICE PROJECT RECEIVED NOTE",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "make service receipt note",
                "source" => "3463o",
                "target" => "3463",
                "userGroup" => "c_holding",
                "stateLabel" => "service receipt note made",
                "stateColor" => "#009900",
                "stateCaption" => "Receipt by",
            ),
            // step ke 4, realisais ppn sudah tidak dipakai lagi, karena ppn dijurnal saat pembayaran.

//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "3463",
//                "target" => "3113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
        ),
//        "template" => "template/transaksi_4.html",
        "template" => "template/transaksi_projek.html",
        "selectedPrice" => array(),
        "lockerCheck" => array(),

        "selectorModel" => "MdlJasa",
        "selectorSrcModel" => "MdlJasa",
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih barang / jasa",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProductException/select",
        "editHandlerMethod" => "select",
        // PIHAK I
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "Pilih vendor yang mengejakan projek",
        "pihakProcessor" => "_processPihak/select",
        // PIHAK II
        "pihakModelMain" => "MdlCabang",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "outlet / cabang",
        "pihakMainFilters" => array(
            "id<>.-1",

        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        // PIHAK III
//        "pihakModelExtern" => "MdlCustomerProject",
        "pihakModelExtern" => "MdlCustomer_and_pre",
        "pihakExternCaller" => "_selectorPihak/selectPihakExtern",
        "pihakExternLabel" => "customer project",
        "pihakExternViewedFields" => array(
            "nama",
        ),
        "pihakExternFilters" => array(
            "status=.1",
            "trash=.0",
//            "kategori_id=.2",
        ),
        "pihakExternProcessor" => "_processPihak/selectExtern",
        // PIHAK IV
        "pihakModelProjek" => "MdlProdukProject",
        "pihakProjekCaller" => "_selectorPihak/selectPihakProjek",
        "pihakProjekLabel" => "produk project",
        "pihakProjekViewedFields" => array(
            "kode",
            "nama",
            "transaksi_no_app",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
//            "customer_id=customerProjek",
            "transaksi_id>.0",
            "closing_status=.0",
//            "uang_muka_approved>.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectProjek",
        "pihakProjekResetor" => array(
            "enabled" => true,
//            "keys" => array(
//                "pihakExtern",
//                "pihakProjek",
//                "project",
//            ),
            "label_warning" => "Perhatian!!! Mengganti Project akan menghapus formulir yang sudah dibuat.",
        ),

//        "pihakExternNota" => true,

        //        "pihakMainValueSrc" => array(
        //            "npwp" => "npwp",
        //        ),
//        "pihakValidate" => array(
//            "wajib_pajak" => array(
//                "model" => "MdlWajibPajak",
//                "result" => array(
//                    1 => array(
//                        "kolom" => "npwp",
//                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    2 => array(
//                        "kolom" => "no_ktp",
//                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    3 => array(
////                        "kolom" => "no_ktp",
//                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    "none" => array(
////                        "kolom" => "no_ktp",
//                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                ),
//            ),
//        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "customerName" => "customer",
            "produkProjek__label" => "project",
            "nomer_top" => "PRE PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "SRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            "customerName" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PO number",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "receipt number",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_srn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "SRN number",
                ),
                "description_main_followup" => "INV<br>from vendor",
                "nomer" => "realisasi ppn number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "eFaktur" => "e-faktur",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            2 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(
                "print_label" => "nomer",
            ),
            5 => array(
                "print_label" => "nomer",
            ),
        ),
        "compactHistoryFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pph23MethodPotongan__label" => "status pph 23",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "dpp_ppn_persen" => "dpp_ppn_persen",
            "dpp_pph_persen" => "dpp_pph_persen",
            "pph" => "pph",
            "harga_disc" => "harga_disc",
            "dppPPh" => "dppPPh",
            "pph_nilai" => "pph_nilai",
            "dppPPn" => "dppPPn",
            "ppn" => "ppn",
        ),
        "shopingCartCompareFields" => array(
            1 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

            2 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

        ),
        //-------------------------
        "shoppingCartFields" => array(),
        "shoppingCartNumFields" => array(),
        //-------------------------
        "shoppingCartAdvanceItems" => true,
        "shoppingCartAdvanceItemsKey" => "pph",
        "shoppingCartAdvanceItemsSelector" => "_processSelectProductException/subSelect",
        "shoppingCartAdvanceItemsRemove" => "_processSelectProductException/subRemove",
        "shoppingCartAdvanceItemsAdd" => "_processSelectProductException/subAdd",
        "followupAdvanceItemsSelector" => "_followupLiveEdit/subSelect",
        "followupAdvanceItemsRemove" => "_followupLiveEdit/subRemove",
        "followupAdvanceItemsAdd" => "_followupLiveEdit/subAdd",
        "shoppingCartAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
            ),
        ),
        "shoppingCartAdvanceNumFields" => array(
            1 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
            0 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "shoppingCartAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),

        ),

        "shoppingCartAdvanceSubFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
        ),
        "shoppingCartAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "shoppingCartAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                    "dpp_ppn_persen",
                ),
            ),
            0 => array(
                1 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
            ),
        ),
        "shoppingCartAdvanceFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartAdvanceMaxValidators" => array(
            "key" => array(
                "dpp_pph_persen" => "DPP PPH (%) melebihi batas maksimum 100%",
                "dpp_ppn_persen" => " DPP PPN (%) melebihi batas maksimum 100%",
            ),
            "value" => "100",
        ),
        //-------------------------

        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
            2 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
            3 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
            4 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),

        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "dpp_persen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                "dpp_persen",
//                "discPersen",
//            ),
//            3 => array(
//                "harga",
//                "dpp_persen",
//                "discPersen",
//            ),
//            4 => array(
//                "dpp_persen",
//            ),
        ),

        "shopingCartParamForceEditable" => array(
            //ini untuk force editable fields
            1 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            2 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            3 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            4 => array(
                "allow_params_edit" => "dpp_persen"
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
            //            "nilai_dpp_ppn" =>"DPP PPN"
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "jml*(harga_disc+ppn)",
            //            2 => "jml*(harga_disc+ppn)",
            //            3 => "jml*(harga_disc+ppn)",
            //            4 => "jml*(harga_disc+ppn)",
            1 => "jml*(harga)",
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            4 => "jml*(harga)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
            4 => false,
        ),
        "shopingCartEditableCompare" => array(
            "dpp_persen" => array(
                "npwp_allowed" => array(
                    0 => false,
                    1 => true
                ),

            ),
        ),
        "shopingcartAddDpp" => array(
            1 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            4 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            5 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),


        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    "npwp" => "NPWP",
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "customerProjek" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Customer Project",
                "mdlName" => "MdlCustomer_and_pre",
//                "mdlFilter" => array("id=pihakExternID"),
                "mdlFilter" => array("id=pihakProjekCustomerID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "alamat_1" => "Alamat",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    "npwp" => "npwp",
                ),
                "editPoints" => array(),
                "noValidate" => false,
            ),
            "produkProjek" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Produk Project",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array("id=pihakProjekID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "transaksi_id_app" => "SO ID",
                    "transaksi_no_app" => "SO Number",
                    "cabang_nama" => "cabang",
                    "cabang_id" => "kode cabang",
                ),
                "editPoints" => array(),
                "noValidate" => false,
            ),
            /*
             * branch tetap dibutuhkan karena akan auto distribusi jasa cabang tujuan place2ID
             */
            "branch" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "Cabang pelaksana project",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=produkProjek__cabang_id"),//mengikuti project yag dipilih
//                "mdlFilter" => array("id=.project"),//tidak dipakai
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "noValidate" => false,
                "editPoints" => array(),
            ),

//            "workOrderProjek" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Work Order Project",
//                "defaultValue" => "pihakProjekWorkOrderID",
////                "defaultValue" => "14",
//                "mdlName" => "MdlProjectWorkOrder",
//                "mdlFilter" => array(
//                    "produk_id=produkProjek"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "Nama",
////                    "transaksi_id" => "SO ID",
////                    "transaksi_no" => "SO Number",
//                ),
//                "editPoints" => array(),
//                "noValidate" => false,
//            ),
            "pph23MethodPotongan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23MethodPotongan",
                "key" => "id",
                //                "defaultValue" => "item",
                "disabled_select" => array(
                    "gate" => "valid_pph_key",
                    "value" => array(
                        "0" => "disabled",
                        "1" => "",
                    ),
                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
                ),


                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                    //                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1, 2),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlSupplierAddress",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "",
                    "alamat" => "",
                    "tlp" => "Phone",
                    "alias" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod1",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "defaultValue" => "credit",
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
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
            /*
             * gudang2 tetap dibutuhkan karena akan auto distribusi jasa gudang project cabang
             */
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=place2ID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cash" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            ////                        "defaultValue" => "nett2",
            ////                        "minValue" => "nett2",
            ////                        "maxValue" => "nett2",
            ////                        "defaultValue" => "new_net3",
            //                        "defaultValue" => "nett",
            //                        "minValue" => "nett",
            //                        "maxValue" => "nett",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "c_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            ////                    "discount" => array(
            ////                        "label" => "open discount",
            ////                        "defaultValue" => ".0",
            ////                        "maxValue" => "nett2*50/100",
            ////                        "auth" => array(
            ////                            //                            "groupID" => "c_admin",
            ////                            "groupID" => "o_finance",
            ////                        ),
            ////                        "addPoints" => array(1, 2),
            ////                    ),
            //
            //                ),
            //
            //            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "dppPPh" => array(
                        "label" => "Dpp pph 23",
                        "defaultValue" => "dppPPh",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
//                            ",
//
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph_nilai" => array(
                        "label" => "Pph 23",
                        "defaultValue" => "pph_nilai",
                        "maxValue" => "pph_value",
                        "minValue" => "pph_value",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "dppPPn" => array(
                        "label" => "Dpp ppn",
                        "defaultValue" => "dppPPn",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "ppn" => array(
                        "label" => "Ppn",
                        "defaultValue" => "ppn",
                        "maxValue" => "ppn_value",
                        "minValue" => "ppn_value",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "payment_out" => array(
                        "label" => "Grand total",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "suppliers_nama" => "vendor",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(
            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array("1", "4"),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "dppPPn" => "DPP",
                    "ppn" => "PPN",
                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "dppPPn" => "number",
                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        // berada di midValidate() Transaksi
        "efakturValidator" => array(
            4 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn", // lebih dari 0
                    //                "ppnfactor",
                ),
            ),
        ),
        "detailForceMain" => array(
            2 => array(
                "source" => "pph",
                "target" => "valid_pph_key",
                "elemenReset" => "MdlPph23MethodPotongan",
                "current_element" => "pph23MethodPotongan",
            ),
        ),
        // ======== =========
        "followupMainNoteValidator" => array(
            3 => array(
                "enabled" => true,
                "kolom" => array(
                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
                ),
                "source" => array(
                    "description_main_followup",
                ),
            ),
        ),
        "followupMainNote" => array(
            3 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
                "label" => "INVOICE FROM VENDOR (*)",
            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM VENDOR (*)",
            ),

        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
        "previewCtr" => "Create",
        "connectToStep" => array(
//            3 => "3465",
            3 => "3461",
        ),

        "connectToStepMainBuilder" => array(
            3 => array(
                "referenceInsertID" => "insertID",
                "referenceNomer" => "nomer",
                "referenceJenisTr" => "jenisTr",
                "referenceJenisTrMaster" => "jenisTrMaster",
                "referenceReferenceID" => "referenceID",

            ),
        ),
        "clonerTransaction" => array(
            3 => array(
                "main" => array(
                    "cloner" => true,
                ),
//                 "itemToMaster" => array(
//                     "nama" => "transaksi_jenis2_label",
//                     "produk_kode" => "transaksi_jenis2_kode",
//                     "sub_harga" => "transaksi_jenis2_value",
//                     "sub_ppn" => "transaksi_jenis2_value_ppn",
//                     //                    "sub_harga_nppn"=>"transaksi_jenis2_value_nppn",
//                     //                    "transaksi_jenis" =>"paket",
//                 ),
//                 "staticItemToMaster" => array(
//                     "transaksi_jenis2" => "paket",
//                 ),
//                 "details" => array(
//                     "harga" => "harga",
//                     "jual_nppn" => "jual_nppn",
//                     "hpp" => "hpp",
//                     "disc" => "disc",
//                     "ppn" => "ppn",
//
//                     "harga1" => "harga1",
//                     "harga_nett1" => "harga_nett1",
//                     "harga2" => "harga2",
//                     "harga_nett2" => "harga_nett2",
//                 ),
                "resetGate" => array(
                    "items2_sum",
                    "receiptSumFields2",
                    "receiptDetailFields2",
                ),
            ),

        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3463roe",
                "label" => "EDIT SERVICE PROJECT PURCHASE PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3463rorj",
                "label" => "REJECT SERVICE PROJECT PURCHASE PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3463orj",
                "label" => "REJECT SERVICE PROJECT PURCHASE ORDER",
            ),
        ),
        //----
        "tabRequestCode" => array(
            "masterCode" => "3463",
            "stateCode" => "3463",
            "stepNumber" => "1",
            "allowMultiSelect" => true,
        ),
        "tabHistoryFields" => array(
            "produk_id" => array(
                "label" => "All",
                "allowFollowup" => true,
            ),
//            "suppliers_id" => array(
//                "label" => "By Vendor",
//                "allowFollowup" => true,
//            ),
        ),
        "tabFieldsItems" => array(
//            "suppliers_id" => array(
//                "kode" => "kode",
//                "nama" => "Produk Nama",
//                "satuan" => "Satuan",
//                "omset" => "Omset",
//                "average" => "Average harian",
//                "stok" => "Stok",
//                "buffer" => "buffer(qty)",
//                // "moq" => "Moq",
//                "ideal_stok" => "proyeksi stok(qty)",
//                "new_order" => "Rekomendasi order",
//            ),
//            "produk_id" => array(
//                //                "select" => "All",
//                "kode" => "kode",
//                "nama" => "Produk Nama",
//                "satuan" => "Satuan",
//                "omset" => "Omset",
//                "average" => "Average harian",
//                "stok" => "Stok",
//                "buffer" => "buffer<br>(qty)",
//                // "moq" => "Moq",
//                "ideal_stok" => "proyeksi stok(qty)",
//                "new_order" => "Rekomendasi order",
//                // ""=>"",
//                //                "purchased" => "On Purchase",
//                //                "valid_qty" => "Outstanding",

//            ),
            "produk_id" => array(
                "select" => "All",
                //                "dtime" => "tanggal",
                //                "gudang2_nama" => "gudang",
                //                "produk_nama" => "Bahan Baku",
                //                "nomer_top" => "Transaksi No",
                //                "produk_ord_jml" => "Jumlah request",
                //                //                "purchased" => "On Purchase",
                //                //                "valid_qty" => "Outstanding",
//                "id"               => "bID",
                "nama" => "work order",
                "nama_jasa" => "nama jasa",
                "satuan" => "satuan",
                "sales_order" => "kebutuhan",
//                "stock"            => "stok tersedia",
//                "stok_buffer"      => "buffer",
                "purchase_request" => "request",
                "purchase_order" => "outstanding PO",
                // "outstanding"    => "outstanding",
                "new_order" => "kekurangan",
            ),
            "produk_id_attr" => array(
                "select" => array(),
                "id" => array(),
                "nama" => array(),
                "satuan" => array(),
                "stock" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                ),
                "sales_order" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "link" => "bi/Penjualan/supplies",
                    "link_head" => "nama",
                ),
                "stok_buffer" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                ),
                "purchase_request" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                ),
                "purchase_order" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "link" => "bi/Pembelian/Supplies",
                    "link_head" => "nama",
                ),
                "outstanding" => array(),
                "new_order" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "attr" => "class='text-bold bg-danger'",
                ),
            ),
        ),
        "validateRelasiUangMuka" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
    ),
    "3462" => array(),

);