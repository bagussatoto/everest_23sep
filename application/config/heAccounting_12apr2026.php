<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 22/08/2018
 * Time: 16.02
 */


$config["decimalNumberPrecision"] = array(
    "main" => 24,
    "precision" => 17,
);

// menambah rekening di config account struktur
// harus menambah juga di $config['accountRekeningSort'], $config['categoryRL'] di bawah (untuk view ke rugilaba dan neraca)
$config['accountStructure'] = array(
    "aktiva" => array(
        "kas",
        "valas",
        "pettycash",
        "piutang cabang",
        "piutang ke pusat",
        "piutang biaya cabang",
        "piutang dagang",
        "piutang valas",//valas
        "piutang dagang jasa",
        "piutang pembelian",
        "piutang retensi",
        "piutang usaha belum realisasi lokal",
        "piutang usaha belum realisasi export",
        "piutang usaha belum realisasi project",
        "uang muka dibayar",
        "uang muka valas",
        "sewa dibayar dimuka",
        "piutang lain",
        "credit note",
        "persediaan produk",
        "persediaan produk riil",
//        "selisih persediaan karena fifo",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
//        "selisih pembulatan",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
//        "laba(rugi) selisih persediaan karena fifo", // return pembelian
//        "laba(rugi) selisih persediaan karena fifo distribusi",
//        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
//        "laba(rugi) selisih fifo pembatalan transaksi",
//        "laba(rugi) selisih fifo return pembelian",

//        "biaya bunga",

        "persediaan produk rakitan",
        "persediaan produk nonactive",
        "persediaan supplies",
        "persediaan supplies produksi",
        "persediaan supplies proses",
        "persediaan supplies riil",
        "aktiva tetap",
        "aktiva tetap tak berwujud",
        "piutang aktiva tetap cabang",
        "ppn in",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa",// ppn masukan (saat terjadi pembelian jasa)
        "ppn in realisasi",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa realisasi",// ppn masukan (saat terjadi pembelian jasa)
        "pib",// pajak import barang(setara ppn in)
        "pph22",// )
        "pph25",// )
        "pph 23 dibayar di muka",
        "pph 25 dibayar di muka",
        "pph22 dibayar dimuka",
        "ppn dibayar bendahara negara",
        "pph4 ayat 2",
        "pph29",
        "kendaraan",
        "peralatan",
        "peralatan kantor",
        "tanah dan bangunan",
        "mesin produksi",
        "mesin",
        "peralatan produksi",
        //------
        "mesin produksi jkt",
        "kendaraan jkt",
        "kendaraan solo",
        //------

//        "biaya import",
        //        "akum penyu aktiva tetap",

//        "direct labor",
//        "delivery cost",
//        "quality",
//        "overhead",

        "aktiva belum ditempatkan",
//        "biaya",
//        "biaya sewa",
        "deposit pajak",
//        "efisiensi cabang",
//        "efisiensi biaya",
        "projek cost",
    ),
    "hutang" => array(
        "hutang bank",
        "hutang dagang",
        "hutang ke pusat",
        "hutang ke cabang",
        "hutang jangka panjang",
        "hutang ppn",
        "hutang ke konsumen",
        "hutang valas ke konsumen",
        "hutang biaya",
        "hutang ongkir",
        "hutang biaya ke pusat",
        "hutang gaji",
        "hutang aktiva tetap",
        "hutang aktiva tetap pada dc",
        "ongkir",
        "hutang install",
        "ppn out",
        "ppn out sudah ada faktur",
        "hutang bpjs",
        "hutang pph21",
        "hutang pph23",
        "hutang pph29",
        "hutang pph4 ayat 2",
        "pph25_29",
        "hutang kontijensi biaya",
        "hutang lain ppv",
        "hutang lain ppv cabang",
        "hutang jangka panjang",
        "beban harus dibayar",
        "hutang uang muka",
        "hutang sewa",
        "hutang ke pemegang saham",
        "hutang ke pihak lain",
        "hutang biaya bunga",
//        "efisiensi biaya",
        "efisiensi ditempatkan pusat",
        "rekening koran",
        "non rekening koran",
        "hutang garansi",
    ),
    "modal" => array(
        "modal",
        "modal saham disetor",
        "laba ditahan",
        "laba",
        "rugi",
        "laba ditempatkan pusat",
        "laba ditempatkan pusatt",
    ),

    "penghasilan" => array(
        "penjualan",
        "penjualan jasa",
        "penjualan projek",
        "penjualan belum realisasi",
        "jasa kirim",
        "pendapatan",
        "pendapatan lain_lain",
        "pendapatan dari piutang dihapus",
        "laba(rugi) perubahan grade produk",
        "laba(rugi) perubahan grade supplies",
        "laba(rugi) opname produk",
        "laba(rugi) opname supplies",
        "laba(rugi) return produksi",
        "laba(rugi) selisih adjustment",
        "laba(rugi) selisih kurs",
        "rugi laba pembulatan ganjil",
        "efisiensi operasional",
        "efisiensi biaya",
        "efisiensi cabang",
        "selisih biaya produksi",
        "keutungan kurs",
        "transfer stok",
        "bunga dan jasa giro",
        "pendapatan lain-lain koreksi",

        "laba selisih kurs",
        "laba penjualan aset",
        "laba lain lain",
        "laba lain lain cabang",
        "rugilaba konversi valas",
        "laba efisiensi produksi",
    ),
    "penghasilan lain lain" => array(
        "penjualan valas",
    ),
    "biaya lain lain" => array(
        "harga perolehan valas",
        "rugi selisih kurs"
    ),
    "biaya" => array(
        "hpp",
        "hpp projek",

        "biaya",
        "biaya gaji",
        "biaya bpjs",
        "biaya pph21",
        "biaya umum",
        "biaya usaha",
        "biaya jasa",
        "biaya supplies",
        "biaya produksi",
        "biaya operasional",
        "biaya transfer",
        "biaya garansi",
        "biaya import",
        "biaya bunga",
        "biaya sewa",
        "kerugian",
        "return penjualan",

        "selisih persediaan karena fifo",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
        "selisih pembulatan",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
        "laba(rugi) selisih persediaan karena fifo", // return pembelian
        "laba(rugi) selisih persediaan karena fifo distribusi",
        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
        "laba(rugi) selisih fifo pembatalan transaksi",
        "laba(rugi) selisih fifo return pembelian",
//        "selisih biaya produksi",
        "efisiensi biaya",
//        "efisiensi cabang",

        "diskon",
        "kerugian kurs",
        "beban lain lain",
        "rugi piutang dihapus",

        //		"laba(rugi) selisih persediaan karena fifo produksi",
        //        "laba(rugi) perubahan grade produk",
        //		"laba(rugi) perubahan grade bahan",
        //        "ongkir dibayar konsumen",
        //        "ongkos install",

        "biaya kirim",
        "tenaga kerja",

//        "penyusutan",
        "penyusutan kendaraan",
        "penyusutan peralatan kantor",
        "penyusutan peralatan produksi",
        "penyusutan mesin produksi",
        "penyusutan mesin",
        "penyusutan tanah dan bangunan",

        "direct labor",
        "delivery cost",
        "quality",
        "overhead",
    ),

    "lain-lain-deb" => array(
        "alokasi biaya",
        //        "ppn in",// ppn masukan (saat terjadi pembelian)
        //        "ppn in jasa",// ppn masukan (saat terjadi pembelian jasa)
        //        "ongkir dibayar konsumen",
        //        "ongkos install",
    ),
    "lain-lain-kr" => array(
        //        "ppn out",// ppn keluaran (saat terjadi penjualan)
        "ongkir dibayar konsumen",
        "ongkos install",
        "akum penyu aktiva tetap",
        "akum penyu kendaraan",
        "akum penyu mesin",
        "akum penyu peralatan kantor",
        "akum penyu peralatan produksi",
        "akum penyu mesin produksi",
        "akum penyu tanah dan bangunan",
    ),
    "laba(rugi)" => array(
        "laba",
        "rugi",
        "rugilaba",
//        "laba lain lain",
        "rugi lain lain",
        "rugilaba lain lain",
//        "laba lain lain cabang",
        "labarugi kotor",
        "labarugi bersih",
    ),


);
$config['accountAlias'] = array(
    "kas" => "kas",
    //    "valas" => "valas",
    //    "pettycash" => "pettycash",
    "piutang cabang" => "piutang cabang",
    "piutang ke pusat" => "piutang ke pusat",
    "piutang biaya cabang" => "piutang biaya cabang",
    "piutang dagang" => "piutang usaha lokal",
    "piutang valas" => "piutang usaha ekspor",//valas
    "piutang dagang jasa" => "piutang usaha jasa",//valas
    //    "piutang pembelian" => "uang muka pembelian",
//    "piutang pembelian" => "pembelian dibayar  dimuka",
    "piutang pembelian" => "credit note (from return)",
    "piutang lain" => "piutang lain",

    //uang muka
//    "uang muka dibayar" => "uang muka dibayar",
    "uang muka dibayar" => "uang muka ke supplier",
    "uang muka valas" => "uang muka (valas) ke supplier",

    "credit note" => "credit note",
    "persediaan produk" => "persediaan barang jadi",
    "persediaan produk riil" => "persediaan barang jadi beli riil",
    "persediaan produk rakitan" => "persediaan barang jadi produksi",

    "persediaan supplies" => "persediaan bahan baku",
    "persediaan supplies proses" => "persediaan bahan baku dalam proses",
    "persediaan supplies riil" => "persediaan bahan baku riil",

    "aktiva tetap" => "aktiva tetap",
    "aktiva tetap tak berwujud" => "aktiva tetap tak berwujud",
    "ppn in" => "ppn masukan (belum ada faktur)",// ppn masukan (saat terjadi pembelian)
    "ppn in jasa" => "ppn masukan jasa (belum ada faktur)",// ppn masukan (saat terjadi pembelian jasa)
    "ppn in realisasi" => "ppn masukan (sudah ada faktur)",// ppn masukan (saat terjadi pembelian)
    "ppn in jasa realisasi" => "ppn masukan jasa (sudah ada faktur)",// ppn masukan (saat terjadi pembelian jasa)

    "hutang bank" => "hutang bank",
    "hutang dagang" => "hutang usaha",
    "hutang aktiva tetap" => "hutang aktiva tetap",
    "hutang ke pusat" => "hutang ke pusat",
    "hutang ke cabang" => "hutang ke cabang",
    "hutang jangka panjang" => "hutang jangka panjang",
    "hutang ppn" => "hutang ppn",
    "hutang biaya ke pusat" => "hutang biaya ke pusat",
    "hutang ke konsumen" => "hutang ke konsumen",
    "hutang valas ke konsumen" => "uang muka penjualan ekspor",
    "hutang ke pemegang saham" => "hutang ke pemegang saham",
    "hutang ke pihak lain" => "hutang ke pihak lain",
    //    "hutang biaya" => "",
    //    "hutang ongkir" => "",
    //    "ongkir" => "",
    //    "hutang install" => "",
    "ppn out" => "ppn keluaran",
    "pph25" => "pph ps.25",
    "pph4 ayat 2" => "pph ps.4(2)",
    "pph29" => "pph ps.29",
    "pph 23 dibayar di muka" => "pph 23 dibayar di muka",
    "hutang pph23" => "hutang pph 23",
    "hutang pph4 ayat 2" => "hutang pph ps.4(2)",
    "pph25_29" => "pph 25/29",
    "pph22 dibayar dimuka" => "pph 22 dibayar di muka",
    "ppn dibayar bendahara negara" => "ppn dibayar bendahara negara",
    //    "hutang kontijensi biaya" => "",
    "hutang lain ppv" => "hutang lain ppv",
    "hutang lain ppv cabang" => "hutang lain ppv cabang",
    //    "hutang jangka panjang" => "",
    "beban harus dibayar" => "beban harus dibayar",
    "rugi laba pembulatan ganjil" => "laba(rugi) pembulatan ganjil",

    "modal" => "modal",
    "modal saham disetor" => "modal saham disetor",
    "laba ditahan" => "laba ditahan",
    //    "laba" => "",
    //    "rugi" => "",
    "laba ditempatkan pusat" => "laba ditempatkan pusat",
    "laba ditempatkan pusatt" => "laba ditempatkan pusatt",

    "penjualan" => "penjualan",
    "penjualan jasa" => "penjualan jasa",
    "penjualan projek" => "penjualan project",
    //    "jasa kirim" => "",
    "pendapatan" => "pendapatan",
    "pendapatan lain_lain" => "pendapatan lain-lain",
    "laba(rugi) perubahan grade produk" => "laba(rugi) konversi produk",
    "laba(rugi) perubahan grade supplies" => "laba(rugi) konversi supplies",
    //    "laba(rugi) return produksi" => "",
    //    "efisiensi operasional" => "",
    //    "keutungan kurs" => "",
    //
    //    "penjualan valas" => "",

    "hpp" => "harga pokok penjualan",
    "hpp projek" => "harga pokok penjualan project",
    "projek cost" => "jasa pihak ke-3 project",
    "biaya" => "biaya belum dipindahkan",
    "biaya umum" => "beban umum",
    "biaya usaha" => "beban usaha",
    "biaya bunga" => "biaya bunga",
    //    "biaya jasa" => "",
    "biaya transfer" => "beban transfer",
    "biaya produksi" => "beban produksi",
    "biaya operasional" => "beban operasional",
    "kerugian" => "kerugian karena stok opname",
    "return penjualan" => "return penjualan",
    //    "laba(rugi) selisih persediaan karena fifo" => "", // return pembelian
    //    "laba(rugi) selisih persediaan karena fifo distribusi" => "",
    //    "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "",
    //    "laba(rugi) selisih fifo return pembelian" => "",
    "diskon" => "diskon",
    "keuntungan kurs" => "laba selisih kurs",
    "kerugian kurs" => "rugi selisih kurs",
    "beban lain lain" => "beban lain-lain",
    "transfer stok" => "transfer stok",

    //    "ongkir dibayar konsumen" => "",
    //    "ongkos install" => "",
    "akum penyu aktiva tetap" => "akumulasi penyusutan aktiva tetap",
    "akum penyu kendaraan" => "akumulasi penyusutan kendaraan",
    "akum penyu peralatan kantor" => "akumulasi penyusutan peralatan kantor",
    "akum penyu peralatan produksi" => "akumulasi penyusutan peralatan produksi",
    "akum penyu mesin produksi" => "akumulasi penyusutan mesin produksi",
    "akum penyu mesin" => "akumulasi penyusutan mesin",
    "akum penyu tanah dan bangunan" => "akumulasi penyusutan tanah dan bangunan",
    "bunga dan jasa giro" => "bunga dan jasa giro",
    "rugi selisih kurs" => "rugi selisih kurs",
    "pph22 " => "pph22",

    "rugilaba" => "laba(rugi)",
    "penghasilan" => "penghasilan",
//    "biaya" => "biaya",
    "piutang aktiva tetap cabang" => "piutang aktiva tetap(cab**)",
    "hutang aktiva tetap pada dc" => "hutang aktiva tetap pada dc",
    "penyusutan kendaraan" => "penyusutan kendaraan",
    "penyusutan alat kantor" => "penyusutan alat kantor",
    "penyusutan peralatan produksi" => "penyusutan peralatan produksi",
    "penyusutan sewa" => "penyusutan sewa",
    "penyusutan mesin produksi" => "penyusutan mesin produksi",
    "penyusutan mesin" => "penyusutan mesin",
    "penyusutan tanah dan bangunan" => "penyusutan anah dan bangunan",
    "biaya sewa " => "biaya sewa",
    "biaya import " => "biaya import",
    "hutang uang muka" => "hutang uang muka",
//    "uang muka dibayar" => "biaya sewa",
    "laba(rugi) selisih persediaan karena fifo" => "selisih persediaan karena fifo", // return pembelian
    "laba(rugi) selisih persediaan karena fifo distribusi" => "selisih persediaan karena fifo distribusi",
    "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "selisih persediaan karena fifo pemindahan dc",
    "laba(rugi) selisih fifo return pembelian" => "selisih fifo return pembelian",
    "efisiensi biaya" => "efisiensi produksi bom",
    "deposit pajak" => "deposit pajak",
    "rugi piutang dihapus" => "rugi karena penghapusan piutang",
    "pendapatan dari piutang dihapus" => "pendapatan dari piutang dihapus",
    "rugilaba konversi valas" => "laba(rugi) konversi valas",
    "hutang bpjs" => "hutang bpjs",
    "hutang garansi" => "hutang garansi",
    "biaya garansi" => "beban garansi",
    "selisih biaya produksi" => "selisih biaya produksi",
//    "laba efisiensi produksi" => "laba solo",
    "efisiensi cabang" => "efisiensi solo",
    "piutang retensi" => "piutang retensi",
    "laba(rugi) selisih kurs" => "laba(rugi) selisih kurs",
    "biaya bpjs" => "biaya bpjs",
    "biaya pph21" => "biaya pph21",
);
$config['categoryRL'] = array(
    1 => array(
        "penjualan" => "penjualan",
        "return penjualan" => "return penjualan",
//        "penjualan netto" => "penjualan netto",
        "penjualan belum realisasi" => "penjualan belum realisasi",

//        "penjualan projek" => "penjualan projek",
//        "transfer stok" => "transfer stok",
        "hpp" => "hpp",
        "hpp projek" => "hpp projek",
//        "projek cost" => "projek cost",
//        "selisih biaya produksi" => "selisih biaya produksi",
        "efisiensi biaya" => "efisiensi produksi bom",
    ),
    2 => array(
        "biaya" => "biaya",
        "biaya import" => "biaya import",
        "biaya bunga" => "biaya bunga",
        "biaya produksi" => "biaya produksi",
        "biaya umum" => "biaya umum",
        "biaya usaha" => "biaya usaha",
        "biaya gaji" => "biaya gaji",
        "biaya jasa" => "biaya jasa",
        "biaya sewa" => "biaya sewa",
        "biaya garansi" => "biaya garansi",
        "biaya bpjs" => "biaya bpjs",
        "biaya pph21" => "biaya pph21",
    ),
    3 => array(
//        "pendapatan" => "pendapatan",
        "pendapatan lain_lain" => "pendapatan lain_lain",
        "pendapatan dari piutang dihapus" => "pendapatan dari piutang dihapus",
        "pendapatan lain-lain koreksi" => "pendapatan lain-lain koreksi",
        "laba penjualan aset" => "laba penjualan aset",
        "bunga dan jasa giro" => "bunga dan jasa giro",
        "beban lain lain" => "beban lain lain",
        "biaya transfer" => "beban transfer",
        "jasa kirim" => "jasa kirim",

        "overhead" => "overhead",
        "direct labor" => "direct labor",
        "delivery cost" => "delivery cost",
        "quality" => "quality",

        "kerugian" => "kerugian",
        "laba lain lain" => "laba lain lain",
        "laba lain lain cabang" => "laba lain lain cabang",
        "kerugian kurs" => "kerugian kurs",
//        "rugi selisih kurs" => "rugi selisih kurs",
//        "laba selisih kurs" => "laba selisih kurs",
        "keutungan kurs" => "keuntungan kurs",
        "laba(rugi) selisih kurs" => "laba(rugi) selisih kurs",
        "rugilaba konversi valas" => "laba(rugi) konversi valas",
        //-----
        "selisih persediaan karena fifo" => "selisih persediaan karena fifo",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
        "selisih pembulatan" => "selisih pembulatan",//ini untuk selisih pembatalan maupun return yg melibatakan fifo
//        "laba(rugi) selisih persediaan karena fifo" => "laba(rugi) selisih persediaan karena fifo", // return pembelian
//        "laba(rugi) selisih persediaan karena fifo distribusi" => "laba(rugi) selisih persediaan karena fifo distribusi",
//        "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "laba(rugi) selisih persediaan karena fifo pemindahan dc",
//        "laba(rugi) selisih fifo pembatalan transaksi" => "laba(rugi) selisih fifo pembatalan transaksi",
        "laba(rugi) selisih fifo return pembelian" => "laba(rugi) selisih fifo return pembelian",
        //-----
        "laba(rugi) perubahan grade produk" => "laba(rugi) perubahan grade produk",
        "laba(rugi) perubahan grade supplies" => "laba(rugi) perubahan grade supplies",
        "laba(rugi) opname produk" => "laba(rugi) opname produk",
        "laba(rugi) opname supplies" => "laba(rugi) opname supplies",
//        "laba(rugi) return produksi" => "laba(rugi) return produksi",
        "laba(rugi) selisih adjustment" => "laba(rugi) selisih adjustment",
        "rugi laba pembulatan ganjil" => "laba(rugi) pembulatan ganjil",
        "rugi piutang dihapus" => "rugi karena penghapusan piutang",

//        "laba efisiensi produksi" => "laba solo",
        "efisiensi cabang" => "efisiensi solo",
    ),
    4 => array(
        "rugilaba" => "(rugi) laba",
    ),
);
$config['accountRekeningSort'] = array(
    // ===================================== /AKTIVA/ =============================================
    "aktiva" => array(
        "kas",
        "valas",
        "pettycash",

        "piutang dagang",
        "piutang retensi",
        "piutang valas",//valas
        "piutang cabang",
        "piutang ke pusat",
        "piutang pembelian",
        "piutang aktiva tetap cabang",
        "uang muka dibayar",
        "uang muka valas",
        "sewa dibayar dimuka",
        "piutang lain",
        "piutang biaya cabang",
        "piutang aktiva tetap cabang",
        "persediaan produk",
        "persediaan produk rakitan",
        "persediaan produk nonactive",
        "persediaan supplies",
        "persediaan supplies produksi",
        "persediaan supplies proses",

        "ppn in",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa",// ppn masukan (saat terjadi pembelian jasa)
        "ppn in realisasi",// ppn masukan (saat terjadi pembelian)
        "ppn in jasa realisasi",// ppn masukan (saat terjadi pembelian jasa)
        "ppn dibayar bendahara negara",
        "pib",// pajak import barang(setara ppn in)
        "pph22",// )
        "pph22 dibayar dimuka",
        "pph25",// )
        "pph29",
        "pph 23 dibayar di muka",
        "pph4 ayat 2",
        "credit note",
        "deposit pajak",

        "aktiva tetap",
        "akum penyu aktiva tetap",
        "akum penyu kendaraan",
        "akum penyu peralatan kantor",
        "akum penyu peralatan produksi",
        "akum penyu mesin produksi",
        "akum penyu mesin",
        "akum penyu tanah dan bangunan",

        "aktiva tetap tak berwujud",
        "aktiva belum ditempatkan",

        "kendaraan",
        "peralatan",
        "peralatan kantor",
        "tanah dan bangunan",
        "mesin produksi",
        "mesin",
        "peralatan produksi",
        "projek cost",
//        "biaya import",
//        "biaya sewa",
//        "biaya",
//        "biaya bunga",

//        "direct labor",
//        "delivery cost",
//        "quality",
//        "overhead",

//        "efisiensi cabang",
//        "laba(rugi) selisih persediaan karena fifo", // return pembelian
//        "laba(rugi) selisih persediaan karena fifo distribusi",
//        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
//        "laba(rugi) selisih fifo return pembelian",
    ),

    // ===================================== /PASIVA/ =============================================
    "hutang" => array(
        "hutang bank",
        "hutang dagang",
        "hutang ke pusat",
        "hutang ke cabang",
        "hutang ke konsumen",
        "hutang valas ke konsumen",
        "hutang biaya",
        "hutang ongkir",
        "hutang biaya ke pusat",
        "hutang gaji",
        "hutang aktiva tetap",
        "hutang aktiva tetap pada dc",
        "ongkir",
        "hutang install",
        "beban harus dibayar",

        "hutang ppn",
        "ppn out",
        "hutang pph21",
        "hutang pph23",
        "hutang pph4 ayat 2",
        "hutang bpjs",
        "pph_29",
        "pph25_29",


        "hutang kontijensi biaya",
        "hutang lain ppv",
        "hutang lain ppv cabang",
        "hutang jangka panjang",
        "hutang aktiva tetap pada dc",
        "hutang uang muka",
        "hutang sewa",
        "hutang ke pemegang saham",
        "hutang ke pihak lain",
        "hutang biaya bunga",
        "hutang garansi",
        "efisiensi ditempatkan pusat",
    ),

    // ===================================== /MODAL/ =============================================
    "modal" => array(
        "modal",
        "modal saham disetor",
        "laba",
        "rugi",
        "laba ditempatkan pusat",
        "laba ditempatkan pusatt",
        "laba ditahan",

    ),

    // ===================================== /PENGHASILAN/ ========================================
    "penghasilan" => array(
        "penjualan",
        "penjualan projek",
        //"pendapatan",
        "pendapatan lain_lain",
        "laba(rugi) perubahan grade produk",
        "laba(rugi) perubahan grade supplies",
        "laba(rugi) opname produk",
        "laba(rugi) opname supplies",
        "laba(rugi) return produksi",
        "laba(rugi) selisih kurs",
        "rugilaba konversi valas",
        "jasa kirim",
        "efisiensi operasional",
        "keutungan kurs",
        "transfer stok",

        "penjualan valas",
        "pendapatan dari piutang dihapus",
        "selisih biaya produksi",
        //"laba efisiensi produksi",
    ),

    // ===================================== /BIAYA/ =============================================
    "biaya" => array(
        "hpp",
        "hpp projek",
        "projek cost",
        "biaya",
        "biaya gaji",
        "biaya umum",
        "biaya usaha",
        "biaya jasa",
        "biaya transfer",
        "biaya supplies",
        "biaya produksi",
        "biaya operasional",
        "biaya garansi",
        "biaya bpjs",
        "biaya pph21",
        "biaya sewa",
        "biaya import",
        "biaya bunga",
        "efisiensi cabang",

        "kerugian",
        "return penjualan",
        "laba(rugi) selisih persediaan karena fifo", // return pembelian
        "laba(rugi) selisih persediaan karena fifo distribusi",
        "laba(rugi) selisih persediaan karena fifo pemindahan dc",
        "laba(rugi) selisih fifo return pembelian",
        "laba(rugi) selisih adjustment",
        "laba(rugi) karena koreksi hpp",
        "diskon",
        "kerugian kurs",
        "beban lain lain",
        "rugi piutang dihapus",
        "harga perolehan valas",

        "overhead" => "overhead",
        "direct labour" => "direct labour",
        "delivery cost" => "delivery cost",
        "quality" => "quality",

        "penyusutan kendaraan",
        "penyusutan peralatan kantor",
        "penyusutan peralatan produksi",
        "penyusutan mesin produksi",
        "penyusutan mesin",
        "penyusutan tanah dan bangunan",

//        "direct labor",
//        "delivery cost",
//        "quality",
//        "overhead",
    ),

    // ===================================== /laba(rugi)/ =============================================
    "laba(rugi)" => array(
        "laba",
        "rugi",
        "rugilaba",
        "laba lain lain",
        "laba lain lain cabang",
        "rugi lain lain",
        "rugilaba lain lain",
        "labarugi kotor",
        "labarugi bersih",
    ),

);
$config['categoryRLBottom'] = array(

    1 => "laba kotor",
    2 => "total biaya operasional",
    3 => "laba(rugi) lain-lain netto",
    4 => "laba(rugi) bersih netto",
);
$config['accountNetto'] = array(
    "penjualan" => "penjualan",
    "return penjualan" => "penjualan",
    "penjualan projek" => "penjualan",
    //----
    "hpp" => "hpp",
    "hpp projek" => "hpp",
);
$config['accountPersediaan'] = array(
    "persediaan produk" => "persediaan",
    "persediaan produk rakitan" => "persediaan",
    "persediaan supplies" => "persediaan",
    "persediaan supplies proses" => "persediaan",
);

