<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComAutoPurchaseSupplies extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outParams2 = array( //===output ke tabel

    );
    private $writeMode;
    private $saldoAkhir;

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
//            arrPrint($this->inParams);
            $this->load->model("MdlTransaksi");
            $l = new MdlTransaksi();
            // ini  tetap menggunakan lookupJoin versi lama karena tidak ada select id dari tbl transaksi
            // lookupJoined baru butuh indexing_detail
            $l->setFilters(array());
            $l->addFilter("transaksi.link_id=0");
            $l->addFilter("transaksi_data.valid_qty>0");
            $l->addFilter("transaksi_data.next_substep_code='" . $this->inParams['static']['jenis'] . "'");
            $l->addFilter("transaksi_data.produk_id in (" . implode(",", array_keys($this->inParams['items'])) . ")");
            $tmp = $l->lookupJoined_OLD()->result();
//            cekKuning("<b>function: " . __FUNCTION__ . "() || FILE: " . __FILE__ . " || LINE: " . __LINE__ . "</b><br>" . $this->db->last_query());
//arrPRint(sizeof($tmp));
//matiHere();
            if (sizeof($tmp) > 0) {
                $arrTransactionSourceTmp = array();
                foreach ($tmp as $kk => $arVL) {
                    $id = $arVL->id;
                    $id_master = $arVL->id_master;
                    $id_top = $arVL->id_top;
                    $arrTransactionSourceTmp[$id][$id_master] = $id_top;
                }
                $arrTransactionSource = array();
                foreach ($arrTransactionSourceTmp as $idA => $arrIdm) {
                    if (sizeof($arrIdm) > 0) {
                        foreach ($arrIdm as $id_master => $id_top1) {
                            $this->load->model("MdlTransaksi");
                            $l = new MdlTransaksi();
                            $l->setFilters(array());
                            $l->addFilterJoin("transaksi_data.valid_qty>0");
                            $l->addFilter("id_master='" . $id_master . "'");
                            $l->addFilter("next_group_code='sys'");
                            $l->addFilter("link_id=0");
                            $tmpTS = $l->lookupJoined();
                            if (sizeof($tmpTS) > 0) {
                                foreach ($tmpTS as $kk => $arVL) {
                                    $id = $arVL->id;
                                    $id_master = $arVL->id_master;
                                    $id_top2 = $arVL->id_top;
                                    $produk_id = $arVL->produk_id;
                                    $arrTransactionSource[$id_top1][$produk_id] = $id_top2;
                                    $this->outParams2[$id_top1][$produk_id] = $id_top2;
                                }
                            }
                        }
                    }
                }
            }

            $preTmp = array();
            foreach ($tmp as $ky => $datas) {
                $preTmp[$datas->transaksi_id][$datas->produk_id] = $datas->valid_qty;
            }
            $valTmp = array();
            $pushSA = array();
            if (sizeof($preTmp) > 0) {
                foreach ($preTmp as $trID => $orig) {
                    if (sizeof($this->inParams['items']) > 0) {
                        foreach ($this->inParams['items'] as $pid => $qty) {
                            if (isset($orig[$pid])) {
                                if (!isset($pushSA[$pid])) {
                                    $pushSA[$pid] = isset($this->inParams['items'][$pid]) && $this->inParams['items'][$pid] >= 0 ? $this->inParams['items'][$pid] : 0;
                                }
                                $saldo = isset($pushSA[$pid]) ? $pushSA[$pid] : (isset($pushSA[$pid]) ? $pushSA[$pid] : $this->inParams['items'][$pid]); //dari shopingCart atau dari pushSA
                                $needed = $orig[$pid];
                                $valid_qty = $saldo <= $needed ? ($needed - $saldo) : 0;

                                if (($saldo - $needed) >= 0) {
                                    cekHijau("trID:$trID | pid:$pid | saldo:$saldo | needed:$needed | valid_qty:" . $valid_qty . " | " . (($saldo - $needed) >= 0 ? "SISA: " . ($saldo - $needed) : "KURANG: " . ($needed - $saldo)));
                                }
                                else {
                                    cekOrange("trID:$trID | pid:$pid | saldo:$saldo | needed:$needed | valid_qty:" . $valid_qty . " | " . (($saldo - $needed) >= 0 ? "SISA: " . ($saldo - $needed) : "KURANG: " . ($needed - $saldo)));
                                }

                                $this->outParams[$trID][$pid]["valid_qty"] = ($valid_qty);
                                $this->outParams[$trID][$pid]["produk_qty"] = ($qty);
                                $this->outParams[$trID][$pid]["mode"] = "update";
                                $pushSA[$pid] = ($saldo - $needed) >= 0 ? ($saldo - $needed) : 0;
                            }
                        }
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

    private function cekPreValue($jenis, $trxID, $prdID, $saldo)
    {

    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {

            $t = new MdlTransaksi();
            $t->setTableName("transaksi");

            foreach ($this->outParams as $ctr => $arrData) {
                if (sizeof($arrData) > 0) {
                    $this->load->model("MdlTransaksi");
                    $l = new MdlTransaksi();
                    foreach ($arrData as $pid => $params) {
                        $l->setFilters(array());
                        $l->setTableName("transaksi_data");
                        $insertIDs = array();
                        $mode = $params['mode'];
                        $qty = $params['produk_qty'];
                        unset($params['mode']);
                        unset($params['produk_qty']);
                        switch ($mode) {
                            case "new":
                                $insertIDs[] = $l->addData($params);
                                break;
                            case "update":

                                $insertIDs[] = $l->updateData(array(
                                    "transaksi_id" => $ctr,
                                    "produk_id" => $pid,
                                ), $params);

                                $staticTmp = array();
                                $finalStaticTemp = array();

                                if (isset($this->outParams2[$ctr][$pid])) {

                                    $trID2 = $this->outParams2[$ctr][$pid];
                                    // ini  tetap menggunakan lookupJoin versi lama karena tidak ada select id dari tbl transaksi
                                    // lookupJoined baru butuh indexing_detail
                                    $l->setFilters(array());
                                    $l->addFilter("transaksi_data.transaksi_id='" . $trID2 . "'");
                                    $l->addFilter("transaksi_data.produk_id='" . $pid . "'");
                                    $currTmp = $l->lookupJoined_OLD()->result();

                                    $transaksi_no = $this->inParams['static']['transaksi_no'];
                                    if (!isset($staticTmp['static'][$transaksi_no])) {
                                        $staticTmp['static'][$transaksi_no] = $this->inParams['static'];
                                    }
                                    if (!isset($staticTmp['static']['produk_id'])) {
                                        $staticTmp['static'][$transaksi_no]['produk_id'] = $pid;
                                    }
                                    if (!isset($staticTmp['static']['produk_qty'])) {
                                        $staticTmp['static'][$transaksi_no]['produk_qty'] = $qty;
                                    }
                                    if (!isset($staticTmp['static']['valid_qty'])) {
                                        $staticTmp['static'][$transaksi_no]['valid_qty'] = $params['valid_qty'];
                                    }

                                    if (sizeof($currTmp) > 0) {
                                        foreach ($currTmp as $ape => $isinye) {
                                            $staticTmp2 = isset($isinye->ext_blob) ? blobDecode($isinye->ext_blob) : array();

                                            if ($staticTmp2) {
                                                if (!isset($finalStaticTemp['static'])) {
                                                    $finalStaticTemp['static'] = array();
                                                }
                                                $finalStaticTemp['static'] = array_merge($staticTmp2['static'], $staticTmp['static']);
                                            }
                                            else {
                                                $finalStaticTemp = $staticTmp;
                                            }
                                        }
                                    }

                                    // cekUngu('$finalStaticTemp');
                                    // arrPrint($finalStaticTemp);

                                    $updateIDs = array();
                                    $params2 = array(
                                        "ext_intext" => print_r($finalStaticTemp, true),
                                        "ext_blob" => blobEncode($finalStaticTemp),
                                    );
                                    $updateIDs[] = $l->updateData(array(
                                        "transaksi_id" => $trID2,
                                        "produk_id" => $pid,
                                    ), $params2);

                                    cekOrange($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                                }


                                break;
                            default:
                                die("unknown writemode!");
                                break;
                        }
                    }

                    cekHijau("============ |||| ===========");
                    // ini  tetap menggunakan lookupJoin versi lama karena tidak ada select id dari tbl transaksi
                    // lookupJoined baru butuh indexing_detail
                    $l->setFilters(array());
                    $l->addFilter("transaksi_id=$ctr");
                    $tmpTr = $l->lookupJoined_OLD()->result();
                    $itemCheck = array();
                    if (sizeof($tmpTr) > 0) {
                        foreach ($tmpTr as $trx => $rows) {
                            $itemCheck[$rows->transaksi_id][$rows->produk_id] = $rows->valid_qty;
                        }
                        $sumArr = array();
                        $detArr = array();
                        foreach ($itemCheck as $trID => $dPrd) {
                            foreach ($dPrd as $pids => $jml) {
                                if (!isset($sumArr[$trID])) {
                                    $sumArr[$trID] = 0;
                                }
                                $sumArr[$trID] += $jml;
                                $detArr[$trID][$pids] = $jml;
                            }
                        }
                    }

                    foreach ($sumArr as $trID => $jml) {
                        $l->setFilters(array());
                        $l->setTableName("transaksi_data");
                        $updateIDs = array();
                        if ($jml == 0) {
                            $params = array(
                                "next_substep_code" => "",
                                "sub_step_number" => 0
                            );
                            $updateIDs[] = $l->updateData(array(
                                "transaksi_id" => $trID
                            ), $params);
                            cekOrange($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                        }
                        else {
                            cekHere("<b>$trID</b> belum diupdate sisa: $jml");
                            arrPrint($detArr[$trID]);
                        }
                    }

                    $staticTmpt = array();


                    if (isset($this->outParams2[$ctr])) {

                        $transaksi_no = $this->inParams['static']['transaksi_no'];
                        $staticTmpt = array();
                        foreach ($arrData as $pid => $params) {

                            foreach ($this->outParams2[$ctr] as $pids) {
                                if (!isset($staticTmp['static'][$transaksi_no])) {
                                    $staticTmpt['static'][$transaksi_no][$pid] = $this->inParams['static'];
                                }
                                if (!isset($staticTmp['static']['produk_qty'])) {
                                    $staticTmpt['static'][$transaksi_no][$pid]['produk_qty'] = $params['produk_qty'];
                                }
                                if (!isset($staticTmp['static']['valid_qty'])) {
                                    $staticTmpt['static'][$transaksi_no][$pid]['valid_qty'] = $params['valid_qty'];
                                }
                            }
                        }

//                        cekOrange('$transaksi_no: ' . $ctr);
//                        arrPrint( $transaksi_no );

                        cekOrange('$transaksi_no: ' . $transaksi_no);

                        cekOrange('$staticTmpt: ' . $ctr);
                        // arrPrint($staticTmpt);

//                        cekOrange('$this->outParams2[$ctr]: ' . $ctr);
//                        arrPrint( $this->outParams2[$ctr] );

//                        cekOrange('$arrData: ' . $ctr);
//                        arrPrint( $arrData );

//                        $updateIDs = array();
//                        $params2 = array(
//                            "ext_intext" => serialize($staticTmpt),
//                            "ext_blob" => blobEncode($staticTmpt),
//                        );
//                        $trID2 = $this->outParams2[$ctr][$pid];
//                        $updateIDs[] = $l->updateData(array(
//                            "transaksi_id" => $trID2,
//                        ), $params2);
//                        cekOrange( $this->db->last_query() . " [" . $this->db->affected_rows() . "]" );

                    }

//                    arrPrint($sumArr);
//                    arrPrint($detArr);
//                    arrPrint($itemCheck);
//                    cekOrange($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                }
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