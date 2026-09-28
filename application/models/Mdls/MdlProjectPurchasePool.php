<?php
// START OF COMPLETE REPEATED LOGIC
defined('BASEPATH') OR exit('No direct script access allowed');

class MdlProjectPurchasePool extends MdlMother
{
    protected $tableName = "project_purchase_pool";
    protected $indexFields = "id";
    protected $indexFielddasar_s = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(
        "produk_nama" => "project_purchase_pool.produk_nama",
        "produk_kode" => "project_purchase_pool.produk_kode",
        "project_nama" => "project_purchase_pool.project_nama",
    );
    protected $search;
    protected $filters = array(
        "status <> 'CANCELLED'",
    );
    protected $sortBy = array(
        "kolom" => "id",
        "mode"  => "ASC",
    );
    protected $validationRules = array(
        "project_id" => array("required"),
        "produk_id"  => array("required"),
    );

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    protected $listedFieldsView = array("produk_nama");
    protected $fields = array(
        "id" => array(
            "label"     => "id",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "id",
            "inputType" => "hidden",
        ),
        "project_id" => array(
            "label"     => "project id",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "project_id",
            "inputType" => "hidden",
        ),
        "project_nama" => array(
            "label"     => "nama project",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "project_nama",
            "inputType" => "text",
        ),
        "project_start_id" => array(
            "label"     => "start id",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "project_start_id",
            "inputType" => "hidden",
        ),
        "project_start_nomer" => array(
            "label"     => "no kickoff",
            "type"      => "varchar",
            "length"    => "100",
            "kolom"     => "project_start_nomer",
            "inputType" => "text",
        ),
        "cabang_id" => array(
            "label"     => "cabang id",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "cabang_id",
            "inputType" => "hidden",
        ),
        "produk_id" => array(
            "label"     => "produk id",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "produk_id",
            "inputType" => "hidden",
        ),
        "produk_kode" => array(
            "label"     => "kode barang",
            "type"      => "varchar",
            "length"    => "100",
            "kolom"     => "produk_kode",
            "inputType" => "text",
        ),
        "produk_nama" => array(
            "label"     => "nama barang",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "produk_nama",
            "inputType" => "text",
        ),
        "satuan_nama" => array(
            "label"     => "satuan",
            "type"      => "varchar",
            "length"    => "50",
            "kolom"     => "satuan_nama",
            "inputType" => "text",
        ),
        "qty_kebutuhan" => array(
            "label"     => "qty kebutuhan",
            "type"      => "decimal",
            "length"    => "15,2",
            "kolom"     => "qty_kebutuhan",
            "inputType" => "number",
        ),
        "qty_po" => array(
            "label"     => "qty po",
            "type"      => "decimal",
            "length"    => "15,2",
            "kolom"     => "qty_po",
            "inputType" => "number",
        ),
        "qty_sisa" => array(
            "label"     => "qty sisa",
            "type"      => "decimal",
            "length"    => "15,2",
            "kolom"     => "qty_sisa",
            "inputType" => "number",
        ),
        "est_harga_beli" => array(
            "label"     => "est harga beli",
            "type"      => "decimal",
            "length"    => "15,2",
            "kolom"     => "est_harga_beli",
            "inputType" => "number",
        ),
        "status" => array(
            "label"      => "status",
            "type"       => "varchar",
            "length"     => "20",
            "kolom"      => "status",
            "inputType"  => "combo",
            "dataSource" => array(
                "OPEN"      => "OPEN",
                "PARTIAL"   => "PARTIAL",
                "CLOSED"    => "CLOSED",
                "CANCELLED" => "CANCELLED",
            ),
            "defaultValue" => "OPEN",
        ),
    );

    protected $listedFields = array(
        "project_nama"        => "projek",
        "project_start_nomer" => "no order",
        "produk_kode"         => "kode",
        "produk_nama"         => "barang",
        "satuan_nama"         => "satuan",
        "qty_kebutuhan"       => "kebutuhan",
        "qty_po"              => "sudah po",
        "qty_sisa"            => "sisa",
        "status"              => "status",
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

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
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

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
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
}
// END OF COMPLETE REPEATED LOGIC
