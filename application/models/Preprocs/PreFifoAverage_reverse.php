<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverage_reverse extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $outFields = array( // dari tabel rek_cache
        "jenis",
        "produk_id",
        "nama",
        "cabang_id",
        //        "satuan",
        //        "state",
        "jml",
        "hpp",
        "jml_nilai",
        "gudang_id",
    );

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
        cekKuning("STARTING... " . get_class($this));
        if (!is_array($inParams)) {
            die("params required!");
        }
        if (sizeof($inParams) > 0) {

            $updatePairs = array();
            $lCounter = 0;
            foreach ($inParams as $sentParams) {
                arrPrint($sentParams);
                $lCounter++;
                foreach ($sentParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
//                        $_preValues = $this->cekPreValue($sentParams['static']['jenis'], $sentParams['static']['cabang_id'], $sentParams['static']['extern_id'], $sentParams['static']['gudang_id']);
                        $_preValues = $this->cekPreValue("produk", $sentParams['static']['cabang_id'], $sentParams['static']['extern_id'], $sentParams['static']['gudang_id']);

                        $mode = "";
                        if (sizeof($_preValues) > 0) {
                            $mode = "update";
                            $updatePairs[$lCounter][$mode][$key] = $value;

                            $_preValues_id = $_preValues['id'];
                            $_temp_qtt = $_preValues['jml'] + $sentParams['static']['produk_qty'];
//                            $_temp_nilai = $_preValues['jml_nilai'] + ($sentParams['static']['produk_qty'] * $sentParams['static']['produk_nilai']);
                            $_temp_nilai = $_preValues['jml_nilai'] + ($sentParams['static']['produk_qty'] * $sentParams['static']['hpp']);
                            $produk_hpp_avg = $_temp_nilai / $_temp_qtt;
//cekUngu(":: $_temp_qtt :: ". $sentParams['static']['produk_qty'] ." :: ". $_preValues['jml'] ." ::");


                            $updatePairs[$lCounter][$mode]["id"] = $_preValues_id;
                            $updatePairs[$lCounter][$mode]["jml"] = $_temp_qtt;
                            $updatePairs[$lCounter][$mode]["hpp"] = $produk_hpp_avg;
                            $updatePairs[$lCounter][$mode]["jml_nilai"] = $_temp_nilai;
                        }
                    }
                }
            }

            if (sizeof($updatePairs) > 0) {
                $this->load->model("Mdls/MdlFifoAverage");
                $fa = New MdlFifoAverage();

                $insertIDs = array();
                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        foreach ($upSpec as $mode => $pSpec_mode) {
                            switch ($mode) {
                                case "insert":
                                    $insertIDs[] = $fa->addData($pSpec_mode);

                                    cekBiru("$mode :: " . $this->db->last_query());
                                    break;

                                case "update":
                                    $id = $pSpec_mode['id'];
                                    unset($pSpec_mode['id']);

                                    $where = array("id" => "$id");
                                    $insertIDs[] = $fa->updateData($where, $pSpec_mode);


                                    cekOrange("$mode :: " . $this->db->last_query());
                                    break;
                            }
                        }
                    }
//                    if (sizeof($insertIDs) > 0) {
//                        return true;
//                    }
//                    else {
//                        return false;
//                    }
                }
//                else {
//                    return false;
//                }
            }


            cekKuning(":: $mode ::");
            arrPrint($updatePairs);
//            mati_disini(get_class($this));


            if (sizeof($updatePairs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $gudang_id)
    {
        $this->load->model("Mdls/MdlFifoAverage");
        $fa = New MdlFifoAverage();

        //  region filter dan query
        $fa->addFilter("cabang_id='$cabang_id'");
        $fa->addFilter("gudang_id='$gudang_id'");
        $fa->addFilter("produk_id='$produk_id'");
        $fa->addFilter("jenis='$jenis'");
        $tmp_result = $fa->lookupAll()->result();
        //  endregion filter dan query


        if (sizeof($tmp_result) > 0) {
            cekMerah("ada isinya...");
//            $final_result = $tmp_result;
            foreach ($tmp_result as $row) {
                $final_result = array(
                    "id" => $row->id,
                    "jml" => $row->jml,
                    "jml_nilai" => $row->jml_nilai,
                );
            }
        }
        else {
            cekMerah("tidak ada isinya...");
            $final_result = null;
        }

        return $final_result;
    }

    public function exec()
    {
        return $this->result;
    }
}