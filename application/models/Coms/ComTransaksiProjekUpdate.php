<?php


class ComTransaksiProjekUpdate extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;

    public function __construct()
    {
        parent::__construct();
    }

    private $outFields = array( // dari tabel rek_cache
        "id",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "jenis",
    );

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $_preValue = $this->cekPreValue($this->inParams['static']['cabang2_id'], $defaultTransID);
            showLast_query("biru");
            if ($_preValue != null) {
                $this->writeMode = "update";

                $this->outParams[$lCounter]["id"] = $_preValue;
                $this->outParams[$lCounter]["extern_nomer"] = $this->inParams['static']['nomer'];
                $this->outParams[$lCounter]["extern_transaksi_id"] = $this->inParams['static']['tr_id'];
                $this->outParams[$lCounter]["extern_oleh_id"] = $this->inParams['static']['oleh_id'];
                $this->outParams[$lCounter]["extern_oleh_nama"] = $this->inParams['static']['oleh_nama'];
                $this->outParams[$lCounter]["extern_dtime"] = $this->inParams['static']['dtime'];

            }
            else {
                $this->writeMode = "none";
            }

        }

        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    private function cekPreValue($cabang_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->addFilter("id='$transaksiID'");
//        $l->addFilter("cabang_id='$cabang_id'");
        $tmp = $l->lookupAll()->result();
        // cekMerah($this->db->last_query() . " # " . count($tmp));
        // matiHere(sizeof($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
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
            // arrPrint($this->outParams);
            // matiHere($this->writeMode);
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $insertIDs = array();
                switch ($this->writeMode) {

                    case "update":
                        $id = $params['id'];
                        unset($params['id']);
                        $insertIDs[] = $l->updateData(array(
                            "id" => $id,
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;

                }
                // cekBiru($this->db->last_query());
            }
// matiHere("88");
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