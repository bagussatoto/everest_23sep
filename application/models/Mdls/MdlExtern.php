<?php

class MdlExtern extends MdlMother_static
{
    protected $tableName = "extern";
    protected $indexFields = "id";
    protected $tableNames = array();

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();

    protected $validationRules = array(
//        "nama"  => array("required", "singleOnly"),
//        "kode"  => array("required"),
//        "swift" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "externID" => array(
            "label" => "extern_id",
            "type" => "int", "length" => "24", "kolom" => "extern_id",
            "inputType" => "combo",
//            "reference" => "MdlBank",
            //--"inputName" => "folders",
        ),
        "externNama" => array(
            "label" => "extern_nama",
            "type" => "text", "length" => "24", "kolom" => "extern_nama",
            "inputType" => "combo",
//            "reference" => "MdlBank",
            //--"inputName" => "folders",
        ),
        "cabangID" => array(
            "label" => "branch id",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "referenceFilter" => "",
        ),
        "cabangNama" => array(
            "label" => "branch nama",
            "type" => "text", "length" => "24", "kolom" => "cabang_nama",
            "inputType" => "combo",
            "referenceFilter" => "",
        ),
        "relName" => array(
            "label" => "relasi nama",
            "type" => "int", "length" => "24", "kolom" => "rel_nama",
            "inputType" => "combo",
            "referenceFilter" => "",
        ),

    );
    protected $listedFields = array(
        "externID" => "externID",
        "externName" => "externNama",
        "relName" => "relName",


    );


    function __construct()
    {
//        arrprint($this->filters);


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

                    if (file_exists(APPPATH . "models/Coms/$mdlName.php")) {
                        $this->load->model("Coms/$mdlName");
                        $o = New $mdlName();
                        $this->tableName = $o->getTableName();
                    }


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

//    public function lookupByKeyword($key)
//    {
//        $criteria = array();
//        if (sizeof($this->filters) > 0) {
//            $this->fetchCriteria();
//            $criteria = $this->getCriteria();
//        }
//
//        $colCtr = 0;
//        $this->db->where($criteria);
//        $this->db->group_start();
//
//        foreach ($this->listedFieldsSelectItem as $fieldName) {
//            $colCtr++;
//            if ($colCtr == 1) {
//                $this->db->like($this->tableName.".".$fieldName, $key);
//            } else {
//                $this->db->or_like($this->tableName.".".$fieldName, $key);
//            }
//        }
//        $this->db->group_end();
//        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
//        $result = $this->db->get($this->tableName);
//
//
//        return $result;
//    }


    public function lookupAll()
    {

//        arrprint($this->filters);


//        arrprint($this->filters);

        $this->init();

//        cekmerah("lookup start");
//        cekbiru($this->tableName);
//        arrprint($this->filters);
        $criteria = array();
        $criteria2 = "";
//        if (sizeof($this->filters) > 0) {
//            $this->fetchCriteria();
//            $criteria = $this->getCriteria();
//            $criteria2 = $this->getCriteria2();
//        }
//        if (sizeof($criteria) > 0) {
//            $this->db->where($criteria);
//        }
//        if ($criteria2 != "") {
//            $this->db->where($criteria2);
//        }


//        cekmerah("lookup end");
//        cekbiru($this->tableName);
//        arrprint($this->filters);

        $this->db->group_by("extern_id");

        $rslt = $this->db->get($this->tableName);
//        cekmerah($this->db->last_query());


        return $rslt;

    }

}