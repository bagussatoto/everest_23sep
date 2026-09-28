<?php

//--include_once "MdlHistoriData.php";
class MdlProdukBekas extends MdlMother
{
    protected $tableName = "produk";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='item_bekas'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "kode" => array("required","unique","singleOnly"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
//        "jenis"                => array(
//            "label"     => "id",
//            "type"      => "int",
//            "length"    => "24",
//            "kolom"     => "jenis",
//            "inputType" => "hidden",// hidden
//        ),
        "allow_project"     => array(
            "label"        => "ijinkan project",
            "type"         => "int", "length" => "24", "kolom" => "allow_project",
            "inputType"    => "combo",
            "checbox"          => true,
            // "checbox_disabled" => array("kategori_id" => 4),
            "checbox_fungsi" => "checkboxUpdProject",
            "dataSource"   => array(0 => "no", 1 => "yes"),
            "defaultValue" => 0,
            //--"inputName" => "status",
        ),
        "kapasitas"         => array(
            "label"      => "power PK/HP",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "kapasitas_id",
            "inputType"  => "combo",
            "reference"  => "MdlKapasitas",
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "kapasitas_nama",
            "add_btn"    => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
//        "kategori"          => array(
//            "label"      => "jenis",//kategori
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "kategori_id",
//            "inputType"  => "combo-hidden",
//            "reference"  => "MdlProdukKategori",
//            "strField"   => "nama",
//            "editable"   => false,
//            "kolom_nama" => "kategori_nama",
//            "add_btn"    => true,
//            "keterangan" => true,
//            /* ---------------------------------------------
//             * untuk relativ fungsi js belum dicoba
//             * ---------------------------------------------*/
//            // "event_js"   => "",
//        ),
//        "kategori_nama"     => array(
//            "label"      => "jenis",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "kategori_nama",
//            "inputType"  => "hidden",
//            "kolom_nama" => "kategori_nama",
//        ),
//        "sub_kategori"      => array(
//            "label"       => "sub jenis",//kategori
//            "type"        => "int",
//            "length"      => "255",
//            "kolom"       => "sub_kategori_id",
//            "indexFields" => "sub_kategori_id",
//            "inputType"   => "combo-hidden",
//            "reference"   => "MdlProdukSubKategori",
//            "strField"    => "label",
//            "editable"    => false,
//            "kolom_nama"  => "sub_kategori_nama",
//            "add_btn"     => true,
//            "keterangan"  => true,
//            /* ---------------------------------------------
//             * untuk relativ fungsi js belum dicoba
//             * ---------------------------------------------*/
//            // "event_js"   => "",
//        ),
//        "sub_kategori_nama" => array(
//            "label"      => "sub jenis",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "sub_kategori_nama",
//            "inputType"  => "hidden",
//            "kolom_nama" => "sub_kategori_nama",
//        ),
//        "jml_serial"        => array(
//            "label"            => "jml serial",
//            "type"             => "int",
//            "length"           => "32",
//            "kolom"            => "jml_serial",
//            "inputType"        => "text",
//            "editable"         => false,
//            "show"             => false,
//            "checbox"          => true,
//            "checbox_disabled" => array("kategori_id" => 4),
//            "checbox_fungsi" => "checkboxUpd",
//            //            "dataSource"   => array(1=>"single",0=>"non serial"),
//            "defaultValue"     => 0,
//            "dataSource"       => array(0 => "non serial", 1 => "serial"),
//            //            "editable"     => false,
//            // "reference" => "MdlProdukKategori",
//            // "strField"   => "nama",
//            // "editable"   => true,
//            // "kolom_nama" => "kategori_nama",
//            // "add_btn" => true,
//            // "keterangan" => true,
//        ),
//        "skala"             => array(
//            "label"      => "size/skala",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "size_id",
//            "inputType"  => "combo",
//            "reference"  => "MdlProdukSize",
//            "strField"   => "nama",
//            "editable"   => true,
//            "kolom_nama" => "size_nama",
//            "add_btn"    => true,
//            "mdlChild"   => array("tipe_id"),
//        ),
//        "skala_nama"        => array(
//            "label"     => "skala",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "size_nama",
//            "inputType" => "hidden",
//            // "kolom_nama" => "kategori_nama",
//        ),
//        "supplier"          => array(
//            "label"      => "supplier",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "supplier_id",
//            "inputType"  => "combo",
//            "reference"  => "MdlSupplier",
//            "strField"   => "nama",
//            "kolom_nama" => "supplier_nama",
//            "editable"   => true,
//            "add_btn"    => true,
//            "mdlChild"   => array("merek_nama"),
//            // "childDefault"   => "MdlMerek"
//        ),
//        "supplier_nama"     => array(
//            "label"     => "supplier",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "supplier_nama",
//            "inputType" => "hidden",
//        ),
//        "kapasitas_nama"    => array(
//            "label"     => "kapasitas",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "kapasitas_nama",
//            "inputType" => "hidden",
//            // "kolom_nama" => "kategori_nama",
//        ),
//        "merek"             => array(
//            "label"     => "merek",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "merek_nama",
//            "inputType" => "hidden",
//            // "mdlChild"  => array("outdoor","indoor_1","indoor_2","indoor_3","indoor_4","indoor_5"),
//            // "kolom_nama" => "kategori_nama",
//        ),
//        "folder"            => array(
//            "label"      => "Tipe Refrigeran",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "folders",
//            "inputType"  => "combo",
//            "reference"  => "MdlFolderProduk",
//            "strField"   => "nama",
//            "editable"   => true,
//            "kolom_nama" => "folders_nama",
//            "add_btn"    => true,
//        ),
//        "folders_nama"      => array(
//            "label"      => "folder",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "folders_nama",
//            "inputType"  => "hidden",
//            "kolom_nama" => "folders_nama",
//        ),
        "produk_phase"      => array(
            "label"      => "phase",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "phase_id",
            "inputType"  => "combo",
            "reference"  => "MdlPhase",
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "phase_nama",
            "add_btn"    => true,
        ),
        "produk_jenis"      => array(
            "label"     => "jenis produk",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "produk_jenis_id",
            "inputType" => "combo",
            "reference" => "MdlProdukJenis",
        ),
        "produk_jenis_nama" => array(
            "label"      => "kategori",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "produk_jenis_nama",
            "inputType"  => "hidden",
            "kolom_nama" => "produk_jenis_nama",
        ),
        "merek_nama"        => array(
            "label"      => "merek",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "merek_id",
            // "inputType"  => "combo",
            "inputType"  => "combo-blank",
            "reference"  => "MdlMerek",
            // "referenceFilter"  => array(
            //     "supplier_id"
            // ),
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "merek_nama",
            "add_btn"    => true,
            "mdlChild"   => array(
                "outdoor",
                // "tipe_id",
                "indoor_1",
                "indoor_2",
                "indoor_3",
                "part_id_1",
                "part_id_2",
            ),
        ),
        "kode"              => array(
            "label"     => "model unit kode/SKU",
            "type"      => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
//        "satuan_nilai"      => array(
//            "label"     => "Satuan Nilai",
//            "type"      => "int",
//            "length"    => "24",
//            "kolom"     => "satuan_nilai",
//            "inputType" => "text",// hidden
//            //--"inputName" => "id",
//        ),
        "barcode"           => array(
            "label"     => "barcode",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "barcode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
//        "tipe_id"           => array(
//            "label"      => "tipe",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "tipe_id",
//            //            "inputType"  => "combo",
//            "inputType"  => "combo-blank",
//            "reference"  => "MdlTipe",
//            "strField"   => "nama",
//            "editable"   => true,
//            "kolom_nama" => "tipe_nama",
//            "add_btn"    => true,
//        ),
//        "tipe_nama"         => array(
//            "label"     => "kapasitas (PK/HP)",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "tipe_nama",
//            "inputType" => "hidden",
//
//        ),
        // "series_id"         => array(
        //     "label"      => "Produk Series",
        //     "type"       => "int",
        //     "length"     => "255",
        //     "kolom"      => "series_id",
        //     "inputType"  => "combo",
        //     "reference"  => "MdlProdukSeries",
        //     "strField"   => "nama",
        //     "editable"   => true,
        //     "kolom_nama" => "series_nama",
        //     "add_btn"    => true,
        // ),
        // "series_nama"       => array(
        //     "label"     => "Series",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "series_nama",
        //     "inputType" => "hidden",
        //     // "kolom_nama" => "kategori_nama",
        // ),
//        "model"             => array(
//            "label"      => "model",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "tipe_id",
//            "inputType"  => "combo",
//            "reference"  => "MdlModel",
//            "strField"   => "nama",
//            "editable"   => true,
//            "kolom_nama" => "model_nama",
//            "add_btn"    => true,
//        ),
//        "outdoor"           => array(
//            "label"           => "model outdoor",
//            "type"            => "int",
//            "length"          => "255",
//            "kolom"           => "outdoor_id",
//            // "inputType"  => "combo",
//            "inputType"       => "combo-blank",
//            "reference"       => "MdlModelOutdoor",
//            "referenceFilter" => array(
//                "id" => array(
//                    "var" => "supplier_id"
//                )
//            ),
//            // "strField"   => "nama",
//            // "kolom_nama" => "outdoor_nama",
//            "referenceDatas"  => array(
//                "nama"    => "outdoor_nama",
//                "barcode" => "outdoor_barcode",
//                "sku"     => "outdoor_sku",
//            ),
//            "editable"        => true,
//            "add_btn"         => true,
//        ),
//        "outdoor_nama"      => array(
//            "label"     => "outdoor",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "outdoor_nama",
//            "inputType" => "hidden",
//        ),
//        "indoor_1"          => array(
//            "label"          => "model indoor #1",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "indoor_id_1",
//            // "inputType"  => "combo",
//            "inputType"      => "combo-blank",
//            "reference"      => "MdlModelIndoor_1",
//            // "reference"  => "MdlProduk",
//            // "referenceFilter"  => array(
//            //     "jenis" => "indoor"
//            // ),
//            "strField"       => "nama",
//            "kolom_nama"     => "indoor_nama_1",
//            "referenceDatas" => array(
//                "barcode" => "indoor_barcode_1",
//                "sku"     => "indoor_sku_1",
//            ),
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "indoor_nama_1"     => array(
//            "label"     => "kapasitas (PK/HP)",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "indoor_nama_1",
//            "inputType" => "hidden",
//        ),
//        "indoor_2"          => array(
//            "label"          => "model indoor #2",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "indoor_id_2",
//            // "inputType"  => "combo",
//            "inputType"      => "combo-blank",
//            "reference"      => "MdlModelIndoor_1",
//            "strField"       => "nama",
//            "kolom_nama"     => "indoor_nama_2",
//            "referenceDatas" => array(
//                "barcode" => "indoor_barcode_2",
//                "sku"     => "indoor_sku_2",
//            ),
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "indoor_nama_2"     => array(
//            "label"     => "indoor 2",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "indoor_nama_2",
//            "inputType" => "hidden",
//        ),
//        "indoor_3"          => array(
//            "label"          => "model indoor #3",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "indoor_id_3",
//            // "inputType"  => "combo",
//            "inputType"      => "combo-blank",
//            "reference"      => "MdlModelIndoor_1",
//            "strField"       => "nama",
//            "kolom_nama"     => "indoor_nama_3",
//            "referenceDatas" => array(
//                "barcode" => "indoor_barcode_3",
//                "sku"     => "indoor_sku_3",
//            ),
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "indoor_nama_3"     => array(
//            "label"     => "indoor 3",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "indoor_nama_3",
//            "inputType" => "hidden",
//        ),
//        "indoor_4"          => array(
//            "label"          => "model indoor #4",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "indoor_id_4",
//            // "inputType"  => "combo",
//            "inputType"      => "combo-blank",
//            "reference"      => "MdlModelIndoor_1",
//            "strField"       => "nama",
//            "kolom_nama"     => "indoor_nama_4",
//            "referenceDatas" => array(
//                "barcode" => "indoor_barcode_4",
//                "sku"     => "indoor_sku_4",
//            ),
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "indoor_nama_4"     => array(
//            "label"     => "indoor 4",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "indoor_nama_4",
//            "inputType" => "hidden",
//        ),
        "nama"              => array(
            "label"          => "nama produk",
            "type"           => "varchar", "length" => "255", "kolom" => "nama",
            "inputType"      => "text",
            //--"inputName" => "nama",
            "linkModal"      => "Data/viewSerial/ProdukPerSerialNumber",
            "prefTitleModal" => "Serial",
        ),
        "label"             => array(
            "label"     => "alias",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "label",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "keterangan"        => array(
            "label"     => "keterangan",
            "type"      => "varchar", "length" => "255", "kolom" => "keterangan",
            "inputType" => "text",
            //--"inputName" => "",
        ),

        "satuan_id"          => array(
            "label"     => "satuan",
            "type"      => "int", "length" => "24", "kolom" => "satuan_id",
            "inputType" => "combo",
            // "defaultValue" => "ID",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
            "attr"      => "class='text-center'",
            "add_btn"   => true,
        ),
        // "nopart" => array(
        //     "label" => "no part",
        //     "type" => "int", "length" => "24", "kolom" => "no_part",
        //     "inputType" => "text",
        //     //--"inputName" => "satuan",
        // ),
        //        "disc_cat" => array(
        //            "label" => "discount cat",
        //            "type" => "int", "length" => "24", "kolom" => "diskon_kategori",
        //            "inputType" => "combo",
        //            "dataSource" => array("folder", "item"),
        //            //--"inputName" => "jenis",
        //        ),
        "berat"              => array(
            "label"     => "berat",
            "type"      => "int", "length" => "24", "kolom" => "berat",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
        "panjang"            => array(
            "label"     => "panjang",
            "type"      => "int", "length" => "24", "kolom" => "panjang",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
        // "lebar" => array(
        //     "label" => "lebar",
        //     "type" => "int", "length" => "24", "kolom" => "lebar",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "tinggi" => array(
        //     "label" => "tinggi",
        //     "type" => "int", "length" => "24", "kolom" => "tinggi",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "volume" => array(
        //     "label" => "volume",
        //     "type" => "int", "length" => "24", "kolom" => "volume",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "panjang_ckd" => array(
        //     "label" => "CKD length (in millimeters)",
        //     "type" => "int", "length" => "24", "kolom" => "panjang_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "lebar_ckd" => array(
        //     "label" => "CKD width (in millimeters)",
        //     "type" => "int", "length" => "24", "kolom" => "lebar_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "tinggi_ckd" => array(
        //     "label" => "CKD height (in millimeters)",
        //     "type" => "int", "length" => "24", "kolom" => "tinggi_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "berat_ckd" => array(
        //     "label" => "CKD weight (in grams)",
        //     "type" => "int", "length" => "24", "kolom" => "berat_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        "status"             => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),
//        "part_id_1"          => array(
//            "label"           => "remote control",
//            "type"            => "int",
//            "length"          => "255",
//            "kolom"           => "produk_part_id_1",
//            // "inputType"  => "combo",
//            "inputType"       => "combo-blank",
//            "reference"       => "MdlProdukPart_1",
//            "referenceFilter" => array(
//                "id" => array(
//                    "var" => "supplier_id"
//                )
//            ),
//            "strField"        => "nama",
//            "kolom_nama"      => "produk_part_nama_1",
//            "referenceDatas"  => array(
//                "barcode" => "produk_part_barcode_1",
//            ),
//            // "strBarcode"   => "barcode",
//            // "kolom_barcode" => "produk_part_barcode_1",
//            "editable"        => true,
//            "add_btn"         => true,
//        ),
//        "part_nama_1"        => array(
//            "label"     => "part 1",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_nama_1",
//            "inputType" => "hidden",
//        ),
//        "part_barcode_1"     => array(
//            "label"     => "part barcode 1",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_barcode_1",
//            "inputType" => "hidden",
//        ),
//        "part_id_2"          => array(
//            "label"          => "cover",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "produk_part_id_2",
//            // "inputType"  => "combo",
//            "inputType"      => "combo-blank",
//            "reference"      => "MdlProdukPart_2",
//            "strField"       => "nama",
//            "kolom_nama"     => "produk_part_nama_2",
//            // isset($spec["reference"] executor adadi addProcess
//            "referenceDatas" => array(
//                "barcode" => "produk_part_barcode_2",
//            ),
//            "strBarcode"     => "barcode",
//            "kolom_barcode"  => "produk_part_barcode_2",
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "part_nama_2"        => array(
//            "label"     => "part 2",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_nama_2",
//            "inputType" => "hidden",
//        ),
//        "part_barcode_2"     => array(
//            "label"     => "part barcode 2",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_barcode_2",
//            "inputType" => "hidden",
//        ),
        //        "harga" => array(
        //            "label" => "harga",
        //            "type" =>"int","length"=>"24","kolom" => "harga",
        //            "inputType" => "number",
        //            //--"inputName" => "harga",
        //        ),
        //        "dtime" => array(
        //            "label" => "dtime",
        //            "type" =>"int","length"=>"24","kolom" => "dtime",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "oleh name" => array(
        //            "label" => "pic",
        //            "type" =>"int","length"=>"24","kolom" => "oleh_name",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //
        //        "komposisi" => array(
        //            "label" => "komposisi",
        //            "type" =>"int","length"=>"24","kolom" => "komposisi",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "produk dasar" => array(
        //            "label" => "bahan",
        //            "type" =>"int","length"=>"24","kolom" => "produk_dasar_nama",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "jumlah" => array(
        //            "label" => "jumlah",
        //            "type" =>"int","length"=>"24","kolom" => "jml",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "bahan utama" => array(
        //            "label" => "bahan utama",
        //            "type" =>"int","length"=>"24","kolom" => "bahan_utama",
        //            "inputType" => "combo",
        //            //--"inputName" => "bahan_utama",
        //        ),
        // -------------------------------------
//        "part_kategori_id"   => array(
//            "label"          => "kategori part",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "produk_part_kategori_id",
//            "inputType"      => "combo",
//            //            "inputType"  => "combo-blank",
//            "reference"      => "MdlProdukPartKategori",
//            // "strField"   => "nama",
//            // "kolom_nama" => "produk_part_kategori_nama",
//            // isset($spec["reference"] executor adadi addProcess
//            "referenceDatas" => array(
//                "nama" => "produk_part_kategori_nama",
//            ),
//            //            "strBarcode"   => "barcode",
//            //            "kolom_barcode" => "produk_part_barcode_2",
//            "mdlChild"       => array(
//                "part_jenis_id",
//                "part_ukuran_id"
//            ),
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "part_kategori_nama" => array(
//            "label"     => "part 2",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_kategori_nama",
//            "inputType" => "hidden",
//        ),
//        "part_jenis_id"      => array(
//            "label"      => "jenis part",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "produk_part_jenis_id",
//            // "inputType"  => "combo",
//            "inputType"  => "combo-blank",
//            "reference"  => "MdlProdukPartJenis",
//            "strField"   => "nama",
//            "kolom_nama" => "produk_part_jenis_nama",
//            // isset($spec["reference"] executor adadi addProcess
//            // "referenceDatas" => array(
//            //     "nama" => "produk_part_jenis_nama",
//            // ),
//            //            "strBarcode"   => "barcode",
//            //            "kolom_barcode" => "produk_part_barcode_2",
//            "editable"   => true,
//            "add_btn"    => true,
//        ),
//        "part_jenis_nama"    => array(
//            "label"     => "part 2",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_jenis_nama",
//            "inputType" => "hidden",
//        ),
//        "part_ukuran_id"     => array(
//            "label"          => "ukuran",
//            "type"           => "int",
//            "length"         => "255",
//            "kolom"          => "produk_part_ukuran_id",
//            // "inputType"  => "combo",
//            "inputType"      => "combo-blank",
//            "reference"      => "MdlProdukPartUkuran",
//            // "strField"       => "nama",
//            // "kolom_nama"     => "produk_part_ukuran_nama",
//            // isset($spec["reference"] executor adadi addProcess
//            "referenceDatas" => array(
//                "nama" => "produk_part_ukuran_nama",
//            ),
//            //            "strBarcode"   => "barcode",
//            //            "kolom_barcode" => "produk_part_barcode_2",
//            "editable"       => true,
//            "add_btn"        => true,
//        ),
//        "part_ukuran_nama"   => array(
//            "label"     => "part 2",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "produk_part_ukuran_nama",
//            "inputType" => "hidden",
//        ),

    );
    protected $listedFields = array(
//        "folders"    => "folder",
        "kode"       => "kode",
//        "label"      => "label",
        "nama"       => "nama",
        "keterangan" => "keterangan",
//        "satuan" => "satuan",
    );

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

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }
}