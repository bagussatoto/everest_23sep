<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PrePembantuCustomer extends CI_Model
{
    private $requiredParams = array(
    );
    private $resultParams = array(//        "hpp" => "hpp",
    );
    private $inParams;
    private $outParams;
    private $result;

    public function __construct()
    {
        parent::__construct();
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

        if (sizeof($inParams) > 0) {
            $sentParams = $inParams;


            $this->load->model("Coms/ComRekeningPembantuCustomer");

            //region cek yang aktif
            $b = new ComRekeningPembantuCustomer();
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("extern_id='" . $sentParams['static']['extern_id'] . "'");
            $tmp = $b->fetchBalances($sentParams['static']['jenis']);

            cekMerah($this->db->last_query());
            //endregion

            $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);
            if (sizeof($tmp) > 0) {
                if($tmp[0]->debet > 0){
                    $preNumber = detectRekByPosition($sentParams['static']['jenis'], $tmp[0]->debet, "debet");
                }
                else{
                    $preNumber = detectRekByPosition($sentParams['static']['jenis'], $tmp[0]->kredit, "kredit");
                }
                $patchers = array();
                if ($preNumber > 0) {
                    if ($sentParams['static']['nilai'] <= $preNumber) {
                        $nilai_dipakai = $sentParams['static']['nilai'];
                        $nilai_tambah = "0";
                    }
                    else {
                        $nilai_dipakai = $preNumber;
                        $nilai_tambah = ($sentParams['static']['nilai'] - $preNumber) > 0 ? ($sentParams['static']['nilai'] - $preNumber) : "0";
                    }
                }
                else {
                    $nilai_dipakai = "0";
                    $nilai_tambah = $sentParams['static']['nilai'];
                }

                $patchers["main"]["nilai_dipakai_".$patchersKey] = $nilai_dipakai;
                $patchers["main"]["nilai_tambah_".$patchersKey] = $nilai_tambah;

                $this->result = $patchers;

            }
            else {
                cekBiru("tidak ada nilai rekeningnya...");

                $patchers["main"]["nilai_dipakai_".$patchersKey] = "0";
                $patchers["main"]["nilai_tambah_".$patchersKey] = $sentParams['static']['nilai'];

                $this->result = $patchers;
            }
        }
        else {
            $this->result = array();
        }

cekHere("cetak patchers pembantu customers...");
arrPrint($patchers);
//mati_disini(get_class($this));

        if (sizeof($this->result) > 0) {
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