//---------------------------------------------------
$config['accountTypes'] = array(
    "riil" => array(
        "aktiva",
        "hutang",
        "modal",
    ),
    "nominal" => array(
        "penghasilan",
        "biaya",
    ),
);
$config['accountBehavior'] = array(
    "aktiva" => array(
        "debet",
        "kredit",
    ),
    "hutang" => array(
        "kredit",
        "debet",
    ),
    "modal" => array(
        "kredit",
        "debet",
    ),
    "penghasilan" => array(
        "kredit",
        "debet",
    ),
    "penghasilan lain lain" => array(
        "kredit",
        "debet",
    ),
    "biaya" => array(
        "debet",
        "kredit",
    ),
    "biaya lain lain" => array(
        "debet",
        "kredit",
    ),
    "lain-lain-deb" => array(
        "debet",
        "kredit",
    ),
    "lain-lain-kr" => array(
        "kredit",
        "debet",
    ),
    "laba ditempatkan pusat(c)" => array(
        "kredit",
        "debet",
    ),
    "laba ditempatkan pusat(b)" => array(
        "debet",
        "kredit",
    ),
    "laba(rugi)" => array(
        "debet",
        "kredit",
        //        "debet",
    ),

    "kredit lain lain" => array(
        "kredit",
        "debet",
    ),
);
$config['accountBehaviorName'] = array();
$config['accountBehaviorPosition'] = array(
    "debit" => "debet",
    "kredit" => "kredit",
);
$config['accountMinusProtections'] = array(
    "kas",
    "persediaan",
);
$config['accountNeracaExceptions'] = array(
    //	"piutang cabang",
    "hutang ke pusat",
    //	"piutang biaya cabang",
    "hutang biaya ke pusat",
    "laba ditempatkan pusat",
);

$config['accountNeracaExceptions_cabang'] = array(
    "piutang cabang",
    "piutang biaya cabang",
    "laba ditempatkan pusatt",
);

//---------------------------------------------
$config['accountNeracaExceptions_konsolidasi'] = array(
    "piutang cabang",
    "hutang ke pusat",

    "piutang biaya cabang",
    "hutang biaya ke pusat",

    "piutang aktiva tetap cabang",
    "hutang aktiva tetap pada dc",

    "laba ditempatkan pusat",
    "laba ditempatkan pusatt",
);
$config['accountNeracaTipe_konsolidasi'] = array(
    "cost" => array(),
    "riil" => array(
        "hutang lain ppv" => ".0",
        "persediaan produk" => "persediaan produk-hutang lain ppv",
    ),
);
//---------------------------------------------
$config['accountLabarugiExceptions'] = array();
$config['accountLabarugiExceptionsPosition'] = array(
    "laba(rugi) selisih persediaan karena fifo pemindahan dc", // minus maka di debet, plus maka di kredit....
    "rugi laba pembulatan ganjil",
);
$config['accountLabarugiExceptionsPusat'] = array(
    "laba(rugi) selisih persediaan karena fifo distribusi",
    "return penjualan",
    "hpp",
);
$config['accountLabarugiExceptionsCabang'] = array(
    "laba(rugi) selisih persediaan karena fifo",
    "laba(rugi) selisih persediaan karena fifo pemindahan dc",
    "laba(rugi) selisih persediaan karena fifo produksi",
    "laba(rugi) perubahan grade bahan",
    "biaya kontainer",
);
$config['accountAliasExceptions'] = array(
    "ppn in" => "ppn masukan",
    "ppn out" => "ppn keluaran",

    "selisih persediaan karena fifo" => "laba(rugi) selisih persediaan karena fifo",
    "selisih pembulatan" => "selisih pembulatan",
    "selisih persediaan karena fifo distribusi" => "laba(rugi) selisih persediaan karena fifo distribusi",
    "selisih persediaan karena fifo pemindahan dc" => "laba(rugi) selisih persediaan karena fifo pemindahan dc",
    "selisih persediaan karena fifo produksi" => "laba(rugi) selisih persediaan karena fifo produksi",
    "perubahan grade bahan" => "laba(rugi) perubahan grade bahan",
    "perubahan grade produk" => "laba(rugi) perubahan grade produk",
    "perubahan grade supplies" => "laba(rugi) perubahan grade supplies",
);
$config['accountInverterCabang'] = array(
    "laba ditempatkan pusat",
);
$config['accountRekExceptionsCabang'] = array(
    "pettycash",
    "piutang cabang",
    "piutang biaya cabang",
    "persediaan bahan",
    "persediaan produk rakitan",
    "hutang dagang",
    "hutang pph",
    "hutang kontainer",
    "modal",
);
$config['accountRekExceptionsPusat'] = array(
    "piutang dagang",
    "hutang ke pusat",
    "hutang biaya ke pusat",
    "hutang ke konsumen",
    "laba ditahan dicabang",
);

$config['accountRekDetailAdditional'] = array(
    "aktiva tetap" => array(
        "akum penyu aktiva tetap" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu kendaraan" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu peralatan kantor" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu peralatan produksi" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu mesin produksi" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu mesin" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
        "akum penyu tanah dan bangunan" => array(
            "mainLink" => "Ledger/viewMoveDetails_1/Rekening/",
            "detailLink" => "Ledger/viewMoveDetails/",
        ),
    ),
);
$config['accountAkumulasiPenyusutan'] = array(
    "akum penyu aktiva tetap",
    "akum penyu kendaraan",
    "akum penyu peralatan kantor",
    "akum penyu peralatan produksi",
    "akum penyu mesin produksi",
    "akum penyu mesin",
    "akum penyu tanah dan bangunan",
);
$config['accountRekOppositeExceptions'] = array(
    "akum penyu aktiva tetap",
    "akum penyu kendaraan",
    "akum penyu peralatan kantor",
    "akum penyu peralatan produksi",
    "akum penyu mesin produksi",
    "akum penyu mesin",
    "akum penyu tanah dan bangunan",
    //versi coa
    "1020010020",//akum kendaraan
    "1020020020",//akum peralatan kantor
    "1020030020",//akum mesin
    "1020040020",//akum mesin produksi
    "1020041020",//akum perlatan produksi
    "1020050020",//akum bangunan
    "1020060020",//akum tanah
    "1020070020",//akum aset belum ditempatkan
    // "1020090020",//akum kendaraan jkt
    // "1020100020",//akum kendaraan solo
    // "1020110020",//akum  Mesin Produksi Jkt

);
$config['accountCatOppositeExceptions'] = array(
    "aktiva" => array(
        "akum penyu aktiva tetap",
        "akum penyu kendaraan",
        "akum penyu peralatan kantor",
        "akum penyu peralatan produksi",
        "akum penyu mesin produksi",
        "akum penyu mesin",
        "akum penyu tanah dan bangunan",
        //versi coa
        "1020010020",//akum kendaraan
        "1020020020",//akum peralatan kantor
        "1020030020",//akum mesin
        "1020040020",//akum mesin produksi
        "1020041020",//akum perlatan produksi
        "1020050020",//akum bangunan
        "1020060020",//akum tanah
        "1020070020",//akum aset belum ditempatkan
    ),
);
$config['accountRekForeverExceptions'] = array(
    "laba ditahan",
);
$config['accountCatExceptions'] = array(
    "penghasilan",
    "penghasilan lain lain",
    "biaya lain lain",
    "biaya",
);

