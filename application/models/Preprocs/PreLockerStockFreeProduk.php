<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerStockFreeProduk extends CI_Model
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

    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
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
            $needles = array();
            $ids = array();
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
//                    $ids[] = $pSpec["extern_id"];

                }
                $this->load->model("Mdls/MdlLockerStock");
                arrPrintPink($sentParams);
                $produk_id = $sentParams['static']['produk_id'];

                //region cek yang aktif
                $b = new MdlLockerStock();
                $b->setFilters(array());
                $b->addFilter("state='" . $sentParams['static']['state'] . "'");
                $b->addFilter("jenis='" . $sentParams['static']['jenis'] . "'");
                $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                $b->addFilter("transaksi_id='" . $sentParams['static']['transaksi_id'] . "'");
                $b->addFilter("produk_id='" . $sentParams['static']['extern_id'] . "'");

                $localFilters = array();
                if (sizeof($b->getfilters()) > 0) {
                    foreach ($b->getfilters() as $f) {
                        $tmpArr = explode("=", $f);
                        $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                    }
                }
                $query = $this->db->select()
                    ->from($b->getTableName())
                    ->where($localFilters)
                    ->limit(1)
                    ->get_compiled_select();
                $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                cekHitam($this->db->last_query());
//                cekMerah($subtmp);
                if ((sizeof($subtmp) == 0) || ($subtmp == NULL)) {
                    $msg = "Stok " . $sentParams['static']['extern_nama'] . " tidak cukup. Silahkan diperiksa stok anda.";
                    matiHere($msg);
                }
                $tmp[] = (object)$subtmp;
                if (count($tmp) > 0) {
//                    arrprint($tmp);
//                    arrPrint($this->resultParams);
//                    matiHere(__LINE__);
                    $updatePairs = array();
                    foreach ($tmp as $row) {
                        if ($row->jumlah > 0) {
                            if($row->jumlah - $needles[$row->produk_id] < 0){
                                matiHEre("Gagal menyimpan data free poduk code ".__LINE__);
                            }
                            foreach ($this->resultParams as $gateName => $paramSpec) {
//                                foreach ($paramSpec as $key => $val) {
//                                    $patchers[$gateName][$row->produk_id][$key] = $row->$val;
//                                }
//                                $patchers[$gateName][$row->produk_id]["qty"] = $row->jumlah;
//                                $patchers[$gateName][$row->produk_id]["jml"] = $row->jumlah;
//                                $patchers[$gateName][$row->produk_id]["harga"] = $sentParams['static']['harga'];
//                                $patchers[$gateName][$row->produk_id]["hpp"] = $sentParams['static']['harga'];
//                                $patchers[$gateName][$row->produk_id]["produk_rel_harga"] = $sentParams['static']['harga'];
                                foreach ($paramSpec as $key => $val) {
                                    $patchers[$gateName][$produk_id][$key] = $row->$val;
                                }
//                                $patchers[$gateName][$produk_id]["qty"] = $row->jumlah;
//                                $patchers[$gateName][$produk_id]["jml"] = $row->jumlah;
                                //patcher qty dab jml dimatin karena lupa kegunaan nya
//                                $patchers[$gateName][$produk_id]["harga"] = $sentParams['static']['harga'];
//                                $patchers[$gateName][$produk_id]["hpp"] = $sentParams['static']['harga'];
                                $patchers[$gateName][$produk_id]["produk_rel_harga"] = $sentParams['static']['harga'];
                            }
                            if (array_key_exists($row->produk_id, $needles)) {
                                $updatePairs[] = array(
                                    "id" => $row->id,
//                                "produk_id" => $row->produk_id,
                                    "jumlah" => ($row->jumlah - $needles[$row->produk_id]),
                                );
                            }

                        }
                        else {
                            foreach ($this->resultParams as $gateName => $paramSpec) {
//                                $patchers[$gateName][$row->produk_id]["qty"] = 0;//id bonus
//                                $patchers[$gateName][$row->produk_id]["jml"] = 0;
//                                $patchers[$gateName][$row->produk_id]["harga"] = 0;
//                                $patchers[$gateName][$row->produk_id]["hpp"] = 0;
                                $patchers[$gateName][$produk_id]["qty"] = 0;//id produk
                                $patchers[$gateName][$produk_id]["jml"] = 0;
//                                $patchers[$gateName][$produk_id]["harga"] = 0;
//                                $patchers[$gateName][$produk_id]["hpp"] = 0;
                                $patchers[$gateName][$produk_id]["produk_rel_harga"] = 0;
                            }
                            if (array_key_exists($row->produk_id, $needles)) {
                                $updatePairs[] = array(
                                    "id" => $row->id,
                                    "jumlah" => 0,
                                );
                            }
                        }

                    }
//arrPrint($updatePairs);
                    if (sizeof($updatePairs) > 0) {
                        foreach ($updatePairs as $upSpec) {
                            $updateData = $upSpec;
                            unset($updateData["id"]);
                            $b = new MdlLockerStock();
                            $b->setFilters(array());
                            $b->updateData(array("id" => $upSpec['id']), $updateData);
                            cekMerah($this->db->last_query());
                        }
                    }
                }
                else {
                    matiHEre("undefine pre stok free produk " . __LINE__);//stok hold harusnya masuk sat otorisasi pembelian silahkan cek postproc
                }
            }
            //endregion
        }
        else {
            cekBiru("tidak ada lokernya");
            $this->result = array();
        }

        if (isset($patchers) && sizeof($patchers) > 0) {
            $this->result = $patchers;
        }
//
//
//        arrPrintKuning($this->result);
//        mati_disini(__LINE__);
//
//
        if (sizeof($updatePairs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function _prevalue()
    {

    }

    public function exec()
    {
//        arrPrint($this->result);
//        matiHere();
        return $this->result;
    }
}