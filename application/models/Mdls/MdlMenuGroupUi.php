<?php

//--include_once "MdlHistoriData.php";
class MdlMenuGroupUi extends MdlMother
{
    protected $tableName = "set_menu_group_ui";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array("employee_id" => "employee");
    protected $search;
    protected $filters = array(
        "trash='0'");

    protected $validationRules = array(
        "employee_id"   => array("required"),
        //        "cabang_id" => array("required"),
        "menu_category" => array("required"),
        "menu_label"    => array("required"),
        "steps"         => array("required"),
        "steps_label"   => array("required"),

        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"          => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "group_jenis" => array(
            "label"     => "jenis",
            "type"      => "varchar",
            "length"    => "24",
            "kolom"     => "group_jenis",
            "inputType" => "text",
        ),

        "group_nama" => array(
            "label"     => "group",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "group_nama",
            "inputType" => "text",
            "width"     => "250px"
        ),

        "group_id"   => array(
            "label"     => "label",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "group_id",
            "inputType" => "text",
        ),
        "menu_nama"  => array(
            "label"     => "menu",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "menu_nama",
            "inputType" => "text",
        ),
        "menu_jenis" => array(
            "label"     => "tr jenis",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "menu_jenis",
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
        "group_jenis" => "group",
        "group_nama"  => "nama",
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

    public function callGroupMenuTransaksiUi()
    {
        $confDataSources = $this->config->item('heDataBehaviour');
        $confSources_0 = $transaksiUI = $this->config->item("heTransaksi_ui");
        $this->load->model("Mdls/MdlMenuGroup");
        $gr = new MdlMenuGroup();
        $confGroups = $gr->callGroupMenu("transaksi");
        $confDataGroups = $gr->callGroupMenu("data");

        $confSources = array();
        foreach ($confSources_0 as $sourceJenis => $sourceItems) {
            $hideMenu = isset($sourceItems['hideMenu']) ? $sourceItems['hideMenu'] : false;
            if ($hideMenu == false) {

                $confSources[$sourceJenis] = $sourceItems;
            }
        }


        // $this->db->order_by('urutan', 'asc');
        // $this->db->where($filters);
        // $tmp = $this->db->get($this->tableName);
        $tmp = $this->lookupAll();
        $uiDatas = $tmp->result();
        // arrPrintWebs($confGroups);
        // arrPrint($uiDatas);

        $transaksi_ui = array();
        $transaksi_ui_grouped = array();
        foreach ($uiDatas as $uiData) {
            $uGroup = $uiData->group_nama;
            $ujenis = $uiData->menu_jenis;

            $transaksi_ui[$uGroup][] = $ujenis;
            $transaksi_ui_grouped[] = $ujenis;
        }

        // arrPrintWebs($transaksi_ui_grouped);
        // arrPrintWebs(array_keys($confSources));
        // arrPrint($transaksi_ui);
        $transaksi_ui_ungrouped = array_diff(array_keys($confSources), $transaksi_ui_grouped);
        // arrPrint($transaksi_ui_ungrouped);
        $data_ui_ungrouped = array_diff(array_keys($confDataSources), $transaksi_ui_grouped);
        // arrPrint($data_ui_ungrouped);
        /* -----------------------
         * konversi database menjadi strukture config
         * ------------------*/
        $undefine = "undefine";
        foreach ($confGroups as $confGroup) {
            $gNama = $confGroup->nama;

            $gUis[$gNama]['label'] = $confGroup->label;
            $gUis[$gNama]['icon'] = $confGroup->icon;
            $gUis[$gNama]['heTransaksi_ui'] = isset($transaksi_ui[$gNama]) ? $transaksi_ui[$gNama] : array();
        }
        $gUis[$undefine]['label'] = "undefine group";
        $gUis[$undefine]['icon'] = "fa-umbrella";
        $gUis[$undefine]['heTransaksi_ui'] = sizeof($transaksi_ui_ungrouped) ? $transaksi_ui_ungrouped : array();
        //$transaksi_ui_ungrouped
        // arrPrintWebs($gUis);
        // arrPrintWebs($confGroups);
        // arrPrint($confDataGroups);
        // arrPrint($confDataSources);

        // $undefine = "undefine";
        foreach ($confDataGroups as $confDataGroup) {
            $dgNama = $confDataGroup->nama;

            $dgUis[$dgNama]['label'] = $confDataGroup->label;
            $dgUis[$dgNama]['icon'] = $confDataGroup->icon;
            $dgUis[$dgNama]['heTransaksi_ui'] = isset($transaksi_ui[$dgNama]) ? $transaksi_ui[$dgNama] : array();
        }
        $dgUis[$undefine]['label'] = "undefine group";
        $dgUis[$undefine]['icon'] = "fa-umbrella";
        $dgUis[$undefine]['heTransaksi_ui'] = sizeof($data_ui_ungrouped) ? $data_ui_ungrouped : array();

        // $hasil = $tmp->;
        // arrPrintWebs($gUis);
        // arrPrint($dgUis);


        $vars['menuGroup_members'] = $transaksi_ui;
        /* ------------------------------
         * jenis groupnya
         * ---------------------*/
        $vars['transaksi'] = $gUis;   // transaksi
        $vars['data'] = $dgUis;       // data

        return $vars;
    }

    public function callJenisGroup($jenisGroup)
    {
        $confGroups_0 = $this->callGroupMenuTransaksiUi();
        // $confGroups = $confGroups_0['heTransaksiGroup_ui'];
        $confGroups = $confGroups_0[$jenisGroup];
        // $confGroups = $uDatas;
        // showLast_query("orange");
        // arrPrintWebs($confGroups_0);
        // arrPrintWebs($confGroups);
        $jeniseGroup = array();
        foreach ($confGroups as $group => $confGroup) {
            // $xx++;
            $jenises = $confGroup['heTransaksi_ui'];
            $label = $confGroup['label'];
            foreach ($jenises as $jenise) {
                $jeniseGroup[$jenise][] = $group;
            }
        }

        return $jeniseGroup;
    }
}