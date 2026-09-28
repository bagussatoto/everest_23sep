<?php


class ComPaymentSourceItems extends MdlMother
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
//        arrPrintWebs($inParams);
        $lCounter = 0;
        foreach ($inParams as $this->inParams) {
            $lCounter++;
            if (sizeof($this->inParams['static']) > 0) {


                $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
                $_preValue = $this->cekPreValue($this->inParams['static']['jenis'], $this->inParams['static']['cabang_id'], $this->inParams['static']['extern_id'], $this->inParams['static']['label'], $defaultTransID);

                cekMerah($this->db->last_query());
                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

                // arrPrint($this->inParams['static']);
                arrPrint($_preValue);
                // mati_disini("ggg");
                if ($_preValue != null) {
                    $this->writeMode = "update";
                    if (isset($this->inParams['static']['terbayar'])) {
                        $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['terbayar']);
                        $this->outParams[$lCounter]["terbayar"] = ($_preValue["terbayar"] + $this->inParams['static']['terbayar']);

                        $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] - $this->inParams['static']['terbayar_valas']);
                        $this->outParams[$lCounter]["terbayar_valas"] = ($_preValue["terbayar_valas"] + $this->inParams['static']['terbayar_valas']);

                    }
                    elseif (isset($this->inParams['static']['returned'])) {
                        $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['returned']);
                        $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $this->inParams['static']['returned'];

                        $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] - $this->inParams['static']['returned_valas']);
                        $this->outParams[$lCounter]["returned_valas"] = $_preValue['returned_valas'] + $this->inParams['static']['returned_valas'];
                    }
                    elseif (isset($this->inParams['static']['ppn_approved'])) {
                        $this->outParams[$lCounter]["ppn_sisa"] = ($_preValue["ppn_sisa"] - $this->inParams['static']['ppn_approved']);
                        $this->outParams[$lCounter]["ppn_approved"] = $_preValue['ppn_approved'] + $this->inParams['static']['ppn_approved'];

//                    $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] - $this->inParams['static']['returned_valas']);
//                    $this->outParams[$lCounter]["returned_valas"] = $_preValue['returned_valas'] + $this->inParams['static']['returned_valas'];

//                    if (round($this->outParams[$lCounter]["ppn_sisa"]) == 0) {
                        $this->outParams[$lCounter]["ppn_status"] = 0;
//                    }
                    }
                }
                else {
                    //tidak diijinkan nulis payment source dari model. harus dari controler Transaksi letakkan di heTransaksi_misc
                    matiHere("Gagal menyimpan transaksi, silahkan hubungi tim developer untuk melakukan pengecekan sistem. Error Code:  Com " . __LINE__);
                    $this->writeMode = "new";
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


    private function cekPreValue($jenis, $cabang_id, $extern_id, $label, $transaksiID = 0)
    {

        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern_id='$extern_id'");
        $l->addFilter("label='$label'");
        $l->addFilter("transaksi_id='$transaksiID'");
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
                    "sisa" => $row->sisa,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                    "diskon" => $row->diskon,
                    "terbayar_valas" => $row->terbayar_valas,
                    "returned_valas" => $row->returned_valas,
                    "sisa_valas" => $row->sisa_valas,
                    "diskon_valas" => $row->diskon_valas,
                    "ppn_approved" => $row->ppn_approved,
                    "ppn_sisa" => $row->ppn_sisa,
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
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlPaymentSource");
                $l = new MdlPaymentSource();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "jenis" => $params['jenis'],
                            "cabang_id" => $params['cabang_id'],
                            "extern_id" => $params['extern_id'],
                            "label" => $params['label'],
                            "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
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