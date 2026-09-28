<?php


class ComUangMukaValasSourceMain extends MdlMother
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
        "nomer",
        "extern2_id",
        "extern2_nama",
        "extern_label2",
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
        "extern2_id",
        "extern2_nama",
    );
    private $writeMode;


    public function getWriteMode()
    {
        return $this->writeMode;
    }


    public function setWriteMode($writeMode)
    {
        $this->writeMode = $writeMode;
    }


    public function __construct()
    {

    }

    public function pair($inParams)
    {

        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {

            $lCounter = 0;
//            foreach ($this->inParams as $cnt => $inSpec) {
            $inSpec = $this->inParams;
            cekPink2("cetak inSpec");
            arrPrintWebs($inSpec);
            $lCounter++;
            if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                foreach ($inSpec['static'] as $col => $val) {
                    if (in_array($col, $this->outFields)) {
                        $this->outParams[$lCounter][$col] = $val;
                    }
                }
            }


            $externID = isset($inSpec['static']['extern_id']) ? $inSpec['static']['extern_id'] : 0;
            $extern2ID = isset($inSpec['static']['extern2_id']) ? $inSpec['static']['extern2_id'] : 0;
            $label = isset($inSpec['static']['label']) ? $inSpec['static']['label'] : "";
            $cabangID = isset($inSpec['static']['cabang_id']) ? $inSpec['static']['cabang_id'] : 0;
            $extern2Label = isset($inSpec['static']['extern_label2']) ? $inSpec['static']['extern_label2'] : "";
            $nilai = isset($inSpec['static']['nilai']) ? $inSpec['static']['nilai'] : "";
            $nilai_valas = isset($inSpec['static']['nilai_valas']) ? $inSpec['static']['nilai_valas'] : "";
            //-----------------------
            $terbayar = isset($inSpec['static']['terbayar']) ? $inSpec['static']['terbayar'] : 0;
            $terbayar_valas = isset($inSpec['static']['terbayar_valas']) ? $inSpec['static']['terbayar_valas'] : 0;

            $preValues = $this->cekPreValue($externID, $extern2ID, $label, $cabangID, $extern2Label);
            if (sizeof($preValues) > 0) {
                $preTerbayar = $preValues['terbayar'];
                $preTerbayarValas = $preValues['terbayar_valas'];
                $preSisa = $preValues['sisa'];
                $preSisaValas = $preValues['sisa_valas'];
                if ($nilai < 0) {
                    // mengurangi sisa, menambah terbayar
                    $new_terbayar = $preTerbayar + $terbayar;
                    $new_terbayar_valas = $preTerbayarValas + $terbayar_valas;
                    $new_sisa = $preSisa + $nilai;
                    $new_sisa_valas = $preSisaValas + $nilai_valas;
                }
                else {
                    // menambah sisa
                    $new_terbayar = $preTerbayar + $terbayar;
                    $new_terbayar_valas = $preTerbayarValas + $terbayar_valas;
                    $new_sisa = $preSisa + $nilai;
                    $new_sisa_valas = $preSisaValas + $nilai_valas;
                }

                $mode = "update";
                $this->outParams[$lCounter]['id'] = $preValues['id'];
                $this->outParams[$lCounter]['terbayar'] = $new_terbayar;
                $this->outParams[$lCounter]['terbayar_valas'] = $new_terbayar_valas;
                $this->outParams[$lCounter]['sisa'] = $new_sisa;
                $this->outParams[$lCounter]['sisa_valas'] = $new_sisa_valas;
            }
            else {
                $mode = "insert";
                $this->outParams[$lCounter]['tagihan'] = $nilai;
                $this->outParams[$lCounter]['tagihan_valas'] = $nilai_valas;
                $this->outParams[$lCounter]['terbayar'] = 0;
                $this->outParams[$lCounter]['terbayar_valas'] = 0;
                $this->outParams[$lCounter]['sisa'] = $nilai;
                $this->outParams[$lCounter]['sisa_valas'] = $nilai_valas;
            }
            $this->outParams[$lCounter]['mode'] = $mode;

//            }
        }


        return true;

    }

    private function cekPreValue($externID, $extern2ID, $label, $cabangID, $extern2Label)
    {

        $this->load->model("Mdls/MdlPaymentUangMukaValas");
        $tr = new MdlPaymentUangMukaValas();
        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("extern2_id='$extern2ID'");
        $tr->addFilter("extern_label2='$extern2Label'");
        $tmpR = $tr->lookupAll()->result();
//        arrPrint($tmpR);
        cekHitam($this->db->last_query() . " # " . sizeof($tmpR));
        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,

                    "tagihan" => $row->tagihan,
                    "terbayar" => $row->terbayar,
//                    "returned" => $row->returned,
                    "sisa" => $row->sisa,

                    "tagihan_valas" => $row->tagihan_valas,
                    "terbayar_valas" => $row->terbayar_valas,
                    "sisa_valas" => $row->sisa_valas,
                );
            }
        }
        else {
            $result = array();
        }

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        $this->load->model("Mdls/MdlPaymentUangMukaValas");
        $l = new MdlPaymentUangMukaValas();
        if (sizeof($this->outParams) > 0) {
            $insertIDs = array();
//            switch ($this->writeMode) {
//                case "new":
//                    foreach ($this->outParams as $cnt => $oSpec) {
//                        $insertIDs[] = $l->addData($oSpec);
//                        cekHitam($this->db->last_query());;
//                    }
//                    break;
//                default :
//                    $insertIDs = array();
//                    break;
//            }

            foreach ($this->outParams as $cnt => $oSpec) {
//                arrPrintWebs($oSpec);
                switch ($oSpec['mode']) {
                    case "insert":
                        unset($oSpec['mode']);
                        $insertIDs[] = $l->addData($oSpec);
                        break;
                    case "update":
                        $id = $oSpec['id'];
                        unset($oSpec['mode']);
                        unset($oSpec['id']);
                        $insertIDs[] = $l->updateData(
                            array(
                                "id" => $id,
                            ),
                            $oSpec);
                        break;
//                    default:
//                        mati_disini(get_class($this));
//                        break;
                }
                showLast_query("biru");
            }

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

//        mati_disini(get_class($this));
    }


}