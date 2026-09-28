<?php

//--include_once "MdlHistoriData.php";

class MdlSetupBungaPihak3 extends MdlMother
{

    protected $tableName = "bunga_pinjaman_pihak3";
    protected $indexFields = "id";
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");
    protected $validationRules = array(
        "status" => array("required"),
        "repeat" => array("required"),
    );
    protected $outFields = array(
        "extern_id",
        "extern_nama",
        "extern_value",
        "extern_value_2",
        "repeat",
    );
    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }


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
            "editable" => false,
            //--"inputName" => "nama",
        ),

        "extern_nama" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "24", "kolom" => "extern_nama",
            "inputType" => "text",
            "editable" => false,
            //--"inputName" => "nama",
        ),

        "extern_value" => array(
            "label" => "nilai pinjaman (Rp)",
            "type" => "int", "length" => "24", "kolom" => "extern_value",
            "inputType" => "text",
            "editable" => false,
            //--"inputName" => "nama",
        ),

        "extern_value_2" => array(
            "label" => "persentase bunga (%)",
            "type" => "int", "length" => "24", "kolom" => "extern_value_2",
            "inputType" => "text",
            "editable" => true,
            //--"inputName" => "nama",
        ),

//        "note" => array(
//            "label" => "note",
//            "type" => "int", "length" => "5", "kolom" => "note",
//            "inputType" => "textarea",
//            "editable"  => true,
//            //--"inputName" => "nama",
//        ),

//        "serial_no" => array(
//            "label" => "serial no",
//            "type" => "carchar", "length" => "24", "kolom" => "serial_no",
//            "inputType" => "text",
//            "editable"  => true,
//        ),
//
//        "kode" =>array(
//            "label" => "kode",
//            "type" => "varchar", "length" => "24", "kolom" => "kode",
//            "inputType" => "text",
//            "editable"  => true,
//        ),
//
//        "harga_perolehan" => array(
//            "label" => "harga perolehan",
//            "type" => "int", "length" => "24", "kolom" => "harga_perolehan",
//            "inputType" => "text",
//            "editable"  => false,
//            //--"inputName" => "nama",
//        ),

//        "dtime_perolehan" => array(
//            "label" => "tgl perolehan",
//            "type" => "int", "length" => "24", "kolom" => "dtime_perolehan",
//            "inputType" => "date",
//            //--"editable"  => false,
//            //--"inputName" => "nama",
//        ),
//
//        "dtime_start" => array(
//            "label" => "mulai dipakai",
//            "type" => "int", "length" => "24", "kolom" => "dtime_start",
//            "inputType" => "date",
//            //--"editable"  => false,
//            //--"inputName" => "nama",
//        ),

//        "economic_life_time" => array(
//            "label" => "umur ekonomis",
//            "type" => "varchar", "length" => "255", "kolom" => "economic_life_time",
//            "inputType" => "combo",
//            "dataSource" => array(48 => "4T (48 Bln)", 96 => "8T (96 Bln)", 192 => "16T (192 Bln)", 240 => "20T (240 Bln)"), "defaultValue" => 48,
//            //--"inputName" => "nama",
//        ),

        //AssetsProduction
//        "asset_account" => array(
//            "label" => "asset account",
//            "subLabel" => "<span class='fa fa-question-circle link' data-toggle='tooltip' data-placement='bottom' title='--' data-original-title='--'>  </span>",
//            "type" => "varchar", "length" => "255", "kolom" => "asset_account",
//            "inputType" => "combo",
//            "reference" => "MdlFolderAset",
//            //--"inputName" => "nama",
//        ),
//        "rekening_main" => array(
//            "label" => "main rekening",
//            "subLabel" => "<span class='fa fa-question-circle link' data-toggle='tooltip' data-placement='bottom' title='--' data-original-title='--'>  </span>",
//            "type" => "varchar", "length" => "100", "kolom" => "rekening_main",
//            "inputType" => "combo",
//            "reference" => "MdlProdukRakitanPreBiaya",
//            //--"inputName" => "nama",
//        ),
//        "rekening_details" => array(
//            "label" => "detail rekening",
//            "subLabel" => "<span class='fa fa-question-circle link' data-toggle='tooltip' data-placement='bottom' title='--' data-original-title='--'>  </span>",
//            "type" => "varchar", "length" => "255", "kolom" => "rekening_details",
//            "inputType" => "combo",
//            "reference" => "MdlProdukRakitanPreBiaya",
//            //--"inputName" => "nama",
//        ),

//        "residual" => array(
//            "label" => "nilai residu",
//            "type" => "varchar", "length" => "255", "kolom" => "residual_value",
//            "inputType" => "combo",
//            "dataSource" => array(1 => "1"), "defaultValue" => 1,
//            //--"inputName" => "nama",
//        ),

        "repeat" => array(
            "label" => "setiap tanggal?",
            "type" => "varchar", "length" => "20", "kolom" => "repeat",
            "inputType" => "text",
            "eventTrigger" => "onkeyup=\"console.log(this.value)\"",
            //--"inputName" => "nama",
        ),

//        "depresiasi" => array(
//            "label" => "Depresiasi Type",
//            "type" => "int", "length" => "32", "kolom" => "depresiasi",
//            "inputType" => "combo",
//            "dataSource" => array(0=>"Depresiasi (OFF)",1=>"Depresiasi (ON)"), "defaultValue" => 0,
//        ),

        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );

    protected $listedFieldsPending = array(
        "extern_nama" => "name",
        "extern_value" => "nilai pinjaman",
        "extern_value_2" => "Interest (%)",
//        "serial_no" =>"serial_no",
//        "harga_perolehan" => "harga perolehan",
    );

    protected $listedFieldsActive = array(
        "extern_nama" => "name",
        "sisa" => "sisa pinjaman",
        "extern_value_2" => "bunga(%)",
        "nilai_bunga" => "nilai bunga(Rp)",
        "repeat" => "due date",
        "last_update" => "last update",
        "history" => "history pembayaran bunga",
    );


    public function getXorPairs()
    {
        return $this->xorPairs;
    }

    public function setXorPairs($xorPairs)
    {
        $this->xorPairs = $xorPairs;
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

    //region pending
    public function getListedFieldsPending()
    {
        return $this->listedFieldsPending;
    }

    public function setListedFieldsPending($listedFieldsPending)
    {
        $this->listedFieldsPending = $listedFieldsPending;
    }
    //endregion pending

    //region Active
    public function getListedFieldsActive()
    {
        return $this->listedFieldsActive;
    }

    public function setListedFieldsActive($listedFieldsActive)
    {
        $this->listedFieldsActive = $listedFieldsActive;
    }
    //endregion active

    //region Sold
    public function getListedFieldsSold()
    {
        return $this->listedFieldsSold;
    }

    public function setListedFieldsSold($listedFieldsSold)
    {
        $this->listedFieldsSold = $listedFieldsSold;
    }
    //endregion sold

    //region Depre
    public function getListedFieldsDepre()
    {
        return $this->listedFieldsDepre;
    }

    public function setListedFieldsDepre($listedFieldsDepre)
    {
        $this->listedFieldsDepre = $listedFieldsDepre;
    }
    //endregion depre

}