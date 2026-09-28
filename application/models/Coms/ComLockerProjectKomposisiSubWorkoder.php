<?php
class ComLockerProjectKomposisiSubWorkoder extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

//        "jenis",
//        "produk_id",
//        "cabang_id",
//        "nama",
//        "satuan",
//        "state",
//        "jumlah",
//        "oleh_id",
//        "oleh_nama",
//        "transaksi_id",
//        "nomer",
//        "gudang_id",
//        "status",
//        "trash",
//        "project_id",
//        "work_order_id",
//        "produk_dasar_id",
//        "src_key",
        "qty_debet",
        "qty_kredit",
        "produk_dasar_id",
        "produk_id",
        "fase_id",
        "sub_fase_id",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
//        arrPrint($inParams);
//        matiHere();
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
//                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                arrprint($paramAsli['static']);
                // locker membutuhkan cabangID dan gudangID, bila tidak ada kiriman gerbang nilai maka dihentikan saja.
                $msg_1 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
//                $msg_2 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
//                $msg_3 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
                $msg_4 = "Transaksi gagal disimpan karena id gudang login tidak terdaftar. silahkan relogin atau hubungi admin.";
                $msg_5 = "Transaksi gagal disimpan karena id produk tidak terdaftar. silahkan relogin atau hubungi admin.";
                $msg_6 = "Transaksi gagal disimpan karena workorder tidak terdaftar. silahkan relogin atau hubungi admin.";

                $cabangID = isset($paramAsli['static']['cabang_id']) ? $paramAsli['static']['cabang_id'] : mati_disini($msg_1);
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = isset($paramAsli['static']['gudang_id']) ? $paramAsli['static']['gudang_id'] : mati_disini($msg_4);
                $produkID = isset($paramAsli['static']['produk_id']) ? $paramAsli['static']['produk_id'] : mati_disini($msg_5);
                $produkID = isset($paramAsli['static']['fase_id']) ? $paramAsli['static']['fase_id'] : mati_disini($msg_6);


//                matiHEre(__LINE__);

                $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['produk_dasar_id'], "active", $defaultOlehID, $defaultTransID, $defaultGudangID,$paramAsli['static']['project_id'],$paramAsli['static']['fase_id'],$paramAsli['static']['sub_fase_id']);
                ceklime($this->db->last_query());

                if ($_preValue != null) {

                    $this->outParams[$lCounter]["qty_saldo"] = ($_preValue-$paramAsli['static']['qty_kredit']);
                    $this->outParams[$lCounter]["mode"] = "update";

                    if ($this->outParams[$lCounter]["qty_saldo"] < 0) {

                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['jumlah'] . ", avail: " . $_preValue;
                        mati_disini($msg);
                        die(lgShowAlert($msg));
                    }
                }
                else {
                    matiHEre("gagal mendapatakan data komposisi workorder");
                }
//arrprint($this->outParams);
//                matiHere();
                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    if (sizeof($this->outParams) > 0) {
                        $insertIDs = array();
                        foreach ($this->outParams as $ctr => $params) {
                            $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
                            $l = new MdlProjectKomposisiWorkorderSub();
                            $l->setFilters(array());
                            $insertIDs = array();
                            $mode = $params['mode'];
                            unset($params['mode']);
                            switch ($mode) {
                                case "new":
                                    matiHEre(__LINE__);
                                    $insertIDs[] = $l->addData($params);
                                    break;
                                case "update":

                                    $insertIDs[] = $l->updateData(array(
//                                        "cabang_id" => $params['cabang_id'],
//                                        "gudang_id" => $params['gudang_id'],
                                        "produk_id" => $params['produk_id'],
                                        "fase_id" => $params['fase_id'],
                                        "sub_fase_id" => $params['sub_fase_id'],
                                        "produk_dasar_id" => $params['produk_dasar_id'],
//                                        "state" => $params['state'],
//                                        "oleh_id" => $params['oleh_id'],
//                                        "transaksi_id" => $params['transaksi_id'],
                                    ), $params);
                                    showLast_query("kuning");
                                    break;
                                default:
                                    die("unknown writemode!");
                                    break;
                            }

                        }
                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
                            cekMerah("::: PERIODE : $periode :::");
                            return false;
                        }
                    }
                    else {
                        cekMerah("::: PERIODE : $periode :::");
                        return false;
                    }
                }

                $pakai_cek = 0;
                if($pakai_cek == 1){
                    $_preValue_locker = $this->cekLockerValidate(
                        $paramAsli['static']['jenis'],
                        $paramAsli['static']['jenis'],
                        $paramAsli['static']['cabang_id'],
                        $paramAsli['static']['produk_id'],
                        $defaultGudangID,
                        0
                    );
                }
            }
        }
//        matiHere();
        if (sizeof($insertIDs) > 0) {

            return true;
        }
        else {
            return false;
        }


