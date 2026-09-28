<?php

//--include_once "MdlHistoriData.php";

class MdlLockerValue extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array(
        "jenis_locker='value'",
//        "jenis='produk'",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama", "satuan",
    );

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }


    function __construct()
    {
        parent::__construct();
        $this->tableName = "stock_locker_value";
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
        );
        $this->listedFieldsView = array();
        $this->listedFieldsForm = array();
        $this->validationRules = array();
        $this->listedFieldsHidden = array();


    }

    public function cekLoker($cab, $prod, $state, $oleh = 0, $transaksi_id = 0, $gudang_id, $jenis)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("produk_id='$prod'");
        $this->addFilter("state='$state'");
        $this->addFilter("jenis='$jenis'");
        if ($oleh != 0) {
            $this->addFilter("oleh_id='$oleh'");
        }
        if ($transaksi_id > 0) {
            $this->addFilter("transaksi_id='$transaksi_id'");
        }
        $tmp = $this->lookupAll()->result();
        cekMerah($this->db->last_query());

        if (sizeof($tmp) > 0) {
            return array(
                "id" => $tmp[0]->id,
                "nilai" => $tmp[0]->nilai,
            );
        }
        else {
            return array();
        }
    }

    public function fetchStates2($cab, $gudang_id, $ids = 0)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        if (is_array($ids) && sizeof($ids) > 0) {
            $this->addFilter("produk_id in (" . implode(",", $ids) . ")");
        }
        $tmp = $this->lookupAll()->result();
//cekBiru($this->db->last_query());

        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $pID = $row->produk_id;

                if ($row->transaksi_id > 0) {
                    if (!isset($results[$pID][$row->state . "_trID"])) {
                        $results[$pID][$row->state . "_trID"] = 0;
                    }
                    $results[$pID][$row->state . "_trID"] += $row->nilai;
                }
                else {
                    if (!isset($results[$pID][$row->state])) {
                        $results[$pID][$row->state] = 0;
                    }
                    $results[$pID][$row->state] += $row->nilai;
                }

            }
        }
        else {
            $results = array();
        }
        return $results;
    }


}