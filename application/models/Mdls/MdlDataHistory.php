<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 7/24/2018
 * Time: 3:52 PM
 */
class MdlDataHistory extends MdlMother
{
    protected $tableName = "data__history";
    protected $fields = array(
        "orig_id" =>array(
            "label"     => "orig_id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
        ),
        "mdl_name" =>array(
            "label"     => "id",
            "type"      => "varchar", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
        ),
        "mdl_label" =>array(
            "label"     => "id",
            "type"      => "varchar", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
        ),
        "old_content" =>array(
            "label"     => "old_content",
            "type"      => "mediumblob", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
        ),
        "new_content" =>array(
            "label"     => "new_content",
            "type"      => "mediumblob", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
        ),
        "label" =>array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
        ),
        "oleh_id" =>array(
            "label"     => "PIC",
            "type"      => "int", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
        ),
        "oleh_name" =>array(
            "label"     => "PIC",
            "type"      => "int", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
        ),
        "dtime" =>array(
            "label"     => "tanggal",
            "type"      => "timedStamp", "length" => "24", "kolom" => "id",
//            "inputType" => "hidden",// hidden
        ),


    );
    protected $filters = array();
    protected $validationRules = array();
    protected $customLink = array();

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
    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }
    //</editor-fold>

    protected $listedFields = array(
        "id"    => "id",
        "dtime"    => "tanggal",
        "mdl_label"       => "jenis",
        "oleh_name"      => "PIC",
        "label"      => "label",
        "old_content" => "data lama",
        "new_content" => "data baru"
    );



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
    protected $sortBy = array(
        "kolom" => "id",
        "mode"  => "DESC",
    );

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
        return $this->db->get_where($this->tableName, $criteria);

    }

}