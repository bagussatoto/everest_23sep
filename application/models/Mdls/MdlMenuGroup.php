<?php

//--include_once "MdlHistoriData.php";
class MdlMenuGroup extends MdlMother
{
    protected $tableName = "set_menu_group";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array("employee_id" => "employee");
    protected $search;
    protected $filters = array(
        "trash='0'");

    protected $validationRules = array(
        "nama"   => array("required"),
        //        "cabang_id" => array("required"),
        "label" => array("required"),
        "menu_label"    => array("required"),
        "steps"         => array("required"),
        "steps_label"   => array("required"),

        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"    => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "jenis" => array(
            "label"        => "jenis",
            "type"         => "varchar",
            "length"       => "24",
            "kolom"        => "jenis",
            "inputType"    => "combo",
            "dataSource"   => array(
                "transaksi" => "transaksi",
                "data"      => "data"
            ),
            "defaultValue" => "transaksi",
        ),

        "urutan" => array(
            "label"     => "order",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "urutan",
            "inputType" => "text",
            // "width"     => "250px"
        ),

        "nama" => array(
            "label"     => "group",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "nama",
            "inputType" => "text",
            "width"     => "250px",
            "editable"  => false,
        ),

        "label"  => array(
            "label"     => "label",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "label",
            "inputType" => "text",
        ),
        "icon"   => array(
            "label"     => "group_icon",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "icon",
            "inputType" => "text",
        ),
        "status" => array(
            "label"        => "status",
            "type"         => "varchar",
            "length"       => "255",
            "kolom"        => "status",
            "inputType"    => "combo",
            "dataSource"   => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
        ),
        //        "group_name"  => array(
        //            "label"     => "step",
        //            "type"      => "varchar", "length" => "255", "kolom" => "group_name",
        //            "inputType" => "text",
        //        ),
        //        "group_label"  => array(
        //            "label"     => "step",
        //            "type"      => "varchar", "length" => "255", "kolom" => "group_label",
        //            "inputType" => "text",
        //        ),

    );
    protected $listedFields = array(
        "urutan" => "order",
        "jenis"  => "jenis",
        "nama"   => "nama",
        "label"  => "label",
        "icon"   => "fa icon",
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

    public function callGroupAccess()
    {
        $transaksiUI = $this->config->item("heTransaksi_ui");
        $availStepTemp_0 = array();
        foreach ($transaksiUI as $jenis => $details) {
            $steps = $details["steps"];
            $parentLabels = $details["label"];
            $tempAvail = array();
            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                $access_group = $stepDetails["userGroup"];
                $tempAvail[$access_group][$steps] = $steps_label;

            }
            $availStepTemp_0[$jenis] = $tempAvail;
        }
        $availStepTemp = array();
        foreach ($availStepTemp_0 as $jn => $tempGr) {
            foreach ($tempGr as $gr => $temp) {
                $availStepTemp[$gr][$jn] = $temp;
            }
        }

        return $availStepTemp;

    }

    public function callGroupMenuTransaksi()
    {
        // $confSources = $transaksiUI = $this->config->item("heTransaksi_ui");
        $filters = array(
            "jenis" => "transaksi",
        );
        $hasil = "";

        $this->db->order_by('urutan', 'asc');
        $this->db->where($filters);
        // $tmp = $this->db->get($this->tableName);
        $tmp = $this->lookupAll();
        $confGroups = $tmp->result();


        $hasil = $confGroups;

        return $hasil;
    }

    public function callGroupMenu($jenisGroup)
    {
        // $confSources = $transaksiUI = $this->config->item("heTransaksi_ui");
        $filters = array(
            "jenis" => $jenisGroup,
        );
        $hasil = "";

        $this->db->order_by('urutan', 'asc');
        $this->db->where($filters);
        // $tmp = $this->db->get($this->tableName);
        $tmp = $this->lookupAll();
        $confGroups = $tmp->result();


        $hasil = $confGroups;

        return $hasil;
    }


}