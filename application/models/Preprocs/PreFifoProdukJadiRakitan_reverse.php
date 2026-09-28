<?php


class PreFifoProdukJadiRakitan_reverse extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $outFields = array( // dari tabel rek_cache
        "produk_id",
        "produk_nama",
        "unit",
        "hpp",
        "jml_nilai",
        "cabang_id",
        "transaksi_id",
        "transaksi_jenis",
        "dtime",
        "fulldate",
        "gudang_id",
    );
    private $writeMode;

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
        cekKuning("STARTING... [$master_id] " . get_class($this));

        if (!is_array($inParams)) {
            die("params required!");
        }
//arrprint($inParams);
//mati_disini();
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            $jmlItems = array();
            $itemsNama = array();
            $itemsID = array();
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli as $spec) {
                    if (!isset($jmlItems[$spec['extern_id']])) {
                        $jmlItems[$spec['extern_id']] = 0;
                    }
                    $jmlItems[$spec['extern_id']] += $spec['produk_qty'];
                    $transID = $spec['transaksi_id_ref'];
                    $cabangID = $spec['cabang_id'];
                    $itemsNama[$spec['extern_id']] = $spec['extern_nama'];
                    $itemsID[$spec['extern_id']] = $spec['extern_id'];
                }
            }

            $this->writeMode = "update";
            $this->load->model("Mdls/MdlFifoProdukJadiRakitan");
            $l = new MdlFifoProdukJadiRakitan();
            $l->addFilter("transaksi_id='$transID'");
            $l->addFilter("unit>0");
            $l->addFilter("produk_id in ('" . implode("','", $itemsID) . "')");
            $l->addFilter("cabang_id='$cabangID'");
            $lTmp = $l->lookupAll()->result();
            showLast_query("orange");
//arrPrintPink($lTmp);
            $fifoID_tbl = array();
            $jmlItemsFifo = array();
            if (sizeof($lTmp) > 0) {
                foreach ($lTmp as $lSpec) {

                    $fifoID_tbl[] = array(
                        "id" => $lSpec->id,
                        "unit" => $lSpec->unit,
                        "jml_nilai" => $lSpec->jml_nilai,
                        "jml_nilai_riil" => $lSpec->jml_nilai_riil,
                        "ppv_nilai_riil" => $lSpec->ppv_nilai_riil,
                        "ppn_in_nilai" => $lSpec->ppn_in_nilai,
                        "produk_id" => $lSpec->produk_id,
                    );
                    if (!isset($jmlItemsFifo[$lSpec->produk_id])) {
                        $jmlItemsFifo[$lSpec->produk_id] = 0;
                    }
                    $jmlItemsFifo[$lSpec->produk_id] += $lSpec->unit;
                }
            }

            if (sizeof($jmlItemsFifo) > 0) {
                foreach ($jmlItemsFifo as $pID => $jmlFifo) {
                    $jml = isset($jmlItems[$pID]) ? $jmlItems[$pID] : 0;
                    $nama = isset($itemsNama[$pID]) ? htmlspecialchars($itemsNama[$pID]) : "";
                    cekMerah(":: $jml :: $jmlFifo ::");
                    if ($jml > $jmlFifo) {
                        $msg = "Jumlah stok $nama tidak cukup. Pembatalan transaksi tidak bisa dilanjutkan.";
                        cekMerah($msg);
                        die(lgShowAlertBiru($msg));
                    }

                }
            }

//arrPrintPink($itemsID);
//arrPrintPink($fifoID_tbl);
//mati_disini();
            foreach ($fifoID_tbl as $ii => $spec) {
                cekHitam("produkID:: " . $spec['produk_id']);
                if ($spec['produk_id'] == $itemsID[$spec['produk_id']]) {
                    if ($spec['unit'] == $jmlItems[$spec['produk_id']]) {
                        $this->outParams[$ii] = array(
                            "id" => $spec['id'],
                            "unit" => 0,
                            "jml_ot" => $spec['unit'],
                            "jml_nilai" => 0,
                            "jml_nilai_ot" => $spec['jml_nilai'],
                            "jml_nilai_riil" => 0,
                            "ppv_nilai_riil" => 0,
                            "ppn_in_nilai" => 0,
                        );
                    }
                    else {
                        cekKuning("unit fifo: " . $spec['unit'] . ", jml request: " . $jmlItems[$spec['produk_id']]);
                    }
                }
            }


        }


//mati_disini("==== FIFO REVERSE ====");
        if (sizeof($this->outParams) > 0) {

            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlFifoProdukJadiRakitan");
                $l = new MdlFifoProdukJadiRakitan();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
                        $id = $params['id'];
                        unset($params['id']);
                        $where = array(
                            "id" => $id,
                        );
                        $insertIDs[] = $l->updateData($where, $params);
                        showLast_query("pink");
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }

        }
        else {
//            die("nothing to write down here");
//            return false;
            return true;
        }

//        mati_disini(get_class($this));

//        if (sizeof($insertIDs) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }

        return true;
    }

    public function exec()
    {
        return $this->result;
    }

    //--------------------------------------
    public function lookupFifoById($transaksi_id)
    {
        $this->load->model("Mdls/MdlFifoProdukJadiRakitan");
        $l = new MdlFifoProdukJadiRakitan();
        $l->addFilter("transaksi_id='$transaksi_id'");
        $lTmp = $l->lookupAll()->result();
        $jmlItemsFifo = array();
        if (sizeof($lTmp) > 0) {
            foreach ($lTmp as $lSpec) {
                if (!isset($jmlItemsFifo[$lSpec->produk_id])) {
                    $jmlItemsFifo[$lSpec->produk_id] = 0;
                }
                $jmlItemsFifo[$lSpec->produk_id] += $lSpec->unit;
            }
        }

        return $jmlItemsFifo;
    }
}