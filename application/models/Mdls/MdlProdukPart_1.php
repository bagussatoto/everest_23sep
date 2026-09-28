<?php

class MdlProdukPart_1 extends MdlMother
{
    protected $tableName = "produk_part_1";
    protected $indexFields = "id";
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "sku" => array("required", "singleOnly"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"   => array(
            "label"     => "nama/alias",
            "type"      => "int",
            "length"    => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
            //            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "sku"   => array(
            "label"     => "SKU/KODE",
            "type"      => "int",
            "length"    => "255", "kolom" => "sku",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),

        // "skala"             => array(
        //     "label"      => "size/skala",
        //     "type"       => "int",
        //     "length"     => "255",
        //     "kolom"      => "size_id",
        //     "inputType"  => "combo",
        //     "reference"  => "MdlProdukSize",
        //     "strField"   => "nama",
        //     "editable"   => true,
        //     "kolom_nama" => "size_nama",
        //     "add_btn"    => true,
        //     "mdlChild"   => array("tipe_id"),
        // ),
        // "skala_nama"        => array(
        //     "label"     => "skala",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "size_nama",
        //     "inputType" => "hidden",
        //     // "kolom_nama" => "kategori_nama",
        // ),
        "barcode"           => array(
            "label"      => "barcode",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "barcode",
            //            "inputType"  => "combo",
            "inputType"  => "text",
        //     "reference"  => "MdlTipe",
            "strField"   => "nama",
            "editable"   => true,
            // "kolom_nama" => "tipe_nama",
            "add_btn"    => true,
        ),
        // "tipe_nama"         => array(
        //     "label"     => "kapasitas (PK/HP)",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "tipe_nama",
        //     "inputType" => "hidden",
        //     // "kolom_nama" => "kategori_nama",
        // ),
        "merek" => array(
            "label"      => "merek",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "merek_id",
            "inputType"  => "combo",
            "reference"  => "MdlMerek",
            "referenceFilter"  => array(
                "id" => array(
                    "var" => "merek_id"
                )
            ),
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "merek_nama",
            "add_btn"    => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "merek_nama" => array(
            "label"      => "merek",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "merek_nama",
            "inputType"  => "hidden",
            "kolom_nama" => "merek_nama",
        ),
        "supplier" => array(
            "label"      => "supplier",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "supplier_id",
            "inputType"  => "combo",
            "reference"  => "MdlSupplier",
            "referenceFilter"  => array(
                "id" => array(
                    "var" => "supplier_id"
                )
            ),
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "supplier_nama",
            "add_btn"    => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "supplier_nama" => array(
            "label"      => "supplier",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "supplier_nama",
            "inputType"  => "hidden",
            // "kolom_nama" => "merek_nama",
        ),
        "status" => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "nama" => "name",
        "barcode" => "barcode",
        "kode" => "sku",
        "merek_nama" => "merek",
        "supplier_nama" => "supplier",
    );

    //region gs
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