// ini rekening pembantu level 1
$config['accountChilds'] = array(
    "1010010010" => "RekeningPembantuKas",//kas
    "1010010030" => "RekeningPembantuCreditNote",//piutang pembelian / credit note
    "1010010040" => "RekeningPembantuKas",//pettycash
    "1010010020" => "RekeningPembantuValas",//valas
    "1010030030" => "RekeningPembantuProduk",//persediaan produk
    "1010030070" => "RekeningPembantuProduk",//persediaan produk rakitan
    "1010030010" => "RekeningPembantuSupplies",//persediaan supplies
    "2020020" => "RekeningPembantuBank",//hutang bank
    "2010010" => "RekeningPembantuSupplier",//hutang dagang
    "hutang uang muka" => "RekeningPembantuSupplier",
    "1010040050" => "RekeningPembantuSupplier",//ppn in
    "1010040070" => "RekeningPembantuSupplier",//ppn in jasa
    "1010020030" => "RekeningPembantuPiutangSupplierMain",//piutang pembelian
    "1010020010" => "RekeningPembantuCustomer",//piutang dagang
    "1010020070" => "RekeningPembantuCustomerLain",//piutang lain
    "1010020060" => "RekeningPembantuCustomer",//piutang retensi
    "1010020090" => "RekeningPembantuCustomer",//piutang dagang
    "1010025010" => "RekeningPembantuLogamMulia",//logam mulia
    "1010020050" => "RekeningPembantuCustomer",//piutang dagang jasa
    "1010020080" => "RekeningPembantuCustomer",//piutang dagang project
    "piutang lain" => "RekeningPembantuPiutangLain",
    "1010050010" => "RekeningPembantuUangMuka",//uang muka dibayar
//    "1010050010" => "RekeningPembantuUangMukaMain",//uang muka dibayar
    "1010050020" => "RekeningPembantuUangMukaMain",//uang muka valas
    "1010050040" => "RekeningPembantuUangMuka",//uang muka no ppn, no relasi
//    "1010050040" => "RekeningPembantuUangMukaMain",//uang muka no ppn, no relasi
    "sewa dibayar dimuka" => "RekeningPembantuSewa",
    "2010050" => "RekeningPembantuCustomer",//hutang ke konsumen
//    "2010050" => "RekeningPembantuCustomerDetail",//hutang ke konsumen
//    "2010060" => "RekeningPembantuCustomer",//hutang ke konsumen
    "2010100" => "RekeningPembantuCustomerValas",//hutang valas ke konsumen
    "efisiensi operasional" => "RekeningPembantuEfisiensi",
    "1010060010" => "RekeningPembantuAntarcabang",//piutang cabang
    "1010060040" => "RekeningPembantuAntarcabang",//piutang biaya cabang
    "2040010" => "RekeningPembantuAntarcabang",//hutang ke pusat
    "2010040" => "RekeningPembantuSupplier",//hutang biaya
    "2010020" => "RekeningPembantuSupplier",//hutang sewa
    "3010020" => "RekeningPembantuModal",//hutang modal
    "2020010" => "RekeningPembantuHutangSaham",//hutang sewa
    "2010090020" => "RekeningPembantuBiayaHarusDibayar",//hutangbiaya harusdibayar
//    "1010050030" => "RekeningPembantuUangMukaMain",
    "1010050030" => "RekeningPembantuUangMuka",
    "aktiva tetap" => "RekeningPembantuAktivaTetap",
    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",//pindah semebntara
    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetap",
    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "akum penyu mesin" => "RekeningPembantuAkumPenyusutanMesin",
//    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetapAdjust",
//    "aktiva tetap tak berwujud" => "RekeningPembantuAktivaTetapTakBerwujud",
    "piutang valas" => "RekeningPembantuCustomerValas",
    "biaya operasional" => "RekeningPembantuBiayaOperasional",
    "modal" => "RekeningPembantuModal",
    "hutang jangka panjang" => "RekeningPembantuHutangJangkaPanjang",
    "biaya bunga" => "RekeningPembantuLoanItem",

    "biaya" => "RekeningPembantuBiaya",
    "biaya import" => "RekeningPembantuBiayaImport",
    "biaya umum" => "RekeningPembantuBiayaUmum",
    "6030" => "RekeningPembantuBiayaUmum",
    "biaya usaha" => "RekeningPembantuBiayaUsaha",
    "6010" => "RekeningPembantuBiayaUsaha",
//    "6010" => "RekeningPembantuBiayaUsahaMain",
    "biaya produksi" => "RekeningPembantuBiayaProduksi",
    "beban harus dibayar" => "RekeningPembantuBebanHarusDibayar",
    "pendapatan" => "RekeningPembantuPendapatan",
    "laba ditahan" => "RekeningPembantuLabaDitahan",
    "beban lain lain" => "RekeningPembantuBebanLainLain",
    "hutang gaji" => "RekeningPembantuAntarcabang",
    "2040020" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
    "2040030" => "RekeningPembantuAntarcabang",//hutang aktiva tetap pada dc
    "2010030" => "RekeningPembantuSupplier",//hutang aktiva tetap
    "piutang aktiva tetap cabang" => "RekeningPembantuAntarcabang",
//    "penyusutan" => "RekeningPembantuDepresiasi",
    "penyusutan kendaraan" => "RekeningPembantuDepresiasi",//p
    "penyusutan peralatan kantor" => "RekeningPembantuDepresiasi",//p
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "penyusutan mesin produksi" => "RekeningPembantuDepresiasi",
    "penyusutan mesin" => "RekeningPembantuDepresiasi",
    "penyusutan bangunan" => "RekeningPembantuDepresiasi",
    "perlengkapan umum" => "RekeningPembantuDepresiasi",
    "penyusutan tanah dan bangunan" => "RekeningPembantuDepresiasi",

    "6040010" => "RekeningPembantuDepresiasi",
    "6040020" => "RekeningPembantuDepresiasi",
    "6040030" => "RekeningPembantuDepresiasi",
    "6040040" => "RekeningPembantuDepresiasi",
    "6040050" => "RekeningPembantuDepresiasi",
    "6040060" => "RekeningPembantuDepresiasi",


    "overhead" => "RekeningPembantuBiayaKomposisiProduksi",
    "5020030" => "RekeningPembantuBiayaKomposisiProduksi",//direct labor
    "5020020" => "RekeningPembantuBiayaKomposisiProduksi",//delivery cost
    "5020040" => "RekeningPembantuBiayaKomposisiProduksi",//quality
    "5020050" => "RekeningPembantuBiayaKomposisiProduksi",//bahan baku
    "7010150" => "RekeningPembantuLRLainlainDetail",//pendapatan lain lain
    "7010170" => "RekeningPembantuPendapatanItem",//laba lain lain
    // "7010150" => "RekeningPembantuLRLainlain",//laba lain lain unutk builder auto adjustment jadi digeser ke detail


//    "overhead" => "RekeningPembantuEfisiensiBiaya",

    "3020010" => "RekeningPembantuEfisiensiBiayaMain",
    //"3020010010" => "RekeningPembantuEfisiensiBiaya",
    "3020010020" => "RekeningPembantuEfisiensiBiaya",
    "3020010030" => "RekeningPembantuEfisiensiBiaya",

    "pph25" => "RekeningPembantuPph",
    "pph4 ayat 2" => "RekeningPembantuPph",
    "hutang ke pemegang saham" => "RekeningPembantuHutangSaham",
    "hutang ke pihak lain" => "RekeningPembantuHutangPihakLain",
    "hutang biaya bunga" => "RekeningPembantuHutangBiayaBunga",
    "hutang pph23" => "RekeningPembantuPph",
//    "hutang pph4 ayat 2" => "RekeningPembantuPph",
    "efisiensi biaya" => "RekeningPembantuEfisiensiBiayaMain",
    "hutang lain ppv cabang" => "RekeningPembantuAntarcabang",
    // "rugilaba lain lain" => "RekeningPembantuLRLainlain",
//    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",
    "laba lain lain" => "RekeningPembantuLRLainlain",
    //---
    "4010" => "RekeningPembantuPenjualan",
    "5010" => "RekeningPembantuHpp",
    "6050" => "RekeningPembantuBiayaGaji",
    "6080" => "RekeningPembantuBiayaBpjs",
    "6090" => "RekeningPembantuBiayaPph21",
    "6100010" => "RekeningPembantuBiaya",
    "6100020" => "RekeningPembantuBiayaImport",
    "2010090030" => "RekeningPembantuHutangDevidenItem",
    //akum penyusutan
    "1020010020" => "RekeningPembantuAkumPenyusutanKendaraan",
    "1020020020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "1020040020" => "RekeningPembantuAkumPenyusutanMesinProduksi",
    "1020030020" => "RekeningPembantuAkumPenyusutanMesin",
    "1020041020" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "1020050020" => "RekeningPembantuAkumPenyusutanBangunan",

    "1020010010" => "RekeningPembantuAktivaBerwujud",
    "1020020010" => "RekeningPembantuAktivaBerwujud",
    "1020040010" => "RekeningPembantuAktivaBerwujud",
    "1020030010" => "RekeningPembantuAktivaBerwujud",
    "1020041010" => "RekeningPembantuAktivaBerwujud",
    "1020050010" => "RekeningPembantuAktivaBerwujud",


    "8040" => "RekeningPembantuSupplier",//diskon
    "8050" => "RekeningPembantuSupplier",//cadangan diskon

    "2010080" => "RekeningPembantuAntarcabang",//hutang gaji
    "2010120" => "RekeningPembantuKomisi",//hutang komisi
    "2030010" => "RekeningPembantuPphMain",//hutang pph 21

    "1010070030" => "RekeningPembantuCustomer",
    "4030" => "RekeningPembantuPenjualan",
);
$config['accountChildsItems'] = array(
    "1010010010" => "RekeningPembantuKasItem",//kas
    "1010010040" => "RekeningPembantuKasItem",//pettycash
    "1010010020" => "RekeningPembantuValasItem",//valas
    "1010030030" => "RekeningPembantuProduk",//persediaan produk
    "1010030070" => "RekeningPembantuProduk",//persediaan produk rakitan
    "1010030010" => "RekeningPembantuSupplies",//persediaan supplies
    "2020020" => "RekeningPembantuBank",//hutang bank
    "2010010" => "RekeningPembantuSupplierItem",//hutang dagang
    "hutang uang muka" => "RekeningPembantuSupplierItem",
    "1010040050" => "RekeningPembantuSupplierItem",//ppn in
    "1010040070" => "RekeningPembantuSupplierItem",//ppn in jasa
    "1010020030" => "RekeningPembantuPiutangSupplierMain",//piutang pembelian
    "1010020010" => "RekeningPembantuCustomerItem",//piutang dagang
    "1010020060" => "RekeningPembantuCustomerItem",//piutang retensi
    "1010020050" => "RekeningPembantuCustomerItem",//piutang dagang jasa
    "piutang lain" => "RekeningPembantuPiutangLain",
    "1010050010" => "RekeningPembantuUangMuka",//uang muka dibayar
    "1010050030" => "RekeningPembantuUangMuka",//uang muka dibayar
    "1010050040" => "RekeningPembantuUangMuka",//uang muka dibayar no ppn, no relasi
    "1010050020" => "RekeningPembantuUangMukaMain",//uang muka valas
    "sewa dibayar dimuka" => "RekeningPembantuSewa",
    "2010050" => "RekeningPembantuCustomerItem",//hutang ke konsumen
//    "2010060" => "RekeningPembantuCustomerItem",//hutang ke konsumen
    "2010100" => "RekeningPembantuCustomerValasItem",//hutang valas ke konsumen
    "efisiensi operasional" => "RekeningPembantuEfisiensi",
    "1010060010" => "RekeningPembantuAntarcabangItem",//piutang cabang
    "1010060040" => "RekeningPembantuAntarcabangItem",//piutang biaya cabang
    "2040010" => "RekeningPembantuAntarcabangItem",//hutang ke pusat
    "2010040" => "RekeningPembantuSupplierItem",//hutang biaya
    "2010020" => "RekeningPembantuSupplierItem",//hutang sewa
    "2020010" => "RekeningPembantuHutangSahamItem",//hutang saham
    "3010020" => "RekeningPembantuModalItem",//hutang saham
    "1020010010" => "RekeningPembantuAktivaBerwujud",//kendaraan
    "1020020010" => "RekeningPembantuAktivaBerwujud",//peralatan kantor
    "1020050010" => "RekeningPembantuAktivaBerwujud",//bangunan
    "aktiva tetap" => "RekeningPembantuAktivaTetap",
    "1020070010" => "RekeningPembantuAktivaBelumDitempatkan",//pindah semebntara
    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetap",
    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "akum penyu mesin" => "RekeningPembantuAkumPenyusutanMesin",
//    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetapAdjust",
//    "aktiva tetap tak berwujud" => "RekeningPembantuAktivaTetapTakBerwujud",
    "piutang valas" => "RekeningPembantuCustomerValas",
    "biaya operasional" => "RekeningPembantuBiayaOperasional",
    "modal" => "RekeningPembantuModal",
    "hutang jangka panjang" => "RekeningPembantuHutangJangkaPanjang",
    "biaya bunga" => "RekeningPembantuLoanItem",

    "biaya" => "RekeningPembantuBiaya",
    "biaya import" => "RekeningPembantuBiayaImport",
    "biaya umum" => "RekeningPembantuBiayaUmum",
    "6030" => "RekeningPembantuBiayaUmum",
    "biaya usaha" => "RekeningPembantuBiayaUsaha",
    "6010" => "RekeningPembantuBiayaUsaha",
    "biaya produksi" => "RekeningPembantuBiayaProduksi",
    "6020" => "RekeningPembantuBiayaProduksi",
    "beban harus dibayar" => "RekeningPembantuBebanHarusDibayar",
    "pendapatan" => "RekeningPembantuPendapatan",
    "laba ditahan" => "RekeningPembantuLabaDitahan",
    "beban lain lain" => "RekeningPembantuBebanLainLain",
    "hutang gaji" => "RekeningPembantuAntarcabang",
    "2040020" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
    "2040030" => "RekeningPembantuAntarcabang",//hutang aktiva tetap pada dc
    "2010030" => "RekeningPembantuSupplierItem",//hutang aktiva tetap
    "piutang aktiva tetap cabang" => "RekeningPembantuAntarcabang",
//    "penyusutan" => "RekeningPembantuDepresiasi",
    "penyusutan kendaraan" => "RekeningPembantuDepresiasi",//p
    "penyusutan peralatan kantor" => "RekeningPembantuDepresiasi",//p
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "penyusutan mesin produksi" => "RekeningPembantuDepresiasi",
    "penyusutan mesin" => "RekeningPembantuDepresiasi",
    "penyusutan bangunan" => "RekeningPembantuDepresiasi",
    "perlengkapan umum" => "RekeningPembantuDepresiasi",
    "penyusutan tanah dan bangunan" => "RekeningPembantuDepresiasi",

    "6040010" => "RekeningPembantuDepresiasi",
    "6040020" => "RekeningPembantuDepresiasi",
    "6040030" => "RekeningPembantuDepresiasi",
    "6040040" => "RekeningPembantuDepresiasi",
    "6040050" => "RekeningPembantuDepresiasi",
    "6040060" => "RekeningPembantuDepresiasi",


    "overhead" => "RekeningPembantuBiayaKomposisiProduksi",
    "5020030" => "RekeningPembantuBiayaKomposisiProduksi",//direct labor
    "5020020" => "RekeningPembantuBiayaKomposisiProduksi",//delivery cost
    "5020040" => "RekeningPembantuBiayaKomposisiProduksi",//quality
    "5020050" => "RekeningPembantuBiayaKomposisiProduksi",//bahan baku
    "7010150" => "RekeningPembantuLRLainlainDetail",//pendapatan lain lain
    "7010170" => "RekeningPembantuPendapatanItem",//laba lain lain
    // "7010150" => "RekeningPembantuLRLainlain",//laba lain lain unutk builder auto adjustment jadi digeser ke detail


//    "overhead" => "RekeningPembantuEfisiensiBiaya",

    "3020010" => "RekeningPembantuEfisiensiBiayaMain",
    "3020010020" => "RekeningPembantuEfisiensiBiaya",
    "3020010030" => "RekeningPembantuEfisiensiBiaya",

    "pph25" => "RekeningPembantuPph",
    "pph4 ayat 2" => "RekeningPembantuPph",
    "hutang ke pemegang saham" => "RekeningPembantuHutangSaham",
    "hutang ke pihak lain" => "RekeningPembantuHutangPihakLain",
    "hutang biaya bunga" => "RekeningPembantuHutangBiayaBunga",
    "hutang pph23" => "RekeningPembantuPph",
//    "hutang pph4 ayat 2" => "RekeningPembantuPph",
    "efisiensi biaya" => "RekeningPembantuEfisiensiBiayaMain",
    "hutang lain ppv cabang" => "RekeningPembantuAntarcabang",
    // "rugilaba lain lain" => "RekeningPembantuLRLainlain",
//    "aktiva belum ditempatkan" => "RekeningPembantuAktivaBelumDitempatkan",
    "laba lain lain" => "RekeningPembantuLRLainlain",
    //---
    "4010" => "RekeningPembantuPenjualanItem",
    "5010" => "RekeningPembantuHppItem",
    "6050" => "RekeningPembantuBiayaGajiItem",
    "6080" => "RekeningPembantuBiayaBpjsItem",
    "6090" => "RekeningPembantuBiayaPph21Item",
    "6100010" => "RekeningPembantuBiaya",
    "6100020" => "RekeningPembantuBiayaImport",

    "8040" => "RekeningPembantuSupplier",//diskon
    "8050" => "RekeningPembantuSupplier",//cadangan diskon
    "2010120" => "RekeningPembantuKomisi",//hutang komisi
);
$config['accountSubChilds__'] = array(
    "kendaraan" => "RekeningPembantuAktivaBerwujud",
    "peralatan" => "RekeningPembantuAktivaBerwujud",
    "peralatan kantor" => "RekeningPembantuAktivaBerwujud",
    "tanah dan bangunan" => "RekeningPembantuAktivaBerwujud",
    "mesin produksi" => "RekeningPembantuAktivaBerwujud",
    "peralatan produksi" => "RekeningPembantuAktivaBerwujud",
    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "akum penyu mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",
    "akum penyu mesin" => "RekeningPembantuAkumPenyusutanMesin",
    "hutang bank" => "RekeningPembantuRelasiRekeningKoran",
    //---------------------------------------
    "2010050" => "RekeningPembantuCustomerDetail",//hutang ke konsumen
    "1010020030" => "RekeningPembantuPiutangSupplierDetailMain",//piutang pembelian
);
$config['accountSubChilds'] = array(
//    "kendaraan" => "RekeningPembantuAktivaBerwujud",
//    "peralatan" => "RekeningPembantuAktivaBerwujud",
//    "peralatan kantor" => "RekeningPembantuAktivaBerwujud",
//    "tanah dan bangunan" => "RekeningPembantuAktivaBerwujud",
//    "mesin produksi" => "RekeningPembantuAktivaBerwujud",
//    "peralatan produksi" => "RekeningPembantuAktivaBerwujud",
//
//    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
//    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
//    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
//    "akum penyu mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",
//
//    "hutang bank" => "RekeningPembantuRelasiRekeningKoran",
    // "1010030" => "RekeningPembantuProduk",//persediaan

    "1010010" => "RekeningPembantuKas",//kas
    "1010010010" => "RekeningPembantuKas",//kas
    "1010010020" => "RekeningPembantuValas",//valas
    "1010010030" => "RekeningPembantuSupplier",//piutang pembelian / credit note
    "1010010040" => "RekeningPembantuKas",//pettycash
    "1010020010" => "RekeningPembantuCustomer",//piutang dagang lokal
    "1010020070" => "RekeningPembantuCustomerLain",//piutang dagang lokal
    "1010020060" => "RekeningPembantuCustomer",//piutang retensi
    "1010025010" => "RekeningPembantuLogamMulia",//logam mulia
    "1010020020" => "RekeningPembantuCustomer",//piutang dagang eksport
    "1010020030" => "RekeningPembantuPiutangSupplierDetailItem",///klaim hadiah
    "1010020040" => "RekeningPembantuSupplier",//piutang usaha jasa
    "1010020050" => "RekeningPembantuCustomer",//piutang retensi
//    "1010020060" => "RekeningPembantuSupplier",//piutang pembelian, cn vendor

    "1010030010" => "RekeningPembantuSupplies",//persediaan supplies
    "1010030020" => "RekeningPembantuSuppliesRiil",//persediaan supplies riil
    "1010030" => "RekeningPembantuProduk",//persediaan produk
    // "1010030030" => "RekeningPembantuProduk",//persediaan produk
    "1010030040" => "RekeningPembantuProdukRiil",//persediaan produk riil
//    "1010030050" => "RekeningPembantuProdukRiil",//persediaan produk riil
//    "1010030060" => "RekeningPembantuProdukRiil",//persediaan produk riil
//    "1010030070" => "RekeningPembantuProdukRiil",//persediaan produk riil


    "1010050010" => "RekeningPembantuUangMukaMainReference",    // uang muka dibayar ke vendor RekeningPembantuSupplier
    "1010050020" => "RekeningPembantuUangMukaMainReference",    // uang muka dibayar ke vendor RekeningPembantuSupplier
    "1010050030" => "RekeningPembantuUangMukaMainReference",    // uang muka dibayar ke vendor RekeningPembantuSupplier
//    "1010050040" => "RekeningPembantuUangMukaMainReference",    // uang muka dibayar ke vendor no ppn, no relasi

    "1010060010" => "RekeningPembantuAntarcabang",//piutang cabang
    "1010060020" => "RekeningPembantuAntarcabang",//piutang aktiva tetap
    "1010060030" => "RekeningPembantuAntarcabang",//piutang ke pusat
    "1010060040" => "RekeningPembantuAntarcabang",//piutang biaya cabang


    "2010010" => "RekeningPembantuSupplier",//hutang dagang
    "2010020" => "RekeningPembantuSupplier",//hutang sewa
    "2010030" => "RekeningPembantuSupplier",//hutang aktiva tetap
    "2010040" => "RekeningPembantuSupplier",//hutang biaya
    "2010050" => "RekeningPembantuCustomerDetail",//hutang ke konsumen, dipakai oleh PreRekeningValueDetail saat penjualan 03 februari 2024
    "2010060" => "RekeningPembantuSupplier",//hutang bpjs
    "2010070" => "RekeningPembantuSupplier",//hutang biaya bunga
    "2010080" => "RekeningPembantuSupplier",//hutang gaji
    "2010090" => "RekeningPembantuSupplier",//hutang lancar lainnya
    "2010100" => "RekeningPembantuCustomer",//hutang valas ke konsumen
    "2010110" => "RekeningPembantuCustomer",//hutang jasa ke konsumen
    "2010090020" => "RekeningPembantuBiayaHarusDibayar",//hutang harusdibayar


    "01040100005" => "RekeningPembantuSupplier", // ppn masukan belum ada faktur
    "01040100006" => "RekeningPembantuSupplier", // ppn masukan sudah ada faktur
    "010405" => "RekeningPembantuSupplier",//piutang pembelian
    "020201" => "RekeningPembantuHutangSaham",//hutang ke pemegang saham
    "020401" => "RekeningPembantuAntarcabang",//hutang ke pusat


//    "2020010" => "RekeningPembantuAntarcabang",//hutang ke pemegang saham
    "2020020" => "RekeningPembantuBank",//hutang bank
    "2020030" => "RekeningPembantuBank",//hutang ke pihak laiin


    "2040010" => "RekeningPembantuAntarcabang",//hutang ke pusat
    "2040020" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
    "2040030" => "RekeningPembantuAntarcabang",//hutang aktiva tetap ke dc
    "2040040" => "RekeningPembantuAntarcabang",//hutang ke cabang


    "3010010" => "RekeningPembantuModal",//modal saham disetor


    "6010" => "RekeningPembantuBiayaUsaha",//biaya usaha
    "6020" => "RekeningPembantuBiayaProduksi",//biaya produksi
    "6030" => "RekeningPembantuBiayaUmum",//biaya umum
//    "6040" => "RekeningPembantuBiayaUmum",//biaya penyusutan
//    "6050" => "RekeningPembantuBiayaUmum",//biaya gaji
//    "6060" => "RekeningPembantuBiayaUmum",//biaya bunga
//    "6070" => "RekeningPembantuBiayaUmum",//biaya transfer
//    "6080" => "RekeningPembantuBiayaUmum",//biaya bpjs
//    "6090" => "RekeningPembantuBiayaUmum",//biaya pph 21
//    "6100" => "RekeningPembantuBiayaUmum",//biaya

//akumpenyusutan
    "1020010020" => "RekeningPembantuAkumPenyusutanKendaraan",
    "1020020020" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
    "1020040020" => "RekeningPembantuAkumPenyusutanMesinProduksi",
    "1020030020" => "RekeningPembantuAkumPenyusutanMesin",
    "1020041020" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
    "1020050020" => "RekeningPembantuAkumPenyusutanBangunan",

);
// rekening pembantu level 3 (key berupa rekening)
$config['accountSuperSubChilds'] = array(
    "kendaraan" => "RekeningPembantuAktivaBerwujud",
    "peralatan" => "RekeningPembantuAktivaBerwujud",
    "peralatan kantor" => "RekeningPembantuAktivaBerwujud",
    "tanah dan bangunan" => "RekeningPembantuAktivaBerwujud",
    "mesin produksi" => "RekeningPembantuAktivaBerwujud",
    "mesin" => "RekeningPembantuAktivaBerwujud",
    "peralatan produksi" => "RekeningPembantuAktivaBerwujud",

//    "akum penyu kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
//    "akum penyu peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
//    "akum penyu peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
//    "akum penyu mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",

    "rekening koran" => "RekeningPembantuRekeningKoran",
    "non rekening koran" => "RekeningPembantuRekeningKoran",
//    "uang muka valas" => "RekeningPembantuUangMukaExternMain",
    "1010050010" => "RekeningPembantuUangMukaMainReference",
    "1010050030" => "RekeningPembantuUangMukaMainReference",

);
// rekening pembantu level 3 (key pembantu berupa non rekening)
$config['accountSuperSubChildsNonRekening'] = array(
    "uang muka valas" => "RekeningPembantuUangMukaExternMain",
);


$config['accountChildItems'] = array(

    "kas" => "RekeningPembantuKasItem",
    "valas" => "RekeningPembantuValasItem",
    "persediaan produk" => "RekeningPembantuProduk",
    "persediaan supplies" => "RekeningPembantuSupplies",
    "hutang dagang" => "RekeningPembantuSupplierItem",
    "ppn in" => "RekeningPembantuSupplierItem",
    "ppn in jasa" => "RekeningPembantuSupplierItem",
    "piutang pembelian" => "RekeningPembantuSupplierItem",
    "piutang dagang" => "RekeningPembantuCustomerItem",
    "hutang ke konsumen" => "RekeningPembantuCustomerItem",
//    "ppn out" => "RekeningPembantuCustomerItem",
    "hutang ke pihak lain" => "RekeningPembantuHutangPihak3Item",
    "efisiensi operasional" => "RekeningPembantuEfisiensi",
    "piutang cabang" => "RekeningPembantuAntarcabangItem",
    "piutang ke pusat" => "RekeningPembantuAntarcabangItem",
    "hutang ke pusat" => "RekeningPembantuAntarcabangItem",
    "hutang ke cabang" => "RekeningPembantuAntarcabangItem",
    "hutang biaya" => "RekeningPembantuSupplierItem",
    "hutang uang muka" => "RekeningPembantuSupplier",
    "hutang sewa" => "RekeningPembantuSupplier",
    "piutang valas" => "RekeningPembantuCustomerValasItem",
    "biaya umum" => "RekeningPembantuBiayaUmum",
    "biaya produksi" => "RekeningPembantuBiayaProduksi",
    "biaya usaha" => "RekeningPembantuBiayaUsaha",
//    "aktiva tetap" => "RekeningPembantuAktivaTetap",
    "aktiva tetap" => "RekeningPembantuAsetBerwujud",
    "piutang lain" => "RekeningPembantuPiutangLainItem",
    "akum penyu aktiva tetap" => "RekeningPembantuAkumPenyusutanAktivaTetapItem",
    "modal" => "RekeningPembantuModalItem",
    "beban harus dibayar" => "RekeningPembantuBebanHarusDibayarItem",
    "pendapatan" => "RekeningPembantuPendapatanItem",
    "laba ditahan" => "RekeningPembantuLabaDitahanItem",
    "beban lain lain" => "RekeningPembantuBebanLainLainItem",
//    "penyusutan" => "RekeningPembantuDepresiasiItem",
    "penyusutan kendaraan" => "RekeningPembantuDepresiasiItem",
    "penyusutan peralatan kantor" => "RekeningPembantuDepresiasiItem",
    "penyusutan peralatan produksi" => "RekeningPembantuDepresiasiItem",
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "penyusutan mesin produksi" => "RekeningPembantuDepresiasiItem",
    "penyusutan mesin" => "RekeningPembantuDepresiasiItem",
    "penyusutan tanah dan bangunan" => "RekeningPembantuDepresiasiItem",

    "akum penyu kendaraan" => "RekeningPembantuDepresiasiItem",
    "akum penyu peralatan kantor" => "RekeningPembantuDepresiasiItem",
    "akum penyu peralatan produksi" => "RekeningPembantuDepresiasiItem",
    "akum penyu mesin produksi" => "RekeningPembantuDepresiasiItem",
    "akum penyu mesin" => "RekeningPembantuDepresiasiItem",
    "akum penyu tanah dan bangunan" => "RekeningPembantuDepresiasiItem",

    "biaya import" => "RekeningPembantuBiayaImport",
    "biaya sewa" => "RekeningPembantuBiayaSewa",
    "efisiensi biaya" => "RekeningPembantuEfisiensiBiayaMain",
    "rugilaba lain lain" => "RekeningPembantuLRLainlain",
//    "kendaraan" =>"RekeningPembantuAsetBerwujud",
);

