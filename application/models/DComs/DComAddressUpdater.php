<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2019
 * Time: 4:03 PM
 */
class DComAddressUpdater extends MdlMother
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "address";
        $this->jenis = array(
            "bill" => "jenis", "shipment" => "jenis",
        );
        $this->connectedUpdater = array(
            "bill" => "MdlCustomerAddress",
            "shipment" => "MdlCustomerBillAddress",
        );
    }

    private $inParams = array( //===inputan dari transaksi

    );

    private $writeMode;

    private $outParams = array( //===output ke tabel

    );

    private $outFields = array( // dari tabel percustomer ke address
        "id" => "extern_id",
        "nama" => "alias",
        "alamat_1" => "alamat",
        "tlp_1" => "tlp",
        "tlp_2" => "tlp_2",
        "kelurahan" => "kelurahan",
        "kecamatan" => "kecamatan",
        "kabupaten" => "kabupaten",
        "propinsi" => "propinsi",
        "kode_pos" => "kodepos",
        "no_ktp" => "no_ktp",
        "npwp" => "npwp",
    );

    private $selectUpdateFields = array(
        "npwp", "no_ktp",
    );


    //region geter setter
    public function getSelectUpdateFields()
    {
        return $this->selectUpdateFields;
    }

    public function setSelectUpdateFields($selectUpdateFields)
    {
        $this->selectUpdateFields = $selectUpdateFields;
    }

    public function getWriteMode()
    {
        return $this->writeMode;
    }

    public function setWriteMode($writeMode)
    {
        $this->writeMode = $writeMode;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    //endregion

    public function pair($inParam)
    {
        arrPrint($inParam);

        $mode = isset($this->writeMode) ? $this->writeMode : "edit";
        cekHere("$mode");
        $clasNames = $this->connectedUpdater;

        switch ($mode) {
            case "insert":

                foreach ($this->jenis as $value => $kol) {
                    $clasName = $clasNames[$value];
                    $temp = array();

                    foreach ($this->outFields as $kolom => $alias) {
                        if (isset($inParam[$kolom])) {
                            $temp[$alias] = $inParam[$kolom];
                            $temp[$kol] = $value;
                        }
                    }
                    $temp['main_used'] = "1";

                    $this->load->model("Mdls/" . $clasName);
                    $m = new $clasName();
                    $insertIDs[] = $m->addData($temp);
                    cekHere($this->db->last_query());
                }
//                matiHere();
                break;
            default:
                $update = array();
                $ids = $inParam['id'];
                foreach ($this->outFields as $kolom => $alias) {
                    if (isset($inParam[$kolom])) {
                        $update[$alias] = $inParam[$kolom];
                    }
                }
                if (isset($update['extern_id'])) {
                    unset($update['extern_id']);
                    unset($update['extern_nama']);
                    unset($update['alias']);
                }
                $cleanData = array_filter($update);

                arrPrint($cleanData);
                if (sizeof($cleanData) > 0) {
                    foreach ($this->connectedUpdater as $mdlName) {
                        $this->load->model("Mdls/" . $mdlName);
                        $o = new $mdlName();
                        $where = array(
                            "extern_id" => "$ids",
                            "main_used" => "1",
                        );
                        $o->updateData($where, $cleanData);
                        cekHitam($this->db->last_query());
                    }
                }

                break;
        }
        return true;
    }

    public function cekPreValue($id)
    {

    }


    public function exec()
    {
        return true;

    }
}