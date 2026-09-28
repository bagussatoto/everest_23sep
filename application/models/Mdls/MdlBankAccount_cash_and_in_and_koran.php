<?php

class MdlBankAccount_cash_and_in_and_koran extends MdlMother
{
    protected $tableName = "bank";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis<>'account_out'",
        "jenis in ('account_cash','account_in')",
//        "jenis2<>'2'",
//        "jenis<>'bank'",
        "folders>'0'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "kode" => array("required"),
        "swift" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "folder" => array(
            "label" => "folder",
            "type" => "int", "length" => "24", "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlBank",
            //--"inputName" => "folders",
        ),
        "cabang" => array(
            "label" => "branch",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            "referenceFilter" => "",
        ),
        "nama" => array(
            "label" => "nama bank/nomor rekening",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),

//        "kode"  => array(
//            "label"      => "kode",
//            "type"       => "int", "length" => "24", "kolom" => "kode",
//            "inputType"  => "combo",
//            "dataSource" => array(
//                "0"   => "0-cash-",
//                "14"  => "14BANK BCA",
//                "8"   => "8BANK MANDIRI",
//                "9"   => "9BANK BNI",
//                "427" => "427BANK BNI SYARIAH",
//                "2"   => "2BANK BRI",
//                "451" => "451BANK SYARIAH MANDIRI",
//                "22"  => "22BANK CIMB NIAGA",
//                "22"  => "22BANK CIMB NIAGA SYARIAH",
//                "147" => "147BANK MUAMALAT",
//                "213" => "213BANK TABUNGAN PENSIUNAN NASIONAL (BTPN)",
//                "213" => "213JENIUS",
//                "422" => "422BANK BRI SYARIAH",
//                "200" => "200BANK TABUNGAN NEGARA (BTN)",
//                "13"  => "13PERMATA BANK",
//                "11"  => "11BANK DANAMON",
//                "16"  => "16BANK BII MAYBANK",
//                "426" => "426BANK MEGA",
//                "153" => "153BANK SINARMAS",
//                "950" => "950BANK COMMONWEALTH",
//                "28"  => "28BANK OCBC NISP",
//                "441" => "441BANK BUKOPIN",
//                "536" => "536BANK BCA SYARIAH",
//                "26"  => "26BANK LIPPO",
//                "31"  => "31CITIBANK",
//                "789" => "789INDOSAT DOMPETKU",
//                "911" => "911TELKOMSEL TCASH",
//
//            ),
//        ),
//        "swift" => array(
//            "label"      => "swift",
//            "type"       => "int", "length" => "24", "kolom" => "swift",
//            "inputType"  => "combo",
//            //--"inputName" => "swift",
//            "dataSource" => array(
//                "0"        => "0-cash-",
//                "ABNAIDJA" => "ABNAIDJA",
//                "HAGAIDJA" => "HAGAIDJA",
//                "ARTGIDJA" => "ARTGIDJA",
//                "BKKBIDJA" => "BKKBIDJA",
//                "BUMIIDJA" => "BUMIIDJA",
//                "BBAIIDJA" => "BBAIIDJA",
//                "BBIJIDJA" => "BBIJIDJA",
//                "BBUKIDJA" => "BBUKIDJA",
//                "BNPAIDJA" => "BNPAIDJA",
//                "CENAIDJA" => "CENAIDJA",
//                "CTCBIDJA" => "CTCBIDJA",
//                "BKCHIDJA" => "BKCHIDJA",
//                "BICNIDJA" => "BICNIDJA",
//                "BDINIDJA" => "BDINIDJA",
//                "DEUTIDJA" => "DEUTIDJA",
//                "DBSBIDJA" => "DBSBIDJA",
//                "BEXIIDJA" => "BEXIIDJA",
//                "EKONIDJA" => "EKONIDJA",
//                "FINBIDJA" => "FINBIDJA",
//                "HSBCIDJA" => "HSBCIDJA",
//                "INDOIDJA" => "INDOIDJA",
//                "IBBKIDJA" => "IBBKIDJA",
//                "AWANIDJA" => "AWANIDJA",
//                "LIPBIDJA" => "LIPBIDJA",
//                "BMRIIDJA" => "BMRIIDJA",
//                "MBBEIDJA" => "MBBEIDJA",
//                "MEGAIDJA" => "MEGAIDJA",
//                "MHCCIDJA" => "MHCCIDJA",
//                "NISPIDJA" => "NISPIDJA",
//                "BNIAIDJA" => "BNIAIDJA",
//                "BNINIDJA" => "BNINIDJA",
//                "OCBCIDJA" => "OCBCIDJA",
//                "BBBAIDJA" => "BBBAIDJA",
//                "PINBIDJA" => "PINBIDJA",
//                "RABOIDJA" => "RABOIDJA",
//                "BPIAIDJA" => "BPIAIDJA",
//                "BRINIDJA" => "BRINIDJA",
//                "SUNIIDJA" => "SUNIIDJA",
//                "SWBAIDJA" => "SWBAIDJA",
//                "BSMDIDJA" => "BSMDIDJA",
//                "BTANIDJA" => "BTANIDJA",
//                "SAINIDJA" => "SAINIDJA",
//                "UOBBIDJA" => "UOBBIDJA",
//                "HVBKIDJA" => "HVBKIDJA",
//
//            ),
//        ),
        "alias" => array(
            "label" => "a.n. pemegang rekening",
            "type" => "int", "length" => "24", "kolom" => "alias",
            "inputType" => "text",
            //--"inputName" => "alias",
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
        "folders" => "folder",
        "nama" => "name",
        //        "alamat_1"=>"address",
        "alias" => "account holder",


    );

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



}