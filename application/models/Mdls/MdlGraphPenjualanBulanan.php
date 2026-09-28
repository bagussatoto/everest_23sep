<?php

//--include_once "MdlHistoriData.php";

class MdlGraphPenjualanBulanan extends MdlMother
{
    protected $tableName = "rugilaba";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array();

    protected $listedFieldsView = array("nama", "tlp_1", "npwp");
    protected $fields = array(
//        "id"           => array(
//            "label"     => "id",
//            "type"      => "int", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
//            //--"inputName" => "id",
//        ),
//        "name"         => array(
//            "label"     => "name",
//            "type"      => "int", "length" => "24", "kolom" => "nama",
//            "inputType" => "text",
//            //--"inputName" => "nama",
//        ),
//        "first_name"   => array(
//            "label"     => "first name",
//            "type"      => "int", "length" => "24", "kolom" => "nama_depan",
//            "inputType" => "text",
//            //--"inputName" => "nama_depan",
//        ),
//        "last_name"    => array(
//            "label"     => "last name",
//            "type"      => "int", "length" => "24", "kolom" => "nama_belakang",
//            "inputType" => "text",
//            //--"inputName" => "nama_belakang",
//        ),
//        //        "login_name" => array(
//        //            "label" => "login ID",
//        //            "type" => "int", "length" => "24", "kolom" => "nama_login",
//        //            "inputType" => "text",
//        //            //--"inputName" => "nama_login",
//        //        ),
//        "email"        => array(
//            "label"     => "email",
//            "type"      => "int", "length" => "24", "kolom" => "email",
//            "inputType" => "text",
//            //--"inputName" => "email",
//        ),
//        "telp"         => array(
//            "label"     => "phone",
//            "type"      => "int", "length" => "24", "kolom" => "tlp_1",
//            "inputType" => "text",
//            //--"inputName" => "telp",
//        ),
//        "alamat"       => array(
//            "label"     => "address",
//            "type"      => "int", "length" => "24", "kolom" => "alamat_1",
//            "inputType" => "text",
//            //--"inputName" => "alamat",
//        ),
//        "kabupaten"    => array(
//            "label"     => "district",
//            "type"      => "int", "length" => "24", "kolom" => "kabupaten",
//            "inputType" => "text",
//            //--"inputName" => "alamat",
//        ),
//        "propinsi"     => array(
//            "label"     => "province",
//            "type"      => "int", "length" => "24", "kolom" => "propinsi",
//            "inputType" => "text",
//            //--"inputName" => "alamat",
//        ),
//        "nik"          => array(
//            "label"     => "nik",
//            "type"      => "int", "length" => "24", "kolom" => "no_ktp",
//            "inputType" => "text",
//            //--"inputName" => "nik",
//        ),
//        "npwp"         => array(
//            "label"     => "tax-ID",
//            "type"      => "int", "length" => "24", "kolom" => "npwp",
//            "inputType" => "text",
//            //--"inputName" => "npwp",
//        ),
//        //        "due time" => array(
//        //            "label" => "due (in seconds)",
//        //            "type" => "int", "length" => "24", "kolom" => "jatuh_tempo",
//        //            "inputType" => "text",
//        //            //--"inputName" => "jatuh_tempo",
//        //        ),
//        "jatuh tempo"  => array(
//            "label"     => "due (in days)",
//            "type"      => "int", "length" => "24", "kolom" => "due_days",
//            "inputType" => "text",
//            //--"inputName" => "jatuh_tempo",
//        ),
//        "credit_limit" => array(
//            "label"     => "credit limit",
//            "type"      => "int", "length" => "24", "kolom" => "kredit_limit",
//            "inputType" => "text",
//            //--"inputName" => "kredit_limit",
//        ),
//        //        "trash" => array(
//        //            "label" => "trash",
//        //            "type" =>"int","length"=>"24","kolom" => "trash",
//        //            "inputType" => "int",
//        //            //--"inputName" => "trash",
//        //        ),
//        "status"       => array(
//            "label"      => "status",
//            "type"       => "int", "length" => "24", "kolom" => "status",
//            "inputType"  => "combo",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
//            //--"inputName" => "status",
//        ),
//        //        "ppn" => array(
//        //            "label" => "VAT factor (%)",
//        //            "type" =>"int","length"=>"24","kolom" => "ppn",
//        //            "inputType" => "number",
//        //            //--"inputName" => "ppn",
//        //        ),
//        "diskon"       => array(
//            "label"     => "discount (%)",
//            "type"      => "int", "length" => "24", "kolom" => "diskon",
//            "inputType" => "number",
//            //--"inputName" => "diskon",
//        ),
//        "attn"         => array(
//            "label"     => "CP",
//            "type"      => "int", "length" => "24", "kolom" => "contact_person",
//            "inputType" => "text",
//            //--"inputName" => "person_nama",
//        ),

    );
    protected $listedFields = array(
//        "nama"  => "name",
//        //        "alamat_1"=>"address",
//        "email" => "email",
//        "tlp_1" => "phone",
//        "npwp"  => "tax-ID",

    );

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


