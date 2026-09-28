<?php


class ComPaymentSrcItem extends MdlMother
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
        "extern_date2",
        "extern_label2",
        "realisasi_kurang",
        "realisasi_netto",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
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

            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {

                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $jenis = isset($inSpec['static']['reverted_target']) ? $inSpec['static']['reverted_target'] : $inSpec['static']['target_jenis'];
                    $jenisPembatalan = isset($inSpec['static']['jenisPembatalan']) ? $inSpec['static']['jenisPembatalan'] : NULL;
//                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);

                    if ($inSpec['static']['lewati'] == 1) {
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
//                    showLast_query("merah");
//                    arrPrintWebs($prev);
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
//                    if ($prev['id'] > 0) {
                    if ($prev['id'] != NULL) {
                        $jmlBayar = 0;
                        $jmlHapus = 0;

                        if (isset($inSpec['static']['terbayar'])) {
                            if ($prev['sisa'] >= $inSpec['static']['terbayar']) {
                                $jmlBayar = $inSpec['static']['terbayar'];
                            }
                            else {
                                $jmlBayar = $prev['sisa'];
                            }
                            if (abs($inSpec['static']['terbayar']) > 0) {

                            }
                            else {
                                if (in_array($inSpec['static']['target_jenis'], $this->jenisTrBlacklist)) {

                                }
                                else {
                                    if (isset($inSpec['static']['force_allowed']) && ($inSpec['static']['force_allowed'] == 1)) {

                                    }
                                    else {

                                        $msg = "Transaksi Gagal disimpan karena jumlah pembayaran anda 0. Silahkan dikoreksi lagi atau hubungi admin. code: " . __LINE__;
                                        mati_disini($msg);
                                    }
                                }
                            }
                        }
                        $newTerbayar = ($prev['terbayar'] + $jmlBayar);
                        if (isset($inSpec['static']['dihapus'])) {
                            if ($prev['sisa'] >= $inSpec['static']['dihapus']) {
                                $jmlHapus = $inSpec['static']['dihapus'];
                            }
                            else {
                                $jmlHapus = $prev['sisa'];
                            }
                        }
                        $newDihapus = ($prev['dihapus'] + $jmlHapus);
                        if (isset($inSpec['static']['returned'])) {
                            $jmlReturned = $inSpec['static']['returned'];
                        }
                        else {
                            $jmlReturned = 0;
                        }
                        $newReturned = ($prev['returned'] + $jmlReturned);
                        $tagihan = $prev['tagihan'];

                        $newSisa = ($prev['sisa'] - $jmlBayar - $jmlHapus - $jmlReturned);
                        cekKuning(":: newSisa :: $newSisa ::");
                        //-----------------------------
                        if ($newTerbayar == 0) {
                            if (isset($inSpec['static']['reverted_target'])) {
                            }
                            else {
                                if (in_array($jenisPembatalan, $this->jenisPembatalan)) {

                                }
                                else {
                                    if (isset($inSpec['static']['exception_setor']) && ($inSpec['static']['exception_setor'] == 1)) {

                                    }
                                    else {
                                        $msg = "Transaksi gagal, karena nilai pembayaran atau penerimaan sebesar 0. Silahkan diperiksa kembali transaksi ini. code: " . __LINE__;
                                        cekMerah($msg);
                                        mati_disini($msg);
                                        die(lgShowAlert($msg));
                                    }
                                }
                            }
                        }
                        //-----------------------------
                        if ($newSisa < 0) {
                            $msg = "Transaksi gagal, karena saldo tidak cukup. Silahkan diperiksa kembali transaksi ini.";
                            cekMerah($msg);
                            die(lgShowAlert($msg));
                        }
                        elseif ($newSisa == $prev['sisa']) {
                            if (($inSpec['static']['terbayar'] > 0)) {
//                                cekHere(ipadd());
//                                if(ipadd()=="202.65.117.72"){
//
//                                }
//                                else{
//                                }
                                $msg = "Transaksi gagal, karena tagihan tidak berkurang. Silahkan diperiksa kembali transaksi ini. [$jmlBayar] [$newSisa] **";
                                cekMerah($msg);
                                die(lgShowAlert($msg));
                            }
                        }
                        elseif ($newTerbayar < 0) {
                            $newTerbayarCek = $newTerbayar;
                            if ($newTerbayarCek < 0) {
                                $newTerbayarCek = $newTerbayarCek * -1;
                            }
                            if ($newTerbayarCek > 10) {
                                $msg = "Transaksi gagal, karena nilai pembayaran atau penerimaan tidak valid. Silahkan diperiksa kembali transaksi ini.";
                                die(lgShowAlert($msg));
                            }
                        }
                        elseif ($newTerbayar > $tagihan) {
                            $selisih = $newTerbayar - $tagihan;
                            if ($selisih > 5) {
                                $msg = "Transaksi gagal, karena nilai pembayaran atau penerimaan tidak valid. Silahkan diperiksa kembali transaksi ini.";
                                die(lgShowAlert($msg));
                            }
                        }

                        $this->outParams[$lCounter]['where'] = array(
                            "id" => $prev['id'],
                            "cabang_id" => $inSpec['static']['cabang_id'],

                        );
                        $this->outParams[$lCounter]['update'] = array(
                            "terbayar" => $newTerbayar,
                            "dihapus" => $newDihapus,
                            "returned" => $newReturned,
                            "diskon" => $targetVal,
                            "sisa" => $newSisa,

                            "terbayar_valas" => $qtyTerbayar,
                            "sisa_valas" => $newSisa_valas,

                            "transaksi_ref_id" => isset($inSpec['static']['transaksi_ref_id']) ? $inSpec['static']['transaksi_ref_id'] : 0,
                            "transaksi_ref_no" => isset($inSpec['static']['transaksi_ref_no']) ? $inSpec['static']['transaksi_ref_no'] : 0,
                            //handling setor pajak bulanan
                            "setor_id" => isset($inSpec['static']['setor_id']) ? $inSpec['static']['setor_id'] : 0,
                            "setor_nomer" => isset($inSpec['static']['setor_nomer']) ? $inSpec['static']['setor_nomer'] : 0,
                            "setor_ebilling" => isset($inSpec['static']['setor_ebilling']) ? $inSpec['static']['setor_ebilling'] : 0,
                            "setor_date" => isset($inSpec['static']['setor_date']) ? $inSpec['static']['setor_date'] : "",
//
//                            "extern_date2" => isset($inSpec['static']['extern_date2']) ? $inSpec['static']['extern_date2'] : 0,
//                            "extern_label2" => isset($inSpec['static']['extern_label2']) ? $inSpec['static']['extern_label2'] : 0,
                        );
                        if (isset($inSpec['static']['extern_date2']) && ($inSpec['static']['extern_date2'] != NULL)) {
                            $this->outParams[$lCounter]['update']['extern_date2'] = $inSpec['static']['extern_date2'];
                        }
                        if (isset($inSpec['static']['extern_label2']) && ($inSpec['static']['extern_label2'] != NULL)) {
                            $this->outParams[$lCounter]['update']['extern_label2'] = $inSpec['static']['extern_label2'];
                        }

                        if (isset($inSpec['static']['realisasi_kurang']) && ($inSpec['static']['realisasi_kurang'] != NULL)) {
                            $this->outParams[$lCounter]['update']['realisasi_kurang'] = $inSpec['static']['realisasi_kurang'];
                        }
                        if (isset($inSpec['static']['realisasi_netto']) && ($inSpec['static']['realisasi_netto'] != NULL)) {
                            $this->outParams[$lCounter]['update']['realisasi_netto'] = $inSpec['static']['realisasi_netto'];
                        }

                        if (isset($inSpec['static']['exception_setor']) && ($inSpec['static']['exception_setor'] == 1)) {
                            if (($inSpec['static']['terbayar'] == 0) && ($inSpec['static']['nilai_ppn_keluaran_didelete'] > 0)) {
                                $this->outParams[$lCounter]['update']['setor_id'] = 0;
                                $this->outParams[$lCounter]['update']['setor_nomer'] = "";
                            }
                        }

                    }

                    if (isset($inSpec['static']['force_exec']) && ($inSpec['static']['force_exec'] == 1)) {
                        $force_exec = 1;
                    }

                }
            }
        }
arrPrintHitam($this->outParams);
cekHitam("force_exec: $force_exec");
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
        $tr->addFilter("label='$label'");
        $tr->addFilter("cabang_id='$cabangID'");
        $tr->addFilter("target_jenis='$targetJenis'");
        $tr->addFilter("transaksi_id='$transaksiID'");
        if (($tabelID != null) || ($tabelID != 0)) {
            $tr->addFilter("id='$tabelID'");
        }
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
//arrPrintWebs($localFilters);
        $query = $this->db->select()
            ->from($tr->getTableNames()['paymentSrc'])
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();
//showLast_query("biru");
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
                cekUngu(":: efek update tabel: $insID ::" . $this->db->last_query());
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

