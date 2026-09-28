<?php

$config["coTransaksiLayout"] = array(
    //taxes A/P Payment
    "682" => array(
        "receiptTemplate" => array(
            1 => "template/672r.html",
            2 => "template/672.html",
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
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
            ),
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
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            //            3 => array(
            //                "produk_nama" => "product name",
            //                "produk_ord_jml" => "qty",
            //            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            3 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    // config pembayaran hutang gaji ke cabang
    "1483_OLD" => array(
        "receiptTemplate" => array(
            1 => "template/1483.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",

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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "realisasi_kurang" => "koreksi",
//                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),
    "4447" => array(
        //        "receiptTemplate"     => "application/template/489.html",
        "receiptTemplate" => array(
            1 => "template/489.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "creditValue" => "additional discount",
                //                "additional_value" => "additional kurs",
                //                "additional_expense" => "additional expense",
                //                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "loan installment",
                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),

        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "sisa" => "sisa",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "vendorDetails__nama" => "name",

        ),
        "reviewCompactListDetailSum" => array(
            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
            //
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
    // config pembayaran biaya umum
    "462" => array(
        "receiptTemplate" => array(
            1 => "template/462.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "branchTarget__nama" => "Cabang",
                "externMain__nama" => "kategory biaya",
                "dtaDetail__label" => "beban biaya",

            ),
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "non_pph" => "non pph23",
                "valid_dpp" => "dpp pph23",
                "pph23_nilai" => "pph 23",

                "creditAmount" => "supplier credit amount",
                //                "harus_bayar" => "amount remains to pay",
                "nilai_dipakai_credit_card" => "credit card",
                "payment_out" => "cash payout",

                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai5" => "DPP pph21",
                "extern_nilai2" => "DPP pph23",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "payment_out"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "satuan" => "uom",
            "extern_nilai2" => "DPP pph23",
            "extern_nilai3" => "DPP ppn",
            "ppn" => "ppn",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            //            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "sisa" => "total amount",
            "non_pph" => "non pph23",
            "valid_dpp" => "dpp pph23",
            "pph23_nilai" => "pph 23",
            "creditAmount" => "supplier credit amount",
            "payment_out" => "cash payout",
            "new_sisa" => "remain to pay (from list)",
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
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
                - Menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Jika pph 23 tidak dipotong, maka nominal phh 23 akan dimasukan ke biaya usaha(non npwp).<br>
                - Cicilan ap payment jasa dimasukkan sebagai uang muka. Uang muka hanya bisa dipakai saat pelunasan.
                ",
        "fixedNoteLink" => array(
            array(
                "link" => base_url() . "kas/Create/index/464",
                "label" => "Untuk memasukkan Uang Muka ke Vendor bisa melalui menu Transaksi -> Pembayaran -> Uang Muka ",
            ),
        ),
    ),
    // config pembayaran hutang ke supplier
    "487" => array(
        "receiptTemplate" => array(
            1 => "template/487.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "amount",
                "creditAmount" => "credit note",
                "creditValue" => "additional discount",
                "additional_value" => "exchange rate difference",
                "additional_expense" => "additional expense",
                "uang_muka_dipakai" => "paid by deposit",
                "nilai_dipakai_credit_card" => "credit card",
                "nilai_entry" => "payment",
                "new_sisa" => "balance of invoice",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array("inWordInd" => "harus_bayar"),

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "sisa" => "sisa",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
            //
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
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
                - Menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Menggunakan Cash Account HARUS mengisi jumlah nominal (baris payment) yang akan digunakan.<br>
                
                ",
    ),
    // config pembayaran hutang ke supplier
    "489" => array(
        //        "receiptTemplate"     => "application/template/489.html",
        "receiptTemplate" => array(
            1 => "template/489.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "tagihan bruto",
                "selisih_koreksi" => "Koreksi -",
                "selisih_koreksi_plus" => "Koreksi +",
                "uang_muka_dipakai_ppn" => "Uangmuka + ppn",
                "dpp_final" => "Dpp",
                "ppn_final" => "Ppn",
                "tagihan_bayar" => "Tagihan",
                "additional_expense" => "tambahan biaya",
                "uang_muka_dipakai" => "Deposit (Uang Muka)",
                "credit_note_dipakai" => "Credit Note (Return Pembelian)",
                "credit_note_diskon" => "klaim kepada supplier",
                "nilai_diskon_dipakai_add" => "diskon",
                "nilai_entry" => "Dibayar",
                "new_sisa" => "Sisa tagihan",

            ),
        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "receipNumFields" => array(
            1 => array(
                "sisa" => "sisa",
//                "sub_harga_x" => "koreksi",
            ),

        ),
        "printLocation" => "Printing/viewReceiptReg",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "sisa" => "sisa",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "qty**",
            //            "*" => "-",
            //            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
            //
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
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Melakukan Koreksi harga perolehan.<br>
                - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
                - Menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Menggunakan Cash Account HARUS mengisi jumlah nominal (baris payment) yang akan digunakan.<br>
                
                ",
    ),
    "111" => array(
        //        "receiptTemplate"     => "application/template/489.html",
        "receiptTemplate" => array(
            1 => "template/489.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "dateFaktur" => "tgl entry faktur",
                "eFaktur" => "faktur",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "dpp_ppn" => "dpp",
//                            "pp" => "dpp",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "sisa" => "amount",
//                "additional_expense" => "Additional Expense",
//                "uang_muka_dipakai" => "Deposit (Uang Muka)",
//                "credit_note_dipakai" => "Credit Note (Return Pembelian)",
//                "credit_note_diskon" => "klaim kepada supplier",
//                "nilai_diskon_dipakai_add" => "diskon",
                "dpp_ppn" => "dpp",
                "ppn_sisa" => "ppn",
//                "new_sisa" => "balance of invoice",
//
//                "new_sisa" => "remain to pay (from list)",
//                "additional_value" => "exchange rate difference",
//                "creditValue" => "additional discount",
            ),
        ),
        "receiptSumFieldsReplacer" => array(//            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "receiptNumFields" => array(
            1 => array(//                "sisa" => "sisa",
            ),

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
//            "nama" => "Description",
//            "qty" => "qty",
//            "sisa" => "sisa",
//            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
            //
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
        "fixedNote" => "Catatan:<br>
                - Dapat melakukan realisasi ppn masukan untuk multi Penerimaan produk(GRN) dalam satu PO.<br>
                - Jika Nilai realisaisi ppn lebih kecil/lebih besar, akan berimbas pada nilai hutang dagang.<br>
                - Untuk Lintas Po saat in dimatikan, silahkan konsultasikan degnan tim kami jika ada keperluan untuk lintas PO.<br>
                ",
    ),
    //  config pembayaran expense/biaya usaha
    "477" => array(
        "receiptTemplate" => array(
            1 => "template/477.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "reference number",
                "sisa" => "amount",
                "diskon" => "discount",
                "nilai_bayar" => "paid amount",
                "new_sisa" => "remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total",
                "nilai_dipakai_credit_card" => "credit card",
                "nilai_bayar" => "jumlah pembayaran",
                "new_sisa" => "sisa belum dibayar",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "fixedNote" => "Catatan:<br>
                Status Pemotongan PPh ditentukan pada saat pembayaran. Oleh karena itu, saat cabang mengajukan permintaan biaya, mereka tidak perlu memilih
                   status pajak yang dikenakan. Pemilihan PPh hanya dilakukan di tahap pencairan atau pembayaran.<br><br>

Tarif PPh dapat dipilih berdasarkan jenis transaksi. Sistem memungkinkan pemilihan tarif yang sesuai agar pemotongan pajak sesuai
    dengan ketentuan yang berlaku untuk masing-masing jenis transaksi.<br><br>

Apabila status pemotongan PPh dipotong dan vendor tidak dipotongan PPh(dibayar penuh sesuai tagihan), maka:<br>
     Transaksi tetap akan diproses menggunakan metode gross-up (yaitu PPh ditanggung perusahaan).<br>
     Nilai PPh-nya akan dibebankan ke akun biaya usaha.
     Vendor tidak wajib ditentukan,Sistem akan otomatis mencatat ke akun PPh Kolektif sebagai penampung PPh gross-up tersebut.
                ",
        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            "sisa" => "amount",
            "diskon" => "discount",
            "nilai_bayar" => "paid amount",
            "new_sisa" => "remain",
            "tagihan" => "total price",
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
            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "tagihan" => "original amount",
            "nilai_entry" => "paid using cash account",
            "nilai_bayar" => "total amount of payment",
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
    "1477" => array(
        "receiptTemplate" => array(
            1 => "template/477.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "reference number",
                "sisa" => "amount",
                "diskon" => "discount",
                "nilai_bayar" => "paid amount",
                "new_sisa" => "remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "original amount",
                "nilai_dipakai_credit_card" => "credit card",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                //        "new_sisa"    => "remain debt (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
    ),
    "6475" => array(
        "receiptTemplate" => array(
            1 => "template/475.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "reference number",
                "sisa" => "amount",
                "diskon" => "discount",
                "nilai_bayar" => "paid amount",
                "new_sisa" => "remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "original amount",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                //        "new_sisa"    => "remain debt (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
    ),
    //pembayaran hutang kepemegang saham
    "4448" => array(
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
                "nomer" => "No. Pembayaran",
                "vendorDetails__label" => "Pemegang Saham",
                "dtime" => "Date",
                "cash_account__label" => "Cash Account",
            ),
            2 => array(
                "nomer" => "No. Pembayaran",
                "vendorDetails__label" => "Pemegang Saham",
                "dtime" => "Date",
                "cash_account__label" => "Cash Account",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item(s)",
                "produk_ord_jml" => "qty",
            ),
        ),
        "subAmountValue" => array(
            1 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "nilai dibayar",
                "nilai_bayar" => "payment",
            ),
            2 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "nilai dibayar",
                "nilai_bayar" => "payment",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "total bayar",
                "new_sisa" => "sisa hutang",
            ),
            2 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "total bayar",
                "new_sisa" => "sisa hutang",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "nilai hutang",
                "nilai_bayar" => "nilai bayar",
                //                "new_sisa" => "nilai sisa",
                //                "terbayar" => "paid",
                //                "sisa" => "sisa",
            ),
        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),

        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "nilai_bayar"),
            ),
            1 => array(
                "in_word" => array("inWordInd" => "nilai_bayar"),
            ),
        ),
    ),
    // config pembayaran hutang gaji ke cabang
    "1485" => array(
        "receiptTemplate" => array(
            1 => "template/1485.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            //            "tlp_1" => "phone",
            //            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",

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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "creditAmount_dipakai" => "potongan",
                "harus_bayar" => "amount remains to pay",
//                "nilai_entry" => "amount of payment",
//                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            "sisa" => "amount",
            //            "diskon" => "discount",
            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "tagihan" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "tagihan" => "original amount",
            "nilai_entry" => "paid using cash account",
            "nilai_bayar" => "total amount of payment",
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
    //  config pembayaran expense/biaya umum
    "475" => array(
        "receiptTemplate" => array(
            1 => "template/475.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "reference number",
                "sisa" => "amount",
                "diskon" => "discount",
                "nilai_bayar" => "paid amount",
                "new_sisa" => "remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "original amount",
                "nilai_dipakai_credit_card" => "credit card",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                //        "new_sisa"    => "remain debt (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            "sisa" => "amount",
            //            "diskon" => "discount",
            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "tagihan" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "tagihan" => "original amount",
            "nilai_entry" => "paid using cash account",
            "nilai_bayar" => "total amount of payment",
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
    "1475" => array(
        "receiptTemplate" => array(
            1 => "template/475.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "reference number",
                "sisa" => "amount",
                "diskon" => "discount",
                "nilai_bayar" => "paid amount",
                "new_sisa" => "remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "original amount",
                "nilai_dipakai_credit_card" => "credit card",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                //        "new_sisa"    => "remain debt (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            "sisa" => "amount",
            //            "diskon" => "discount",
            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "tagihan" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "tagihan" => "original amount",
            "nilai_entry" => "paid using cash account",
            "nilai_bayar" => "total amount of payment",
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
    // config pembayaran hutang bpjs
    "1487" => array(
        "receiptTemplate" => array(
            1 => "template/475.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",

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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "harus_bayar" => "amount remains to pay",
//                "nilai_entry" => "amount of payment",
//                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),
    //pembayaran hutang kepemegang saham
    "4411" => array(
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
                "nomer" => "No. Pembayaran",
                "vendorDetails__label" => "Pemegang Saham",
                "dtime" => "Date",
                "cash_account__label" => "Cash Account",
            ),
            2 => array(
                "nomer" => "No. Pembayaran",
                "vendorDetails__label" => "Pemegang Saham",
                "dtime" => "Date",
                "cash_account__label" => "Cash Account",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item(s)",
                "produk_ord_jml" => "qty",
            ),
        ),
        "subAmountValue" => array(
            1 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "nilai dibayar",
                "nilai_bayar" => "payment",
            ),
            2 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "nilai dibayar",
                "nilai_bayar" => "payment",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "total bayar",
                "new_sisa" => "sisa hutang",
            ),
            2 => array(
                "sisa" => "total hutang",
                "nilai_bayar" => "total bayar",
                "new_sisa" => "sisa hutang",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "nilai hutang",
                "nilai_bayar" => "nilai bayar",
                //                "new_sisa" => "nilai sisa",
                //                "terbayar" => "paid",
                //                "sisa" => "sisa",
            ),
        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),

        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "nilai_bayar"),
            ),
            1 => array(
                "in_word" => array("inWordInd" => "nilai_bayar"),
            ),
        ),
    ),
    //imbal jasa A/P payment
    "2119" => array(
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
                "amount" => "extern_nilai2",
                "nilai_pph23" => "pph 23",
                "nilai_pph21" => "pph 21",
                "tagihan" => "grand total",

                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "pph_23" => "pph21",
                "sisa" => "amount remaining",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "extern_nilai2" => "Unit Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
    ),
    //pph 29
    "5684" => array(
        "receiptTemplate" => array(
            1 => "template/672.html",
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
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
            ),
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
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            //            3 => array(
            //                "produk_nama" => "product name",
            //                "produk_ord_jml" => "qty",
            //            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            3 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    //pph 23 A/P payment
    "115" => array(
        "receiptTemplate" => array(
            1 => "template/651.html",
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
                "produk_nama" => "detail transactions",
                "extern_nama" => "vendor/supplier",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "detail transactions",
                "extern_nama" => "vendor/supplier",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "detail transactions",
                "extern_nama" => "vendor/supplier",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harus_bayar" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "sisa" => "pph23",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
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
                "in_word" => array("inWordInd" => "tagihan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
    ),
    //setor ppn bulanan
    "114" => array(
        "receiptTemplate" => array(
            1 => "template/114r.html",
            // 2 => "template/114.html",
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
        "receiptGlobalStepVar" => array(
            1 => array(
                "jenis_label" => "Detil entry",
            ),
        ),
        /**
         * tampil setelah title nota
         */
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Tgl Entry ebilling",
                "ebilling" => "no. ebilling",
                "ebillingDate" => "Tgl e-billing",
                //                "dtime_required" => "Required Date",
                // "cabang_nama" => "branch",
                //                "pihakName" => "Category",
                //                "pihakMainRulesName" => "Aset",
            ),
            // 2 => array(
            //     "nomer" => "No.",
            //     "dtime" => "Date",
            //     //                "dtime_required" => "Required Date",
            //     "cabang_nama" => "branch",
            //     "pihakName" => "Category",
            //     "pihakMainRulesName" => "Aset",
            // ),
            // 3 => array(
            //     "nomer" => "No.",
            //     "dtime" => "Date",
            //     //                "dtime_required" => "Required Date",
            //     "cabang_nama" => "branch",
            //     "pihakName" => "Category",
            //     "pihakMainRulesName" => "Aset",
            // ),
            // 4 => array(
            //     "nomer" => "No.",
            //     "dtime" => "Date",
            //     //                "dtime_required" => "Required Date",
            //     "cabang_nama" => "branch",
            //     "pihakName" => "Category",
            //     "pihakMainRulesName" => "Aset",
            // ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "receipt no.",
            "dtime" => "e-billing date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        /**
         * untuk tabel kedua (yg bawah)
         */
        "receiptDetailFields" => array(
            1 => array(
                "dtime_entry" => "entry date",
                "extern_date2" => "e-faktur date",
                "extern_label2" => "e-faktur",
                "customers_nama" => "customer",
                "produk_nama" => "no entry",
                "extern2_nama" => "faktur penjualan",
                "extern_nilai2" => "tax base (DPP)",
                //                "satuan" => "satuan",
                //                "harga" => "price",
                //            "ppn" => "ppn",
            ),

        ),
        /**
         * untuk table pertama (yg atas)
         */
        "receiptDetailSrcFields" => array(
            1 => array(
                "dtime_entry" => "entry date",
                "extern_date2" => "E-faktur date",
                "extern_label2" => "E-faktur",
                "extern_nama" => "supplier",
                "nama" => "realisasi",
                "extern_nilai2" => "tax base (DPP)",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "satuan",
                "sisa" => "PPN masukan",
                "subtotal" => "subtotal",
                //            "ppn" => "ppn",
            ),

        ),
        "receiptDetailFields2" => array(
            2 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                //                "nett" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                //                "nett" => "price",
                //            "ppn" => "ppn",
            ),
            4 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                "nett" => "price",
                "ppn" => "ppn",
            ),
        ),
        /**
         * sum kanan bagian bawah
         * */
        "receiptSumDetailFields" => array(
            // 1 => array(
            //     "okok" => "yesyes test"
            // ),
            2 => array(
                "subtotal" => "Total Price",
            ),
        ),
        /** ----
         * summary nota yg bawah
         */
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "ppn keluaran",
                "ppn_masukan" => "ppn masukan",
                "denda_nilai" => "biaya pinalti",
                "nilai_deposit_src_dipakai" => "deposit",
                "nilai_entry" => "wajib setor"
                //                "harga" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "nilai_bayar" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            4 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "headerField" => "heTransaksi_layout",
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
            "nama" => "Description",
            //            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "Date",

        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
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
        "fixedNote" => "Notes:<br>
        - biaya pinalti dapat berupa biaya keterlambatan dan / bunga<br>
        - biaya pinalti akan dibebankan ke biaya lain-lain",
    ),
    //setor hutang pph ps4(2)
    "1120" => array(
        "receiptTemplate" => array(
            1 => "template/651r.html",
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
                "harus_bayar" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
    ),
    //payment source object pajak
    "5682" => array(
        "receiptTemplate" => array(
            1 => "template/672r.html",
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
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
            ),
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
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            //            3 => array(
            //                "produk_nama" => "product name",
            //                "produk_ord_jml" => "qty",
            //            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            3 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    //service A/P payment
    "1462" => array(
        "receiptTemplate" => array(
            1 => "template/462.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
            ),
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "harga" => "price",
                //                "referensi" => "reference",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "non_pph" => "non pph23",
                "valid_dpp" => "dpp pph23",
                "pph23_nilai" => "pph 23",

                "creditAmount" => "supplier credit amount",
                "uang_muka_dipakai" => "(deposit)",
                "payment_out" => "cash payout",

                // "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "payment_out"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "satuan" => "uom",
            "extern_nilai2" => "DPP pph23",
            "extern_nilai3" => "DPP ppn",
            "ppn" => "ppn",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            //            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "sisa" => "total amount",
            "non_pph" => "non pph23",
            "valid_dpp" => "dpp pph23",
            "pph23_nilai" => "pph 23",
            "creditAmount" => "supplier credit amount",
            "payment_out" => "cash payout",
            "new_sisa" => "remain to pay (from list)",
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
        "allowPrint" => array(
            1 => array("size" => "normal"),
        ),
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
                - Menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Jika pph 23 tidak dipotong, maka nominal phh 23 akan dimasukan ke biaya usaha(non npwp).<br>
                - Cicilan ap payment jasa dimasukkan sebagai uang muka. Uang muka hanya bisa dipakai saat pelunasan.
                ",
        "fixedNoteLink" => array(
            array(
                "link" => base_url() . "kas/Create/index/464",
                "label" => "Untuk memasukkan Uang Muka ke Vendor bisa melalui menu Transaksi -> Pembayaran -> Uang Muka ",
            ),
        ),
    ),
    //  config pembayaran expense/biaya produksi
    "476" => array(
        "receiptTemplate" => array(
            1 => "template/476.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "reference number",
                "sisa" => "amount",
                "diskon" => "discount",
                "nilai_bayar" => "paid amount",
                "new_sisa" => "remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "original amount",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                //        "new_sisa"    => "remain debt (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            "sisa" => "amount",
            //            "diskon" => "discount",
            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "tagihan" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "tagihan" => "original amount",
            "nilai_entry" => "paid using cash account",
            "nilai_bayar" => "total amount of payment",
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
    //config A/P payment import
    "4891" => array(
        //        "receiptTemplate"     => "application/template/489.html",
        "receiptTemplate" => array(
            1 => "template/489.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "amount",

                //                "additional_value" => "exchange rate difference",
                //                "additional_expense" => "additional expense",
                //
                //                "nilai_entry" => "payment",
                //                "new_sisa" => "balance of invoice",

            ),
        ),
        "receipSumFields" => array(
            1 => array(
                "sisa" => "amount",
                "valas_nilai_stock" => "stock valas",
                "uang_muka_valas_dipakai" => "uang muka valas",
                "valas_kurang" => "kekurangan valas",
                "kurs_actual" => "kurs beli",
                "additional_value" => "exchange rate difference (idr)",
                "nilai_entry" => "payment (IDR)",
                "valas_new_sisa" => "balance of invoice (valas)",
                "new_sisa_ui" => "balance of invoice (idr)",
            ),
        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "sisa" => "sisa",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
            //
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
    // config pembayaran hutang sewa ke supplier
    "1424" => array(
        "receiptTemplate" => array(
            1 => "template/1424.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
            ),
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "harga" => "price",
                "ppn" => "ppn",
                //                "referensi" => "reference",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai2" => "Unit Price",
                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "valid_ppn_nilai" => "ppn",
                "pphps4_2_nilai" => "(pph ps4.2)",
                "pph23_nilai" => "(pph 23)",
                "credit_note_dipakai" => "credit note",
                "creditValue" => "additional discount",
                "additional_value" => "exchange rate difference",
                "additional_expense" => "additional expense",
                "uang_muka_dipakai" => "paid by deposit",
                "nilai_entry" => "payment",
                "new_sisa" => "balance of invoice",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "payment_out"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
        ),
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
    ),

    // config pembayaran hutang aset ke supplier
    "4821" => array(
        //        "receiptTemplate"     => "application/template/489.html",
        "receiptTemplate" => array(
            //            1 => "application/template/489.html",
            1 => "template/462.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                //                "non_pph" => "non pph23",
                //                "valid_dpp" =>"dpp pph23**",
                //                "pph23_nilai" => "pph 23",

                "credit_note_dipakai" => "credit note",
                "uang_muka_dipakai" => "credit note",
                //                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                //                "payment_out" => "cash payout",

                "new_sisa" => "balance of invoice",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "sisa" => "total amount",
                //                "non_pph" => "non pph23",
                //                "valid_dpp" =>"dpp pph23**",
                //                "pph23_nilai" => "pph 23",

                //                "credit_note_dipakai" => "credit note",
                //                "uang_muka_dipakai"   => "credit note",
                ////                "harus_bayar" => "amount remains to pay",
                //                "nilai_entry"         => "amount of payment",
                ////                "payment_out" => "cash payout",
                //
                //                "new_sisa" => "balance of invoice",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
        ),
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_non_insentif_reguler" => "DPP",
                "dpp_non_insentif" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        "receiptEfakturInsentifFields" => array(
            1 => array(
                "eFaktur_insentif" => "Nomer Faktur",
                "dateFaktur_insentif" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_insentif_reguler" => "DPP",
                "dpp_insentif" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
                - Menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Menggunakan Cash Account HARUS mengisi jumlah nominal (baris payment) yang akan digunakan.<br>
                
                ",
    ),
    "117" => array(
        "receiptTemplate" => array(
            1 => "template/1483.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",

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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                //                "creditAmount" => "supplier credit amount",
                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Transaksi/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),


    //rekening koran payable
    "4440" => array(
        //        "receiptTemplate"     => "application/template/489.html",
        "receiptTemplate" => array(
            1 => "template/489.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "nomer_top" => "SO No.",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "amount",
//                "creditAmount" => "supplier credit amount",
//                "creditValue" => "additional discount",
//                "additional_value" => "additional kurs",
//                "additional_expense" => "additional expense",
//                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "loan installment",
                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "receiptSumFieldsReplacer" => array(
            "additional kurs" => "additional__label",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),

        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "sisa" => "sisa",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "vendorDetails__nama" => "name",

        ),
        "reviewCompactListDetailSum" => array(
            "." => "qty",
//            "*" => "-",
//            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
//            "harga" => "Total Amount",
//            "ppn" => "VAT",
//            "hpp_nppn" => "Grand Total",
//
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

    // config pembayaran service projek
    "483_OLD_2024-11-30" => array(
        "receiptTemplate" => array(
            1 => "template/462.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "branchTarget__nama" => "Cabang",
                "externMain__nama" => "kategory biaya",
                "dtaDetail__label" => "beban biaya",

            ),
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "non_pph" => "non pph23",
                "valid_dpp" => "dpp pph23",
                "pph23_nilai" => "pph 23",

                "creditAmount" => "supplier credit amount",
                //                "harus_bayar" => "amount remains to pay",
                "payment_out" => "cash payout",

                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai5" => "DPP pph21",
                "extern_nilai2" => "DPP pph23",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "payment_out"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "satuan" => "uom",
            "extern_nilai2" => "DPP pph23",
            "extern_nilai3" => "DPP ppn",
            "ppn" => "ppn",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            //            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "sisa" => "total amount",
            "non_pph" => "non pph23",
            "valid_dpp" => "dpp pph23",
            "pph23_nilai" => "pph 23",
            "creditAmount" => "supplier credit amount",
            "payment_out" => "cash payout",
            "new_sisa" => "remain to pay (from list)",
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
        // - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Melakukan pembayaran Fullpayment dengan menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Jika pph 23 tidak dipotong, maka nominal phh 23 akan dimasukan ke biaya usaha(non npwp).<br>
                - Cicilan ap payment jasa dimasukkan sebagai uang muka. Uang muka hanya bisa dipakai saat pelunasan.
                ",
        "fixedNoteLink" => array(
            array(
                "link" => base_url() . "kas/Create/index/464",
                "label" => "Untuk memasukkan Uang Muka ke Vendor bisa melalui menu Transaksi -> Pembayaran -> Uang Muka ",
            ),
        ),
    ),
    // config pembayaran service projek
    "483" => array(
        "receiptTemplate" => array(
            1 => "template/462.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "branchTarget__nama" => "Cabang",
                "externMain__nama" => "kategory biaya",
                "dtaDetail__label" => "beban biaya",

            ),
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
//                "non_pph" => "non pph23",
//                "valid_dpp" => "dpp pph23",
//                "pph23_nilai" => "pph 23",
//                "creditAmount" => "supplier credit amount",
//                //                "harus_bayar" => "amount remains to pay",
//                "payment_out" => "cash payout",
//                "new_sisa" => "remain to pay (from list)",
                "selisih_koreksi" => "koreksi minus",
                "selisih_koreksi_plus" => "koreksi plus",
                "uang_muka_dipakai_ppn" => "Uangmuka+ppn",
                "dpp_pph" => "DPP PPh",
                "dpp_netto" => "DPP PPN",
                "ppn_netto" => "ppn(11%)",
                "tagihan_bayar" => "tagihan",
                "pph21_nilai" => "(PPh 21)",
                "pph23_nilai" => "(PPh 23)",
                "uang_muka_dipakai" => "Deposit (titipan dengan relasi PO) ",
                "uang_muka_nonrelasi_dipakai" => "Deposit (titipan tanpa relasi) ",
                "nilai_dipakai_credit_card" => "credit card",
                "payment_out" => "Kewajiban Bayar",
//                "uang_muka_dipakai" => "total amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "extern_nilai5" => "DPP pph21",
                "extern_nilai2" => "DPP pph23",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                //                "sisa" => "sisa",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "payment_out"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "satuan" => "uom",
            "extern_nilai2" => "DPP pph23",
            "extern_nilai3" => "DPP ppn",
            "ppn" => "ppn",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            //            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "sisa" => "total amount",
            "non_pph" => "non pph23",
            "valid_dpp" => "dpp pph23",
            "pph23_nilai" => "pph 23",
            "creditAmount" => "supplier credit amount",
            "payment_out" => "cash payout",
            "new_sisa" => "remain to pay (from list)",
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
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
        // - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
                - Melakukan pembayaran Fullpayment dengan menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
                - Jika pph 23 tidak dipotong, maka nominal phh 23 akan dimasukan ke biaya usaha(non npwp).<br>
                - Cicilan ap payment jasa dimasukkan sebagai uang muka. Uang muka hanya bisa dipakai saat pelunasan.<br>
                - <b>Isilah DPP PPh Ps 23 karena tidak sama antar jenis/bisang usaha.</b>
                ",
        "fixedNoteLink" => array(
            array(
                "link" => base_url() . "kas/Create/index/464",
                "label" => "Untuk memasukkan Uang Muka ke Vendor bisa melalui menu Transaksi -> Pembayaran -> Uang Muka ",
            ),
        ),
    ),
    //setor ppn bulanan
    "1148" => array(
        "receiptTemplate" => array(
            1 => "template/114r.html",
            // 2 => "template/114.html",
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
        "receiptGlobalStepVar" => array(
            1 => array(
                "jenis_label" => "Detil entry",
            ),
        ),
        /**
         * tampil setelah title nota
         */
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Tgl Entry ebilling",
                "ebilling" => "no. ebilling",
                "ebillingDate" => "Tgl e-billing",
                //                "dtime_required" => "Required Date",
                // "cabang_nama" => "branch",
                //                "pihakName" => "Category",
                //                "pihakMainRulesName" => "Aset",
            ),
            // 2 => array(
            //     "nomer" => "No.",
            //     "dtime" => "Date",
            //     //                "dtime_required" => "Required Date",
            //     "cabang_nama" => "branch",
            //     "pihakName" => "Category",
            //     "pihakMainRulesName" => "Aset",
            // ),
            // 3 => array(
            //     "nomer" => "No.",
            //     "dtime" => "Date",
            //     //                "dtime_required" => "Required Date",
            //     "cabang_nama" => "branch",
            //     "pihakName" => "Category",
            //     "pihakMainRulesName" => "Aset",
            // ),
            // 4 => array(
            //     "nomer" => "No.",
            //     "dtime" => "Date",
            //     //                "dtime_required" => "Required Date",
            //     "cabang_nama" => "branch",
            //     "pihakName" => "Category",
            //     "pihakMainRulesName" => "Aset",
            // ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "receipt no.",
            "dtime" => "e-billing date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        /**
         * untuk tabel kedua (yg bawah)
         */
        "receiptDetailFields" => array(
            1 => array(
                "dtime_entry" => "entry date",
                "extern_date2" => "e-faktur date",
                "extern_label2" => "e-faktur",
                "customers_nama" => "customer",
                "produk_nama" => "no entry",
                "extern2_nama" => "faktur penjualan",
                "extern_nilai2" => "tax base (DPP)",
                //                "satuan" => "satuan",
                //                "harga" => "price",
                //            "ppn" => "ppn",
            ),

        ),
        /**
         * untuk table pertama (yg atas)
         */
        "receiptDetailSrcFields" => array(
            1 => array(
                "dtime_entry" => "entry date",
                "extern_date2" => "E-faktur date",
                "extern_label2" => "E-faktur",
                "extern_nama" => "supplier",
                "nama" => "realisasi",
                "extern_nilai2" => "tax base (DPP)",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "satuan",
                "sisa" => "PPN masukan",
                "subtotal" => "subtotal",
                //            "ppn" => "ppn",
            ),

        ),
        "receiptDetailFields2" => array(
            2 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                //                "nett" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                //                "nett" => "price",
                //            "ppn" => "ppn",
            ),
            4 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                "nett" => "price",
                "ppn" => "ppn",
            ),
        ),
        /**
         * sum kanan bagian bawah
         * */
        "receiptSumDetailFields" => array(
            // 1 => array(
            //     "okok" => "yesyes test"
            // ),
            2 => array(
                "subtotal" => "Total Price",
            ),
        ),
        /** ----
         * summary nota yg bawah
         */
        "receiptSumFields" => array(
            1 => array(
                "tagihan" => "ppn keluaran",
                "ppn_masukan" => "ppn masukan",
                "denda_nilai" => "biaya pinalti",
                "nilai_deposit_src_dipakai" => "deposit",
                "nilai_entry" => "wajib setor"
                //                "harga" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "nilai_bayar" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            4 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "headerField" => "heTransaksi_layout",
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
            "nama" => "Description",
            //            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "Date",

        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
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
        "fixedNote" => "Notes:<br>
        - biaya pinalti dapat berupa biaya keterlambatan dan / bunga<br>
        - biaya pinalti akan dibebankan ke biaya lain-lain",
    ),

    // pembayaran hutang komisi
    "1488" => array(
        "receiptTemplate" => array(
            1 => "template/1488.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",

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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total",
                "nilai_bayar" => "jumlah pembayaran",
                "new_sisa" => "sisa belum dibayar",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),


    "1483" => array(
        "receiptTemplate" => array(
            1 => "template/1483.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",

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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "extern_nama_old" => "supplier/vendor/konsumen<br>atau pihak lain",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total pph ps 21",
//                "realisasi_kurang" => "koreksi",
//                "harus_bayar" => "amount remains to pay",
                "nilai_dipakai_credit_card" => "credit card",
                "nilai_entry" => "kewajiban bayar",
                "new_sisa" => "sisa belum dibayar",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(//                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),

    "3675" => array(
        "receiptTemplate" => array(
            1 => "template/3675.html",
            2 => "template/3675.html",
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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total",
                "nilai_bayar" => "jumlah pembayaran",
                "new_sisa" => "sisa belum dibayar",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),

    "4811" => array(
        "receiptTemplate" => array(
            //            1 => "application/template/489.html",
            1 => "template/462.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "description",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
//                "credit_note_dipakai" => "credit note",
//                "uang_muka_dipakai" => "credit note",
                //                "harus_bayar" => "amount remains to pay",

                "nilai_entry" => "amount of payment",
                "new_sisa" => "balance of invoice",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "sisa" => "total amount",
                //                "non_pph" => "non pph23",
                //                "valid_dpp" =>"dpp pph23**",
                //                "pph23_nilai" => "pph 23",

                //                "credit_note_dipakai" => "credit note",
                //                "uang_muka_dipakai"   => "credit note",
                ////                "harus_bayar" => "amount remains to pay",
                //                "nilai_entry"         => "amount of payment",
                ////                "payment_out" => "cash payout",
                //
                //                "new_sisa" => "balance of invoice",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nilai_entry",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nilai_entry"),
            ),
        ),
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
            ),
        ),
//        "fixedNote" => "Pembayaran ini mengijinkan Anda:<br>
//                - Hanya menggunakan Cash Account atau Uang Muka atau Credit note.<br>
//                - Menggunakan kombinasi antara Cash Account, Uang Muka, Credit note.<br>
//                - Menggunakan Cash Account HARUS mengisi jumlah nominal (baris payment) yang akan digunakan.<br>
//
//                ",
    ),

);