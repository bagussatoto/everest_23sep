<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComSyncPpnBendahara extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "tagihan",
        "terbayar",
        "returned",
        "sisa",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "extern2_id",
        "extern2_nama",
        "extern_date2",
        "transaksi_id",
        "jenis",
        "label",
        "tagihan_valas",
        "terbayar_valas",
        "returned_valas",
        "sisa_valas",
        "ppn",
        "ppn_approved",
        "ppn_sisa",
        "ppn_status",
        "ppn_pph_faktor",
        "extern_nilai2",
        "target_jenis",
        "reference_jenis",
        "dihapus",

    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
// arrPrintWebs($inParams);
// matiHere();
        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['extern2_id']) ? $this->inParams['static']['extern2_id'] : 0;
            $_preValue = $this->cekPreValue($this->inParams['static']['jenis'],$this->inParams['static']['target_jenis'], $this->inParams['static']['cabang_id'], $defaultTransID);
            if ($_preValue != null) {
                $this->writeMode = "update";
                $this->outParams[$lCounter]["id"] = $_preValue["id"];
                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
            }
            else {

                $this->writeMode = "none";
            }
        }


        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return true;
        }
    }


    private function cekPreValue($jenis, $target,$cabang_id, $extern2_id)
    {

        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern2_id='$extern2_id'");
        $l->addFilter("target_jenis='$target'");

//        $tmp = $l->lookupAll()->result();
//        cekMerah($this->db->last_query() . " # " . count($tmp));
        $result = array();
        $localFilters = array();
        if (sizeof($l->getFilters()) > 0) {
            foreach ($l->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }

        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmp = $this->db->query("{$query} FOR UPDATE")->result();



        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                );
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
        // arrPrint($this->outParams);
        // matiHEre();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlPaymentSource");
                $l = new MdlPaymentSource();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        // $insertIDs[] = $l->addData($params);//dimatiin sengaja cuma mau update
                        break;
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "id" => $params['id'],
                            // "cabang_id" => $params['cabang_id'],
                            // "extern_id" => $params['extern_id'],
                            // "label" => $params['label'],
                            // "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        break;
                    default:
                        // die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }
// matiHere(__LINE__);
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        else {
            // die("nothing to write down here");
            return true;
        }

    }
}