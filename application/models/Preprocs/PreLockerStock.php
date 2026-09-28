<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerStock extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array(//        "hpp" => "hpp",
    );
    private $inParams;
    private $outParams;
    private $result;

    //<editor-fold desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
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

        arrPrint($inParams);

        if (sizeof($inParams) > 0) {
            $needles = array();
            $ids = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    if (isset($pSpec['kategori_id']) && $pSpec['kategori_id'] == "4") {

                    }
                    else {
                        $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                        $ids[] = $pSpec["extern_id"];
                    }
                }

            }
            cekUngu("cetak NEEDLES");
            $this->load->model("Mdls/MdlLockerStock");
            //region cek yang aktif
            if (sizeof($ids) > 0) {
                $b = new MdlLockerStock();
                $b->addFilter("state='active'");
                $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
                $tmp = $b->lookupAll()->result();
            }
            else {
                $tmp = array();
            }
            cekHitam(__LINE__ . " === " . $this->db->last_query());
            //endregion

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                cekBiru("ada lokernya");
//                arrPrint($this->resultParams);
//                matiHere();
                $patchers = array();
                foreach ($tmp as $row) {
                    foreach ($this->resultParams as $key => $val) {
                        $patchers[$row->produk_id][$key] = $row->$val;
                    }
                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        $after_jml = ($row->jumlah - $needles[$row->produk_id]);
                        if ($after_jml < 0) {
//                            $msg = "Item " . $row->nama . ", state active terdeteksi minus ($after_jml)";
                            $msg = "Item " . $row->nama . ", stok tidak cukup. Stok tersedia " . $row->jumlah . ", jumlah yang anda input " . $needles[$row->produk_id] . ". Silahkan cek kembali stok anda.";
                            mati_disini(($msg));
                            die(lgShowAlert($msg));
                        }
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jumlah" => $after_jml,
                            "nama" => $row->nama,
                            "satuan" => $row->satuan,
                        );
                    }
                }


                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $b = new MdlLockerStock();
                        $updateData = $upSpec;
                        unset($updateData["id"]);

                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah("update locker pertama " . __LINE__ . " --- " . $this->db->last_query());

                        $exHold = $b->cekLoker($sentParams['static']['cabang_id'], $upSpec['produk_id'], "hold", 0, $master_id, $sentParams['static']['gudang_id']);
                        cekHitam($this->db->last_query());
                        if (sizeof($exHold) > 0) {//===ada, berarti diupdate jumlah holdnya (penambahan jumlah hold)

                            $after_jml = ($exHold['jumlah'] + $needles[$upSpec['produk_id']]);
                            if ($after_jml < 0) {
                                $msg = "Item " . $exHold['nama'] . ", state hold terdeteksi minus ($after_jml), masterID:$master_id";
                                matiHere($msg);
//                                die(lgShowAlert($msg));
                            }

                            $b = new MdlLockerStock();
                            $b->updateData(
                                array("id" => $exHold['id']),
                                array(
                                    "jumlah" => $after_jml,
                                )
                            );
                            cekMerah("update locker kedua " . $this->db->last_query());
                        }
                        else {//===tidak ada, berarti insert
                            $b->addData(array(
                                    "cabang_id" => $sentParams['static']['cabang_id'],
                                    "produk_id" => $upSpec['produk_id'],
                                    "nama" => $upSpec['nama'],
                                    "satuan" => $upSpec['satuan'],
                                    "state" => "hold",
                                    "jumlah" => $needles[$upSpec['produk_id']],
                                    "transaksi_id" => $master_id,
                                )
                            );
                            cekMerah($this->db->last_query());
                        }
                    }
                }


//                $this->result = $patchers;
                $this->result = $updatePairs;

                //======================
            }
            else {
                if (count($ids) > 1) {
                    matiHEre("error on exec locker produk code " . __LINE__ . " Fn: " . __FUNCTION__);
                }
                //untuk baypas penjualan jasa
                if (isset($sentParams['static']['kategori_id']) && $sentParams['static']['kategori_id'] == "4") {
                    $updatePairs[] = array(
//                            "id" => $row->id,
                        "state" => "active",
                        "jenis" => "produk",
                        "stock_locker" => "stock",
                        "produk_id" => $sentParams['static']['extern_id'],
                        "jumlah" => 0,
                        "nama" => $sentParams['static']['extern_nama'],
                        "cabang_id" => $sentParams['static']['cabang_id'],
                        "gudang_id" => $sentParams['static']['gudang_id'],
//                            "satuan" => $row->satuan,
                    );

//                    $b->addData(array(
//                            "cabang_id" => $sentParams['static']['cabang_id'],
//                            "gudang_id" => $sentParams['static']['gudang_id'],
//                            "produk_id" => $sentParams['static']['extern_id'],
//                            "nama" => $sentParams['static']['extern_nama'],
//                            "satuan" =>isset($sentParams['static']['satuan']) ? $sentParams['static']['satuan']:"",
//                            "state" => "hold",
//                            "jumlah" => $sentParams['static']['produk_qty'],
//                            "transaksi_id" => $master_id,
//                        )
//                    );
                    cekMerah($this->db->last_query());
                    $this->result = $updatePairs;
                }
                else {
                    die(lgShowAlert("stock product " . $sentParams['static']['extern_nama'] . " not available."));
                    $this->result = array();
                }
            }
        }
        else {
            cekBiru("tidak ada lokernya");
            $this->result = array();
        }
//        if (sizeof($updatePairs) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
        return true;
    }

    public function exec()
    {
//        arrPrint($this->result);
//        matiHere();
        return $this->result;
    }
}