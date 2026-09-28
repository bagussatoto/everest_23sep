<?php

$config["coTransaksiLayout"] = array(

    // config po jasa project
    "3461" => array(
        "receiptTemplate" => array(
            1 => "template/3461r.html",
            2 => "template/3461.html",
//            3 => "template/3463.html",
//            4 => "template/3463.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "referenceNomer" => "Reference No",
                "dtime" => "Date",
                "cabang2_nama" => "Branch",
                "customerName" => "Customer",
                "produkProjek__label" => "Project",
                "produkProjek__transaksi_no_app" => "SO Project",

            ),
            2 => array(
                "nomer" => "No",
                "referenceNomer" => "Reference No",
                "dtime" => "Date",
                "cabang2_nama" => "Branch",
                "customerName" => "Customer",
                "produkProjek__label" => "Project",
                "produkProjek__transaksi_no_app" => "SO Project",

            ),
//            3 => array(
//                "nomer" => "Invoice No.",
//                "nomer_po" => array(
//                    "source" => "ids_his",
//                    "target" => "nomer",
//                    "label" => "PO No.",
//                    "step" => "2",
//                ),
//                "nomer_top" => "PO Pre No.",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
//            ),
//            4 => array(
//                "nomer" => "Invoice No.",
//                "nomer_po" => array(
//                    "source" => "ids_his",
//                    "target" => "nomer",
//                    "label" => "PO No.",
//                    "step" => "2",
//                ),
//                "nomer_top" => "PO Pre No.",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                // "dueDate_value" => "Due Date",
//                "dateFaktur" => "faktur date",
//                "eFaktur" => "e-faktur ",
//            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "satuan" => "UOM",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                //                "ppnPersen" => "VAT(%)",// ppnFactor
                //                "ppn" => "VAT(Rp)",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                //                "ppnPersen" => "VAT(%)",// ppnFactor
                //                "ppn" => "VAT(Rp)",
            ),
//            3 => array(
//                "harga" => "Unit Price",
//                //                "discPersen" => "DISC(%)",
//                //                "disc" => "DISC(Rp)",
//                //                "ppnPersen" => "VAT(%)",// ppnFactor
//                //                "ppn" => "VAT(Rp)",
//            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
//            3 => array(
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            4 => array(
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                //                "disc" => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
//                //                "ppn_value" => "PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                //                "disc" => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
//                //                "ppn_value" => "PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
//            3 => array(
//                //                "harga" => "Total Amount",
//                //                "disc" => "DISC",
//                //                "nilai_dpp_ppn" => "DPP PPN",
//                ////                "ppn_value" => "PPN",
//                //                "ppn" => "PPN",
//                //                "payment_out" => "Grand Total",
//            ),
//            4 => array(
//                //                "harga" => "Total Amount",
//                //                "disc" => "DISC",
//                //                "nilai_dpp_ppn" => "DPP PPN",
//                ////                "ppn_value" => "PPN",
//                //                "ppn" => "PPN",
//                //                "payment_out" => "Grand Total",
//            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga"         => "Total Amount",
                //                "disc"          => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn"           => "PPN",
                //                "payment_out"   => "Grand Total",
            ),
            2 => array(
                //                "harga"         => "Total Amount",
                //                "disc"          => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn"           => "PPN",
                //                "payment_out"   => "Grand Total",
            ),
            3 => array(
                //                "harga" => "Total Amount",
                //                "disc" => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
            4 => array(
                //                "harga" => "Total Amount",
                //                "disc" => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
        ),
        "receiptSumFieldsZeroAllowed" => array(
            1 => array(
                "nilai_dpp_ppn" => "DPP PPN",
                "ppn_value" => "PPN",
            ),
            2 => array(
                "nilai_dpp_ppn" => "DPP PPN",
                "ppn_value" => "PPN",
            ),
            3 => array(
                "nilai_dpp_ppn" => "DPP PPN",
                "ppn_value" => "PPN",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(//                "sub_harga" => "Total Price",//tak matiin grn ndak ada total price
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "staticFooter" => array(
            1 => "SAN/F/PUR002/R00",
            2 => "SAN/F/PUR002/R00",
        ),
        //------------------------
        "receiptAdvanceItems" => true,
        "receiptAdvanceItemsKey" => "pph",
        "receiptAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "produk_nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "produk_nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
//                3 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "produk_nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "produk_nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
//                3 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
        ),
        "receiptAdvanceNumFields" => array(
            1 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
            ),
            0 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
//                3 => "jml*(harga_disc+ppn)",
//                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
//                3 => "jml*(harga_disc+ppn)",
//                4 => "jml*(harga_disc+ppn)",
            ),

        ),
        "receiptAdvanceSubFields" => array(
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
//                3 => array(
//                    "nama" => "Description",
////                    "jml" => "Qty",
////                    "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                    "jml" => "Qty",
////                    "satuan" => "Satuan",
//                ),
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
//                3 => array(
//                    "nama" => "Description",
////                    "jml" => "Qty",
////                    "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                    "jml" => "Qty",
////                    "satuan" => "Satuan",
//                ),
            ),
        ),
        "receiptAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
                ),
