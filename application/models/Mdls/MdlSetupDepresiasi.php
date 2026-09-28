<?php

//--include_once "MdlHistoriData.php";

class MdlSetupDepresiasi extends MdlMother
{

    protected $tableName = "setup_depresiasi";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
//    protected $filters = array("jenis<>'division'","status='1'", "trash='0'");
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
//        "tlp_1"  => array("required", "numberOnly"),
        "status" => array("required"),
//        "extern_nama" => array("readonly"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "extern_id" => array(
            "label" => "extern id",
            "type" => "int", "length" => "24", "kolom" => "extern_id",
            "inputType" => "hidden",
//            "editable"  => false,
            //--"inputName" => "nama",
        ),
        "cabang_id" => array(
            "label" => "cabang id",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "hidden",
//            "editable"  => false,
            //--"inputName" => "nama",
        ),
        "extern_nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "extern_nama",
            "inputType" => "text",
            "editable" => false,
            //--"inputName" => "nama",
        ),
        "serial_no" => array(
            "label" => "serial no",
            "type" => "carchar", "length" => "24", "kolom" => "serial_no",
            "inputType" => "text",
            "editable" => false,
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "varchar", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            "editable" => false,
        ),
        "harga_perolehan" => array(
            "label" => "harga perolehan",
            "type" => "int", "length" => "24", "kolom" => "harga_perolehan",
            "inputType" => "text",
            "editable" => false,
            //--"inputName" => "nama",
        ),
        "dtime_perolehan" => array(
            "label" => "tgl perolehan",
            "type" => "int", "length" => "24", "kolom" => "dtime_perolehan",
            "inputType" => "date",
//            "editable"  => false,
            //--"inputName" => "nama",
        ),
        "dtime_start" => array(
            "label" => "mulai dipakai",
            "type" => "int", "length" => "24", "kolom" => "dtime_start",
            "inputType" => "date",
//            "editable"  => false,
            //--"inputName" => "nama",
        ),
        "economic_life_time" => array(
            "label" => "umur ekonomis",
            "type" => "varchar", "length" => "255", "kolom" => "economic_life_time",
            "inputType" => "combo",
            "dataSource" => array(48 => "4(tahun)", 96 => "8(tahun)", 192 => "16(tahun)", 120 => "20(tahun)"), "defaultValue" => 48,
            //--"inputName" => "nama",
        ),
        "residual" => array(
            "label" => "nilai residu",
            "type" => "varchar", "length" => "255", "kolom" => "residual_value",
            "inputType" => "combo",
            "dataSource" => array(1 => "1"), "defaultValue" => 1,
            //--"inputName" => "nama",
        ),
        "repeat" => array(
            "label" => "tanggal depresiasi",
            "type" => "int", "length" => "255", "kolom" => "repeat",
            "inputType" => "text",

            //--"inputName" => "nama",
        ),

        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
//            "inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "extern_nama" => "name",
//        "extern_nama" => "name",
        "kode" => "kode",
        "serial_no" => "serial_no",
        "harga_perolehan" => "harga perolehan",
        "dtime_perolehan" => "tanggal perolehan",
        "dtime_start" => "mulai dipakai",
        "repeat" => "tanggal depresiasi",
        "economic_life_time" => "masa ekonomis(bln)",
        "residual_value" => "nilai residu",
//        "alamat" => "address",

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

    /*----------------------------------------------------------------
 * auto penambahan COA, bisa dugunakan keperluan lain
 * konnecting ke model yg lain
 * ----------------------------------------------------------*/
    // protected $connectingData = array(
    //     "MdlAccounts" => array(
    //         "path"          => "Mdls",
    //         "fungsi"        => "addExtern_coa",
    //         /* ------------------- ------------------- -------------------
    //          * staticOptions bisa handling array atau singgle
    //          * ---------------------------------------------------------*/
    //         "staticOptions" => "010304",
    //         "fields"        => array(
    //             "extern_jenis"   => array(
    //                 "str" => "produk",
    //             ),
    //             "extern_id"      => array(
    //                 "var_main" => "mainInsertId",
    //             ),
    //             "rekening"      => array(
    //                 "var_main" => "mainInsertId",
    //             ),
    //             "head_name"      => array(
    //                 "var_main" => "nama",
    //             ),
    //             "p_head_name"    => array(
    //                 "var_main" => "strHead_code",
    //             ),
    //             "create_by"      => array(
    //                 "var_main" => "my_name",
    //             ),
    //             /* -------------------------------------------------
    //              * filter yg ingin langsung diaktifkan
    //              * -------------------------------------------------*/
    //             "is_active"      => array(
    //                 "str" => "1",
    //             ),
    //             "is_transaction" => array(
    //                 "str" => "1",
    //             ),
    //             "is_rekening_pembantu" => array(
    //                 "str" => "1",
    //             ),
    //             // "is_hutang" => array(
    //             //     "str" => "1",
    //             // ),
    //             // "is_gl" => array(
    //             //     "str" => "1",
    //             // ),
    //         ),
    //         "updateMain"    => array(
    //             "condites" => array(
    //                 "id" => "mainInsertId",
    //             ),
    //             "datas"    => array(
    //                 "coa_code" => "lastInset_code",
    //             )
    //         )
    //     )
    // );
    //
    // public function getConnectingData()
    // {
    //     return $this->connectingData;
    // }
    //
    // public function setConnectingData($connectingData)
    // {
    //     $this->connectingData = $connectingData;
    // }
}