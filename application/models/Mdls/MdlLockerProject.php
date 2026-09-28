<?php


class MdlLockerProject extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array(
        "stock_locker_project.jenis_locker='transaksi'",
        "stock_locker_project.jenis='transaksi'",
    );
    protected $sortBy = array(
        "kolom" => "jumlah",
        "mode" => "DESC",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "stock_locker_project.nama",
//        "stock_locker_transaksi.satuan",
    );


    public function getTableName()
    {
        return $this->tableName;
    }


    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }


    function __construct()
    {
        parent::__construct();
        $this->tableName = "stock_locker_project";
        $this->indexFields = "id";
        $this->fields = array(
            "id" => array(
                "label" => "id",
                "type" => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "produk_id" => array(
                "label" => "produk_id",
                "type" => "int", "length" => "24", "kolom" => "produk_id",
                "inputType" => "text",// hidden
                //--"inputName" => "produk_id",
            ),
            "nama" => array(
                "label" => "nama",
                "type" => "int", "length" => "24", "kolom" => "nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),

            "jumlah" => array(
                "label" => "jumlah",
                "type" => "int", "length" => "24", "kolom" => "jumlah",
                "inputType" => "varchar",
                //--"inputName" => "jumlah",
            ),
            "satuan" => array(
                "label" => "satuan",
                "type" => "int", "length" => "24", "kolom" => "satuan",
                "inputType" => "varchar",
                //--"inputName" => "satuan",
            ),
            "barcode" => array(
                "label" => "satuan",
                "type" => "int", "length" => "24", "kolom" => "barcode",
                "inputType" => "varchar",
                //--"inputName" => "satuan",
            ),
        );
        $this->listedFieldsView = array();
        $this->listedFieldsForm = array();
        $this->validationRules = array();
        $this->listedFieldsHidden = array();

    }

    public function cekLoker($cab, $prod, $state, $oleh = 0, $transaksi_id = 0, $gudang_id)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("produk_id='$prod'");
        $this->addFilter("state='$state'");
        if ($oleh != 0) {
            $this->addFilter("oleh_id='$oleh'");
        }
        if ($transaksi_id > 0) {
            $this->addFilter("transaksi_id='$transaksi_id'");
        }
        $tmp = $this->lookupAll()->result();
//        cekBiru($this->db->last_query());
        if (sizeof($tmp) > 0) {
            return array(
                "id" => $tmp[0]->id,
                "jumlah" => $tmp[0]->jumlah,
            );
        }
        else {
            return array();
        }
    }

    public function fetchStates($cab, $gudang_id, $ids = 0)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("transaksi_id='0'");
        if (is_array($ids) && sizeof($ids) > 0) {

            $this->addFilter("produk_id in (" . implode(",", $ids) . ")");
        }


        $tmp = $this->lookupAll()->result();
//        cekBiru($this->db->last_query());

        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $pID = $row->produk_id;
//                if(!isset($results[$pID])){
//                    $results[$pID]=array(
//                        "active"=>0,
//                        "hold"=>0,
//                        "sold"=>0,
//                    );
//                }

                if (!isset($results[$pID][$row->state])) {
                    $results[$pID][$row->state] = 0;
                }

                $results[$pID][$row->state] += $row->jumlah;
            }
        }
        else {
            $results = array();
        }
        return $results;
    }

    public function fetchStates2($cab, $gudang_id, $ids = 0)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
//        $this->addFilter("transaksi_id='0'");
        if (is_array($ids) && sizeof($ids) > 0) {
            $this->addFilter("produk_id in (" . implode(",", $ids) . ")");
        }
        $tmp = $this->lookupAll()->result();
//        cekHitam($this->db->last_query());

        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $pID = $row->produk_id;

                if ($row->transaksi_id > 0) {
                    if (!isset($results[$pID][$row->state . "_trID"])) {
                        $results[$pID][$row->state . "_trID"] = 0;
                    }
                    $results[$pID][$row->state . "_trID"] += $row->jumlah;
                }
                else {
                    if (!isset($results[$pID][$row->state])) {
                        $results[$pID][$row->state] = 0;
                    }
                    $results[$pID][$row->state] += $row->jumlah;
                }

            }
        }
        else {
            $results = array();
        }
        return $results;
    }
    //----------------------------------------------------------------------------------
    //----EXEC LOCKER TRANSAKSI DOFOLLOWUP----------------------------------------------
    public function execLocker($mainGate, $nextStepNum, $refID, $newID)
    {
        if ($refID != NULL) {
            // meng-nol-kan HOLD oleh saya
            if (is_array($refID)) {
                $arrFilter = array(
                    "jenis='transaksi'",
                    "jenis_locker='transaksi'",
                    "state='hold'",
                    "oleh_id=" . $this->session->login['id'],
                    "jumlah>'0'",
                );
                $this->setFilters(array());
                foreach ($arrFilter as $f) {
                    $this->addFilter($f);
                }
                $this->addFilter("transaksi_id in ('" . implode("','", $refID) . "')");
            }
            else {
                $arrFilter = array(
                    "jenis='transaksi'",
                    "jenis_locker='transaksi'",
                    "state='hold'",
//                "cabang_id=" . $mainGate['placeID'],
                    "oleh_id=" . $this->session->login['id'],
                    "transaksi_id='$refID'",
                    "jumlah>'0'",
                );
                $this->setFilters(array());
                foreach ($arrFilter as $f) {
                    $this->addFilter($f);
                }
            }
            $tmpS = $this->lookupAll()->result();

            if (sizeof($tmpS) > 0) {
//                arrPrintWebs($tmpS);
                $where = array("id" => $tmpS[0]->id);
                $data = array("jumlah" => "0");
                $this->setFilters(array());
                $this->updateData($where, $data);
//                showLast_query("orange");
            }
        }

        if ($newID != NULL) {
            // newID active bila nextStepNum lebih dari 0
            if ($nextStepNum != 0) {
                $ltActive = array(
                    "state" => "active",
                    "produk_id" => $newID,
                    "transaksi_id" => $newID,
//                    "cabang_id" => $mainGate['placeID'],
                    "oleh_id" => 0,
                    "oleh_nama" => "",
                    "jenis" => "transaksi",
                    "jenis_locker" => "transaksi",
                    "jumlah" => "1",
                );
                $this->addData($ltActive);
//                showLast_query("hijau");
            }
        }


    }
}

