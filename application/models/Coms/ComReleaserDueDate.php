<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComReleaserDueDate extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        "transaksi_id",
        "customers_id",
        "customers_nama",
        "cabang_id",
        "cabang_nama",
        "nomer",
        "dtime",
        "due_date",
        "oleh_nama",
        "oleh_id",
        "transaksi_nilai",
        "release_id",//id payment
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
//        arrPrintWebs($this->inParams);
        $validateData = $this->cekPreValue($this->inParams);
//        arrPrintWebs($validateData);
        if (sizeof($validateData) > 0) {
//            $where = $validateData['key'];
            $where = $validateData['update_shipment'];
            $val = $validateData['detail'];
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->updateDueDate($where, $val);
            cekHere($this->db->last_query());
//            mati_disini(__LINE__);
            return true;

        }
        else {
            return true;
        }


    }

    public function cekPreValue($outParams)
    {

        $this->load->model("Mdls/MdlPaymentSource");
        $this->load->model("MdlTransaksi");
        $lunas = array();
        foreach ($outParams as $param) {
            $mainID = $param['static']['extern_id'];
            $trId = $param['static']['transaksi_id'];
            $ket = $param['static']['keterangan'];
            $nomer = $param['static']['transaksi_nomer'];
            $jenis = isset($param['static']['extern_jenis']) ? $param['static']['extern_jenis'] : "0";
            if ($jenis == "582spd") {
                $tempRelease[] = $mainID;
            }
            else {
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id='$mainID'");
                $arrMaindata = $tr->lookupMainTransaksi()->result();
//                showLast_query("kuning");
                foreach ($arrMaindata as $mainTemp) {
                    if (isset($mainTemp->ids_prev)) {
                        $tempRelease = blobDecode($mainTemp->ids_prev);
                    }
                }
            }

            $update = array();
//            if (sizeof($tempRelease) > 0) {
////                $tr->setFilters(array());
//                $where = "transaksi_id in (" . implode(",", $tempRelease) . ")";
//
//                $update = array(
//                    "detail" => array(
//                        "status" => "0",
//                        "release_id" => $trId,
//                        "keterangan" => "$ket",
//                        "release_dtime" => date("Y-m-d H:i:s"),
//                        "release_name" => $nomer,
//                        "rel_id" => $this->session->login['id'],
//                        "rel_nama" => $this->session->login['nama'],
//                    ),
//                    "key" => "transaksi_id in (" . implode(",", $tempRelease) . ")",
//                );
//            }
            //--------------------------
            // bagian membaca paymentsource masih ada sisa atau tidak ???
//            cekHere("cetak kiriman...");
//            arrPrintWebs($param);
            $pymSrc = New MdlPaymentSource();
            $pymSrc->addFilter("transaksi_id='$mainID'");
            $pymSrc->addFilter("target_jenis='" . $param['static']['jenis'] . "'");
            $tmp = $pymSrc->lookupAll()->result();
            cekHere($this->db->last_query());
//            arrPrintWebs($tmp);

            if (sizeof($tmp) > 0) {
                foreach ($tmp as $tmpSpec) {
                    // mengambil yang dinyatakan lunas dibayar
                    if ($tmpSpec->sisa < 100) {
//                        $lunas[$tmpSpec->id] = $tmpSpec->transaksi_id;
                        $lunas[] = $tmpSpec->transaksi_id;
                    }
                }
            }
        }
//        arrPrintPink($lunas);
        if (sizeof($lunas) > 0) {
            $arrTrID_shipment = array();
            $tr = New MdlTransaksi();
            $this->db->select(array("id", "ids_prev"));
            $tr->addFilter("id in ('" . implode("','", $lunas) . "')");
            $trTmp = $tr->lookupAll()->result();
//            showLast_query("biru");
//            arrPrintPink($trTmp);
            if (sizeof($trTmp) > 0) {
                foreach ($trTmp as $spec) {
                    $idPrevDecode = blobDecode($spec->ids_prev);
                    foreach ($idPrevDecode as $trID_shipment) {
                        $arrTrID_shipment[] = $trID_shipment;

                    }
                }
            }
            if (sizeof($arrTrID_shipment) > 0) {
                $update['update_shipment'] = "transaksi_id in ('" . implode("','", $arrTrID_shipment) . "')";
                $update['detail'] = array(
                        "status" => "0",
                        "release_id" => $trId,
                        "keterangan" => "$ket",
                        "release_dtime" => date("Y-m-d H:i:s"),
                        "release_name" => $nomer,
                        "rel_id" => $this->session->login['id'],
                        "rel_nama" => $this->session->login['nama'],
                );
            }
        }

//arrPrintPink($update);
//mati_disini();
        return $update;
    }

    public function exec()
    {
        return true;


    }
}