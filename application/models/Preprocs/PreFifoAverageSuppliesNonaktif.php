<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverageSuppliesNonaktif extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array(
        "hpp" => "hpp",
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

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {
            $needles = array();
            $ids = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $ids[] = $pSpec["extern_id"];
                }
            }
            $this->load->model("Mdls/MdlFifoAverageSuppliesNonaktif");
            $b = new MdlFifoAverageSuppliesNonaktif();
            $b->addFilter("jenis='supplies_nonaktif'");
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
//            $b->addFilter("produk_id='" . $sentParams['static']['extern_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
            $tmp = $b->lookupAll()->result();

            $updatePairs = array();
            if (sizeof($tmp) > 0) {

                $patchers = array();
                foreach ($tmp as $row) {
                    foreach ($this->resultParams as $key => $val) {
                        $patchers[$row->produk_id][$key] = $row->$val;
                    }
                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needles[$row->produk_id]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needles[$row->produk_id])),

                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needles[$row->produk_id])),
                        );
                    }
                }

                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageSuppliesNonaktif();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }

                $this->result = $patchers;
//            arrPrint($patchers);
            }
            else {
                $this->result = array();
            }
        }
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function exec()
    {
        return $this->result;
    }
}