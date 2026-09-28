<?php


class ComPaymentAntiSrcItem extends MdlMother
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
        "id", "jenis", "target_jenis", "reference_jenis", "transaksi_id", "extern_id", "extern_nama", "nomer", "label", "tagihan", "terbayar", "sisa", "cabang_id", "cabang_nama", "oleh_id", "oleh_nama", "dtime", "fulldate",
    );
    private $koloms = array(
        "id", "jenis", "target_jenis", "reference_jenis", "transaksi_id", "extern_id", "extern_nama", "nomer", "label", "tagihan", "terbayar", "sisa", "cabang_id", "cabang_nama", "oleh_id", "oleh_nama", "dtime", "fulldate",
    );


    public function __construct()
    {

    }


    public function pair($inParams)
    {

        $this->inParams = $inParams;
        arrprint($this->inParams);

        if (sizeof($this->inParams) > 0) {

            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {

                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);
                    cekHitam("pre-value");
                    arrprint($prev);

                    if (isset($inSpec['static']['terbayar'])) {
                        $targetKey = "terbayar";
                        $targetVal = $inSpec['static']['terbayar'];
                    }
                    else {
                        if (isset($inSpec['static']['diskon'])) {
                            $targetKey = "diskon";
                            $targetVal = $inSpec['static']['diskon'];
                        }
                    }

                    if ($prev['id'] > 0 && $prev['sisa'] > 0) {
                        if ($prev['sisa'] >= $targetVal) {
                            $jmlBayar = $targetVal;
                        }
                        else {
                            $jmlBayar = $prev['sisa'];
                        }
                        $newTerbayar = ($prev[$targetKey] + $jmlBayar);
                        $newSisa = ($prev['sisa'] - $jmlBayar);

                        $this->outParams[$lCounter]['where'] = array(
                            "id" => $prev['id'],
                            "cabang_id" => $inSpec['static']['cabang_id'],

                        );
                        $this->outParams[$lCounter]['update'] = array(
                            $targetKey => $newTerbayar,
                            "sisa" => $newSisa,
                        );
                    }
                }
            }
        }

        return true;
//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
    }

    private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID)
    {
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
//        $tmpR = $tr->lookupPaymentAntiSrcByTransID($transaksiID)->result();
//
//        cekHitam($this->db->last_query() . " # " . sizeof($tmpR));
//
        $result = array();
        $localFilters = array();
        if (sizeof($tr->getFilters()) > 0) {
            foreach ($tr->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }

        $query = $this->db->select()
            ->from($tr->getTableNames()['paymentAntiSrc'])
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();

        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,
                    "tagihan" => $row->tagihan,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                    "diskon" => $row->diskon,
                    "sisa" => $row->sisa,
                );
            }
        }
        else {
            $result = array(
                "id" => 0,
                "tagihan" => 0,
                "terbayar" => 0,
                "returned" => 0,
                "diskon" => 0,
                "sisa" => 0,
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
            foreach ($this->outParams as $cnt => $oSpec) {
                $tr = new MdlTransaksi();
                $tr->updatePaymentAntiSrc($oSpec['where'], $oSpec['update']) or die("can not update paymentSrc");
                cekHitam($this->db->last_query());
            }
            return true;
        }
        else {
            return true;
        }


    }


}