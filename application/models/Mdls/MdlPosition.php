<?php

class MdlPosition extends MdlMother_static
{
    protected $tableName = "position";
    protected $indexFields = "id";
    protected $tableNames = array();

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();
    protected $validationRules = array();

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
    );
    protected $listedFields = array(
        "externID" => "externID",
        "externName" => "externNama",
        "relName" => "relName",


    );
    protected $staticData = array();

    function __construct()
    {
//        $rekChildsConfig = $this->config->item("accountChildSources") != null ? $this->config->item("accountChildSources") : array();
//        $cCode = $cCode = "_TR_" . $this->uri->segment(4);
//        $key = isset($_SESSION[$cCode]["main"]["pihakID"]) ? $_SESSION[$cCode]["main"]["pihakID"] : "0";
//
//        if (sizeof($rekChildsConfig) > 0) {
//            if (array_key_exists($key, $rekChildsConfig)) {
//                $mdlName = $rekChildsConfig[$key];
//                $this->load->model("Mdls/" . $mdlName);
//                $m = New $mdlName();
//
//                $result = $m->lookupAll()->result();
//                if (sizeof($result) > 0) {
//                    foreach ($result as $row) {
//
//                        $this->staticData[$row->id] = array(
//                            "id" => $row->id,
//                            "name" => $row->nama,
//                        );
//                    }
//                }
//            }
//        }
                        $this->staticData[-1] = array(
                            "id" => "kredit",
                            "name" => "kredit",
                        );
    }

    function init()
    {
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
    }

    //region gs
    public function addFilter($f)
    {
        $this->filters[] = $f;
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


}