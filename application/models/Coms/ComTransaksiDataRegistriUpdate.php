<?php

class ComTransaksiDataRegistriUpdate extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "transaksi_id",
        "items3",
        "items4",
    );

    public function __construct()
    {
        parent::__construct();
        $this->jenisTrUpdate = array(
            "9911", "9912"
        );
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        cekHitam("cetak inParams ");
        arrPrint($this->inParams);


        $lCounter = 0;
        $this->writeMode = "update";
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                if(in_array($array_params['jenisTr'], $this->jenisTrUpdate)){
                    $this->outParams = array();
                }
                else{

                    $lCounter++;
                    foreach ($array_params as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }

                    $transaksi_id = $array_params['transaksi_id'];

                    $prev = $this->cekPreValue($transaksi_id);
                    $items3 = $prev['items3'] != NULL ? blobDecode($prev['items3']) : array();
                    $items4 = $prev['items4'] != NULL ? blobDecode($prev['items4']) : array();

                    $cCode = "_TR_" . $array_params['jenisTrMaster'];
                    $insertID = $array_params['insertID'];
                    $items3[$insertID] = isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array();
                    $items4[$insertID] = isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array();
//                    arrPrintPink($items3);
//                    arrPrintWebs($items4);
                    $this->outParams[$lCounter]['items3'] = blobEncode($items3);
                    $this->outParams[$lCounter]['items4'] = blobEncode($items4);

                }
            }
        }


        return true;
//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
    }

    private function cekPreValue($transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setTableName($l->getTableNames()['dataRegistry']);
        $l->setFilters(array());
        $l->setSortBy(array("kolom" => "transaksi_id", "mode" => "ASC"));
        $l->addFilter("transaksi_id='$transaksiID'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            $result = array(
                "id" => $tmp[0]->transaksi_id,
                "items3" => $tmp[0]->items3,
                "items4" => $tmp[0]->items4,
            );
        }
        else {
            $result = array();
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
                        arrPrintPink($params);
                        $trID = $params['transaksi_id'];
                        unset($params['transaksi_id']);
                        $where = array(
                            "transaksi_id" => $trID,
                        );
                        $l->setTableName($l->getTableNames()['dataRegistry']);

                        $insertIDs[] = $l->updateData($where, $params);
                        cekBiru($this->db->last_query());
                        break;
                    default:
                        die("unknown writemode!");
                        break;
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
            cekHitam(":: tidak ada Update di " . __CLASS__);
            return true;
//
//
//            die("nothing to write down here");
//            return false;
        }
    }


}