<?php

class MdlHistoriData extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $validationRules = array();
    protected $listedFieldsView = array();
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;


    //  region setter, getter

    function __construct()
    {
        parent::__construct();
        $this->tableName = "data_history";
        $this->indexFields = "id";
        $this->fields = array(
            "mdl_nama",
            "mdl_label",
            "old_content",
            "new_content",
            "oleh_id",
            "oleh_nama",
            "label",
            "dtime",
            "orig_id",
        );
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

    //  endregion setter, getter

    /**
     * @param array $listedFieldsHidden
     */
    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    function lookupTotalActive()
    {
        $this->db->select('id');
        $controler = $this->uri->segment(1);
        $arrWhere = array(
            "mdl_label" => $controler,
            "mdl_name"  => 'Mdl' . $controler,
            "orig_id"   => $this->uri->segment(3),
        );
        $this->db->where($arrWhere);
        $q = $this->db->get($this->tableName)->num_rows();

        return $q;
    }

    function lookupLimitedActive($position = '', $batas = '')
    {
        $controler = $this->uri->segment(1);
        $arrWhere = array(
            "mdl_label" => $controler,
            "mdl_name"  => 'Mdl' . $controler,
            "orig_id"   => $this->uri->segment(3),
        );
        $this->db->where($arrWhere);
        $this->db->limit($batas, $position);
        $this->db->order_by('id', 'desc');
        $q = $this->db->get($this->tableName);
//echo $this->db->last_query() . "<br><br>";

        return $q;
    }


    function addHistoryData($arrData)
    {

        $this->addData($arrData);
    }
}