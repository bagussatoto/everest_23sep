<?php

//--include_once "MdlHistoriData.php";

class MdlMother_static extends CI_Model
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

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
        "cabang_id" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),

    );
    protected $staticData = array();


    protected $listedFields = array(
        "nama" => "name",
        "due_days" => "due days",
        "status" => "status",

    );

    public function __construct()
    {
        parent::__construct();

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


    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    function init()
    {
//        cekmerah("init start");
//        arrprint($this->filters);
        $newFilters = array();
        if (sizeof($this->filters) > 0) {

            foreach ($this->filters as $f) {
                $f_ex = explode("=", $f);
                if ($f_ex[0] == "relName") {
                    $tableIndex = trim($f_ex[1], "'");
                    $mdlName = "Com" . $tableIndex;

                    $this->load->model("Coms/$mdlName");
                    $o = New $mdlName();
                    $this->tableName = $o->getTableName();

                }
                else {
                    $newFilters[] = $f;

                }
            }
            $this->filters = $newFilters;
        }
        else {
//            cekKuning("tidak pakai filters....");
        }
//        arrprint($this->filters);
//        cekmerah("init stop");
    }

    //@override with static data
    public function lookupAll()
    {
//        arrprint($this->getFilters());
//        cekbiru("executing lookupAll()");
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
//        arrprint($rawFilters);
//arrPrint($this->staticData);
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("STATIC ada isinya");

            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
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
//        arrprint($this->getFilters());
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
//        arrprint($rawFilters);
//        arrprint($this->staticData);
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $included = sizeof($rawFilters) > 0 ? false : true;
                $iCtr++;
                $subSql = 'SELECT ';
                $fCtr = 0;
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
                        }
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $subSql .= " union ";
                }


                if ($included) {
                    $sql .= $subSql;
                }
            }
            $sql = rtrim($sql, " union ");
//            cekkuning($sql);

            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }


    //lookupByKeyword

    public function lookupByID($id)
    {
//        arrprint($this->getFilters());
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
//        arrprint($rawFilters);

//        arrprint($this->staticData);
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
//            cekkuning("iterating static data");
            foreach ($this->staticData as $iSpec) {
                if ($id == $iSpec["id"]) {

                    $included = sizeof($rawFilters) > 0 ? false : true;
                    $iCtr++;
                    $subSql = 'SELECT ';
                    $fCtr = 0;
                    foreach ($this->fields as $fID => $fSpec) {
                        $fCtr++;
                        $subSql .= "'" . $iSpec[$fID] . "' as $fID";
                        if ($fCtr < sizeof($this->fields)) {
                            $subSql .= ",";
                        }

                        if (sizeof($rawFilters) > 0) {
                            if (array_key_exists($fID, $rawFilters) && $iSpec[$fID] == $rawFilters[$fID]) {
                                $included = true;
                            }
                        }
                    }
                    if ($iCtr < sizeof($this->staticData)) {
                        $subSql .= " union ";
                    }
                    if ($included) {
                        $sql .= $subSql;
                    }
                }
            }
//            cekkuning($sql);
            $sql = rtrim($sql, " union ");
            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }

}