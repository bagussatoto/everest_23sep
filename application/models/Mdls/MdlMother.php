<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdlMother
 *
 * @author aziz
 */
class MdlMother extends CI_Model
{
    protected $filters = array();
    protected $criteria = array();
    protected $criteria2 = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "nama",
    );
    protected $compactListedFields = array(
        "nama" => "name",
    );
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "ASC",
    );
    protected $xorPairs = array();
    protected $limiteEditor = array();
    protected $unionPairs = array();
    protected $validateData = array();
    protected $autoFillFields = array();
    protected $aliasName = array();
    protected $joinedFilter = array();
    protected $toko_id;

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    protected $cabang_id;

    public function getCabangId()
    {
        return $this->cabang_id;
    }

    public function setCabangId($cabang_id)
    {
        $this->cabang_id = $cabang_id;
    }

    protected $maximumData;

    public function getMaximumData()
    {
        return $this->maximumData;
    }

    public function setMaximumData($maximumData)
    {
        $this->maximumData = $maximumData;
    }

    protected $btnActions;

    public function getBtnActions()
    {
        return $this->btnActions;
    }

    public function setBtnActions($btnActions)
    {
        $this->btnActions = $btnActions;
    }

    protected $btnActionAll;

    public function getBtnActionAll()
    {
        return $this->btnActionAll;
    }

    public function setBtnActionAll($btnActionAll)
    {
        $this->btnActionAll = $btnActionAll;
    }

    protected $staticData = array();

    protected $jointSelectFields;

    public function getJointSelectFields()
    {
        return $this->jointSelectFields;
    }

    public function setJointSelectFields($jointSelectFields)
    {
        //string setJointSelectFields("main,detail")
        $this->jointSelectFields = $jointSelectFields;
    }

    public function addFilterJoin($f)
    {
        $this->joinedFilter[] = $f;
    }

    public function getJoinedFilter()
    {
        return $this->joinedFilter;
    }

    public function setJoinedFilter($joinedFilter)
    {
        $this->joinedFilter = $joinedFilter;
    }

    public function getAliasName()
    {
        return $this->aliasName;
    }

    public function setAliasName($aliasName)
    {
        $this->aliasName = $aliasName;
    }

    public function getAutoFillFields()
    {
        return $this->autoFillFields;
    }

    public function setAutoFillFields($autoFillFields)
    {
        $this->autoFillFields = $autoFillFields;
    }

    //    private $conditional;
    public function getValidateData()
    {
        return $this->validateData;
    }

    public function setValidateData($validateData)
    {
        $this->validateData = $validateData;
    }

    public function getCompactListedFields()
    {
        return $this->compactListedFields;
    }

    public function setCompactListedFields($compactListedFields)
    {
        $this->compactListedFields = $compactListedFields;
    }

    //region gs
    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
    }

    public function getLimiteEditor()
    {
        return $this->limiteEditor;
    }

    public function setLimiteEditor($limiteEditor)
    {
        $this->limiteEditor = $limiteEditor;
    }

    public function getXorPairs()
    {
        return $this->xorPairs;
    }

    public function setXorPairs($xorPairs)
    {
        $this->xorPairs = $xorPairs;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function getStaticData()
    {
        return $this->staticData;
    }

    public function setStaticData($staticData)
    {
        $this->staticData = $staticData;
    }

    //endregion
    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function removeFilter($f)
    {
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $cnt => $fx) {
                if ($fx == $f) {
                    unset($this->filters[$cnt]);
                }
            }
        }
    }

    function init()
    {

    }

    public function lookupAll()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }

        // kalau ada table2 → union
        if (!empty($this->tableUnion)) {
            $columns = '*'; // ganti sesuai kebutuhan (lebih aman tulis nama kolom)

            // SELECT dari table1
            $this->db->select($columns);
            if (!empty($criteria)) $this->db->where($criteria);
            if ($criteria2 != "") $this->db->where($criteria2);
            $sql1 = $this->db->get_compiled_select($this->tableName);
            $this->db->reset_query();

            // SELECT dari table2
            $this->db->select($columns);
            if (!empty($criteria)) $this->db->where($criteria);
            if ($criteria2 != "") $this->db->where($criteria2);
            $sql2 = $this->db->get_compiled_select($this->tableUnion);
            $this->db->reset_query();

            // gabung UNION ALL
            $unionSql = "$sql1 UNION ALL $sql2";

            // order by global
            if (isset($this->sortBy) && !empty($this->sortBy)) {
                $unionSql .= " ORDER BY " . $this->sortBy['kolom'] . " " . $this->sortBy['mode'];
            }

            return $this->db->query($unionSql);
        }

        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
        }
        $res = $this->db->get($this->tableName);
        return $res;
    }

    public function lookupAllInactive()
    {
        $criteria = array();
        $criteria2 = array(
            "status" => 0,
            // "trash" => 0,
        );


        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
        }
        //arrPrint($criteria);
        $res = $this->db->get($this->tableName);
        //        cekkuning($this->db->last_query());
        return $res;

    }

    public function fetchCriteria()
    {

        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $this->criteria = array();
            $this->criteria2 = array();
            foreach ($this->filters as $f) {
                if (!is_string($f) || trim($f) === '') {
                    continue;
                }
                $fCnt++;
                $tmp = explode(" in ", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai klausa "IN"
                    $this->criteria2 = $tmp[0] . " IN " . trim($tmp[1], "'");
                }
                else {
                    $tmp = explode(" is ", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai klausa "IS"
                        $this->criteria2 = $tmp[0] . " IS " . trim($tmp[1], "'");
//                        cekHere($this->criteria2);
                    }
                    else {

                        $tmp = explode(" like ", $f);
                        if (sizeof($tmp) > 1) { //==berarti pakai like
                            $this->criteria2 = $tmp[0] . " like '" . trim($tmp[1], "'") . "'";
                        }
                        else {
                            $tmp = explode("=", $f);
                            if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                                $this->criteria[$tmp[0] . "="] = trim($tmp[1], "'");
                                //                                $this->criteria[$tmp[0]] = trim($tmp[1], "'");
                            }
                            else {
                                $tmp = explode("<>", $f);
                                if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                                    //$this->criteriaNot[$tmp[0]] = trim($tmp[1], "'");
                                    $this->criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                                }
                                else {

                                    $tmp = explode("<", $f);
                                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                                        $this->criteria[$tmp[0] . "<"] = trim($tmp[1], "'");

                                    }
                                    else {

                                        //asli
//                                        $tmp = explode(">", $f);
//                                        if (sizeof($tmp) > 1) {
//                                            //==berarti pakai tanda tidak sama dengan <>
//                                            $this->criteria[$tmp[0] . ">"] = trim($tmp[1], "'");
//                                        }
                                        //AI
                                        $tmp = explode(">", $f);
                                        if (sizeof($tmp) > 1) {
                                            $val = trim($tmp[1], "'");
                                            if (is_numeric($val)) {
                                                $val = (int)$val; // konversi ke integer
                                            }
                                            $this->criteria[$tmp[0] . ">"] = $val;
                                        }

                                        //                                    else {
                                        //                                        $tmp = explode(" is ", $f);
                                        //                                        if (sizeof($tmp) > 1) { //==berarti pakai klausa "IS"
                                        //                                            $this->criteria2 = $tmp[0] . " IS " . trim($tmp[1], "'");
                                        //                                        }
                                        //                                    }
                                        //                                arrPrint($this->criteria);
                                    }
                                }
                            }
                        }
                    }


                }


            }
        }
        //        arrPrint($this->criteria);
