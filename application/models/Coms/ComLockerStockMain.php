<?php


class ComLockerStockMain extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        "jenis",
        "produk_id",
        "cabang_id",
        "nama",
        "satuan",
        "state",
        "jumlah",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
        "status",
        "trash",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams[0] = $inParams;
        arrPrintPink($this->inParams);

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];

                $_preValue = $this->cekPreValue(
                    $paramAsli['static']['jenis'],
                    $paramAsli['static']['cabang_id'],
                    $paramAsli['static']['produk_id'],
                    $paramAsli['static']['state'],
                    $defaultOlehID,
                    $defaultTransID,
                    $defaultGudangID
                );

                if ($_preValue != null) {

                    $this->outParams[$lCounter]["jumlah"] = ($paramAsli['static']['jumlah'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "update";

                    if ($this->outParams[$lCounter]["jumlah"] < 0) {

                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['jumlah'] . ", avail: " . $_preValue;
                        die(lgShowAlert($msg));
                    }
                }
                else {

                    $this->outParams[$lCounter]["mode"] = "new";
                }
            }
        }

        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerStockMain");
        $l = new MdlLockerStockMain();

//        $l->setFilters(array());
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

//        $tmp = $l->lookupAll()->result();
//        cekMerah($this->db->last_query() . " # " . count($tmp));
//        arrPrint($l->getfilters());
//        matiHEre();
        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }

        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
        showLast_query("biru");
        cekHitam(sizeof($tmp));

//        $nilai = $tmp['qty_debet'];
        if (sizeof($tmp) > 0) {
//            foreach ($tmp as $row) {
            $result = $tmp['jumlah'];
//                $result = $row->jumlah;
//            }
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {

        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlLockerStockMain");
                $l = new MdlLockerStockMain();
                $insertIDs = array();
                $mode = $params['mode'];
                unset($params['mode']);
                switch ($mode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "cabang_id" => $params['cabang_id'],
                            "gudang_id" => $params['gudang_id'],
                            "produk_id" => $params['produk_id'],
                            "state" => $params['state'],
                            "oleh_id" => $params['oleh_id'],
                            "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekPink("LOCKER " . $this->db->last_query());

            }
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        else {
            die("nothing to write down here");
            return false;
        }

    }
}