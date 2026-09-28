<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PrePriceJual extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array(
        "harga" => "harga_baru",
    );
    private $inParams;
    private $outParams;
    private $result;

    //<editor-fold desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
    }

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
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

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $sentParams)
    {
        if (!is_array($sentParams)) {
            die("params required!");
        }
        $this->load->model("Mdls/MdlHarga");
        $b = new MdlHarga();
        $ids = array_column($sentParams, "id");
//        $b->addFilter("jenis='item'");
        $b->addFilter("id in (" . implode(",", $ids) . ")");

        $tmp = $b->lookupAll()->result();

        if (sizeof($tmp) > 0) {
            $patchers = array();
            foreach ($tmp as $row) {
                foreach ($this->resultParams as $key => $val) {
                    $patchers[$row->id][$key] = $row->$val;
                }
            }
            $this->result = $patchers;
        } else {
            $this->result = array();
        }

    }

    public function exec()
    {
        return $this->result;
    }
}