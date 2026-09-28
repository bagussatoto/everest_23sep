<?php


class PreRekeningKoranPembatalan extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $paymentMethod = array();


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;

        $this->jenisTrException = array("9911", "9912");

    }

    //<editor-fold desc="getter-setter">
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }


        if (sizeof($inParams) > 0) {
            $cashID = isset($inParams['static']['extern_id']) ? $inParams['static']['extern_id'] : 0;
            $cabangID = isset($inParams['static']['cabang_id']) ? $inParams['static']['cabang_id'] : 0;
            $nilai = isset($inParams['static']['nilai']) ? $inParams['static']['nilai'] : 0;
            $method = isset($inParams['static']['method']) ? $inParams['static']['method'] : NULL;
            $jenis = isset($inParams['static']['jenis']) ? $inParams['static']['jenis'] : NULL;
            $state = isset($inParams['static']['state']) ? $inParams['static']['state'] : NULL;
            $jenisTr = isset($inParams['static']['jenisTr']) ? $inParams['static']['jenisTr'] : NULL;
            $cCode = "_TR_" . $jenisTr;

            if (($method != NULL) && ($method == "rekening_koran")) {


                // membaca saldo hutang rekening koran
                $this->load->model("Coms/ComRekeningPembantuRekeningKoranMain");
                $b = new ComRekeningPembantuRekeningKoranMain();
                $b->addFilter("rekening='rekening koran'");
                $b->addFilter("cabang_id='" . $cabangID . "'");
                $b->addFilter("extern_id='" . $cashID . "'");
                $b->addFilter("periode='forever'");
                $tmp = $b->lookupAll()->result();
                // membaca plafon rekening koran

                $nilai_koran_full = $nilai;
                $nilai_cash_full = 0;

                $selisih = $nilai - $tmp[0]->kredit;

                if ($tmp[0]->kredit > 0) {
                    if ($selisih > 0) {
                        $nilai_koran = $selisih;
                        $nilai_cash = $selisih;
                    }
                    else {
                        $nilai_koran = 0;
                        $nilai_cash = 0;
                    }
                }
                else {
                    $nilai_koran = $nilai;
                    $nilai_cash = $nilai;
                }

                // masuk ke gerbang...
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        $patchers[$gateName][$key] = $$val;
                    }
                }


                // build config jurnal tambahan, kas pada hutang rekening koran
                $arrJurnalTambahan = array(
                    //jurnal, rekening, rekening pembantu
                    94 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "nilai_cash",
                            "hutang bank" => "nilai_koran",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    95 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "kas" => "nilai_cash",
                            "hutang bank" => "nilai_koran",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "nilai_cash",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
                    97 => array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "hutang bank" => "nilai_koran",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account__folders",//id bank
                            "extern_nama" => "cash_account__folders_nama",//lbel bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "cash_account__folders",
                            "extern2_nama" => "cash_account__folders_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
//                            "h" => "harga",//
                            "hutang bank" => "nilai_koran",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",//id relasi rekening koran
                            "extern_nama" => ".rekening koran",//lbel relasi rekening koran
                            "extern2_id" => "cash_account__folders",//id folder rekening koran
                            "extern2_nama" => "cash_account__folders_nama",//label folder rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    99 => array(
                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
                        "loop" => array(
                            "rekening koran" => "nilai_koran",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",//id rekening koran
                            "extern_nama" => "cash_account__label",//label rekening koran
                            "extern2_id" => "cash_account__folders",//folder rekening koran
                            "extern2_nama" => "cash_account__folders_nama",//folder rekening koran

                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "nilai_koran_full",
                            "produk_qty" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregkening koran
                );

                $arrPostProcTambahan = array(
                    // rekening koran
                    99 => array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account_target",
                            "nama" => "cash_account_target__label",
                            "nilai" => "-nilai_koran",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",
                            "extern_nama" => "cash_account_target__label",
                            "debet" => "-nilai_koran",
                            "produk_nilai" => "-nilai_koran",
                            "gudang_id" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening kas
                    97 => array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                );

                foreach ($arrJurnalTambahan as $cnt => $spec) {
                    $_SESSION[$cCode]['revert']['jurnal']['master'][$cnt] = $spec;

                }
                foreach ($arrPostProcTambahan as $cnt => $spec) {
                    $_SESSION[$cCode]['revert']['postProc']['master'][$cnt] = $spec;

                }

            }
            else {

                // yang ini jalan normal, bukan pembatalan...
                if (!in_array($jenisTr, $this->jenisTrException)) {
                    $nilai_koran = 0;
                    $nilai_cash = $nilai;

                    // masuk ke gerbang...
                    foreach ($this->resultParams as $gateName => $paramSpec) {
                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$key] = $$val;
                        }
                    }
                }
                else {
                    // kalau pembatalan transaksi pakai yang ini...
                    // supaya tidak menginjek gerbang yang sudah ada
                    // jadi yang dipakai tetap gerbang aslinya
                    foreach ($this->resultParams as $gateName => $paramSpec) {
                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName]['pembatalan'] = 0;
                        }
                    }
                }
            }


        }
        $this->result = $patchers;


        if (sizeof($this->result) > 0) {
            return true;
        }
        else {
            return false;
        }


//        mati_disini(":: rekkoran $nilai_koran :: cashnilai $nilai_cash :: nilai $nilai ::");
    }

    public function exec()
    {
        return $this->result;
    }
}