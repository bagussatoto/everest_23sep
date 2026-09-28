<?php

/**
 * Class ComPaymentSrcUmItem
 * untuk insert per so karena
 */
class ComPaymentSrcUmItem extends MdlMother
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
        arrPrint($inParams);
//        matiHere();

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            $insertID = array();
            foreach ($this->inParams as $cnt => $inSpec) {
//                arrPrint($inSpec['static']);
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $jenis = isset($inSpec['static']['reverted_target']) ? $inSpec['static']['reverted_target'] : $inSpec['static']['target_jenis'];
//                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);
                    $x_value = isset($inSpec['static']['reverted_target']) ? -1 : 1;
                    $prev = $this->cekPreValue(
                        $inSpec['static']['target_jenis'],
                        $inSpec['static']['extern_id'],
                        $inSpec['static']['extern2_id'],
                        $inSpec['static']['cabang_id'],
                        $inSpec['static']['label']

                    );
                    $this->load->model("MdlTransaksi");
                    $tr = new MdlTransaksi();
                    if ($prev === null) {

                        //insertbaru

                        $insertID[]=$tr->writePaymentSrc($inSpec['static']['transaksi_id'], array(
                            "jenis" => $jenis,
                            "target_jenis" => $inSpec['static']['target_jenis'],
                            "reference_jenis" => $inSpec['static']['jenis'],
                            "extern_id" => $inSpec['static']['extern_id'],
                            "extern_nama" => $inSpec['static']['extern_nama'],
                            "nomer" => $inSpec['static']['transaksi_no'],
                            "label" => $inSpec['static']['label'],
                            "tagihan" => $inSpec['static']['sisa'],
                            "terbayar" => 0,
                            "sisa" => $inSpec['static']['sisa'],
                            "ppn" => $inSpec['static']['ppn'],
                            "cabang_id" => $inSpec['static']['cabang_id'],
                            "cabang_nama" => $inSpec['static']['cabang_nama'],
                            "oleh_id" => $this->session->login['id'],
                            "oleh_nama" => $this->session->login['nama'],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                        ));
                        cekHitam($this->db->last_query());
//                        matiHere(__LINE__);
                    } else {
                        //
                        $id_table = $prev["id"];
                        $preValue = $prev["sisa"];
                        $currValue = $inSpec['static']['sisa'] * $x_value;
                        $newValue = $preValue + $currValue;
                        $where = array(
                            "id" => $id_table,
                        );
                        $data = array(
                            "tagihan" => $newValue,
                            "sisa" => $newValue,
                        );
                        $insertID[]=$tr->updatePaymentSrc($where, $data);

                    }
                }
            }
        }

        if(count($insertID)>0){
            return true;
        }
        else{
            return false;
        }


    }

    private function cekPreValue($targetJenis, $externID, $extern2ID, $cabangID, $label)
    {


        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("label='$label'");
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("extern2_id='$extern2ID'");
        $tmpR = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        if (count($tmpR) > 0) {
        }

        if (sizeof($tmpR) > 0) {

            $result = array(
                "id" => $tmpR[0]->id,
                "tagihan" => $tmpR[0]->tagihan,
                "terbayar" => $tmpR[0]->terbayar,
                "returned" => $tmpR[0]->returned,
                "sisa" => $tmpR[0]->sisa,

            );
        } else {
            $result = null;
        }

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        return true;


    }


}