<?php

//--include_once "MdlHistoriData.php";
class MdlProdukMergerRakitan extends MdlMother
{
    protected $tableName = "produk";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis<>'folder'",
        "jenis in ('item', 'item_rakitan')",
        "status=1",
        "trash=0"
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "nama",
        "kode" => "kode",
        "keterangan" => "keterangan",
        "label" => "label",
        "no_part" => "no_part",
    );
    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        //        "status" => array("required"),
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
        "folder" => array(
            "label" => "folder",
            "type" => "int", "length" => "24", "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlFolderProduk",

        ),
        "kode" => array(
            "label" => "kode",
            "type" => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "label" => array(
            "label" => "label",
            "type" => "int", "length" => "24", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "label",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "keterangan" => array(
            "label" => "keterangan",
            "type" => "varchar", "length" => "255", "kolom" => "keterangan",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "deskripsi" => array(
            "label" => "deskripsi",
            "type" => "int", "length" => "24", "kolom" => "deskripsi",
            "inputType" => "text",
            //--"inputName" => "",
        ),

        "satuan" => array(
            "label" => "satuan",
            "type" => "int", "length" => "24", "kolom" => "satuan",
            "inputType" => "combo",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
        ),
        "nopart" => array(
            "label" => "no part",
            "type" => "int", "length" => "24", "kolom" => "no_part",
            "inputType" => "text",
            //--"inputName" => "satuan",
        ),

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

        "panjang_ckd" => array(
            "label" => "CKD length (in millimeters)",
            "type" => "int", "length" => "24", "kolom" => "panjang_gross",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
        "lebar_ckd" => array(
            "label" => "CKD width (in millimeters)",
            "type" => "int", "length" => "24", "kolom" => "lebar_gross",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
        "tinggi_ckd" => array(
            "label" => "CKD height (in millimeters)",
            "type" => "int", "length" => "24", "kolom" => "tinggi_gross",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
        "berat_ckd" => array(
            "label" => "CKD weight (in grams)",
            "type" => "int", "length" => "24", "kolom" => "berat_gross",
            "inputType" => "number",
            //--"inputName" => "berat",
        ),
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
    );
    protected $listedFields = array(
        "folders" => "folder",
        "kode" => "kode",
        "label" => "label",
        "nama" => "nama",
        "keterangan" => "keterangan",
        "satuan" => "satuan",
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