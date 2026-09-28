// START OF COMPLETE REPEATED LOGIC
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * [AGENT_LOG]
 * ROLE      : Lead Architect Agent & Software Engineer Agent
 * PURPOSE   : Model metadata-driven untuk lorong rak di dalam ruangan.
 * COMPLIANCE: Standar ISO 9001 (Manajemen Mutu Gudang) & MdlMother Metadata.
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
class MdlGudangLorong extends MdlMother
{
    protected $tableName = "gudang_lorong";
    protected $indexFields = "id";
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "ruang_id" => array("required"),
        "nama"     => array("required", "singleOnly"),
    );

    protected $fields = array(
        "id" => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",
        ),
        "cabang" => array(
            "label"        => "Cabang",
            "type"         => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType"    => "hidden",
            "reference"    => "MdlCabang",
            "kolom_nama"   => "cabang_nama",
            "defaultValue" => ".my_cabang_id",
        ),
        "cabang_nama" => array(
            "label"     => "Cabang",
            "type"      => "varchar", "length" => "255", "kolom" => "cabang_nama",
            "inputType" => "hidden",
        ),
        "ruang" => array(
            "label"      => "Ruang",
            "type"       => "int", "length" => "24", "kolom" => "ruang_id",
            "inputType"  => "combo",
            "reference"  => "MdlGudangRuang",
            "strField"   => "nama",
            "kolom_nama" => "ruang_nama",
            "editable"   => true,
            "referenceFilter" => array(
                "cabang_id" => array(
                    "var" => "my_cabang_id"
                )
            ),
        ),
        "ruang_nama" => array(
            "label"     => "Ruang",
            "type"      => "varchar", "length" => "255", "kolom" => "ruang_nama",
            "inputType" => "hidden",
        ),
        "nama" => array(
            "label"     => "Nama Lorong",
            "type"      => "varchar", "length" => "100", "kolom" => "nama",
            "inputType" => "text",
        ),
    );

    protected $listedFields = array(
        "nama"        => "nama lorong",
        "ruang_nama"  => "nama ruang",
        "cabang_nama" => "cabang",
    );

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

    public function paramSyncNamaNama()
    {
        return array(
            "MdlGudangRuang" => array(
                "id"         => "ruang_id",
                "kolomDatas" => array(
                    "nama" => "ruang_nama",
                ),
            ),
            "MdlCabang" => array(
                "id"         => "cabang_id",
                "kolomDatas" => array(
                    "nama" => "cabang_nama",
                ),
            ),
        );
    }
}
// END OF COMPLETE REPEATED LOGIC
