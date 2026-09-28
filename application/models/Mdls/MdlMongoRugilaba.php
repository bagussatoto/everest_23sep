<?php


class MdlMongoRugilaba extends MdlMongoMother
{
    protected $tableName = "rugilaba";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status" => "1",
        "trash" => "0",
    );

    protected $validationRules = array();

    protected $listedFieldsView = array();
    protected $fields = array();
    protected $listedFields = array();

    //region getter dan setter
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


    //=============
    public function fetchDates()
    {

        //$this->db->where(array("jenis" => $tCode));
//
//
//        $this->db->group_by("fulldate");
//        $this->db->order_by("fulldate", "desc");
//
//        $criteria = array();
//        $criteria2 = "";
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

        $tmp = $this->mongo_db->get_where($this->tableName, $this->filters);
        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $rows) {
                $row = (object)$rows;
                $results[$row->fulldate] = $row->fulldate;
            }
        }
        return $results;
    }

    public function fetchBalances($date = "")
    {

        if (strlen($date) == 10) {
            $this->filters['fulldate'] = $date;
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);

            $this->filters['bln'] = $date_ex[1];
            $this->filters['thn'] = $date_ex[0];

        }
        else {
            $this->filters['thn'] = $date;
        }

        $result = array();
        $lastID = "";
        $tmp = $this->mongo_db->get_where($this->tableName, $this->filters);

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $rows) {
                $row = (object)$rows;
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

        if (strlen($date) == 10) {
            $this->filters['fulldate'] = $date;
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);

            $this->filters['bln'] = $date_ex[1];
            $this->filters['thn'] = $date_ex[0];

        }
        else {
            $this->filters['thn'] = $date;
        }

        $result = array();
        $lastID = "";
        $tmpCabang = array();
        $tmpLastID = array();
        $tmpLastResult = array();
        $tmp = $this->mongo_db->get_where($this->tableName, $this->filters);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $rows) {
                $row = (object)$rows;
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