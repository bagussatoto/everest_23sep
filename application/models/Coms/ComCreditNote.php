<?php


class ComCreditNote extends MdlMother
{

    protected $filters = array();
    private $tableName;
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
        "amount",
        "used",
        "remain",
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
        "amount",
        "used",
        "remain",
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
        arrprint($this->inParams);

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $inSpec) {

                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;

                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);


                    $mode = $inSpec['static']['mode'];
                    switch ($mode) {
                        case "update":
                            if ($prev['id'] > 0) {
                                if ($prev['remain'] > 0) {
                                    if ($prev['remain'] >= $inSpec['static']['used']) {
                                        $jmlBayar = $inSpec['static']['used'];
                                    }
                                    else {
                                        $jmlBayar = $prev['remain'];
                                    }
                                    $newTerbayar = ($prev['used'] + $jmlBayar);
                                    $newSisa = ($prev['remain'] - $jmlBayar);

                                    $this->outParams[$lCounter]['where'] = array(
                                        "id" => $prev['id'],
                                        "cabang_id" => $inSpec['static']['cabang_id'],

                                    );
                                    $this->outParams[$lCounter][$mode] = array(
                                        "used" => $newTerbayar,
                                        "remain" => $newSisa,
                                    );
                                }
                            }

                            $result = true;

                            break;
                        case "insert":
                            foreach ($inSpec['static'] as $key => $val) {
                                if (in_array($key, $this->outFields)) {
                                    $this->outParams[$lCounter][$mode][$key] = $val;
                                }
                            }

                            if (sizeof($this->outParams) > 0) {
                                $result = true;
                            }
                            else {
                                $result = false;
                            }

                            break;
//                        default:
//                            break;
                    }


                }
            }
        }

//arrPrint($this->outParams);
//mati_disini();


        return $result;
    }

    private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID)
    {
        $this->load->model("Mdls/MdlCreditNote");
        $tr = new MdlCreditNote();
        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
        $tmpR = $tr->lookupAll()->result();
        cekHitam($this->db->last_query() . " # " . sizeof($tmpR));
        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,
                    "amount" => $row->amount,
                    "used" => $row->used,
                    "remain" => $row->remain,
                );
            }
        }
        else {
            $result = array(
                "id" => 0,
                "amount" => 0,
                "used" => 0,
                "remain" => 0,
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

        $this->load->model("Mdls/MdlCreditNote");
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $cnt => $oSpec) {
                arrPrint($oSpec);
                $tr = new MdlCreditNote();
                foreach ($oSpec as $mode => $pSpec) {
                    switch ($mode) {
                        case "update":
                            $tr->updateData($oSpec['where'], $oSpec['update']) or die("can not update CreditNote source");
                            break;
                        case "insert":
                            $tr->addData($pSpec) or die("can not add CreditNote source");
                            break;
//                        default:
//                            break;
                    }
                    cekHitam($this->db->last_query());
                }
            }

//            mati_disini("exec ATAS");
            return true;
        }
        else {
            cekUngu("exec tidak ngapa2in");
            return true;
        }


    }


}