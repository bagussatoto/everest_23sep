<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoProdukJadiRakitan extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

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
            $patchers = array();
            $needles = array();
            $needlesNama = array();
            $fullfills = array();
            $kekurangans = array();
            $updatePairs = array();
            $ids = array();
            foreach ($inParams as $sentParams) {
//                cekMerah("cetak sentParam");
//                arrPrint($sentParams);
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $needlesNama[$pSpec['extern_id']] = $pSpec['extern_nama'];

                    $ids[] = $pSpec["extern_id"];
                }

            }


            $this->load->model("Mdls/MdlFifoProdukJadiRakitan");
            foreach ($needles as $ppID => $keperluan) {
                $bb = new MdlFifoProdukJadiRakitan();
                $bb->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $bb->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $bb->addFilter("produk_id='$ppID'");
                $bb->addFilter("unit>0");
                $tmpBB = $bb->lookupAllQty()->result();
                cekBiru($this->db->last_query());
                $tersedia = 0;
                if (sizeof($tmpBB) > 0) {
                    $tersedia = $tmpBB[0]->qty;
                }
                if ($keperluan > $tersedia) {
                    $msg = "Jumlah stok " . $needlesNama[$ppID] . " tidak cukup." . $tersedia;
                    die(lgShowAlert($msg));
                    mati_disini("KURANGGGGG....");
                }
            }

            $b = new MdlFifoProdukJadiRakitan();
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
            $b->addFilter("unit>0");

            $tmp = $b->lookupAll()->result();
            cekHere("FIFO: " . $this->db->last_query());

            if (sizeof($tmp) > 0) {
                $markedIDs = array();
                $pCtr = 0;
//                $gtCtr = 0;
                foreach ($needles as $pID => $jml) {
                    $pCtr++;
                    if (!isset($fullfills[$pID])) {
                        $fullfills[$pID] = 0;
                    }
                    $kekurangans[$pID] = $needles[$pID];

                    $gtCtr = $pID * $pID;

                    $nyerah = false;
                    while ($kekurangans[$pID] > 0 || $nyerah) {
                        $rowCtr = 0;
                        foreach ($tmp as $row) {

                            if (!in_array($row->id, $markedIDs)) {
                                $jmlAvail = $row->unit;
                                if ((int)$row->produk_id == (int)$pID) {

                                    $gtCtr++;

                                    if ($jmlAvail >= $kekurangans[$pID]) {  //==jumlah yg tersedia ternyata cukup atau malah lebih

                                        $produk_jenis_id = $row->produk_jenis_id;
                                        $produk_jenis = $row->produk_jenis;
                                        $produk_id = $row->produk_id;
                                        $nama = $row->produk_nama;
                                        $hpp = $row->hpp;
                                        $hpp_riil = $row->hpp_riil;
                                        $ppv_riil = $row->ppv_riil;
                                        $suppliers_id = $row->suppliers_id;
                                        $suppliers_nama = $row->suppliers_nama;
                                        $kode_produksi = $row->kode_produksi;
                                        $ppn_in = $row->ppn_in;
                                        $qty = $jmlDiambil = $kekurangans[$pID];
                                        $subtotal = $hpp * $qty;
                                        $hpp_nppv = $row->hpp_nppv;

                                        $fullfills[$pID] += $jmlDiambil;
                                        $kekurangans[$pID] -= $jmlDiambil;

//                                        cekHere("jumlahnya cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
//                                        cekHere("kekurangannya sekarang: " . $kekurangans[$pID] . "");

                                        $updatePairs[] = array(
                                            "id" => $row->id,
                                            "produk_id" => $row->produk_id,
                                            "unit" => ($row->unit - $jmlDiambil),
                                            "jml_nilai" => ($row->jml_nilai - ($jmlDiambil * $row->hpp)),
                                            "jml_ot" => ($row->jml_ot + $jmlDiambil),
                                            "jml_nilai_ot" => ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp)),

                                            "jml_nilai_riil" => ($row->jml_nilai_riil - ($jmlDiambil * $row->hpp_riil)),
                                            "ppv_nilai_riil" => ($row->ppv_nilai_riil - ($jmlDiambil * $row->ppv_riil)),

                                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($jmlDiambil * $row->ppn_in)),

                                            "jml_nilai_nppv" => ($row->jml_nilai_nppv - ($jmlDiambil * $row->hpp_nppv)),
                                        );
                                        $sisaDiTabel = ($row->unit - $jmlDiambil);
                                        if ($sisaDiTabel < 1) {
                                            $markedIDs[] = $row->id;

                                            unset($tmp[$rowCtr]);
                                        }


                                        //<editor-fold desc="patchers HPP dari FIFO RIIL">
                                        foreach ($this->resultParams as $gateName => $paramSpec) {

                                            foreach ($paramSpec as $key => $val) {
                                                cekBiru("ATAS ::: $key => $val :::");

                                                $hasil[$key] = $$val;
                                                $hasil['rowPreFifo'] = $gtCtr;
                                            }
//                                            $patchers[$gateName][] = $hasil;
                                            $patchers[$gateName][$gtCtr] = $hasil;
                                        }
                                        //</editor-fold>

                                        break;
                                    }
                                    else { //==jumlahnya kurang bro
                                        $produk_jenis_id = $row->produk_jenis_id;
                                        $produk_jenis = $row->produk_jenis;
                                        $produk_id = $row->produk_id;
                                        $nama = $row->produk_nama;
                                        $hpp = $row->hpp;
                                        $hpp_riil = $row->hpp_riil;
                                        $ppv_riil = $row->ppv_riil;
                                        $suppliers_id = $row->suppliers_id;
                                        $suppliers_nama = $row->suppliers_nama;
                                        $kode_produksi = $row->kode_produksi;
                                        $ppn_in = $row->ppn_in;
                                        $qty = $jmlDiambil = $jmlAvail;
                                        $subtotal = $hpp * $qty;
                                        $hpp_nppv = $row->hpp_nppv;

                                        $fullfills[$pID] = $jmlDiambil;
                                        $kekurangans[$pID] -= $jmlDiambil;

//                                        cekHere("jumlahnya tidak cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
//                                        cekHere("kekurangannya sekarang: " . $kekurangans[$pID] . "<br>");

                                        $updatePairs[] = array(
                                            "id" => $row->id,
                                            "produk_id" => $row->produk_id,
                                            "unit" => ($row->unit - $jmlDiambil),
                                            "jml_nilai" => ($row->jml_nilai - ($jmlDiambil * $row->hpp)),
                                            "jml_ot" => ($row->jml_ot + $jmlDiambil),
                                            "jml_nilai_ot" => ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp)),

                                            "jml_nilai_riil" => ($row->jml_nilai_riil - ($jmlDiambil * $row->hpp_riil)),
                                            "ppv_nilai_riil" => ($row->ppv_nilai_riil - ($jmlDiambil * $row->ppv_riil)),

                                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($jmlDiambil * $row->ppn_in)),

                                            "jml_nilai_nppv" => ($row->jml_nilai_nppv - ($jmlDiambil * $row->hpp_nppv)),
                                        );
                                        $sisaDiTabel = ($row->unit - $jmlDiambil);
                                        if ($sisaDiTabel < 1) {
                                            $markedIDs[] = $row->id;
                                        }


                                        //<editor-fold desc="patchers HPP dari FIFO RIIL">
                                        foreach ($this->resultParams as $gateName => $paramSpec) {
                                            foreach ($paramSpec as $key => $val) {
                                                cekBiru("BAWAH ::: $key => $val :::");

                                                $hasil[$key] = $$val;
                                                $hasil['rowPreFifo'] = $gtCtr;
                                            }
//                                            $patchers[$gateName][] = $hasil;
                                            $patchers[$gateName][$gtCtr] = $hasil;
                                        }
                                        //</editor-fold>


                                        if ($kekurangans[$pID] == 0) {
                                            unset($tmp[$rowCtr]);
                                            break;
                                        }
                                    }
                                }
                            }
                            $rowCtr++;
                        }
                        if ($rowCtr > sizeof($tmp)) {
                            $nyerah = true;
//                            cekHijau("baris fifo ke $rowCtr, padahal jumlah barisnya " . sizeof($tmp));
//                            cekHijau("NYERAHHH... fifo untuk barang $pID tidak ada yang mau DIPAKE!");
                            die(lgShowAlert("UNABLE TO  EXAMINE FIFO"));
                            break;
                        }
                    }
                }

//                cekBiru("cetak Parchers...");
//                arrPrint($patchers);
//                cekBiru("used batches: ");
//                arrPrint($updatePairs);
//mati_disini();


                $this->result = $patchers;
//                $this->result = array();
            }
            else {
                $this->result = array();
            }

            if (sizeof($updatePairs) > 0) {
                foreach ($updatePairs as $upSpec) {
                    $updateData = $upSpec;
                    unset($updateData["id"]);
                    $b = new MdlFifoProdukJadiRakitan();
                    $b->updateData(array("id" => $upSpec['id']), $updateData);
                    cekMerah($this->db->last_query());
                }
            }
        }


        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function exec()
    {
        return $this->result;
    }
}