$config['accountChildSources'] = array(
    "kas" => "MdlBankAccount",
    "valas" => "MdlCurrency",
    "1010030030" => "MdlProduk2",
    "1010030070" => "MdlProdukRakitan",
    "1010030010" => "MdlSupplies",
    "hutang dagang" => "MdlSupplier",
    "ppn in" => "MdlSupplier",
    "ppn in jasa" => "MdlSupplier",
    "piutang pembelian" => "MdlSupplier",
    "piutang dagang" => "MdlCustomer",
    "hutang ke konsumen" => "MdlCustomer",
    "ppn out" => "MdlCustomer",
    "efisiensi operasional" => "MdlProduk",
    "piutang cabang" => "MdlCabang",
    "piutang ke pusat" => "MdlCabang",
    "hutang ke pusat" => "MdlCabang",
    "hutang ke cabang" => "MdlCabang",
    "hutang biaya" => "MdlSupplier",
    "piutang valas" => "MdlCustomer",
    "aktiva tetap" => "MdlAktivaTetap",
    "aktiva belum ditempatkan" => "MdlFolderAset",
    "akum penyu aktiva tetap" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu peralatan kantor" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu peralatan produksi" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu mesin produksi" => "MdlDtaAkumPenyusutanAktivaTetap",
    "akum penyu mesin" => "MdlDtaAkumPenyusutanAktivaTetap",
    "aktiva tetap tak berwujud" => "MdlDtaAktivaTakBerwujud",
    "biaya operasional" => "MdlDtaBiayaOperasional",
    "modal" => "MdlDtaModal",
    //    "hutang jangka panjang"        => "MdlDtaHutangJangkaPanjang",
    "piutang lain" => "MdlDtaPerson2",
    "biaya" => "MdlExpense",
    "biaya umum" => "MdlDtaBiayaUmum",
    "biaya produksi" => "MdlDtaBiayaProduksi",
    "biaya usaha" => "MdlDtaBiayaUsaha",
    "hutang jangka panjang" => "MdlDtaSupplier2",
    "beban harus dibayar" => "MdlDtaSupplier2",
    "pendapatan" => "MdlDtaSubPendapatan",
    "laba ditahan" => "MdlDtaLabaDitahan",
    "beban lain lain" => "MdlDtaBebanLainLain",
    "uang muka dibayar" => "MdlUangMuka",
    "sewa dibayar dimuka" => "MdlFolderSewa",
    "biaya sewa" => "MdlDtaBiayaSewa",
    "biaya import" => "MdlExpense",
    "hutang bank" => "MdlRekeningKoran",
    "hutang ke pemegang saham" => "MdlDtaModal",
    "hutang ke pihak lain" => "MdlDtaHutangPihak3",
    "hutang biaya bunga" => "MdlDtaModal",
    "hutang pph23" => "MdlDtaModal",
    "hutang pph4 ayat 2" => "MdlDtaModal",
    "efisiensi biaya" => "MdlProdukRakitanPreBiaya",
);

$config['accountChildsLinks'] = array(
    "piutang dagang" => "Ledger/viewMoveDetails/RekeningPembantuCustomer/piutang%20dagang",
    "hutang dagang" => "Ledger/viewMoveDetails/RekeningPembantuSupplier/hutang%20dagang",
    "kas" => "Ledger/viewMoveDetails/RekeningPembantuKas/kas",

    //    "valas" => "valas",
    //    "pettycash" => "pettycash",
    // "piutang cabang" => "piutang cabang",
    // "piutang biaya cabang" => "piutang biaya cabang",
    // "piutang dagang" => "piutang usaha lokal",
    // "piutang valas" => "piutang usaha ekspor",//valas
    //    "piutang pembelian" => "uang muka pembelian",
    // "piutang pembelian" => "pembelian dibayar  dimuka",
    // "piutang lain" => "piutang lain",
    // "credit note" => "credit note",
    // "persediaan produk" => "persediaan barang jadi beli",
    // "persediaan produk rakitan" => "persediaan barang jadi produksi",
    //    "persediaan produk nonactive" => "",
    // "persediaan supplies" => "persediaan bahan baku",
    //    "persediaan supplies produksi" => "",
    // "aktiva tetap" => "aktiva tetap",
    // "aktiva tetap tak berwujud" => "aktiva tetap tak berwujud",
    // "ppn in" => "ppn masukan",
    // ppn masukan (saat terjadi pembelian)
    //    "ppn in jasa" => "",// ppn masukan (saat terjadi pembelian jasa)
    //    "aktiva tetap" => "",
    //

    // "hutang dagang" => "hutang usaha",
    // "hutang ke pusat" => "hutang ke pusat",
    // "hutang jangka panjang" => "hutang jangka panjang",
    // "hutang ppn" => "hutang ppn",
    // "hutang biaya ke pusat" => "hutang biaya ke pusat",
    // "hutang ke konsumen" => "uang muka penjualan lokal",
    // "hutang valas ke konsumen" => "uang muka penjualan ekspor",
    //    "hutang biaya" => "",
    //    "hutang ongkir" => "",
    //    "ongkir" => "",
    //    "hutang install" => "",
    // "ppn out" => "ppn keluaran",
    // "pph25_29" => "pph ps.25/29",
    //    "hutang kontijensi biaya" => "",
    // "hutang lain ppv" => "hutang lain ppv",
    //    "hutang jangka panjang" => "",
    // "beban harus dibayar" => "beban harus dibayar",

    // "modal" => "modal",
    // "modal saham disetor" => "modal saham disetor",
    // "laba ditahan" => "laba ditahan",
    //    "laba" => "",
    //    "rugi" => "",
    // "laba ditempatkan pusat" => "laba ditempatkan pusat",
    // "laba ditempatkan pusatt" => "laba ditempatkan pusatt",

    // "penjualan" => "penjualan",
    //    "jasa kirim" => "",
    // "pendapatan" => "pendapatan",
    // "pendapatan lain_lain" => "pendapatan lain-lain",
    // "laba(rugi) perubahan grade produk" => "(rugi)laba konversi",
    //    "laba(rugi) return produksi" => "",
    //    "efisiensi operasional" => "",
    //    "keutungan kurs" => "",
    //
    //    "penjualan valas" => "",

    // "hpp" => "harga pokok penjualan",
    //    "biaya" => "",
    // "biaya umum" => "beban umum",
    // "biaya usaha" => "beban usaha",
    //    "biaya jasa" => "",
    //    "biaya supplies" => "",
    // "biaya produksi" => "beban produksi",
    // "biaya operasional" => "beban operasional",
    //    "kerugian" => "",
    // "return penjualan" => "return penjualan",
    //    "laba(rugi) selisih persediaan karena fifo" => "", // return pembelian
    //    "laba(rugi) selisih persediaan karena fifo distribusi" => "",
    //    "laba(rugi) selisih persediaan karena fifo pemindahan dc" => "",
    //    "laba(rugi) selisih fifo return pembelian" => "",
    // "diskon" => "diskon",
    // "keuntungan kurs" => "laba selisih kurs",
    // "kerugian kurs" => "rugi selisih kurs",
    // "beban lain lain" => "beban lain-lain",
    // "transfer stok" => "transfer stok",

    //    "ongkir dibayar konsumen" => "",
    //    "ongkos install" => "",
    // "akum penyu aktiva tetap" => "akumulasi penyusutan aktiva tetap",

    //    "laba" => "",
    //    "rugi" => "",
    //    "laba lain lain" => "",
    //    "rugi lain lain" => "",
    //    "rugilaba lain lain" => "",
    //    "labarugi kotor" => "",
    //    "labarugi bersih" => "",
    // "rugilaba" => "laba(rugi)",
    // "penghasilan" => "penghasilan",
    // "biaya" => "biaya",
);

$config['accountChilds2'] = array(
    "kas" => array(
        "RekeningPembantuKas",
    ),
    "pettycash" => array(
        "RekeningPembantuKas",
    ),
    "valas" => array(
        "RekeningPembantuValas",
    ),
    "persediaan produk" => array(
        "RekeningPembantuProduk",
    ),
    "persediaan supplies" => array(
        "RekeningPembantuSupplies",
    ),
    "hutang dagang" => array(
        "RekeningPembantuSupplier",
    ),
    "ppn in" => array(
        "RekeningPembantuSupplier",
    ),
    "piutang pembelian" => array(
        "RekeningPembantuSupplier",
    ),
    "piutang dagang" => array(
        "RekeningPembantuCustomer",
    ),
    "piutang lain" => array(
        "RekeningPembantuPiutangLain",
    ),
    "hutang ke konsumen" => array(
        "RekeningPembantuCustomer",
    ),
//    "ppn out" => array(
//        "RekeningPembantuCustomer",
//    ),
    "hutang valas ke konsumen" => array(
        "RekeningPembantuCustomerValas",
    ),
    "efisiensi operasional" => array(
        "RekeningPembantuEfisiensi",
    ),
    "piutang cabang" => array(
        "RekeningPembantuAntarcabang",
    ),
    "hutang ke pusat" => array(
        "RekeningPembantuAntarcabang",
    ),
    "hutang biaya" => array(
        "RekeningPembantuSupplier",
        "RekeningPembantuAntarcabang",
    ),
    "aktiva tetap" => array(
        "RekeningPembantuAktivaTetap",
    ),
    "akum penyu aktiva tetap" => array(
        "RekeningPembantuAkumPenyusutanAktivaTetap",
    ),
    "aktiva tetap tak berwujud" => array(
        "RekeningPembantuAktivaTetapTakBerwujud",
    ),
    "piutang valas" => array(
        "RekeningPembantuCustomerValas",
    ),
    "biaya operasional" => array(
        "RekeningPembantuBiayaOperasional",
    ),
    "modal" => array(
        "RekeningPembantuModal",
    ),
    "hutang jangka panjang" => array(
        "RekeningPembantuHutangJangkaPanjang",
    ),

    "biaya" => array(
        "RekeningPembantuBiaya",
    ),
    "biaya umum" => array(
        "RekeningPembantuBiayaUmum",
    ),
    "biaya usaha" => array(
        "RekeningPembantuBiayaUsaha",
    ),
    "biaya produksi" => array(
        "RekeningPembantuBiayaProduksi",
    ),
    "beban harus dibayar" => array(
        "RekeningPembantuBebanHarusDibayar",
    ),
    "pendapatan" => array(
        "RekeningPembantuPendapatan",
    ),
//    "laba ditahan" => array(
//"RekeningPembantuLabaDitahan",),
//    "beban lain lain" => array(
//"RekeningPembantuBebanLainLain",),
    "hutang gaji" => array(
        "RekeningPembantuAntarcabang",
    ),
    "hutang biaya ke pusat" => array(
        "RekeningPembantuAntarcabang",
    ),
    "piutang biaya cabang" => array(
        "RekeningPembantuAntarcabang",
    ),

    "overhead" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "direct labor" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "delivery cost" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "quality" => array(
        "RekeningPembantuBiayaKomposisiProduksi",
    ),
    "aktiva belum ditempatkan" => array(
        "MdlFolderAset"
    ),
);


//===config untuk saldo rekening
$config['accountBalanceColumns'] = array(
    "RekeningPembantuKas" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "header" => array(
            "debet" => "balance (IDR)",
        ),
    ),
    "RekeningPembantuPendapatan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "customer name",
            "extern2_nama" => "valas name",
            "qty_kredit" => "kredit (valas)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (valas)",
            "debet" => "debet (valas)",
        ),
        "header" => array(
            "debet" => "balance (IDR)",
        ),
    ),
    "Rekening" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuSupplier" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "periode" => "bulanan"
    ),
    "RekeningPembantuLogamMulia" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "qty_kredit" => "kredit (qty)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (qty)",
            "debet" => "debet (qty)",
        ),
        "pairedModel" => array(
            "mdlName" => "MdlDtaLogamMulia",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(//                "jenis in ('item', 'item_rakitan', 'item_komposit')",
            ),
            "fieldName" => array(
                // "id" => "pID",
//                "kode" => "kode",
                "nama" => "nama",
                "satuan_nama" => "satuan_nama",
//                "jenis" => "jenis",
//                "status" => "status",
//                "trash" => "trash",
//                "kategori_id" => "kategori_id",
//                "kategori_nama" => "kategori_nama",
//                "tipe_produk" => "jml_serial",
                "size_nama" => "satuan_nama",
//                "merek_nama" => "merek_nama",
            ),
            "viewedColumns" => array(
//                "kode" => "product code",
//                "merek_nama" => "merek",
//                "kategori_nama" => "category",
//                "tipe_produk" => "tipe produk",
//                "satuan" => "uom",
            ),
//            "jenisItems" => array(
//                "item" => "Produk",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
//            ),
            "linkData_history" => "Data/viewHistories/",
        ),
    ),
    "RekeningPembantuPiutangSupplierMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPiutangSupplierDetailMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuBank" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomerLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomer" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomerDetail" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAntarcabang" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "periode" => "bulanan"
    ),
    "RekeningPembantuBiayaHarusDibayar" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuProduk" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            //            "kode" => "product code",
            "extern_nama" => "item name",
            //			"qty_kredit"  => "balance (qty)",
            //            "kredit"      => "balance (IDR)",
            "qty_kredit" => "kredit (qty)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (qty)",
            "debet" => "debet (qty)",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
        "pairedModel" => array(
            "mdlName" => "MdlProduk2",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(
                "jenis in ('item', 'item_rakitan', 'item_komposit')",
            ),
            "fieldName" => array(
                // "id" => "pID",
                "kode" => "kode",
                "extern_nama" => "nama",
                "jenis" => "jenis",
                "status" => "status",
                "trash" => "trash",
                "kategori_id" => "kategori_id",
                "kategori_nama" => "kategori_nama",
                "tipe_produk" => "jml_serial",
                "size_nama" => "size_nama",
                "merek_nama" => "merek_nama",
            ),
            "viewedColumns" => array(
                "kode" => "product code",
                "merek_nama" => "merek",
                "kategori_nama" => "category",
                "tipe_produk" => "tipe produk",
            ),
            "jenisItems" => array(
                "item" => "Produk",
                "item_rakitan" => "ProdukRakitan",
                "item_komposit" => "ProdukKomposit",
            ),
            "linkData_history" => "Data/viewHistories/",
        ),

        "additionalPairedModel" => array(
            "mdlNameRek" => "ComRekeningPembantuProduk",
            "mdlMethodRek" => "fetchBalances",
            "mdlMethodRek_moves" => "fetchMoves2_periode",
            "prefix" => "ng_",

            "mdlNameData" => "MdlGudang",
            "mdlMethodData" => "lookupAll",
        ),
        "additionalViewedColumns" => array(
//            "ng_qty_debet" => "qty not good<br>(qty)",
//            "ng_debet" => "balance not good<br>(IDR)",
            "ng_qty_debet" => "gudang project<br>(qty)",
            "ng_debet" => "gudang project<br>(IDR)",
        ),
        "additionalTotalViewedColumns" => array(
            "total_qty_debet" => "total qty<br>(qty)",
            "total_debet" => "total balance<br>(IDR)",
        ),
        "additionalPairSerialViewedColums" => array(
            "jumlah_serial" => "stok serial",
//            "total_debet" => "total balance<br>(IDR)",
        ),
        "additionalPairSerial" => array(
            "mdlSparator" => "Coms",
            "mdlMethod" => "fetchBalances",
            "rekening" => "1010030030",
            "mdlName" => "ComRekeningPembantuProdukPerSerial",
            "mdlName2" => "ComRekeningPembantuProdukPerSerialIntransit",
            "ctrlMethode" => "viewSerial",
            "viewedColumns" => array(
//                "extern2_nama"=>"serial",
                "jml_serial" => "serial",
                "jml_serial_transit" => "serial transit",
            ),
            "filter" => array(
                "qty_debet>0",
            ),
        ),

        "customLink" => array(
            "qty_debet", "debet", "ng_debet", "ng_qty_debet"
        ),
        "additionalPairedWo" => array(
            "mdlNameRek" => "ComRekeningPembantuProduk",
            "mdlMethodRek" => "fetchBalances",
//            "prefix" => "wo_",

            "mdlNameData" => "MdlTasklistProject",
            "mdlMethodData" => "lookupAll",
        ),
    ),
    "RekeningPembantuCustomerValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "customer name",
            "extern2_nama" => "valas name",
            //			"qty_kredit"  => "balance (qty)",
            //            "kredit"      => "balance (IDR)",
            "qty_kredit" => "kredit (valas)",
            "kredit" => "kredit (IDR)",
            "qty_debet" => "debet (valas)",
            "debet" => "debet (valas)",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuSupplies" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
        "mdlData" => "MdlSupplies",
        "mdlDataKeys" => array(
            "id", "satuan", "nama"
        ),
        "pairedModel" => array(
            "mdlName" => "MdlSupplies",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(
                "jenis in ('item')",
            ),
            "fieldName" => array(
                // "id" => "pID",
                "kode" => "kode",
                "extern_nama" => "nama",
                "jenis" => "jenis",
                "status" => "status",
                "trash" => "trash",
            ),
//            "viewedColumns" => array(
//                "kode" => "product code",
//            ),
            "jenisItems" => array(
                "item" => "Supplies",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
            ),
            "linkData_history" => "Data/viewHistories/",
        ),
        "additionalPairedModel" => array(
            "mdlNameRek" => "ComRekeningPembantuSupplies",
            "mdlMethodRek" => "fetchBalances",
            "mdlMethodRek_moves" => "fetchMoves2_periode",
            "prefix" => "ng_",

            "mdlNameData" => "MdlGudang",
            "mdlMethodData" => "lookupAll",
        ),
        "additionalViewedColumns" => array(
//            "ng_qty_debet" => "qty not good<br>(qty)",
//            "ng_debet" => "balance not good<br>(IDR)",
            "ng_qty_debet" => "gudang project<br>(qty)",
            "ng_debet" => "gudang project<br>(IDR)",
        ),
    ),
    "RekeningPembantuEfisiensi" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
    ),

    "RekeningPembantuAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumnsStatus" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",

            "debet" => "aktiva (IDR)",
            "kredit" => "akumulasi depresiasi (IDR)",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaUmum" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
        "periode" => "bulanan"
    ),
    "RekeningPembantuPiutangLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuModal" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiaya" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBebanLainLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaUsaha" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
        "periode" => "bulanan"
    ),
    "RekeningPembantuHutangAktivaTetapDc" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaKomposisiProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuEfisiensiBiayaMain" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAktivaBerwujud" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuDepresiasi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuUangMuka" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuUangMukaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuUangMukaExternMain" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern2_id" => "account ID",
            "extern2_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
            "qty_kredit" => "kredit",
            "qty_debet" => "debet",
        ),
        "viewed2Columns" => array(
            "extern2_id" => "account ID",
            "extern2_nama" => "account name",
//            "kredit" => "kredit",
//            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuBiayaImport" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),
    "RekeningPembantuAktivaBelumDitempatkan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(//            "gudang_id=gudang_id",
        ),
    ),

    "RekeningPembantuAkumPenyusutanAktivaTetapAdjust" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanKendaraan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "pairedModel" => array(
            "mdlName" => "MdlAsetDetail",
            "mdlMethod" => "lookupAll",
            "key" => "id",
            "filters" => array(
                "jenis in ('item')",
            ),
            "fieldName" => array(
                // "id" => "pID",
                "kode" => "kode",
                "extern_nama" => "nama",
                "serial_no" => "serial_no",
                "status" => "status",
                "trash" => "trash",
//                "kategori_id" => "kategori_id",
//                "kategori_nama" => "kategori_nama",
//                "tipe_produk" => "jml_serial",
//                "size_nama" => "size_nama",
//                "merek_nama" => "merek_nama",
            ),
            "viewedColumns" => array(
                "kode" => "kode*",
                "merek_nama" => "merek",
                "serial_no" => "no seri",
//                "tipe_produk" => "tipe produk",
            ),
            "jenisItems" => array(
                "item" => "AsetDetail",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
            ),
            "linkData_history" => "Data/viewHistories/",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanBangunan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),

    "RekeningPembantuHutangSaham" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangPihakLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangBiayaBunga" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLoanItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPphMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuEfisiensiBiayaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLRLainlain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCreditNote" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),

    "RekeningPembantuRelasiRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLabaDitahan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuPiutangSupplierDetailItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),

    "RekeningPembantuUangMukaMainReference" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern2_nama" => "account name",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "viewFilters" => array(
            "debet>.0",
        ),
    ),

);

