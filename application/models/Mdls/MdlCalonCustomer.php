<?php

//--include_once "MdlHistoriData.php";
class MdlCalonCustomer extends MdlMother
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
        $this->tableName = "per_customers";
        $this->indexFields = "id";
        $this->fields = array(
            "id"            => array(
                "label"     => "id",
                "type"      => "int", "length" => "24", "kolom" => "id",
                "inputType" => "hidden",// hidden
                //--"inputName" => "id",
            ),
            "nama"          => array(
                "label"     => "nama",
                "type"      => "int", "length" => "24", "kolom" => "nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            "nama depan"    => array(
                "label"     => "nama depan",
                "type"      => "int", "length" => "24", "kolom" => "nama_depan",
                "inputType" => "text",
                //--"inputName" => "nama_depan",
            ),
            "nama belakang" => array(
                "label"     => "nama belakang",
                "type"      => "int", "length" => "24", "kolom" => "nama_belakang",
                "inputType" => "text",
                //--"inputName" => "nama_belakang",
            ),
            "nama login"    => array(
                "label"     => "nama login",
                "type"      => "int", "length" => "24", "kolom" => "nama_login",
                "inputType" => "text",
                //--"inputName" => "nama_login",
            ),
            "email"         => array(
                "label"     => "email",
                "type"      => "int", "length" => "24", "kolom" => "email",
                "inputType" => "text",
                //--"inputName" => "email",
            ),
            "telp"          => array(
                "label"     => "telp",
                "type"      => "int", "length" => "24", "kolom" => "tlp_1",
                "inputType" => "text",
                //--"inputName" => "telp",
            ),
            "alamat"        => array(
                "label"     => "alamat",
                "type"      => "int", "length" => "24", "kolom" => "alamat_1",
                "inputType" => "text",
                //--"inputName" => "alamat",
            ),
            "nik"           => array(
                "label"     => "nik",
                "type"      => "int", "length" => "24", "kolom" => "no_ktp",
                "inputType" => "text",
                //--"inputName" => "nik",
            ),
            "npwp"          => array(
                "label"     => "tax-ID",
                "type"      => "int", "length" => "24", "kolom" => "npwp",
                "inputType" => "text",
                //--"inputName" => "npwp",
            ),
            "jatuh tempo"   => array(
                "label"     => "jatuh tempo",
                "type"      => "int", "length" => "24", "kolom" => "jatuh_tempo",
                "inputType" => "text",
                //--"inputName" => "jatuh_tempo",
            ),
            "credit_limit"  => array(
                "label"     => "credit limit",
                "type"      => "int", "length" => "24", "kolom" => "kredit_limit",
                "inputType" => "text",
                //--"inputName" => "kredit_limit",
            ),
            "trash"         => array(
                "label"     => "trash",
                "type"      => "int", "length" => "24", "kolom" => "trash",
                "inputType" => "int",
                //--"inputName" => "trash",
            ),
            "status"        => array(
                "label"      => "status",
                "type"       => "int", "length" => "24", "kolom" => "status",
                "inputType"  => "combo",
                "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
                //--"inputName" => "status",
            ),
            "jenis"         => array(
                "label"     => "jenis",
                "type"      => "int", "length" => "24", "kolom" => "jenis",
                "inputType" => "text",
                //--"inputName" => "jenis",
            ),
            "dtime"         => array(
                "label"     => "dtime",
                "type"      => "int", "length" => "24", "kolom" => "dtime",
                "inputType" => "text",
                //--"inputName" => "",
            ),
            //            "oleh name" => array(
            //                "label" => "pic",
            //                "type" =>"int","length"=>"24","kolom" => "oleh_name",
            //                "inputType" => "text",
            //                //--"inputName" => "",
            //            ),
            //            "keterangan" => array(
            //                "label" => "keterangan",
            //                "type" =>"int","length"=>"24","kolom" => "label",
            //                "inputType" => "text",
            //                //--"inputName" => "",
            //            ),
        );
        $this->listedFieldsView = array("nama", "email", "telp", "alamat", "nik", "npwp", "jatuh tempo");
        $this->listedFieldsForm = array("nama", "nama depan", "nama belakang", "nama login", "email", "telp", "alamat", "nik", "npwp", "kredit limit", "jatuh tempo", "status");
        $this->validationRules = array(
            "nama"   => array("required", "singleOnly"),
            "tlp_1"  => array("required", "numberOnly"),
            "no_ktp" => array("required", "numberOnly"),
            "npwp"   => array("required"),
            "status" => array("required"),
        );
        $this->listedFieldsHidden = array("id", "jenis");


        $this->session->unset_userdata('search');
        if (null != $this->input->post('search')) {
            $this->search = $this->input->post('search');
            $this->session->set_userdata('search', $this->search);
        } else {
            $this->search = $this->session->search;
        }

        if (in_array('active', $this->uri->segment_array())) {
            $this->filters = array(
                "status='0'",
                "trash='0'",
            );
        } elseif (in_array('non_active', $this->uri->segment_array())) {
            $this->filters = array(
                "status='0'",
                "trash='1'",
            );
        } else {
            $this->filters = array(
                "status='0'",
            );
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

//    function lookupTotalActive()
//    {
//        strlen($this->search)>3 ? $this->db->like('nama', $this->search) : "";
//        $this->db->select('id');
//        $arrWhere = array(
//            "trash" => 0,
//        );
//        $this->db->where($arrWhere);
//        $q = $this->db->get($this->tableName)->num_rows();
//
//        return $q;
//    }
//    function lookupTotalNonActive()
//    {
//        strlen($this->search)>3 ? $this->db->like('nama', $this->search) : "";
//        $this->db->select('id');
//        $arrWhere = array(
//            "trash" => 1,
//        );
//        $this->db->where($arrWhere);
//        $q = $this->db->get($this->tableName)->num_rows();
//
//        return $q;
//    }
//    function lookupTotalAll()
//    {
//        strlen($this->search)>3 ? $this->db->like('nama', $this->search) : "";
//        $this->db->select('id');
//        $q = $this->db->get($this->tableName)->num_rows();
//
//        return $q;
//    }

//    function lookupLimitedActive($position, $batas)
//    {
//        $list = array();
//        foreach ($this->listedFieldsView as $kolom) {
//            $list[] = $this->fields[$kolom]['kolom'];
//        }
//
////        $this->db->select($list);
//        $arrWhere = array(
//            "trash" => 0,
//        );
//        $this->db->where($arrWhere);
//        strlen($this->search)>3 ? $this->db->like('nama', $this->search) : "";
//        $this->db->order_by('nama', 'asc');
//        $this->db->limit($batas, $position);
//        $q = $this->db->get($this->tableName);
//
//        return $q;
//    }
//    function lookupLimitedNonActive($position, $batas)
//    {
//        $list = array();
//        foreach ($this->listedFieldsView as $kolom) {
//            $list[] = $this->fields[$kolom]['kolom'];
//        }
//
////        $this->db->select($list);
//        $arrWhere = array(
//            "trash" => 1,
//        );
//        $this->db->where($arrWhere);
//        strlen($this->search)>3 ? $this->db->like('nama', $this->search) : "";
//        $this->db->order_by('nama', 'asc');
//        $this->db->limit($batas, $position);
//        $q = $this->db->get($this->tableName);
//
//        return $q;
//    }
//    function lookupLimitedAll($position, $batas)
//    {
//        $list = array();
//        foreach ($this->listedFieldsView as $kolom) {
//            $list[] = $this->fields[$kolom]['kolom'];
//        }
//
////        $this->db->select($list);
//        strlen($this->search)>3 ? $this->db->like('nama', $this->search) : "";
//        $this->db->order_by('nama', 'asc');
//        $this->db->limit($batas, $position);
//        $q = $this->db->get($this->tableName);
//
//        return $q;
//    }
//
//    function lookupTotalHistoryAll(){
//        $h = New MdlHistoriData();
//        $rows = $h->lookupTotalActive();
//
//        return $rows;
//    }
//    function lookupHistory($position, $batas){
//
//        $h = New MdlHistoriData();
//        $result = $h->lookupLimitedActive($position, $batas)->result();
//
//
//        return $result;
//    }

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