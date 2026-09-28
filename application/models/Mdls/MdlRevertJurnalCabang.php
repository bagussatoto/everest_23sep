<?php


class MdlRevertJurnalCabang extends MdlMother
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='payment'",
//        "status='1'",
//        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "id_master" => array(
            "label" => "id_master",
            "type" => "int", "length" => "24", "kolom" => "id_master",
            "inputType" => "text",
        ),
        "value_src" => array(
            "label" => "value_src",
            "type" => "int", "length" => "255", "kolom" => "value_src",
            "inputType" => "text",
        ),
        "revertStep" => array(
            "label" => "revertStep",
            "type" => "int", "length" => "3", "kolom" => "revertStep",
            "inputType" => "text",
        ),
        "revertReference" => array(
            "label" => "revertReference",
            "type" => "int", "length" => "11", "kolom" => "revertReference",
            "inputType" => "text",
        ),

    );
    protected $staticData = array(
        //------
//        array(
//            "id" => "999",//di isi jenis
//            "id_master" => "999",//di isi jenis master
//            "nama" => "ADJUSTMENT",
//            "value_src" => "nett",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
        //------

        //authorization depresiasi
//        array(
//            "id" => "8787",//di isi jenis
//            "id_master" => "8787",//di isi jenis
//            "nama" => "authorization depresiasi",
//            "value_src" => "subtotal", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //Penerimaan Penjualan Tunai
        array(
            "id" => "4464",//di isi jenis
            "id_master" => "4464",//di isi jenis
            "nama" => "PENERIMAAN PENJUALAN TUNAI",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),
        //uang muka konsumen
        array(
            "id" => "4467",//di isi jenis
            "id_master" => "4467",//di isi jenis
            "nama" => "UANG MUKA",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

        //AR Receipt
        array(
            "id" => "749",//di isi jenis
            "id_master" => "749",//di isi jenis
            "nama" => "AR RECEIPT",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

        //SALES PROJECT
//        array(
//            "id" => "588so",//di isi jenis
//            "id_master" => "588",//di isi jenis
//            "nama" => "SALES PROJECT",
//            "value_src" => "nett", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//            "detailSubGate" => null,// membalikkan fg
//            "detailSumGate" => null,// membalikkan po service
//        ),

        //SALES PROJECT PACKINGLIST
//
//        array(
//            "id" => "588spd",//di isi jenis
//            "id_master" => "588",//di isi jenis
//            "nama" => "SALES PROJECT PACKINGLIST",
//            "value_src" => "nett", // harga
//            "revertStep" => true,
//            "detailGate" => null,
//            "detailSubGate" => "items2_sum",// membalikkan fg
//            "detailSumGate" => "rsltItems3_sub",// membalikkan po service
//        ),

//        //---- pembatalan SO POS
//        array(
//            "id" => "5823so",//di isi jenis
//            "id_master" => "5823",//di isi jenis
//            "nama" => "POS",
//            "value_src" => "hpp", // harga
//            "revertStep" => false,
////            "revertStep" => true,
//            "detailGate" => null,
//        ),

        //PACKINGLIST
        array(
            "id" => "5822spd",//di isi jenis
            "id_master" => "5822",//di isi jenis
            "nama" => "PACKINGLIST",
            "value_src" => "hpp", // harga
//            "revertStep" => false,
            "revertStep" => true,
            "revertReference" => true,
            "detailGate" => null,
        ),
        array(
            "id" => "5823spd",//di isi jenis
            "id_master" => "5823",//di isi jenis
            "nama" => "PACKINGLIST POS",
            "value_src" => "hpp", // harga
//            "revertStep" => false,
            "revertStep" => true,
            "revertReference" => true,
            "detailGate" => null,
        ),
        //PROJECT RUNNING
        array(
            "id" => "588st",//di isi jenis
            "id_master" => "588",//di isi jenis
            "nama" => "PROJECT",
            "value_src" => "grandTotal", // harga
//            "revertStep" => false,
            "revertStep" => true,
            "detailGate" => null,
        ),

        //RETURN PENJUALAN
        array(
            "id" => "9822",//di isi jenis
            "id_master" => "9822",//di isi jenis
            "nama" => "RETURN PENJUALAN",
            "value_src" => "hpp", // harga
            "revertStep" => false,
//            "revertStep" => true,
            "detailGate" => null,
        ),


//        array(
//            "id" => "582spd",//di isi jenis
//            "id_master" => "582",//di isi jenis
//            "nama" => "PACKINGLIST",
//            "value_src" => "hpp", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //INVOICING
//        array(
//            "id" => "582",//di isi jenis
//            "id_master" => "582",//di isi jenis
//            "nama" => "INVOICING",
//            "value_src" => "nilai_tambah_ppn_out", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
        //-------------------------------------
        //UANG MUKA (DP DENGAN PPN)
//        array(
//            "id" => "4465",//di isi jenis
//            "id_master" => "4465",//di isi jenis
//            "nama" => "UANG MUKA (DP DENGAN PPN)",
//            "value_src" => "harga", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //Termin project
        array(
            "id" => "7499",//di isi jenis
            "id_master" => "7499",//di isi jenis
            "nama" => "TERMIN",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

        // PENERIMAAN RETENSI
        array(
            "id" => "7488",//di isi jenis
            "id_master" => "7488",//di isi jenis
            "nama" => "PENERIMAAN PEMBAYARAN RETENSI PROJECT",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

        //UANG BELUM TERIDENTIFIKASI
        array(
            "id" => "7444",//di isi jenis
            "id_master" => "7444",//di isi jenis
            "nama" => "UANG MUKA BELUM TERIDENTIFIKASI",
            "value_src" => "harga", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),

        //UANG MUKA (DP TANPA PPN)
//        array(
//            "id" => "4464",//di isi jenis
//            "id_master" => "4464",//di isi jenis
//            "nama" => "UANG MUKA (DP TANPA PPN)",
//            "value_src" => "harga", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        //reception distribution service project
//        array(
//            "id" => "3465",//di isi jenis
//            "id_master" => "3465",//di isi jenis
//            "nama" => "RECEPTION DISTRIBUTION SERVICE PROJECT",
////            "nama" => "RECEPTION DISTRIBUTION SVC PROJ from HO",
//            "value_src" => "harga_disc", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//-------------------------------------

//        array(
//            "id" => "687",//di isi jenis
//            "id_master" => "687",//di isi jenis
//            "nama" => "re-stok (into active warehouse)",
//            "value_src" => "hpp", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),
//-------------------------------------

//        array(
//            "id" => "334",//di isi jenis
//            "id_master" => "334",//di isi jenis
//            "nama" => "PRODUCT CONVERSION (BRANCH)",
//            "value_src" => "hpp", // harga
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

//-------------------------------------

//        array(
//            "id" => "585",//di isi jenis
//            "id_master" => "585",//di isi jenis master
//            "nama" => "STOCK RECEPTION (DISTRIBUTION)",
//            "value_src" => "hpp",
//            "revertStep" => false,
//            "detailGate" => null,
//        ),

        // 6677
        array(
            "id" => "6677",//di isi jenis
            "id_master" => "6677",//di isi jenis
            "nama" => "CASHBACK PENJUALAN (BIAYA) HADIAH BARANG",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),
        // 7468
        array(
            "id" => "7468",//di isi jenis
            "id_master" => "7468",//di isi jenis
            "nama" => "PENERIMAAN UANG UNTUK PEMBAYARAN PAJAK/BIAYA DARI HADIAH DAN SEJENISNYA",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),
        // 66771
        array(
            "id" => "66771",//di isi jenis
            "id_master" => "66771",//di isi jenis
            "nama" => "PENYERAHAN PRODUK HADIAH",
            "value_src" => "nilai_bayar", // harga
            "revertStep" => false,
            "detailGate" => null,
        ),


    );


    protected $listedFields = array(
        "nama" => "nama",
//        "due_days" => "due days",
        "status" => "status",

    );

    public function __construct()
    {

    }

    //region gs

    public function getStaticData()
    {
        return $this->staticData;
    }

    public function setStaticData($staticData)
    {
        $this->staticData = $staticData;
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
    //endregion


    //@override with static data
    public function lookupAll()
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }

    public function lookupByKeyword($key)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->filters);
            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }
    }

    public function lookupByID($id)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("ada isinya:: $id");
            $iCtr = 0;
            $sql = "";
//			arrprint($this->fields);
            $tmp = array();
            foreach ($this->staticData as $aSpec) {
                $arrNew = array();
//                arrPrintWebs($aSpec);
//                if (in_array($id, $aSpec)) {
                if ($aSpec['id'] == $id) {
                    foreach ($this->fields as $fID => $fSpec) {
                        $arrNew[$fID] = $aSpec[$fID];
                    }
                    $tmp[] = $arrNew;
                }

            }

            foreach ($tmp as $iSpec) {
                if (in_array($id, $iSpec)) {
//                    arrPrintPink($iSpec);
                    $iCtr++;
                    $sql .= 'SELECT ';
                    $fCtr = 0;
                    foreach ($this->fields as $fID => $fSpec) {
//                        cekHere($fID);
                        $fCtr++;
                        $sql .= "'" . $iSpec[$fID] . "' as $fID";
                        if ($fCtr < sizeof($this->fields)) {
                            $sql .= ",";
                        }
                    }
                    if ($iCtr < sizeof($tmp)) {
                        $sql .= " union ";
                    }
                }

            }

            return $this->db->query($sql);

        }
        else {
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }
}