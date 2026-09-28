<?php

$config["coTransaksiLayout"] = array(
    //pembiayaan supplies
    "7762" => array(
        "receiptTemplate" => array(
            1 => "template/762r.html",
            2 => "template/762.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            //            "suppliers_nama" => "Supplier",
            //            "tlp_1" => "phone",
            //            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            //            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            2 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "subtotal",
            ),
            2 => array(
                "sub_harga" => "subtotal",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Download Data mentah",
                    "target" => "ExcelWriter/exp/",
                ),
            )
        ),
        "customHistoriExcel" => array(
            "nomer" => array(
                "label" => "nomer nota",
                "type" => "string",
            ),
            "dtime" => array(
                "label" => "tanggal",
                "type" => "string",
            ),
            "pihakName" => array(
                "label" => "pembebanan",
                "type" => "string",
            ),
            "oleh_nama" => array(
                "label" => "PIC",
                "type" => "string",
            ),
            "produk_kode" => array(
                "label" => "sku",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "produk",
                "type" => "string",
            ),
            "jml" => array(
                "label" => "Qty",
                "type" => "integer",
            ),
            "sub_harga" => array(
                "label" => "amount per transaksi",
                "type" => "integer",
            ),
            "category_expense__nama" => array(
                "label" => "kategori biaya",
                "type" => "text",
            ),
            "pihak3Name" => array(
                "label" => "biaya",
                "type" => "text",
            ),
            "description" => array(
                "label" => "catatan",
                "type" => "text",
            ),
        )
    ),
    //otorisasi biaya produksi
    "2676" => array(
        "receiptTemplate" => array(
            1 => "template/676r.html",
            2 => "template/676.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Transaksi/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    //  config request expense/biaya usaha
    "677" => array(
        "receiptTemplate" => array(
            1 => "template/677r.html",
            2 => "template/677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    //otorisasi biaya usaha
    "2677" => array(
        "receiptTemplate" => array(
            1 => "template/677r.html",
            2 => "template/677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenisTrName" => "activity",
            //            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2Name" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),

        "receiptDetailFields2" => array(
            1 => array(
                "nomer" => "name",
            ),
            2 => array(
                "nomer" => "name",
            ),
        ),
        "receiptNumFields2" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "receiptSubDetailFields2" => array(
            1 => array(
                "nama" => "sub item",
            ),
            2 => array(
                "nama" => "sub item",
            ),
        ),
        "receiptSubNumFields2" => array(
            1 => array(
                "harga" => "sub-amount",
            ),
            2 => array(
                "harga" => "sub-amount",
            ),
        ),

        "receiptDetailFields3" => array(
            1 => array(
                "reference_topNomer" => "request number",
                "nomer" => "receipt number",

            ),
            2 => array(
                "reference_topNomer" => "request number",
                "nomer" => "receipt number",

            ),
        ),
        "receiptNumFields3" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),

        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    //  config request expense/biaya
    "1674" => array(
        "receiptTemplate" => array(
            1 => "template/1674r.html",
            2 => "template/1674.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "take_homepay" => "take home pay",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "take_homepay" => "take home pay",

                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            //            "sisa" => "amount",
            //            "diskon" => "discount",
            //            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "harga" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "total amount",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedNoteTop" => "<strong>Jumlah gaji yang diisi adalah Jumlah yang diberikan/diterima karyawan.</strong>",
    ),
    "7674" => array(
        "receiptTemplate" => array(
            1 => "template/1674r.html",
            2 => "template/1674.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "take_homepay" => "take home pay",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
                "harga" => "total",
            ),
            2 => array(
//                "take_homepay" => "take home pay",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
                "harga" => "total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            //            "sisa" => "amount",
            //            "diskon" => "discount",
            //            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "harga" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "total amount",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),
    //  config request expense/biaya
    "11674" => array(
        "receiptTemplate" => array(
            1 => "template/1674r.html",
            2 => "template/1674.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "take_homepay" => "take home pay",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "take_homepay" => "take home pay",

                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            //            "sisa" => "amount",
            //            "diskon" => "discount",
            //            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "harga" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "total amount",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedNoteTop" => "<strong>Jumlah gaji yang diisi adalah Jumlah yang diberikan/diterima karyawan.</strong>",
    ),
    //  config request expense/biaya
    "21674" => array(
        "receiptTemplate" => array(
            1 => "template/1674r.html",
            2 => "template/1674.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "take_homepay" => "take home pay",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "take_homepay" => "take home pay",

                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            //            "sisa" => "amount",
            //            "diskon" => "discount",
            //            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "harga" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "total amount",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),

    //request baiya umum cabang
    "675" => array(
        "receiptTemplate" => array(
            1 => "template/675r.html",
            2 => "template/675.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    "2675" => array(
        "receiptTemplate" => array(
            1 => "template/675r.html",
            2 => "template/675.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    //biaya umum pusat
    "1675" => array(
        "receiptTemplate" => array(
            1 => "template/675r.html",
            2 => "template/675.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "harga" => "amount",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            ////            "*" => "-",
            ////            "-" => "-",
            //            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),
    //biaya besar belum mengacu pada pembebanan
    "4675" => array(
        "receiptTemplate" => array(
            1 => "template/675r.html",
            2 => "template/675.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    //biaya usaha pusat
    "1677" => array(
        "receiptTemplate" => array(
            1 => "template/677r.html",
            2 => "template/677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "harga" => "amount",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            ////            "*" => "-",
            ////            "-" => "-",
            //            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),
    // config supplies yang dibiayakan
    "762" => array(
        "receiptTemplate" => array(
            1 => "template/762r.html",
            2 => "template/762.html",
            //                3 => "application/template/762.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            //            "suppliers_nama" => "Supplier",
            //            "tlp_1" => "phone",
            //            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            //            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            2 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "item name",
            "qty" => "qty",
            "satuan" => "satuan",
            "harga" => "price",
            "sub_harga" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "-",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "tagihan" => "original amount",
            //            "nilai_entry" => "paid using cash account",
            //            "nilai_bayar" => "total amount of payment",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedNote" => "Nilai yang tampil disini adalah estimasi pembiayaan supplies. Nilai yang sebenarnya setelah dilakukan otorisasi pembiayaan supplies.",
    ),
    "9982" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            2 => "template/9982.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
            2 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "ppn" => "VAT",
                //                "nett2" => "Grand Total",
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
            2 => array(
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "orig expense",
            "pihak2Name" => "to expense",
            "harga_disc" => "unit price",
            "sub_harga_disc" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "sub_harga_disc" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Amount",
            "ppn" => "VAT",
            "nett" => "Grand Total",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),

    ),// biaya usaha
    "9983" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            2 => "template/9982.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
            2 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "ppn" => "VAT",
                //                "nett2" => "Grand Total",
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
            2 => array(
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "orig expense",
            "pihak2Name" => "to expense",
            "harga_disc" => "unit price",
            "sub_harga_disc" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "sub_harga_disc" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Amount",
            "ppn" => "VAT",
            "nett" => "Grand Total",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),

    ),// biaya umum
    "9984" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            2 => "template/9982.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
            2 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "ppn" => "VAT",
                //                "nett2" => "Grand Total",
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
            2 => array(
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "nama" => "orig expense",
            "pihak2Name" => "to expense",
            "harga_disc" => "unit price",
            "sub_harga_disc" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "sub_harga_disc" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Amount",
            "ppn" => "VAT",
            "nett" => "Grand Total",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),

    ),// biaya produksi
    "9985" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            2 => "template/9982.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
            2 => array(
                "produk_nama" => "orig expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                "pihak2Name" => "to expense",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "ppn" => "VAT",
                //                "nett2" => "Grand Total",
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
            2 => array(
                "harga_disc" => "Amount",
                "ppn" => "VAT",
                "nett" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "orig expense",
            "pihak2Name" => "to expense",
            "harga_disc" => "unit price",
            "sub_harga_disc" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            //            "sub_harga_disc" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Amount",
            "ppn" => "VAT",
            "nett" => "Grand Total",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),

    ),// pindah biaya usaha ke lainnya
    //koreksi biaya ke ppv
    "9922" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            2 => "template/9982.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                //                "pihak2Name" => "to expense",
            ),
            2 => array(
                "produk_nama" => "expense",
                //                "produk_kode" => "part name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
                //                "pihak2Name" => "to expense",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Amount",

            ),
            2 => array(
                "harga" => "Amount",

            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "ppn" => "VAT",
                //                "nett2" => "Grand Total",
                //                "harga_disc" => "Amount",
                //                "ppn" => "VAT",
                //                "nett" => "Grand Total",
            ),
            2 => array(
                //                "harga_disc" => "Amount",
                //                "ppn" => "VAT",
                //                "nett" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
    ),
    //request biaya bunga kepemegang saham
    "4449" => array(
        "receiptTemplate" => array(
            1 => "template/4449r.html",
            2 => "template/4449.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "pemegang saham",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No. ",
                "nomer_top2" => "No. Pinjaman",
                "dtime" => "Date",
                "olehName" => "Requested By",
            ),
            2 => array(
                "nomer" => "No. ",
                "nomer_top2" => "No. Pinjaman",
                "dtime" => "Date",
                "olehName" => "Approved By",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama ",
            ),
            2 => array(
                "produk_nama" => "nama ",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "nilai_pph23" => "pph23 15%",
                "grand_total" => "grand total",
            ),
            2 => array(
                "nilai_pph23" => "pph23 15%",
                "grand_total" => "grand total",
            ),
        ),
        "receiptSumFieldsReplacer" => array(//            "additional kurs" => "additional__label",
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai bunga<br>(bulanan)",
            ),
            2 => array(
                "harga" => "nilai bunga<br>(bulanan)",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),

        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "grand_total"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "grand_total"),
            ),
        ),
    ),
    //imbalan jasa
    "119" => array(
        "receiptTemplate" => array(
            1 => "template/651r.html",
            2 => "template/651.html",

        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "customers_nama" => "GRN No",
                "nomer" => "No",
                "dtime" => "Date",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "customers_nama" => "GRN No",
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            //            3 => array(
            //                "nomer" => "Receipt  No.",
            //                "nomer_top" => "PO No.",
            //                "dtime" => "Date",
            //                //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            //            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                                "ppn" => "VAT",
                "nilai_pph23" => "pph 23",
                "nilai_pph21" => "pph 21",
                "nett" => "grand total",
            ),
            2 => array(
                "subtotal" => "amount",
                "nilai_pph23" => "pph 23",
                "nilai_pph21" => "pph 21",
                "nett" => "grand total",
            ),
            //            3 => array(
            //                "harga" => "amount",
            ////                "ppn" => "VAT",
            ////                "nett" => "grand total",
            //            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                "ppnPersen" => "pph21 (%)",
                "ppn" => "pph21 (IDR)",
            ),
            2 => array(
                "harga" => "Price",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Transaksi/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
    ),
    //  config request expense/biaya produksi
    "676" => array(
        "receiptTemplate" => array(
            1 => "template/676r.html",
            2 => "template/676.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),

    "2762" => array(
        "receiptTemplate" => array(
            1 => "template/762r.html",
            2 => "template/762.html",
            //                3 => "template/762.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            //            "suppliers_nama" => "Supplier",
            //            "tlp_1" => "phone",
            //            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            //            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            2 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "harga",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
        ),
        "fixedNote" => "Nilai yang tampil disini adalah estimasi pembiayaan supplies. Nilai yang sebenarnya setelah dilakukan otorisasi pembiayaan supplies.",
    ),
    //pendapatan lain-lain
    "742" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            //            2 => "application/template/675.html",
        ),
        "headerNota" => array(
            //            "vendor"            => array(
            //                "suppliers_nama" => "name",
            //                "tlp_1"          => "phone",
            //                "alamat_1"       => "address",
            //            ),
            //            "delivery addrress" => array(
            //                "dtime"             => "date",
            //                "suppliers_nama"    => "Supplier",
            //                "tlp_1"             => "phone",
            //                "alamat_1"          => "address",
            //                "dtime_jatuh_tempo" => "jatuh tempo",
            //                "pembayaran"        => "payment method",
            //            ),
            "pendapatan lain-lain" => array(
                "nomer" => "receipt no.",
                "dtime" => "date",

                //                "currency"       => "currency",
                //                "devlivery_date" => "delivery date",
                //                "top"            => "term of payment",
                //                "tos"            => "term of shipment",
                //                "capacity"       => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    //config beban lain lain
    "743" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            // 1 => "template/742.html",
            //            2 => "application/template/675.html",
        ),
        "headerNota" => array(
            //            "vendor"            => array(
            //                "suppliers_nama" => "name",
            //                "tlp_1"          => "phone",
            //                "alamat_1"       => "address",
            //            ),
            //            "delivery addrress" => array(
            //                "dtime"             => "date",
            //                "suppliers_nama"    => "Supplier",
            //                "tlp_1"             => "phone",
            //                "alamat_1"          => "address",
            //                "dtime_jatuh_tempo" => "jatuh tempo",
            //                "pembayaran"        => "payment method",
            //            ),
            "pendapatan lain-lain" => array(
                "nomer" => "receipt no.",
                "dtime" => "date",

                //                "currency"       => "currency",
                //                "devlivery_date" => "delivery date",
                //                "top"            => "term of payment",
                //                "tos"            => "term of shipment",
                //                "capacity"       => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    "2674" => array(
        "receiptTemplate" => array(
            1 => "template/1674r.html",
            2 => "template/1674.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "take_homepay" => "take home pay",
                // "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "take_homepay" => "take home pay",
                // "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),

    //otorisasi biaya budgeting transaksi auto terbit saat QC
    "3674" => array(
        "receiptTemplate" => array(
            1 => "template/1674r.html",
            2 => "template/1674.html",
        ),
        "headerNota" => array(
            "vendor" => array(

                "pihakProjekCustomerName" => "Konsumen",
                "pihakProjekName" => "Projek",
                "pihakWoProjekSpk" => "SPK",
            ),
//            "delivery addrress" => array(
//                "dtime" => "date",
//                "suppliers_nama" => "Supplier",
//                "tlp_1" => "phone",
//                "alamat_1" => "address",
//                "dtime_jatuh_tempo" => "jatuh tempo",
//                "pembayaran" => "payment method",
            //                ),
//            "purchase order" => array(
//                "nomer" => "receipt no.",
//                "currency" => "currency",
//                "devlivery_date" => "delivery date",
//                "top" => "term of payment",
//                "tos" => "term of shipment",
//                "capacity" => "address",
            //                ),
        ),
        "fixedElements" => array(
            1 => array(
                "pihakProjekCustomerName" => "Konsumen",
                "pihakProjekName" => "Projek",
//                "pihakWoProjekName" => "Wo",
                "pihakWoProjekSpk" => "SPK",
                "nomer" => "No request",
                "dtime" => "tanggal",
                "pihakWoProjekEmployeeName" => "Pelaksana",
            ),
            2 => array(
                "pihakProjekCustomerName" => "Konsumen",
                "pihakProjekName" => "Projek",
//                "pihakWoProjekName" => "Wo",
                "pihakWoProjekSpk" => "SPK",
                "nomer" => "No request",
                "dtime" => "tanggal",
//                "nomer" => "No",
//                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "take_homepay" => "take home pay",
                // "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "take_homepay" => "take home pay",
                // "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),

        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),


    //  config request expense/biaya usaha
    "6677" => array(
        "receiptTemplate" => array(
            1 => "template/6677r.html",
            2 => "template/6677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "itemLabels2" => array(
            "nama" => "Detil cash back",
            "produk_kode" => "SKU",
            "qty" => "qty",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        "fixedElementsSwitch" => array(
            "gate" => "pajakOption",
            "key" => array(
                "pph21" => array(
                    "customerDetails" => false,
                    "freelancerDetails" => true,
                ),
                "pph23" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
                "pph23_15" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
            ),
        ),
    ),

    "6678" => array(
        "receiptTemplate" => array(
            1 => "template/6677r.html",
            2 => "template/6677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        "fixedElementsSwitch" => array(
            "gate" => "pajakOption",
            "key" => array(
                "pph21" => array(
                    "customerDetails" => false,
                    "freelancerDetails" => true,
                ),
                "pph23" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
                "pph23_15" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
            ),
        ),
    ),
    //otorisasi biaya usaha
    "16677" => array(
        "receiptTemplate" => array(
            1 => "template/6677r.html",
            2 => "template/6677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenisTrName" => "activity",
            //            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2Name" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),

        "receiptDetailFields2" => array(
            1 => array(
                "nomer" => "name",
            ),
            2 => array(
                "nomer" => "name",
            ),
        ),
        "receiptNumFields2" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "receiptSubDetailFields2" => array(
            1 => array(
                "nama" => "sub item",
            ),
            2 => array(
                "nama" => "sub item",
            ),
        ),
        "receiptSubNumFields2" => array(
            1 => array(
                "harga" => "sub-amount",
            ),
            2 => array(
                "harga" => "sub-amount",
            ),
        ),

        "receiptDetailFields3" => array(
            1 => array(
                "reference_topNomer" => "request number",
                "nomer" => "receipt number",

            ),
            2 => array(
                "reference_topNomer" => "request number",
                "nomer" => "receipt number",

            ),
        ),
        "receiptNumFields3" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),

        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "headerField" => "heTransaksi_layout",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        "fixedElementsSwitch" => array(
            "gate" => "pajakOption",
            "key" => array(
                "pph21" => array(
                    "customerDetails" => false,
                    "freelancerDetails" => true,
                ),
                "pph23" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
                "pph23_15" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
            ),
        ),
    ),
    "16678" => array(
        "receiptTemplate" => array(
            1 => "template/6677r.html",
            2 => "template/6677.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenisTrName" => "activity",
            //            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2Name" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "produk_nama" => "nama",
                //                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),

        "receiptDetailFields2" => array(
            1 => array(
                "nomer" => "name",
            ),
            2 => array(
                "nomer" => "name",
            ),
        ),
        "receiptNumFields2" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "receiptSubDetailFields2" => array(
            1 => array(
                "nama" => "sub item",
            ),
            2 => array(
                "nama" => "sub item",
            ),
        ),
        "receiptSubNumFields2" => array(
            1 => array(
                "harga" => "sub-amount",
            ),
            2 => array(
                "harga" => "sub-amount",
            ),
        ),

        "receiptDetailFields3" => array(
            1 => array(
                "reference_topNomer" => "request number",
                "nomer" => "receipt number",

            ),
            2 => array(
                "reference_topNomer" => "request number",
                "nomer" => "receipt number",

            ),
        ),
        "receiptNumFields3" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),

        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "headerField" => "heTransaksi_layout",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        "fixedElementsSwitch" => array(
            "gate" => "pajakOption",
            "key" => array(
                "pph21" => array(
                    "customerDetails" => false,
                    "freelancerDetails" => true,
                ),
                "pph23" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
                "pph23_15" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
            ),
        ),
    ),
    "1676" => array(
        "receiptTemplate" => array(
            1 => "template/1676.html",
            2 => "template/1676.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
                "alias" => "attn",

            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "pihakPembebanan__label" => "cabang pembebanan",
                "biaya_detail__label" => "kategori biaya",
                "pphMethodeStatus__label" => "status pajak",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "pihakPembebanan__label" => "cabang pembebanan",
                "biaya_detail__label" => "kategori biaya",
                "pphMethodeStatus__label" => "status pajak",
            ),
        ),
        "fixedSignatures" => array(),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
//            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item logam mulia",
                "produk_ord_jml" => "jumlah(gr)",
                "satuan" => "satuan",
            ),
            2 => array(
                "produk_nama" => "item logam mulia",
                "produk_ord_jml" => "jumlah(gr)",
                "satuan" => "satuan",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                "nett2" => "amount",
                "nilai_pph21" => "pph ps 21",
                "nilai_pph23" => "pph ps 23",
            ),
            2 => array(
                "nett2" => "amount",
                "nilai_pph21" => "pph ps 21",
                "nilai_pph23" => "pph ps 23",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "grand_total" => "sub-total"

            ),
            //            2 => array(
            //                "stok" => "stok",
            //                "valas_nilai" => "price",
            //                "sub_nett2_valas" => "sub-total"
            //            ),
            //
            //            3 => array(
            ////                "stok" => "stok",
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            4 => array(
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            5 => array(
            //                "valas_nilai" => "price",
            //                "disc" => "disc",
            //                "sub_nett2_valas" => "sub-total"
            //
            //            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "staticFooter" => array(
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nett2",),
            "currency_id" => "valasDetails",
        ),
        "fixedNoteTop" => "Kalau Emas diuangkan jangan pakai menu ini.<br>Emas melibatkan <b>STOK (Fisik)</b> dan melibatkan transfer pajak oleh penerima.",
    ),

    "66772" => array(
        "receiptTemplate" => array(
            1 => "template/66772r.html",
            2 => "template/66772.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "produk_nama" => "nama",
                "produk_ord_jml" => "qty",
                "inv_new_net1" => "dpp",
                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "harga" => "nilai",
//                "ppn" => "PPN",
//                "grandTotal" => "Grand Total",
            ),
            2 => array(
//                "harga" => "nilai",
//                "ppn" => "PPN",
//                "grandTotal" => "Grand Total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "itemLabels2" => array(
            "nama" => "Detil rebate",
            "produk_kode" => "SKU",
            "qty" => "qty",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
//        "fixedElementsSwitch" => array(
//            "gate" => "pajakOption",
//            "key" => array(
//                "pph21" => array(
//                    "customerDetails" => false,
//                    "freelancerDetails" => true,
//                ),
//                "pph23" => array(
//                    "customerDetails" => true,
//                    "freelancerDetails" => false,
//                ),
//                "pph23_15" => array(
//                    "customerDetails" => true,
//                    "freelancerDetails" => false,
//                ),
//            ),
//        ),
    ),

);