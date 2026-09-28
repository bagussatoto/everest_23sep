<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 07/08/2018
 * Time: 21.34
 */
class MdlMenu extends MdlMother
{
    protected $tableName;
    protected $tableName_1;
    protected $indexFields;
    protected $fields;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "set_menu";
        $this->tableName_1 = "set_landingpage";
        $this->indexFields = "id";
        $this->fields = array(
            "id"              => array(
                "label"     => "id",
                "type"      => "int", "length" => "24", "kolom" => "id",
                "inputType" => "hidden",// hidden
                //--"inputName" => "id",
            ),
            "per_employee_id" => array(
                "label"     => "employee id",
                "type"      => "int", "length" => "24", "kolom" => "per_employee_id",
                "inputType" => "hidden",// hidden
                //--"inputName" => "per_employee_id",
            ),
            "menu_label"      => array(
                "label"     => "menu label",
                "type"      => "int", "length" => "24", "kolom" => "menu_label",
                "inputType" => "hidden",// hidden
                //--"inputName" => "menu_label",
            ),
            "menu_category"   => array(
                "label"     => "menu category",
                "type"      => "int", "length" => "24", "kolom" => "menu_category",
                "inputType" => "hidden",// hidden
                //--"inputName" => "menu_category",
            ),
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
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getIndexFields()
    {
        return $this->indexFields;
    }

    /**
     * @param string $indexFields
     */
    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    /**
     * @return string
     */
    public function getTableName1()
    {
        return $this->tableName_1;
    }

    /**
     * @param string $tableName_1
     */
    public function setTableName1($tableName_1)
    {
        $this->tableName_1 = $tableName_1;
    }


    //  region akses menu
    public function lookupMyMenu($emp_id)
    {
        $koloms = array_keys($this->fields);

        $this->setConditional(array("per_employee_id" => $emp_id));
        $var_ot = $this->lookupSelectedData($koloms);

        return $var_ot;
    }

    public function lookupMyMenuMain($emp_id)
    {
        $koloms = array_keys($this->fields);

        $this->setConditional(array("per_employee_id" => $emp_id, "menu_category" => ""));
        $var_ot = $this->lookupSelectedData($koloms);

        return $var_ot;
    }

    public function lookupMyMenuSub($emp_id)
    {
        $koloms = array_keys($this->fields);
        $this->setConditional(array("per_employee_id" => $emp_id, "menu_category !=" => ""));

        $var_ot = $this->lookupSelectedData($koloms);

        return $var_ot;
    }
    //  endregion akses menu

    //  region landing
    public function lookupMyLandingSub($emp_id)
    {

        $koloms = array_keys($this->fields);

        $this->tableName = $this->tableName_1;
        $this->setConditional(array("per_employee_id" => $emp_id, "menu_category !=" => ""));
        $var_ot = $this->lookupSelectedData($koloms);

        return $var_ot;
    }
    //  endregion landing
}