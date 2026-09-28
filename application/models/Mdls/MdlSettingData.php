<?php

////--include_once "MdlHistoriData.php";

class MdlSettingData extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $indexFieldName;
    protected $fieldContents = array();
    protected $historyEnabled;
    protected $validationRules = array();
    protected $tableName__tmp;
    protected $tableName__history;
    protected $unlistedFields = array();
    protected $listedFields = array();
    protected $relations = array("MdlOutlet"); //===isi array berupa data model
    protected $selfRelation = false;
    protected $selfCategorySpec = array();
    protected $filters = array();
    protected $child;
    protected $sortby;
    protected $customLink = array();
    protected $listedFieldsView = array();
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;


    //  region setter, getter

    function __construct()
    {
        parent::__construct();
        $this->tableName = "per_employee";
        $this->indexFields = "id";
        $this->fields = array(
            "id" => array(
                "label" => "id",
                "type" => "int", "length" => "24", "kolom" => "id",
                "inputType" => "hidden",// hidden
                //--"inputName" => "id",
            ),
            "nama" => array(
                "label" => "nama",
                "type" => "int", "length" => "24", "kolom" => "nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            "nama login" => array(
                "label" => "nama login",
                "type" => "int", "length" => "24", "kolom" => "nama_login",
                "inputType" => "text",
                //--"inputName" => "nama_login",
            ),
            "email" => array(
                "label" => "email",
                "type" => "int", "length" => "24", "kolom" => "email",
                "inputType" => "text",
                //--"inputName" => "email",
            ),
            "telp" => array(
                "label" => "telp",
                "type" => "int", "length" => "24", "kolom" => "tlp_1",
                "inputType" => "text",
                //--"inputName" => "telp",
            ),
            "trash" => array(
                "label" => "trash",
                "type" => "int", "length" => "24", "kolom" => "trash",
                "inputType" => "int",
                //--"inputName" => "trash",
            ),
            "status" => array(
                "label" => "status",
                "type" => "int", "length" => "24", "kolom" => "status",
                "inputType" => "button",
                //--"inputName" => "status",
            ),
            "password" => array(
                "label" => "password",
                "type" => "int", "length" => "24", "kolom" => "password",
                "inputType" => "password",
                //--"inputName" => "password",
            ),
            "cabang" => array(
                "label" => "status kantor",
                "type" => "int", "length" => "24", "kolom" => "cabang_id",
                "inputType" => "combo",
                //--"inputName" => "cabang_id",
            ),
            "jabatan" => array(
                "label" => "jabatan",
                "type" => "int", "length" => "24", "kolom" => "jenis",
                "inputType" => "combo",
                //--"inputName" => "jenis",
            ),
            "hak akses" => array(
                "label" => "akses",
                "type" => "int", "length" => "24", "kolom" => "jenis",
                "inputType" => "button",
                //--"inputName" => "jenis",
            ),
            "landing" => array(
                "label" => "landing",
                "type" => "int", "length" => "24", "kolom" => "jenis",
                "inputType" => "button",
                //--"inputName" => "jenis",
            ),
            "reset" => array(
                "label" => "reset",
                "type" => "int", "length" => "24", "kolom" => "jenis",
                "inputType" => "button",
                //--"inputName" => "jenis",
            ),
        );
        $this->listedFieldsView = array(
            "nama",
            "email",
            "telp",
            "cabang",
            "jabatan",
            "hak akses",
            "landing",
            "reset",
        );
        $this->listedFieldsForm = array(
            "nama",
            "nama depan",
            "nama belakang",
            "nama login",
            "password",
            "email",
            "telp",
            "cabang",
            "jabatan",
            "status",
        );
        $this->validationRules = array(
            "nama" => array("required", "singleOnly"),
            "tlp_1" => array("required", "numberOnly"),
            "status" => array("required"),
        );
        $this->listedFieldsHidden = array("id");

        $this->session->unset_userdata('search');
        if (null != $this->input->post('search')) {
            $this->search = $this->input->post('search');
            $this->session->set_userdata('search', $this->search);
        }
        else {
            $this->search = $this->session->search;
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    /**
     * @param array $listedFieldsView
     */
    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    /**
     * @return array
     */
    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    /**
     * @param array $listedFieldsForm
     */
    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    /**
     * @return mixed
     */
    public function getIndexFields()
    {
        return $this->indexFields;
    }

    /**
     * @param mixed $indexFields
     */
    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /**
     * @param array $validationRules
     */
    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * @return array
     */
    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }
    //    protected $filters = array();

    /**
     * @param array $listedFieldsHidden
     */
    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    /**
     * @return mixed
     */
    public function getIndexFieldName()
    {
        return $this->indexFieldName;
    }

    /**
     * @param mixed $indexFieldName
     */
    public function setIndexFieldName($indexFieldName)
    {
        $this->indexFieldName = $indexFieldName;
    }

    /**
     * @return array
     */
    public function getFieldContents()
    {
        return $this->fieldContents;
    }

    /**
     * @param array $fieldContents
     */
    public function setFieldContents($fieldContents)
    {
        $this->fieldContents = $fieldContents;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return mixed
     */
    public function getSortby()
    {
        return $this->sortby;
    }


    //  endregion setter, getter

    /**
     * @param mixed $sortby
     */
    public function setSortby($sortby)
    {
        $this->sortby = $sortby;
    }

    function lookupTotalActive()
    {
        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->select('id');
        $arrWhere = array(
            "trash" => 0,
            "jenis !=" => "supplier",
        );
        $this->db->where($arrWhere);
        $q = $this->db->get($this->tableName)->num_rows();

        return $q;
    }

    function lookupLimitedActive($position, $batas)
    {
        $list = array();
        foreach ($this->listedFieldsView as $kolom) {
            $list[] = $this->fields[$kolom]['kolom'];
        }

        $arrWhere = array(
            "trash" => 0,
            "jenis !=" => 'supplier',
        );
        $this->db->where($arrWhere);
        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->order_by('nama', 'asc');
        $this->db->limit($batas, $position);
        $q = $this->db->get($this->tableName);

        return $q;
    }

}