<?php

//--include_once "MdlHistoriData.php";

class MdlBiayaJasaStatic extends MdlMother
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='payment'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "name" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "mdl_name" => array(
            "label" => "mdl_name",
            "type" => "int", "length" => "24", "kolom" => "mdl_name",
            "inputType" => "text",
        ),
        "comName_items" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "comName_items",
            "inputType" => "text",
        ),
        "coa_code" => array(
            "label" => "coa code",
            "type" => "int", "length" => "24", "kolom" => "coa_code",
            "inputType" => "text",
        ),

    );
    protected $staticData = array(
        array(
            "id" => "1",
            "nama" => "biaya umum",
            "mdl_name" => "MdlDtaBiayaUmum",
            "comName_items" => "RekeningPembantuBiayaUmum",
//            "label" =>"ppn masukan",
            "coa_code"=>"6030",//lihat di coa
        ),
//        array(
//            "id"=>"2",
//            "nama"=>"biaya produksi",
//            "mdl_name" =>"MdlDtaBiayaProduksi",
//            "comName_items" =>"ComRekeningPembantuBiayaProduksi",
////            "label" =>"ppn masukan",
//        ),
        array(
            "id" => "3",
            "nama" => "biaya usaha",
            "mdl_name" => "MdlDtaBiayaUsaha",
            "comName_items" => "RekeningPembantuBiayaUsaha",
            "coa_code"=>"6010",//lihat di coa
        ),
//        array(
//            "id" => "4",
//            "nama" => "biaya operasional",
//            "mdl_name" => "MdlDtaBiayaOperasional",
//            "comName_items" => "RekeningPembantuBiayaOperasional",
//            "coa_code"=>"0603",//lihat di coa
//        ),


    );
    protected $listedFields = array(
        "nama" => "nama",
        "label" => "label",
//        "due_days" => "due days",
//        "status"   => "status",
    );

    public function __construct()
    {

    }

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


    //@override with static data

    public function lookupAll()
    {
        if(isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData)>0){
//            cekkuning("ada isinya");
            $iCtr=0;
            $sql="";
//			arrprint($this->filters);
            foreach($this->staticData as $iSpec){
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr=0;
                foreach($this->fields as $fID=>$fSpec){
                    $fCtr++;
                    $sql .= "'".$iSpec[$fID]."' as $fID";
                    if($fCtr<sizeof($this->fields)){
                        $sql.=",";
                    }
                }
                if($iCtr<sizeof($this->staticData)){
                    $sql.=" union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }else{
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }
    public function lookupByKeyword($key)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }
    }

    public function lookupByID($id)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->fields);
            $tmp = array();
            foreach ($this->staticData as $aSpec) {
                $arrNew = array();
                if (in_array($id, $aSpec)) {
                    foreach ($this->fields as $fID => $fSpec) {
                        $arrNew[$fID] = $aSpec[$fID];
                    }
                    $tmp[] = $arrNew;
                }

            }

            foreach ($tmp as $iSpec) {
                if (in_array($id, $iSpec)) {
                    $iCtr++;
                    $sql .= 'SELECT ';
                    $fCtr = 0;
                    foreach ($this->fields as $fID => $fSpec) {
//                        cekHere($fID);
                        $fCtr++;
                        $sql .= "'" . $iSpec[$fID] . "' as $fID";
                        if ($fCtr < sizeof($this->fields)) {
                            $sql .= ",";
                        }
                    }
                    if ($iCtr < sizeof($tmp)) {
                        $sql .= " union ";
                    }
                }

            }

            return $this->db->query($sql);

//            arrPrint($arr);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }
}