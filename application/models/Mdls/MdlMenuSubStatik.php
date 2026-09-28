<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 07/08/2018
 * Time: 21.34
 */
class MdlMenuSubStatik extends MdlMother
{

    protected $tableName;
    protected $tableName_1;
    protected $indexFields;
    protected $fields;

    protected $validationRules = array(
        "kategori_id" => array("required"),
        "nama"        => array("required", "unique"),
    );

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "set_menu_statik";
        $this->indexFields = "id";
        $this->fields = array(

            "id"              => array(
                "label"     => "id",
                "type"      => "int",
                "length"    => "24",
                "kolom"     => "id",
                "inputType" => "hidden",
            ),

            "kategori" => array(
                "label"      => "jenis",
                "type"      => "int",
                "length"     => "255",
                "kolom"     => "kategori_id",
                "inputType"  => "hidden",
            ),

            "sub_kategori" => array(
                "label"      => "sub jenis",
                "type"       => "int",
                "length"     => "255",
                "kolom"      => "sub_kategori_id",
                "inputType"  => "hidden",
            ),

            "jml_serial" => array(
                "label"        => "jml serial",
                "type"         => "int",
                "length"       => "32",
                "kolom"        => "jml_serial",
                "inputType"    => "text",
                "editable"     => true,
                "defaultValue" => 0,
            ),

            "label" => array(
                "label"      => "text menu",
                "type"       => "varchar",
                "length"     => "255",
                "kolom"      => "label",
                "inputType"  => "text",
            ),

            "title" => array(
                "label"      => "sub title",
                "type"       => "varchar",
                "length"     => "255",
                "kolom"      => "title",
                "inputType"  => "hidden",
            ),

            "type" => array(
                "label"      => "type menu",
                "type"       => "varchar",
                "length"     => "255",
                "kolom"      => "type",
                "inputType"  => "text",
            ),

            "add_produk" => array(
                "label"      => "type menu",
                "type"       => "varchar",
                "length"     => "255",
                "kolom"      => "add_produk",
                "inputType"  => "text",
            ),

            "anakan" => array(
                "label"      => "type menu",
                "type"       => "varchar",
                "length"     => "255",
                "kolom"      => "anakan",
                "inputType"  => "text",
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

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }
}