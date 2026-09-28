<?php

class MdlTaxScenarioHadiahStatic extends MdlMother_static
{
    protected $tableName = "static";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();

    protected $validationRules = array(
        "name" => array("required", "singleOnly"),
    );

    protected $listedFieldsView = array("name");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "varchar", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",
        ),
        "name" => array(
            "label" => "name",
            "type" => "varchar", "length" => "255", "kolom" => "name",
            "inputType" => "text",
        ),
    );

    protected $staticData = array(
        array(
            "id" => "1",
            "name" => "PPN & PPh ditanggung pemberi (gross-up)",
        ),
        array(
            "id" => "2",
            "name" => "PPN & PPh tidak ditanggung penerima",
        ),
        array(
            "id" => "3",
            "name" => "PPN & PPh ditanggung penerima",
        ),
        array(
            "id" => "4",
            "name" => "PPN ditanggung pemberi, PPh ditanggung penerima",
        ),
    );

    protected $listedFields = array(
        "name" => "name",
    );

    public function __construct()
    {
        parent::__construct();
    }
}