$config['accountBalanceProfiles'] = array(
    "RekeningPembantuProduk" => array(
        "showValue" => true,
        "showQty" => true,
        // ===== QUERY CONFIG =====
        'base' => array(
            'from' => 'produk p',
            'joins' => array(
                array(
                    "(
                    SELECT
                        extern_id,
                        periode,
                        rekening,
                        cabang_id,
                        gudang_id,
                        MAX(extern_nama) AS extern_nama,
                        
                        /* KOLOM ASLI (utama) */
                        SUM(
                            CASE
                                WHEN cabang_id = :cabang_id AND gudang_id = :gudang_id THEN qty_debet
                                ELSE 0
                            END
                        ) AS qty_debet,
                        SUM(
                            CASE
                                WHEN cabang_id = :cabang_id AND gudang_id = :gudang_id THEN debet
                                ELSE 0
                            END
                        ) AS debet,

                        /* NG: gudang normal (1..1000) */
                        SUM(CASE WHEN gudang_id > 0 AND gudang_id < 1000 THEN qty_debet ELSE 0 END) AS ng_qty_debet,
                        SUM(CASE WHEN gudang_id > 0 AND gudang_id < 1000 THEN debet     ELSE 0 END) AS ng_debet,

                        /* NG2: gudang khusus (>1000) */
                        SUM(CASE WHEN gudang_id > 1000 THEN qty_debet ELSE 0 END) AS ng2_qty_debet,
                        SUM(CASE WHEN gudang_id > 1000 THEN debet     ELSE 0 END) AS ng2_debet,

                        MAX(dtime) AS dtime
                    FROM _rek_pembantu_produk_cache
                    WHERE
                        periode  = :periode
                        AND rekening = :rekening
                        AND cabang_id = :cabang_id
                    GROUP BY extern_id
                ) r_ppc",
                    "r_ppc.extern_id = p.id",
                    "left",
                    false
                ),
            ),
            'where' => array(
                "r_ppc.periode" => ":periode",   // runtime
                "r_ppc.rekening" => ":rekening",  // runtime
            ),
        ),
        'modules' => array(
//            'sp' => array(
//                'enabled' => true,
//                'select' => array(
//                    'IFNULL(sp.qty_debet,0):ng_qty_debet',
//                    'IFNULL(sp.debet,0):ng_debet',
//                    '(r_ppc.qty_debet + IFNULL(sp.qty_debet,0)):total_qty_debet',
//                    '(r_ppc.debet + IFNULL(sp.debet,0)):total_debet',
//                ),
//                'join' => array(
//                    'type'  => 'left',
//                    'on'    => 'sp.extern_id = r_ppc.extern_id AND sp.cabang_id = r_ppc.cabang_id',
//                    'sql'   => "(SELECT extern_id, cabang_id,
//                            SUM(qty_debet) ng_qty_debet,
//                            SUM(debet) ng_debet
//                      FROM _rek_pembantu_produk_cache
//                      WHERE gudang_id > 0
//                      GROUP BY extern_id) sp"
//                ),
//            ),
//            'sa' => array(
//                'enabled' => true,
//                'select' => array(
//                    'IFNULL(sa.cnt,0):serial_available_count',
//                    "CASE WHEN IFNULL(sa.cnt,0) > 0 THEN 1 ELSE 0 END:is_serial"
//                ),
//                'join' => array(
//                    'type'  => 'left',
//                    'on'    => 'sa.produk_id=p.id AND sa.cabang_id=r_ppc.cabang_id AND sa.gudang_id=r_ppc.gudang_id',
//                    'sql'   => "(SELECT produk_id, cabang_id, gudang_id, COUNT(*) cnt
//                      FROM _rek_pembantu_produk_perserial_cache
//                      WHERE qty_debet > 0
//                      GROUP BY produk_id) sa"
//                ),
//            ),
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => null),
            'gudang_id' => array('type' => 'int', 'default' => null),

            // toggle kolom
            'show_qty' => array('type' => 'bool', 'default' => true),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY (satu sumber untuk header + DT) =====
        'columns' => array(
            // key = field di result row
            'pId' => array(
                'select' => 'p.id:pId',
                'search' => 'p.id',
                'order' => 'p.id',
            ),
            'status' => array(
                'select' => 'p.status:status',
                'search' => 'p.status',
                'order' => 'p.status',
            ),
            'kode' => array(
                'select' => 'p.kode:kode',
                'search' => 'p.kode',
                'order' => 'p.kode',
            ),
            'jenis' => array(
                'select' => 'p.jenis:jenis',
                'search' => 'p.jenis',
                'order' => 'p.jenis',
            ),
            'merek_nama' => array(
                'select' => 'p.merek_nama:merek_nama',
                'search' => 'p.merek_nama',
                'order' => 'p.merek_nama',
            ),
            'kategori_nama' => array(
                'select' => 'p.kategori_nama:kategori_nama',
                'search' => 'p.kategori_nama',
                'order' => 'p.kategori_nama',
            ),
            'tipe_produk' => array(
                'select' => 'p.jml_serial:jml_serial',
                'search' => 'p.jml_serial',
                'order' => 'p.jml_serial',
            ),
            'extern_nama' => array(
                'select' => 'p.nama:extern_nama',
                'search' => 'p.nama',
                'order' => 'p.nama',
            ),
            'extern_id' => array(
                'select' => 'r_ppc.extern_id:extern_id',
                'search' => 'r_ppc.extern_id',
                'order' => 'r_ppc.extern_id',
            ),
            'rekening' => array(
                'select' => 'r_ppc.rekening:rekening',
                'search' => 'r_ppc.rekening',
                'order' => 'r_ppc.rekening',
            ),
            'gudang_id' => array(
                'select' => 'r_ppc.gudang_id:gudang_id',
                'search' => 'r_ppc.gudang_id',
                'order' => 'r_ppc.gudang_id',
            ),
            'size_nama' => array(
                'select' => 'p.size_nama:size_nama',
                'search' => 'p.size_nama',
                'order' => 'p.size_nama',
            ),

            // qty group (bisa dimatikan)
            'qty_debet' => array(
                'select' => 'r_ppc.qty_debet:qty_debet',
                'order' => 'r_ppc.qty_debet',
                'flag' => 'qty',
            ),
            'ng_qty_debet' => array(
                'select' => 'r_ppc.ng_qty_debet:ng_qty_debet',
                'order' => 'r_ppc.ng_qty_debet',
                'flag' => 'qty',
            ),
            'wo_qty_debet' => array(
                'select' => 'r_ppc.ng2_qty_debet:wo_qty_debet',
                'order' => 'r_ppc.ng2_qty_debet',
                'flag' => 'qty',
            ),
//            'qty_kredit' => array(
//                'label'=>'Qty Kredit',
//                'select'=>'r_ppc.qty_kredit:qty_kredit',
//                'order'=>'r_ppc.qty_kredit',
//                'flag'=>'qty',
//            ),

            // rupiah group (bisa dimatikan)
            'debet' => array(
                'select' => 'r_ppc.debet:debet',
                'order' => 'r_ppc.debet',
                'flag' => 'rp',
            ),

            'ng_debet' => array(
                'select' => 'r_ppc.ng_debet:ng_debet',
                'order' => 'r_ppc.ng_debet',
                'flag' => 'rp',
            ),

            'wo_debet' => array(
                'select' => 'r_ppc.ng2_debet:wo_debet',
                'order' => 'r_ppc.ng2_debet',
                'flag' => 'rp',
            ),

            //  (IFNULL(r_ppc.qty_debet,0) + IFNULL(r_ppc.ng_qty_debet,0)) AS total_qty_debet,
            //  (IFNULL(r_ppc.debet,0)     + IFNULL(r_ppc.ng_debet,0))     AS total_debet,

            'total_qty_debet' => array(
                'select' => '(IFNULL(r_ppc.qty_debet,0) + IFNULL(r_ppc.ng_qty_debet,0)):total_qty_debet',
                'order' => 'total_qty_debet',
                'flag' => 'qty',
            ),

            'total_debet' => array(
                'select' => '(IFNULL(r_ppc.debet,0) + IFNULL(r_ppc.ng_debet,0)):total_debet',
                'order' => 'debet',
                'search' => 'debet',
                'flag' => 'rp',
            ),

            'stamp' => array(
                'select' => 'r_ppc.dtime:stamp',
                'order' => 'r_ppc.dtime',
                'search' => 'r_ppc.dtime',
                'flag' => 'date',
            ),

//            'kredit' => array(
//                'label'=>'Kredit (Rp)',
//                'select'=>'r_ppc.kredit:kredit',
//                'order'=>'r_ppc.kredit',
//                'flag'=>'rp',
//            ),

            // computed dari module sa
//            'serial_available_count' => array(
//                'label'=>'Serial Aktif',
//                'select'=>'IFNULL(sa.cnt,0):serial_available_count',
//                'order'=>'serial_available_count', // alias OK (escape false)
//                'requires'=>array('sa'),
//            ),
//            'is_serial' => array(
//                'label'=>'Tipe',
//                'select'=>"CASE WHEN IFNULL(sa.cnt,0) > 0 THEN 1 ELSE 0 END:is_serial",
//                'order'=>'is_serial',
//                'requires'=>array('sa'),
//            ),
        ),
        'layout' => array(
            'pId',
            'status',
            'kode',
            'jenis',
            'rekening',
            'gudang_id',
            'merek_nama',
            'kategori_nama',
            'tipe_produk',
            'extern_id',
            'extern_nama',
            'size_nama',
            'qty_debet',
            'debet',
            'ng_qty_debet',
            'ng_debet',
            'total_qty_debet',
            'total_debet',
            'stamp',
        ),
        //header ini yg di build menjadi header dataTable
        'header' => array(
            "pId" => "pID",
//            "rekening" => "rek",

            //dari pairedModel -> viewedColumns
            "kode" => "product code",
            "merek_nama" => "merek",
            "kategori_nama" => "category",
            "tipe_produk" => "tipe produk",

            "extern_nama" => "item names",
            "size_nama" => "UOM",
            "qty_:debet" => "gudang reguler</br>(QTY)",
            ":debet" => "gudang reguler</br>(IDR)",
            "ng_qty_debet" => "gudang project<br>(qty)",
            "ng_debet" => "gudang project<br>(IDR)",

            "total_qty_debet" => "total qty<br>(qty)",
            "total_debet" => "total balance<br>(IDR)",

            "stamp" => "timestamp",

        ),
        // ===== DATATABLE RULES =====
        // (nanti order_map bisa dibangun otomatis dari layout+columns)
        'datatable' => array(
            'default_order' => array('pId' => 'ASC'),
            'multi_order' => false,
        ),
        "pairedModel" => array(
            "jenisItems" => array(
                "item" => "Produk",
                "item_rakitan" => "ProdukRakitan",
                "item_komposit" => "ProdukKomposit",
            ),
        ),
        "pairedSerial" => true,
        "additionalPairedWo" => array(
            "mdlNameRek" => "ComRekeningPembantuProduk", //model
            "mdlMethodRek" => "fetchBalances",

            "mdlNameData" => "MdlTasklistProject", //model
            "mdlMethodData" => "lookupAll",
        ),
    ),
    "RekeningPembantuKas" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_subkas_cache s',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
                array(
                    "(
                    SELECT
                        produk_id,
                        cabang_id,
                        SUM(nilai) AS active_value
                    FROM stock_locker_value
                    WHERE
                        jenis = 'kas'
                        AND state = 'active'
                        AND transaksi_id = 0
                        AND cabang_id = :cabang_id
                    GROUP BY produk_id, cabang_id
                ) sl_active",
                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
                    "left",
                    false
                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
                array(
                    "(
                    SELECT
                        produk_id,
                        cabang_id,
                        SUM(nilai) AS hold_value
                    FROM stock_locker_value
                    WHERE
                        jenis = 'kas'
                        AND state = 'hold'
                        AND transaksi_id > 0
                        AND cabang_id = :cabang_id
                    GROUP BY produk_id, cabang_id
                ) sl_hold",
                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
                    "left",
                    false
                ),

                array(
                    "(
                    SELECT
                        id,
                        folders_nama,
                        alias
                    FROM bank
                ) bank",
                    "bank.id = s.extern_id",
                    "left",
                    false
                ),
            ),
            'where' => array(
                "s.periode" => ":periode",
                "s.rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 's.id:pId',
                'search' => 's.id',
                'order' => 's.id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'pid',
                'select' => 's.extern_id:extern_id',
                'search' => 's.extern_id',
                'order' => 's.extern_id',
            ),

            'folders_nama' => array(
                'label' => 'alias',
                'select' => 'bank.folders_nama:folders_nama',
                'search' => 'bank.folders_nama',
                'order' => 'bank.folders_nama',
            ),

            'extern_nama' => array(
                'label' => 'rekening',
                'select' => 's.extern_nama:extern_nama',
                'search' => 's.extern_nama',
                'order' => 's.extern_nama',
            ),
            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'balance' => array(
                'label' => 'Balance (IDR)',
                'select' => 'IFNULL(sl_active.active_value,0):balance',
                'order' => 'balance', // alias boleh (escape false)
                'flag' => 'rp',
            ),

            // DEPOSIT IN TRANSIT = hold_value
            'deposit_transit' => array(
                'label' => 'Deposit in Transit (IDR)',
                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
                'order' => 'deposit_transit',
                'flag' => 'rp',
            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
            'effective_balance' => array(
                'label' => 'Effective Balance (IDR)',
                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
                'order' => 'effective_balance',
                'flag' => 'rp',
            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 's.dtime:stamp',
                'order' => 's.dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
            'pId',
            'extern_id',
            'folders_nama',
            'extern_nama',
//            'uom',
            'balance',
            'deposit_transit',
            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "pid",
            "folders_nama" => "alias",
            "extern_nama" => "rekening",
//            "uom"              => "UOM",
            "balance" => "account balance (IDR)",
            "deposit_transit" => "IN TRANSIT (IDR)",
            "effective_balance" => "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_nama' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuSupplier" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_supplier_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
                "thn" => ":tahun",
                "bln" => ":bulan",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "SUPPLIER ID",
            "extern_nama" => "SUPPLIER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "BALANCE (IDR)",
            ":debet" => "BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
        "periode" => "bulanan"
    ),
    "RekeningPembantuSupplies" => array(
        "showValue" => true,
        "showQty" => true,
        // ===== QUERY CONFIG =====
        'base' => array(
            'from' => 'produk p',
            'joins' => array(
                array(
                    "(
                    SELECT
                        extern_id,
                        periode,
                        rekening,
                        cabang_id,
                        gudang_id,
                        MAX(extern_nama) AS extern_nama,
                        
                        /* KOLOM ASLI (utama) */
                        SUM(
                            CASE
                                WHEN cabang_id = :cabang_id AND gudang_id = :gudang_id THEN qty_debet
                                ELSE 0
                            END
                        ) AS qty_debet,
                        SUM(
                            CASE
                                WHEN cabang_id = :cabang_id AND gudang_id = :gudang_id THEN debet
                                ELSE 0
                            END
                        ) AS debet,

                        /* NG: gudang normal (1..1000) */
                        SUM(CASE WHEN gudang_id > 0 AND gudang_id < 1000 THEN qty_debet ELSE 0 END) AS ng_qty_debet,
                        SUM(CASE WHEN gudang_id > 0 AND gudang_id < 1000 THEN debet     ELSE 0 END) AS ng_debet,

                        /* NG2: gudang khusus (>1000) */
                        SUM(CASE WHEN gudang_id > 1000 THEN qty_debet ELSE 0 END) AS ng2_qty_debet,
                        SUM(CASE WHEN gudang_id > 1000 THEN debet     ELSE 0 END) AS ng2_debet,

                        MAX(dtime) AS dtime
                    FROM _rek_pembantu_supplies_cache
                    WHERE
                        periode  = :periode
                        AND rekening = :rekening
                        AND cabang_id = :cabang_id
                    GROUP BY extern_id
                ) r_ppc",
                    "r_ppc.extern_id = p.id",
                    "left",
                    false
                ),
            ),
            'where' => array(
                "r_ppc.periode" => ":periode",   // runtime
                "r_ppc.rekening" => ":rekening",  // runtime
            ),
        ),
        'modules' => array(
//            'sp' => array(
//                'enabled' => true,
//                'select' => array(
//                    'IFNULL(sp.qty_debet,0):ng_qty_debet',
//                    'IFNULL(sp.debet,0):ng_debet',
//                    '(r_ppc.qty_debet + IFNULL(sp.qty_debet,0)):total_qty_debet',
//                    '(r_ppc.debet + IFNULL(sp.debet,0)):total_debet',
//                ),
//                'join' => array(
//                    'type'  => 'left',
//                    'on'    => 'sp.extern_id = r_ppc.extern_id AND sp.cabang_id = r_ppc.cabang_id',
//                    'sql'   => "(SELECT extern_id, cabang_id,
//                            SUM(qty_debet) ng_qty_debet,
//                            SUM(debet) ng_debet
//                      FROM _rek_pembantu_produk_cache
//                      WHERE gudang_id > 0
//                      GROUP BY extern_id) sp"
//                ),
//            ),
//            'sa' => array(
//                'enabled' => true,
//                'select' => array(
//                    'IFNULL(sa.cnt,0):serial_available_count',
//                    "CASE WHEN IFNULL(sa.cnt,0) > 0 THEN 1 ELSE 0 END:is_serial"
//                ),
//                'join' => array(
//                    'type'  => 'left',
//                    'on'    => 'sa.produk_id=p.id AND sa.cabang_id=r_ppc.cabang_id AND sa.gudang_id=r_ppc.gudang_id',
//                    'sql'   => "(SELECT produk_id, cabang_id, gudang_id, COUNT(*) cnt
//                      FROM _rek_pembantu_produk_perserial_cache
//                      WHERE qty_debet > 0
//                      GROUP BY produk_id) sa"
//                ),
//            ),
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => null),
            'gudang_id' => array('type' => 'int', 'default' => null),

            // toggle kolom
            'show_qty' => array('type' => 'bool', 'default' => true),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY (satu sumber untuk header + DT) =====
        'columns' => array(
            // key = field di result row
            'pId' => array(
                'label' => 'PID',
                'select' => 'p.id:pId',
                'search' => 'p.id',
                'order' => 'p.id',
            ),
            'extern_id' => array(
                'label' => 'extern_id',
                'select' => 'r_ppc.extern_id:extern_id',
                'search' => 'r_ppc.extern_id',
                'order' => 'r_ppc.extern_id',
            ),
            'kode' => array(
                'label' => 'Kode',
                'select' => 'p.kode:kode',
                'search' => 'p.kode',
                'order' => 'p.kode',
            ),
            'jenis' => array(
                'label' => 'jenis',
                'select' => 'p.jenis:jenis',
                'search' => 'p.jenis',
                'order' => 'p.jenis',
            ),
            'merek_nama' => array(
                'label' => 'merek',
                'select' => 'p.merek_nama:merek_nama',
                'search' => 'p.merek_nama',
                'order' => 'p.merek_nama',
            ),
//            'kategori_nama' => array(
//                'label' => 'kategori',
//                'select' => 'p.kategori_nama:kategori_nama',
//                'search' => 'p.kategori_nama',
//                'order'  => 'p.kategori_nama',
//            ),
//            'tipe_produk' => array(
//                'label' => 'tipe',
//                'select' => 'p.jml_serial:jml_serial',
//                'search' => 'p.jml_serial',
//                'order'  => 'p.jml_serial',
//            ),
            'extern_nama' => array(
                'label' => 'nama',
                'select' => 'r_ppc.extern_nama:extern_nama',
                'search' => 'r_ppc.extern_nama',
                'order' => 'r_ppc.extern_nama',
            ),
            'rekening' => array(
                'label' => 'rekening',
                'select' => 'r_ppc.rekening:rekening',
                'search' => 'r_ppc.rekening',
                'order' => 'r_ppc.rekening',
            ),
            'gudang_id' => array(
                'label' => 'gudang_id',
                'select' => 'r_ppc.gudang_id:gudang_id',
                'search' => 'r_ppc.gudang_id',
                'order' => 'r_ppc.gudang_id',
            ),
            'satuan' => array(
                'label' => 'UOM',
                'select' => 'p.satuan:satuan',
                'search' => 'p.satuan',
                'order' => 'p.satuan',
            ),

            // qty group (bisa dimatikan)
            'qty_debet' => array(
                'label' => 'Gudang Reguler (QTY)',
                'select' => 'r_ppc.qty_debet:qty_debet',
                'order' => 'r_ppc.qty_debet',
                'flag' => 'qty',
            ),
            'ng_qty_debet' => array(
                'label' => 'Gudang Project (QTY)',
                'select' => 'r_ppc.ng_qty_debet:ng_qty_debet',
                'order' => 'r_ppc.ng_qty_debet',
                'flag' => 'qty',
            ),
            'wo_qty_debet' => array(
                'label' => 'Gudang WO (QTY)',
                'select' => 'r_ppc.ng2_qty_debet:wo_qty_debet',
                'order' => 'r_ppc.ng2_qty_debet',
                'flag' => 'qty',
            ),
//            'qty_kredit' => array(
//                'label'=>'Qty Kredit',
//                'select'=>'r_ppc.qty_kredit:qty_kredit',
//                'order'=>'r_ppc.qty_kredit',
//                'flag'=>'qty',
//            ),

            // rupiah group (bisa dimatikan)
            'debet' => array(
                'label' => 'Gudang Reguler (Rp)',
                'select' => 'r_ppc.debet:debet',
                'order' => 'r_ppc.debet',
                'flag' => 'rp',
            ),

            'ng_debet' => array(
                'label' => 'Gudang Project (Rp)',
                'select' => 'r_ppc.ng_debet:ng_debet',
                'order' => 'r_ppc.ng_debet',
                'flag' => 'rp',
            ),

            'wo_debet' => array(
                'label' => 'Gudang WO (Rp)',
                'select' => 'r_ppc.ng2_debet:wo_debet',
                'order' => 'r_ppc.ng2_debet',
                'flag' => 'rp',
            ),

            //  (IFNULL(r_ppc.qty_debet,0) + IFNULL(r_ppc.ng_qty_debet,0)) AS total_qty_debet,
            //  (IFNULL(r_ppc.debet,0)     + IFNULL(r_ppc.ng_debet,0))     AS total_debet,

            'total_qty_debet' => array(
                'label' => 'Gudang Project (Rp)',
                'select' => '(IFNULL(r_ppc.qty_debet,0) + IFNULL(r_ppc.ng_qty_debet,0)):total_qty_debet',
                'order' => 'r_ppc.qty_debet',
                'flag' => 'qty',
            ),

            'total_debet' => array(
                'label' => 'Gudang Project (Rp)',
                'select' => '(IFNULL(r_ppc.debet,0) + IFNULL(r_ppc.ng_debet,0)):total_debet',
                'order' => 'r_ppc.debet',
                'flag' => 'rp',
            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'r_ppc.dtime:stamp',
                'order' => 'r_ppc.debet',
                'flag' => 'date',
            ),

//            'kredit' => array(
//                'label'=>'Kredit (Rp)',
//                'select'=>'r_ppc.kredit:kredit',
//                'order'=>'r_ppc.kredit',
//                'flag'=>'rp',
//            ),

            // computed dari module sa
//            'serial_available_count' => array(
//                'label'=>'Serial Aktif',
//                'select'=>'IFNULL(sa.cnt,0):serial_available_count',
//                'order'=>'serial_available_count', // alias OK (escape false)
//                'requires'=>array('sa'),
//            ),
//            'is_serial' => array(
//                'label'=>'Tipe',
//                'select'=>"CASE WHEN IFNULL(sa.cnt,0) > 0 THEN 1 ELSE 0 END:is_serial",
//                'order'=>'is_serial',
//                'requires'=>array('sa'),
//            ),
        ),
        'layout' => array(
            'pId',
            'extern_id',
            'kode',
            'jenis',
            'rekening',
            'gudang_id',
            'merek_nama',
//            'kategori_nama',
//            'tipe_produk',
            'extern_nama',
            'satuan',
            'qty_debet',
            'debet',
            'ng_qty_debet',
            'ng_debet',
            'total_qty_debet',
            'total_debet',
            'stamp',
        ),
        //header ini yg di build menjadi header dataTable
        'header' => array(
            "pId" => "pID",
//            "rekening" => "rek",

            //dari pairedModel -> viewedColumns
            "kode" => "product code",
            "merek_nama" => "merek",
//            "kategori_nama" => "category",
//            "tipe_produk" => "tipe produk",

            "extern_nama" => "item names",
            "satuan" => "UOM",
            "qty_:debet" => "gudang reguler</br>(QTY)",
            ":debet" => "gudang reguler</br>(IDR)",
            "ng_qty_debet" => "gudang project<br>(qty)",
            "ng_debet" => "gudang project<br>(IDR)",

            "total_qty_debet" => "total qty<br>(qty)",
            "total_debet" => "total balance<br>(IDR)",

            "stamp" => "timestamp",

        ),
        // ===== DATATABLE RULES =====
        // (nanti order_map bisa dibangun otomatis dari layout+columns)
        'datatable' => array(
            'default_order' => array('pId' => 'ASC'),
            'multi_order' => false,
        ),
        "pairedModel" => array(
//            "jenisItems" => array(
//                "item" => "Produk",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
//            ),
        ),
    ),
    "RekeningPembantuCustomer" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_customer_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuAntarcabang" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_antarcabang_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
                "thn" => ":tahun",
                "bln" => ":bulan"
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "id",
            "extern_nama" => "cabang",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
        "periode" => "bulanan"
    ),
    "RekeningPembantuUangMuka" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_uang_muka_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'debet' => array(
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // BALANCE = active_value
            'kredit' => array(
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),

            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'debet',
//            'kredit',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "SUPPLIER ID",
            "extern_nama" => "SUPPLIER NAME",
//            "uom"              => "UOM",
            ":debet" => "SALDO BALANCE (IDR)",
//            "kredit"          => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuUangMukaMainReference" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_uang_muka_reference_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
                "debet>" => 1000,
                "extern_id" => ":extern2_id",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'debet' => array(
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // BALANCE = active_value
            'kredit' => array(
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),

            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'debet',
//            'kredit',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
            ":debet" => "SALDO BALANCE (IDR)",
//            "kredit"          => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuCreditNote" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_creditnote_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "supplier id",
            "extern_nama" => "supplier nama",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuPiutangSupplierMain" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_piutangsupplier_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "SUPPLIER ID",
            "extern_nama" => "SUPPLIER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuLogamMulia" => array(
        "showValue" => true,
        "showQty" => true,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_sublogammulia_cache',
            'joins' => array(
                array(
                    "(
                    SELECT
                        id,
                        nama,
                        satuan_nama
                    FROM dta_logam_mulia
                ) emas",
                    "emas.id = extern_id",
                    "left",
                    false
                ),

                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'emas.nama:extern_nama',
                'search' => 'emas.nama',
                'order' => 'emas.nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
            'uom' => array(
                'label' => 'UOM',
                'select' => "emas.satuan_nama:uom",
                'search' => "emas.satuan_nama",
                'order' => "emas.satuan_nama",
            ),

            // BALANCE = active_value
//            'kredit' => array(
//                'label'  => 'Balance (IDR)',
//                'select' => 'kredit:kredit',
//                'search' => 'kredit',
//                'order'  => 'kredit',
//            ),
            'qty_debet' => array(
                'select' => 'qty_debet:qty_debet',
                'search' => 'qty_debet',
                'order' => 'qty_debet',
                'flag' => 'qty',
            ),
            'debet' => array(
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
            'uom',
//            'kredit',
            'qty_debet',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "ITEMS ID",
            "extern_nama" => "ITEMS NAMA",
            "uom" => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "qty_debet" => "(QTY)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuBank" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_bank_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
//            'kredit' => array(
//                'label'  => 'Balance (IDR)',
//                'select' => 'kredit:kredit',
//                'search' => 'kredit',
//                'order'  => 'kredit',
//            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
//            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuHutangSaham" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_hutang_saham_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
//            'kredit' => array(
//                'label'  => 'Balance (IDR)',
//                'select' => 'kredit:kredit',
//                'search' => 'kredit',
//                'order'  => 'kredit',
//            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
//            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuHutangBiayaBunga" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_hutangbunga_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
//            'kredit' => array(
//                'label'  => 'Balance (IDR)',
//                'select' => 'kredit:kredit',
//                'search' => 'kredit',
//                'order'  => 'kredit',
//            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
//            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_pph_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuBiaya" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_subbiaya_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => " ID",
            "extern_nama" => " NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuDepresiasi" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_depresiasi_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => " ID",
            "extern_nama" => " NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuModal" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_modal_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuPphMain" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_pph_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuLoanItem" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_loan_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
//            'kredit' => array(
//                'label'  => 'Balance (IDR)',
//                'select' => 'kredit:kredit',
//                'search' => 'kredit',
//                'order'  => 'kredit',
//            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
//            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuBiayaUmum" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_subbiayaumum_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
//            'kredit' => array(
//                'label'  => 'Balance (IDR)',
//                'select' => 'kredit:kredit',
//                'search' => 'kredit',
//                'order'  => 'kredit',
//            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
//            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "BIAYA ID",
            "extern_nama" => "BIAYA NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
        "periode" => "bulanan"
    ),
    "RekeningPembantuBiayaUsaha" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_subbiayausaha_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
        "periode" => "bulanan"
    ),
    "RekeningPembantuBiayaProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_subbiayaproduksi_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            "debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
//        "periode" => "bulanan"
    ),
    "RekeningPembantuBiayaHarusDibayar" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_biayaharusdibayar_cache',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 'extern_id',
                'search' => 'extern_id',
                'order' => 'extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 'extern_nama',
                'search' => 'extern_nama',
                'order' => 'extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 'kredit:kredit',
                'search' => 'kredit',
                'order' => 'kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 'debet:debet',
                'search' => 'debet',
                'order' => 'debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 'dtime:stamp',
                'order' => 'dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "CUSTOMER ID",
            "extern_nama" => "CUSTOMER NAME",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
//        "periode" => "bulanan"
    ),
    "RekeningPembantuAkumPenyusutanKendaraan" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_akumpenyukendaraan_cache s',
            'joins' => array(
                array(
                    "(
                    SELECT
                        id,
                        merk,
                        serial_no,
                        label,
                        nama,
                        kode
                    FROM aset_detail
                    WHERE
                        jenis = 'item'
                ) aset",
                    "aset.id = s.extern_id",
                    "left",
                    false
                ),

                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 's.extern_id',
                'search' => 's.extern_id',
                'order' => 's.extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 's.extern_nama',
                'search' => 's.extern_nama',
                'order' => 's.extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.kredit:kredit',
                'search' => 's.kredit',
                'order' => 's.kredit',
            ),
            'qty_kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_kredit:qty_kredit',
                'search' => 's.qty_kredit',
                'order' => 's.qty_kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.debet:debet',
                'search' => 's.debet',
                'order' => 's.debet',
            ),
            'qty_debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_debet:qty_debet',
                'search' => 's.qty_debet',
                'order' => 's.qty_debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'kode' => array(
                'label' => 'Timestamp',
                'select' => 'aset.kode:kode',
                'order' => 'aset.kode',
                'search' => 'aset.kode',
            ),
            'serial_no' => array(
                'label' => 'Timestamp',
                'select' => 'aset.serial_no:serial_no',
                'order' => 'aset.serial_no',
                'search' => 'aset.serial_no',
            ),
            'merek' => array(
                'label' => 'Timestamp',
                'select' => 'aset.merk:merek',
                'order' => 'aset.merk',
                'search' => 'aset.merk',
            ),
            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 's.dtime:stamp',
                'order' => 's.dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
            'kode',
            'serial_no',
            'merek',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "ASSET ID",
            "kode" => "kode asset",
            "serial_no" => "no serial",
            "merek" => "merek",
            "extern_nama" => "nama ASSET",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
