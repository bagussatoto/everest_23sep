<?php


class MdlProdukPerSerialNumberLocker extends MdlMother
{
    protected $tableName = "produk_per_serialnumber_locker";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        //        "nama" => "produk.nama",
        //        "kode" => "produk.kode",
        //        "keterangan" => "produk.keterangan",
    );
    protected $search;
    protected $filters = array(
        "produk_per_serialnumber_locker.status='1'",
        "produk_per_serialnumber_locker.trash='0'",
    );
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "ASC",
    );
    protected $validationRules = array(
//        "serial_number" => array("required", "singleOnly"),
        //        "suppliers_id" => array("required"),
    );
    protected $validateData = array(//        "serial_number",
    );
    protected $conditional;

    public function getConditional()
    {
        return $this->conditional;
    }

    public function setConditional($conditional)
    {
        $this->conditional = $conditional;
    }

    public function getValidateData()
    {
        return $this->validateData;
    }

    public function setValidateData($validateData)
    {
        $this->validateData = $validateData;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    protected $listedFieldsView = array("serial_number");

    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "produk id" => array(
            "label" => "produk",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProduk",
            "name2" => "nama",
            //            "name2" => "keterangan",
            //--"inputName" => "nama",
        ),
        "serial" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_serial_number",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_nama",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "sku" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_sku",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );

    protected $listedFields = array(
        //        "folders"    => "folder",
        "produk_sku" => "sku",
        //        "label"      => "label",
        "produk_id" => "pID",
        "produk_nama" => "produk nama",
        "produk_serial_number" => "SN",
    );

    //<editor-fold desc="getter and setter">
    public function getTableName2()
    {
        return $this->tableName2;
    }

    public function setTableName2($tableName2)
    {
        $this->tableName2 = $tableName2;
    }

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

    //</editor-fold>

    public function dataCek($search)
    {
        $criteria = array(
            "produk_serial_number" => $search,
            "status" => 1,
            "trash" => 0,
        );
        $this->db->where($criteria);

        $criteria1 = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria1 = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria1) > 0) {
            $this->db->where($criteria1);
        }

        $resultDB = $this->db->get($this->tableName)->result_array();

        if (sizeof($resultDB) > 0) {
            $result = $resultDB;// ada data...
        }
        else {
            $result = array();// tidak ada data...
        }

        return $result;
    }

    public function lookupJoinSearchProdukSerial($key)
    {
        $tbl_1 = $this->tableName;
        $tbl_2 = "produk";

        $koloms = array(
            "$tbl_1.produk_serial_number",// serial dari pabrik
            "$tbl_1.produk_serial_number_2",// serial dari hasil generate system
            "$tbl_2.barcode"
        );
        $condites = array(
            "$tbl_1.status" => 1,
            "$tbl_1.trash" => 0,
//            "$tbl_1.produk_serial_number" => $key,// serial dari pabrik
        );
        $condites2 = array(
            "$tbl_1.produk_serial_number" => $key,// serial dari pabrik
            "$tbl_1.produk_serial_number_2" => $key,// serial dari hasil generate system
        );
        $this->db->where($condites);
        $this->db->group_start();
        $this->db->or_where($condites2);
        $this->db->group_end();
        $this->db->join($tbl_2, "$tbl_1.produk_id = $tbl_2.id", 'right');
        $resultDB = $this->db->get($tbl_1);

        return $resultDB;
    }

    public function lookupJoinProduk($key = "")
    {
        $tbl_1 = $this->tableName;
        $tbl_2 = "produk";
        if ($key != "") {
            $koloms = array(
                "$tbl_1.produk_serial_number",
                "$tbl_2.barcode"
            );
            $this->createSmartSearch($key, $koloms);
        }
        $this->db->join($tbl_2, "$tbl_1.produk_id = $tbl_2.id", 'right');
        $resultDB = $this->db->get($tbl_1);

        return $resultDB;
    }

    //-------------------------------


}