//                3 => array(
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
//                ),
//                4 => array(
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
//                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
//                3 => array(
//                    "jml" => "Qty",
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
//                    "jml" => "Qty",
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
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
        "receiptAdvanceFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),

    ),
    "3465" => array(
        "receiptTemplate" => array(
            1 => "template/3465r.html",
            2 => "template/3465.html",
//            3 => "template/3463.html",
//            4 => "template/3463.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "referenceNomer" => "Reference No",
                "dtime" => "Date",
                "cabang2_nama" => "Branch",
                "customerName" => "Customer",
                "produkProjek__label" => "Project",
                "produkProjek__transaksi_no_app" => "SO Project",

            ),
            2 => array(
                "nomer" => "No",
                "referenceNomer" => "Reference No",
                "dtime" => "Date",
                "cabang2_nama" => "Branch",
                "customerName" => "Customer",
                "produkProjek__label" => "Project",
                "produkProjek__transaksi_no_app" => "SO Project",

            ),
//            3 => array(
//                "nomer" => "Invoice No.",
//                "nomer_po" => array(
//                    "source" => "ids_his",
//                    "target" => "nomer",
//                    "label" => "PO No.",
//                    "step" => "2",
//                ),
//                "nomer_top" => "PO Pre No.",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
//            ),
//            4 => array(
//                "nomer" => "Invoice No.",
//                "nomer_po" => array(
//                    "source" => "ids_his",
//                    "target" => "nomer",
//                    "label" => "PO No.",
//                    "step" => "2",
//                ),
//                "nomer_top" => "PO Pre No.",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                // "dueDate_value" => "Due Date",
//                "dateFaktur" => "faktur date",
//                "eFaktur" => "e-faktur ",
//            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "satuan" => "UOM",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                //                "ppnPersen" => "VAT(%)",// ppnFactor
                //                "ppn" => "VAT(Rp)",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                //                "ppnPersen" => "VAT(%)",// ppnFactor
                //                "ppn" => "VAT(Rp)",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                //                "ppnPersen" => "VAT(%)",// ppnFactor
                //                "ppn" => "VAT(Rp)",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                //                "disc" => "DISC",
//                "nilai_dpp_pph" => "DPP PPH23",
//                "pph_nilai" => "PPH23",
                //                "nilai_dpp_ppn" => "DPP PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                //                "disc" => "DISC",
//                "nilai_dpp_pph" => "DPP PPH23",
//                "pph_nilai" => "PPH23",
                //                "nilai_dpp_ppn" => "DPP PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
//            3 => array(
//                //                "harga" => "Total Amount",
//                //                "disc" => "DISC",
//                //                "nilai_dpp_ppn" => "DPP PPN",
//                ////                "ppn_value" => "PPN",
//                //                "ppn" => "PPN",
//                //                "payment_out" => "Grand Total",
//            ),
//            4 => array(
//                //                "harga" => "Total Amount",
//                //                "disc" => "DISC",
//                //                "nilai_dpp_ppn" => "DPP PPN",
//                ////                "ppn_value" => "PPN",
//                //                "ppn" => "PPN",
//                //                "payment_out" => "Grand Total",
//            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga"         => "Total Amount",
                //                "disc"          => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn"           => "PPN",
                //                "payment_out"   => "Grand Total",
            ),
            2 => array(
                //                "harga"         => "Total Amount",
                //                "disc"          => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn"           => "PPN",
                //                "payment_out"   => "Grand Total",
            ),
            3 => array(
                //                "harga" => "Total Amount",
                //                "disc" => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
            4 => array(
                //                "harga" => "Total Amount",
                //                "disc" => "DISC",
                //                "nilai_dpp_ppn" => "DPP PPN",
                ////                "ppn_value" => "PPN",
                //                "ppn" => "PPN",
                //                "payment_out" => "Grand Total",
            ),
        ),
        "receiptSumFieldsZeroAllowed" => array(
            1 => array(
                "nilai_dpp_ppn" => "DPP PPN",
                "ppn_value" => "PPN",
            ),
            2 => array(
                "nilai_dpp_ppn" => "DPP PPN",
                "ppn_value" => "PPN",
            ),
            3 => array(
                "nilai_dpp_ppn" => "DPP PPN",
                "ppn_value" => "PPN",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(//                "sub_harga" => "Total Price",//tak matiin grn ndak ada total price
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "staticFooter" => array(
            1 => "SAN/F/PUR002/R00",
            2 => "SAN/F/PUR002/R00",
        ),
        //------------------------
        "receiptAdvanceItems" => true,
        "receiptAdvanceItemsKey" => "pph",
        "receiptAdvanceFields" => array(
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
//                3 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
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
//                3 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "produk_nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
        ),
        "receiptAdvanceNumFields" => array(
            1 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
            ),
            0 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
//                3 => "jml*(harga_disc+ppn)",
//                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
//                3 => "jml*(harga_disc+ppn)",
//                4 => "jml*(harga_disc+ppn)",
            ),

        ),
        "receiptAdvanceSubFields" => array(
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
        "receiptAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
                ),
//                3 => array(
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
//                ),
//                4 => array(
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_pph_persen" => "DPP PPH 23(%)",
//                    "dppPPh" => "dpp pph 23",
//                    "pph" => "PPH(Rp)",
//                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
                ),
//                3 => array(
//                    "jml" => "Qty",
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
//                    "jml" => "Qty",
//                    "harga" => "Unit Price",
////                    "discPersen" => "DISC(%)",
////                    "disc" => "DISC(Rp)",
////                    "harga_disc" => "Netto",
////
//                    "dpp_ppn_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
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
        "receiptAdvanceFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),

    ),

);