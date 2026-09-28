<?php


class PreProduksiPreBiaya extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
        $this->jenisTrException = array("2762");
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

        if (!is_array($inParams)) {
            die("params required!");
        }
//        arrPrint($inParams);
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $this->load->model("Mdls/MdlCabang");


        $n = New MdlProdukRakitanPreBiaya();
        $tmp = $n->lookupAll()->result();
        cekBiru($this->db->last_query());
        $no = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $nSpec) {
                $no++;
                // $preBiayaDef[$no] = $nSpec->nama;
                $preBiayaDef[$no] = $nSpec->coa_code;
            }
        }


        $patchers = array();
        if (sizeof($inParams) > 0) {
            arrPrint($inParams);
//            mati_disini(__LINE__);
            foreach ($inParams as $ctr => $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $cCode = "_TR_" . $pSpec['jenisTr'];
                    $rowFifo = $pSpec['row_fifo'];
                    $patchers = array();

                    $c = New MdlCabang();
                    $c->addFilter("id='" . $pSpec['cabang2_id'] . "'");
                    $tmp = $c->lookupAll()->result();
//                    cekHere(":: " . $this->db->last_query());
                    arrPrintKuning($pSpec);
                    $production = false;
                    if (sizeof($tmp) > 0) {
                        $production = (isset($tmp[0]->tipe) && $tmp[0]->tipe == "produksi") ? true : false;
                    }
//                    cekBiru("production is " . $production);

                    $ctr = $pSpec['rowPreFifo'];
                    foreach ($preBiayaDef as $k => $v) {
                        if ($production == true) {
                            if (sizeof($_SESSION[$cCode]['pairs']['preBiaya']) > 0) {
//                                cekPink("perulangan preBiaya");
                                if (isset($_SESSION[$cCode]['pairs']['preBiaya'][$pSpec['produk_id']])) {
                                    $urut = $_SESSION[$cCode]['pairs']['preBiaya'][$pSpec['produk_id']]['urut'];

                                    if ($urut == $k) {
                                        foreach ($this->resultParams as $gateName => $paramSpec) {
                                            foreach ($paramSpec as $key => $val) {
                                                if (in_array($pSpec['jenisTr'], $this->jenisTrException)) {
                                                    $patchers[$gateName][$ctr][$key . "_" . $urut] = isset($pSpec[$val]) ? $pSpec[$val] : 0;
                                                }
                                                else {
                                                    $patchers[$gateName][$pSpec["produk_id"]][$key . "_" . $urut] = isset($pSpec[$val]) ? $pSpec[$val] : 0;
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        foreach ($this->resultParams as $gateName => $paramSpec) {
                                            foreach ($paramSpec as $key => $val) {
                                                if (in_array($pSpec['jenisTr'], $this->jenisTrException)) {
                                                    $patchers[$gateName][$ctr][$key . "_" . $k] = 0;
                                                }
                                                else {
                                                    $patchers[$gateName][$pSpec["produk_id"]][$key . "_" . $k] = 0;
                                                }
                                            }
                                        }
                                    }
                                    if (in_array($pSpec['jenisTr'], $this->jenisTrException)) {
//                                        mati_disini(":: $val ::");
                                        $patchers[$gateName][$ctr]["subtotal_rev"] = isset($pSpec[$val]) ? $pSpec[$val] : 0;
//                                        $patchers[$gateName][$ctr]["qty"] = $pSpec["jml"];
//                                        $patchers[$gateName][$ctr]["jml"] = $pSpec["jml"];
//                                        $patchers[$gateName][$ctr]["qty"] = 1;
//                                        $patchers[$gateName][$ctr]["jml"] = 1;
//                                        cekUngu(__LINE__);
                                    }
                                    else {
                                        $patchers[$gateName][$pSpec["produk_id"]]["subtotal_rev"] = isset($pSpec[$val]) ? $pSpec[$val] : 0;
//                                        $patchers[$gateName][$pSpec["produk_id"]]["qty"] = 1;
//                                        $patchers[$gateName][$pSpec["produk_id"]]["jml"] = 1;
//                                        cekUngu(__LINE__);
                                    }
                                }
                                else {
//                                    cekHitam(":: TIDAK ada paiirrrrr " . $pSpec['produk_id']);
                                    $msg = "Biaya " . $pSpec['nama'] . " belum direlasikan dengan Product Cost.<br><span style='font-size:15px;color:blue;'>Silahkan direlasikan di menu 601 Product Cost Defines.</span>";
                                    die(lgShowAlert($msg));
                                }
                            }
                            else {
                                cekHitam(":: TIDAK ada paiirrrrr........... ::");
                            }
                        }
                        else {
                            foreach ($this->resultParams as $gateName => $paramSpec) {
                                foreach ($paramSpec as $key => $val) {
                                    if (in_array($pSpec['jenisTr'], $this->jenisTrException)) {
                                        $patchers[$gateName][$ctr][$key . "_" . $k] = 0;
                                        $patchers[$gateName][$ctr]["subtotal_rev"] = 0;
                                    }
                                    else {
                                        $patchers[$gateName][$pSpec["produk_id"]][$key . "_" . $k] = 0;
                                        $patchers[$gateName][$pSpec["produk_id"]]["subtotal_rev"] = 0;
                                    }


                                }
                            }
                        }
                    }

                }
            }
        }
        cekHitam("cetak patcher....");
        arrPrint($patchers);
//        mati_disini();
        $this->result = $patchers;
        return $this->result;
    }

    public function exec()
    {
        return $this->result;
    }


}