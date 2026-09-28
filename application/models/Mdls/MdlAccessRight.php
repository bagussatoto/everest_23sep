<?php
// START OF COMPLETE REPEATED LOGIC

//--include_once "MdlHistoriData.php";
class MdlAccessRight extends MdlMother
{
    protected $tableName = "set_menu";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array("employee_id" => "employee");
    protected $search;
    protected $filters = array(
        "trash='0'");

    protected $validationRules = array(
        "employee_id" => array("required"),
//        "cabang_id" => array("required"),
        "menu_category" => array("required"),
        "menu_label" => array("required"),
        "steps" => array("required"),
        "steps_label" => array("required"),

        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "menu_label" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "menu_label",
            "inputType" => "combo",
            "reference" => "MdlFolderProduk",
        ),

        "menu_category" => array(
            "label" => "category",
            "type" => "varchar", "length" => "255", "kolom" => "menu_category",
            "inputType" => "text",
            "width" => "250px"
        ),

        "steps" => array(
            "label" => "step",
            "type" => "varchar", "length" => "255", "kolom" => "steps",
            "inputType" => "text",
        ),
        "steps_code" => array(
            "label" => "step",
            "type" => "varchar", "length" => "255", "kolom" => "steps_code",
            "inputType" => "text",
        ),
        "steps_label" => array(
            "label" => "step",
            "type" => "varchar", "length" => "255", "kolom" => "steps_label",
            "inputType" => "text",
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
        "employe_id" => "id",
        "menu_category" => "menu",
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

    public function getBulkUsersByJenisAndCabang($jenisTr, $cabangId = null)
    {
        $ci = &get_instance();
        $result = array();
        if (empty($jenisTr)) {
            return $result;
        }

        $transUI = $ci->config->item("coTransaksiUi");
        if (empty($transUI)) {
            $transUI = $ci->config->item("heTransaksi_ui");
        }
        if (empty($transUI) && isset($ci->configUi)) {
            $transUI = $ci->configUi;
        }
//        $connectTo = isset($transUI[$jenisTr]['connectTo']) ? $transUI[$jenisTr]['connectTo'] : '';

        $ci->db->select("sm.steps, sm.steps_code, pe.id, pe.nama, pe.nama_login");
        $ci->db->from("set_menu sm");
        $ci->db->join("per_employee pe", "pe.id = sm.employee_id", "inner");
        $ci->db->where("sm.active", "1");
        $ci->db->where("sm.trash", "0");
        $ci->db->where("pe.trash", "0");
        $ci->db->where("pe.ghost", "0");

        $ci->db->group_start();
//        $ci->db->where_in("sm.menu_category", $jenisTr);
        $ci->db->where_in("sm.steps_code", $jenisTr);
//        $ci->db->where("sm.menu_category", (string)$jenisTr);
//        $ci->db->or_like("sm.menu_category", (string)$jenisTr, "after");
//        $ci->db->or_like("sm.steps_code", (string)$jenisTr, "after");
//        if (!empty($connectTo)) {
//            $ci->db->or_where("sm.menu_category", (string)$connectTo);
//            $ci->db->or_where("sm.steps_code", (string)$connectTo);
//            $ci->db->or_like("sm.menu_category", (string)$connectTo, "after");
//            $ci->db->or_like("sm.steps_code", (string)$connectTo, "after");
//        }
        $ci->db->group_end();

        if ($cabangId != null && $cabangId > 0) {
            $ci->db->group_start();
            $ci->db->where("pe.cabang_id", $cabangId);
            $ci->db->or_where("sm.cabang_id", $cabangId);
//            $ci->db->or_where("pe.cabang_id IS NULL", null, false);
//            $ci->db->or_where("sm.cabang_id IS NULL", null, false);
            $ci->db->group_end();
        }

        $query = $ci->db->get();
        cekHere($ci->db->last_query());
        if ($query && $query->num_rows() > 0) {
            $rows = $query->result_array();
//            arrPrintPink($rows);
            foreach ($rows as $row) {
                $stepNum = intval($row['steps']);
                $stepCode = ($row['steps_code']);
                $userName = !empty($row['nama']) ? $row['nama'] : (isset($row['nama_login']) ? $row['nama_login'] : '');
                if (!empty($userName) && $stepNum > 0) {
                    if (!isset($result[$stepCode])) {
                        $result[$stepCode] = array();
                    }
                    if (!in_array($userName, $result[$stepCode])) {
                        $result[$stepCode][] = $userName;
                    }
                }
            }
        }

        return $result;
    }
}
// END OF COMPLETE REPEATED LOGIC