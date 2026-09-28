<?php


class ComPaymentSrcItem2 extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "tagihan",
        "terbayar",
        "sisa",
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );
    private $koloms = array(
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "tagihan",
        "terbayar",
        "sisa",
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );


    public function __construct()
    {

    }


    public function pair($inParams)
    {

        $this->inParams = $inParams;


        if (sizeof($this->inParams) > 0) {

            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
//                arrPrint($inSpec['static']);
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $jenis = isset($inSpec['static']['reverted_target']) ? $inSpec['static']['reverted_target'] : $inSpec['static']['target_jenis'];
//                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);
                    $prev = $this->cekPreValue($jenis, $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id'], $inSpec['static']['extern_id']);
//                    cekHitam($this->db->last_query());
//                    cekHitam("pre-value :: $jenis ::");
//                    arrPrint($prev);
//                    arrPrint($inSpec['static']);

                    if (isset($inSpec['static']['diskon'])) {
//                            $targetKey = "diskon";
                        $targetVal = $inSpec['static']['diskon'];
                    }
                    else {
                        $targetVal = 0;
                    }


                    //  bagian valas......
                    if ($prev['id'] > 0) {
                        if ($prev['sisa_valas'] >= $inSpec['static']['bayar_valas']) {
                            $qtyBayar = $inSpec['static']['bayar_valas'];
                        }
                        else {
                            $qtyBayar = $prev['sisa_valas'];
                        }
                        $qtyTerbayar = ($prev['terbayar_valas'] + $qtyBayar);
                        $newSisa_valas = ($prev['sisa_valas'] - $qtyBayar);

                        if ($newSisa_valas < 0) {
                            $msg = "Transaksi gagal, karena saldo valas tidak cukup. Silahkan diperiksa kembali transaksi ini.";
                            die(lgShowAlert($msg));
                        }

                    }


                    //  bagian reguler dengan rupiah......
                    if ($prev['id'] > 0) {
                        if ($prev['sisa'] >= $inSpec['static']['terbayar']) {
                            $jmlBayar = $inSpec['static']['terbayar'];
                        }
                        else {
                            $jmlBayar = $prev['sisa'];
                        }

                        $newTerbayar = ($prev['terbayar'] + $jmlBayar);
                        $newSisa = ($prev['sisa'] - $jmlBayar);
//                        $newSisa = $prev['sisa'];

                        if ($newSisa < 0) {
                            $msg = "Transaksi gagal, karena saldo tidak cukup. Silahkan diperiksa kembali transaksi ini.";
                            die(lgShowAlert($msg));
                        }
                        elseif ($newSisa == $prev['sisa']) {
                            if (($inSpec['static']['terbayar'] > 0)) {
                                $msg = "Transaksi gagal, karena tagihan tidak berkurang. Silahkan diperiksa kembali transaksi ini.";
                                die(lgShowAlert($msg));
                            }
                        }

                        $this->outParams[$lCounter]['where'] = array(
                            "id" => $prev['id'],
                            "cabang_id" => $inSpec['static']['cabang_id'],

                        );
                        $this->outParams[$lCounter]['update'] = array(
                            "terbayar" => $newTerbayar,
                            "diskon" => $targetVal,
                            "sisa" => $newSisa,

                            "terbayar_valas" => $qtyTerbayar,
                            "sisa_valas" => $newSisa_valas,
                        );
                    }


                }
            }
        }


        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID, $externID)
    {


        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpR = $tr->lookupPaymentSrcByTransID($transaksiID)->result();
//        arrPrint($tmpR);
//        cekHitam($this->db->last_query() . " # " . sizeof($tmpR));
//        die();
        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,
                    "tagihan" => $row->tagihan,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                    "sisa" => $row->sisa,
                    "tagihan_valas" => $row->tagihan_valas,
                    "terbayar_valas" => $row->terbayar_valas,
//                    "return_valas" => $row->return_valas,
                    "sisa_valas" => $row->sisa_valas,
                );
            }
        }
        else {
            $result = array(
                "id" => 0,
                "tagihan" => 0,
                "terbayar" => 0,
                "returned" => 0,
                "sisa" => 0,
                "tagihan_valas" => 0,
                "terbayar_valas" => 0,
                "returned_valas" => 0,
                "sisa_valas" => 0,
            );
        }

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {

        $this->load->model("MdlTransaksi");
        if (sizeof($this->outParams) > 0) {
            $insertIDs = array();
            foreach ($this->outParams as $cnt => $oSpec) {
//                arrPrint($oSpec);
//                $terbayar = $oSpec['update']['terbayar'];
//                $oSpec['update']['terbayar'] = 0;
//                $oSpec['update']['sisa'] = $terbayar;

                $tr = new MdlTransaksi();
                $insID = $tr->updatePaymentSrc($oSpec['where'], $oSpec['update']) or die("can not update paymentSrc");
                $insertIDs[] = $insID;
//                cekUngu(":: $insID ::");

//                $insertIDs[] = $tr->updatePaymentSrc($oSpec['where'], $oSpec['update']) or die("can not update paymentSrc");
//                cekHitam(get_class($this) . " :: " . $this->db->last_query());
            }


//            mati_disini("payment source stop dulu");
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }


    }


}