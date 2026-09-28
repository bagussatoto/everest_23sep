<?php

//--include_once "MdlHistoriData.php";

class MdlLockerStockBooking extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array(
        "jenis_locker='stock'",
        "stock_locker.jenis='produk'",
    );
    protected $sortBy = array(
        "kolom" => "jumlah",
        "mode" => "DESC",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
//        "stock_locker.nama",
//        "stock_locker.satuan",

        "produk.id",
        "produk.nama",
        "produk.barcode",
        "produk.kode",
//        "produk.keterangan",
        "produk.label",
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
        $this->tableName = "stock_locker_booking";
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

    #override
//    public function lookupAll()
//    {
//        $criteria = array();
//        $criteria2 = "";
//        if (sizeof($this->filters) > 0) {
//            $this->fetchCriteria();
//            $criteria = $this->getCriteria();
//            $criteria2 = $this->getCriteria2();
//        }
//        if (sizeof($criteria) > 0) {
//            $this->db->where($criteria);
//        }
//        if ($criteria2 != "") {
//            $this->db->where($criteria2);
//        }
//        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
//            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
////            cekkuning("sorting by ".$this->sortBy['kolom']);
//        }
//
//        $res = $this->db->get($this->tableName);
//        $this->db->join('produk', "produk.id = ".$this->tableName.".produk_id ");
////        cekkuning($this->db->last_query());
//        return $res;
//
//    }


    public function lookupByKeyword($key)
    {

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }


        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        $this->createSmartSearch($key, $this->listedFieldsSelectItem);
        $this->db->join('produk', "produk.id = " . $this->tableName . ".produk_id ");
        $result = $this->db->get($this->tableName);


        return $result;
    }

    // START OF COMPLETE REPEATED LOGIC
    /* ----------------------------------------------------
     * method ini tidak bisa mengcover order paket
     * -------------------------------------------------*/
    public function getStokBooking()
    {
        $tbl_1 = "transaksi";
        $tbl_2 = "transaksi_data";
        $arrJenisTr = array("5822so", "5823so");

        $selected = array(
            "sum(valid_qty) as 'sum_valid_qty'",
            "produk_id",
            "produk_nama",
            "$tbl_1.cabang_id",
            "$tbl_1.gudang_status_id",
            "$tbl_1.gudang_status_nama",
        );
        $this->db->select($selected);
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id", 'inner');

        $condites = array(
            "$tbl_1.trash_4" => "0",
//            "$tbl_1.jenis" => "5822so",
            "$tbl_2.valid_qty>" => 0,
            "$tbl_2.next_substep_code!=" => "",
        );
        $this->db->where($condites);
        $this->db->where_in("$tbl_1.jenis", $arrJenisTr);

        // $this->db->group_by("produk_id");
        $this->db->group_by("$tbl_1.cabang_id, produk_id, gudang_status_id");
        $query = $this->db->get()->result_array();

        $queries = array();
        foreach ($query as $item) {
            $cabang_id = $item["cabang_id"];
            $produk_id = $item["produk_id"];
            $gudang_id = $item["gudang_status_id"];

            $queries[$cabang_id][$produk_id][$gudang_id] = $item;
            if (!isset($queries[$produk_id][$gudang_id])) {
                $queries[$produk_id][$gudang_id] = $item;
                $queries[$produk_id][$gudang_id]["sum_valid_qty"] = 0;
            }
            $queries[$produk_id][$gudang_id]["sum_valid_qty"] += $item["sum_valid_qty"];
        }

        return $queries;
    }
    // END OF COMPLETE REPEATED LOGIC

    public function getStokBookingByPoduk($pid, $gstatus)
    {
        $tbl_1 = "transaksi";
        $tbl_2 = "transaksi_data";
        $arrJenisTr = array("5822so", "5823so");

        $selected = array(
            "sum(valid_qty) as 'sum_valid_qty'",
            "produk_id",
            "produk_nama",
            "transaksi_id",
            "$tbl_1.gudang_status_id",
            "$tbl_1.gudang_status_nama",
            "$tbl_1.gudang_status_jenis",
            "$tbl_1.dtime",
            "$tbl_1.fulldate",
            "$tbl_1.customers_nama",
            "$tbl_1.salesman_nama",
            "$tbl_1.oleh_nama",
            "$tbl_1.nomer",
            "$tbl_1.counters",
            "$tbl_1.cabang_id",
            "$tbl_1.cabang_nama",
            "$tbl_1.jenis",
        );
        $this->db->select($selected);
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id", 'inner');

        $condites = array(
            "$tbl_1.trash_4" => "0",
//            "$tbl_1.jenis" => "5822so",
            "$tbl_1.gudang_status_id" => $gstatus,
            "$tbl_2.valid_qty>" => 0,
            "$tbl_2.next_substep_code!=" => "",
            "$tbl_2.produk_id" => $pid,
        );
        $this->db->where($condites);
        $this->db->where_in("$tbl_1.jenis", $arrJenisTr);
        // $this->db->group_by("produk_id");
        $this->db->group_by("transaksi_id, gudang_status_id");
//        $this->db->group_by("transaksi_id");
//        $this->db->group_by("gudang_status_id");
        $query = $this->db->get()->result_array();

        foreach ($query as $item) {
            $transaksi_id = $item["transaksi_id"];
            $produk_id = $item["produk_id"];
            $gudang_id = $item["gudang_status_id"];

            $queries[$transaksi_id][$gudang_id] = $item;
        }

        return $queries;
    }


}