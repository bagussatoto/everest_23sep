<?php

//--include_once "MdlHistoriData.php";
class MdlSettUx extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $validationRules = array();
    protected $listedFieldsSelectFolder = array();
    protected $listedFieldsViewFolder = array();
    protected $listedFieldsSelectItem = array();
    protected $listedFieldsViewItem = array();
    protected $listedFieldsFormFolder = array();
    protected $listedFieldsFormItem = array();
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $kategori;
    protected $kategori_id;
    protected $search;
    protected $condites;

    public function getCondites()
    {
        return $this->condites;
    }

    public function setCondites($condites)
    {
        $this->condites = $condites;
    }


    //<editor-fold desc="getter-setter">

    function __construct()
    {
        parent::__construct();
        $this->tableName = "set_ux";
        $this->indexFields = "id";
        $this->fields = array(
            "id"            => array(
                "label"     => "id",
                "type"      => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "employee_nama" => array(
                "label"     => "nama",
                "type"      => "int", "length" => "255", "kolom" => "nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            // "folder"     => array(
            //     "label"     => "folder/kategory",
            //     "type"      => "int", "length" => "255", "kolom" => "folders",
            //     "inputType" => "combo",
            //     //--"inputName" => "folders",
            // ),
            // "satuan"     => array(
            //     "label"     => "satuan",
            //     "type"      => "int", "length" => "24", "kolom" => "satuan",
            //     "inputType" => "combo",
            //     //--"inputName" => "satuan",
            // ),
            // "trash"      => array(
            //     "label"     => "trash",
            //     "type"      => "int", "length" => "24", "kolom" => "trash",
            //     "inputType" => "int",
            //     //--"inputName" => "trash",
            // ),
            // "jenis"      => array(
            //     "label"      => "pilih jenis",
            //     "type"       => "int", "length" => "24", "kolom" => "jenis",
            //     "inputType"  => "combo",
            //     "dataSource" => array("folder", "item"),
            //     //--"inputName" => "jenis",
            // ),
            // "harga"      => array(
            //     "label"     => "harga",
            //     "type"      => "int", "length" => "24", "kolom" => "harga",
            //     "inputType" => "number",
            //     //--"inputName" => "harga",
            // ),
            "last_update"   => array(
                "label"     => "dtime",
                "type"      => "int", "length" => "24", "kolom" => "dtime",
                "inputType" => "text",
                //--"inputName" => "",
            ),
            // "oleh name" => array(
            //     "label" => "pic",
            //     "type" =>"int","length"=>"24","kolom" => "oleh_nama",
            //     "inputType" => "text",
            //     //--"inputName" => "",
            // ),
            "keterangan"    => array(
                "label"     => "keterangan",
                "type"      => "int", "length" => "24", "kolom" => "label",
                "inputType" => "text",
                //--"inputName" => "",
            ),
            "status"        => array(
                "label"      => "status",
                "type"       => "int", "length" => "24", "kolom" => "status",
                "inputType"  => "combo",
                "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
                //--"inputName" => "status",
            ),
        );
        $this->listedFieldsSelectFolder = array("nama", "jenis");
        $this->listedFieldsSelectItem = array("nama", "folder", "satuan", "jenis", "harga");
        $this->listedFieldsViewFolder = array("nama");
        $this->listedFieldsViewItem = array("nama", "harga", "satuan");
        $this->listedFieldsFormFolder = array("nama");
        $this->listedFieldsFormItem = array("nama", "folder", "satuan", "harga");
        $this->validationRules = array(
            "nama"   => array("required", "singleOnly"),
            "status" => array("required"),
        );
        $this->kategori_id = null != $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $this->listedFieldsHidden = array("id", "jenis");

        if (null != $this->uri->segment(3) && (!is_numeric($this->uri->segment(3)))) {

            $this->kategori = $this->uri->segment(3);
            $this->listedFieldsForm = $this->listedFieldsFormItem;
        }
        else {
            $this->kategori = "folder";
            $this->listedFieldsForm = $this->listedFieldsFormFolder;
        }


        $this->session->unset_userdata('search');
        if (null != $this->input->post('search')) {
            $this->search = $this->input->post('search');
            $this->session->set_userdata('search', $this->search);
        }
        else {
            $this->search = $this->session->search;
        }
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsSelectFolder()
    {
        return $this->listedFieldsSelectFolder;
    }

    public function setListedFieldsSelectFolder($listedFieldsSelectFolder)
    {
        $this->listedFieldsSelectFolder = $listedFieldsSelectFolder;
    }

    public function getListedFieldsViewFolder()
    {
        return $this->listedFieldsViewFolder;
    }

    public function setListedFieldsViewFolder($listedFieldsViewFolder)
    {
        $this->listedFieldsViewFolder = $listedFieldsViewFolder;
    }

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    public function getListedFieldsViewItem()
    {
        return $this->listedFieldsViewItem;
    }

    public function setListedFieldsViewItem($listedFieldsViewItem)
    {
        $this->listedFieldsViewItem = $listedFieldsViewItem;
    }

    public function getListedFieldsFormFolder()
    {
        return $this->listedFieldsFormFolder;
    }

    public function setListedFieldsFormFolder($listedFieldsFormFolder)
    {
        $this->listedFieldsFormFolder = $listedFieldsFormFolder;
    }

    public function getListedFieldsFormItem()
    {
        return $this->listedFieldsFormItem;
    }

    public function setListedFieldsFormItem($listedFieldsFormItem)
    {
        $this->listedFieldsFormItem = $listedFieldsFormItem;
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

    public function getKategori()
    {
        return $this->kategori;
    }

    public function setKategori($kategori)
    {
        $this->kategori = $kategori;
    }

    public function getKategoriId()
    {
        return $this->kategori_id;
    }

    public function setKategoriId($kategori_id)
    {
        $this->kategori_id = $kategori_id;
    }

    public function getSearch()
    {
        return $this->search;
    }

    //</editor-fold>

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function simpanUx($employee_id, $param_key, $param)
    {
        // $employee_id = isset($);
        $other_condites = isset($this->condites) ? $this->condites : "";
        // lihat
        $condites = array(
                "employee_id" => $employee_id,
                // "default_nilai" => $param
            ) + $other_condites;
        $new_condites = array(
            "default_key"   => $param_key,
            "default_nilai" => $param,
        );
        $srcs = $this->lookupByCondition($condites)->result();
        showLast_query("kuning");
        // arrPrintKuning($srcs);

        if (count($srcs) == 0) {
            // belum ada insert
            $this->addData(($new_condites + $condites));
            // showLast_query("kuning");
        }
        else {
            // sudah ada update
            $updparams = array(
                "employee_id",
                "menu",
                "judul",
            );
            // arrPrintPink($condites);
            $updCondites = array_intersect_key($condites, array_flip($updparams));
            // arrPrintHijau($updCondites);
            $this->updateData($updCondites, $new_condites);
            // showLast_query("hijau");
        }
    }

    public function editBillInv(){
        $condites = array(
            "menu" => "edit_bill_invoice",
            // "default_nilai" => $param
        );
        $srcs = $this->lookupByCondition($condites)->result();
    }
}