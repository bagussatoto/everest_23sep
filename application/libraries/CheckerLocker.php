<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class CheckerLocker
{

    protected $sesId;
    protected $cabangId;
    protected $gudangId;
    protected $execute;


    public function getExecute()
    {
        return $this->execute;
    }

    public function setExecute($execute)
    {
        $this->execute = $execute;
    }

    public function getSesId()
    {
        return $this->sesId;
    }

    public function setSesId($sesId)
    {
        $this->sesId = $sesId;
    }

    public function getCabangId()
    {
        return $this->cabangId;
    }

    public function setCabangId($cabangId)
    {
        $this->cabangId = $cabangId;
    }

    public function getGudangId()
    {
        return $this->gudangId;
    }

    public function setGudangId($gudangId)
    {
        $this->gudangId = $gudangId;
    }


    //----------------------
    public function __construct()
    {

        $this->CI =& get_instance();

        $this->CI->load->model("Coms/ComRekeningPembantuKas");
        $this->CI->load->model("Mdls/MdlLockerValue");
        $this->CI->load->model("Mdls/MdlCabang");
    }

    //locker kas----------------------
    public function lockerKas()
    {
        $branchID = $this->cabangId;
        $whID = 0;
        $items = array();
        $products = array();
        $ids = array();
        $ffStocks = array();
        $rekening = "kas";

        $c = new ComRekeningPembantuKas();
        $c->addFilter("cabang_id='$branchID'");
        $tmp = $c->fetchBalances($rekening);
//        showLast_query("pink");
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $products[$row->extern_id] = array(
                    "name" => $row->extern_nama,
                    "stock" => $row->debet,
                );
                $ids[] = $row->extern_id;
            }
        }

        $l = new MdlLockerValue();
        $l->addFilter("cabang_id='$branchID'");
        $l->addFilter("jenis='kas'");
        $l->addFilter("gudang_id='0'");
        $lStocks = $l->fetchStates2($branchID, 0, $ids);
//        showLast_query("biru");

        if (sizeof($products) > 0) {

            $this->CI->db->trans_start();
            $nof = 0;
            foreach ($products as $pID => $pSpec) {

                $jmlActive = isset($lStocks[$pID]['active']) ? $lStocks[$pID]['active'] : 0;
                $jmlHold = isset($lStocks[$pID]['hold']) ? $lStocks[$pID]['hold'] : 0;
                $jmlHoldTrID = isset($lStocks[$pID]['hold_trID']) ? $lStocks[$pID]['hold_trID'] : 0;
                $jmlSold = isset($lStocks[$pID]['sold']) ? $lStocks[$pID]['sold'] : 0;

                $jmlRek = isset($pSpec['stock']) ? $pSpec['stock'] : 0;
                $jmlLoker = ($jmlActive + $jmlHold + $jmlHoldTrID);

                $jmlActive_new = $pSpec['stock'] - $jmlHold - $jmlHoldTrID;
                $selisih = $jmlRek - ($jmlActive + $jmlHoldTrID);

                $items[] = array(
                    "pID" => $pID,
                    "pName" => $pSpec['name'],
                    "fifoReal" => 0,
                    "fifoAvg" => 0,
                    "debet" => $pSpec['stock'],
                    "active" => $jmlActive,
                    "hold" => $jmlHold,
                    "holdTrID" => $jmlHoldTrID,
                    "sold" => $jmlSold,
                    "selisih" => $selisih,
                    "totalLocker" => $jmlLoker,
                );

                $nof++;
                if ($pSpec['stock'] != $jmlLoker) {
                    $where = array(
                        "jenis" => "kas",
                        "jenis_locker" => "value",
                        "cabang_id" => $branchID,
                        "gudang_id" => $whID,
                        "produk_id" => $pID,
                        "transaksi_id" => 0,
                        "state" => "active",
                    );
                    $data = array(
                        "nilai" => $jmlActive_new,
                    );
                    $ll = new MdlLockerValue();
                    $ll->updateData($where, $data);
                    $update = $this->CI->db->last_query() . ";";
//                    cekKuning($update);
                }
                else {
//                    cekHijau("[$pID] rek kas dan locker kas cocok");
                }
            }
            if ($this->execute == true) {
                $this->CI->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            }
        }

        $resultLib = array(
            "items" => isset($items) ? $items : array(),

        );
        return $resultLib;
    }

}
