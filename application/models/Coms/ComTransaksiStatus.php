<?php

class ComTransaksiStatus extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "transaksi_status";

    }

    private $outFields = array( // dari tabel rek_cache
        "cabang_id",
        "cabang_nama",
        "jenis",
        "jenis_master",
        "oleh_id",
        "oleh_nama",
        "suppliers_id",
        "suppliers_nama",
        "customers_id",
        "customers_nama",
        "id_master",
        "transaksi_nilai",
        "diskon_nilai",
        "ppn_nilai",
        "transaksi_net",
        "transaksi_dibayar",
        "transaksi_reject",
        "transaksi_fullfillment",
        "transaksi_dikirim",
        "transaksi_saldo",
//        "transaksi_no",
        "transaksi_id",
        "fulldate",
        "dtime",
        "status_id",
        "status_nama",
    );

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        arrPrintCyan($inParams);

        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;// id so, 5822so
            $defaultJenis = isset($this->inParams['static']['jenis']) ? $this->inParams['static']['jenis'] : 0;// id so, 5822so
            $defaultTransNomer = isset($this->inParams['static']['transaksi_no']) ? $this->inParams['static']['transaksi_no'] : 0;// id so, 5822so
            $defaultReferenceID = isset($this->inParams['static']['referensi_id']) ? $this->inParams['static']['referensi_id'] : 0;// id so 5822so pada 4464, 749
            $defaultNilai = isset($this->inParams['static']['nilai']) ? $this->inParams['static']['nilai'] : 0;
            $transaksi_dibayar = isset($this->inParams['static']['transaksi_dibayar']) ? $this->inParams['static']['transaksi_dibayar'] : 0;
            $transaksi_reject = isset($this->inParams['static']['transaksi_reject']) ? $this->inParams['static']['transaksi_reject'] : 0;
            $transaksi_fullfillment = isset($this->inParams['static']['transaksi_fullfillment']) ? $this->inParams['static']['transaksi_fullfillment'] : 0;
            $transaksi_dikirim = isset($this->inParams['static']['transaksi_dikirim']) ? $this->inParams['static']['transaksi_dikirim'] : 0;
            $transaksi_nett = isset($this->inParams['static']['transaksi_nett']) ? $this->inParams['static']['transaksi_nett'] : 0;
            $rejection = isset($this->inParams['static']['rejection']) ? $this->inParams['static']['rejection'] : 0;

            $defaultTransID_cek = ($defaultReferenceID > 0) ? $defaultReferenceID : $defaultTransID;
            if ($defaultTransID_cek > 0) {

//            $_preValue = $this->cekPreValue($defaultJenis, $defaultTransID);
                $_preValue = $this->cekPreValue($defaultTransID_cek);
                arrPrintWebs($_preValue);
                if ($_preValue != null) {
                    $this->writeMode = "update";
                    $tblid = $_preValue->id;
                    $prev_transaksi_dibayar = $_preValue->transaksi_dibayar;
                    $prev_transaksi_reject = $_preValue->transaksi_reject;
                    $prev_transaksi_fullfillment = $_preValue->transaksi_fullfillment;
                    $prev_transaksi_dikirim = $_preValue->transaksi_dikirim;
                    $prev_transaksi_saldo = $_preValue->transaksi_saldo;
                    $this->outParams[$lCounter]["id"] = $tblid;
                    if ($rejection == 0) {// bukan reject

                        $this->outParams[$lCounter]["transaksi_dibayar"] = $prev_transaksi_dibayar + $transaksi_dibayar;
                        $this->outParams[$lCounter]["transaksi_reject"] = $prev_transaksi_reject + $transaksi_reject;
                        $this->outParams[$lCounter]["transaksi_fullfillment"] = $prev_transaksi_fullfillment + $transaksi_fullfillment;
                        $this->outParams[$lCounter]["transaksi_dikirim"] = $prev_transaksi_dikirim + $transaksi_dikirim;
                        $this->outParams[$lCounter]["transaksi_saldo"] = $prev_transaksi_saldo - $transaksi_dibayar - $transaksi_reject - $transaksi_fullfillment;
                        $transaksi_saldo = $prev_transaksi_saldo - $transaksi_dibayar - $transaksi_reject - $transaksi_fullfillment;

                        cekHere("$prev_transaksi_saldo - $transaksi_dibayar - $transaksi_reject - $transaksi_fullfillment === $transaksi_saldo");

                        if ($this->outParams[$lCounter]["transaksi_saldo"] < 100) {
                            $this->outParams[$lCounter]["status_id"] = "12";
                            $this->outParams[$lCounter]["status_nama"] = "lunas";
                        }
                        else {
                            $this->outParams[$lCounter]["status_id"] = "11";
                            $this->outParams[$lCounter]["status_nama"] = "belum lunas";
                        }
                    }
                    else {
                        $transaksi_reject = $prev_transaksi_saldo;
                        $this->outParams[$lCounter]["transaksi_dibayar"] = $prev_transaksi_dibayar + $transaksi_dibayar;
                        $this->outParams[$lCounter]["transaksi_reject"] = $prev_transaksi_reject + $transaksi_reject;
                        $this->outParams[$lCounter]["transaksi_fullfillment"] = $prev_transaksi_fullfillment + $transaksi_fullfillment;
                        $transaksi_saldo = $prev_transaksi_saldo - $transaksi_dibayar - $transaksi_reject - $transaksi_fullfillment;
                        $this->outParams[$lCounter]["transaksi_saldo"] = $transaksi_saldo;

                        cekHere("$prev_transaksi_saldo || $transaksi_dibayar || $transaksi_reject || $transaksi_fullfillment");

                        $this->outParams[$lCounter]["status_id"] = "13";
                        $this->outParams[$lCounter]["status_nama"] = "rejected";

                    }

                }
                else {
                    $this->writeMode = "new";
                    foreach ($this->inParams['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }
                    $this->outParams[$lCounter]["transaksi_saldo"] = $transaksi_nett;
                    $this->outParams[$lCounter]["transaksi_id"] = $defaultTransID;
                    $this->outParams[$lCounter]["nomer"] = $defaultTransNomer;
                    $this->outParams[$lCounter]["status_id"] = "10";
                    $this->outParams[$lCounter]["status_nama"] = "belum dibayar";
                    $this->outParams[$lCounter]["nomer"] = $defaultTransNomer;
                }
            }

        }
//        arrPrintPink($this->outParams);
        return true;
//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
    }

//    private function cekPreValue($jenisTr, $transaksiID = 0)
    private function cekPreValue($transaksiID = 0)
    {

//        $this->addFilter("jenis='$jenisTr'");
        $this->addFilter("transaksi_id='$transaksiID'");
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($this->tableName)
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row;
            }
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
            $tableName = $this->tableName;

            foreach ($this->outParams as $ctr => $params) {
                $tbl_id = $params["id"];
                unset($params["id"]);

                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $this->db->insert($tableName, $params);
                        $insertIDs[] = $this->db->insert_id();
                        break;
                    case "update":
                        $this->db->where('id', $tbl_id);
                        $insertIDs[] = $this->db->update($tableName, $params);
//                        $insertIDs[] = $l->updateData(array(
//                            "id" => $tbl_id,
//                        ), $params);
//                    
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }


//            mati_disini(__LINE__);

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        else {
            return true;
//            die("nothing to write down here");
//            return false;
        }

    }


}