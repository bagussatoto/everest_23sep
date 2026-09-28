<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/9/2018
 * Time: 8:03 PM
 */
class ComFifoBahan extends CI_Model
{
    private $tableName = "rek_cache_persediaan_bahan_fifo";
    private $inParams = array( //===inputan dari transaksi
    );
    private $outParams = array( //===output ke tabel
    );
    private $allowedOperatingModes = array("NEW", "UPDATE");
    private $operatingMode = null;
    private $requiredFields = array(
        "NEW" => array(
            "produk_id",
            "produk_nama",
            "cabang_id",
            "unit",
            "hpp",
            "jml_nilai",
            "transaksi_id",
            "transaksi_jenis",
        ),
        "UPDATE" => array(
            "produk_id",
            "produk_nama",
            "cabang_id",
            "unit_ot",
            "jml_nilai_ot",
            "transaksi_id",
            "transaksi_jenis",
        ),
    );

    //<editor-fold desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
    }

    public function getOperatingMode()
    {
        return $this->operatingMode;
    }

    public function setOperatingMode($operatingMode)
    {
        $this->operatingMode = $operatingMode;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
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

    //</editor-fold>

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function pair($inParams)
    {
        if ($this->operatingMode == null) {
            die("needed operatingMode to be set. Allowed ones are " . implode(",", $this->allowedOperatingModes));
        }
        if (!in_array($this->operatingMode, $this->allowedOperatingModes)) {
            die("invalid needed operatingMode. It should be " . implode(",", $this->allowedOperatingModes));
        }
        $this->outParams = array(
            "dtime_last" => date("Y-m-d H:i:s"),

        );
        foreach ($this->requiredFields[$this->operatingMode] as $fName) {
            if (!isset($inParams[$fName])) {
                die("param for $fName is not set, padahal dia required");
            }
            $this->outParams[$fName] = $inParams[$fName];
        }

        switch ($this->operatingMode) {
            case "NEW":
                break;
            case "UPDATE":
                break;
        }

    }

    public function exec()
    {
        print_r($this->outParams);
        die("EO outParams");
        $this->load->model("Mdls/MdlFifoBahan");
        $f = new MdlFifoBahan();
        $insertIDs = array();

    }

}