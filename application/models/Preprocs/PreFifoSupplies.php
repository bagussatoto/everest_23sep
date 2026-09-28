<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoSupplies extends CI_Model
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
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $needlesNama[$pSpec['extern_id']] = $pSpec['extern_nama'];

                    $ids[] = $pSpec["extern_id"];
                }

            }
            $this->load->model("Mdls/MdlFifoSupplies");
            foreach ($needles as $ppID => $keperluan) {
                $bb = new MdlFifoSupplies();
                $bb->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $bb->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $bb->addFilter("produk_id='$ppID'");
                $bb->addFilter("unit>0");
                $tmpBB = $bb->lookupAllQty()->result();
//                cekBiru($this->db->last_query());
                $tersedia = 0;
                if (sizeof($tmpBB) > 0) {
                    $tersedia = $tmpBB[0]->qty;
                }
                if ($keperluan > $tersedia) {
                    $msg = "Jumlah stok " . $needlesNama[$ppID] . " tidak cukup.";
                    die(lgShowAlert($msg));
                    mati_disini("KURANGGGGG....");
                }
            }

            $preProccInjector = isset($this->config->item('heTransaksi_core')[$sentParams['static']['jenisTr']]['preProcessorInjector']) ? $this->config->item('heTransaksi_core')[$sentParams['static']['jenisTr']]['preProcessorInjector'] : array();


            $b = new MdlFifoSupplies();
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
            $b->addFilter("unit>0");

            $tmpCopy = $tmp = $b->lookupAll()->result();
            cekHere("FIFO: " . $this->db->last_query());

            if (sizeof($tmpCopy) > 0) {
                $markedIDs = array();
                $pCtr = 0;
//                $gtCtr = 0;
                foreach ($needles as $pID => $jml) {
                    echo("<br>needle pID: $pID, perlu jml: $jml");
                    $pCtr++;
                    if (!isset($fullfills[$pID])) {
                        $fullfills[$pID] = 0;
                    }
                    $kekurangans[$pID] = $needles[$pID];

                    $gtCtr = $pID * $pID;

                    $nyerah[$pID] = false;
                    while ($kekurangans[$pID] > 0 || $nyerah[$pID]) {
//                        echo("<br>cetak kekurangan dalam perulangan :: $pID :: " . $kekurangans[$pID]);
                        $rowCtr = 0;
                        if ($kekurangans[$pID] > 0) {
                            foreach ($tmpCopy as $row) {

                                echo("<br>-- id: " . $row->id . " pID: " . $row->produk_id . " jml: " . $row->unit);

                                if (!in_array($row->id, $markedIDs)) {
                                    $jmlAvail = $row->unit;

                                    if ((int)$row->produk_id == (int)$pID) {
                                        $gtCtr++;
                                        echo("<br>---- produk ID cocok");
                                        if ($jmlAvail >= $kekurangans[$pID]) {//==jumlah yg tersedia ternyata cukup atau malah lebih

                                            $produk_id = $row->produk_id;
                                            $nama = $row->produk_nama;
                                            $produk_jenis = $row->produk_jenis;

                                            $suppliers_id = $row->suppliers_id;
                                            $suppliers_nama = $row->suppliers_nama;
                                            $ppn_in = $row->ppn_in;

                                            $hpp = $row->hpp;
                                            $hpp_riil = $row->hpp_riil;
                                            $ppv_riil = $row->ppv_riil;
                                            $qty = $jmlDiambil = $kekurangans[$pID];
                                            $subtotal = $hpp * $qty;
                                            //---
                                            $oleh_id = $row->oleh_id;
                                            $oleh_nama = $row->oleh_nama;
                                            $purchase_id = $row->purchase_id;
                                            $purchase_nomer = $row->purchase_nomer;
                                            $kode_produksi = $row->kode_produksi;
                                            //---

                                            $fullfills[$pID] += $jmlDiambil;
                                            $kekurangans[$pID] -= $jmlDiambil;


                                            echo("<br>------ jumlahnya cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
//                                            echo("<br>kekurangannya sekarang: " . $kekurangans[$pID] . "");

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

                                                unset($tmpCopy[$rowCtr]);
                                            }


                                            //<editor-fold desc="patchers HPP dari FIFO RIIL">
                                            foreach ($this->resultParams as $gateName => $paramSpec) {

                                                foreach ($paramSpec as $key => $val) {
                                                    echo("<br>ATAS ::: $key => $val :::");

                                                    $hasil[$key] = $$val;
                                                    $hasil['rowPreFifo'] = $gtCtr;

//                                                    $hasil['cabang_id'] = $sentParams['static']['cabang_id'];
//                                                    $hasil['gudang_id'] = $sentParams['static']['gudang_id'];
                                                    if (sizeof($preProccInjector) > 0) {
                                                        foreach ($preProccInjector as $keyInjecor => $valInjector) {
                                                            $hasil[$keyInjecor] = isset($sentParams['static'][$valInjector]) ? $sentParams['static'][$valInjector] : "";
                                                        }
                                                    }
                                                }
                                                arrPrint($hasil);
                                                $patchers[$gateName][$gtCtr] = $hasil;
//                                                $patchers[$gateName][] = $hasil;
                                            }
                                            //</editor-fold>

                                            break;
                                        }
                                        else {//==jumlahnya kurang bro
                                            $produk_id = $row->produk_id;
                                            $nama = $row->produk_nama;
                                            $produk_jenis = $row->produk_jenis;

                                            $suppliers_id = $row->suppliers_id;
                                            $suppliers_nama = $row->suppliers_nama;
                                            $ppn_in = $row->ppn_in;

                                            $hpp = $row->hpp;
                                            $hpp_riil = $row->hpp_riil;
                                            $ppv_riil = $row->ppv_riil;
                                            $qty = $jmlDiambil = $jmlAvail;
                                            $subtotal = $hpp * $qty;
                                            //---
                                            $oleh_id = $row->oleh_id;
                                            $oleh_nama = $row->oleh_nama;
                                            $purchase_id = $row->purchase_id;
                                            $purchase_nomer = $row->purchase_nomer;
                                            $kode_produksi = $row->kode_produksi;
                                            //---

                                            $fullfills[$pID] = $jmlDiambil;
                                            $kekurangans[$pID] -= $jmlDiambil;

                                            echo("<br>------ jumlahnya tidak cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
//                                            echo("<br>kekurangannya sekarang: " . $kekurangans[$pID] . "<br>");

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
                                                unset($tmpCopy[$rowCtr]);
                                            }


                                            //<editor-fold desc="patchers HPP dari FIFO RIIL">
                                            foreach ($this->resultParams as $gateName => $paramSpec) {
                                                foreach ($paramSpec as $key => $val) {
                                                    echo("<br>BAWAH ::: $key => $val :::");

                                                    $hasil[$key] = $$val;
                                                    $hasil['rowPreFifo'] = $gtCtr;

//                                                    $hasil['cabang_id'] = $sentParams['static']['cabang_id'];
//                                                    $hasil['gudang_id'] = $sentParams['static']['gudang_id'];
                                                    if (sizeof($preProccInjector) > 0) {
                                                        foreach ($preProccInjector as $keyInjecor => $valInjector) {
                                                            $hasil[$keyInjecor] = isset($sentParams['static'][$valInjector]) ? $sentParams['static'][$valInjector] : "";
                                                        }
                                                    }
                                                }
                                                arrPrint($hasil);
                                                $patchers[$gateName][$gtCtr] = $hasil;
//                                                $patchers[$gateName][] = $hasil;
                                            }
                                            //</editor-fold>


                                            if ($kekurangans[$pID] == 0) {
                                                unset($tmpCopy[$rowCtr]);
                                                break;
                                            }
                                        }
//                                        $lastGtCtr = $gtCtr;
//                                        echo("<br>kekurangans setelah ambil");
//                                        arrPrint($kekurangans);
//                                        arrPrint($needles);
                                    }
//                                    else {
//                                        echo("<br>---- produk ID TIDAK cocok");
//                                        echo("<br>produk_id != pID " . $row->produk_id . "  ----  " . $pID);
//                                        mati_disini("produk_id != pID :: TIDAK sama STOPP...");
//                                        break;
//                                    }
                                }
                                $rowCtr++;

                                echo("<br>-- cetak kekurangans pID: " . $pID . " = " . $kekurangans[$pID]);
                            }


                            if ($rowCtr > sizeof($tmp)) {
                                $nyerah[$pID] = true;
//                                echo("<br>baris fifo ke $rowCtr, padahal jumlah barisnya " . sizeof($tmp));
//                                echo("<br>NYERAHHH... fifo untuk barang $pID tidak ada yang mau DIPAKE!");
                                die("UNABLE TO EXAMINE FIFO");
                                break;
                            }
                        }
//                        echo("<br>cetak kekurangan dan nyerah :: " . $nyerah[$pID]);
//                        arrPrint($kekurangans);
                    }
                }


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
                    $b = new MdlFifoSupplies();
                    $b->updateData(array("id" => $upSpec['id']), $updateData);
                    cekMerah($this->db->last_query());
                }
            }
        }

//        mati_disini(get_class($this));

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