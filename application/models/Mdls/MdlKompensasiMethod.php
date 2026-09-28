<?php


class MdlKompensasiMethod extends MdlMother
{

    protected $tableName = "per_supplier_diskon_target_debet";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'", "trash='0'"
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
        ),
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
        ),
        "coa_code" => array(
            "label" => "name",
            "type" => "varchar", "length" => "255", "kolom" => "coa_code",
            "inputType" => "text",
        ),
    );
//    protected $staticData = array(
//        array(
//            "id" => "1",
//            "name" => "CASH/TUNAI",
//            "coa_code" => "1010010010",
//        ),
//        array(
//            "id" => "2",
//            "name" => "CREDIT NOTE",
//            "coa_code" => "1010010030",
//        ),
//        array(
//            "id" => "3",
//            "name" => "PIUTANG SUPPLIER",
//            "coa_code" => "1010020030",
//        ),
//    );
    protected $listedFields = array();
    protected $unionPairs = array();

    function __construct()
    {
        parent::__construct();
    }

    //region getter setter
    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
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
    //endregion


//    //@override with static data
//    public function lookupAll()
//    {
////        arrprint($this->staticData);
//        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
////            cekkuning("ada isinya");
//            $iCtr = 0;
//            $sql = "";
//			arrPrintPink($this->filters);
//            foreach ($this->staticData as $iSpec) {
//                arrPrint($iSpec);
//                $iCtr++;
//                $sql .= 'SELECT ';
//                $fCtr = 0;
//                foreach ($this->fields as $fID => $fSpec) {
//                    $fCtr++;
//                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
//                    if ($fCtr < sizeof($this->fields)) {
//                        $sql .= ",";
//                    }
//                }
//                if ($iCtr < sizeof($this->staticData)) {
//                    $sql .= " union ";
//                }
//            }
////            cekkuning($sql);
//            return $this->db->query($sql);
//        }
//        else {
////            cekkuning("TIDAK ada isinya");
//            return null;
//        }
//
//    }
//    public function lookupByKeyword($key)
//    {
//        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
////            cekkuning("ada isinya");
//            $iCtr = 0;
//            $sql = "";
////			arrprint($this->filters);
//            foreach ($this->staticData as $iSpec) {
//                $iCtr++;
//                $sql .= 'SELECT ';
//                $fCtr = 0;
//                foreach ($this->fields as $fID => $fSpec) {
//                    $fCtr++;
//                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
//                    if ($fCtr < sizeof($this->fields)) {
//                        $sql .= ",";
//                    }
//                }
//                if ($iCtr < sizeof($this->staticData)) {
//                    $sql .= " union ";
//                }
//            }
////            cekkuning($sql);
//            return $this->db->query($sql);
//        }
//        else {
////            cekkuning("TIDAK ada isinya");
//            return null;
//        }
//    }
//
//    public function lookupByID($id)
//    {
//        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
////            cekkuning("ada isinya");
//            $iCtr = 0;
//            $sql = "";
////			arrprint($this->fields);
//            $tmp = array();
//            foreach ($this->staticData as $aSpec) {
//                $arrNew = array();
//                if (in_array($id, $aSpec)) {
//                    foreach ($this->fields as $fID => $fSpec) {
//                        $arrNew[$fID] = $aSpec[$fID];
//                    }
//                    $tmp[] = $arrNew;
//                }
//
//            }
//
//            foreach ($tmp as $iSpec) {
//                if (in_array($id, $iSpec)) {
//                    $iCtr++;
//                    $sql .= 'SELECT ';
//                    $fCtr = 0;
//                    foreach ($this->fields as $fID => $fSpec) {
////                        cekHere($fID);
//                        $fCtr++;
//                        $sql .= "'" . $iSpec[$fID] . "' as $fID";
//                        if ($fCtr < sizeof($this->fields)) {
//                            $sql .= ",";
//                        }
//                    }
//                    if ($iCtr < sizeof($tmp)) {
//                        $sql .= " union ";
//                    }
//                }
//
//            }
//
//            return $this->db->query($sql);
//
////            arrPrint($arr);
//        }
//        else {
////            cekkuning("TIDAK ada isinya");
//            return null;
//        }
//
//    }



}