<?php


class ComLockerValueDetailItem extends MdlMother
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
        "gudang_id",
        "nama",
        "satuan",
        "state",
        "jumlah",
        "nilai",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
        "extern_id",
        "extern_nama",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        arrPrint($inParams);
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $ii => $paramAsli) {

//            $paramAsli = $this->inParams;
//            $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

                if (isset($paramAsli['static']['transaksi_id'])) {
                    $arrTransaksiID = array();
                    if (isset($paramAsli['static']['transaksi_id'])) {
//                    if (base64_decode($paramAsli['static']['transaksi_id'], true) === true) {
                        if (($paramAsli['static']['transaksi_id'] === base64_encode(base64_decode($paramAsli['static']['transaksi_id']))) && (!is_numeric($paramAsli['static']['transaksi_id']))) {
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
                $exception = isset($paramAsli['static']['exception']) ? $paramAsli['static']['exception'] : 0;

                if ($exception == 1) {
                    $paramAsli['static']['produk_id'] = $transaksiID;
                    $paramAsli['static']['nama'] = $paramAsli['static']['transaksi_no'];
                    $defaultTransID = 0;
                    $this->outParams[$lCounter]['produk_id'] = $paramAsli['static']['produk_id'];
                    $this->outParams[$lCounter]['nama'] = $paramAsli['static']['nama'];
                    $this->outParams[$lCounter]['transaksi_id'] = 0;
                }

                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $_preValue_locker = $this->cekLockerValidate(
                        $paramAsli['static']['jenis'],
                        $paramAsli['static']['jenis'],
                        $paramAsli['static']['cabang_id'],
                        $paramAsli['static']['produk_id'],
                        $defaultGudangID
                    );
                }

                $_preValue = $this->cekPreValue(
                    $paramAsli['static']['jenis'],
                    $paramAsli['static']['cabang_id'],
                    $paramAsli['static']['produk_id'],
                    $paramAsli['static']['state'],
                    $defaultOlehID,
                    $defaultTransID,
                    $defaultGudangID);

                if ($_preValue != null) {
                    $this->outParams[$lCounter]["nilai"] = ($paramAsli['static']['nilai'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "update";

                    if (round($this->outParams[$lCounter]["nilai"], 0) < 0) {
                        $msg = "Transaksi gagal, karena " . $paramAsli['static']['jenis'] . " " . $paramAsli['static']['nama'] . " tidak cukup. Saldo " . ($_preValue + 0);
                        mati_disini(($msg));
                    }
                }
                else {
                    $this->outParams[$lCounter]["nilai"] = ($paramAsli['static']['nilai'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "new";
                    if (round($this->outParams[$lCounter]["nilai"], 0) < 0) {
                        $msg = "Transaksi gagal, karena " . $paramAsli['static']['nama'] . " state " . $paramAsli['static']['state'] . ", tidak cukup. avail: " . $_preValue . ", needed: " . $this->outParams[$lCounter]["nilai"];
                        mati_disini(($msg));
                    }
                }


                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    if (sizeof($this->outParams) > 0) {
                        $insertIDs = array();
                        foreach ($this->outParams as $ctr => $params) {
                            $mode = isset($params["mode"]) ? $params["mode"] : "";

                            $this->load->model("Mdls/MdlLockerValueDetail");
                            $l = new MdlLockerValueDetail();
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
                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
                            cekMerah("::: PERIODE : $periode :::");
                            return false;
                        }

                    }
                    else {
//                    die("nothing to write down here");
                        return false;
                    }
                }

//
//            $_preValue_locker = $this->cekLockerValidate(
//                $paramAsli['static']['jenis'],
//                $paramAsli['static']['jenis'],
//                $paramAsli['static']['cabang_id'],
//                $paramAsli['static']['produk_id'],
//                $defaultGudangID
//            );

            }

        }


        if (sizeof($insertIDs) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function exec()
    {

        return true;
    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerValueDetail");
        $l = new MdlLockerValueDetail();


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

    public function fetchBalances($rek, $key = "", $sortBy = "", $sortMode = "ASC")
    {//==memanggil saldo2 dari rekening tertentu

        $this->load->model("Mdls/MdlLockerValueDetail");
        $l = new MdlLockerValueDetail();

        if (is_array($rek) && (sizeof($rek) > 0)) {
            $l->addFilter("jenis in ('" . implode("','", $rek) . "')");
        }
        else {

            $l->addFilter("jenis=$rek");
        }

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
//cekLime($this->db->last_query());
        $results = array();
        if (sizeof($result->result()) > 0) {
            foreach ($result->result() as $row) {
                $results[] = $row;
            }
        }
//arrPrint($results);
        // yang direturn hasil dari tabel, apa adanya...
        return $results;

    }

    private function cekLockerValidate($jenis, $jenis_item, $cabang_id, $produk_id, $gudang_id, $qty_kiriman = 0)
    {

        //region locker stok
        $this->load->model("Mdls/MdlLockerValueDetail");
        $l = new MdlLockerValueDetail();
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $lTmp = $l->lookupAll()->result();
//        arrPrintWebs($lTmp);
        showLast_query("biru");
        $locker_result = array(
            "active" => 0,
            "hold" => 0,
        );
        $locker_baris_active = array();
        if (sizeof($lTmp) > 0) {
            foreach ($lTmp as $lSpec) {
                if ($lSpec->state == "active") {
                    $locker_result["active"] += $lSpec->nilai;
                    $locker_baris_active[0] = $lSpec;
                }
                if ($lSpec->state == "hold") {
                    $locker_result["hold"] += $lSpec->nilai;
                }
            }
        }
        $total_locker["total"] = $locker_result["active"] + $locker_result["hold"];
        //endregion


        //region model rekening pembantu
        $rek_result = array(
            "debet" => 0
        );
        $mdlRek = NULL;
        switch ($jenis_item) {
            case "kas":
                $mdlRek = "ComRekeningPembantuKas";
                $mdlRekCoa = "1010010010";
                break;
        }
        if ($mdlRek != NULL) {
            $this->load->model("Coms/$mdlRek");
            $md = New $mdlRek();
            $md->addFilter("periode='forever'");
            $md->addFilter("cabang_id='$cabang_id'");
//            $md->addFilter("gudang_id='$gudang_id'");
            $md->addFilter("extern_id='$produk_id'");
            $mdTmp = $md->lookupAll()->result();
            showLast_query("kuning");
//            arrPrintKuning($mdTmp);
            $rek_result["debet"] = isset($mdTmp[0]->debet) ? $mdTmp[0]->debet : 0;


            arrPrint($locker_result);
            arrPrintKuning($total_locker);
            arrPrintWebs($rek_result);

            $selisih = $rek_result["debet"] - $total_locker["total"];
            if ($selisih != 0) {
                $locker_active_seharusnya = ($rek_result["debet"] - $locker_result["hold"]) + $qty_kiriman;
                $locker_active_seharusnya = ($locker_active_seharusnya < 0) ? 0 : $locker_active_seharusnya;
                if (sizeof($locker_baris_active) > 0) {
                    // update
                    cekOrange("update locker active, menjadi $locker_active_seharusnya");
//                arrPrintWebs($locker_baris_active);

                    $where = array(
                        "id" => $locker_baris_active[0]->id,
                    );
                    $data = array(
                        "nilai" => $locker_active_seharusnya
                    );
                    $l = new MdlLockerValueDetail();
                    $l->updateData($where, $data);
                    showLast_query("orange");
                }
                else {
                    // insert
                    cekHijau("insert locker active, menjadi $locker_active_seharusnya");
                    $data = array(
                        "jenis" => $jenis,
                        "jenis_locker" => "stock",
                        "cabang_id" => $cabang_id,
//                        "gudang_id" => $gudang_id,
                        "produk_id" => $produk_id,
//                    "nama",
                        "state" => "active",
                        "nilai" => $locker_active_seharusnya,
                    );
                    $l = new MdlLockerValueDetail();
                    $l->addData($data);
                    showLast_query("hijau");
                }
            }
            else {
                cekHijau("locker stok vs rekening pembantu sudah cocok");
            }

        }
        //endregion

//        mati_disini(__LINE__);
    }

}

