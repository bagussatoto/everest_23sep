<?php

class ReComTitipanUangMukaSupplier extends MdlMother
{
    private $jenisTr;

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $this->load->model("MdlTransaksi");
        $this->jenis_selesai = array(
            "467", "1467", "461", "463", "3463"
        );
    }

    public function pair($extern_id = NULL, $extern_nilai = NULL)
    {

        $cCode = "_TR_" . $this->jenisTr;
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            if ($extern_id != NULL) {
                $tr = New MdlTransaksi();
                $tr->addFilter("id='$extern_id'");
                $trTmp = $tr->lookupAll()->result();
                if (sizeof($trTmp) > 0) {
                    $master_id = $trTmp[0]->id_master;
                    $transaksi_nilai = $trTmp[0]->transaksi_nilai;
                    $trash_4 = $trTmp[0]->trash_4;
                    $cancel_nama = $trTmp[0]->cancel_name;
                    $cancel_dtime = $trTmp[0]->cancel_dtime;
                    $nomer = $trTmp[0]->nomer;
                    $nomer_f = formatField_he_format("nomer_nolink", $nomer);

                    // region cek po dibatalkan/direject atau belum...
                    if ($trash_4 == 1) {
                        $msg = "PO yang dipilih tidak bisa direlasikan dengan Titipan.";
                        $msg .= " PO sudah dibatalkan/direject oleh $cancel_nama pada $cancel_dtime.";
                        $msg .= " Silahkan pilih PO lain atau Titipan tanpa relasi PO.";
                        $msg .= " code: " . __LINE__;
                        die(lgShowAlertMerah($msg));
                    }
                    // endregion cek po dibatalkan/direject atau belum...

                    // region cek nilai transaksi dengan uang muka/titipan yang sudah masuk...
                    $tr = New MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->addFilter("extern2_id='$extern_id'");
                    $tr->addFilter("label='uang muka'");
                    $tr->addFilter("jenis='4643'");
                    $tr->addFilter("sisa>'0'");
                    $trTmp = $tr->lookupAllUangMukaSrc("0")->result();
                    $sisa = isset($trTmp[0]->sisa) ? $trTmp[0]->sisa : 0;
//                    $nilai_input = isset($_SESSION[$cCode]["main"]["nett"]) ? $_SESSION[$cCode]["main"]["nett"] : 0;
                    $nilai_input = isset($extern_nilai) ? $extern_nilai : 0;
                    $total_um_input = $sisa + $nilai_input;
                    if ($nilai_input > $transaksi_nilai) {
                        $selisih = $nilai_input - $transaksi_nilai;
                        if ($selisih > 100) {
                            $nilai_input_f = number_format($nilai_input, 0, ".", ",");
                            $transaksi_nilai_f = number_format($transaksi_nilai, 0, ".", ",");
                            $msg = "Nilai Titipan yang anda isikan sebesar $nilai_input_f melebihi nilai PO ($nomer_f) sebesar $transaksi_nilai_f. Silahkan dikoreksi lagi. code: " . __LINE__;
                            die(lgShowAlertMerah($msg));
                        }
                        else {
                            // tetap lanjut.....
                        }
                    }
                    if ($total_um_input > $transaksi_nilai) {
                        $selisih = $total_um_input - $transaksi_nilai;
                        if ($selisih > 100) {
                            $sisa_f = number_format($sisa, 0, ".", ",");
                            $nilai_input_f = number_format($nilai_input, 0, ".", ",");
                            $total_um_input_f = number_format($total_um_input, 0, ".", ",");
                            $transaksi_nilai_f = number_format($transaksi_nilai, 0, ".", ",");
                            $msg = "Nilai total Titipan sebesar $total_um_input_f (Titipan sebelumnya $sisa_f, input Titipan saat ini $nilai_input_f) melebihi nilai PO ($nomer_f) sebesar $transaksi_nilai_f. Silahkan dikoreksi lagi. code: " . __LINE__;
                            die(lgShowAlertMerah($msg));
                        }
                    }
                    // endregion cek nilai transaksi dengan uang muka/titipan yang sudah masuk...
//cekHijau("[total_um_input: $total_um_input] || [transaksi_nilai: $transaksi_nilai]");
                    // region cek po seelsai
                    $tr = New MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->addFilter("id_master='$master_id'");
                    $tr->addFilter("jenis in ('" . implode("','", $this->jenis_selesai) . "')");
                    $tr->addFilter("trash_4='0'");
                    $trCek = $tr->lookupAll()->result();
                    $total_grn = 0;
                    $trid_grn = array();
                    if (sizeof($trCek) > 0) {
                        foreach ($trCek as $trCekSpec) {
                            $grn = $trCekSpec->transaksi_nilai;
                            $total_grn += $grn;

                            $trid_grn[$trCekSpec->id] = $trCekSpec->id;
                        }
                    }
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        if ($total_grn >= $transaksi_nilai) {
                            $msg = "PO yang dipilih tidak bisa direlasikan dengan Titipan.";
                            $msg .= " PO sudah selesai sampai dengan GRN atau SRN.";
                            $msg .= " Silahkan pilih PO lain atau Titipan tanpa relasi PO.";
                            $msg .= " code: " . __LINE__;
                            die(lgShowAlertMerah($msg));
                        }
                    }
//arrPrint($trid_grn);
                    if (sizeof($trid_grn) > 0) {
                        $tr = New MdlTransaksi();
                        $tr->setFilters(array());
//                        $tr->addFilter("sisa>'0'");
                        $tr->addFilter("transaksi_id in ('" . implode("','", $trid_grn) . "')");
                        $trPym = $tr->lookUpAllPaymentSrc()->result();
                        showLast_query("biru");
                        $sisa_belum_bayar = 0;
                        $tagihan_total = 0;
                        if (sizeof($trPym) > 0) {
                            foreach ($trPym as $trPymSpec) {
                                $tagihan = $trPymSpec->tagihan;
                                $returned = $trPymSpec->returned;
                                $sisa = $trPymSpec->sisa;
                                $sisa_belum_bayar += $sisa;
                                $tagihan_total += ($tagihan - $returned);
                            }
                        }
                        if ($sisa_belum_bayar > 1000) {
                            // masih bisa diberikan titipan/uang muka
                        }
                        else {
                            cekMerah("masuk disini... [tagihan_total: $tagihan_total] [sisa_belum_bayar: $sisa_belum_bayar]");
                            $tagihan_total_nppn = $tagihan_total * 1.11;
                            if ($transaksi_nilai > $tagihan_total_nppn) {
                                // baru grn sebagian...
                            }
                            else {
                                $msg = "PO ($nomer_f) yang dipilih tidak bisa direlasikan dengan Titipan.";
                                $msg .= " PO sudah selesai atau sudah LUNAS pembayaran.";
                                $msg .= " Silahkan pilih PO lain atau Titipan tanpa relasi PO.";
                                $msg .= " code: " . __LINE__;
                                die(lgShowAlertMerah($msg));
                            }
                        }
                    }
                    // endregion cek po seelsai

//                    mati_disini("[po: $transaksi_nilai] [$sisa] [$nilai_input] [$total_um_input] [grn: $total_grn]");
                }
            }
        }
//        mati_disini(get_class($this) . " ==== " . __LINE__);
        cekUngu("BISA DILANJUTKAN...");

        return true;
    }

    public function exec()
    {
        return true;
    }
}