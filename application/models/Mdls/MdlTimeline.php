<?php

//--include_once "MdlHistoriData.php";

class MdlTimeline extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $validationRules = array();
    protected $listedFieldsView = array();
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters;


    //  region setter, getter

    function __construct()
    {
        parent::__construct();
        $this->tableName = "aktifitas";
        $this->indexFields = "id";
        $this->fields = array(
            "id"             => array(
                "label"     => "id",
                "type"      => "int", "length" => "24", "kolom" => "id",
                "inputType" => "hidden",// hidden
                //--"inputName" => "id",
            ),
            "deskripsi"      => array(
                "label"     => "deskripsi",
                "type"      => "int", "length" => "24", "kolom" => "deskripsi",
                "inputType" => "text",
                //--"inputName" => "deskripsi",
            ),
            "customers id"   => array(
                "label"     => "customers id",
                "type"      => "int", "length" => "24", "kolom" => "customers_id",
                "inputType" => "int",
                //--"inputName" => "customers_id",
            ),
            "customers nama" => array(
                "label"     => "customers nama",
                "type"      => "int", "length" => "24", "kolom" => "customers_nama",
                "inputType" => "text",
                //--"inputName" => "customers_nama",
            ),
            "employee id"    => array(
                "label"     => "employee id",
                "type"      => "int", "length" => "24", "kolom" => "employee_id",
                "inputType" => "int",
                //--"inputName" => "employee_id",
            ),
            "employee nama"  => array(
                "label"     => "employee nama",
                "type"      => "int", "length" => "24", "kolom" => "employee_nama",
                "inputType" => "text",
                //--"inputName" => "employee_nama",
            ),
            "kategori"       => array(
                "label"     => "kategori",
                "type"      => "int", "length" => "24", "kolom" => "kategori",
                "inputType" => "text",
                //--"inputName" => "kategori",
            ),
            "latitude"       => array(
                "label"     => "latitude",
                "type"      => "int", "length" => "24", "kolom" => "latitude",
                "inputType" => "text",
                //--"inputName" => "latitude",
            ),
            "longitude"      => array(
                "label"     => "longitude",
                "type"      => "int", "length" => "24", "kolom" => "longitude",
                "inputType" => "text",
                //--"inputName" => "longitude",
            ),
            "ipadd"          => array(
                "label"     => "ipadd",
                "type"      => "int", "length" => "24", "kolom" => "ipadd",
                "inputType" => "text",
                //--"inputName" => "ipadd",
            ),
            "devices"        => array(
                "label"     => "devices",
                "type"      => "int", "length" => "24", "kolom" => "devices",
                "inputType" => "text",
                //--"inputName" => "devices",
            ),
            "jenis"          => array(
                "label"     => "devices",
                "type"      => "int", "length" => "24", "kolom" => "devices",
                "inputType" => "text",
                //--"inputName" => "devices",
            ),
        );
        $this->listedFieldsView = array("deskripsi", "customers nama", "employee nama", "kategori");
        $this->listedFieldsForm = array("deskripsi", "customers nama", "employee nama", "kategori");
        $this->validationRules = array(
            "customers nama" => array("required"),
            "deskripsi"      => array("required"),
        );
        $this->listedFieldsHidden = array("id", "latitude", "longitude", "devices", "ipadd", "jenis");

        $this->session->unset_userdata('search');
        if (null != $this->input->post('search')) {
            $this->search = $this->input->post('search');
            $this->session->set_userdata('search', $this->search);
        } else {
            $this->search = $this->session->search;
        }

        if (in_array('active', $this->uri->segment_array())) {
            $this->filters = array(
                "trash='0'",
            );
        } elseif (in_array('non_active', $this->uri->segment_array())) {
            $this->filters = array(
                "trash='1'",
            );
        } else {
            $this->filters = array();
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

    /**
     * @param array $listedFieldsHidden
     */
    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    //  endregion setter, getter

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    function lookupBasicNota($customersId)
    {
        $arraySelect = array("alamat_1", "tlp_1");
        $arrWhere = array(
            "id" => "$customersId",
        );

        $this->db->select($arraySelect);
        $this->db->where($arrWhere);
        $q = $this->db->get($this->tableName);
        echo $this->db->last_query();
        return $q;
    }

    public function lookupByKeyword($key)
    {

        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                } else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                        //$criteriaNot[$tmp[0]] = trim($tmp[1], "'");
                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }
        //return $this->db->get($this->tableName);
        //return $this->db->get_where($this->tableName, $criteria);
        $colCtr = 0;
        $this->db->where($criteria);

        $this->db->group_start();
        foreach ($this->fields as $fName => $fSpec) {
            $colCtr++;
            $kolomMentah = $fSpec['kolom'];
            $fieldName = isset($kolomMentah) ? $kolomMentah : $fSpec;
            // echo "fSpec: $fName, fieldname: $fieldName <br>";
            // $fieldName=$fSpec;
            // echo "checking $fieldName for similarity with $key <br>";
            if ($colCtr == 1) {
                $this->db->like($fieldName, $key);
            } else {
                $this->db->or_like($fieldName, $key);
            }
        }
        $this->db->group_end();
        // $this->db->get($this->tableName);
        // print_r($this->db->last_query());
        return $this->db->get($this->tableName);
    }
}