<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 7/24/2018
 * Time: 3:52 PM
 */
class MdlDataTmp extends CI_Model
{
    private $tableName = "data__tmp";
    private $fields = array(
        "orig_id",
        "mdl_name",
        "mdl_label",
        "proposed_by",
        "proposed_by_name",
        "proposed_date",
        "content",
    );
    private $filters = array();
    private $validationRules = array();
    private $customLink = array();

    //<editor-fold desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
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

    public function getCustomLink()
    {
        return $this->customLink;
    }

    //</editor-fold>

    public function setCustomLink($customLink)
    {
        $this->customLink = $customLink;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    function updateData($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableName, $data);
    }

    function addData($data)
    {

        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
    }

    function deleteData($where)
    {
        $this->db->where($where);
        $this->db->delete($this->tableName);
    }

    public function proposeNew()
    {

    }

    public function swapTo()
    {

    }

    public function reject()
    {

    }

    public function lookupAll()
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
                }
                else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }
        return $this->db->get_where($this->tableName, $criteria);
    }
}