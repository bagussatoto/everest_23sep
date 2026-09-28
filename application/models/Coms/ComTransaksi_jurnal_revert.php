<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksi_jurnal_revert extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        "jenis",
        "produk_id",
        "cabang_id",
        "nama",
        "satuan",
        "state",
        "jumlah",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
    );

    private $memenuhiSyarat;

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->memenuhiSyarat = false;
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            if (isset($this->inParams['static']['singleReference']) && $this->inParams['static']['singleReference'] > 0) {
                $this->memenuhiSyarat = true;
            }
        }
//arrPrintPink($inParams);
        //==kalau tidak berasal dari koneksi, cuekin saja (selalu true
//        cekbiru("memenuhi syarat?: ".$this->memenuhiSyarat);
//        arrPRint($this->inParams);
        $main_code = $this->inParams['main_code'];
        $next_code = $this->inParams['next_code'];
        $id_master = $this->inParams['refID'];
        $step_num = $this->inParams['step_num'];
//cekHitam(":: ID TR $id_master");

//        $validateData = $this->cekPreValue($id_master, $main_code, $next_code, $step_num);
//        return $validateData;


        return true;
    }

    public function cekPreValue($id, $code, $next_code, $step)
    {
//        cekHitam($trID);


        $preDataCore = isset($this->config->item("heTransaksi_core")[$code][$step]) ? $this->config->item("heTransaksi_core")[$code][$step] : array();
        $preDataUi = isset($this->config->item("heTransaksi_ui")[$code]["steps"]) ? $this->config->item("heTransaksi_ui")[$code]["steps"] : array();
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setTableName($tr->getTableNames()['main']);
        $tr->addFilter("id_master='$id'");
        $tr->addFilter("jenis_master='$code'");
        $tr->addFilter("jenis='$next_code'");
        $tr->addFilter("trash_4='0'");
        $tempData = $tr->lookupAll()->result();
//        cekHitam($this->db->last_query());
        if (sizeof($tempData) > 0) {
            $no = $tempData[0]->id;
            $step = $tempData[0]->step_number;
            $jenis = $tempData[0]->jenis;

            //cek payment source avail
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($jenis, $paymentSources)) {
                //cek sudah di followup belum?
                $this->load->model("Mdls/MdlPaymentSource");
                $m = new MdlPaymentSource();

                matiHEre("under maintenance ." . __LINE__ . " function" . __FILE__);
            }
            else {
                cekLime("no payment connected");
                //cek ada jurnal?
                $this->load->model("Coms/ComJurnal");
                $j = new ComJurnal();
                $j->addFilter("transaksi_id='$no'");
                $data = $j->fetchMoves($no);
                if (sizeof($data) > 0) {
                    //ndak boleh reject diarahkan untuk reject nota terkait lebih dahulu
                    $nomer = $data[0]->transaksi_no;
                    $errMsg = formatField("nama", $nomer);
                    $label = $preDataUi[$step]["label"];
                    $errLabel = "Tidak diijinkan melakukan pembatalan karena transaksi sudah difollowup, silahkan batalkan terlebih dahulu transaksi ( " . $label . " ) dengan nomer berikut " . $errMsg;

                    die(lgShowAlert($errLabel));
                    matiHere("" . $errLabel);
                }
                else{
                    $tmp = true;
                }
            }

        }
        else {
            $tmp = true;
        }
        return $tmp;
    }

    public function exec()
    {
        return true;


    }
}