//                arrPrint($this->criteria2);
    }

    public function fetchCriteriaJoined()
    {
        if (sizeof($this->joinedFilter) > 0) {
            $fCnt = 0;
            $this->criteria = array();
            $this->criteria2 = array();
            foreach ($this->joinedFilter as $f) {
                $fCnt++;
                $tmp = explode(" in ", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai klausa "IN"
                    $this->criteria2 = $tmp[0] . " IN " . trim($tmp[1], "'");
                    //                    $this->criteria2 = $tmp[0]." IN ".trim($tmp[1], "'");
                    //                    $this->criteria[$tmp[0]] = " IN ".trim($tmp[1], "'");
                }
                else {
                    $tmp = explode(" is ", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai klausa "IS"
                        $this->criteria2 = $tmp[0] . " IS " . trim($tmp[1], "'");
                    }
                    else {

                        $tmp = explode(" like ", $f);
                        if (sizeof($tmp) > 1) { //==berarti pakai like
                            $this->criteria2 = $tmp[0] . " like '" . trim($tmp[1], "'") . "'";
                        }
                        else {
                            $tmp = explode("=", $f);
                            if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                                $this->criteria[$tmp[0] . "="] = trim($tmp[1], "'");
                                //                                $this->criteria[$tmp[0]] = trim($tmp[1], "'");
                            }
                            else {
                                $tmp = explode("<>", $f);
                                if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                                    //$this->criteriaNot[$tmp[0]] = trim($tmp[1], "'");
                                    $this->criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                                }
                                else {

                                    $tmp = explode("<", $f);
                                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                                        $this->criteria[$tmp[0] . "<"] = trim($tmp[1], "'");

                                    }
                                    else {
                                        $tmp = explode(">", $f);
                                        if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                                            $this->criteria[$tmp[0] . ">"] = trim($tmp[1], "'");

                                        }
                                        //                                    else {
                                        //                                        $tmp = explode(" is ", $f);
                                        //                                        if (sizeof($tmp) > 1) { //==berarti pakai klausa "IS"
                                        //                                            $this->criteria2 = $tmp[0] . " IS " . trim($tmp[1], "'");
                                        //                                        }
                                        //                                    }
                                        //                                arrPrint($this->criteria);
                                    }
                                }
                            }
                        }
                    }

                }
            }
        }
        //        arrPrint($this->criteria);
        //        arrPrint($this->criteria2);

    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    public function getCriteria2()
    {
        return $this->criteria2;
    }

    public function lookupByID($id)
    {

        $criteria = array("id" => $id);
        //        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $criteria + $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->where($criteria);

        return $this->db->get($this->tableName);
    }

    public function lookupByIdOnly($id)
    {
        $criteria = array("id" => $id);

        $this->db->where($criteria);
        return $this->db->get($this->tableName);
    }

    public function lookupByCondition($condition)
    {

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

        $this->db->where($condition);
        $result = $this->db->get($this->tableName);

        return $result;
    }

    public function lookupAllColumn()
    {
        $all_columns = $this->db->list_fields($this->tableName);
        $blackList_column = array(
            "cabang_nama",
            "gudang_nama",
            "r_move",
        );
        $columns = array_diff($all_columns, $blackList_column);

        return $columns;
    }

    public function updateKolom($data_id, $data_upates)
    {
        $tbl = $this->tableName;
        if (is_array($data_upates)) {
            $arrSet = $data_upates;
        }
        else {
            matiHere("arrUpdate harus dalam format array");
        }
        $this->db->set($arrSet);
        $this->db->where("id", $data_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateData($where, $data)
    {
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
        $this->db->where($where);
        $this->db->update($this->tableName, $data);


        // $mdlName = get_class($this);
        // $postProcs = isset($this->config->item("dataPostProcessors")[$mdlName]) ? $this->config->item("dataPostProcessors")[$mdlName] : array();
        // if (sizeof($postProcs) > 0) {
        //     cekmerah("ada post-processors " . __FILE__ ." ". __LINE__);
        //     foreach ($postProcs as $pp) {
        //         $comName = "DCom" . $pp;
        //         cekmerah("post-proc name: $pp / $comName");
        //         $this->load->model("DComs/" . $comName);
        //         $o = new $comName();
        //         $o->pair($data) or die(lgShowError($comName, "failed to pair the params of DCom"));
        //         $o->exec() or die(lgShowError($comName, "failed to execute DCom"));
        //     }
        // }

        return true;
    }

    public function updateDataIn($data)
    {
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
        $this->db->where($criteria);
        $this->db->update($this->tableName, $data);


        $mdlName = get_class($this);
        $postProcs = isset($this->config->item("dataPostProcessors")[$mdlName]) ? $this->config->item("dataPostProcessors")[$mdlName] : array();
        if (sizeof($postProcs) > 0) {
            cekmerah("ada post-processors");
            foreach ($postProcs as $pp) {
                $comName = "DCom" . $pp;
                cekmerah("post-proc name: $pp / $comName");
                $this->load->model("DComs/" . $comName);
                $o = new $comName();
                $o->pair() or die(lgShowError($comName, "failed to pair the params of DCom"));
                $o->exec() or die(lgShowError($comName, "failed to execute DCom"));
            }
        }

        return true;
    }

    public function addData($data)
    {

        //        $this->db->insert($this->tableName, $data);
        //        return $this->db->insert_id();
        //        arrprint($data);
        //        arrprint($this->filters);
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            foreach ($this->filters as $f) {

                $strF = explode("=", $f);

                if (sizeof($strF) > 1) {
                    $tmpKey = $strF[0];
                    $exKey = explode(".", $tmpKey);
                    if (sizeof($exKey) > 1) {
                        $origKey = $exKey[1];
                        if (isset($data[$tmpKey])) {
                            cekhijau("removing $f, replaced by $origKey");
                            $data[$origKey] = $data[$tmpKey];
                            unset($data[$tmpKey]);
                        }
                    }
                    else {
                        //                        cekhijau("NOT removing $f");
                        $origKey = $tmpKey;
                    }
                    if (!isset($data[$origKey])) {
                        $data[$strF[0]] = trim($strF[1], "'");
                    }
                }

            }

        }
        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();
    }

    public function deleteData($where)
    {
        $this->db->where($where);
        $this->db->delete($this->tableName);
        return true;
        //        cekHere($this->db->last_query());

    }

    public function lookupLimitedData($limit, $start, $key = "", $condition = null)
    {
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }

        $this->db->where($criteria);

        if ($key != "") {
            //            $this->db->group_start();
            //            $colCtr = 0;
            //            foreach ($this->fields as $fName => $fSpec) {
            //                $colCtr++;
            //                $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            //                if ($colCtr == 1) {
            //                    $this->db->like($this->tableName . "." . $fieldName, $key);
            //                } else {
            //                    $this->db->or_like($this->tableName . "." . $fieldName, $key);
            //                }
            //            }
            //            $this->db->group_end();

            $tmpCols = array();
            if (isset($this->listedFieldsSelectItem)) {
                foreach ($this->listedFieldsSelectItem as $fName => $fSpec) {
                    $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpCols[$fieldName] = $fieldName;
                }
            }
            else {
                foreach ($this->fields as $fName => $fSpec) {
                    $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpCols[$fieldName] = $fieldName;
                }
            }

            $this->createSmartSearch($key, $tmpCols);
        }


        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
            //            cekkuning("sorting by ".$this->sortBy['kolom']);
        }
        else {
            //            cekkuning("not sorting");
        }

        $this->db->limit($limit, $start);
        $query = $this->db->get($this->tableName);
        //        die($this->db->last_query());
        $data = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }
        else {
            return $data;
        }

        return false;
    }

    public function lookupDataCount($key = "")
    {
        if (isset($this->prefix) && $this->prefix != null && isset($this->tableNames[$this->prefix]["main"])) {
            $tableName = $this->tableNames[$this->prefix]["main"];
        }
        else {
            $tableName = $this->tableName;
        }
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("count(*) as totalnum");
//         cekBiru($criteria);
        $this->db->where($criteria);
        if ($key != "") {
            //            $this->db->group_start();
            //            $colCtr = 0;
            //            foreach ($this->fields as $fName => $fSpec) {
            //                $colCtr++;
            //                $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            //                if ($colCtr == 1) {
            //                    $this->db->like($this->tableName . "." . $fieldName, $key);
            //                } else {
            //                    $this->db->or_like($this->tableName . "." . $fieldName, $key);
            //                }
            //            }
            //            $this->db->group_end();
            $tmpCols = array();
            if (isset($this->listedFieldsSelectItem)) {
                foreach ($this->listedFieldsSelectItem as $fName => $fSpec) {
                    $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpCols[$fieldName] = $fieldName;
                }
            }
            else {
                foreach ($this->fields as $fName => $fSpec) {
                    $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpCols[$fieldName] = $fieldName;
                }
            }
            $this->createSmartSearch($key, $tmpCols);
            //            $this->createSmartSearch($key, $this->listedFieldsSelectItem);
        }

        $rslt = $this->db->get($tableName)->result();
        // cekHijau($this->db->last_query());
        if (sizeof($rslt) > 0) {
            return $rslt[0]->totalnum;
        }
        return 0;

    }

    public function lookupDataCount_($key = "")
    {

        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }
        $this->db->where($criteria);
        if ($key != "") {
            $this->db->group_start();
            $colCtr = 0;
            foreach ($this->fields as $fName => $fSpec) {
                $colCtr++;
                $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                if ($colCtr == 1) {
                    $this->db->like($this->tableName . "." . $fieldName, $key);
                }
                else {
                    $this->db->or_like($this->tableName . "." . $fieldName, $key);
                }
            }
            $this->db->group_end();
        }
        //        cekHijau($this->db->last_query());
        return $this->db->get($this->tableName)->num_rows();

    }

    public function detectPrimarySearchColumn()
    {
        $candidateCols = array("nama", "name", "nomer", "nomor", "kode");
        if (isset($this->listedFieldsSelectItem) && is_array($this->listedFieldsSelectItem) && sizeof($this->listedFieldsSelectItem) > 0) {
            foreach ($candidateCols as $cCol) {
                foreach ($this->listedFieldsSelectItem as $kCol => $vCol) {
                    $val = is_string($vCol) ? $vCol : "";
                    $key = is_string($kCol) ? $kCol : "";
                    if ($val === $cCol || $key === $cCol) {
                        return $val !== "" ? $val : $key;
                    }
                    if ($val !== "" && substr($val, -(strlen($cCol) + 1)) === "." . $cCol) {
                        return $val;
                    }
                }
            }

            foreach ($this->listedFieldsSelectItem as $kCol => $vCol) {
                $colName = is_numeric($kCol) ? $vCol : $kCol;
                if ($colName != "id" && substr($colName, -3) != ".id") {
                    return $colName;
                }
            }
        }

        return isset($this->sortBy['kolom']) ? $this->sortBy['kolom'] : "id";
    }

    public function lookupByKeyword($key)
    {

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

        //        $this->db->limit(15, 0);// walah ngapain dilimit 15 disini?

        $cleanKey = trim($key);
        if (strlen($cleanKey) >= 3) {
            $this->createSmartSearch($cleanKey, $this->listedFieldsSelectItem);

            $primaryCol = $this->detectPrimarySearchColumn();
            if ($primaryCol != "" && $primaryCol != "id" && substr($primaryCol, -3) != ".id") {
                $escapedLike = $this->db->escape_like_str($cleanKey);
                $escapedExact = $this->db->escape($cleanKey);

                $caseOrder = "CASE "
                    . "WHEN " . $primaryCol . " = " . $escapedExact . " THEN 1 "
                    . "WHEN " . $primaryCol . " LIKE '" . $escapedLike . " %' THEN 2 "
                    . "WHEN " . $primaryCol . " LIKE '" . $escapedLike . "%' THEN 3 "
                    . "ELSE 4 END ASC, "
                    . "CHAR_LENGTH(" . $primaryCol . ") ASC";

                $this->db->order_by($caseOrder, '', FALSE);
            }
        }

        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);

        $result = $this->db->get($this->tableName);
        //showLast_query("biru");

        return $result;
    }

    public function lookupRecentData($tCode)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);
        //$this->db->where(array("tcode='$tCode'"));
        $mdlName = get_class($this);
        $tmpTblName = $this->tableName;
        $this->db->order_by("hit", "desc");
        $this->db->order_by("updated", "desc");
        $this->db->join('_selector', "_selector.object_id = $tmpTblName.id and tcode='$tCode' and object_type='$mdlName'");
        $this->db->limit(12);
        return $this->db->get();
    }

    public function lookupSelectedData($kolom_name)
    {
        $this->db->select($kolom_name);
        if (isset($this->conditional)) {
            // $arrWhere = array(
            //     "trash"        => 0, //            "status" => 1,
            //     "cabang_id !=" => 0
            // );
            //            arrPrint($this->conditional);
            //             die("**");
            $this->db->where($this->conditional);
        }
        //        arrPrint($this->conditional);
        //arrPrint($this->tableName);
        //        cekHere($this->db->last_query());
        return $q = $this->db->get($this->tableName);


    }

    public function lookupTotalActive()
    {
        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->select('id');
        //        $arrWhere = array(
        //            "trash" => 0,
        //            "jenis =" => "supplier"
        //        );
        //        $this->db->where($arrWhere);
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }
        $q = $this->db->get_where($this->tableName, $criteria)->num_rows();

        return $q;
    }

    public function lookupTotalNonActive()
    {
        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->select('id');
        //        $arrWhere = array(
        //            "trash" => 1,
        //        );
        //        $this->db->where($arrWhere);
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }
        $q = $this->db->get_where($this->tableName, $criteria)->num_rows();

        return $q;
    }

    public function lookupTotalAll()
    {

        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->select('id');
        $q = $this->db->get($this->tableName)->num_rows();

        return $q;
    }

    public function lookupLimitedActive($position = "", $batas = "")
    {
        $list = array();
        foreach ($this->listedFieldsView as $kolom) {
            $list[] = $this->fields[$kolom]['kolom'];
        }
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            //            print_r($criteria);die();
        }


        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        //        $this->db->order_by('nama', 'asc');
        $this->db->limit($batas, $position);
        $q = $this->db->get_where($this->tableName, $criteria);
        //        cekHere($this->db->last_query());
        return $q;
    }

    public function lookupLimitedNonActive($position = "", $batas = "")
    {
        $list = array();
        foreach ($this->listedFieldsView as $kolom) {
            $list[] = $this->fields[$kolom]['kolom'];
        }
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            //            print_r($criteria);die();
        }

        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        //        $this->db->order_by('nama', 'asc');
        $this->db->limit($batas, $position);
        $q = $this->db->get_where($this->tableName, $criteria);

        return $q;
    }

    public function lookupLimitedAll($position = "", $batas = "")
    {
        $list = array();
        foreach ($this->listedFieldsView as $kolom) {
            $list[] = $this->fields[$kolom]['kolom'];
        }
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }

        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        //        $this->db->order_by('nama', 'asc');
        $this->db->limit($batas, $position);
        //        $q = $this->db->get_where($this->tableName, $criteria);
        $q = $this->db->get_where($this->tableName, $criteria);

        return $q;
    }

    public function lookupLimitedBySelected($conditional = "")
    {

        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            //            print_r($criteria);die();
        }
        //        $this->db->order_by('nama', 'asc');
        if (strlen($this->search) > 3) {
            $this->db->like('nama', $this->search);

            $q = $this->db->get_where($this->tableName, $criteria);
        }
        else {
            $q = null;
        }

        return $q;
    }

    public function lookupRecentHistories()
    {
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
        $this->db->select("*");
        // $this->db->from($this->tableNames['main']);
        $this->db->limit(20);
        $this->db->order_by("id", "desc");
        $result = $this->db->get($this->tableName);
        //        cekBiru($this->db->last_query());
        return $result;
    }

    function createSmartSearch($key, $arrColumns)
    {
        //        $key = mysql_escape_string($key);

        $key = rtrim(ltrim($key, " "), " ");
        // $key = trim($key);
        $key = str_replace("   ", " ", $key);
        $key = str_replace("  ", " ", $key);
        $arrKey = explode(" ", $key);
        $kCnt = 0;

        $this->db->group_start();
        foreach ($arrKey as $k) {
            $kCnt++;
            $cCnt = 0;

            $colCtr = 0;
            $this->db->group_start();
            foreach ($arrColumns as $fieldName) {
                $colCtr++;
                if ($colCtr == 1) {
                    //                    $this->db->like($this->tableName.".".$fieldName, $k);
                    $this->db->like($fieldName, $k);
                }
                else {
                    //                    $this->db->or_like($this->tableName.".".$fieldName, $k);
                    $this->db->or_like($fieldName, $k);
                }
            }
            $this->db->group_end();


        }
        $this->db->group_end();

    }

    // ----------------------------------begin mode data table----------------------------------
    function make_query()
    {
        // var $select_column = $this->listedFields;
        // arrPrint($this->order_column);
        // $this->db->select($this->select_column);
        // $this->db->select(array_flip($this->listedFields));
        $koloms = array();
        foreach ($this->fields as $kolom_bawaan => $fieldAttrs) {
            // $koloms_0[] = $kolom_bawaan;
            $koloms_1[] = isset($fieldAttrs['kolom']) ? $fieldAttrs['kolom'] : $kolom_bawaan;
            // $koloms_0[] = isset($fieldAttrs['kolom_alt']) ? $kolom_bawaan : $fieldAttrs['kolom_alt'];
            // $koloms_0[] = isset($fieldAttrs['kolom_alt']) ? $fieldAttrs['kolom_alt'] : "";
            $koloms_0[] = isset($this->kolomAlt) ? $kolom_bawaan : "";
            $koloms_2[] = isset($fieldAttrs['kolom_alt']) ? $fieldAttrs['kolom_alt'] : "";
        }
        // echo ($koloms_0);
        $koloms = array_filter(array_unique(array_merge($koloms_0, $koloms_1, $koloms_2)));

        // arrPrintPink($koloms);
        $this->db->select($koloms);
        $searchKoloms = array_flip($this->listedFieldsSelectItem);
        $this->db->from($this->tableName);
        // if (isset($_POST["search"]["value"])) {
        if (isset($_POST["search"]["value"]) && (strlen($_POST["search"]["value"]) > 0)) {
            $this->createSmartSearch($_POST["search"]["value"], $searchKoloms);
        }
        if (isset($_POST["order"])) {
            /* -----------------------------------
             * ada -1 untuk mengurangi urutan key request order, dikarenakan di data ada tambahan kolom nomer
             * -------------------------------*/
            $this->db->order_by($this->order_column[(-1 + $_POST['order']['0']['column'])], $_POST['order']['0']['dir']);
        }
        else {
            $this->db->order_by('id', 'DESC');
        }
        // arrPrint($this->ciFilters);
        if (isset($this->ciFilters)) {

            // $cabang_id = my_cabang_id();
            foreach ($this->ciFilters as $ky => $val) {
                $ekp_val = explode(".", $val);
                if (sizeof($ekp_val) > 1) {
                    $value = $ekp_val[1]();
                }
                else {
                    $value = $val;
                }

                $ciFilters[$ky] = $value;
            }

            // $this->db->where($this->ciFilters);
            $this->db->where($ciFilters);
        }
        else {
            $criteria = array();
            if (sizeof($this->filters) > 0) {
                $this->fetchCriteria();
                $criteria = $this->getCriteria();
            }
            $this->db->where($criteria);
        }

        $searchPerKoloms = array_keys($this->listedFields);
        $dt_columns = $_POST['columns'];
        // arrPrintPink($dt_columns);
        // arrPrintPink($searchPerKoloms);
        $dt_column_searchs = array();
        foreach ($dt_columns as $col_ky => $dt_column) {
            $dt_search = $dt_column['search']['value'];
            if (!empty($dt_search)) {
                // cekHijau("$col_ky || $dt_search");
                $kolom_nama = $searchPerKoloms[($col_ky - 1)];

                $dt_column_searchs["$kolom_nama like"] = "%$dt_search%";
            }
        }
        if (count($dt_column_searchs) > 0) {
            $this->db->where($dt_column_searchs);
        }

    }

    function make_datatables()
    {
        $this->make_query();
        // echo $this->make_query();
        $plength = isset($_POST["length"]) ? $_POST["length"] : "";
        $pstart = isset($_POST['start']) ? $_POST["start"] : "";
        if ($plength != -1) {
            $this->db->limit($plength, $pstart);
        }

        $query = $this->db->get();
        // showLast_query("merah");

        return $query->result();
    }

    function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_all_data()
    {
        $this->db->select("*");
        $this->db->from($this->tableName);
        return $this->db->count_all_results();
    }

    // ----------------------------------end mode data table----------------------------------

    public function syncNamaNama($produkIds = "", $objectID = "")
    {
        if (method_exists($this, "paramSyncNamaNama")) {
            $mdls = method_exists($this, "paramSyncNamaNama") ? $this->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
            $vars = array();
            foreach ($mdls as $mdl => $params) {
                $this->load->model("Mdls/$mdl");
                $tm = new $mdl();
                $kolom = $params['id'];
                $kolom_targets_0 = $params['kolomDatas'];
                if (is_array($produkIds)) {
                    $this->db->or_where_in("id", $produkIds);
                }
                elseif ($produkIds > 0) {
                    $this->db->where("id", $produkIds);
                }
                $tmps = $tm->lookupAll()->result();
                foreach ($tmps as $tmp) {
                    $datas = array();
                    foreach ($kolom_targets_0 as $kolom_source => $kolom_target) {
                        $vars[$kolom][$tmp->id] = $tmp->$kolom_source;
                        $wheres = array(
                            $kolom => $tmp->id
                        );
                        $datas[$kolom_target] = $tmp->$kolom_source;
                    }
                    if (strlen($objectID) > 0) {
                        $wheres["id"] = $objectID;
                    }
                    $this->updateData($wheres, $datas);
                }
            }
//            $this->db->trans_complete();//ini tidak boleh di mother membuat commit bocor**
        }
        else {
            cekMerah("tidak terjadi sync nama-nama karena belum ada paramSyncnamaNama");
        }
    }

    public function lookupJmlActive()
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiDisini("toko_id harap diset " . __METHOD__);
        $this->db->select(array(
            "id",
        ));
        // $this->db->where(array(
        //     "toko_id" => $toko_id,
        // ));
        $tmps = $this->lookupAll()->num_rows();

        return $tmps;
    }

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }

    /* ----------------------------------------------------------------------
     * disetting pada masing2 mdl
     * contoh di MdlProdukPerSupplier
     * ----------------------------------------------------------------------*/
    public function lookupInnerJoin()
    {
        $tbl_1 = $this->tableName;
        foreach ($this->fields as $kolom_bawaan => $fieldAttrs) {
            // $koloms_0[] = $kolom_bawaan;
            $anakan = isset($fieldAttrs['anakan']) && $fieldAttrs['anakan'] == true ? $fieldAttrs['anakan'] : false;
            if ($anakan == false) {

                $koloms_1[] = isset($fieldAttrs['kolom']) ? "$tbl_1." . $fieldAttrs['kolom'] : $kolom_bawaan;
                // $koloms_0[] = isset($fieldAttrs['kolom_alt']) ? $kolom_bawaan : $fieldAttrs['kolom_alt'];
                // $koloms_0[] = isset($fieldAttrs['kolom_alt']) ? $fieldAttrs['kolom_alt'] : "";
                $koloms_0[] = isset($this->kolomAlt) ? $kolom_bawaan : "";
                $koloms_2[] = isset($fieldAttrs['kolom_alt']) ? $fieldAttrs['kolom_alt'] : "";
            }
        }
        $koloms = array_filter(array_unique(array_merge($koloms_0, $koloms_1, $koloms_2)));
        // ------------------------------------------------------
        $this->db->select($koloms);
        $this->db->from($tbl_1);

        // contoh ada di MdlCompany
        if (isset($this->innerJoint)) {
            foreach ($this->innerJoint as $paramJoins) {
                $tbl_slave = $paramJoins["tbl"];
                $selects = $paramJoins["select"];
                $onCondite = $paramJoins["on"];
                $whereSlaveCondite = isset($paramJoins["where_slave"]) ? $paramJoins["where_slave"] : array();
                $whereMainCondite = isset($paramJoins["where_main"]) ? $paramJoins["where_main"] : array();
                foreach ($selects as $selectKolom) {
                    $selectJoint[] = "$tbl_slave.$selectKolom";
                }

                $on_condite = "";
                foreach ($onCondite as $mainKolom => $slaveKolom) {
                    $var = "$tbl_1.$mainKolom = $tbl_slave.$slaveKolom";
                    if ($on_condite == "") {
                        $on_condite .= "$var";
                    }
                    else {
                        $on_condite = "$on_condite AND $var";
                    }
                }


                $this->db->select($selectJoint);
                $this->db->join($tbl_slave, $on_condite, 'inner');

                if (count($whereSlaveCondite)) {
                    foreach ($whereSlaveCondite as $slvKolom => $slvCondite) {
                        $slvCondites["$tbl_slave.$slvKolom"] = $slvCondite;
                    }
                    // arrPrintKuning($slvCondites);
                    $this->db->where($slvCondites);
                }
                if (count($whereMainCondite)) {
                    foreach ($whereMainCondite as $slvKolom => $slvCondite) {
                        $slvCondites["$tbl_1.$slvKolom"] = $slvCondite;
                    }
                    // arrPrintKuning($slvCondites);
                    $this->db->where($slvCondites);
                }

            }
        }

        $query = $this->db->get();
        // showLast_query("merah");

        return $query;
    }
}
