<?php


class ComPaymentSrcItemUpdateFaktur extends MdlMother
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
        "extern_label2",
        "realisasi_kurang",
        "realisasi_netto",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "transaksi_ref_id",
        "transaksi_ref_no",
        "dpp_ppn",
        "extern_date2",
        "extern_kode2",
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
        "extern_date2",
        "extern_label2",
        "extern_kode2",
        "realisasi_kurang",
        "realisasi_netto",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
    );


    public function __construct()
    {
        $this->jenisPembatalan = array(9911, 9912);
    }


    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
//arrPrint($this->inParams);
            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {

                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $jenis = isset($inSpec['static']['reverted_target']) ? $inSpec['static']['reverted_target'] : $inSpec['static']['jenis'];
                    $jenisPembatalan = isset($inSpec['static']['jenisPembatalan']) ? $inSpec['static']['jenisPembatalan'] : NULL;
//                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);

                    if (isset($inSpec['static']['lewati']) && $inSpec['static']['lewati'] == 1) {
                        return true;
                    }

                    $prev = $this->cekPreValue(
                        $jenis,
                        $inSpec['static']['transaksi_id'],
                        $inSpec['static']['label'],
                        $inSpec['static']['cabang_id'],
                        $inSpec['static']['tabel_id']
                    );
//                    showLast_query("biru");
                    //upate only data faktur tidak adayang lain
                    if ($prev['id'] != NULL) {
                        $this->outParams[$lCounter]['where'] = array(
                            "id" => $prev['id'],
                            "cabang_id" => $inSpec['static']['cabang_id'],

                        );
                        $this->outParams[$lCounter]['update'] = array(
                            "transaksi_ref_id" => isset($inSpec['static']['transaksi_ref_id']) ? $inSpec['static']['transaksi_ref_id'] : 0,
                            "transaksi_ref_no" => isset($inSpec['static']['transaksi_ref_no']) ? $inSpec['static']['transaksi_ref_no'] : 0,
                            "dpp_ppn"=>isset($inSpec['static']['dpp_ppn']) ? $inSpec['static']['dpp_ppn'] : 0,
                            "ppn_approved"=>isset($inSpec['static']['ppn_approved']) ? $inSpec['static']['ppn_approved'] : 0,
                            "extern_date2"=>isset($inSpec['static']['extern_date2']) ? $inSpec['static']['extern_date2'] : 0,
                            "extern_kode2"=>isset($inSpec['static']['extern_kode2']) ? $inSpec['static']['extern_kode2'] : 0,
                        );
                    }
                }
            }
        }
//arrPrintHitam($this->outParams);
//cekHitam("force_exec: ");
        if(isset($force_exec) && ($force_exec == 1)){
            return true;
        }
        else{
            if (sizeof($this->outParams) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    private function cekPreValue($targetJenis, $transaksiID, $label, $cabangID, $tabelID)
    {


        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
//        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
        if (($tabelID != null) || ($tabelID != 0)) {
            $tr->addFilter("id='$tabelID'");
        }
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
                    "dihapus" => $row->dihapus,
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
                "dihapus" => 0,
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

                $tr = new MdlTransaksi();
                $insID = $tr->updatePaymentSrc($oSpec['where'], $oSpec['update']) or die("can not update paymentSrc");
//                cekUngu(":: efek update tabel: $insID ::" . $this->db->last_query());
                if ($insID == 0) {
                    $msg = "Transaksi Gagal disimpan karena jumlah pembayaran anda 0. Silahkan dikoreksi lagi atau hubungi admin. code: " . __LINE__;
                    mati_disini($msg);
                }
                $insertIDs[] = $insID;

            }

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
//            return false;
            return true;
        }


    }


}

