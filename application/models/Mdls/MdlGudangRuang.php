// START OF COMPLETE REPEATED LOGIC
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * [AGENT_LOG]
 * ROLE      : Lead Architect Agent & Software Engineer Agent
 * PURPOSE   : Model metadata-driven untuk ruangan di dalam gudang.
 * COMPLIANCE: Standar ISO 9001 (Manajemen Mutu Gudang) & MdlMother Metadata.
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
class MdlGudangRuang extends MdlMother
{
    protected $tableName = "gudang_ruang";
    protected $indexFields = "id";
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "gudang_id" => array("required"),
        "nama"      => array("required", "singleOnly"),
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
        "gudang" => array(
            "label"      => "Gudang",
            "type"       => "int", "length" => "24", "kolom" => "gudang_id",
            "inputType"  => "combo",
            "reference"  => "MdlGudang",
            "strField"   => "nama",
            "kolom_nama" => "gudang_nama",
            "editable"   => true,
            "referenceFilter" => array(
                "cabang_id" => array(
                    "var" => "my_cabang_id"
                )
            ),
        ),
        "gudang_nama" => array(
            "label"     => "Gudang",
            "type"      => "varchar", "length" => "255", "kolom" => "gudang_nama",
            "inputType" => "hidden",
        ),
        "nama" => array(
            "label"     => "Nama Ruang",
            "type"      => "varchar", "length" => "100", "kolom" => "nama",
            "inputType" => "text",
        ),
    );

    protected $listedFields = array(
        "nama"        => "nama ruang",
        "gudang_nama" => "nama gudang",
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
            "MdlGudang" => array(
                "id"         => "gudang_id",
                "kolomDatas" => array(
                    "nama" => "gudang_nama",
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
