<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoValas extends CI_Model
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
            // cekOrange("cetak inParams");
            // arrPrint($inParams);
            // matiHEre();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $needlesNama[$pSpec['extern_id']] = $pSpec['extern_nama'];

                    $ids[] = $pSpec["extern_id"];

                    if (isset($pSpec['cash_methode']) && ($pSpec['cash_methode'] == "valas")) {
                        $run_fifo = true;
                    }
                    elseif (isset($pSpec['cash_methode']) && ($pSpec['cash_methode'] == "cash")) {
                        $run_fifo = false;
                    }
                    else {
                        $run_fifo = true;
                    }
                }
            }


            if ($run_fifo == true) {

                $this->load->model("Mdls/MdlFifoValas");
                foreach ($needles as $ppID => $keperluan) {
                    $bb = new MdlFifoValas();
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
                    $kurang = $keperluan - $tersedia;
//                    if ($keperluan > $tersedia) {
                    if (round($kurang) > 0) {
                        $msg = "Jumlah stok " . $needlesNama[$ppID] . " $keperluan > $tersedia tidak cukup**.";

                        die(lgShowAlert($msg));
                        mati_disini("KURANGGGGG.... perlu: $keperluan - tersedia: $tersedia, [$kurang]");
                    }
                }

                $b = new MdlFifoValas();
                $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
                $b->addFilter("unit>0");
                $tmp = $b->lookupAll()->result();
                cekHere("FIFO: " . $this->db->last_query());

                if (sizeof($tmp) > 0) {
                    $markedIDs = array();
                    $pCtr = 0;
//                    cekHitam("cetak neddle-----");
//                    arrprint($needles);
                    foreach ($needles as $pID => $jml) {
//                        $pCtr++;
                        if (!isset($fullfills[$pID])) {
                            $fullfills[$pID] = 0;
                        }
                        $kekurangans[$pID] = $needles[$pID];

                        $gtCtr = $pID * $pID;
//                        $xCtr = 0;
//                    cekMerah("memeriksa ketersediaan untuk $pID, kekurangannya " . $kekurangans[$pID]);
                        $nyerah = false;
//                        while ($kekurangans[$pID] > 0 || $nyerah) {
                        while (round($kekurangans[$pID]) > 0 || $nyerah) {
                            $rowCtr = 0;
                            foreach ($tmp as $row) {
//                            $rowCtr++;
                                if (!in_array($row->id, $markedIDs)) {
                                    $jmlAvail = $row->unit;
                                    if ((int)$row->produk_id == (int)$pID) {
//                                    echo "memeriksa ID " . $row->id . "...<br>";
//                                        $gtCtr++;
//                                        $pCtr++;
                                        $xCtr++;

                                        if ($jmlAvail >= $kekurangans[$pID]) {
                                            //==jumlah yg tersedia ternyata cukup atau malah lebih

                                            $produk_id = $row->produk_id;
                                            $nama = $row->produk_nama;
                                            $hpp = $row->hpp;
                                            $qty = $jmlDiambil = $kekurangans[$pID];
                                            $subtotal = $hpp * $qty;

                                            $fullfills[$pID] += $jmlDiambil;
                                            $kekurangans[$pID] -= $jmlDiambil;

                                            cekHere("jumlahnya cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
                                            cekHere("kekurangannya sekarang: " . $kekurangans[$pID] . "");

                                            $updatePairs[] = array(
                                                "id" => $row->id,
                                                "produk_id" => $row->produk_id,
                                                "unit" => ($row->unit - $jmlDiambil),
                                                "jml_nilai" => ($row->jml_nilai - ($jmlDiambil * $row->hpp)),
                                                "jml_ot" => ($row->jml_ot + $jmlDiambil),
                                                "jml_nilai_ot" => ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp)),

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
                                                }

//                                                $patchers[$gateName][] = $hasil;
                                                $patchers[$gateName][$xCtr] = $hasil;
                                            }
                                            //</editor-fold>

                                            break;
                                        }
                                        else {
                                            //==jumlahnya kurang bro
                                            $produk_id = $row->produk_id;
                                            $nama = $row->produk_nama;
                                            $hpp = $row->hpp;
                                            $qty = $jmlDiambil = $jmlAvail;
                                            $subtotal = $hpp * $qty;

                                            $fullfills[$pID] = $jmlDiambil;
                                            $kekurangans[$pID] -= $jmlDiambil;

                                            cekHere("jumlahnya tidak cukup, yaitu $jmlAvail, akan diambil $jmlDiambil");
                                            cekHere("kekurangannya sekarang: " . $kekurangans[$pID] . "<br>");

                                            $updatePairs[] = array(
                                                "id" => $row->id,
                                                "produk_id" => $row->produk_id,
                                                "unit" => ($row->unit - $jmlDiambil),
                                                "jml_nilai" => ($row->jml_nilai - ($jmlDiambil * $row->hpp)),
                                                "jml_ot" => ($row->jml_ot + $jmlDiambil),
                                                "jml_nilai_ot" => ($row->jml_nilai_ot + ($jmlDiambil * $row->hpp)),

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
                                                }

//                                                $patchers[$gateName][] = $hasil;
                                                $patchers[$gateName][$xCtr] = $hasil;
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
                        $b = new MdlFifoValas();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                }

                if (sizeof($updatePairs) > 0) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                cekPink2("fifo valas tidak running karena methode BUKAN valas");
                return true;
            }
        }


    }

    public function exec()
    {
        return $this->result;
    }
}