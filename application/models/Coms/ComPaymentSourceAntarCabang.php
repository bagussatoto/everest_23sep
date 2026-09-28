<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPaymentSourceAntarCabang extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
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
        "tagihan_valas",
        "terbayar_valas",
        "returned_valas",
        "sisa_valas",
        "ppn",
        "ppn_approved",
        "ppn_sisa",
        "ppn_status",
        "ppn_pph_faktor",
        "extern_nilai2",
        "target_jenis",
        "reference_jenis",
        "nomer",
        "extern_nilai2",
        "npwp",
        "oleh_id",
        "oleh_nama",
        "extern2_id",
        "extern2_nama",
        "extern_date2",
        "extern_label2",
        "customers_id",
        "customers_nama",

    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;

        if (sizeof($this->inParams['static']) > 0) {
            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;

            if($this->inParams['static']['srcValue']>0){
                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
            }
            else{
                cekHitam("sini 0");
            }

            if(sizeof($this->outParams)>0){
                $this->load->model("Mdls/MdlPaymentSource");
                $l = new MdlPaymentSource();
                $insertIDs = array();
                foreach($this->outParams as $k =>$val){
                    $insertIDs[]= $l->addData($val);
                }
            }
            else{
                ceklime("tidak nulis paymentsource antar cabang kareana nilai 0");

            }
            // arrPrint($this->inParams['static']);
            // arrPrint($this->outParams);
            // cekHitam($this->db->last_query());
        }

        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return true;
        }
    }
    public function exec()
    {
        return true;

    }
}