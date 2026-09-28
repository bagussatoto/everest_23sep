<?php


class MdlRevertJurnal extends MdlMother
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='payment'",
//        "status='1'",
//        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "id_master" => array(
            "label" => "id_master",
            "type" => "int", "length" => "24", "kolom" => "id_master",
            "inputType" => "text",
        ),
        "value_src" => array(
            "label" => "value_src",
            "type" => "int", "length" => "255", "kolom" => "value_src",
            "inputType" => "text",
        ),
        "revertStep" => array(
            "label" => "revertStep",
            "type" => "int", "length" => "3", "kolom" => "revertStep",
            "inputType" => "text",
        ),
        "revertRequest" => array(
            "label" => "revertRequest",
            "type" => "int",
            "length" => "255",
            "kolom" => "revertRequest",
            "inputType" => "text",
        ),

    );
    protected $staticData = array(

        //ADJUSTMENT------

//        array(
//            "id" => "999",//di isi jenis
//            "id_master" => "999",//di isi jenis master
//            "nama" => "ADJUSTMENT",
//            "value_src" => "nett",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        array(
//            "id" => "9912",//di isi jenis
//            "id_master" => "9912",//di isi jenis master
//            "nama" => "PEMBATALAN TRANSACTION (BRANCH)",
//            "value_src" => "nett",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //FG PURCHASING IMPORT------

//        array(
//            "id" => "460",//di isi jenis
//            "id_master" => "460",//di isi jenis master
//            "nama" => "FG PURCHASING (IMPORT)",
//            "value_src" => "nett",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //FG AP PAYMENT------
        array(
            "id" => "489",//di isi jenis
            "id_master" => "489",//di isi jenis
            "nama" => "FG A/P PAYMENT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),
        array(
            "id" => "487",//di isi jenis
            "id_master" => "487",//di isi jenis
            "nama" => "SUPPLIES A/P PAYMENT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),

        //FG A/P PAYMENT IMPORT------
//        array(
//            "id" => "4891",//di isi jenis
//            "id_master" => "4891",//di isi jenis
//            "nama" => "FG A/P PAYMENT IMPORT",
//            "value_src" => "nilai_bayar",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        // PPh Ps 21 A/P PAYMENT
        array(
            "id" => "1483",//di isi jenis
            "id_master" => "1483",//di isi jenis
            "nama" => "PPh Ps 21 A/P PAYMENT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // BPJS A/P PAYMENT
        array(
            "id" => "1487",//di isi jenis
            "id_master" => "1487",//di isi jenis
            "nama" => "BPJS A/P PAYMENT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // HUTANG GAJI A/P PAYMENT
        array(
            "id" => "1485",//di isi jenis
            "id_master" => "1485",//di isi jenis
            "nama" => "HUTANG GAJI A/P PAYMENT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),

        //uang muka valas di onkan  7/28/2022 awalnya off, lihat dari data backup masih on

//        array(
//            "id" => "4466",//di isi jenis
//            "id_master" => "4466",//di isi jenis
//            "nama" => "UANG MUKA VALAS",
//            "value_src" => "nilai_bayar",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        array(
            "id" => "111",//di isi jenis
//            "id_master" => "466",//di isi jenis
            "id_master" => "111",//di isi jenis
            "nama" => "REALISASI PPN MASUKAN",
            "value_src" => "selisih_ppn_realisasi",
//            "revertStep" => false,
            "revertStep" => true,
            "detailGate" => null,
        ),
        // PEMBELIAN FG REGULER
        array(
            "id" => "467",//di isi jenis
            "id_master" => "466",//di isi jenis
            "nama" => "FG PURCHASING",
            "value_src" => "nett",
//            "revertStep" => false,
            "revertStep" => true,
            "detailGate" => null,
        ),
        // RETURN PEMBELIAN FG REGULER
        array(
            "id" => "967",//di isi jenis
            "id_master" => "967",//di isi jenis
            "nama" => "FG PURCHASING RETURN",
            "value_src" => "nett",
//            "revertStep" => false,
            "revertStep" => true,
            "detailGate" => null,
        ),
        // PEMBELIAN SUPPLIES REGULER
        array(
            "id" => "461",//di isi jenis
            "id_master" => "461",//di isi jenis
            "nama" => "SUPPLIES PURCHASING",
            "value_src" => "nett",
//            "revertStep" => false,
            "revertStep" => true,
            "detailGate" => null,
        ),
        //PEMBELIAN ASET
        array(
            "id" => "423",//di isi jenis
            "id_master" => "421",//di isi jenis
            "nama" => "ASET PURCHASING",
            "value_src" => "nilai_persediaan",
//            "revertStep" => false,
            "revertStep" => false,
            "detailGate" => null,
        ),

        //SERVICE A/P PAYMENT
        array(
            "id" => "462",//di isi jenis
            "id_master" => "462",//di isi jenis
            "nama" => "SERVICE A/P PAYMENT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //SERVICE A/P PAYMENT PUSAT
        array(
            "id" => "1462",//di isi jenis
            "id_master" => "1462",//di isi jenis
            "nama" => "SERVICE A/P PAYMENT PUSAT",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //SERVICE PURCASHING
        array(
            "id" => "463",//di isi jenis
            "id_master" => "463",//di isi jenis
            "nama" => "SERVICE PURCASHING",
            "value_src" => "nett",
            "revertStep" => true,
            "detailGate" => null,
        ),
        //SERVICE PURCASHING (PUSAT)
        array(
            "id" => "1463",//di isi jenis
            "id_master" => "1463",//di isi jenis
            "nama" => "SERVICE PURCASHING (PUSAT)",
            "value_src" => "nett",
            "revertStep" => true,
            "detailGate" => null,
        ),

        //-----------pematalan srn po projek------------
        //SERVICE PROJECT RECEIVED NOTE
        array(
            "id" => "3463",//di isi jenis
            "id_master" => "3463",//di isi jenis
            "nama" => "SERVICE PROJECT RECEIVED NOTE",
            "value_src" => "harga_disc",
            "revertStep" => true,
            "detailGate" => null,
        ),
        //SERVICE PROJEK A/P PAYMENT
        array(
            "id" => "483",//di isi jenis
            "id_master" => "483",//di isi jenis
            "nama" => "SERVICE PROJEK A/P PAYMENT",
            "value_src" => "biaya_jasa",
            "revertStep" => false,
            "detailGate" => null,
        ),

        //PENAMBAHAN HUTANG BANK
//        array(
//            "id" => "444",//di isi jenis
//            "id_master" => "444",//di isi jenis
//            "nama" => "PENAMBAHAN HUTANG BANK",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//        //PENAMBAHAN HUTANG KE PEMEGANG SAHAM
//        array(
//            "id" => "446",//di isi jenis
//            "id_master" => "446",//di isi jenis
//            "nama" => "PENAMBAHAN HUTANG KE PEMEGANG SAHAM",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//        //IMBALAN JASA
//        array(
//            "id" => "119",//di isi jenis
//            "id_master" => "119",//di isi jenis
//            "nama" => "IMBALAN JASA",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//        //IMBALAN JASA A/P PAYMENT
//        array(
//            "id" => "2119",//di isi jenis
//            "id_master" => "2119",//di isi jenis
//            "nama" => "IMBALAN JASA A/P PAYMENT",
//            "value_src" => "nilai_entry",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//        //REALISASI PPN MASUKAN(PO FG)
//        array(
//            "id" => "111",//di isi jenis
//            "id_master" => "466",//di isi jenis
//            "nama" => "REALISASI PPN MASUKAN(PO FG)",
//            "value_src" => "ppn",
//            "revertStep" => true,
//            "detailGate" => null,
//        ),

//        //REALISASI PPN MASUKAN(PO JASA)
//        array(
//            "id" => "113",//di isi jenis
//            "id_master" => "463",//di isi jenis
//            "nama" => "REALISASI PPN MASUKAN(PO JASA)",
//            "value_src" => "ppn",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //REALISASI PPN KELUARAN
        array(
            "id" => "110",//di isi jenis
            "id_master" => "110",//di isi jenis
            "nama" => "OTORISASI ENTRY E-FAKTUR PPN KELUARAN",
            "value_src" => "ppn",
            "revertStep" => true,// true -> pembatalan mundur 1 langkah
            "detailGate" => null,
        ),
//
//        //PPH PS4(2)
//        array(
//            "id" => "118",//di isi jenis
//            "id_master" => "118",//di isi jenis
//            "nama" => "PPH PS4(2)",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //PPH PS 25
//        array(
//            "id" => "117",//di isi jenis
//            "id_master" => "117",//di isi jenis
//            "nama" => "PPH PS 25",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //PETTYCASH REFILL (BRANCH)
        array(
            "id" => "771",//di isi jenis
            "id_master" => "771",//di isi jenis
            "nama" => "PETTYCASH REFILL (BRANCH)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => "items",
        ),
        //PETTYCASH REFILL (CENTER)
        array(
            "id" => "1771",//di isi jenis
            "id_master" => "1771",//di isi jenis
            "nama" => "PETTYCASH REFILL (CENTER)",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => "items",
        ),
//        //TRANSFER EXPENSE TO GENERAL EXPENSE
//        array(
//            "id" => "9983",//di isi jenis
//            "id_master" => "9983",//di isi jenis
//            "nama" => "TRANSFER EXPENSE TO GENERAL EXPENSE",
//            "value_src" => "harga_disc",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //TRANSFER EXPENSE TO MARKETING EXPENSE
//        array(
//            "id" => "9982",//di isi jenis
//            "id_master" => "9982",//di isi jenis
//            "nama" => "TRANSFER EXPENSE TO MARKETING EXPENSE",
//            "value_src" => "harga_disc",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //TRANSFER EXPENSE TO PRODUCTION EXPENSE
//        array(
//            "id" => "9984",//di isi jenis
//            "id_master" => "9984",//di isi jenis
//            "nama" => "TRANSFER EXPENSE TO PRODUCTION EXPENSE",
//            "value_src" => "harga_disc",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //import expense A/P payment
//        array(
//            "id" => "652",//di isi jenis
//            "id_master" => "652",//di isi jenis
//            "nama" => "import expense A/P payment",
//            "value_src" => "nilai_entry",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//        array(
//            "id" => "444",//di isi jenis yang akan di batalkan
//            "id_master" => "444",//di isi jenis master
//            "nama" => "penambahan hutang",
//        ),

        //PENERIMAAN SETORAN
//        array(
//            "id" => "758",//di isi jenis
//            "id_master" => "758",//di isi jenis
//            "nama" => "PENERIMAAN SETORAN",
//            "value_src" => "nilai_entry",
//            "revertStep" => true,
//            "detailGate" => "items",
//        ),
//        //PENERIMAAN SETORAN UANG MUKA
//        array(
//            "id" => "7758",//di isi jenis
//            "id_master" => "7758",//di isi jenis
//            "nama" => "PENERIMAAN SETORAN UANG MUKA",
//            "value_src" => "nilai_entry",
//            "revertStep" => true,
//            "detailGate" => "items",
//        ),
//        //PEMBIAYAAN SUPPLIES
//        array(
//            "id" => "762",//di isi jenis
//            "id_master" => "762",//di isi jenis
//            "nama" => " PEMBIAYAAN SUPPLIES",
//            "value_src" => "hpp",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //BIAYA LAIN LAIN
//        array(
//            "id" => "743",//di isi jenis
//            "id_master" => "743",//di isi jenis
//            "nama" => "BIAYA LAIN LAIN",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //PENDAPATAN LAIN LAIN
        array(
            "id" => "742",//di isi jenis
            "id_master" => "742",//di isi jenis
            "nama" => "PENDAPATAN LAIN LAIN",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),


        // ===========================================
        // ========= perlu dites dulu : OK ================
        //PEMBATALAN APPROVAL BIAYA USAHA
        array(
            "id" => "2677",//di isi jenis
            "id_master" => "2677",//di isi jenis
            "nama" => "OTORISASI BIAYA USAHA (BRANCH)",
            "value_src" => "subtotal", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),
//        //PEMBATALAN APPROVAL BIAYA PRODUKSI
//        array(
//            "id" => "2676",//di isi jenis
//            "id_master" => "2676",//di isi jenis
//            "nama" => "PEMBATALAN APPROVAL BIAYA PRODUKSI (BRANCH)",
//            "value_src" => "subtotal", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//        //PEMBATALAN APPROVAL BIAYA UMUM
        array(
            "id" => "2675",//di isi jenis
            "id_master" => "2675",//di isi jenis
            "nama" => "OTORISASI BIAYA UMUM (BRANCH)",
            "value_src" => "subtotal", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

//        //PEMBATALAN ASET PURCHASING
//        array(
//            "id" => "423",//di isi jenis
//            "id_master" => "421",//di isi jenis
//            "nama" => "PEMBATALAN ASET PURCHASING",
//            "value_src" => "subtotal", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //PEMBATALAN ASET A/P PAYMENT
        array(
            "id" => "4821",//di isi jenis yang dibatalkan
            "id_master" => "4821",//di isi jenis master
            "nama" => "PEMBATALAN ASET A/P PAYMENT",
            "value_src" => "subtotal", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

        //TAXES
//        array(
//            "id" => "681",//di isi jenis
//            "id_master" => "681",//di isi jenis
//            "nama" => "TAXES",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //EXPENSE PAYMENT (biaya umum pusat)
        array(
            "id" => "1475",//di isi jenis
            "id_master" => "1475",//di isi jenis
            "nama" => "EXPENSE PAYMENT (BIAYA UMUM PUSAT)",
            "value_src" => "nilai_bayar",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //EXPENSE PAYMENT (BIAYA USAHA PUSAT)
        array(
            "id" => "1477",//di isi jenis
            "id_master" => "1477",//di isi jenis
            "nama" => "EXPENSE PAYMENT (BIAYA USAHA PUSAT)",
            "value_src" => "nilai_bayar",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //EXPENSE PAYMENT(BIAYA UMUM)
        array(
            "id" => "475",//di isi jenis
            "id_master" => "475",//di isi jenis
            "nama" => "EXPENSE PAYMENT (BIAYA UMUM CABANG)",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //EXPENSE PAYMENT(BIAYA USAHA)
        array(
            "id" => "477",//di isi jenis
            "id_master" => "477",//di isi jenis
            "nama" => "EXPENSE PAYMENT (BIAYA USAHA CABANG)",
            "value_src" => "nilai_entry",
            "revertStep" => false,
            "detailGate" => null,
        ),

        //BIAYA GAJI PUSAT
        array(
            "id" => "1674",//di isi jenis
            "id_master" => "1674",//di isi jenis
            "nama" => "BIAYA GAJI (PUSAT)",
            "value_src" => "hutang_gaji",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //BIAYA GAJI CABANG
        array(
            "id" => "21674",//di isi jenis
            "id_master" => "21674",//di isi jenis
            "nama" => "OTORISASI BIAYA GAJI (CABANG)",
            "value_src" => "hutang_gaji",
            "detailGate" => null,
        ),
        //BIAYA BPJS/PPh21
        array(
            "id" => "7674",//di isi jenis
            "id_master" => "7674",//di isi jenis
            "nama" => "BIAYA BPJS / PPh Ps21",
            "value_src" => "hutang_gaji",
            "revertStep" => false,
            "detailGate" => null,
        ),


        //SALARY A/P PAYMENT
        array(
            "id" => "1485",//di isi jenis
            "id_master" => "1485",//di isi jenis
            "nama" => "GAJI A/P PAYMENT",
            "value_src" => "hutang_gaji",
            "revertStep" => false,
            "detailGate" => null,
        ),


//        //PENAMBAHAN NILAI ASSET
//        array(
//            "id" => "7620",//di isi jenis
//            "id_master" => "7620",//di isi jenis
//            "nama" => "PENAMBAHAN NILAI ASSET",
//            "value_src" => "hpp",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //APPROVAL BIAYA BUNGA
//        array(
//            "id" => "4449",//di isi jenis
//            "id_master" => "4449",//di isi jenis
//            "nama" => "APPROVAL BIAYA BUNGA",
//            "value_src" => "nilai_bunga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //UANG MUKA
        array(
            "id" => "464",//di isi jenis
            "id_master" => "464",//di isi jenis
            "nama" => "UANG MUKA KE SUPPLIER",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        array(
            "id" => "4643",//di isi jenis
            "id_master" => "4643",//di isi jenis
            "nama" => "TITIPAN KE SUPPLIER",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

        array(
            "id" => "1424",//di isi jenis
            "id_master" => "1424",//di isi jenis
            "nama" => "SEWA A/P PAYMENT (PUSAT)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

//        //Otorisasi biaya produksi
//        array(
//            "id" => "2676",//di isi jenis
//            "id_master" => "2676",//di isi jenis
//            "nama" => "Otorisasi biaya produksi",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//        //-- pembatalan otorisasi biaya usaha pusat
//        //Otorisasi penambahan hutang pihak lain
//        array(
//            "id" => "447",//di isi jenis
//            "id_master" => "447",//di isi jenis
//            "nama" => "Otorisasi penambahan hutang pihak lain",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//


//        array(
//            "id" => "3685",//di isi jenis
//            "id_master" => "3685",//di isi jenis master
//            "nama" => "STOCK TRANSFER RECEPTION",
//            "value_src" => "hpp",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        // PEMBIAYAAN SUPPLIES
        array(
            "id" => "7762",//di isi jenis
            "id_master" => "7762",//di isi jenis
            "nama" => "PEMBIAYAAN SUPPLIES",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //OTORISASI BIAYA USAHA
        array(
            "id" => "1677r",//di isi jenis
            "id_master" => "1677",//di isi jenis
            "nama" => "BIAYA USAHA (PUSAT)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        //BIAYA UMUM (PUSAT)
        array(
            "id" => "1675r",//di isi jenis
            "id_master" => "1675",//di isi jenis
            "nama" => "BIAYA UMUM (PUSAT)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

        // REALISASI KLAIM KE SUPPLIER
        array(
            "id" => "3333",//di isi jenis
            "id_master" => "3333",//di isi jenis
            "nama" => "REALISASI KLAIM KE SUPPLIER",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // INPUT KLAIM SUPPLIER (DADAKAN)
        array(
            "id" => "3344",//di isi jenis
            "id_master" => "3344",//di isi jenis
            "nama" => "INPUT KLAIM SUPPLIER (DADAKAN)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // INPUT OTORISASI PENGEMBALIAN UANG KE KONSUMEN
        array(
            "id" => "19467",//di isi jenis
            "id_master" => "19467",//di isi jenis
            "nama" => "OTORISASI PENGEMBALIAN UANG KE KONSUMEN",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
            "revertRequest" => true,
        ),
        // CASH BALANCE INTERCHANGE (PUSAT)
        array(
            "id" => "1757",//di isi jenis
            "id_master" => "1757",//di isi jenis
            "nama" => "CASH BALANCE INTERCHANGE (PUSAT)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
            "revertRequest" => true,
        ),
        // OTORISASI KONVERSI PRODUK (PUSAT)
        array(
            "id" => "1334",//di isi jenis
            "id_master" => "1334",//di isi jenis
            "nama" => "OTORISASI KONVERSI PRODUK (PUSAT)",
            "value_src" => "harga",
            "revertStep" => true,
            "detailGate" => null,
        ),


        // SETOR HUTANG PPh 23
        array(
            "id" => "115",//di isi jenis
            "id_master" => "115",//di isi jenis
            "nama" => "SETOR HUTANG PPh 23",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

        // otorisasi pengembalian uang ke konsumen
//        array(
//            "id" => "19467",//di isi jenis
//            "id_master" => "19467",//di isi jenis
//            "nama" => "OTORISASI PENGEMBALIAN UANG KE KONSUMEN",
//            "value_src" => "harga",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        // penerimaan kas dari deposit/creditnote
        //(return pembelian/klaim diskon supplier)
        array(
            "id" => "7467",//di isi jenis
            "id_master" => "7467",//di isi jenis
            "nama" => "PENERIMAAN KAS DARI DEPOSIT/CREDITNOTE<br>(RETURN PEMBELIAN/KLAIMDISKON SUPPLIER<br>TITIPAN TANPA RELASI PO)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // otorisasi cashback/komisi reguler
        array(
            "id" => "16677",//di isi jenis
            "id_master" => "16677",//di isi jenis
            "nama" => "OTORISASI CASHBACK/KOMISI PENJUALAN (BIAYA)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // otorisasi cashback/komisi project
        array(
            "id" => "16678",//di isi jenis
            "id_master" => "16678",//di isi jenis
            "nama" => "OTORISASI CASHBACK/KOMISI PROJECT (BIAYA)",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        // otorisasi pembayaran cashback/komisi
        array(
            "id" => "1488",//di isi jenis
            "id_master" => "1488",//di isi jenis
            "nama" => "KOMISI A/P PAYMENT",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

        // biaya project ap payment
        array(
            "id" => "3675",//di isi jenis
            "id_master" => "3675",//di isi jenis
            "nama" => "BIAYA PROJECT A/P PAYMENT",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        array(
            "id" => "1676",//di isi jenis
            "id_master" => "1676",//di isi jenis
            "nama" => "PENGELUARAN LOGAM MULIA",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

        array(
            "id" => "384",//di isi jenis
            "id_master" => "384",//di isi jenis
            "nama" => "PURCHASING VALAS AUTHORIZATION",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),
        array(
            "id" => "5844",//di isi jenis
            "id_master" => "5844",//di isi jenis
            "nama" => "PENJUALAN KE DIRI SENDIRI",
            "value_src" => "harga",
            "revertStep" => false,
            "detailGate" => null,
        ),

    );


    protected $listedFields = array(
        "nama" => "nama",
//        "due_days" => "due days",
        "status" => "status",

    );

    public function __construct()
    {

    }

    //region gs

    public function getStaticData()
    {
        return $this->staticData;
    }

    public function setStaticData($staticData)
    {
        $this->staticData = $staticData;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }
    //endregion


    //@override with static data
    public function lookupAll()
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }

    public function lookupByKeyword($key)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {

                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }
    }

    public function lookupByID($id)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->fields);
            $tmp = array();
            foreach ($this->staticData as $aSpec) {
//                arrPrint($aSpec);
                $arrNew = array();
//                if (in_array($id, $aSpec)) {
                if ($aSpec['id'] == $id) {
                    cekHitam($id . " ini array");
                    foreach ($this->fields as $fID => $fSpec) {
                        $arrNew[$fID] = $aSpec[$fID];
                    }
                    $tmp[] = $arrNew;
                }

            }

            foreach ($tmp as $iSpec) {
                if (in_array($id, $iSpec)) {
                    $iCtr++;
                    $sql .= 'SELECT ';
                    $fCtr = 0;
                    foreach ($this->fields as $fID => $fSpec) {
//                        cekHere($fID);
                        $fCtr++;
                        $sql .= "'" . $iSpec[$fID] . "' as $fID";
                        if ($fCtr < sizeof($this->fields)) {
                            $sql .= ",";
                        }
                    }
                    if ($iCtr < sizeof($tmp)) {
                        $sql .= " union ";
                    }
                }

            }

            return $this->db->query($sql);

//            arrPrint($arr);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }
}


