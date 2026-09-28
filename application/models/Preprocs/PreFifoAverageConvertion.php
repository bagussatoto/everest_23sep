<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverageConvertion extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

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

    public function pair($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        $updatePairs = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $ids[] = $pSpec["extern_id"];
                }
            }


            $this->load->model("Mdls/MdlFifoAverageConvertion");
            $b = new MdlFifoAverageConvertion();
            $b->addFilter("jenis='produk'");
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
            $tmp = $b->lookupAll()->result();
            cekBiru($this->db->last_query() . " # " . sizeof($tmp));

            if (sizeof($tmp) > 0) {
                $patchers = array();
                foreach ($tmp as $row) {

                    foreach ($this->resultParams as $gateName => $paramSpec) {

                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$row->produk_id][$key] = $row->$val;
                        }
                    }

                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needles[$row->produk_id]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needles[$row->produk_id])),
                            "jml_nilai_riil" => ($row->jml_nilai_riil - ($row->hpp_riil * $needles[$row->produk_id])),
                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needles[$row->produk_id])),
                        );
                    }
                }

                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageConvertion();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }

                $this->result = $patchers;

            }
            else {
                $this->result = array();
            }

            if (sizeof($updatePairs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }

    public function exec()
    {
        return $this->result;
    }
}