<?php

class MdlPpnPayMethodStatic extends MdlMother_static
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
        "is_cash" => array(
            "label" => "is_cash",
            "type" => "int", "length" => "3", "kolom" => "is_cash",
            "inputType" => "hidden",
        ),
        "is_hutang" => array(
            "label" => "is_hutang",
            "type" => "int", "length" => "3", "kolom" => "is_hutang",
            "inputType" => "hidden",
        ),
    );

    protected $staticData = array(
        array(
            "id" => "kas",
            "name" => "bayar kas/bank",
            "is_cash" => "1",
            "is_hutang" => "0",
        ),
//        array(
//            "id" => "utang",
//            "name" => "akui utang ppn",
//            "is_cash" => "0",
//            "is_hutang" => "1",
//        ),
    );

    protected $listedFields = array(
        "name" => "name",
    );

    public function __construct()
    {
        parent::__construct();
    }
}
