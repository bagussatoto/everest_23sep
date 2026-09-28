<?php

class MdlStaticNotes extends MdlMother
{
    protected $tableName = "static_note";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("fungsi='notes'", "trash='0'");

    protected $validationRules = array(
        "nilai" => array("required"),
        // "untuk" => array("required", "singleOnly"),
        "jenis" => array("required", "singleOnly"),
        "cabang_id" => array("required"),
    );

    protected $listedFieldsView = array("nilai");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "cabang_id" => array(
            "label" => "cabang",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",// hidden
            "reference" => "MdlCabang",
            //--"inputName" => "id",
        ),
        // "untuk" => array(
        //     "label" => "transaksi type",
        //     "type" => "text", "length" => "", "kolom" => "untuk",
        //     "inputType" => "combo",
        //     "dataSource" => array(
        //         "reguler sales" => "reguler sales",
        //         "international sales" => "international sales",
        //         "service sales" => "service sales",
        //         "service purcashing"=>"service purcashing",
        //         "service purcashing project"=>"service purcashing project",
        //         "FG purcashing"=>"FG purcashing",
        //         ),
        //     "defaultValue" => "reguler sales",
        //     //--"inputName" => "nama",
        // ),
        // "jenis" => array(
        //     "label" => "transaksi type",
        //     "type" => "text", "length" => "", "kolom" => "jenis",
        //     "inputType" => "combo",
        //     "dataSource" => array(
        //         "582" => "reguler sales",
        //         "382" => "international sales",
        //         "385" => "service sales",
        //         "463"=>"service purcashing",
        //         "3463"=>"service purcashing project",
        //         "466"=>"FG purcashing",
        //         ),
        //     "defaultValue" => "reguler sales",
        //     //--"inputName" => "nama",
        // ),
        "jenis" => array(
            "label" => "transaksi type",
            "type" => "text", "length" => "", "kolom" => "jenis",
            "inputType" => "combo",
            "reference" => "MdlJenisTransaksiStaticNotes",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "untuk",
            //--"inputName" => "nama",
        ),
        "nilai" => array(
            "label" => "notes",
            "type" => "text", "length" => "", "kolom" => "nilai",
            "inputType" => "textarea",
            //--"inputName" => "nama",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "255", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "untuk" => "transaksi",
        "nilai" => "notes",
        "cabang_id" => "cabang",
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
            "MdlJenisTransaksiStaticNotes"     => array(
                "id"         => "jenis",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "untuk",
                ),
            ),
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
        );

        return $mdls;

    }


}