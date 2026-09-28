<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerPettycash extends CI_Model
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

        cekHitam("cetak inParams LockerValue");
        arrPrint($inParams);

        if (sizeof($inParams) > 0) {
            $sentParams = $inParams;

            $patchers = array();


            $cabangID = isset($sentParams['static']['cabang_id']) ? $sentParams['static']['cabang_id'] : 0;
            $cabang2ID = isset($sentParams['static']['cabang2_id']) ? $sentParams['static']['cabang2_id'] : 0;
            $olehID = isset($sentParams['static']['oleh_id']) ? $sentParams['static']['oleh_id'] : 0;


            $this->load->model("Mdls/MdlLockerValue");


            //region cek yang aktif
            $b = new MdlLockerValue();
            $b->addFilter("jenis='" . $sentParams['static']['jenis'] . "'"); // rekeningnya
            $b->addFilter("state='" . $sentParams['static']['state'] . "'");
            $b->addFilter("cabang_id='$cabangID'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id='" . $sentParams['static']['produk_id'] . "'");
            $b->addFilter("transaksi_id='" . $sentParams['static']['transaksi_id'] . "'");
            $b->addFilter("oleh_id='$olehID'");
            $tmp = $b->lookupAll()->result();

            cekMerah($this->db->last_query());
            arrPrint($tmp);
            //endregion

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                $patchers = array();

                $preValue = $tmp[0]->nilai;
                $currentValue = $sentParams['static']['nilai'];
                $afterValue = $preValue + $currentValue;

                if ($afterValue < 0) {
                    $msg = "insufficient value for " . $sentParams['static']['jenis'] . ". avail: " . $preValue;
                    die(lgShowAlert($msg));
                }

                // masih manula lho ini, yang auto belum ketemu, he he he
                if ($cabangID == $cabang2ID) {
                    $kas = $currentValue;
                    $biaya = $currentValue;
                    $biaya2 = 0;
                    $piutang_biaya = 0;
                    $hutang_biaya_ke_pusat = 0;
                }
                else {
                    $kas = $currentValue;
                    $biaya = 0;
                    $biaya2 = $currentValue;
                    $piutang_biaya = $currentValue;
                    $hutang_biaya_ke_pusat = $currentValue;
                }

                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        $patchers[$gateName][$key] = $$val;
                    }
                }

                $updatePairs[] = array(
                    "id" => $tmp[0]->id,
                    "produk_id" => $tmp[0]->produk_id,
                    "nilai" => $afterValue,
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

//                        $exHold = $b->cekLoker($sentParams['static']['cabang_id'], $upSpec['produk_id'], "hold", 0, $sentParams['static']['transaksi_id'], $sentParams['static']['gudang_id'], $sentParams['static']['jenis']);
//                        cekUngu("cetak exHold...");
//                        arrPrint($exHold);
//
//                        if (sizeof($exHold) > 0) {//===ada, berarti diupdate
//                            $b = new MdlLockerValue();
//                            $b->updateData(
//                                array("id" => $exHold['id']),
//                                array("nilai" => $exHold['nilai'] + $currentValue)
//                            );
//                            cekKuning("update locker kedua ");
//                            cekKuning($this->db->last_query());
//                        }
//                        else {//===tidak ada, berarti insert
//                            $b->addData(array(
//                                    "cabang_id" => $cabangID,
//                                    "gudang_id" => $sentParams['static']['gudang_id'],
//                                    "produk_id" => $upSpec['produk_id'],
//                                    "nama" => $upSpec['nama'],
//                                    "satuan" => $upSpec['satuan'],
//                                    "state" => "hold",
//                                    "nilai" => $currentValue,
//                                    "transaksi_id" => $sentParams['static']['transaksi_id'],
//                                    "oleh_id" => 0,
//                                )
//                            );
//                            cekHitam("insert ke locker ");
//                            cekHitam($this->db->last_query());
//                        }
                    }
                }
                $this->result = $patchers;
            }
//            else {
//                cekBiru("tidak ada lokernya");
//
//                $nilai_dipakai = "0";
//                $nilai_tambah = $sentParams['static']['nilai'];
//
//                foreach ($this->resultParams as $gateName => $paramSpec) {
//                    foreach ($paramSpec as $key => $val) {
//                        $patchers[$gateName][$key . "_" . $patchersKey] = $$val;
//                    }
//                }
//
//                $this->result = $patchers;
//            }

        }
        else {
            $this->result = array();
        }

//        cekHere("cetak patcher...");
//        arrPrint($patchers);
//        mati_disini(get_class($this));

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