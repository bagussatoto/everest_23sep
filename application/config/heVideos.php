<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/30/2019
 * Time: 5:49 AM
 */
/* ---------------------------------------------------------------------------------
 * videog ganti dmari gais
 * dg struktur array per_modul/mdl(tanpa karakter Mdl)/link dan label video
 * ---------------------------------------------------------------------------------*/
$config['videos_top'] = array(
    "statik" => array(
        "produk" => array(
            "https://www.youtube.com/embed/erP4edwvdZc?autoplay=1" => "Menambah Data Produk",
            "https://www.youtube.com/embed/1MrTqX6-auY?autoplay=1" => "Define produk non unit",
            "https://www.youtube.com/embed/aBY4LqbQHXU?autoplay=1" => "Upload produk foto",
            "https://www.youtube.com/embed/Q4AqSmkXtXY?autoplay=1" => "Menambah produk (draft)",
            "https://www.youtube.com/embed/rytrqk58Kkg?autoplay=1" => "Menghapus data",
            "https://www.youtube.com/embed/KdHqFAXwtug?autoplay=1" => "Setting harga jual",
            "https://www.youtube.com/embed/NMQtNFPcDZM?autoplay=1" => "Print barcode",
            "https://www.youtube.com/embed/e_M04RFRjsU?autoplay=1" => "Daikin",
            "https://www.youtube.com/embed/Hq3eD1UJvwM?autoplay=1" => "Panasonic",
            "https://www.youtube.com/embed/NlsrcS-iczs?autoplay=1" => "LG",
            "https://www.youtube.com/embed/NJCj76wzLjw?autoplay=1" => "Sharp",
            "https://www.youtube.com/embed/aLeR-t3hnyM?autoplay=1" => "Samsung",
            "https://www.youtube.com/embed/rmY7BLMagvI?autoplay=1" => "Haier",
            "https://www.youtube.com/embed/qwzVBagiszU?autoplay=1" => "Aqua",
            "https://www.youtube.com/embed/6v8KAS15_-o?autoplay=1" => "Gree",
            "https://www.youtube.com/embed/Cijm17mDRnI?autoplay=1" => "Changhong",


        ),
        "edc" => array(
            // https://youtu.be/x7k7ngZmxz8
            "https://www.youtube.com/embed/x7k7ngZmxz8?autoplay=1" => "1.1. Menambah biaya EDC",
        ),
        "customer" => array(
            "https://www.youtube.com/embed/kevA7PuguSY?autoplay=1" => "1.1. Menambah Data Konsumen",
            "https://www.youtube.com/embed/7tZn6GdSjTg?autoplay=1" => "1.2. Edit Data Konsumen",
            "https://www.youtube.com/embed/k1y6Hb_VXgI?autoplay=1" => "1.3. Cara Setting Kredit Limit dan Lama Kredit",
        ),
        "supplier" => array(
            "https://www.youtube.com/embed/CDoIdiIgMM0?autoplay=1" => "1.2. Menambah Data Supplier/Vendor",
        ),
        "salesman" => array(
            "https://www.youtube.com/embed/le9ZmxnCLxs?autoplay=1" => "Menambah Data Salesman",
        ),
        "bankaccount_in" => array(
            "https://www.youtube.com/embed/O9cOZGGkTjA?autoplay=1" => "Menambah rekening bank",
            "https://www.youtube.com/embed/5RsKrz7Rt0Q?autoplay=1" => "Relasi Mesin EDC dengan Rekening Bank",
            "https://www.youtube.com/embed/UGimyPZbBts?autoplay=1" => "Balance interchange",

            "https://www.youtube.com/embed/io6BYnPIrXE?autoplay=1" => "Tambah Data Credit Card",
        ),
        "organisasi" => array(
            "https://www.youtube.com/embed/ymx8aeG3qqI?autoplay=1" => "Strukture Besar dan Hak akses",
        ),
        "employeecabang" => array(
            "https://www.youtube.com/embed/Q2FC3WUT1aI?autoplay=1" => "Menambah User ID Cabang",
        ),
        "hak akses" => array(
            "https://www.youtube.com/embed/11xilTAdkaI?autoplay=1" => "Hak akses Input Biaya (Cabang dan Pusat)",
            "https://www.youtube.com/embed/WkVs-WlMErM?autoplay=1" => "Hak akses Input Pettycash (Cabang dan Pusat)",
            "https://www.youtube.com/embed/sZ84305heEY?autoplay=1" => "Hak akses Otorisasi klaim pettycash dan Refill Pettycash",
            "https://www.youtube.com/embed/oiizMvKYob0?autoplay=1" => "Hak akses Uang Muka ke Supplier",
            "https://www.youtube.com/embed/L_nBLBGLa60?autoplay=1" => "Hak akses Invoicing di Cabang",
            "https://www.youtube.com/embed/RN3eOCG2JTs?autoplay=1" => "Hak akses Pengembalian Uang ke Konsumen (input di Cabang dan otorisasi di DC/Pusat)",
            "https://www.youtube.com/embed/MfCwXH8BXNc?autoplay=1" => "Hak akses Pembayaran Biaya Project di DC/Pusat",
            "https://www.youtube.com/embed/pcUlkBVnTL0?autoplay=1" => "Hak akses Pembayaran Hutang Credit Card (Credit Card AP Payment)",
            "https://www.youtube.com/embed/ip5-8w_jmD8?autoplay=1" => "Seting hak akses Penjualan ke diri sendiri/ Pemakaian Sendiri",
            "https://www.youtube.com/embed/g237xYghsT4?autoplay=1" => "Seting hak akses Input Rebate Penjualan dan Penyerahan Rebate Penjualan",

        ),
    ),
    "diskon" => array(
        "setting_diskon" => array(
            "https://www.youtube.com/embed/wdG9DSGedRg?autoplay=1" => "Setting Diskon Pembelian Free Produk/Barang",
            "https://www.youtube.com/embed/p1tpM19iNdE?autoplay=1" => "Setting harga jual",
        ),
//        "diskon_pembelian" => array(
//
//        ),
//
    ),
    /* -------------------------------------------------------------------------------------
     * --contoh transaksional--
     * ---------------------------------------------------------------------------------*/
    "pembelian" => array(
        "466" => array(
            //         "https://www.youtube.com/embed/CDoIdiIgMM0?autoplay=1" => "cara pembayaran",

            "https://www.youtube.com/embed/tayAZ75g2_8?autoplay=1" => "Seting Hak akses Pembelian/PO Produk",

            "https://www.youtube.com/embed/4HdZZ6fLYSk?autoplay=1" => "Seting Hak akses Klaim free produk supplier",
            //https://youtu.be/dcQsWiFdPUc https://youtu.be/cVgiic6Q49c
            "https://www.youtube.com/embed/Fqn_MCX0WS4?autoplay=1" => "Pembelian/Purchasing Produk (PO)",
            // "https://www.youtube.com/embed/qUB9gvXvSYs?autoplay=1" => "Pembelian/Purchasing Produk (PO)",
//            "https://www.youtube.com/embed/dcQsWiFdPUc?autoplay=1" => "Pembelian/Purchasing Produk (PO)",
            // "https://www.youtube.com/embed/X-0TKrzSmtE?autoplay=1" => "Pembelian/PO Produk",
            "https://www.youtube.com/embed/8lz5lmfHemY?autoplay=1" => "Hak akses realisasi ppn masukan",
//            "https://www.youtube.com/embed/4J3_HTuQHEU?autoplay=1" => "realisasi PPN masukan",
            "https://www.youtube.com/embed/2sV5d8fZ_m0?autoplay=1" => "Pembelian/Purchasing Produk (PO) sampai dengan realisasi PPN masukan (Input E-Faktur)",
            // "https://www.youtube.com/embed/bIxT8HSI9rg?autoplay=1" => "Pembelian/PO Produk",
            "https://www.youtube.com/embed/UbScvoC1w2k?autoplay=1" => "Return Pembelian by nota",
            "https://www.youtube.com/embed/hccyWs7sNhY?autoplay=1" => "Retur pembelian by produk",
            "https://www.youtube.com/embed/OnJTVHJ-PEc?autoplay=1" => "Pembelian supplies",
            // https://youtu.be/LjegyTgMw1E
            "https://www.youtube.com/embed/LjegyTgMw1E?autoplay=1" => "GREE: Diskon Pembelian",


        ),
        "967" => array(
            "https://www.youtube.com/embed/UbScvoC1w2k?autoplay=1" => "Return Pembelian Produk",
        ),
        "463" => array(
            "https://www.youtube.com/embed/IZoA7r-jYIE?autoplay=1" => "Pembatalan SRN",
        )
    ),
    "pembelianjasa" => array(
        "463" => array(
//            "https://www.youtube.com/embed/4fpZJi3Ofu0?autoplay=1" => "Pembelian/PO Jasa/Service dan Pembayaran",
            "https://www.youtube.com/embed/6yim1RQr644?autoplay=1" => "Pembelian/PO Jasa/Service dan Pembayaran",
        ),

    ),
    "distribusifg" => array(
        "583" => array(
            // "https://www.youtube.com/embed/9I3mkC49Vqk?autoplay=1" => "distribusi produk dari dc ke cabang",
            //https://youtu.be/tzKfrGwHJwI
            "https://www.youtube.com/embed/tzKfrGwHJwI?autoplay=1" => "distribusi produk dari dc ke cabang",
        ),
        "585" => array(
            "https://www.youtube.com/embed/9I3mkC49Vqk?autoplay=1" => "distribusi produk dari dc ke cabang",
        ),
        "1983" => array(
            "https://www.youtube.com/embed/tt-wYsOAol8?autoplay=1" => "return stok dari cabang ke dc",
        ),
//        "1985" => array(
//            "https://www.youtube.com/embed/tt-wYsOAol8?autoplay=1" => "return stok dari cabang ke dc",
//        ),
    ),
    "konversi" => array(
//        "1336" => array(
//            "https://www.youtube.com/embed/pe1g3R8GvkE?autoplay=1" => "konversi produk unit ke non unit",
//        ),
        "1339" => array(
            "https://www.youtube.com/embed/pe1g3R8GvkE?autoplay=1" => "konversi produk unit ke non unit",
//            "https://www.youtube.com/embed/jQmzqKPVv5c?autoplay=1" => "konversi sparepart metode potong",
            "https://www.youtube.com/embed/fDlwgKtxJew?autoplay=1" => "konversi sparepart metode potong",
        ),
        "2336" => array(
            // https://youtu.be/oCEQ4FxFtTA
            "https://www.youtube.com/embed/oCEQ4FxFtTA?autoplay=1" => "konversi produk ke supplies",
//            "https://www.youtube.com/embed/jQmzqKPVv5c?autoplay=1" => "konversi sparepart metode potong",
//             "https://www.youtube.com/embed/fDlwgKtxJew?autoplay=1" => "konversi sparepart metode potong",
        ),
    ),
    "otorisasi" => array(
        "777_3" => array(
            "https://www.youtube.com/embed/v29RjuMQ_R0?autoplay=1" => "Hak akses perubahan harga persediaan",
            "https://www.youtube.com/embed/bKhktgwRzMA?autoplay=1" => "Perubahan harga persediaan",
        ),
    ),

    "pembayaran" => array(
        "489" => array(
            "https://www.youtube.com/embed/SDIwNdwNXsU?autoplay=1" => "Pembayaran Hutang Dagang belum ada faktur",
            "https://www.youtube.com/embed/iThzcccU3nM?autoplay=1" => "Pembayaran Hutang Dagang sudah ada faktur",
            "https://www.youtube.com/embed/LjegyTgMw1E?autoplay=1" => "GREE: Diskon Pembelian",
            "https://www.youtube.com/embed/juiPtIdGCKU?autoplay=1" => "Realiasi diskon free produk(klaim diskon)",
            "https://www.youtube.com/embed/JtfuJ0Gtu54?autoplay=1" => "Cara Realisasi Klaim Ke Supplier (diterima kas)",
            "https://www.youtube.com/embed/j22bTbIX8IA?autoplay=1" => "Cara Realisasi Klaim Ke Supplier (diterima Credit Note)",
            "https://www.youtube.com/embed/aIXHULojh7c?autoplay=1" => "Pembayaran Hutang Dagang (gabungan banyak GRN dengan banyak faktur PPN)",
            "https://www.youtube.com/embed/DRrmlpGQUvM?autoplay=1" => "Pembayaran Hutang Dagang dengan Creditnote (Return Pembelian)",
            "https://www.youtube.com/embed/zGMiDleVUA4?autoplay=1" => "Pembayaran Hutang Dagang dengan Titipan tanpa relasi PO",
            "https://www.youtube.com/embed/0uZpH4HPPUY?autoplay=1" => "Pembayaran Hutang Dagang dengan Creditnote (klaim diskon supplier) dan Titipan tanpa relasi PO",

        ),
        "462" => array(
            "https://www.youtube.com/embed/4fpZJi3Ofu0?autoplay=1" => "Pembayaran Hutang Biaya (Service Purchase)",
        ),
        "464" => array(
//            "https://www.youtube.com/embed/1WegRRGdXUM?autoplay=1" => "Input Uang Muka ke Supplier (dibayar dengan akun kas) dan approve efaktur ppn masukan",
//            "https://www.youtube.com/embed/XBb7Hz9gceg?autoplay=1" => "Input Uang Muka ke Supplier (pindah dari uang muka tanpa ppn) dan approve efaktur ppn masukan",
//            "https://www.youtube.com/embed/ZDTNpL6YXFg?autoplay=1" => "Input Uang Muka ke Supplier (tanpa ppn)",
            "https://www.youtube.com/embed/gCiVxfmL_98?autoplay=1" => "Input Titipan Uang ke Supplier (tanpa ppn)",
            "https://www.youtube.com/embed/J_B1BXrnfho?autoplay=1" => "Input Uang Muka dengan PPN ke Supplier (dari titipan)",
        ),
        "3675" => array(
            "https://www.youtube.com/embed/XDcyHeSu_zw?autoplay=1" => "Cara Pembayaran Biaya Project di DC/Pusat (cicilan atau lunas)",
        ),
        "4811" => array(
            "https://www.youtube.com/embed/4QUOzCIWvfg?autoplay=1" => "Contoh Pembayaran Hutang Biaya dengan Credit Card",
            "https://www.youtube.com/embed/zDmWyDLdgMg?autoplay=1" => "Cara Pembayaran Hutang Credit Card",
        ),
    ),
    "aset" => array(
        "423" => array(
            "https://www.youtube.com/embed/G67HB3DiC5s?autoplay=1" => "Seting hak akses pembelian aset",
            "https://www.youtube.com/embed/PooM_4TqJOw?autoplay=1" => "pembelian aset",
            "https://www.youtube.com/embed/7U_T6GDGpIE?autoplay=1" => "Seting hak akses aset A/P payment",
            "https://www.youtube.com/embed/rUAQDXYaZZE?autoplay=1" => "Aset A/P Payment",
            "https://www.youtube.com/embed/c8j2R2as-3g?autoplay=1" => "Seting Depresiasi",
            "https://www.youtube.com/embed/t4pys__RetU?autoplay=1" => "Posting manual Depresiasi",
            "https://www.youtube.com/embed/Wd41Vttu-qQ?autoplay=1" => "Seting Hak Akses Otorisasi Depresiasi",
            "https://www.youtube.com/embed/JCI3CkqJ1YM?autoplay=1" => "Otorisasi Depresiasi",
            "https://www.youtube.com/embed/tNSB5UbCV9U?autoplay=1" => "Distribusi dan penjualan aset",

        ),
        "424" => array(
            "https://www.youtube.com/embed/6ves4vrp0mg?autoplay=1" => "Seting Hak Akses Sewa",
            "https://www.youtube.com/embed/2K0SWyTRYHM?autoplay=1" => "Pembelian Sewa (Request, Otorisasi dan Terima Sewa)",
//            "https://www.youtube.com/embed/0ujwQNsAcKE?autoplay=1" => "Penjualan Aset",
            "https://www.youtube.com/embed/eyoHJ3_H2p0?autoplay=1" => "Input Request dan Otorisasi Penjualan Aset",
            "https://www.youtube.com/embed/IGUcLlZ2N5w?autoplay=1" => "Penjualan ke diri sendiri/ Pemakaian Sendiri",
        ),
    ),
    "inputstok" => array(
        "6699" => array(
//            "https://www.youtube.com/embed/t-48Sk_EnRw?autoplay=1" => "Input Stok Produk",
            "https://www.youtube.com/embed/yzTYIRVAeo8?autoplay=1" => "Input Stok Produk",
        )
    ),
    "penjualan" => array(
//        "582" => array(
//            "https://www.youtube.com/embed/EAdFTEuIKzA?autoplay=1" => "Penjualan",
//            "https://www.youtube.com/embed/xnlUkZxvNY8?autoplay=1" => "Return Penjualan",
//        ),
        "9822" => array(
            "https://www.youtube.com/embed/gWMxUWcQ6oU?autoplay=1" => "Return Penjualan",
        ),
        "5822" => array(
            "https://www.youtube.com/embed/pfHJMqUUO-g?autoplay=1" => "Penjualan (Sales Support Mode) dikirim dari DC",
            "https://www.youtube.com/embed/zcFeMXLw9RM?autoplay=1" => "Penjualan (Sales Support Mode) dikirim dari Cabang",
            "https://www.youtube.com/embed/noy1RhJDsnA?autoplay=1" => "Penjualan (Sales Support Mode) Produk non Unit dikirim dari DC dan Konversi Unit ke Non Unit",
            "https://www.youtube.com/embed/SpR_LQYufUg?autoplay=1" => "Setting Diskon kategori",
            "https://www.youtube.com/embed/vnpZMozUOwY?autoplay=1" => "Edit Sales Order",
            "https://www.youtube.com/embed/-mPokFZSQJ4?autoplay=1" => "Pilih Sopir/Driver Pengiriman ke konsumen",
            "https://www.youtube.com/embed/TrIMw4zddKE?autoplay=1" => "Mengedit Data Customer",
            "https://www.youtube.com/embed/ptsX8Q9hzV8?autoplay=1" => "Penjualan (Sales Support Mode) dan barang dikirim dari Supplier ke konsumen",
            "https://www.youtube.com/embed/5zrcWo8JF8s?autoplay=1" => "Validasi TOP/Limit Kredit",
            "https://www.youtube.com/embed/XC6h-ZUW5tk?autoplay=1" => "Seting hadiah penjualan",
            "https://www.youtube.com/embed/irzsYXne6-k?autoplay=1" => "Penjualan(dengan hadiah)",

        ),
        "5823" => array(
            "https://www.youtube.com/embed/bqHxNg1XXTs?autoplay=1" => "Seting hak akses Transaksi POS",
            "https://www.youtube.com/embed/tnFTr-wjzM?autoplay=1" => "Entry Transaksi  POS",
            "https://www.youtube.com/embed/NqwrxH_NPAQ?autoplay=1" => "Penerimaan kas POS",
            "https://www.youtube.com/embed/-BctpXFmaH0?autoplay=1" => "Packinglist POS",

        ),
        "5844" => array(
            "https://www.youtube.com/embed/ip5-8w_jmD8?autoplay=1" => "Seting hak akses Penjualan ke diri sendiri/ Pemakaian Sendiri",
            "https://www.youtube.com/embed/IGUcLlZ2N5w?autoplay=1" => "Penjualan ke diri sendiri/ Pemakaian Sendiri",
        ),
    ),
    "invoicing" => array(
        "4822" => array(
            "https://www.youtube.com/embed/wUbWAohGgi0?autoplay=1" => "Membuat/cetak Invoice",
        ),
    ),
    "penerimaan" => array(
        "749" => array(
            "https://www.youtube.com/embed/rZfl5xZ3gos?autoplay=1" => "Cara Pelunasan AR dengan Credit Note dari Return Penjualan",
            "https://www.youtube.com/embed/A6G9SzSgp9I?autoplay=1" => "Cara Pelunasan AR Marketplace dan kelebihan bayar menjadi Deposit Konsumen atau Dikembalikan ke Konsumen.",
            "https://www.youtube.com/embed/k1y6Hb_VXgI?autoplay=1" => "1.3. Cara Setting Kredit Limit dan Lama Kredit",
        ),
    ),
    "kas" => array(
        /*
         * start=62 -> video autoplay dari detik ke 62
         */
        "9467" => array(
            // dibawah ini bagian KONSUMEN
            "https://www.youtube.com/embed/RN3eOCG2JTs?autoplay=1" => "Hak akses Pengembalian Uang ke Konsumen (input di Cabang dan otorisasi di DC/Pusat)",
//            "https://www.youtube.com/embed/FuiVaet6XZk?autoplay=1" => "Cara Pengembalian Deposit/Uang Muka/Kelebihan Bayar ke Konsumen (di Cabang)",
            "https://www.youtube.com/embed/K6yXT6VarrI?start=0&autoplay=1" => "Cara Pengembalian Deposit/Uang Muka/Kelebihan Bayar ke Konsumen (di Cabang)",
            "https://www.youtube.com/embed/K6yXT6VarrI?start=62&autoplay=1" => "Cara Otorisasi Pengembalian Deposit/Uang Muka/Kelebihan Bayar ke Konsumen (di DC/Pusat)",
            "https://www.youtube.com/embed/rcvenES9EDI?start=0&autoplay=1" => "Cara Setting Hak Akses Request dan Otorisasi Uang Masuk Tanpa Identitas",
            "https://www.youtube.com/embed/pmgI2o9wZdQ?start=0&autoplay=1" => "Cara Setting Hak Akses Request dan Otorisasi Relasi Uang Masuk Tanpa Identitas",
            "https://www.youtube.com/embed/mp7VSWkRfs4?start=0&autoplay=1" => "Cara Request dan Otorisasi Uang Masuk Tanpa Identitas",
            "https://www.youtube.com/embed/8Maz-qqm-l0?start=0&autoplay=1" => "Cara Request dan Otorisasi Relasi Uang Masuk Tanpa Identitas",

            // dibawah ini bagian SUPPLIER
            "https://www.youtube.com/embed/5kZ7DjRZgEI?autoplay=1" => "Cara Penerimaan kas dari Creditnote Supplier (Return Pembelian, Klaim Diskon Supplier)",
            "https://www.youtube.com/embed/X-7nBqfd5Wo?autoplay=1" => "Cara penerimaan kas dari titipan tanpa relasi po(pengembalian dari supplier)",
            "https://www.youtube.com/embed/pLtviWsDMY8?autoplay=1" => "Cara melepas relasi Titipan dengan PO (menjadi Titipan tanpa relasi)",
//            "https://www.youtube.com/embed/eY69ta-BPVI?autoplay=1" => "Cara membuat Titipan ke Supplier (relasi PO) dari Titipan tanpa Relasi",
//            "https://www.youtube.com/embed/XeQWura0418?autoplay=1" => "Cara ganti relasi Titipan ke Supplier (relasi PO)",
        ),
//        "19467" => array(
////            "https://www.youtube.com/embed/LYz0162ZUSM?autoplay=1" => "Cara Otorisasi Pengembalian Deposit/Uang Muka/Kelebihan Bayar ke Konsumen (di DC/Pusat)",
//            "https://www.youtube.com/embed/K6yXT6VarrI?start=62&autoplay=1" => "Cara Otorisasi Pengembalian Deposit/Uang Muka/Kelebihan Bayar ke Konsumen (di DC/Pusat)",
//        ),
        "7444" => array(
            "https://www.youtube.com/embed/rcvenES9EDI?start=0&autoplay=1" => "Cara Setting Hak Akses Request dan Otorisasi Uang Masuk Tanpa Identitas",
            "https://www.youtube.com/embed/mp7VSWkRfs4?start=0&autoplay=1" => "Cara Request dan Otorisasi Uang Masuk Tanpa Identitas",
        ),
        "7445" => array(
            "https://www.youtube.com/embed/pmgI2o9wZdQ?start=0&autoplay=1" => "Cara Setting Hak Akses Request dan Otorisasi Relasi Uang Masuk Tanpa Identitas",
            "https://www.youtube.com/embed/8Maz-qqm-l0?start=0&autoplay=1" => "Cara Request dan Otorisasi Relasi Uang Masuk Tanpa Identitas",
        ),
    ),

    "biaya" => array(
        "677" => array(
            "https://www.youtube.com/embed/bDW8Ey7IE5g?autoplay=1" => "Input biaya di cabang dan Otorisasi di Kantor Pusat",
        ),
        "6677" => array(
            "https://www.youtube.com/embed/fWUjU1emTV4?autoplay=1" => "Input Request Biaya Komisi Penjualan Freelancer di Cabang dengan PPh21 tarif 2.5% dan Otorisasi di DC/Pusat",
            "https://www.youtube.com/embed/Gf1gDmugWQo?autoplay=1" => "Input Request Biaya Komisi Penjualan Perusahaan di Cabang dengan PPh23 tarif 2% dan Otorisasi di DC/Pusat",
            "https://www.youtube.com/embed/VnsyBhT4ZMY?autoplay=1" => "Input Request Biaya Cashback Penjualan di Cabang dengan PPh23 tarif 15% dan Otorisasi di DC/Pusat",
            //---------------------
            "https://www.youtube.com/embed/1PD-OnWsn-E?autoplay=1" => "Input Request Biaya Cashback Penjualan di Cabang dengan freelancer member/terdaftar (auto otorisasi).",
            "https://www.youtube.com/embed/MnPx0A-tvDk?autoplay=1" => "Pembayaran Komisi/Cashback Penjualan di DC/Pusat oleh Finance ke Freelancer (Member/Terdaftar) lunas dan cicilan.",
            "https://www.youtube.com/embed/g237xYghsT4?autoplay=1" => "Seting hak akses Input Rebate Penjualan dan Penyerahan Rebate Penjualan",
            "https://www.youtube.com/embed/jrQX7g_1O-g?start=0&autoplay=1" => "Seting hak akses Input Cashback/Hadiah barang ke konsumen",
//            "https://www.youtube.com/embed/EjgOeTSMH7w?start=0&autoplay=1" => "Cara Input Cashback/Hadiah barang ke konsumen",
            "https://www.youtube.com/embed/nZ8zwhcrkZg?start=0&autoplay=1" => "Cara Input Cashback/Hadiah barang ke konsumen",
            "https://www.youtube.com/embed/EjgOeTSMH7w?start=150&autoplay=1" => "Cara Penerimaan Uang Untuk Pembayaran Pajak/Hadiah/Sejenisnya",
            "https://www.youtube.com/embed/EjgOeTSMH7w?start=255&autoplay=1" => "Cara Penyerahan Barang Hadiah ke konsumen",

        ),
        "66772" => array(
            "https://www.youtube.com/embed/g237xYghsT4?autoplay=1" => "Seting hak akses Input Rebate Penjualan dan Penyerahan Rebate Penjualan",
            "https://www.youtube.com/embed/h4XIYUstMaI?start=0&autoplay=1" => "Cara Input Rebate Penjualan (Barang) ke konsumen",
            "https://www.youtube.com/embed/h4XIYUstMaI?start=123&autoplay=1" => "Cara Penyerahan Rebate Penjualan (Barang) ke konsumen",
        ),
        "6678" => array(
            "https://www.youtube.com/embed/UPAukLowU_4?start=0&autoplay=1" => "Input Request Biaya Komisi/Cashback Project di Cabang.",
            "https://www.youtube.com/embed/UPAukLowU_4?start=112&autoplay=1" => "Otorisasi Request Biaya Komisi/Cashback Project di DC/Pusat.",
            "https://www.youtube.com/embed/UPAukLowU_4?start=185&autoplay=1" => "Pembayaran Komisi/Cashback Project di DC/Pusat oleh Finance ke Freelancer.",
            //---------------------

        ),
        "1675" => array(
            "https://www.youtube.com/embed/mYhiMIx76HA?autoplay=1" => "Laporan biaya",
        ),
        "7762" => array(
            "https://www.youtube.com/embed/nfOVNycGd0A?autoplay=1" => "Hak akses Pembiayaan Supplies",
            "https://www.youtube.com/embed/7AQf4HyAcjs?autoplay=1" => "Input Pembiayaan Supplies dan Otorisasi Pembiayaan Supplies.",
        ),
        "1674" => array(
            "https://www.youtube.com/embed/wnS7hxzP0gI?autoplay=1" => "Input Request Biaya Gaji dan Otorisasi Request Biaya Gaji",
            "https://www.youtube.com/embed/oFvCAL4ytEc?autoplay=1" => "Cara Edit Request Biaya Gaji",
            "https://www.youtube.com/embed/cEAge3ioBiM?autoplay=1" => "Setting Hak Akses Input Request Biaya Gaji (di cabang) dan Otorisasi Request Biaya Gaji (di DC/Pusat)",
            "https://www.youtube.com/embed/rg82gUNrQgg?autoplay=1" => "Setting Hak Akses BPJS dan PPh21 (ditanggung karyawan dan ditanggung perusahaan)",
            "https://www.youtube.com/embed/ackaA_pGKoI?start=0&autoplay=1" => "Request BPJS dan PPh21 (ditanggung karyawan dan ditanggung perusahaan)",
            "https://www.youtube.com/embed/ackaA_pGKoI?start=92&autoplay=1" => "Otorisasi BPJS dan PPh21 (ditanggung karyawan dan ditanggung perusahaan)",
        ),
        "1676" => array(
            "https://www.youtube.com/embed/izlHZnlWHqc?autoplay=1" => "Seting hak akses Pengeluaran logam mulia",
            "https://www.youtube.com/embed/I9bMaf2XT9I?autoplay=1" => "Request dan Otorisasi Pengeluaran logam mulia",
        ),
    ),
    "pettycash" => array(
        "671" => array(
            "https://www.youtube.com/embed/qIj63mk1Y-0?autoplay=1" => "Penambahan Plafon Pettycash",
            "https://www.youtube.com/embed/jbIPdulf5c0?autoplay=1" => "Input Klaim Pettycash Cabang, Otorisasi Klaim di Pusat dan Pengisian kembali Pettycash",

        ),
    ),
    //  master project
    "master_project" => array(
        "588" => array(
            "https://www.youtube.com/embed/4AUn8mrqGTs?autoplay=1" => "Setting Hak Akses Project",
            "https://www.youtube.com/embed/2mL6UIMVotM?autoplay=1" => "Input Quotation Project",
            "https://www.youtube.com/embed/xVq1mbT8MAI?autoplay=1" => "Membuat Rencana Anggaran (BOM)",
            //------
            "https://www.youtube.com/embed/yB7ZmWgZNWw?start=0&autoplay=1" => "Pembelian FG Project",
            "https://www.youtube.com/embed/yB7ZmWgZNWw?start=260&autoplay=1" => "Pindah Gudang dari Gudang Reguler ke Gudang Project DC",
            "https://www.youtube.com/embed/yB7ZmWgZNWw?start=437&autoplay=1" => "Distribusi ke WO/Pelaksana",
            "https://www.youtube.com/embed/yB7ZmWgZNWw?start=582&autoplay=1" => "Login ke Akun Pelaksana",
            "https://www.youtube.com/embed/6zfR_0s1NFA?start=582&autoplay=1" => "Penerbitan termin",
        ),
        "3463" => array(
            "https://www.youtube.com/embed/v8NVgEn--iw?autoplay=1" => "PO Jasa projek/ tambahan biaya projek",
        ),
    ),
    "taxes" => array(
        "110" => array(
            "https://www.youtube.com/embed/XuhUbzIZg54?autoplay=1" => "Perbedaan 2 searching",
            "https://www.youtube.com/embed/XpyBmDzC9x0?autoplay=1" => "Seting hak akses entry PPN Keluaran",
            "https://www.youtube.com/embed/3Ovvi0AaLrM?autoplay=1" => "Entry PPN Keluaran",
            "https://www.youtube.com/embed/OZ8No01P4d8?autoplay=1" => "Entry PPN Keluaran Gunggungan",
        ),
        "117" => array(
            "https://www.youtube.com/embed/uo9APbAybqM?autoplay=1" => "seting hak akses pph 25",
            "https://www.youtube.com/embed/jzOkhgRRmds?autoplay=1" => "Entry pph 25",
        ),
        "111" => array(
            "https://www.youtube.com/embed/cl0GBTvGrjA?autoplay=1" => "Input Faktur PPN Masukan Gabungan",
        ),
    ),
    "pendapatan" => array(
        "742" => array(
            "https://www.youtube.com/embed/WL0QnWQMMsw?autoplay=1" => "Seting hak akses pendapatan lain lain",
            "https://www.youtube.com/embed/9qU57xPJyW4?autoplay=1" => "Transaksi pendapatan lain lain",
        ),
    ),
    "pembatalan" => array(
        "9911" => array(
            "https://www.youtube.com/embed/zcbvp4gVmVs?autoplay=1" => "Cara Pembatalan Pembiayaan Supplies",
            "https://www.youtube.com/embed/L9QYn4b-m-E?autoplay=1" => "Cara Pembatalan Pembayaran Hutang Dagang (Pembelian Supplies)",
            "https://www.youtube.com/embed/l7f5Zzw77Z4?autoplay=1" => "Cara Pembatalan Pembelian Supplies",
            "https://www.youtube.com/embed/bT6Fdnu19l0?autoplay=1" => "Cara Pembatalan Otorisasi Return Pembelian",
            "https://www.youtube.com/embed/IZoA7r-jYIE?autoplay=1" => "Pembatalan SRN",
        ),
        "9912" => array(
            "https://www.youtube.com/embed/HrLapgG4q3A?autoplay=1" => "Cara Pembatalan Penerimaan Penjualan Tunai",
            "https://www.youtube.com/embed/jvGJx_0Lua8?autoplay=1" => "Cara Pembatalan Penerimaan Ulang Penjualan Tunai",
            "https://www.youtube.com/embed/t7z5iPxNkaw?autoplay=1" => "Cara Pembatalan Packinglist/Pengiriman (dari Penjualan Tunai) karena salsh konsumen dan Otomatis Pembatalan Penerimaan Penjualan Tunai",
        ),

    ),
    "kompensasiharga" => array(
        3333 => array(
            "https://www.youtube.com/embed/juiPtIdGCKU?autoplay=1" => "Realiasi diskon free produk(klaim diskon)",
            "https://www.youtube.com/embed/JtfuJ0Gtu54?autoplay=1" => "Cara Realisasi Klaim Ke Supplier (diterima kas)",
            "https://www.youtube.com/embed/j22bTbIX8IA?autoplay=1" => "Cara Realisasi Klaim Ke Supplier (diterima Credit Note)",
//            "https://www.youtube.com/embed/joH7eeb71Gk?autoplay=1" => "Cara Realisasi Klaim Ke Supplier (diterima Logam Mulia dan Permata) dan Pembayaran Hutang PPh23",

            "https://www.youtube.com/embed/h52talc_5GY?autoplay=1" => "Cara Realisasi Klaim Ke Supplier (diterima Logam Mulia dan Permata)",
            "https://www.youtube.com/embed/RMbrN-tmlPg?autoplay=1" => "Cara Setting Hak Akses Pengeluaran Logam Mulia dan permata",
            "https://www.youtube.com/embed/U2cboEvJKVc?autoplay=1" => "Cara Pengeluaran Logam Mulia dan Permata",

            "https://www.youtube.com/embed/Zx_qDmBJ0m4?autoplay=1" => "Cara Terima Hadiah dari Supplier (Barang, ada PPN)",
            "https://www.youtube.com/embed/tyGeQIthNp0?autoplay=1" => "Cara Distribusi Barang Hadiah dari DC/Pusat ke Cabang",
            "https://www.youtube.com/embed/x1NOHorU6M8?autoplay=1" => "Cara Input Cashback/Hadiah barang ke konsumen",

        ),

    ),
    "opname" => array(
        1119 => array(
            "https://www.youtube.com/embed/tjdZpzgzVwo?autoplay=1" => "Stok Opname",
            "https://www.youtube.com/embed/rQUrXWi6LN4?autoplay=1" => "Pemotongan File Opname",
        ),
        2229 => array(
            "https://www.youtube.com/embed/tjdZpzgzVwo?autoplay=1" => "Stok Opname",
            "https://www.youtube.com/embed/rQUrXWi6LN4?autoplay=1" => "Pemotongan File Opname",
        ),
        1118 => array(
            "https://www.youtube.com/embed/tjdZpzgzVwo?autoplay=1" => "Stok Opname",
            "https://www.youtube.com/embed/rQUrXWi6LN4?autoplay=1" => "Pemotongan File Opname",
        ),

    ),
    "valas" => array(
        "384" => array(
            "https://www.youtube.com/embed/o3QmxK8QDnY?autoplay=1" => "Hak akses Request dan Otorisasi Pembelian Valas/Mata Uang Asing",
            "https://www.youtube.com/embed/lw2BggmgQs0?autoplay=1" => "Input Request dan Otorisasi Pembelian Valas/Mata Uang Asing",
            "https://www.youtube.com/embed/LRi82bmx27Y?autoplay=1" => "Input Request Biaya Cashback Penjualan (Valas/Mata Uang Asing) di Cabang dengan freelancer non member/tidak terdaftar (auto otorisasi).",
        ),

    ),
    "saham" => array(
        "446" => array(
            "https://www.youtube.com/embed/nVPdqkjIZbs?autoplay=1" => "Input Data Pemegang Saham",
            "https://www.youtube.com/embed/_rkj3t0JEX4?autoplay=1" => "Setting Hak Akses Penambahan Hutang Ke Pemegang Saham (Request dan Otorisasi)",
            "https://www.youtube.com/embed/TZTUk8LHCGs?autoplay=1" => "Setting Hak Akses Pembayaran Hutang Ke Pemegang Saham",
            "https://www.youtube.com/embed/qBxUVu9ryOM?autoplay=1" => "Input Request Penambahan Hutang Ke Pemegang Saham dan Otorisasi Request Penambahan Hutang Ke Pemegang Saham",
            "https://www.youtube.com/embed/34qj48kGeIs?autoplay=1" => "Pembayaran Hutang Ke Pemegang Saham (Hutang Ke Pemegang Saham AP Payment)",

        ),
    ),
);

$config['modul_alias'] = array(
    "kompensasiharga" => "rebate supplier",
);


/* ----------------------------------------------------------
 * membentuk array untuk di view di home videos
 * ----------------------------------------------------------*/
foreach ($config['videos_top'] as $modul => $item_mdls) {
    foreach ($item_mdls as $ky => $item_mdl) {
        $modul_alias = isset($config['modul_alias'][$modul]) ? $config['modul_alias'][$modul] : $modul;
        $ky_upd = ($modul != "statik") ? $ky . "/" . $modul_alias : $ky;
        $moduls[$ky_upd] = $item_mdl;
    }
}
$config['videos'] = $moduls;