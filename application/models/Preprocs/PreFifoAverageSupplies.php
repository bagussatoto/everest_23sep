<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverageSupplies extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array(//        "hpp" => "hpp",
    );
    private $inParams;
    private $outParams;
    private $result;

    //<editor-fold desc="getter-setter">

    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
        $this->exceptionTr = array("9855", "9856");
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
        $this->load->model("Mdls/MdlFifoAverageSupplies");

//        arrPrint($inParams);
//        matiHere(__LINE__);

        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {
            $needles = array();
//            $needlesPIDs = array();
            $ids = array();
            $tmp = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
//arrPrint($pSpec);
                    $exception = isset($pSpec['exception']) ? $pSpec['exception'] : 0;
                    $jenisTransaksi = isset($pSpec['jenisTr']) ? $pSpec['jenisTr'] : 0;

                    if (in_array($jenisTransaksi, $this->exceptionTr) && ($exception == 1)) {
                        // boleh tetap dijalankan walaupun nol/0
//                        cekBiru($jenisTransaksi);
//                        cekBiru($exception);
                        $updaters[] = 1;
//                        matiHere(__LINE__);
                    }
                    else {
//                        cekBiru($jenisTransaksi);
//                        cekBiru($exception);
//                        matiHere(__LINE__);
                        // ini yang reguler
                    }

                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
//                    $needlesPIDs[$pSpec['extern_id']] = $pSpec['produk_ids'];

                    $ids[] = $pSpec["extern_id"];


                    $b = new MdlFifoAverageSupplies();
                    $b->addFilter("jenis='supplies'");
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                    $b->addFilter("produk_id='". $pSpec["extern_id"] ."'");
//                    $tmp = $b->lookupAll()->result();
                    $localFilters = array();
                    if (sizeof($b->getfilters()) > 0) {
                        foreach ($b->getfilters() as $f) {
                            $tmpArr = explode("=", $f);
                            $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                        }
                    }
                    $query = $this->db->select()
                        ->from($b->getTableName())
                        ->where($localFilters)
                        ->limit(1)
                        ->get_compiled_select();
                    $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                    $tmp[] = (object)$subtmp;
                }
            }


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

                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needles[$row->produk_id])),
                        );
                    }
                }

                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                        $b = new MdlFifoAverageSupplies();
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
        }
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
//            cekHitam(count($updaters));
//            matiHere("empty response");
            if(count($updaters)>0){
                return true;
            }
            else{
                return false;
            }

        }
    }

    public function exec()
    {
        return $this->result;
    }
}