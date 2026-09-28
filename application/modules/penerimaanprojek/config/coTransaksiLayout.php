<?php

$config["coTransaksiLayout"] = array(

    // config penerimaan piutang customer (uang masuk)
    "749" => array(
        "receiptTemplate" => array(
            1 => "template/749.html",
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
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "UOM",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                // "selisih_round" => "total amount",
                "nilai_round" => "total amount",
                "uang_muka_dipakai" => "uang muka",
                "creditAmount" => "credit note(deposit)",
                "nilai_biaya" => "(biaya suport)",
                "ppn_nilai_dibayar" => "(ppn dibayar bendahara negara)",
                "pph22_nilai" => "(pph 22 dibayar dimuka)",
                "nilai_entry" => "paid ",
                "nilai_bayar" => "total payment",
                // "new_sisa" => "balance",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nilai_bayar",),
        ),
        "fixedNote" => "WARNING:<br>
                - Biaya Suport (biaya admin yang dipotong pihak lain) jika di isi, akan dibebankan sebagai biaya usaha.<br>
                - Cash Received (uang yang diterima) tidak boleh sama atau lebih dari nilai invoice karena sudah dipotong Biaya Support.<br>
                ",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
    ),
    // config penerimaan piutang customer valas (uang masuk)
    "1749" => array(
        "receiptTemplate" => array(
            1 => "template/749.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa_valas" => "total amount",
                "creditAmount" => "customer credit amount",
                "nilai_entry" => "paid using cash account",
                //                "nilai_valas_terbayar" => "total amount of payment",
                "new_sisa_valas" => "remain receivable (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa_valas" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "in_word" => array("inWordEng" => "nilai_entry",),
            "currency_id" => "valasDetails",
        ),
        //        "receiptInword" => array("inWordEng" => "nilai_entry"),
    ),
    //penerimaan piutang jasa kirim
    "2749" => array(
        "receiptTemplate" => array(
            1 => "template/749.html",
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
                "nomer" => "No.",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                "creditAmount" => "customer credit amount",
                //                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                "new_sisa" => "remain receivable (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nilai_bayar",),
        //
        //        ),
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
    // config penerimaan piutang customer project)
    "7499_ORI" => array(
        "receiptTemplate" => array(
            1 => "template/749.html",
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
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "UOM",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                // "selisih_round" => "total amount",
                "nilai_round" => "total amount",
                "uang_muka_dipakai" => "uang muka",
                "creditAmount" => "credit note(deposit)",
                "nilai_biaya" => "(biaya suport)",
                "ppn_nilai_dibayar" => "(ppn dibayar bendahara negara)",
                "pph22_nilai" => "(pph 22 dibayar dimuka)",
                "nilai_entry" => "paid ",
                "nilai_bayar" => "total payment",
                // "new_sisa" => "balance",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nilai_bayar",),
        ),
        "fixedNote" => "WARNING:<br>
                - Biaya Suport (biaya admin yang dipotong pihak lain) jika di isi, akan dibebankan sebagai biaya usaha.<br>
                - Cash Received (uang yang diterima) tidak boleh sama atau lebih dari nilai invoice karena sudah dipotong Biaya Support.<br>
                ",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
    ),
    "7488" => array(
        "receiptTemplate" => array(
            1 => "template/749.html",
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
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "UOM",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                // "selisih_round" => "total amount",
                "nilai_round" => "total amount",
                "uang_muka_dipakai" => "uang muka",
                "creditAmount" => "credit note(deposit)",
                "nilai_biaya" => "(biaya suport)",
                "ppn_nilai_dibayar" => "(ppn dibayar bendahara negara)",
                "pph22_nilai" => "(pph 22 dibayar dimuka)",
                "nilai_entry" => "paid ",
                "nilai_bayar" => "total payment",
                // "new_sisa" => "balance",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nilai_bayar",),
        ),
        "fixedNote" => "WARNING:<br>
                - Biaya Suport (biaya admin yang dipotong pihak lain) jika di isi, akan dibebankan sebagai biaya usaha.<br>
                - Cash Received (uang yang diterima) tidak boleh sama atau lebih dari nilai invoice karena sudah dipotong Biaya Support.<br>
                ",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
    ),


    "7499" => array(
        "receiptTemplate" => array(
            1 => "template/749.html",
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
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "projectName" => "nama project",
                "projectNilai" => "nilai project",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "project_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                // dimatikan
//                "nilai_round" => "total amount",
                // dimatikan juga
                "nilai_entry" => "nilai termin",
                "uang_muka_dipakai_ppn" => "uang muka dipakai",
                "dpp_ppn" => "netto",
                "ppn" => "ppn",
                "uang_muka_npajak_dipakai" => "ppn sudah dibayar (dari uang muka)",
                "ppn_belum_dibayar" => "ppn belum dibayar (kekurangan)",
                "piutang_usaha" => "nilai tagihan",
            ),
        ),

        "receiptAddDpp" => array(
            1 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
//                "sisa" => "due remain",
                "nilai_bayar" => "nilai",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "11" => array(
                "in_word" => array("inWordInd" => "tagihan"),
            ),
        ),
//        "fixedNote" => "WARNING:<br>
//                - Biaya Suport (biaya admin yang dipotong pihak lain) jika di isi, akan dibebankan sebagai biaya usaha.<br>
//                - Cash Received (uang yang diterima) tidak boleh sama atau lebih dari nilai invoice karena sudah dipotong Biaya Support.<br>
//                ",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
    ),
);