<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerValue extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $paymentMethod = array(
        "credit",
        "cia",
        "tt_adv",
        "cbd"
    );


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
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
        cekHitam("cetak inParams LockerValue");
        arrPrint($inParams);

        if (sizeof($inParams) > 0) {
            $sentParams = $inParams;
            $patchers = array();
            $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);

            if ((!isset($sentParams['static']['transaksi_id'])) || ($sentParams['static']['transaksi_id'] == 0)) {
                die(lgShowAlert("insufficient transaksiID for " . $inParams['static']['jenis'] . " by 0" . " code " . __LINE__));
            }

            if (in_array($inParams['static']['paymentMethod'], $this->paymentMethod)) {
                $this->load->model("Mdls/MdlLockerValue");
                cekKuning("METHOD CREDIT / CIA / ...");

                //region cek yang aktif
                $b = new MdlLockerValue();
                $b->addFilter("jenis='" . $sentParams['static']['jenis'] . "'"); // rekeningnya
                $b->addFilter("state='" . $sentParams['static']['state'] . "'");
                $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $b->addFilter("produk_id='" . $sentParams['static']['produk_id'] . "'"); // customersnya
                $b->addFilter("transaksi_id='" . $sentParams['static']['transaksi_id'] . "'"); // trID SO
                $tmp = $b->lookupAll()->result();
                cekMerah(__LINE__ . " :: " . $this->db->last_query());
                //endregion

                $updatePairs = array();
                if (sizeof($tmp) > 0) {
                    $patchers = array();

                    if ($tmp[0]->nilai > 0) {
                        if ($sentParams['static']['nilai'] <= $tmp[0]->nilai) {
                            $nilai_dipakai = $sentParams['static']['nilai'];
                            $nilai_tambah = "0";
                            $sisa = ($tmp[0]->nilai - $sentParams['static']['nilai']) > 0 ? ($tmp[0]->nilai - $sentParams['static']['nilai']) : "0";
                        }
                        else {
                            $nilai_dipakai = $tmp[0]->nilai;
                            $nilai_tambah = ($sentParams['static']['nilai'] - $tmp[0]->nilai) > 0 ? ($sentParams['static']['nilai'] - $tmp[0]->nilai) : "0";
                            $sisa = "0";
                        }
                    }
                    else {
                        $nilai_dipakai = "0";
                        $nilai_tambah = $sentParams['static']['nilai'];
                        $sisa = "0";
                    }

                    if ($sisa < 0) {
                        $msg = "insufficient value for " . $sentParams['static']['jenis'] . " code " . __LINE__;
                        die(lgShowAlert($msg));
                    }

                    foreach ($this->resultParams as $gateName => $paramSpec) {
                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$key . "_" . $patchersKey] = $$val;
                        }
                    }

                    $updatePairs[] = array(
                        "id" => $tmp[0]->id,
                        "produk_id" => $tmp[0]->produk_id,
                        "nilai" => $sisa,
                        "nama" => $tmp[0]->nama,
                        "satuan" => $tmp[0]->satuan,
                    );

                    if (sizeof($updatePairs) > 0) {
                        foreach ($updatePairs as $upSpec) {
                            $b = new MdlLockerValue();
                            $updateData = $upSpec;

                            unset($updateData["id"]);

                            $b->updateData(array("id" => $upSpec['id']), $updateData);
                            cekMerah("update locker pertama " . __LINE__ . " --- " . $this->db->last_query());


                            $exHold = $b->cekLoker($sentParams['static']['cabang_id'], $upSpec['produk_id'], "hold", 0, $sentParams['static']['transaksi_id'], $sentParams['static']['gudang_id'], $sentParams['static']['jenis']);
                            if (sizeof($exHold) > 0) {//===ada, berarti diupdate
                                $b = new MdlLockerValue();
                                $b->updateData(
                                    array("id" => $exHold['id']),
                                    array("nilai" => $exHold['nilai'] + $nilai_dipakai)
//                                    array("nilai" => $exHold['nilai'] + $patchers["main"]["nilai_dipakai_" . $patchersKey])
                                );
                                cekKuning("update locker kedua ");
                                cekKuning($this->db->last_query());
                            }
                            else {//===tidak ada, berarti insert
                                $b->addData(array(
                                        "cabang_id" => $sentParams['static']['cabang_id'],
                                        "gudang_id" => $sentParams['static']['gudang_id'],
                                        "produk_id" => $upSpec['produk_id'],
                                        "nama" => $upSpec['nama'],
                                        "satuan" => $upSpec['satuan'],
                                        "state" => "hold",
                                        "nilai" => $nilai_dipakai,
//                                        "nilai" => $patchers["main"]["nilai_dipakai_" . $patchersKey],
                                        "transaksi_id" => $sentParams['static']['transaksi_id'],
                                        "oleh_id" => 0,
                                    )
                                );
                                cekHitam("insert ke locker ");
                                cekHitam($this->db->last_query());
                            }
                        }
                    }

                    $this->result = $patchers;
                }
                else {
                    cekBiru("tidak ada lokernya");

                    $nilai_dipakai = "0";
                    $nilai_tambah = $sentParams['static']['nilai'];

                    foreach ($this->resultParams as $gateName => $paramSpec) {
                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$key . "_" . $patchersKey] = $$val;
                        }
                    }

                    $this->result = $patchers;
                }
            }
            else {
                cekKuning("BUKAN CREDIT...");

                $nilai_dipakai = "0";
                $nilai_tambah = $sentParams['static']['nilai'];

                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        $patchers[$gateName][$key . "_" . $patchersKey] = $$val;
                    }
                }

                $this->result = $patchers;
            }
        }
        else {
            $this->result = array();
        }


        if (sizeof($this->result) > 0) {
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