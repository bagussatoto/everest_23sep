<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComLockerValueItem extends MdlMother
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
        "note",
        "jumlah",
        "nilai",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
//            $paramAsli = $this->inParams;
            foreach ($this->inParams as $paramAsli) {

                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

//                echo "param asli";
//                arrPrint($paramAsli);

                if (isset($paramAsli['static']['transaksi_id'])) {
                    $arrTransaksiID = array();
                    if (isset($paramAsli['static']['transaksi_id'])) {
                        if (base64_decode($paramAsli['static']['transaksi_id'], true) === true) {
                            cekMerah("base64");
                            $arrTransaksiID = array_values(unserialize(base64_decode($paramAsli['static']['transaksi_id'])));
                        }
                        else {
                            if (is_array($paramAsli['static']['transaksi_id'])) {
                                cekMerah("bukan base64, array");
                                $arrTransaksiID = array_values(array($paramAsli['static']['transaksi_id']));
                            }
                            else {
                                cekMerah("bukan base64, bukan array");
                                $arrTransaksiID = array($paramAsli['static']['transaksi_id']);
                            }
                        }
                    }

                    if (sizeof($arrTransaksiID) == 1) {
                        $transaksiID = $arrTransaksiID[0];
                    }
                    else {
                        $transaksiID = 0;
                    }

                    $this->outParams[$lCounter]["transaksi_id"] = $transaksiID;
                }
                else {
                    $transaksiID = $paramAsli['static']['transaksi_id'];
                }

                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($transaksiID) ? $transaksiID : 0;
                $defaultGudangID = isset($paramAsli['static']['gudang_id']) ? $paramAsli['static']['gudang_id'] : 0;
                if ($paramAsli['static']['cabang_id'] == 0) {
                    if (isset($paramAsli["cabang_id_label"])) {
                        $msg_label = $paramAsli["cabang_id_label"];
                    }
                    else {
                        $msg_label = "transaksi gagal disimpan karena sesi anda habis. silahkan memeriksa ulang transaksi anda atau melakukan relogin. ";
                    }
                    $msg = "$msg_label code: " . __LINE__;
                    mati_disini($msg);
                }
                $_preValue = $this->cekPreValue(
                    $paramAsli['static']['jenis'],
                    $paramAsli['static']['cabang_id'],
                    $paramAsli['static']['produk_id'],
                    $paramAsli['static']['state'],
                    $defaultOlehID,
                    $defaultTransID,
                    $defaultGudangID);
cekBiru($this->db->last_query());
cekHitam($paramAsli['static']['nilai']);
arrPrint($_preValue);
                if ($_preValue != null) {


                    $this->outParams[$lCounter]["nilai"] = ($paramAsli['static']['nilai'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "update";

                    if ($this->outParams[$lCounter]["nilai"] < 0) {
                        $msg = "Transaksi gagal, karena " . $paramAsli['static']['jenis'] . " " . $paramAsli['static']['nama'] . " tidak cukup. Saldo " . ($_preValue + 0);
                        die(lgShowAlert($msg));
                    }
                }
                else {

                    $this->outParams[$lCounter]["nilai"] = ($paramAsli['static']['nilai'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "new";

                    if ($this->outParams[$lCounter]["nilai"] < 0) {
                        $msg = "Transaksi gagal, karena " . $paramAsli['static']['nama'] . " state " . $paramAsli['static']['state'] . ", tidak cukup. avail: " . $_preValue . ", needed: " . $this->outParams[$lCounter]["nilai"];
                        die(lgShowAlert($msg));
                    }

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

        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->nilai;
            }
        }
        else {
            $result = 0;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {

        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $mode = isset($params["mode"]) ? $params["mode"] : "";
//                arrPrint($params);
//                mati_disini($mode);
                $this->load->model("Mdls/MdlLockerValue");
                $l = new MdlLockerValue();
                $insertIDs = array();
                switch ($mode) {
                    case "new":
                        unset($params["mode"]);
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        unset($params["mode"]);
                        $insertIDs[] = $l->updateData(
                            array(
                                "cabang_id" => $params['cabang_id'],
                                "gudang_id" => $params['gudang_id'],
                                "produk_id" => $params['produk_id'],
                                "state" => $params['state'],
                                "oleh_id" => $params['oleh_id'],
                                "transaksi_id" => $params['transaksi_id'],
                                "jenis" => $params['jenis'],
                            ),
                            $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }

//mati_disini(get_class($this));
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

    public function fetchBalances($rek, $key = "", $sortBy = "", $sortMode = "ASC")
    {//==memanggil saldo2 dari rekening tertentu

        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis=$rek");

        if ($sortBy != "") {
            $this->db->order_by($sortBy, $sortMode);
        }
        else {
//            $this->db->order_by("UPPER(" . $this->tableName . ".id)", "desc");
            $this->db->order_by("id", "asc");
        }

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }


        if ($key != "") {
            $this->createSmartSearch($key, array("extern_nama"));
        }


        $result = $l->lookupAll();

        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[] = $row;
            }
        }

        // yang direturn hasil dari tabel, apa adanya...
        return $results;

    }
}