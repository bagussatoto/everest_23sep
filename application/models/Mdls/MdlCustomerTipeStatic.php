<?php

//--include_once "MdlHistoriData.php";

class MdlCustomerTipeStatic extends MdlMother_static
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
//    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='pettycash'",
//        "status='1'",
//        "trash='0'",
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
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "label" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "label",
            "inputType" => "text",
        ),
//        "mdl_name" => array(
//            "label" => "name",
//            "type" => "int", "length" => "24", "kolom" => "mdl_name",
//            "inputType" => "text",
//        ),

    );
    protected $staticData = array(
        array(
            "id" => "1",
            "name" => "reguler",
            "nama" => "reguler",
            "label" => "reguler",
//            "mdl_name" => "MdlDtaBiayaProduksi",
//            "allowed_branch" => array(25),
        ),
        array(
            "id" => "2",
            "nama" => "projek",
            "name" => "projek",
            "label" => "projek",
//            "mdl_name" => "MdlDtaBiayaUsaha",
//            "allowed_branch" => array(1,21),
        ),
    );
    protected $listedFields = array(
        "name" => "nama",
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

    public function lookupAll_old()
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";

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

    public function lookupAll()
    {
        $filterSplitters = array("=", "<>");
        $rawFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                foreach ($filterSplitters as $spl) {
                    if (strpos($f, $spl) !== false) {
                        $ex = explode($spl, $f);
                        if (sizeof($ex) > 1) {
                            $rawFilters[$ex[0]] = trim($ex[1], "'");
                        }
                    }
                }
            }
        }

        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {

            $iCtr = 0;
            $sql = "";

            foreach ($this->staticData as $iSpec) {
                $included = sizeof($rawFilters) > 0 ? false : true;
                $iCtr++;
                $subSql = 'SELECT ';
                $fCtr = 0;
                $inclCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
//                    cekbiru("checking $fID");
                    $fCtr++;
                    $subSql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $subSql .= ",";
                    }

                    if (sizeof($rawFilters) > 0) {
                        if (array_key_exists($fID, $rawFilters) && $iSpec[$fID] == $rawFilters[$fID]) {
                            $included = true;
                            $inclCtr++;
//                            cekbiru($iSpec[$fID]."/$fID included");
                        }
                        else {
//                            cekmerah($iSpec[$fID]."/$fID NOT included");
                        }
                    }
                    else {
//                        cekbiru("NO RAW FILTER");
                    }
                }

//                if ($iCtr < sizeof($this->staticData)) {
//                    $subSql .= " union ";
//                }

                $subSql .= " union ";
//cekHitam(":: $subSql ::");

                if (sizeof($rawFilters) > 0) {
                    if ($inclCtr >= sizeof($rawFilters)) {
                        $sql .= $subSql;
                    }
//                    else{
//                        cekMerah("$inclCtr :: ". sizeof($rawFilters));
//                    }
                }
                else {
                    $sql .= $subSql;
                }

//cekUngu(":: $sql ::");
            }

            $sql = rtrim($sql, " union ");
//cekkuning($sql);

            if ($sql == "") {
                return $this->db->query("select * from data__tmp where 999='-9999' limit 0,1");//==akal2an
            }
            else {
                return $this->db->query($sql);
            }
        }
        else {
//            cekkuning("STATIC TIDAK ada isinya");
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
//            cekkuning("ada isinya");
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