<?php

//--include_once "MdlHistoriData.php";
class MdlProdukRakitanPreBiaya extends MdlMother
{
    protected $tableName = "produk_pre_biaya";
    // index fields dengan coa data tidak terbaca di tabel produk_pre_biaya
    // kolom biaya_id menjadi coa, biaya_nama menjadi kosong
    // saat ini dikembalikan ke id data. tgl 22 november 2022
//    protected $indexFields = "coa_code";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='item_biaya'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "folder" => array("required"),
        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        // "folder"     => array(
        //     "label"     => "Product Parent",
        //     "type"      => "int", "length" => "24", "kolom" => "folder",
        //     "inputType" => "combo",
        //     "reference" => "MdlProdukRakitan",
        //     //--"inputName" => "folders",
        // ),
        // "kode"       => array(
        //     "label"     => "kode",
        //     "type"      => "int", "length" => "24", "kolom" => "kode",
        //     "inputType" => "text",
        //     //--"inputName" => "kode",
        // ),
        // "label"      => array(
        //     "label"     => "label",
        //     "type"      => "int", "length" => "24", "kolom" => "label",
        //     "inputType" => "text",
        //     //--"inputName" => "label",
        // ),
        "nama" => array(
            "label" => "Cost name",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            "editable" => false,
            //--"inputName" => "nama",
        ),
        "coa_code" => array(
            "label" => "coa code",
            "type" => "int", "length" => "24", "kolom" => "coa_code",
            "inputType" => "text",
//            "editable"  => false,
            //--"inputName" => "nama",
        ),
        // "keterangan" => array(
        //     "label"     => "Values",
        //     "type"      => "int", "length" => "24", "kolom" => "label",
        //     "inputType" => "text",
        //     //--"inputName" => "",
        // ),
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
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "id" => "id",
        // "kode"       => "kode",
        // "label"      => "label",
        // "nama_id"       => "nama",
        "nama" => "nama",
        "coa_code" => "coa_code",
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

}