//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id,$project_id,$workorder_id,$subworkorder)
    {
        $param_src = "$cabang_id"."$gudang_id"."$project_id"."$workorder_id"."$produk_id"."$state";
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
        $l = new MdlProjectKomposisiWorkorderSub();

        $l->addFilter("produk_id='$project_id'");
        $l->addFilter("fase_id='$workorder_id'");
        $l->addFilter("sub_fase_id='$subworkorder'");
        $l->addFilter("produk_dasar_id='$produk_id'");

//        $l->addFilter("src_key='$param_src'");
//        $l->addFilter("cabang_id='$cabang_id'");
//        $l->addFilter("gudang_id='$gudang_id'");
//        $l->addFilter("produk_id='$produk_id'");
//        $l->addFilter("state='$state'");
//        $l->addFilter("oleh_id='$olehID'");
//        $l->addFilter("transaksi_id='$transaksiID'");

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
//        arrPrint($tmp);
//        cekHitam($this->db->last_query());
//matiHere("ini ");
//        $nilai = $tmp['qty_debet'];
        if (sizeof($tmp) > 0) {
//            foreach ($tmp as $row) {
            $result = $tmp['qty_saldo'];
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
//        if (sizeof($this->outParams) > 0) {
//            foreach ($this->outParams as $ctr => $params) {
//                $this->load->model("Mdls/MdlLockerStock");
//                $l = new MdlLockerStock();
//                $insertIDs = array();
//                $mode = $params['mode'];
//                unset($params['mode']);
//                switch ($mode) {
//                    case "new":
//                        $insertIDs[] = $l->addData($params);
//                        break;
//                    case "update":
//                        $insertIDs[] = $l->updateData(array(
//                            "cabang_id" => $params['cabang_id'],
//                            "gudang_id" => $params['gudang_id'],
//                            "produk_id" => $params['produk_id'],
//                            "state" => $params['state'],
//                            "oleh_id" => $params['oleh_id'],
//                            "transaksi_id" => $params['transaksi_id'],
//                        ), $params);
//                        break;
//                    default:
//                        die("unknown writemode!");
//                        break;
//                }
//            }
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
//        }
//        else {
//            die("nothing to write down here");
//            return false;
//        }

        return true;
    }

    //-------
    private function cekLockerValidate($jenis, $jenis_item, $cabang_id, $produk_id, $gudang_id, $qty_kiriman = 0)
    {

        //region locker stok
        $this->load->model("Mdls/MdlLockerStock");
        $l = new MdlLockerStock();
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $lTmp = $l->lookupAll()->result();
        showLast_query("biru");
        $locker_result = array(
            "active" => 0,
            "hold" => 0,
        );
        $locker_baris_active = array();
        if (sizeof($lTmp) > 0) {
            foreach ($lTmp as $lSpec) {
                if ($lSpec->state == "active") {
                    $locker_result["active"] += $lSpec->jumlah;
                    $locker_baris_active[0] = $lSpec;
                }
                if ($lSpec->state == "hold") {
                    $locker_result["hold"] += $lSpec->jumlah;
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
            case "produk":
                $mdlRek = "ComRekeningPembantuProduk";
                $mdlRekCoa = "1010030030";
                break;
            case "produk rakitan":
                $mdlRek = "ComRekeningPembantuProduk";
                $mdlRekCoa = "1010030070";
                break;
            case "supplies":
                $mdlRek = "ComRekeningPembantuSupplies";
                $mdlRekCoa = "1010030010";
                break;
        }
        if ($mdlRek != NULL) {
            $this->load->model("Coms/$mdlRek");
            $md = New $mdlRek();
            $md->addFilter("periode='forever'");
            $md->addFilter("cabang_id='$cabang_id'");
            $md->addFilter("gudang_id='$gudang_id'");
            $md->addFilter("extern_id='$produk_id'");
            $mdTmp = $md->lookupAll()->result();
//            showLast_query("kuning");
//            arrPrintKuning($mdTmp);
            $rek_result["debet"] = isset($mdTmp[0]->qty_debet) ? $mdTmp[0]->qty_debet : 0;
        }
        //endregion


//        arrPrint($locker_result);
//        arrPrintKuning($total_locker);
//        arrPrintWebs($rek_result);


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
                    "jumlah" => $locker_active_seharusnya
                );
                $l = new MdlLockerStock();
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
                    "gudang_id" => $gudang_id,
                    "produk_id" => $produk_id,
//                    "nama",
                    "state" => "active",
                    "jumlah" => $locker_active_seharusnya,
                );
                $l = new MdlLockerStock();
                $l->addData($data);
                showLast_query("hijau");
            }
        }
        else {
            cekHijau("locker stok vs rekening pembantu sudah cocok");
        }


//        mati_disini(__LINE__);
    }

}