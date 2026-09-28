<?php

//--include_once "MdlHistoriData.php";
class MdlJasa extends MdlMother
{
    protected $tableName = "produk_supplies";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='jasa'",
        "item_jenis='1'",
        "status='1'",
        "trash='0'",
        );

    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        //        "kode" => array(
        //            "label" => "kode",
        //            "type" =>"int","length"=>"24","kolom" => "kode",
        //            "inputType" => "text",
        //            //--"inputName" => "kode",
        //        ),
        //        "label" => array(
        //            "label" => "label",
        //            "type" =>"int","length"=>"24","kolom" => "label",
        //            "inputType" => "text",
        //            //--"inputName" => "label",
        //        ),
        "nama"   => array(
            "label"     => "nama",
            "type"      => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        //        "keterangan" => array(
        //            "label" => "keterangan",
        //            "type" =>"int","length"=>"24","kolom" => "label",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "deskripsi" => array(
        //            "label" => "deskripsi",
        //            "type" =>"int","length"=>"24","kolom" => "deskripsi",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        "folder" => array(
            "label"     => "folder/kategory",
            "type"      => "int", "length" => "24", "kolom" => "folders",
            "inputType" => "combo",
            //--"inputName" => "folders",
        ),
        "satuan" => array(
            "label"     => "satuan",
            "type"      => "int", "length" => "24", "kolom" => "satuan",
            "inputType" => "combo",
            //--"inputName" => "satuan",
        ),
        //        "nopart" => array(
        //            "label" => "no part",
        //            "type" =>"int","length"=>"24","kolom" => "no_part",
        //            "inputType" => "combo",
        //            //--"inputName" => "satuan",
        //        ),
        "jenis"  => array(
            "label"      => "pilih jenis",
            "type"       => "int", "length" => "24", "kolom" => "jenis",
            "inputType"  => "combo",
            "dataSource" => array("folder", "item"),
            //--"inputName" => "jenis",
        ),
        "status" => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        //        "disc_cat" => array(
        //            "label" => "discount cat",
        //            "type" =>"int","length"=>"24","kolom" => "diskon_kategori",
        //            "inputType" => "combo",
        //            "dataSource" => array("folder", "item"),
        //            //--"inputName" => "jenis",
        //        ),

        //        "berat" => array(
        //            "label" => "weight (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "berat",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang" => array(
        //            "label" => "length (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "panjang",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar" => array(
        //            "label" => "width (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "lebar",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi" => array(
        //            "label" => "height (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "tinggi",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume" => array(
        //            "label" => "volume (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "volume",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "berat ckd" => array(
        //            "label" => "weight (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "berat_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang ckd" => array(
        //            "label" => "length (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "panjang_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar ckd" => array(
        //            "label" => "width (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "lebar_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi ckd" => array(
        //            "label" => "height (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "tinggi_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume ckd" => array(
        //            "label" => "volume (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "volume_gross",
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
    );
    protected $listedFields = array(
        "nama"       => "name",
        "keterangan" => "keterangan",

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