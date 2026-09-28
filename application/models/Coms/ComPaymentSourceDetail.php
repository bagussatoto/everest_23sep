<?php


class ComPaymentSourceDetail extends MdlMother
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
            foreach ($this->inParams as $cnt => $inSpec) {

                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    foreach($inSpec['static'] as $col =>$val){
                        if(in_array($col,$this->outFields)){
                            $this->outParams[$lCounter][$col]=$val;
                        }
                    }
                    $this->writeMode = "new";
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

    private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID)
    {


        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
//        $tmpR = $tr->lookupPaymentSrcByTransID($transaksiID)->result();
//        arrPrint($tmpR);
//        cekHitam($this->db->last_query() . " # " . sizeof($tmpR));
        $result = array();
        $localFilters = array();
        if (sizeof($tr->getFilters()) > 0) {
            foreach ($tr->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }

        $query = $this->db->select()
            ->from($tr->getTableNames()['paymentSrc'])
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
        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();
        if (sizeof($this->outParams) > 0) {
            arrPrint($this->outParams);
            $insertIDs = array();
            switch ($this->writeMode){
                case "new":
                    foreach ($this->outParams as $cnt => $oSpec) {
                      $insertIDs[] = $l->addData($oSpec);
                      cekHitam($this->db->last_query());;
                    }
                    break;
                default :
                    $insertIDs=array();
                    break;
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


    }


}