//        "periode" => "bulanan"
    ),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_akumperkantor_cache s',
            'joins' => array(
                array(
                    "(
                    SELECT
                        id,
                        merk,
                        serial_no,
                        label,
                        nama,
                        kode
                    FROM aset_detail
                    WHERE
                        jenis = 'item'
                ) aset",
                    "aset.id = s.extern_id",
                    "left",
                    false
                ),

                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 's.extern_id',
                'search' => 's.extern_id',
                'order' => 's.extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 's.extern_nama',
                'search' => 's.extern_nama',
                'order' => 's.extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.kredit:kredit',
                'search' => 's.kredit',
                'order' => 's.kredit',
            ),
            'qty_kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_kredit:qty_kredit',
                'search' => 's.qty_kredit',
                'order' => 's.qty_kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.debet:debet',
                'search' => 's.debet',
                'order' => 's.debet',
            ),
            'qty_debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_debet:qty_debet',
                'search' => 's.qty_debet',
                'order' => 's.qty_debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'kode' => array(
                'label' => 'Timestamp',
                'select' => 'aset.kode:kode',
                'order' => 'aset.kode',
                'search' => 'aset.kode',
            ),
            'serial_no' => array(
                'label' => 'Timestamp',
                'select' => 'aset.serial_no:serial_no',
                'order' => 'aset.serial_no',
                'search' => 'aset.serial_no',
            ),
            'merek' => array(
                'label' => 'Timestamp',
                'select' => 'aset.merk:merek',
                'order' => 'aset.merk',
                'search' => 'aset.merk',
            ),
            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 's.dtime:stamp',
                'order' => 's.dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
            'kode',
            'serial_no',
            'merek',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "ASSET ID",
            "kode" => "kode asset",
            "serial_no" => "no serial",
            "merek" => "merek",
            "extern_nama" => "nama ASSET",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
//        "periode" => "bulanan"
    ),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_akumperproduksi_cache s',
            'joins' => array(
                array(
                    "(
                    SELECT
                        id,
                        merk,
                        serial_no,
                        label,
                        nama,
                        kode
                    FROM aset_detail
                    WHERE
                        jenis = 'item'
                ) aset",
                    "aset.id = s.extern_id",
                    "left",
                    false
                ),

                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 's.extern_id',
                'search' => 's.extern_id',
                'order' => 's.extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 's.extern_nama',
                'search' => 's.extern_nama',
                'order' => 's.extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.kredit:kredit',
                'search' => 's.kredit',
                'order' => 's.kredit',
            ),
            'qty_kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_kredit:qty_kredit',
                'search' => 's.qty_kredit',
                'order' => 's.qty_kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.debet:debet',
                'search' => 's.debet',
                'order' => 's.debet',
            ),
            'qty_debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_debet:qty_debet',
                'search' => 's.qty_debet',
                'order' => 's.qty_debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'kode' => array(
                'label' => 'Timestamp',
                'select' => 'aset.kode:kode',
                'order' => 'aset.kode',
                'search' => 'aset.kode',
            ),
            'serial_no' => array(
                'label' => 'Timestamp',
                'select' => 'aset.serial_no:serial_no',
                'order' => 'aset.serial_no',
                'search' => 'aset.serial_no',
            ),
            'merek' => array(
                'label' => 'Timestamp',
                'select' => 'aset.merk:merek',
                'order' => 'aset.merk',
                'search' => 'aset.merk',
            ),
            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 's.dtime:stamp',
                'order' => 's.dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
            'kode',
            'serial_no',
            'merek',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "ASSET ID",
            "kode" => "kode asset",
            "serial_no" => "no serial",
            "merek" => "merek",
            "extern_nama" => "nama ASSET",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
//        "periode" => "bulanan"
    ),
    "RekeningPembantuDepresiasi" => array(
        "showValue" => true,
        "showQty" => false,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subkas sebagai sumber row utama
            'from' => '_rek_pembantu_depresiasi_cache s',
            'joins' => array(
                array(
                    "(
                    SELECT
                        id,
                        merk,
                        serial_no,
                        label,
                        nama,
                        kode
                    FROM aset_detail
                    WHERE
                        jenis = 'item'
                ) aset",
                    "aset.id = s.extern_id",
                    "left",
                    false
                ),

                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS active_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'active'
//                        AND transaksi_id = 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_active",
//                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
//                array(
//                    "(
//                    SELECT
//                        produk_id,
//                        cabang_id,
//                        SUM(nilai) AS hold_value
//                    FROM stock_locker_value
//                    WHERE
//                        jenis = 'kas'
//                        AND state = 'hold'
//                        AND transaksi_id > 0
//                        AND cabang_id = :cabang_id
//                    GROUP BY produk_id, cabang_id
//                ) sl_hold",
//                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
//                    "left",
//                    false
//                ),
            ),
            'where' => array(
                "periode" => ":periode",
                "rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 'id:pId',
                'search' => 'id',
                'order' => 'id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'ACCOUNT ID',
                'select' => 's.extern_id',
                'search' => 's.extern_id',
                'order' => 's.extern_id',
            ),

            'extern_nama' => array(
                'label' => 'ACCOUNT NAMES',
                'select' => 's.extern_nama',
                'search' => 's.extern_nama',
                'order' => 's.extern_nama',
            ),

            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.kredit:kredit',
                'search' => 's.kredit',
                'order' => 's.kredit',
            ),
            'qty_kredit' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_kredit:qty_kredit',
                'search' => 's.qty_kredit',
                'order' => 's.qty_kredit',
            ),
            'debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.debet:debet',
                'search' => 's.debet',
                'order' => 's.debet',
            ),
            'qty_debet' => array(
                'label' => 'Balance (IDR)',
                'select' => 's.qty_debet:qty_debet',
                'search' => 's.qty_debet',
                'order' => 's.qty_debet',
            ),
            // DEPOSIT IN TRANSIT = hold_value
//            'deposit_transit' => array(
//                'label'  => 'Deposit in Transit (IDR)',
//                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
//                'order'  => 'deposit_transit',
//                'flag'   => 'rp',
//            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
//            'effective_balance' => array(
//                'label'  => 'Effective Balance (IDR)',
//                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
//                'order'  => 'effective_balance',
//                'flag'   => 'rp',
//            ),

            'kode' => array(
                'label' => 'Timestamp',
                'select' => 'aset.kode:kode',
                'order' => 'aset.kode',
                'search' => 'aset.kode',
            ),
            'serial_no' => array(
                'label' => 'Timestamp',
                'select' => 'aset.serial_no:serial_no',
                'order' => 'aset.serial_no',
                'search' => 'aset.serial_no',
            ),
            'merek' => array(
                'label' => 'Timestamp',
                'select' => 'aset.merk:merek',
                'order' => 'aset.merk',
                'search' => 'aset.merk',
            ),
            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 's.dtime:stamp',
                'order' => 's.dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
//            'pId',
            'extern_id',
            'extern_nama',
            'kode',
            'serial_no',
            'merek',
//            'uom',
            'kredit',
            'debet',
//            'deposit_transit',
//            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "ASSET ID",
            "kode" => "kode asset",
            "serial_no" => "no serial",
            "merek" => "merek",
            "extern_nama" => "nama ASSET",
//            "uom"              => "UOM",
//            "kredit"          => "SALDO BALANCE (IDR)",
            ":debet" => "SALDO BALANCE (IDR)",
