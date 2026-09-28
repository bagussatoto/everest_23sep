<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverageOpname extends CI_Model
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

    private function normalizeDecimal($value, $scale = 10)
    {
        $value = (float)$value;
        $result = round($value, $scale);
        $epsilon = pow(10, -1 * $scale);
        if (abs($result) < $epsilon) {
            $result = 0;
        }
        return $result;
    }

    private function hasUnitValue($spec)
    {
        return isset($spec["produk_nilai"]) || isset($spec["harga"]) || isset($spec["hpp"]);
    }

    private function pickUnitValue($spec)
    {
        if (isset($spec["produk_nilai"])) {
            return (float)$spec["produk_nilai"];
        }
        if (isset($spec["harga"])) {
            return (float)$spec["harga"];
        }
        if (isset($spec["hpp"])) {
            return (float)$spec["hpp"];
        }
        return 0;
    }

    public function pair($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $needlesNilai = array();
        $needlesNilaiIsProvided = array();
        $ids = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    if($pSpec['produk_qty'] > 0){
                        $externID = $pSpec['extern_id'];
                        $qty = isset($pSpec['produk_qty']) ? (float)$pSpec['produk_qty'] : 0;
                        if (!isset($needles[$externID])) {
                            $needles[$externID] = 0;
                        }
                        $needles[$externID] += $qty;
                        if ($this->hasUnitValue($pSpec)) {
                            $unitValue = $this->pickUnitValue($pSpec);
                            if (!isset($needlesNilai[$externID])) {
                                $needlesNilai[$externID] = 0;
                            }
                            $needlesNilai[$externID] += ($qty * $unitValue);
                            $needlesNilaiIsProvided[$externID] = true;
                        }
                        $ids[] = $pSpec["extern_id"];
                    }
                }
            }

            if(sizeof($ids) > 0){

                $this->load->model("Mdls/MdlFifoAverage");
                $b = new MdlFifoAverage();
                $b->addFilter("jenis='produk'");
                $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
                $tmp = $b->lookupAll()->result();
                $updatePairs = array();
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
                            $qtyOut = isset($needles[$row->produk_id]) ? (float)$needles[$row->produk_id] : 0;
                            $hasValueOut = isset($needlesNilaiIsProvided[$row->produk_id]) && $needlesNilaiIsProvided[$row->produk_id];
                            $nilaiOut = $hasValueOut ? (float)$needlesNilai[$row->produk_id] : ((float)$row->hpp * $qtyOut);
                            $newJml = (float)$row->jml - $qtyOut;
                            $newNilai = (float)$row->jml_nilai - $nilaiOut;
                            if ($newJml == 0) {
                                $newNilai = 0;
                            }
                            $updatePairs[] = array(
                                "id" => $row->id,
                                "produk_id" => $row->produk_id,
                                "jml" => $newJml,
                                "jml_nilai" => $this->normalizeDecimal($newNilai),
                            );
                        }
                    }

                    if (sizeof($updatePairs) > 0) {
                        foreach ($updatePairs as $upSpec) {
                            $updateData = $upSpec;
                            unset($updateData["id"]);
                            $b = new MdlFifoAverage();
                            $b->updateData(array("id" => $upSpec['id']), $updateData);
                            cekMerah($this->db->last_query());
                        }
                    }

                    $this->result = $patchers;
                    print_r($patchers);
                }
                else {
                    $this->result = array();
                }

                if (sizeof($updatePairs) > 0) {
                    cekHijau(":: true ::");
                    return true;
                }
                else {
                    cekHitam(":: false ::");
                    return false;
                }
            }
            else{
                cekHijau(":: true :: to be bypass ::");
                return true;
            }

        }
    }

    public function exec()
    {
        return $this->result;
    }
}
