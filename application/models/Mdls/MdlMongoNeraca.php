<?php


class MdlMongoNeraca extends MdlMongoMother
{
    protected $tableName = "neraca";

    protected $dbName;
    protected $client;
    protected $search;
    protected $filters = array(
        "status" => "1",
        "trash" => "0",
    );


    public function __construct()
    {
        parent::__construct();

//        require 'vendor/autoload.php';
//        $this->client = new MongoDB\Client("mongodb://" . $this->config->item('heMongo')['server'], ['username' => $this->config->item('heMongo')['username'], 'password' => $this->config->item('heMongo')['password']]);
//        $this->dbName=$this->config->item('heMongo')['database'];
    }


    //region getter dan setter

    public function getTableName()
    {
        return $this->tableName;
    }


    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }


    public function getDbName()
    {
        return $this->dbName;
    }


    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }


    public function getClient()
    {
        return $this->client;
    }


    public function setClient($client)
    {
        $this->client = $client;
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
    //endregion


    //=============
    public function fetchDates()
    {
//
//        $this->db->order_by("id", "asc");
//
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
                $results[$row->thn - $row->bln] = "$row->thn-$row->bln";
            }
        }
        return $results;
    }

    public function fetchBalances($date = "")
    {

        if (strlen($date) == 10) {
            $this->filters['fulldate='] = $date;
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);

            $this->filters['bln'] = dateDigitReverse($date_ex[1]);
            $this->filters['thn'] = $date_ex[0];
        }
        else {
            $this->filters['thn'] = $date;

        }

//        arrPrintWebs($this->filters);

        $tmpResult = array();
        $result = array();
        $lastID = "";
        $lastRek = "";
        $tmp = $this->mongo_db->get_where($this->tableName, $this->filters);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $rows) {
                $row = (object)$rows;
                $lastID = $row->transaksi_id;

                if (!isset($tmpResult[$lastID])) {
                    $tmpResult[$lastID] = array();
                }


                $result[] = $row;
                $tmpResult[$lastID][] = $row;
            }
        }

        if (isset($tmpResult[$lastID])) {
            return $tmpResult[$lastID];
        }
        else {
            return array();
        }

    }

    public function fetchBalances2($date = "")
    {
//        $this->db->order_by("cabang_id", "asc");
//        $this->db->order_by("id", "asc");
//
//        $criteria = array();
//        $tmpResult = array();
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
//
//
//        $this->db->where($criteria);

        if (strlen($date) == 10) {
            $this->filters['fulldate='] = $date;
        }
        elseif (strlen($date) == 7) {
            $date_ex = explode("-", $date);

            $this->filters['bln'] = dateDigitReverse($date_ex[1]);
            $this->filters['thn'] = $date_ex[0];
        }
        else {
            $this->filters['thn'] = $date;

        }
//arrPrintWebs($this->filters);

        $result = array();
        $lastID = "";
        $lastRek = "";
        $cabID = "";
        $tmpCabang = array();
        $tmpLastID = array();
        $tmpLastResult = array();
//        $this->db->order_by("rek_id", "ASC");
//        $this->db->order_by("cabang_id", "ASC");
//        $this->db->order_by("id", "ASC");
        $tmp = $this->mongo_db->get_where($this->tableName, $this->filters);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $rows) {
                $row = (object)$rows;
                $lastID = $row->transaksi_id;
                $cabID = $row->cabang_id;

                if (!isset($tmpResult[$cabID][$lastID])) {
                    $tmpResult[$cabID][$lastID] = array();
                }


                $result[] = $row;
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

        if (isset($tmpLastResult)) {
            return $tmpLastResult;
        }
        else {
            return array();
        }

    }

}