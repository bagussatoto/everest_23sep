<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class PrePaymentSourceUmProject extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $result;
    private $outFields = array( // dari tabel rek_cache
        "tagihan",
        "terbayar",
        "returned",
        "sisa",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "transaksi_id",
        "jenis",
        "label",
    );

    //<editor-fold desc="getter and setter">
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

    public function getWriteMode()
    {
        return $this->writeMode;
    }

    public function setWriteMode($writeMode)
    {
        $this->writeMode = $writeMode;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    //</editor-fold>


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $sentParams)
    {
        if (!is_array($sentParams)) {
            die("params required!");
        }
        $inParams = $sentParams;
        arrPrintWebs($inParams);
        if ($inParams['static']['nilai'] > 0) {
            $nilai = $inParams['static']['nilai'];
            $cabang_id = $inParams['static']['cabang_id'];
            $extern_label2 = $inParams['static']['extern_label2'];
            $label = $inParams['static']['label'];
            $extern_id = $inParams['static']['extern_id'];// customer id atau supplier id
            $extern2_id = $inParams['static']['extern2_id'];// referensi nota, SO atau PO

            $this->load->model("Mdls/MdlPaymentSource");
            $b = new MdlPaymentSource();
            $b->addFilter("label='$label'");
            $b->addFilter("cabang_id='$cabang_id'");
            $b->addFilter("extern_id='$extern_id'");
            $b->addFilter("extern2_id='$extern2_id'");
//            $b->addFilter("extern_label2='$extern_label2'");
            $tmp = $b->lookupAll()->result();
            showLast_query("biru");
            if (sizeof($tmp) > 0) {
                $sisa = $tmp[0]->sisa;
                $ppn_sisa = $tmp[0]->ppn_sisa;

                if ($nilai > $sisa) {
                    $nilai_dipakai = $sisa;
                    $sisa_ppn = ($ppn_sisa > 0) ? ($sisa * 0.11) : 0;
                    $nilai_tambah = $nilai - $sisa - $sisa_ppn;
                    $nilai_tambah_ui = ($sisa_ppn > 0) ? $nilai_tambah : 0;
                }
                else {
                    $nilai_dipakai = $nilai;
                    $sisa_ppn = ($ppn_sisa > 0) ? ($nilai * 0.11) : 0;
                    $nilai_tambah = $sisa - $nilai - $sisa_ppn;
                    $nilai_tambah_ui = ($sisa_ppn > 0) ? $nilai_tambah : 0;
                }
                $result = array(
                    "pym_src_dipakai" => $nilai_dipakai,
                    "pym_src_ppn_dipakai" => $sisa_ppn,
                    "pym_src_total_dipakai" => $nilai_dipakai + $sisa_ppn,
                    "pym_src_tambah" => $nilai_tambah,
                    "pym_src_tambah_ui" => $nilai_tambah_ui,
                );
            }
            else {
                $result = array(
                    "pym_src_dipakai" => 0,
                    "pym_src_ppn_dipakai" => 0,
                    "pym_src_total_dipakai" => 0,
                    "pym_src_tambah" => $nilai,
                    "pym_src_tambah_ui" => 0,
                );
            }

        }
        else {
            $result = array(
                "pym_src_dipakai" => 0,
                "pym_src_ppn_dipakai" => 0,
                "pym_src_total_dipakai" => 0,
                "pym_src_tambah" => 0,
                "pym_src_tambah_ui" => 0,
            );
        }

        $patchers = array();
        foreach ($this->resultParams as $gateName => $paramSpec) {
            foreach ($paramSpec as $key => $val) {
                $patchers[$gateName][$key] = $result[$key];
            }
        }

        $this->result = $patchers;
        return true;
    }


    public function exec()
    {
        return $this->result;
    }
}