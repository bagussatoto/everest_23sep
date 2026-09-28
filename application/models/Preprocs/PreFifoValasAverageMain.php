<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoValasAverageMain extends CI_Model
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
       // arrPrintWebs($inParams);
// matiHEre();
        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        $total_kebutuhan = 0;
        if (sizeof($inParams) > 0) {
            $arrParams[0] = $inParams;
            foreach ($arrParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $total_kebutuhan += $pSpec['produk_qty'];

                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $ids[] = $pSpec["extern_id"];

                    if (isset($pSpec['cash_methode']) && ($pSpec['cash_methode'] == "valas")) {
                        $run_fifo = true;
                    }
                    elseif (isset($pSpec['cash_methode']) && ($pSpec['cash_methode'] == "cash")) {
                        $run_fifo = false;
                    }
                    else {
                        $run_fifo = true;
                    }
                }
            }
            // cekHitam("total kebutuhan: $total_kebutuhan");
            // arrPrintWebs($ids);

            if ($run_fifo == true) {
                if ($total_kebutuhan > 0) {
                    // validasi jenis valas -> USD, RMB, -> harus ada, tidak ada maka dimatikan (macem-macem saja)
                    if (sizeof($ids) == 0) {
                        $msg = "Valas yang akan digunakan belum ditentukan. Silahkan ditentukan dahulu.";
                        mati_disini($msg);
                    }

                    $this->load->model("Mdls/MdlFifoValasAverage");
                    $b = new MdlFifoValasAverage();
                    $b->addFilter("jenis='valas'");
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                    $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
                    $tmp = $b->lookupAll()->result();
                    cekLime($this->db->last_query());
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
                                $updatePairs[] = array(
                                    "id" => $row->id,
                                    "produk_id" => $row->produk_id,
                                    "jml" => ($row->jml - $needles[$row->produk_id]),
                                    "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needles[$row->produk_id])),
                                );
                            }
                        }


                        if (sizeof($updatePairs) > 0) {
                            foreach ($updatePairs as $upSpec) {
                                $updateData = $upSpec;
                                unset($updateData["id"]);
                                $b = new MdlFifoValasAverage();
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
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else{
                    return true;
                }
            }
            else {
                cekPink2("fifo valas tidak running karena methode BUKAN valas");
                return true;
            }
        }
    }

    public function exec()
    {
        return $this->result;
    }
}