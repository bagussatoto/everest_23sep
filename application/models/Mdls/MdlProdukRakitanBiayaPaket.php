<?php

//--include_once "MdlHistoriData.php";
class MdlProdukRakitanBiayaPaket extends MdlMother
{
    protected $tableName = "produk_biaya";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='biaya_rakitan_paket'", "status='1'", "trash='0'");
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "produk_nama" => "produk_biaya.produk_nama",
        "biaya_nama" => "produk_biaya.biaya_nama",
    );
    protected $validationRules = array(
        "produk_id" => array("required", "singleOnly"),
        "biaya_id" => array("required", "singleOnly"),
        "nilai" => array("required"),
        "jml" => array("required"),
        "status" => array("required"),
    );

    protected $validateData = array(
        "biaya_id" => "biaya_id",
        "produk_id" => "produk_id",
    );
    protected $listedFieldsView = array("produk_nama");

    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "produk_id" => array(
            "label" => "Target Biaya",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProduk_Paket_Project",
            "strField" => "nama",
            "editable" => false, // edit setelah save
            "kolom_nama" => "produk_nama",
        ),

        "produk_nama" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_nama",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
//        "biaya_id" => array(
//            "label" => "Cost name",
//            "type" => "int",
//            "length" => "24",
//            "kolom" => "biaya_id",
//            "inputType" => "combo",
//            "reference" => "MdlProdukRakitanPreBiayaPaket",
//            "strField" => "nama",
//            "editable" => false,
//            "kolom_nama" => "biaya_nama",
//        ),
        "biaya_id" => array(
            "label" => "Jenis Biaya",
            "type" => "int",
            "length" => "24",
            "kolom" => "biaya_id",
            "inputType" => "combo",
            "reference" => "MdlDtaBiayaProduksi",
            "strField" => "nama",
            "editable" => false, // edit setelah save
            "kolom_nama" => "biaya_nama",
        ),
        "biaya_nama" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "biaya_nama",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "value" => array(
            "label" => "Harga/Lot",
            "type" => "int",
            "length" => "24",
            "kolom" => "nilai",
            "inputType" => "text",
            // "editable"  => false,
            //--"inputName" => "",
        ),
        "jml" => array(
            "label" => "Jumlah Lot Digunakan",
            "type" => "int",
            "length" => "24",
            "kolom" => "jml",
            "inputType" => "text",
            "defaultValue" => 1,
            // "editable"  => false,
            //--"inputName" => "",
        ),
        "total_biaya" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "total_biaya",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        // "deskripsi"  => array(
        //     "label"     => "Value",
        //     "type"      => "int", "length" => "24", "kolom" => "deskripsi",
        //     "inputType" => "text",
        //     //--"inputName" => "",
        // ),
        // "satuan" => array(
        //     "label"     => "satuan",
        //     "type"      => "int", "length" => "24", "kolom" => "satuan",
        //     "inputType" => "combo",
        //     //            "dataSource" => array(
        //     //                "pcs" => "piece",
        //     //                "unit" => "unit"),
        //     //--"inputName" => "satuan",
        //     "reference" => "MdlSatuan",
        // ),
        // "nopart" => array(
        //     "label"     => "no part",
        //     "type"      => "int", "length" => "24", "kolom" => "no_part",
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
        //        "berat" => array(
        //            "label" => "weight (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "berat",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang" => array(
        //            "label" => "length (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "panjang",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar" => array(
        //            "label" => "width (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "lebar",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi" => array(
        //            "label" => "height (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "tinggi",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume" => array(
        //            "label" => "volume (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "volume",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        // "panjang_ckd" => array(
        //     "label"     => "CKD length (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "panjang_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "lebar_ckd"   => array(
        //     "label"     => "CKD width (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "lebar_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "tinggi_ckd"  => array(
        //     "label"     => "CKD height (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "tinggi_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "berat_ckd"   => array(
        //     "label"     => "CKD weight (in grams)",
        //     "type"      => "int", "length" => "24", "kolom" => "berat_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        //        "volume_ckd" => array(
        //            "label" => "volume (CKD)",
        //            "type" => "int", "length" => "24", "kolom" => "volume_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
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
        "status" => array(
            "label" => "status",
            "type" => "int",
            "length" => "24",
            "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    // protected $updateFields = array(
    //     "nama" => array(
    //         "label"     => "namae",
    //         "type"      => "int",
    //         "length"    => "24",
    //         "kolom"     => "nama",
    //         "inputType" => "text",
    //         "reference" => "MdlProdukRakitanPreBiaya",
    //     ),
    // );
    protected $listedFields = array(
        "biaya_nama" => "costing name",
        "produk_nama" => "product name",
        "nilai" => "value(Rp)",
        "jml" => "jml Lot",
        "total_biaya" => "Total Biaya",
        // "keterangan" => "keterangan",
        // "satuan" => "satuan",
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
//            "MdlFolderProdukRakitan" => array(
//                "id" => "folders",
//                // "str" => "merek_nama",
//                "kolomDatas" => array(
//                    "nama" => "folders_nama",
//                ),
//            ),
//            "MdlProduk_Paket_Project" => array(
//                "id" => "produk_id",
//                // "str" => "merek_nama",
//                "kolomDatas" => array(
//                    "nama" => "produk_nama",
//                ),
//            ),
//            "MdlProdukKategori" => array(
//                "id" => "kategori_id",
//                // "str" => "merek_nama",
//                "kolomDatas" => array(
//                    "nama" => "kategori_nama",
//                ),
//            ),
            // "MdlKendaraan"    => array(
            //     "id"  => "kendaraan_id",
            //     // "str" => "kendaraan_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kendaraan_nama",
            //     ),
            // ),
            // "MdlLokasiIndex"  => array(
            //     "id"  => "lokasi",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "lokasi_nama",
            //     ),
            // ),
//            "MdlProdukJenis" => array(
//                "id" => "produk_jenis_id",
//                // "str" => "lokasi_nama",
//                "kolomDatas" => array(
//                    "kode" => "produk_jenis_nama",
//                    "nilai" => "produk_jenis_nilai",
//                ),
//            ),
//            "MdlCabangProduksi" => array(
//                "id" => "cabang_id",
//                // "str" => "lokasi_nama",
//                "kolomDatas" => array(
//                    // "kode" => "produk_jenis_nama",
//                    "nama" => "cabang_nama",
//                ),
//            ),
        );

        return $mdls;

    }

}