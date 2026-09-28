<?php


class MdlRekeningTransaksiCache extends MdlMother
{
    protected $tableName = "z_rekening_transaksi_cache";
    protected $indexFields = "id";
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();
    protected $validationRules = array();
    protected $jenisTr;
    protected $relTargetJenis;

    public function getRelTargetJenis()
    {
        return $this->relTargetJenis;
    }

    public function setRelTargetJenis($relTargetJenis)
    {
        $this->relTargetJenis = $relTargetJenis;
    }

    protected $listedFieldsView = array();

    protected $fields = array();
    protected $listedFields = array();

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
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

    public function lookUpOutstanding($sessionRepacer = false)
    {
        if (isset($this->jenisTr)) {
            $this->db->where("master_jenis", $this->jenisTr);
        }

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
        // arrPrint($this->filters);
        // $jmlData=$this->lookupHistoryCount();

        $this->db->where("(kredit > '0' OR qty_kredit >'0')");
        $this->db->select("*");
        // $this->db->group_by("rekening");
        $this->db->order_by("id", "asc");
        // $this->db->from($this->tableNames['main']);
        $tmp = $this->db->get($this->tableName);
        if (sizeof($tmp) > 0) {
            return $tmp;
        }
        else {
            return array();
        }
        // matiHEre(__LINE__." function ".__FUNCTION__);


    }

    public function lookUpRelTarget()
    {
        if (isset($this->jenisTr)) {
            $this->db->where("master_jenis", $this->jenisTr);
        }
        if (isset($this->relTargetJenis)) {
            if(is_array($this->relTargetJenis)){
                $this->db->where_in("rel_target_jenis", $this->relTargetJenis);
            }
            else{

                $this->db->where("rel_target_jenis", $this->relTargetJenis);
            }
        }
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
        // arrPrint($this->filters);
        // $jmlData=$this->lookupHistoryCount();


        $this->db->select("*");
        // $this->db->group_by("rekening");
        $this->db->order_by("id", "asc");
        // $this->db->from($this->tableNames['main']);
        $tmp = $this->db->get($this->tableName);
        if (sizeof($tmp) > 0) {
            return $tmp;
        }
        else {
            return array();
        }
        // matiHEre(__LINE__." function ".__FUNCTION__);


    }

}