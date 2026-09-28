<?php

class MdlEdc extends MdlMother
{
    protected $tableName = "bank";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='edc'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama"      => array("required", "singleOnly"),
        "machine_id"      => array("required","singleOnly"),
//        "kode"      => array("required"),
//        "swift"     => array("required"),
//        "cabang_id" => array("required"),
        "folders"   => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"       => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "machine_id"     => array(
            "label"     => "ID EDC/Serial",
            "type"      => "text", "length" => "24", "kolom" => "machine_id",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "folder"   => array(
            "label"      => "Relasi Rekening bank",
            "type"       => "int", "length" => "24", "kolom" => "folders",
            "inputType"  => "combo",
            "reference"  => "MdlBankAccount_in",
            //--"inputName" => "folders",
            "strField"   => "nama",
            "editable"   => false,
            "kolom_nama" => "folders_nama",
            "add_btn"    => true,
        ),
        "folders_nama"     => array(
            "label"      => "jenis",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "folders_nama",
            "inputType"  => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),

        "nama"     => array(
            "label"     => "Alias",
            "type"      => "text", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "biaya_persen"     => array(
            "label"     => "biaya(%)",
            "type"      => "int", "length" => "24", "kolom" => "biaya_persen",
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
//        "alias"    => array(
//            "label"     => "alias",
//            "type"      => "int", "length" => "24", "kolom" => "alias",
//            "inputType" => "text",
//            //--"inputName" => "alias",
//        ),
//        "currency" => array(
//            "label"     => "currency",
//            "type"      => "varchar", "length" => "24", "kolom" => "currency_id",
//            "inputType" => "combo",
//            "reference" => "MdlCurrency",
//        ),
        "status"   => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );
    protected $listedFields = array(
        "id"   => "pid",
        "machine_id"   => "id edc/serial",
        "folders_nama"   => "relasi rekening bank",
        "nama"      => "alias",
        "biaya_persen"      => "biaya",
        //        "alamat_1"=>"address",
//        "alias"     => "account holder",
//        "cabang_nama" => "branch",

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

    /*----------------------------------------------------------------
     * auto penambahan COA, bisa dugunakan keperluan lain
     * konnecting ke model yg lain
     * ----------------------------------------------------------*/

//    protected $connectingData = array(
//        "MdlAccounts" => array(
//            "path"          => "Mdls",
//            "fungsi"        => "addExtern_coa",
//            /* ------------------- ------------------- -------------------
//             * staticOptions bisa handling array atau singgle
//             * ---------------------------------------------------------*/
//            "staticOptions" => "1010010010",
//            "fields"        => array(
//                "extern_jenis"   => array(
//                    "str" => "rekening_in",
//                ),
//                "extern_id"      => array(
//                    "var_main" => "mainInsertId",
//                ),
//                "rekening"      => array(
//                    "var_main" => "mainInsertId",
//                ),
//                "head_name"      => array(
//                    "var_main" => "nama",
//                ),
//                "p_head_name"    => array(
//                    "var_main" => "strHead_code",
//                ),
//                "create_by"      => array(
//                    "var_main" => "my_name",
//                ),
//                /* -------------------------------------------------
//                 * filter yg ingin langsung diaktifkan
//                 * -------------------------------------------------*/
//                "is_active"      => array(
//                    "str" => "1",
//                ),
//                "is_transaction" => array(
//                    "str" => "1",
//                ),
//                "is_hutang" => array(
//                    "str" => "1",
//                ),
//                "is_gl" => array(
//                    "str" => "1",
//                ),
//            ),
//            "updateMain"    => array(
//                "condites" => array(
//                    "id" => "mainInsertId",
//                ),
//                "datas"    => array(
//                    "coa_code" => "lastInset_code",
//                )
//            )
//        )
//    );
//
//    public function getConnectingData()
//    {
//        return $this->connectingData;
//    }
//
//    public function setConnectingData($connectingData)
//    {
//        $this->connectingData = $connectingData;
//    }
    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlBankAccount_in"   => array(
                "id"         => "folders",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "folders_nama",
                    "id" => "folders",
                ),
            ),

        );

        return $mdls;

    }
}