//            "deposit_transit"  => "DEPOSIT IN TRANSIT (IDR)",
//            "effective_balance"=> "EFFECTIVE BALANCE (IDR)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_id' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
//        "periode" => "bulanan"
    ),
    "RekeningPembantuValas" => array(
        "showValue" => true,
        "showQty" => true,
        // ===== QUERY CONFIG =====
        'base' => array(
            // subvalas sebagai sumber row utama
            'from' => '_rek_pembantu_subvalas_cache s',
            'joins' => array(
                // ACTIVE: saldo aktif (state=active, transaksi_id=0)
                array(
                    "(
                    SELECT
                        produk_id,
                        cabang_id,
                        SUM(nilai) AS active_value
                    FROM stock_locker_value
                    WHERE
                        jenis = 'valas'
                        AND state = 'active'
                        AND transaksi_id = 0
                        AND cabang_id = :cabang_id
                    GROUP BY produk_id, cabang_id
                ) sl_active",
                    "sl_active.produk_id = s.extern_id AND sl_active.cabang_id = s.cabang_id",
                    "left",
                    false
                ),

                // HOLD: saldo ditahan / transit (state=hold, transaksi_id>0)
                array(
                    "(
                    SELECT
                        produk_id,
                        cabang_id,
                        SUM(nilai) AS hold_value
                    FROM stock_locker_value
                    WHERE
                        jenis = 'valas'
                        AND state = 'hold'
                        AND transaksi_id > 0
                        AND cabang_id = :cabang_id
                    GROUP BY produk_id, cabang_id
                ) sl_hold",
                    "sl_hold.produk_id = s.extern_id AND sl_hold.cabang_id = s.cabang_id",
                    "left",
                    false
                ),

                array(
                    "(
                    SELECT
                        id,
                        nama,
                        exchange
                    FROM currency
                ) currency",
                    "currency.id = s.extern_id",
                    "left",
                    false
                ),
            ),
            'where' => array(
                "s.periode" => ":periode",
                "s.rekening" => ":rekening",
//                "s.cabang_id" => ":cabang_id",
            ),
        ),
        'modules' => array(// kosong dulu
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => -1),

            // toggle kolom (kalau nanti kepake)
            'show_qty' => array('type' => 'bool', 'default' => false),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY =====
        'columns' => array(
            'pId' => array(
                'label' => 'PID',
                'select' => 's.id:pId',
                'search' => 's.id',
                'order' => 's.id',
            ),

            // kalau kamu punya field kode di cache, ganti '0' => 's.extern_kode:kode'
//            'kode' => array(
//                'label'  => 'Kode',
//                'select' => '0:kode',
//                'search' => null,
//                'order'  => null,
//            ),
            'extern_id' => array(
                'label' => 'pid',
                'select' => 's.extern_id:extern_id',
                'search' => 's.extern_id',
                'order' => 's.extern_id',
            ),

            'folders_nama' => array(
                'label' => 'alias',
                'select' => 'currency.nama:nama',
                'search' => 'currency.nama',
                'order' => 'currency.nama',
            ),

            'extern_nama' => array(
                'label' => 'rekening',
                'select' => 's.extern_nama:extern_nama',
                'search' => 's.extern_nama',
                'order' => 's.extern_nama',
            ),
            // kas biasanya ga punya UOM, jadi 0 / kosong
//            'uom' => array(
//                'label'  => 'UOM',
//                'select' => "'' :uom",
//                'search' => null,
//                'order'  => null,
//            ),

            // BALANCE = active_value
            'debet' => array(
                'label' => 'Balance',
                'select' => 's.debet:debet',
                'order' => 'debet', // alias boleh (escape false)
                'flag' => 'rp',
            ),

            // BALANCE = active_value
            'balance' => array(
                'label' => 'Balance',
                'select' => 'IFNULL(sl_active.active_value,0):balance',
                'order' => 'balance', // alias boleh (escape false)
                'flag' => 'rp',
            ),

            // DEPOSIT IN TRANSIT = hold_value
            'deposit_transit' => array(
                'label' => 'Deposit in Transit',
                'select' => 'IFNULL(sl_hold.hold_value,0):deposit_transit',
                'order' => 'deposit_transit',
                'flag' => 'rp',
            ),

            // EFFECTIVE BALANCE = balance - transit (kalau definisi kamu beda, tinggal ubah rumusnya)
            'effective_balance' => array(
                'label' => 'Effective Balance',
                'select' => '(IFNULL(sl_active.active_value,0) - IFNULL(sl_hold.hold_value,0)):effective_balance',
                'order' => 'effective_balance',
                'flag' => 'rp',
            ),

            'stamp' => array(
                'label' => 'Timestamp',
                'select' => 's.dtime:stamp',
                'order' => 's.dtime',
                'flag' => 'date',
            ),
        ),
        'layout' => array(
            'pId',
            'extern_id',
//            'folders_nama',
            'extern_nama',
//            'uom',
            'debet',
            'balance',
            'deposit_transit',
            'effective_balance',
            'stamp',
        ),
        // header ini yg di build menjadi header dataTable
        'header' => array(
//            "pId"              => "PID",
            "extern_id" => "pid",
//            "folders_nama"     => "alias",
            "extern_nama" => "rekening",
//            "uom"              => "UOM",
            "debet" => "account balance (RP)",
            "balance" => "account balance (QTY)",
            "deposit_transit" => "IN TRANSIT (QTY)",
            "effective_balance" => "EFFECTIVE BALANCE (QTY)",
            "stamp" => "TIMESTAMP",
        ),
        // ===== DATATABLE RULES =====
        'datatable' => array(
            'default_order' => array('extern_nama' => 'ASC'),
            'multi_order' => false,
        ),
        // (opsional) kalau masih kepake struktur pairedModel lama, boleh kosong
        "pairedModel" => array(),
    ),
    "RekeningPembantuAktivaBerwujud" => array(
        "showValue" => true,
        "showQty" => true,
        // ===== QUERY CONFIG =====
        'base' => array(
            'from' => 'aset_detail p',
            'joins' => array(
                array(
                    "(
                    SELECT
                        extern_id,
                        periode,
                        rekening,
                        cabang_id,
                        gudang_id,
                        MAX(extern_nama) AS extern_nama,
                        
                        /* KOLOM ASLI (utama) */
                        SUM(
                            CASE
                                WHEN cabang_id = :cabang_id AND gudang_id = :gudang_id THEN qty_debet
                                ELSE 0
                            END
                        ) AS qty_debet,
                        SUM(
                            CASE
                                WHEN cabang_id = :cabang_id AND gudang_id = :gudang_id THEN debet
                                ELSE 0
                            END
                        ) AS debet,

                        /* NG: gudang normal (1..1000) */
                        SUM(CASE WHEN gudang_id > 0 AND gudang_id < 1000 THEN qty_debet ELSE 0 END) AS ng_qty_debet,
                        SUM(CASE WHEN gudang_id > 0 AND gudang_id < 1000 THEN debet     ELSE 0 END) AS ng_debet,

                        /* NG2: gudang khusus (>1000) */
                        SUM(CASE WHEN gudang_id > 1000 THEN qty_debet ELSE 0 END) AS ng2_qty_debet,
                        SUM(CASE WHEN gudang_id > 1000 THEN debet     ELSE 0 END) AS ng2_debet,

                        MAX(dtime) AS dtime
                    FROM _rek_pembantu_produk_cache
                    WHERE
                        periode  = :periode
                        AND rekening = :rekening
                        AND cabang_id = :cabang_id
                    GROUP BY extern_id
                ) r_ppc",
                    "r_ppc.extern_id = p.id",
                    "left",
                    false
                ),
            ),
            'where' => array(
                "r_ppc.periode" => ":periode",   // runtime
                "r_ppc.rekening" => ":rekening",  // runtime
            ),
        ),
        'modules' => array(
//            'sp' => array(
//                'enabled' => true,
//                'select' => array(
//                    'IFNULL(sp.qty_debet,0):ng_qty_debet',
//                    'IFNULL(sp.debet,0):ng_debet',
//                    '(r_ppc.qty_debet + IFNULL(sp.qty_debet,0)):total_qty_debet',
//                    '(r_ppc.debet + IFNULL(sp.debet,0)):total_debet',
//                ),
//                'join' => array(
//                    'type'  => 'left',
//                    'on'    => 'sp.extern_id = r_ppc.extern_id AND sp.cabang_id = r_ppc.cabang_id',
//                    'sql'   => "(SELECT extern_id, cabang_id,
//                            SUM(qty_debet) ng_qty_debet,
//                            SUM(debet) ng_debet
//                      FROM _rek_pembantu_produk_cache
//                      WHERE gudang_id > 0
//                      GROUP BY extern_id) sp"
//                ),
//            ),
//            'sa' => array(
//                'enabled' => true,
//                'select' => array(
//                    'IFNULL(sa.cnt,0):serial_available_count',
//                    "CASE WHEN IFNULL(sa.cnt,0) > 0 THEN 1 ELSE 0 END:is_serial"
//                ),
//                'join' => array(
//                    'type'  => 'left',
//                    'on'    => 'sa.produk_id=p.id AND sa.cabang_id=r_ppc.cabang_id AND sa.gudang_id=r_ppc.gudang_id',
//                    'sql'   => "(SELECT produk_id, cabang_id, gudang_id, COUNT(*) cnt
//                      FROM _rek_pembantu_produk_perserial_cache
//                      WHERE qty_debet > 0
//                      GROUP BY produk_id) sa"
//                ),
//            ),
        ),
        // ===== FILTER SCHEMA =====
        'filters' => array(
            'periode' => array('type' => 'string', 'default' => 'forever'),
            'rekening' => array('type' => 'int', 'required' => true),
            'cabang_id' => array('type' => 'int', 'default' => null),
            'gudang_id' => array('type' => 'int', 'default' => null),

            // toggle kolom
            'show_qty' => array('type' => 'bool', 'default' => true),
            'show_rp' => array('type' => 'bool', 'default' => true),

            // module override
            'modules' => array('type' => 'array', 'default' => array()),
        ),
        // ===== OUTPUT COLUMNS REGISTRY (satu sumber untuk header + DT) =====
        'columns' => array(
            // key = field di result row
            'pId' => array(
                'select' => 'p.id:pId',
                'search' => 'p.id',
                'order' => 'p.id',
            ),
            'status' => array(
                'select' => 'p.status:status',
                'search' => 'p.status',
                'order' => 'p.status',
            ),
            'kode' => array(
                'select' => 'p.kode:kode',
                'search' => 'p.kode',
                'order' => 'p.kode',
            ),
            'jenis' => array(
                'select' => 'p.jenis:jenis',
                'search' => 'p.jenis',
                'order' => 'p.jenis',
            ),
            'merek_nama' => array(
                'select' => 'p.merek_nama:merek_nama',
                'search' => 'p.merek_nama',
                'order' => 'p.merek_nama',
            ),
            'kategori_nama' => array(
                'select' => 'p.kategori_nama:kategori_nama',
                'search' => 'p.kategori_nama',
                'order' => 'p.kategori_nama',
            ),
            'tipe_produk' => array(
                'select' => 'p.jml_serial:jml_serial',
                'search' => 'p.jml_serial',
                'order' => 'p.jml_serial',
            ),
            'extern_nama' => array(
                'select' => 'p.nama:extern_nama',
                'search' => 'p.nama',
                'order' => 'p.nama',
            ),
            'extern_id' => array(
                'select' => 'r_ppc.extern_id:extern_id',
                'search' => 'r_ppc.extern_id',
                'order' => 'r_ppc.extern_id',
            ),
            'rekening' => array(
                'select' => 'r_ppc.rekening:rekening',
                'search' => 'r_ppc.rekening',
                'order' => 'r_ppc.rekening',
            ),
            'gudang_id' => array(
                'select' => 'r_ppc.gudang_id:gudang_id',
                'search' => 'r_ppc.gudang_id',
                'order' => 'r_ppc.gudang_id',
            ),
            'size_nama' => array(
                'select' => 'p.size_nama:size_nama',
                'search' => 'p.size_nama',
                'order' => 'p.size_nama',
            ),

            // qty group (bisa dimatikan)
            'qty_debet' => array(
                'select' => 'r_ppc.qty_debet:qty_debet',
                'order' => 'r_ppc.qty_debet',
                'flag' => 'qty',
            ),
            'ng_qty_debet' => array(
                'select' => 'r_ppc.ng_qty_debet:ng_qty_debet',
                'order' => 'r_ppc.ng_qty_debet',
                'flag' => 'qty',
            ),
            'wo_qty_debet' => array(
                'select' => 'r_ppc.ng2_qty_debet:wo_qty_debet',
                'order' => 'r_ppc.ng2_qty_debet',
                'flag' => 'qty',
            ),
//            'qty_kredit' => array(
//                'label'=>'Qty Kredit',
//                'select'=>'r_ppc.qty_kredit:qty_kredit',
//                'order'=>'r_ppc.qty_kredit',
//                'flag'=>'qty',
//            ),

            // rupiah group (bisa dimatikan)
            'debet' => array(
                'select' => 'r_ppc.debet:debet',
                'order' => 'r_ppc.debet',
                'flag' => 'rp',
            ),

            'ng_debet' => array(
                'select' => 'r_ppc.ng_debet:ng_debet',
                'order' => 'r_ppc.ng_debet',
                'flag' => 'rp',
            ),

            'wo_debet' => array(
                'select' => 'r_ppc.ng2_debet:wo_debet',
                'order' => 'r_ppc.ng2_debet',
                'flag' => 'rp',
            ),

            //  (IFNULL(r_ppc.qty_debet,0) + IFNULL(r_ppc.ng_qty_debet,0)) AS total_qty_debet,
            //  (IFNULL(r_ppc.debet,0)     + IFNULL(r_ppc.ng_debet,0))     AS total_debet,

            'total_qty_debet' => array(
                'select' => '(IFNULL(r_ppc.qty_debet,0) + IFNULL(r_ppc.ng_qty_debet,0)):total_qty_debet',
                'order' => 'total_qty_debet',
                'flag' => 'qty',
            ),

            'total_debet' => array(
                'select' => '(IFNULL(r_ppc.debet,0) + IFNULL(r_ppc.ng_debet,0)):total_debet',
                'order' => 'debet',
                'search' => 'debet',
                'flag' => 'rp',
            ),

            'stamp' => array(
                'select' => 'r_ppc.dtime:stamp',
                'order' => 'r_ppc.dtime',
                'search' => 'r_ppc.dtime',
                'flag' => 'date',
            ),

//            'kredit' => array(
//                'label'=>'Kredit (Rp)',
//                'select'=>'r_ppc.kredit:kredit',
//                'order'=>'r_ppc.kredit',
//                'flag'=>'rp',
//            ),

            // computed dari module sa
//            'serial_available_count' => array(
//                'label'=>'Serial Aktif',
//                'select'=>'IFNULL(sa.cnt,0):serial_available_count',
//                'order'=>'serial_available_count', // alias OK (escape false)
//                'requires'=>array('sa'),
//            ),
//            'is_serial' => array(
//                'label'=>'Tipe',
//                'select'=>"CASE WHEN IFNULL(sa.cnt,0) > 0 THEN 1 ELSE 0 END:is_serial",
//                'order'=>'is_serial',
//                'requires'=>array('sa'),
//            ),
        ),
        'layout' => array(
            'pId',
            'status',
            'kode',
            'nama',
//            'jenis',
//            'rekening',
//            'gudang_id',
//            'merek_nama',
//            'kategori_nama',
//            'tipe_produk',
            'extern_id',
            'extern_nama',
//            'size_nama',
            'qty_debet',
            'debet',
            'ng_qty_debet',
            'ng_debet',
            'total_qty_debet',
            'total_debet',
            'stamp',
        ),
        //header ini yg di build menjadi header dataTable
        'header' => array(
            "pId" => "pID",
//            "rekening" => "rek",

            //dari pairedModel -> viewedColumns
            "kode" => "product code",
//            "merek_nama" => "merek",
//            "kategori_nama" => "category",
//            "tipe_produk" => "tipe produk",

            "extern_nama" => "item names",
//            "size_nama" => "UOM",
            "qty_:debet" => "gudang reguler</br>(QTY)",
            ":debet" => "gudang reguler</br>(IDR)",
            "ng_qty_debet" => "gudang project<br>(qty)",
            "ng_debet" => "gudang project<br>(IDR)",

            "total_qty_debet" => "total qty<br>(qty)",
            "total_debet" => "total balance<br>(IDR)",

            "stamp" => "timestamp",

        ),
        // ===== DATATABLE RULES =====
        // (nanti order_map bisa dibangun otomatis dari layout+columns)
        'datatable' => array(
            'default_order' => array('pId' => 'ASC'),
            'multi_order' => false,
        ),
//        "pairedModel" => array(
//            "jenisItems" => array(
//                "item" => "Produk",
//                "item_rakitan" => "ProdukRakitan",
//                "item_komposit" => "ProdukKomposit",
//            ),
//        ),
//        "pairedSerial" => true,
//        "additionalPairedWo" => array(
//            "mdlNameRek" => "ComRekeningPembantuProduk", //model
//            "mdlMethodRek" => "fetchBalances",
//
//            "mdlNameData" => "MdlTasklistProject", //model
//            "mdlMethodData" => "lookupAll",
//        ),
    ),
);

$config['accountBalanceAdditionalColumns'] = array(
    "aktiva tetap" => array(
        "netto" => "netto (IDR)",
    ),

);

$config['accountBalanceAdvanceColumns'] = array(
    "piutang dagang" => array(
        "loadModel" => "MdlTransaksi",
        "model" => "MdlTransaksi",
        "method" => "lookupAllDueDate",
        "filter" => array(
            "status=1",
        ),
        "header" => array(
            "due_date" => "Due Date",
            "aging" => "Aging (Days)",
            "over_due" => "Over Due",
        ),
    ),
);

//==config untuk mutasi rekening
$config['accountMoveColumns'] = array(
    "Rekening" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "urut" => "no urut",
            "ids_his" => "reference number",
            "description" => "description",
            "jenis" => "keterangan",
//            "jenis_label" => "keterangan",
            "transaksi_no" => "invoice number",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "customerDetails__npwp" => "customer npwp",
            "oleh_nama" => "by",
            "cabang_nama" => "branch",


            "referenceNomer" => "cancelled number",
            "description_main_followup" => "vendor's invoice referral",
            "contra_account" => "contra accounts",

            "kredit" => "kredit",
            "debet" => "debet",
        ),
        "linkToDetail" => array(
            "suppliers_nama" => array(
                "key" => "suppliers_id",
                "rekening" => array(
                    // rekening (location page yang dibuka) => pairing arah link
                    "hutang dagang" => array(
                        "com" => "RekeningPembantuSupplier",
                        "rek" => "hutang dagang",
                    ),
                    "persediaan produk" => array(
                        "com" => "RekeningPembantuSupplier",
                        "rek" => "hutang dagang",
                    ),
                    "persediaan supplies" => array(
                        "com" => "RekeningPembantuSupplier",
                        "rek" => "hutang dagang",
                    ),
                ),
            ),
            "customers_nama" => array(
                "key" => "customers_id",
                "rekening" => array(
                    // rekening (location page yang dibuka) => pairing arah link
                    "piutang dagang" => array(
                        "com" => "RekeningPembantuCustomer",
                        "rek" => "piutang dagang",
                    ),
                    "persediaan produk" => array(
                        "com" => "RekeningPembantuCustomer",
                        "rek" => "piutang dagang",
                    ),
                    "persediaan supplies" => array(
                        "com" => "RekeningPembantuCustomer",
                        "rek" => "piutang dagang",
                    ),
                ),
            ),
        ),
        "baselink" => "Ledger/viewMoveDetails/",
        "extHistoryFields" => array(
            "review_details" => "transaksi_id",
        ),

        "viewedColumnsAdditional" => array(
            // rekening
            "kas" => array(
//                "pairRegistries" => "main",
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "cash_account" => array(
                        "cash_account__label" => NULL,
                        "cash_account_source__label" => "sumber kas",
                        "cash_account_target__label" => "kas tujuan",
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang cabang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang dagang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang biaya cabang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "piutang pembelian" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "uang muka dibayar" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "cash_account" => array(
                        "cash_account__label" => NULL,
                        "cash_account_source__label" => "sumber kas",
                        "cash_account_target__label" => "kas tujuan",
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),

            "persediaan produk" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "persediaan supplies" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),

            "hutang dagang" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "description_main_followup" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                    "description_main_followup" => array(
                        "description_main_followup" => NULL,
                    ),
                ),
            ),
            "hutang biaya" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang bank" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang aktiva tetap" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang ke pemegang saham" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang biaya bunga" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "hutang ke pusat" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
                    "description_main_followup" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                    "description_main_followup" => array(
                        "description_main_followup" => NULL,
                    ),
                ),
            ),

            "biaya bunga" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "biaya umum" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "biaya usaha" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "biaya produksi" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),
            "beban lain lain" => array(
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "cash_account" => "main",
                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
//                    "cash_account" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
//                        "cash_account_target__label" => "kas tujuan",
//                    ),
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
                    "details_item" => array(
                        "nama" => NULL,
                    ),
                ),
            ),

            "laba(rugi) perubahan grade produk" => array(
//                "pairRegistries" => "main",
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
//                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
//                        "cash_account__label" => NULL,
//                        "cash_account_source__label" => "sumber kas",
                        "referenceNomer" => NULL,
                    ),
//                    "details_item" => array(
//                        "nama" => NULL,
//                    ),
                ),
            ),
            "selisih persediaan karena fifo" => array(
//                "pairRegistries" => "main",
                "pairRegistries" => array("main", "items"),
                "sourceGate" => array(
                    "referenceNomer" => "main",
//                    "details_item" => "items",
                ),
                "kolom" => array(
//                    "cash_account" => "cash account",
//                    "details_item" => "details",
                ),
                "kolom_detail" => array(
                    // label => isi kolomnya
                    "referenceNomer" => array(
                        "referenceNomer" => NULL,
                    ),
//                    "details_item" => array(
//                        "nama" => NULL,
//                    ),
                ),
            ),
        ),
    ),
    "RekeningPembantuModal" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuKas" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis_label" => "keterangan",
            "suppliers_nama" => "vendor/supplier",
            "customers_nama" => "konsumen",
            "oleh_nama" => "pic",
            "description" => "description",
            "cash_account__merchant" => "merchant",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuPettycash" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "items_fields" => "isi",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            "qty_kredit_akhir" => "kredit bal (valas)",
            "qty_debet_akhir" => "debet bal (valas)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",
        ),

    ),
    "RekeningPembantuSupplier" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuLogamMulia" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuPiutangSupplierMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuPiutangSupplierDetailMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuPiutangSupplierDetailItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
            //            "kredit_akhir"      => "BAL (kredit)",
            //            "debet_akhir"       => "BAL (debet)",
        ),

    ),
    "RekeningPembantuBank" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCustomerLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuCustomer" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuCustomerDetail" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuAntarcabang" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaHarusDibayar" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuProduk" => array(
        "showValue" => false,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "description" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor/supplier",
            "customers_nama" => "customer",
            "cabang_nama" => "branch",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit (IDR)",
            "debet" => "debet (IDR)",
            //
            "qty_kredit_akhir" => "kredit bal (qty)",
            "qty_debet_akhir" => "debet bal (qty)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",

        ),
        "headerLooping" => array(
            "unit" => array(
                "label" => "unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "unitPrice" => array(
                "label" => "price per unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "sumPrice" => array(
                "label" => "total value",
                "attrHeader" => "class='bg-info text-center'",
            ),
        ),

    ),
    "RekeningPembantuCustomerValas" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "customer name",
            "extern2_nama" => "valas name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (valas)",
            "qty_debet" => "debet (valas)",
            "kredit" => "kredit (IDR)",
            "debet" => "debet (IDR)",
            //
            "qty_kredit_akhir" => "kredit bal (valas)",
            "qty_debet_akhir" => "debet bal (valas)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",

        ),

    ),
    "RekeningPembantuSupplies" => array(
        "showValue" => false,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "cabang_nama" => "branch",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit (IDR)",
            "debet" => "debet (IDR)",
            //
            "qty_kredit_akhir" => "kredit bal (qty)",
            "qty_debet_akhir" => "debet bal (qty)",
            "kredit_akhir" => "kredit bal (IDR)",
            "debet_akhir" => "debet bal (IDR)",
        ),
        "headerLooping" => array(
            "unit" => array(
                "label" => "unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "unitPrice" => array(
                "label" => "price per unit",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "sumPrice" => array(
                "label" => "total value",
                "attrHeader" => "class='bg-info text-center'",
            ),
        ),
    ),
    "RekeningPembantuEfisiensi" => array(
        "showValue" => false,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiaya" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBebanLainLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaUmum" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaUsaha" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuBiayaProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuHutangAktivaTetapDc" => array(
        "showValue" => false,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuDepresiasi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuAktivaBerwujud" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuPph" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuPphMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),

    ),
    "RekeningPembantuUangMuka" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "extern2_nama" => "relasi po",
            "kredit" => "kredit",
            "debet" => "debet",
            "keterangan" => "description",
        ),
    ),
    "RekeningPembantuUangMukaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuUangMukaMainReference" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuUangMukaExternMain" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuUangMukaReference" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "oleh_nama" => "by",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuBiayaSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuSewa" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuBiayaImport" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanKendaraan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanBangunan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAktivaBelumDitempatkan" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangSaham" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangPihakLain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuHutangBiayaBunga" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
//            "suppliers_nama" => "vendor",
//            "customers_nama" => "customer",
            "oleh_nama" => "by",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLoanItem" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuCreditNote" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "note",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
//    "RekeningPembantuPph" => array(
//        "showValue" => true,
//        "showQty" => false,
//        "viewedColumns" => array(
//            "extern_nama" => "account name",
//            "dtime" => "date",
//            "keterangan" => "description",
//            "jenis" => "tCode",
//            "qty_kredit" => "kredit (qty)",
//            "qty_debet" => "debet (qty)",
//            "kredit" => "kredit",
//            "debet" => "debet",
//        ),
//    ),
    "RekeningPembantuEfisiensiBiayaMain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuEfisiensiBiaya" => array(
        "showValue" => true,
        "showQty" => true,
        "viewedColumns" => array(
            "extern_nama" => "account name",
            "dtime" => "date",
            "keterangan" => "description",
            "jenis" => "tCode",
            "qty_kredit" => "kredit (qty)",
            "qty_debet" => "debet (qty)",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),

    "RekeningPembantuRelasiRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "tCode",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuRekeningKoran" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "tCode",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "RekeningPembantuLRLainlain" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "tCode",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
    "PaymentAntisourceCustomer" => array(
        "showValue" => true,
        "showQty" => false,
        "viewedColumns" => array(
//            "extern_nama" => "account name",
            "dtime" => "date",
            "jenis" => "tCode",
            "keterangan" => "description",
            "kredit" => "kredit",
            "debet" => "debet",
        ),
    ),
);