    //=============
    public function fetchDates()
    {

        $this->db->group_by("fulldate");
        $this->db->order_by("fulldate", "desc");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $tmp = $this->db->get($this->tableName)->result();
        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $results[$row->fulldate] = $row->fulldate;
            }
        }
        return $results;
    }

    public function fetchBalances($date = "")
    {

        $this->db->order_by("rek_id", "desc");

        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                }
                else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                        //$criteriaNot[$tmp[0]] = trim($tmp[1], "'");
                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }
        //$criteria['parent_id'] = 0;
        $this->db->where($criteria);
//        if (strlen($date) > 8) {
//            $this->db->where(array("fulldate<=" => "$date"));
//        }
        if (strlen($date) == 10) {
            $this->db->where(array("fulldate<=" => "$date"));
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);
            $this->db->where(
                array(
                    "bln=" => $date_ex[1],
                    "thn=" => $date_ex[0],
                )
            );
        }
        else {
            $this->db->where(
                array(
                    "thn=" => $date,
                )
            );
        }

        //$this->db->limit(100);
        $result = array();
        $lastID = "";
        $tmp = $this->db->get($this->tableName)->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if ($row->rekening != $lastID) {
                    $result[] = $row;
                }
                $lastID = $row->rekening;
            }
        }
        return $result;
    }

    public function fetchBalances2($date = "")
    {

        $this->db->order_by("rek_id", "ASC");
        $this->db->order_by("cabang_id", "ASC");
        $this->db->order_by("id", "ASC");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        if (strlen($date) == 10) {
            $this->db->where(array("fulldate<=" => "$date"));
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);
            $this->db->where(
                array(
                    "bln=" => $date_ex[1],
                    "thn=" => $date_ex[0],
                )
            );
        }
        else {
            $this->db->where(
                array(
                    "thn=" => $date,
                )
            );
        }
        $result = array();
        $lastID = "";
        $tmpCabang = array();
        $tmpLastID = array();
        $tmpLastResult = array();
        $tmp = $this->db->get($this->tableName)->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $lastID = $row->transaksi_id;
                $cabID = $row->cabang_id;

                if (!isset($tmpResult[$cabID][$lastID])) {
                    $tmpResult[$cabID][$lastID] = array();
                }
                $tmpResult[$cabID][$lastID][] = $row;

                $tmpCabang[$cabID] = $cabID;
                $tmpLastID[$cabID] = $lastID;


            }
            foreach ($tmpCabang as $cabID) {
                $lastID = $tmpLastID[$cabID];
                $tmpLastResult[$cabID][$lastID] = $tmpResult[$cabID][$lastID];
            }
        }
        else {
            $tmpLastResult = array();
        }


        return $tmpLastResult;
    }

    public function fetchBalances2($date = "")
    {

        $this->db->order_by("rek_id", "ASC");
        $this->db->order_by("cabang_id", "ASC");
        $this->db->order_by("id", "ASC");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        if (strlen($date) == 10) {
            $this->db->where(array("fulldate<=" => "$date"));
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);
            $this->db->where(
                array(
                    "bln=" => $date_ex[1],
                    "thn=" => $date_ex[0],
                )
            );
        }
        else {
            $this->db->where(
                array(
                    "thn=" => $date,
                )
            );
        }
        $result = array();
        $lastID = "";
        $tmpCabang = array();
        $tmpLastID = array();
        $tmpLastResult = array();
        $tmp = $this->db->get($this->tableName)->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $lastID = $row->transaksi_id;
                $cabID = $row->cabang_id;

                if (!isset($tmpResult[$cabID][$lastID])) {
                    $tmpResult[$cabID][$lastID] = array();
                }
                $tmpResult[$cabID][$lastID][] = $row;

                $tmpCabang[$cabID] = $cabID;
                $tmpLastID[$cabID] = $lastID;


            }
            foreach ($tmpCabang as $cabID) {
                $lastID = $tmpLastID[$cabID];
                $tmpLastResult[$cabID][$lastID] = $tmpResult[$cabID][$lastID];
            }
        }
        else {
            $tmpLastResult = array();
        }


        return $tmpLastResult;
    }
}