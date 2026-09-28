<?php

//--include_once "MdlHistoriData.php";

class MdlGudangDefault_center extends MdlMother
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='payment'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label"     => "name",
            "type"      => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),
        "nama" => array(
            "label"     => "nama",
            "type"      => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),

    );
    protected $staticData=array(


    );




    protected $listedFields = array(
        "nama"     => "name",
        "due_days" => "due days",
        "status"   => "status",

    );

    public function __construct()
    {
        $this->load->model("Mdls/MdlCabang");
        $o=new MdlCabang();
        $o->addFilter("id='-1'");
        $tmp=$o->lookupAll()->result();

        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $gudSpec=getDefaultWarehouseID($row->id);
                $tmpArr=array(
                    "id"=>$gudSpec['gudang_id'],
                    "name"=>$gudSpec['gudang_nama'],
                    "nama"=>$gudSpec['gudang_nama'],
                );
                $this->staticData[$row->id]=$tmpArr;
            }
        }

    }

    //region gs

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
    //endregion


    //@override with static data
    public function lookupAll()
    {
        if(isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData)>0){
//            cekkuning("ada isinya");
            $iCtr=0;
            $sql="";
//			arrprint($this->filters);
            foreach($this->staticData as $iSpec){
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr=0;
                foreach($this->fields as $fID=>$fSpec){
                    $fCtr++;
                    $sql .= "'".$iSpec[$fID]."' as $fID";
                    if($fCtr<sizeof($this->fields)){
                        $sql.=",";
                    }
                }
                if($iCtr<sizeof($this->staticData)){
                    $sql.=" union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }else{
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }

}