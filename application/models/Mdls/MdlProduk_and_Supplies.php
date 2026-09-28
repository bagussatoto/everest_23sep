<?php

//--include_once "MdlHistoriData.php";
class MdlProduk_and_Supplies extends MdlMother
{
    protected $tableName = "produk";
    protected $indexFields = "id";
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "produk.jenis='item'",
        "produk.jenis in ('item', 'supplies')",
        "produk.status='1'",
        "produk.trash='0'"
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "id" => "id",
        "nama" => "nama",
        "kode" => "kode",
        "barcode" => "barcode",
        // "keterangan"    => "keterangan",
        // "label"         => "label",
        // "no_part" => "no_part",
        "folders_nama" => "folders_nama",
        "tipe_nama" => "tipe_nama",
        "size_nama" => "size_nama",
        "kategori_nama" => "kategori_nama",
        "merek_nama" => "merek_nama",
    );
    protected $validationRules = array(
        // "barcode"     => array("required"),
        "kategori_id" => array("required"),
        "supplier" => array("required"),
        // "skala"       => array("required"),
        "nama" => array("required", "unique"),
        // "lebar_gross"     => array("required", "singleOnly"),
        // "panjang_gross"   => array("required", "singleOnly"),
        // "tinggi_gross"    => array("required", "singleOnly"),
        // "berat_gross"     => array("required", "singleOnly"),
        "kode" => array("required", "unique"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "jenis" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "jenis",
            "inputType" => "hidden",// hidden
        ),
        "allow_project" => array(
            "label" => "ijinkan project",
            "type" => "int", "length" => "24", "kolom" => "allow_project",
            "inputType" => "combo",
            "checbox" => true,
            // "checbox_disabled" => array("kategori_id" => 4),
            "checbox_fungsi" => "checkboxUpdProject",
            "dataSource" => array(0 => "no", 1 => "yes"),
            "defaultValue" => 0,
            //--"inputName" => "status",
        ),
        "allow_autopurchase" => array(
            "label" => "ijinkan autopurchase",
            "type" => "int", "length" => "24", "kolom" => "allow_autopurchase",
            "inputType" => "combo",
            "checbox" => true,
            "checbox_fungsi" => "checkboxUpdAutopurchase",
            "dataSource" => array(0 => "no", 1 => "yes"),
            "defaultValue" => 0,
        ),
        "kapasitas" => array(
            "label" => "power PK/HP",
            "type" => "int",
            "length" => "255",
            "kolom" => "kapasitas_id",
            "inputType" => "combo",
            "reference" => "MdlKapasitas",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "kapasitas_nama",
            "add_btn" => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "kategori" => array(
            "label" => "jenis",//kategori
            "type" => "int",
            "length" => "255",
            "kolom" => "kategori_id",
            "inputType" => "combo-hidden",
            "reference" => "MdlProdukKategori",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "kategori_nama",
            "add_btn" => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "kategori_nama" => array(
            "label" => "jenis",
            "type" => "int",
            "length" => "255",
            "kolom" => "kategori_nama",
            "inputType" => "hidden",
            "kolom_nama" => "kategori_nama",
        ),
        "sub_kategori" => array(
            "label" => "sub jenis",//kategori
            "type" => "int",
            "length" => "255",
            "kolom" => "sub_kategori_id",
            "indexFields" => "sub_kategori_id",
            "inputType" => "combo-hidden",
            "reference" => "MdlProdukSubKategori",
            "strField" => "label",
            "editable" => false,
            "kolom_nama" => "sub_kategori_nama",
            "add_btn" => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "sub_kategori_nama" => array(
            "label" => "sub jenis",
            "type" => "int",
            "length" => "255",
            "kolom" => "sub_kategori_nama",
            "inputType" => "hidden",
            "kolom_nama" => "sub_kategori_nama",
        ),
        "jml_serial" => array(
            "label" => "jml serial",
            "type" => "int",
            "length" => "32",
            "kolom" => "jml_serial",
            "inputType" => "text",
            "editable" => false,
            "show" => false,
            "checbox" => true,
            "checbox_disabled" => array("kategori_id" => 4),
            "checbox_fungsi" => "checkboxUpd",
            //            "dataSource"   => array(1=>"single",0=>"non serial"),
            "defaultValue" => 0,
            "dataSource" => array(0 => "non serial", 1 => "serial"),
            //            "editable"     => false,
            // "reference" => "MdlProdukKategori",
            // "strField"   => "nama",
            // "editable"   => true,
            // "kolom_nama" => "kategori_nama",
            // "add_btn" => true,
            // "keterangan" => true,
        ),
        "skala" => array(
            "label" => "size/skala",
            "type" => "int",
            "length" => "255",
            "kolom" => "size_id",
            "inputType" => "combo",
            "reference" => "MdlProdukSize",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "size_nama",
            "add_btn" => true,
            "mdlChild" => array("tipe_id"),
        ),
        "skala_nama" => array(
            "label" => "skala",
            "type" => "int",
            "length" => "255",
            "kolom" => "size_nama",
            "inputType" => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),
        "supplier" => array(
            "label" => "supplier",
            "type" => "int",
            "length" => "255",
            "kolom" => "supplier_id",
            "inputType" => "combo",
            "reference" => "MdlSupplier",
            "strField" => "nama",
            "kolom_nama" => "supplier_nama",
            "editable" => true,
            "add_btn" => true,
            "mdlChild" => array("merek_nama"),
            // "childDefault"   => "MdlMerek"
        ),
        "supplier_nama" => array(
            "label" => "supplier",
            "type" => "int",
            "length" => "255",
            "kolom" => "supplier_nama",
            "inputType" => "hidden",
        ),
        "kapasitas_nama" => array(
            "label" => "kapasitas",
            "type" => "int",
            "length" => "255",
            "kolom" => "kapasitas_nama",
            "inputType" => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),
        "merek" => array(
            "label" => "merek",
            "type" => "int",
            "length" => "255",
            "kolom" => "merek_nama",
            "inputType" => "hidden",
            // "mdlChild"  => array("outdoor","indoor_1","indoor_2","indoor_3","indoor_4","indoor_5"),
            // "kolom_nama" => "kategori_nama",
        ),
        "folder" => array(
            "label" => "Tipe Refrigeran",
            "type" => "int",
            "length" => "255",
            "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlFolderProduk",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "folders_nama",
            "add_btn" => true,
        ),
        "folders_nama" => array(
            "label" => "folder",
            "type" => "int",
            "length" => "255",
            "kolom" => "folders_nama",
            "inputType" => "hidden",
            "kolom_nama" => "folders_nama",
        ),
        "produk_phase" => array(
            "label" => "phase",
            "type" => "int",
            "length" => "255",
            "kolom" => "phase_id",
            "inputType" => "combo",
            "reference" => "MdlPhase",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "phase_nama",
            "add_btn" => true,
        ),
        "produk_jenis" => array(
            "label" => "jenis produk",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_jenis_id",
            "inputType" => "combo",
            "reference" => "MdlProdukJenis",
        ),
        "produk_jenis_nama" => array(
            "label" => "kategori",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_jenis_nama",
            "inputType" => "hidden",
            "kolom_nama" => "produk_jenis_nama",
        ),
        "merek_nama" => array(
            "label" => "merek",
            "type" => "int",
            "length" => "255",
            "kolom" => "merek_id",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlMerek",
            // "referenceFilter"  => array(
            //     "supplier_id"
            // ),
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "merek_nama",
            "add_btn" => true,
            "mdlChild" => array(
                "outdoor",
                // "tipe_id",
                "indoor_1",
                "indoor_2",
                "indoor_3",
                "part_id_1",
                "part_id_2",
            ),
        ),
        "kode" => array(
            "label" => "model unit kode/SKU",
            "type" => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "satuan_nilai" => array(
            "label" => "Satuan Nilai",
            "type" => "int",
            "length" => "24",
            "kolom" => "satuan_nilai",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "barcode" => array(
            "label" => "barcode",
            "type" => "int",
            "length" => "24",
            "kolom" => "barcode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "tipe_id" => array(
            "label" => "tipe",
            "type" => "int",
            "length" => "255",
            "kolom" => "tipe_id",
            //            "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlTipe",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "tipe_nama",
            "add_btn" => true,
        ),
        "tipe_nama" => array(
            "label" => "kapasitas (PK/HP)",
            "type" => "int",
            "length" => "255",
            "kolom" => "tipe_nama",
            "inputType" => "hidden",

        ),
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
        "model" => array(
            "label" => "model",
            "type" => "int",
            "length" => "255",
            "kolom" => "tipe_id",
            "inputType" => "combo",
            "reference" => "MdlModel",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "model_nama",
            "add_btn" => true,
        ),
        "outdoor" => array(
            "label" => "model outdoor",
            "type" => "int",
            "length" => "255",
            "kolom" => "outdoor_id",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlModelOutdoor",
            "referenceFilter" => array(
                "id" => array(
                    "var" => "supplier_id"
                )
            ),
            // "strField"   => "nama",
            // "kolom_nama" => "outdoor_nama",
            "referenceDatas" => array(
                "nama" => "outdoor_nama",
                "barcode" => "outdoor_barcode",
                "sku" => "outdoor_sku",
            ),
            "editable" => true,
            "add_btn" => true,
        ),
        "outdoor_nama" => array(
            "label" => "outdoor",
            "type" => "int",
            "length" => "255",
            "kolom" => "outdoor_nama",
            "inputType" => "hidden",
        ),
        "indoor_1" => array(
            "label" => "model indoor #1",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_id_1",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlModelIndoor_1",
            // "reference"  => "MdlProduk",
            // "referenceFilter"  => array(
            //     "jenis" => "indoor"
            // ),
            "strField" => "nama",
            "kolom_nama" => "indoor_nama_1",
            "referenceDatas" => array(
                "barcode" => "indoor_barcode_1",
                "sku" => "indoor_sku_1",
            ),
            "editable" => true,
            "add_btn" => true,
        ),
        "indoor_nama_1" => array(
            "label" => "kapasitas (PK/HP)",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_nama_1",
            "inputType" => "hidden",
        ),
        "indoor_2" => array(
            "label" => "model indoor #2",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_id_2",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlModelIndoor_1",
            "strField" => "nama",
            "kolom_nama" => "indoor_nama_2",
            "referenceDatas" => array(
                "barcode" => "indoor_barcode_2",
                "sku" => "indoor_sku_2",
            ),
            "editable" => true,
            "add_btn" => true,
        ),
        "indoor_nama_2" => array(
            "label" => "indoor 2",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_nama_2",
            "inputType" => "hidden",
        ),
        "indoor_3" => array(
            "label" => "model indoor #3",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_id_3",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlModelIndoor_1",
            "strField" => "nama",
            "kolom_nama" => "indoor_nama_3",
            "referenceDatas" => array(
                "barcode" => "indoor_barcode_3",
                "sku" => "indoor_sku_3",
            ),
            "editable" => true,
            "add_btn" => true,
        ),
        "indoor_nama_3" => array(
            "label" => "indoor 3",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_nama_3",
            "inputType" => "hidden",
        ),
        "indoor_4" => array(
            "label" => "model indoor #4",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_id_4",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlModelIndoor_1",
            "strField" => "nama",
            "kolom_nama" => "indoor_nama_4",
            "referenceDatas" => array(
                "barcode" => "indoor_barcode_4",
                "sku" => "indoor_sku_4",
            ),
            "editable" => true,
            "add_btn" => true,
        ),
        "indoor_nama_4" => array(
            "label" => "indoor 4",
            "type" => "int",
            "length" => "255",
            "kolom" => "indoor_nama_4",
            "inputType" => "hidden",
        ),
        "nama" => array(
            "label" => "nama produk",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            "linkModal" => "Data/viewSerial/ProdukPerSerialNumber",
            "prefTitleModal" => "Serial",
        ),
        "label" => array(
            "label" => "alias",
            "type" => "int",
            "length" => "24",
            "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "keterangan" => array(
            "label" => "keterangan",
            "type" => "varchar", "length" => "255", "kolom" => "keterangan",
            "inputType" => "textarea",
            //--"inputName" => "",
        ),

        "satuan_id" => array(
            "label" => "satuan",
            "type" => "int", "length" => "24", "kolom" => "satuan_id",
            "inputType" => "combo",
            // "defaultValue" => "ID",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
            "attr" => "class='text-center'",
            "add_btn" => true,
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
        "berat" => array(
            "label" => "berat",
            "type" => "int", "length" => "24", "kolom" => "berat",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
        "panjang" => array(
            "label" => "panjang",
            "type" => "int", "length" => "24", "kolom" => "panjang",
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
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        "part_id_1" => array(
            "label" => "remote control",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_id_1",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlProdukPart_1",
            "referenceFilter" => array(
                "id" => array(
                    "var" => "supplier_id"
                )
            ),
            "strField" => "nama",
            "kolom_nama" => "produk_part_nama_1",
            "referenceDatas" => array(
                "barcode" => "produk_part_barcode_1",
            ),
            // "strBarcode"   => "barcode",
            // "kolom_barcode" => "produk_part_barcode_1",
            "editable" => true,
            "add_btn" => true,
        ),
        "part_nama_1" => array(
            "label" => "part 1",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_nama_1",
            "inputType" => "hidden",
        ),
        "part_barcode_1" => array(
            "label" => "part barcode 1",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_barcode_1",
            "inputType" => "hidden",
        ),
        "part_id_2" => array(
            "label" => "cover",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_id_2",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlProdukPart_2",
            "strField" => "nama",
            "kolom_nama" => "produk_part_nama_2",
            // isset($spec["reference"] executor adadi addProcess
            "referenceDatas" => array(
                "barcode" => "produk_part_barcode_2",
            ),
            "strBarcode" => "barcode",
            "kolom_barcode" => "produk_part_barcode_2",
            "editable" => true,
            "add_btn" => true,
        ),
        "part_nama_2" => array(
            "label" => "part 2",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_nama_2",
            "inputType" => "hidden",
        ),
        "part_barcode_2" => array(
            "label" => "part barcode 2",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_barcode_2",
            "inputType" => "hidden",
        ),
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
        "part_kategori_id" => array(
            "label" => "kategori part",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_kategori_id",
            "inputType" => "combo",
            //            "inputType"  => "combo-blank",
            "reference" => "MdlProdukPartKategori",
            // "strField"   => "nama",
            // "kolom_nama" => "produk_part_kategori_nama",
            // isset($spec["reference"] executor adadi addProcess
            "referenceDatas" => array(
                "nama" => "produk_part_kategori_nama",
            ),
            //            "strBarcode"   => "barcode",
            //            "kolom_barcode" => "produk_part_barcode_2",
            "mdlChild" => array(
                "part_jenis_id",
                "part_ukuran_id"
            ),
            "editable" => true,
            "add_btn" => true,
        ),
        "part_kategori_nama" => array(
            "label" => "part 2",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_kategori_nama",
            "inputType" => "hidden",
        ),
        "part_jenis_id" => array(
            "label" => "jenis part",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_jenis_id",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlProdukPartJenis",
            "strField" => "nama",
            "kolom_nama" => "produk_part_jenis_nama",
            // isset($spec["reference"] executor adadi addProcess
            // "referenceDatas" => array(
            //     "nama" => "produk_part_jenis_nama",
            // ),
            //            "strBarcode"   => "barcode",
            //            "kolom_barcode" => "produk_part_barcode_2",
            "editable" => true,
            "add_btn" => true,
        ),
        "part_jenis_nama" => array(
            "label" => "part 2",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_jenis_nama",
            "inputType" => "hidden",
        ),
        "part_ukuran_id" => array(
            "label" => "ukuran",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_ukuran_id",
            // "inputType"  => "combo",
            "inputType" => "combo-blank",
            "reference" => "MdlProdukPartUkuran",
            // "strField"       => "nama",
            // "kolom_nama"     => "produk_part_ukuran_nama",
            // isset($spec["reference"] executor adadi addProcess
            "referenceDatas" => array(
                "nama" => "produk_part_ukuran_nama",
            ),
            //            "strBarcode"   => "barcode",
            //            "kolom_barcode" => "produk_part_barcode_2",
            "editable" => true,
            "add_btn" => true,
        ),
        "part_ukuran_nama" => array(
            "label" => "part 2",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_part_ukuran_nama",
            "inputType" => "hidden",
        ),
    );
    protected $autoFillFields = array(
        "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );
    protected $listedFields = array(
        "id" => "pID",
        "allow_project" => "ijinkan project",//ijinkan project
        "kategori_nama" => "jenis",//kategori
        "jml_serial" => "serial",//size
        "size_nama" => "skala",//size
        //        "allow_project" => "allow_project",//size
        "tipe_nama" => "tipe",
        "kode" => "sku",
        "barcode" => "barcode",
        "nama" => "nama/deskripsi",
        "label" => "nama alias",
        "kapasitas_nama" => "kapasitas",
        "supplier_nama" => "supplier",
        "merek_nama" => "merek",
        "series_nama" => "Series",
        "satuan_nilai" => "Satuan Nilai",

        "folders_nama" => "refrigeran",
        "outdoor_nama" => "model outdoor",
        "indoor_nama_1" => "model indoor #1",
        "indoor_nama_2" => "model indoor #2",
        "indoor_nama_3" => "model indoor #3",
        "produk_part_nama_1" => "model remot",
        "produk_part_nama_2" => "cover",
        // "keterangan"    => "keterangan",

        // "satuan"         => "satuan",
        // "status"         => "status",
    );
    protected $excelFields = array(
        "id" => array(
            "label" => "pID",
            "type" => "integer",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "string",
        ),
        "no_part" => array(
            "label" => "part",
            "type" => "string",
        ),
        "nama" => array(
            "label" => "produk",
            "type" => "string",
        ),
        "keterangan" => array(
            "label" => "note",
            "type" => "string",
        ),
        "berat_gross" => array(
            "label" => "berat",
            "type" => "integer",
        ),
        "lebar_gross" => array(
            "label" => "lebar",
            "type" => "integer",
        ),
        "panjang_gross" => array(
            "label" => "panjang",
            "type" => "integer",
        ),
        "tinggi_gross" => array(
            "label" => "tinggi",
            "type" => "integer",
        ),
        "pic" => array(
            "label" => "images",
            "type" => "string",
            "replacer" => "hahaha",
        ),
        /* -------------- ----------------------------------
         * key masih manual untuk pairing dr data tambahan
         * ----------------------- ------------------------*/
        "vendor" => array(
            "label" => "vendor",
            "type" => "string",
        ),
    );

    protected $masterFields = array(
        "unit" => array(
            "label" => "unit",
            "jml_serial" => "",
            "anakan" => array(),
            "kategori" => "main",
            //            "mdl"        => "MdlDataUnit", //dimatikan saja
        ),
        "non_unit" => array(
            "label" => "non unit",
            "jml_serial" => "",
            "anakan" => array(),
            "kategori" => "main",
            //            "anakan"     => array(
            //                "id",
            //                "kategori",
            //                "nama",
            //                "kode",
            //                "barcode",
            //                "keterangan",
            //                "satuan_id",
            //                "merek_nama",
            //                "panjang",
            //                "berat",
            //                "status",
            //                "jml_serial",
            //            )
        ),
        "jasa" => array(
            "label" => "jasa",
            "jml_serial" => "",
            "anakan" => array(),
            "kategori" => "main",
            //            "anakan"     => array(
            //                "id",
            //                "kategori",
            //                "nama",
            //                "kode",
            //                "barcode",
            //                "keterangan",
            //                "satuan_id",
            //                "merek_nama",
            //                "panjang",
            //                "berat",
            //                "status",
            //                "jml_serial",
            //            )
        ),
    );

    public function getMasterFields()
    {
        return $this->masterFields;
    }

    public function setMasterFields($masterFields)
    {
        $this->masterFields = $masterFields;
    }

    //=================

    protected $masterSubs = array(
        //        "unit_2"  => array(
        //            "label"      => "MULTI-S 2C",
        //            "jml_serial" => "3",
        //            "kategori"   => "1",
        //    "title"      => "1 Outdoor 1 Indoor",
        //            "type"   => "unit",
        //            "add_produk"   => array(
        //                "outdoor",
        //                "indoor_1",
        //                "indoor_2",
        //        "indoor_3",
        //            ),
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "skala",
        //                "tipe_id",
        //        "series_id",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "outdoor",
        //                "indoor_1",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        // "unit_2"    => array(
        //     "label"      => "UNIT AC MULTI-S 2C",
        //     "jml_serial" => "3",
        //            "kategori"   => "1",
        //            "type"   => "unit",
        //     "title"      => "1 Outdoor 2 Indoor",
        //            "add_produk"   => array(
        //                "outdoor",
        //                "indoor_1",
        //                "indoor_2",
        //            ),
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "skala",
        //                "tipe_id",
        //         "series_id",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "outdoor",
        //                "indoor_1",
        //         "indoor_2",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        //        "unit_3"  => array(
        //     "label"      => "unit AC MUTLI-S 3C",
        //            "jml_serial" => "4",
        //            "kategori"   => "1",
        //            "type"   => "unit",
        //            "title"   => "1 Outdoor 3 Indoor",
        //            "add_produk"   => array(
        //                "outdoor",
        //                "indoor_1",
        //                "indoor_2",
        //                "indoor_3",
        //            ),
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "skala",
        //                "tipe_id",
        //         "series_id",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "outdoor",
        //                "indoor_1",
        //                "indoor_2",
        //                "indoor_3",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        //        "outdoor" => array(
        //            "label"      => "AC outdoor",
        //            "jml_serial" => "1",
        //            "type"   => "non_unit",
        //            "kategori"   => "3",
        //            "sub_kategori"   => "9",
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "sub_kategori",
        //                "skala",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "series_id",
        ////                "outdoor",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        //        "indoor"  => array(
        //            "label"      => "AC indoor",
        //            "jml_serial" => "1",
        //            "type"   => "non_unit",
        //            "kategori"   => "3",
        //            "sub_kategori"   => "8",
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "sub_kategori",
        //                "skala",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "series_id",
        ////                "indoor_1",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        //        "lainlain" => array(
        //            "label"      => "Lain-lain",
        //            "jml_serial" => "1",
        //            "type"       => "non_unit",
        //            "kategori"   => "3",
        //            "sub_kategori"   => "10",
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "sub_kategori",
        //                "skala",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        ////                "indoor_1",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        //        "tv" => array(
        //            "label"      => "SMART TV",
        //            "jml_serial" => "1",
        //            "type"   => "non_unit",
        //            "kategori"   => "3",
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "skala",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "indoor_1",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),

        //        "blender" => array(
        //            "label"      => "SMART BLENDER",
        //            "jml_serial" => "1",
        //            "type"   => "non_unit",
        //            "kategori"   => "3",
        //            "anakan"     => array(
        //                "id",
        //                "kategori",
        //                "skala",
        //                "nama",
        //                "kode",
        //                "barcode",
        //                "folder",
        //                "kapasitas",
        //                "produk_phase",
        //                "supplier",
        //                "merek_nama",
        //                "indoor_1",
        //                "status",
        //                "jml_serial",
        //            )
        //        ),
    );

    public function getMasterSubs()
    {
        $statikSub = $this->masterSubs;
        $this_data = $this->db->select('*')
            ->from("set_menu_statik")
            ->where("active=1 AND trash=0")
            ->get()->result();
        $arrMenuAdd = array();
        if (!empty($this_data)) {
            foreach ($this_data as $k => $rd) {
                $arrMenuAdd[$rd->id] = (array)$rd;
                if ($rd->anakan) {
                    $arrAnakan = explode(",", $rd->anakan);
                    $arrMenuAdd[$rd->id]['anakan'] = (array)$arrAnakan;
                }
                if ($rd->add_produk) {
                    $arrAddProduk = explode(",", $rd->add_produk);
                    $arrMenuAdd[$rd->id]['add_produk'] = $arrAddProduk;
                }
                if ($rd->kategori_id) {
                    $arrMenuAdd[$rd->id]['kategori'] = $rd->kategori_id;
                }
                if ($rd->sub_kategori_id) {
                    $arrMenuAdd[$rd->id]['sub_kategori'] = $rd->sub_kategori_id;
                }
            }
        }
        //        return array_merge($statikSub,$arrMenuAdd);
        return $arrMenuAdd;
    }

    public function setMasterSubs($masterSubs)
    {
        $this->masterSubs = $masterSubs;
    }

    //=================

    protected $mainMenus = array(
        "unit" => array(
            "unit_1",
            "unit_2",
            "unit_3",
        ),
        "non_unit" => array(
            "elektronik",
        )
    );
    protected $formSub = array(
        "1" => array(
            "id",
            "kategori",
            "sub_kategori",
            "skala",
            "allow_project",
            "nama",
            "kode",
            "barcode",
            "folder",
            "kapasitas",
            "produk_phase",
            "supplier",
            "merek_nama",
            "series_id",
            //                "indoor_1",
            "status",
            "jml_serial",
        ),
        "2" => array(
            "id",
            "kategori",
            "sub_kategori",
            "skala",
            "allow_project",
            "nama",
            "kode",
            "barcode",
            "folder",
            "kapasitas",
            "produk_phase",
            "supplier",
            "merek_nama",
            "series_id",
            //                "indoor_1",
            "status",
            "jml_serial",
        ),
        "3" => array(
            "id",
            "kategori",
            "sub_kategori",
            "skala",
            "allow_project",
            "nama",
            "kode",
            "barcode",
            "folder",
            "kapasitas",
            "produk_phase",
            "supplier",
            "merek_nama",
            "series_id",
            //                "indoor_1",
            "status",
            "jml_serial",
        ),
        "4" => array(
            "id",
            "kategori",
            "sub_kategori",
            "skala",
            "allow_project",
            "nama",
            "kode",
            "barcode",
            "folder",
            "kapasitas",
            "produk_phase",
            "supplier",
            "merek_nama",
            "series_id",
            //                "indoor_1",
            "status",
            "jml_serial",
        ),
        "5" => array(
            "id",
            "kategori",
            "sub_kategori",
            "skala",
            "allow_project",
            "nama",
            "kode",
            "barcode",
            "folder",
            "kapasitas",
            "produk_phase",
            "supplier",
            "merek_nama",
            "series_id",
            //                "indoor_1",
            "status",
            "jml_serial",
        ),
        "6" => array(
            "id",
            "kategori",
            "sub_kategori",
            "skala",
            "allow_project",
            "nama",
            "kode",
            "barcode",
            "folder",
            "kapasitas",
            "produk_phase",
            "supplier",
            "merek_nama",
            "series_id",
            //                "indoor_1",
            "status",
            "jml_serial",
        ),
    );

    public function getFormSubs()
    {
        return $this->formSub;
    }

    public function setFormSubs($formSub)
    {
        $this->formSub = $formSub;
    }

    /* ---------------------------------------------------------------------------------
     * dengan adanya methode ini akan metriger munculnya tombol download xlsx di GUI
     * berpasangan dengan method excelFields diatasnya ini
     * ----------------------------------- ----------------------------------------------*/
    protected $excelWriters = array(
        "namaFile" => "data_produk",
        "dataTambahan" => "MdlProdukPerSupplier",
        "dataTambahanBase" => "MdlSupplier",
        "dataTambahanFields" => array(
            "produk_id",
            "suppliers_id"
        ),
        "dataImage" => "MdlImages",
        "dataImageFields" => array(
            "parent_id" => "files"
        ),
    );

    public function getExcelFields()
    {
        return $this->excelFields;
    }

    public function setExcelFields($excelFields)
    {
        $this->excelFields = $excelFields;
    }

    public function getExcelWriters()
    {
        return $this->excelWriters;
    }

    public function setExcelWriters($excelWriters)
    {
        $this->excelWriters = $excelWriters;
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

    protected $navFilters = array(
        "label" => "jenis",
        "mdlFilter" => "MdlProdukKategori",
        "kolomKey" => "kategori_id",
    );

    public function getNavFilters()
    {
        return $this->navFilters;
    }

    public function setNavFilters($navFilters)
    {
        $this->navFilters = $navFilters;
    }

    protected $lookupNonAktif = array(
        // "label" => "aktif",
        // "mdlFilter" => "MdlProduk",
        // "kolomKey" => "status",
    );

    public function lookupNonAktif()
    {
        $this->setFilters(array());
        $condites = array(
            "status" => 0,
            "trash" => 0,
        );
        $this->db->where($condites);
        $vars = $this->lookupAll();

        return $vars;

        // return $this->lookupNonAktif;
    }

    public function setLookupNonAktif($lookupNonAktif)
    {
        $this->lookupNonAktif = $lookupNonAktif;
    }

    public function getAutoFillFields()
    {
        return $this->autoFillFields;
    }

    public function setAutoFillFields($autoFillFields)
    {
        $this->autoFillFields = $autoFillFields;
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

    public function updateLimit($produk_id, $limit)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "limit" => $limit,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateLeadTime($produk_id, $nilai)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "lead_time" => $nilai,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateIndeks($produk_id, $nilai)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "indeks" => $nilai,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function callProdukFire()
    {
        $fireKategories = array(
            "143"
        );
        $produks = $this->lookupAll()->result();

        $nonFireProduks = array();
        $fireProduks = array();
        foreach ($produks as $produkSrc) {
            if (in_array($produkSrc->folders, $fireKategories)) {
                $fireProduks[] = $produkSrc;
            }
            else {
                $nonFireProduks[] = $produkSrc;
            }
        }
        $vars['fire'] = $fireProduks;
        $vars['nonFire'] = $nonFireProduks;

        return $vars;
    }

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            "kode",
            "nama",
            "label",
            "folders_nama",
            "kategori_id",
            "kategori_nama",
            "barcode",
            "no_part",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            "satuan",
            "jml_serial",
            "diskon_persen",
            "premi_jual",
            "supplier_id",
            "merek_nama",
            "kategori_nama",
            "outdoor_nama",
            "indoor_nama_1",
            "indoor_nama_2",
            "indoor_nama_3",
            "indoor_nama_4",
            // "indoor_nama_5",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("id", $produkIds);
            }
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            // "MdlSatuan" => array(
            //     "id"         => "satuan_id",    // kolom_src => kolom_target (berisi id src)
            //     // "str" => "folders_nama",
            //     "kolomDatas" => array(
            //         "satuan" => "satuan",       // kolom_data => kolom_target (berisi nama)
            //     ),
            // ),
            "MdlFolderProduk" => array(
                "id" => "folders",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "folders_nama",
                ),
            ),
            "MdlProdukKategori" => array(
                "id" => "kategori_id",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "kategori_nama",
                ),
            ),
            "MdlMerek" => array(
                "id" => "merek_id",
                // "str" => "kendaraan_nama",
                "kolomDatas" => array(
                    "nama" => "merek_nama",
                ),
            ),
            // "MdlLokasiIndex"  => array(
            //     "id"  => "lokasi",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "lokasi_nama",
            //     ),
            // ),
            "MdlProdukJenis" => array(
                "id" => "produk_jenis_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "kode" => "produk_jenis_nama",
                    "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlSatuan" => array(
                "id" => "satuan_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "kode" => "satuan",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlPhase" => array(
                "id" => "phase_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "phase_nama",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlModelOutdoor" => array(
                "id" => "outdoor_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "outdoor_nama",
                    "barcode" => "outdoor_barcode",
                    "sku" => "outdoor_sku",
                ),
            ),
            "MdlModelIndoor_1" => array(
                "id" => "indoor_id_1",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "indoor_nama_1",
                    "barcode" => "indoor_barcode_1",
                    "sku" => "indoor_sku_1",
                ),
            ),
            "MdlModelIndoor_2" => array(
                "id" => "indoor_id_1",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "indoor_nama_2",
                    "barcode" => "indoor_barcode_2",
                    "sku" => "indoor_sku_2",
                ),
            ),
            "MdlModelIndoor_3" => array(
                "id" => "indoor_id_1",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "indoor_nama_3",
                    "barcode" => "indoor_barcode_3",
                    "sku" => "indoor_sku_3",
                ),
            ),
            "MdlModelIndoor_4" => array(
                "id" => "indoor_id_4",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "indoor_nama_4",
                    "barcode" => "indoor_barcode_4",
                    "sku" => "indoor_sku_4",
                ),
            ),
            "MdlProdukPart_1" => array(
                "id" => "produk_part_id_1",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "produk_part_nama_1",
                    "barcode" => "produk_part_barcode_1",
                ),
            ),
            "MdlProdukPart_2" => array(
                "id" => "produk_part_id_2",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "produk_part_nama_2",
                    "barcode" => "produk_part_barcode_2",
                ),
            ),
            "MdlSupplier" => array(
                "id" => "supplier_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "supplier_nama",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlProdukSize" => array(
                "id" => "size_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "size_nama",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlKapasitas" => array(
                "id" => "kapasitas_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "kapasitas_nama",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlTipe" => array(
                "id" => "tipe_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "tipe_nama",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            "MdlProdukSeries" => array(
                "id" => "series_id",
                "kolomDatas" => array(
                    "nama" => "series_nama",
                ),
            ),
            "MdlProdukSubKategori" => array(
                "id" => "sub_kategori_id",
                "kolomDatas" => array(
                    "label" => "sub_kategori_nama",
                ),
            ),
        );

        return $mdls;

    }

    /*----------------------------------------------------------------
     * auto penambahan COA, bisa dugunakan keperluan lain
     * konnecting ke model yg lain
     * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlAccounts" => array(
            "path" => "Mdls",
            "fungsi" => "addExtern_coa",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            // "staticOptions" => "010304",old
            "staticOptions" => "1010030030",//new coa
            "fields" => array(
                "extern_jenis" => array(
                    "str" => "produk",
                ),
                "extern_id" => array(
                    "var_main" => "mainInsertId",
                ),
                "rekening" => array(
                    "var_main" => "mainInsertId",
                ),
                "head_name" => array(
                    "var_main" => "nama",
                ),
                "p_head_name" => array(
                    "var_main" => "strHead_code",
                ),
                "create_by" => array(
                    "var_main" => "my_name",
                ),
                /* -------------------------------------------------
                 * filter yg ingin langsung diaktifkan
                 * -------------------------------------------------*/
                "is_active" => array(
                    "str" => "1",
                ),
                "is_transaction" => array(
                    "str" => "1",
                ),
                "is_rekening_pembantu" => array(
                    "str" => "1",
                ),
                // "is_hutang" => array(
                //     "str" => "1",
                // ),
                // "is_gl" => array(
                //     "str" => "1",
                // ),
            ),
            "updateMain" => array(
                "condites" => array(
                    "id" => "mainInsertId",
                ),
                "datas" => array(
                    "coa_code" => "lastInset_code",
                )
            )
        ),
        /*---baru bisa handling produk vs 1 supplier*/
        "MdlProdukPerSupplier" => array(
            "path" => "Mdls",
            "fungsi" => "addProdukSupplier",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            // "staticOptions" => "010304",old

            // --------------------------------------
            "staticOptions" => array(
                "suppliers_id" => array(
                    "var_main" => "supplier_id",
                ),
                "produk_id" => array(
                    "var_main" => "mainInsertId",
                ),
            ),
            // --------------------------------------
            // "staticOptions" => array(
            //     "var_main" => "mainInsertId",
            // ),//new coa
            // -----------------------------------------
            "fields" => array(
                "suppliers_id" => array(
                    "var_main" => "supplier_id",
                ),
                "produk_id" => array(
                    "var_main" => "mainInsertId",
                ),
                "cabang_id" => array(
                    "var_main" => "cabang_id",
                ),
                // "head_name"            => array(
                //     "var_main" => "nama",
                // ),
                // "p_head_name"          => array(
                //     "var_main" => "strHead_code",
                // ),
                // "create_by"            => array(
                //     "var_main" => "my_name",
                // ),
                /* -------------------------------------------------
                 * filter yg ingin langsung diaktifkan
                 * -------------------------------------------------*/
                "status" => array(
                    "str" => "1",
                ),
                // "is_transaction"       => array(
                //     "str" => "1",
                // ),
                // "is_rekening_pembantu" => array(
                //     "str" => "1",
                // ),
                // "is_hutang" => array(
                //     "str" => "1",
                // ),
                // "is_gl" => array(
                //     "str" => "1",
                // ),
            ),
            // "updateMain"    => array(
            //     "condites" => array(
            //         "id" => "mainInsertId",
            //     ),
            //     "datas"    => array(
            //         "coa_code" => "lastInset_code",
            //     )
            // )
        ),
    );

    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }

    public function linkAddData()
    {
        return "Data/addProduk/" . substr(get_class(), 3);
    }

    public function addProdukIndoor($head_code, $datas)
    {
        arrPrintHijau($head_code);
        // matiHere(__LINE__);
        /*------------mencari data yg sama sudah ada didb belom---------------*/
        $this_data = $this->db->select('*')
            ->from($this->tableName)
            ->where($head_code)
            ->get()
            ->row();
        // sizeof($this_data) == 0 ? matiHere("data untuk $head_code tidak ditemukan " . __METHOD__) : "";

        showLast_query("lime");

        /// menulis ke tabel
        $last_id = $this->addData($datas);
        // showLast_query("merah");

        return $last_id;
        // return $head_code;
    }

    protected $pairedData = array(
        "MdlImages" => array(
            // "kolom"       => array(
            //     "files"         => "images",
            //     ),
            "kolom" => "image",
            "default_nilai" => 0,
            "label" => "image",
            "link" => "image",
            "methode" => "callSpecs",
            "methode_key" => "files",
        ),
    );

    public function getPairedData()
    {
        return $this->pairedData;
    }

    public function setPairedData($pairedData)
    {
        $this->pairedData = $pairedData;
    }
}