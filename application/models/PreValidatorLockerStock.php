<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreValidatorLockerStock extends CI_Model
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

    public function pair($master_id, $sentParams)
    {
        if (!is_array($sentParams)) {
            die("params required!");
        }

        $needles = array();
        foreach ($sentParams as $pSpec) {
            $needles[$pSpec['id']] = $pSpec['qty'];
        }

//        echo "needles<br>";
//        arrprint($needles);

        $this->load->model("Mdls/MdlLockerStock");
        //region cek yang aktif
        $b = new MdlLockerStock();
        $ids = array_column($sentParams, "id");
        $b->addFilter("state='active'");
        $b->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $b->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $b->addFilter("produk_id in (" . implode(",", $ids) . ")");
        $tmp = $b->lookupAll()->result();
//        cekHitam($this->db->last_query());
        //endregion

//        $exActive=cekLoker($cab, $prod, $state, $oleh = 0, $transaksi_id = 0);

        $arrPairs['items'] = array();
        $arrPairs['items2'] = array();
        $fullfills = array();
        $arrKekurangan = array();
        $arrStock = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                //==update yg sesuai
                if (array_key_exists($row->produk_id, $needles)) {
                    $arrStock[$row->produk_id] = $row->jumlah;
                    if ($row->jumlah >= $needles[$row->produk_id]) {
                        $fullfills[$row->produk_id] = $needles[$row->produk_id];
                    }
                    else {
                        if ($row->jumlah > 0) {
                            $fullfills[$row->produk_id] = $row->jumlah;
                            $arrKekurangan[$row->produk_id] = $needles[$row->produk_id] - $row->jumlah;
                        }
                    }
                }
            }
            foreach ($needles as $key => $val) {
                if (isset($fullfills[$key])) {
                    if ($fullfills[$key] >= $val) {
                        $tmp1[$key] = array(
                            "produk_id" => $key,
                            "produk_ord_jml" => $val,
                        );
                    }
                    else {
                        $tmp1[$key] = array(
                            "produk_id" => $key,
                            "produk_ord_jml" => $fullfills[$key],
                        );
                        $tmp2[$key] = array(
                            "produk_id" => $key,
                            "produk_ord_jml" => $val - $fullfills[$key],
                            "current_stock" => isset($arrStock[$key]) ? $arrStock[$key] : 0,
                        );
                    }
                }
                else {
                    $tmp2[$key] = array(
                        "produk_id" => $key,
                        "produk_ord_jml" => $val,
                        "current_stock" => isset($arrStock[$key]) ? $arrStock[$key] : 0,
                    );
                }
            }
            if (isset($tmp1)) {
                $arrPairs['items'] = $tmp1;
            }
            if (isset($tmp2)) {
                $arrPairs['items2'] = $tmp2;
            }
        }
        else {
            foreach ($needles as $iID => $iJml) {
                $arrPairs['items2'][$iID] = array(
                    "produk_id" => $iID,
                    "produk_ord_jml" => $iJml,
                    "current_stock" => 0,
                );
            }

        }
        $this->result = $arrPairs;
    }

    public function exec()
    {
        return $this->result;
    }
}