$config['accountBalanceProtections'] = array(
//    "kas",
//    "valas",
//    "persediaan produk",
//    "persediaan produk riil",
//    "persediaan produk rakitan",
//    "persediaan supplies",
//    "persediaan supplies riil",
//
//    "piutang dagang",
//    "piutang cabang",
//    "piutang pembelian",
//    "piutang valas",
//    "piutang supplier",
//    "hutang ke konsumen",
//    "hutang ke pusat",
//    "hutang dagang",
//    "hutang bpjs",
//    "hutang aktiva tetap",
//    "piutang aktiva tetap cabang",
//    "aktiva belum ditempatkan",
//    "uang muka",
//    "uang muka dibayar",
//    "uang muka valas",

    "1010010010",//    "kas",
    "1010010020",//    "valas",
    "1010010030",//    "credit note",

    "1010020010",//Piutang Usaha Lokal
    "1010020070",//Piutang Usaha lain
    "1010020090",//Piutang Usaha Lokal
    "1010025010",//logam mulia

    "1010030030",//Persediaan Produk
    "1010030040",//Persediaan Produk Riil
    "1010030060",//Persediaan Project Cost
    "1010030070",
    "1010030010",
    "1010030020",
    "1010060010",//Piutang Cabang
    "1010020030",
    "1010020040",
//    "piutang supplier",
    "2010050",//Hutang Ke Konsumen
    "2010120",//Hutang komisi

    "2040010",//    "hutang ke pusat",
    "2040020",//    "hutang biaya ke pusat",
    "2040030",//Hutang Aktiva Tetap Pada Dc
    "2040040",//Hutang Ke Cabang

    "2010010",
    "2010060",
    "2010030",
    "2010080",
    "1010060020",//Piutang Aktiva Tetap Cabang
    "1020070010",
//    "uang muka",
    "1010050010",
    "1010050020",
    "1010050030",
    "1010050040",
    "1010060030",//Piutang Ke Pusat
    "1010060040",//Piutang Biaya Cabang

    "8040",
    "8050",
);
$config['accountBalanceConsolidation'] = array(
    "piutang cabang",
    "hutang ke pusat",
    "piutang biaya cabang",
    "hutang biaya ke pusat",
    "piutang ke pusat",
    "hutang ke cabang",
    "piutang aktiva tetap cabang",
    "hutang aktiva tetap pada dc",

    //----
//    "1010060010",
//    "2040010",
//    "1010060040",
//    "2040020",
//    "1010060030",
//    "2040040",
//    "1010060020",
//    "2040030",
    //----label persediaan riil
//    "1010030020",
//    "1010030040",
    //----
//    "1010060050",
//    "2040050",

);

$config['accountBalanceColumLocker'] = array(
    "RekeningPembantuKas" => array(
        "enabledView" => true,
        "mdlName" => "MdlLockerValue",
        "state" => array(
            "hold" => array(
                "label" => "deposit in transit (idr)",
                "filters" => array(
                    "jenis=.kas",
                    "state=.hold",
                    "transaksi_id >0",
//                    "nilai >0"
                ),
                "viewedColums" => array(
                    "nilai" => "on proses otorisasi",
                ),
            ),
            "active" => array(
                "label" => "effective balance (idr)",
                "filters" => array(
                    "jenis=.kas",
                    "state=.active",
                    "transaksi_id=.0",
                ),
                "viewedColums" => array(
                    "nilai" => "available",
                ),
            ),

        ),

        "mdlNameRekeningCache" => "ComRekeningPembantuKas",
        "filter" => array(
            "periode='forever'",
            "rekening='kas'",
        ),
        "label" => "deposit in transit",
    ),
    "RekeningPembantuProduk" => array(
        "enabledView" => false,
        "mdlName" => "MdlLockerStock",
        "state" => array(
            "hold" => array(
                "label" => "stock in transit",
                "filters" => array(
                    "jenis=.produk",
                    "state=.hold",
                    "transaksi_id >0",
                ),
                "viewedColums" => array(
                    "jumlah" => "",
                ),
            ),
            "active" => array(
                "label" => "effective stock",
                "filters" => array(
                    "jenis=.produk",
                    "state=.active",
                    "transaksi_id=.0",
                ),
                "viewedColums" => array(
                    "jumlah" => "",
                ),
            ),

        ),

        "mdlNameRekeningCache" => "ComRekeningPembantuProduk",
        "filter" => array(
            "periode='forever'",
            "rekening='persediaan produk'",
        ),
        "label" => "stock in transit",
    ),
);

$config['accountRekeningBypass'] = array(
    "hutang biaya",
    "kas",
);

//ini yang baru, tinggal ditukar saja dengan yang bawah--------------------------------------------------------
$config['accountElementMutasi'] = array(

    "center" => array(
        "creditAmount" => array(
            "label" => "Credit Note",
            "rekening" => "1010010030",
//            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCreditNote/1010010030/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuCreditNote",
            "source" => "debet",
            "detail_judul" => "Sisa Credit Note",
        ),
        "creditAmountReturnPembelian" => array(
            "label" => "Credit Note Return Pembelian",
            "rekening" => "1010020030",
            "sub_rekening" => "1010020030010",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuPiutangSupplierDetailItem/1010020030/1010020030010/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuPiutangSupplierDetailItem",
            "source" => "debet",
            "detail_judul" => "Sisa Credit Note Return Pembelian",
        ),
        "titipanNonRelasi" => array(
            "label" => "titipan relasi PO(non ppn)",
            "rekening" => "1010050010",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuUangMuka/1010050010/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuUangMuka",
            "source" => "debet",
            "detail" => array(
                "comName" => "ComRekeningPembantuUangMukaMainReference",
                "link" => "Ledger/loadBalances_l1/RekeningPembantuUangMukaMainReference/1010050010/",
            ),
            "detail_judul" => "Sisa Titipan Relasi PO (Non PPN)",
            "headers" => array(
                "dtime_um" => "tanggal titipan",
                "nomer_um" => "nomer titipan",
                "dtime_po" => "tanggal purchase order",
                "nomer_po" => "nomer purchase order",
                "debet" => "sisa titipan",
                "oleh_nama_um" => "pic titipan",
            ),
            "pairedTransaksi" => "MdlTransaksi",
        ),
        "titipanRelasi" => array(
            "label" => "titipan non relasi PO(non ppn)",
            "rekening" => "1010050040",
//            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuUangMuka/1010050040/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuUangMuka",
            "source" => "debet",
            "detail_judul" => "Sisa Titipan Non Relasi PO (Non PPN)",
        ),
        "uangMuka" => array(
            "label" => "Uang Muka PPN",
            "rekening" => "1010050030",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuUangMuka/1010050030/",
            "allowed" => array("489", "487", "462", "1462", "483"),
            "comName" => "ComRekeningPembantuUangMuka",
            "source" => "debet",
            "detail" => array(
                "comName" => "ComRekeningPembantuUangMukaMainReference",
                "link" => "Ledger/loadBalances_l1/RekeningPembantuUangMukaMainReference/1010050030/",
            ),
            "detail_judul" => "Sisa Uang Muka PPN",
            "headers" => array(
                "dtime_um" => "tanggal Uang Muka",
                "nomer_um" => "nomer Uang Muka",
                "dtime_po" => "tanggal purchase order",
                "nomer_po" => "nomer purchase order",
                "debet" => "sisa Uang Muka",
                "oleh_nama_um" => "pic Uang Muka",
            ),
            "pairedTransaksi" => "MdlTransaksi",
        ),
        "diskonKhusus" => array(
            "label" => "Cadangan Diskon",
            "rekening" => "8050",
//            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuSupplier/8050/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuSupplier",
            "source" => "kredit",
            "detail_judul" => "Sisa Uang Muka PPN",
        ),
//        "saldoHutangUsaha" => array(
//            "label" => "Saldo Hutang",
//            "rekening" => "2010010",
////            "sub_rekening" => "2010050050",
//            "link" => "Ledger/loadMoveDetails/RekeningPembantuSupplier/2010010/",
//            "allowed" => array("489"),
//            "comName" => "ComRekeningPembantuSupplier",
//            "source" => "kredit",
//        ),
    ),

    "branch" => array(
        "uangMuka" => array(
            "label" => "Uang Muka Tanpa Ppn",
            "rekening" => "2010050",
            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/2010050/",
            "allowed" => array("749", "4464"),
            "comName" => "ComRekeningPembantuCustomerDetail",
            "source" => "kredit",
            "cabang_id" => "-1",
        ),
        "creditAmount" => array(
            "label" => "Credit Note(Deposit) Return Penjualan",
            "rekening" => "2010050",
            "sub_rekening" => "2010050040",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/2010050/",
            "allowed" => array("749"),
            "comName" => "ComRekeningPembantuCustomerDetail",
            "source" => "kredit",
        ),
        "customerDetails" => array(
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomer/1010020010/",
        ),
        "cash_account_source" => array(
            "source" => "cash_account_source",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuKas/1010010010/",
        ),
    ),

);
// ini yang lama, kolomnya belum banyak...
$config['accountElementMutasi__OLD'] = array(

    "center" => array(
        "creditAmount" => array(
            "label" => "Credit Note",
            "rekening" => "1010010030",
//            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCreditNote/1010010030/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuCreditNote",
            "source" => "debet",
            "detail_judul" => "Sisa Credit Note",
        ),
        "titipanNonRelasi" => array(
            "label" => "titipan relasi PO(non ppn)",
            "rekening" => "1010050010",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuUangMuka/1010050010/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuUangMuka",
            "source" => "debet",
            "detail" => array(
                "comName" => "ComRekeningPembantuUangMukaMainReference",
                "link" => "Ledger/loadBalances_l1/RekeningPembantuUangMukaMainReference/1010050010/",
            ),
            "detail_judul" => "Sisa Titipan Relasi PO (Non PPN)",
        ),
        "titipanRelasi" => array(
            "label" => "titipan non relasi PO(non ppn)",
            "rekening" => "1010050040",
//            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuUangMuka/1010050040/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuUangMuka",
            "source" => "debet",
            "detail_judul" => "Sisa Titipan Non Relasi PO (Non PPN)",
        ),
        "uangMuka" => array(
            "label" => "Uang Muka PPN",
            "rekening" => "1010050030",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuUangMuka/1010050030/",
            "allowed" => array("489", "487", "462", "1462", "483"),
            "comName" => "ComRekeningPembantuUangMuka",
            "source" => "debet",
            "detail" => array(
                "comName" => "ComRekeningPembantuUangMukaMainReference",
                "link" => "Ledger/loadBalances_l1/RekeningPembantuUangMukaMainReference/1010050030/",
            ),
            "detail_judul" => "Sisa Uang Muka PPN",
        ),
        "diskonKhusus" => array(
            "label" => "Cadangan Diskon",
            "rekening" => "8050",
//            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuSupplier/8050/",
            "allowed" => array("489", "487", "462", "1462", "483", "1424"),
            "comName" => "ComRekeningPembantuSupplier",
            "source" => "kredit",
            "detail_judul" => "Sisa Uang Muka PPN",
        ),
//        "saldoHutangUsaha" => array(
//            "label" => "Saldo Hutang",
//            "rekening" => "2010010",
////            "sub_rekening" => "2010050050",
//            "link" => "Ledger/loadMoveDetails/RekeningPembantuSupplier/2010010/",
//            "allowed" => array("489"),
//            "comName" => "ComRekeningPembantuSupplier",
//            "source" => "kredit",
//        ),
    ),

    "branch" => array(
        "uangMuka" => array(
            "label" => "Uang Muka Tanpa Ppn",
            "rekening" => "2010050",
            "sub_rekening" => "2010050050",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/2010050/",
            "allowed" => array("749"),
            "comName" => "ComRekeningPembantuCustomerDetail",
            "source" => "kredit",
        ),
        "creditAmount" => array(
            "label" => "Credit Note(Deposit) Return Penjualan",
            "rekening" => "2010050",
            "sub_rekening" => "2010050040",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomerDetail/2010050/",
            "allowed" => array("749"),
            "comName" => "ComRekeningPembantuCustomerDetail",
            "source" => "kredit",
        ),
        "customerDetails" => array(
            "link" => "Ledger/loadMoveDetails/RekeningPembantuCustomer/1010020010/",
        ),
        "cash_account_source" => array(
            "source" => "cash_account_source",
            "link" => "Ledger/loadMoveDetails/RekeningPembantuKas/1010010010/",
        ),
    ),

);


//config yang dibaca oleh pajak
$config['pairPajak'] = array(
    //akan diselect dari session login
    "pkp" => array(
        "overWriteValidate" => array(
            "barang" => array(
                "purcashing" => array(
                    "pkp" => "11",
                    "non_pkp" => "0",
                ),
                "sales" => array(
                    "pkp" => "11",
                    "non_pkp" => "0",
                ),
            ),
            "jasa" => array(
                "purcashing" => array(
                    "npwp" => "11",
                    "non_pkp" => "0",
                ),
                "sales" => array(
                    "pkp" => "11",
                    "non_pkp" => "0",
                ),
            ),
        ),
        "over_write" => array(
            //list Transksi pengecualian true akan di over ride dengan ppn 0%, untuk jembatan pkp tapi ppn 0
            //            "582" => "enable",
            //            "580" => "true",
            //            "466" => "true",
            //            "461" => "true",
            //            "2463" => "true",
        ),
        "value" => array(
            "enable" => array("ppnFactor" => "0"),//pkp tapi ppn 0%
            /*---akan masuk ke session login----------*/
            "default" => array(
                "ppnFactor" => "11",
                /*-----bila ada yg old akan ada pilihan di ui pembayaran-----*/
                // "ppnFactorOld" => "11"
            ),//default
            "minimal" => array("ppnFactorMinimal" => "10"),
        ),


    ),

    "non_pkp" => array(
        "overWriteValidate" => array(
            "purcashing" => array(
                "pkp" => "0",//untuk hitung gerbang ppn
                "non_pkp" => "0",
            ),
            "sales" => array(
                "pkp" => "0",//untuk hitung gerbang ppn
                "non_pkp" => "0",
            ),
        ),
        "over_write" => array(
            //listransksi pengecualian
            //            "582"=>"false",
            //            "580"=>"false",
            //            "466"=>"true",
            //            "461"=>"false",
        ),
        "value" => array(
            "enable" => array("ppnFactor" => "0"),//pkp tapi ppn 0%
            "default" => array("ppnFactor" => "0"),//default
        ),
    ),
);
//-----------------------------------------------------
$config['accountChildUmumItem'] = array(
    "1010010" => "RekeningPembantuKasSetaraKas",//kas setara kas
    "1010010010" => "RekeningPembantuKas",//kas
//    "010102" => "RekeningPembantuValas",//valas
//    "010304" => "RekeningPembantuProduk",//persediaan produk
//    "020202" => "RekeningPembantuBank",//hutang bank
//    "020101" => "RekeningPembantuSupplier",//hutang dagang
//    "01040105" => "RekeningPembantuSupplier",//
//    "01040107" => "RekeningPembantuSupplier",//
//    "010405" => "RekeningPembantuSupplier",//piutang pembelian
//    "020201" => "RekeningPembantuHutangSaham",//hutang ke pemegang saham
//    "020401" => "RekeningPembantuAntarcabang",//hutang ke pusat
//    "010201" => "RekeningPembantuCustomer",//piutang dagang
    "3010020" => "RekeningPembantuModal",//modal
    "1010010040" => "RekeningPembantuKas",//pettycash
//    "011002" => "RekeningPembantuAntarcabang",//piutang cabang
//    "0109" => "RekeningPembantuAntarcabang",//piutang biaya cabang
//    "020402" => "RekeningPembantuAntarcabang",//hutang biaya ke pusat
//    "1003" => "RekeningPembantuPendapatanLainLain",//pendapatan beban lain ke pusat
//    "1004" => "RekeningPembantuBebanLainLain",//beban lain ke pusat
//    "07" => "RekeningPembantuBiayaUsaha",// beban usaha ke pusat
//    "08" => "RekeningPembantuBiayaUmum",//beban umum ke pusat
//    "09" => "RekeningPembantuBiayaOperasional",// beban Operasiona ke pusat
    "7010" => "RekeningPembantuLRLainlain",
    //-----
    "1020010010" => "RekeningPembantuAktivaBerwujud",//kendaraan
    "1020020010" => "RekeningPembantuAktivaBerwujud",//Peralatan Kantor
    "1020050010" => "RekeningPembantuAktivaBerwujud",//Bangunan
    "1020060010" => "RekeningPembantuAktivaBerwujud",//Tanah
);
$config['accountKasInAllowed'] = array(
    "3010020",// => "RekeningPembantuModal",//modal
    "7010",// => "RekeningPembantuLRLainlain",//modal
);

$config['accountModalAllowed'] = array(
//    "3010020",// => "RekeningPembantuModal",//modal
    "1010010",// => "RekeningPembantuLRLainlain",//modal

    "1020010010",//kendaraan
    "1020020010",//Peralatan Kantor
    "1020050010",//Bangunan
    "1020060010",//Tanah
);

//--------------------------------------
$config['pemindahbukuan'] = array(
    // kas
    "1010010010" => array(
        "LockerValue" => array(
            "comName" => "LockerValueItem",
            "jenis_locker" => "kas",
        ),
    ),
    // persediaan produk
    "1010030030" => array(
        "LockerStock" => array(
            "comName" => "LockerStock",
            "jenis_locker" => "stock",
        ),
        "LockerStockMutasi" => array(
            "comName" => "LockerStockMutasi",
            "jenis_locker" => "stock",
        ),
        "FifoAverage" => array(
            "comName" => "FifoAverage",
            "jenis_locker" => "produk",
        ),
    ),
    // persediaan bahan baku
    "1010030010" => array(
        "LockerStock" => array(
            "comName" => "LockerStock",
            "jenis_locker" => "stock",
        ),
        "FifoAverage" => array(
            "comName" => "FifoAverage",
            "jenis_locker" => "supplies",
        ),
    ),
);

//--------------------------------------
$config['pemindahbukuanTransisi'] = array(
    // hutang dagang
    "2010010" => array(
        "LockerValue" => array(
            "comName" => "LockerValueItem",
            "jenis_locker" => "hutang dagang transisi",
        ),
    ),
    // piutang dagang
    "1010020" => array(
        "LockerValue" => array(
            "comName" => "LockerValueItem",
            "jenis_locker" => "piutang dagang transisi",
        ),
    ),
);
//--------------------------------------
$config['shortItemsFields'] = array(
    "nama" => array(
        "label" => "nama",
        "addKey" => "keterangan",
    ),
    "harga" => "nilai",
);

$config['accountMinusAllowedJenisTr'] = array(
    "rekening" => array(
        "1010060010",// piutang cabang
        "2040010",// hutang ke pusat
        "1010060040",// piutang biaya cabang
        "2040020",// hutang biaya ke pusat
        "1010060020",// piutang aktiva tetap cabang
        "2040030",// hutang aktiva tetap ke dc
        "1010060030",// piutang ke pusat
        "2040040",// hutang ke cabang
        "8040",//transisi cadangan diskon
        "8050",
    ),
    "jenisTransaksi" => array(
        "9855",//return distribusi project
        "5855",//distribusi project
        "1985",//return distribusi
        "585",//distribusi
        "749",//penerimaan piutang
        "4464",//penerimaan penjualan tunai
        "4467",//uang muka konsumen
    ),
);

$config['kodePajak'] = array(
    "01" => "Kode faktur pajak 010 adalah digunakan untuk Penyerahan Barang Kena Pajak ( BKP ) atau Jasa Kena Pajak (JKP) yang PPN-nya terutang dipungut oleh PKP penjual.",
    "02" => "Kode faktur pajak 020 adalah digunakan jika Penyerahan BKP atau JKP kepada pemungut PPN seperti bendahara pemerintah, BUMN, badan usaha tertentu, yang PPN-nya dipungut oleh pemungut PPN bendahara pemerintah.",
    "03" => "Kode faktur pajak 030 adalah digunakan untuk Penyerahan BKP/JKP kepada pemungut PPN lainnya selain bendahara pemerintah, dan PPN-nya dipungut oleh pemungut PPN lainnya selain bendahara pemerintah.",
    "04" => "Kode faktur pajak 040 adalah digunakan untuk Penyerahan BKP/JKP yang menggunakan DPP nilai lain yang PPNnya dipungut oleh PKP penjual yang melakukan penyerahan.",
    "05" => "Tidak digunakan.",
    "06" => "Kode faktur pajak 060 adalah digunakan untuk penyerahan lainnya dan PPN-nya dipungut oleh PKP penjual yang menyerahkan BKP/JKP, dan juga penyerahan BKP/JKP dilakukan kepada orang pribadi pemegang paspor luar negeri sesuai ketentuan dalam Pasal 16E UU PPN.",
    "07" => "Kode faktur pajak 070 adalah digunakan untuk Penyerahan BKP/JKP yang mendapat fasilitas PPN Tidak Dipungut atau Ditanggung Pemerintah (DTP).",
    "08" => "Kode faktur pajak 080 adalah digunakan untuk penyerahan BKP/JKP yang mendapat fasilitas bebas PPN.",
    "09" => "Kode faktur pajak 090 adalah digunakan untuk penyerahan aktiva Pasal 16D yang PPN-nya dipungut oleh PKP penjual yang menyerahkan BKP.",
);


?>