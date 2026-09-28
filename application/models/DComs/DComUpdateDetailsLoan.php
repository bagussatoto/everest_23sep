<?php


class DComUpdateDetailsLoan extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "tagihan",
        "terbayar",
        "sisa",
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );
    private $koloms = array(
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "tagihan",
        "terbayar",
        "sisa",
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );

    public function __construct()
    {

    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
                $mdlName = $inSpec["MdlName"];
                $this->load->model("Mdls/" . $mdlName);
                $d = new $mdlName();
                $selectField = $d->getOutFields();
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $prev = $this->cekPreValue($inSpec['static']['transaksi_id_request_bunga'], $mdlName);
                    if (isset($prev['id'])) {
                        $writeMode = "update";
                        $data = array(
                            "nomer_approve_bunga" => $inSpec['static']['tmpNomorNota2'],
                            "dtime_approve_bunga" => date('Y-m-d H:i:s'),
                            "pihakid_approve_bunga" => $inSpec['static']['pihakid'],
                            "pihak_approve_bunga" => $inSpec['static']['nama_login'],
                        );
                        $where = array(
                            "transaksi_id_request_bunga" => $inSpec['static']['transaksi_id_request_bunga'],
                        );
                    }
                    else {
                        $writeMode = "new";
                        foreach ($selectField as $ix => $fields) {
                            if (isset($inSpec['static'][$fields])) {
                                $data[$fields] = $inSpec['static'][$fields];
                            }
                        }
                    }

                    switch ($writeMode) {
                        case "new" :
                            $data['extern_jenis'] = "main";
                            $insertIDs[] = $d->addData($data);
                            cekLime($this->db->last_query());

                            Break;
                        case "update":
                            $insID = $d->updateData($where, $data) or die("can not update paymentSrc");
                            $insertIDs[] = $insID;
                            cekLime($this->db->last_query());
                            break;
                        default :
                            matiHere("method undefined yet!!" . __LINE__ . "func" . __FUNCTION__);
                            break;
                    }
                }
            }
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
    }


    private function cekPreValue($id, $mdlName)
    {
        $this->load->model($mdlName);
        $tr = new $mdlName();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id_request_bunga='$id'");
        $tmpR = $tr->lookUpAll()->result();
        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,
                );
            }
        }
        else {
            $result = null;
        }

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        return true;

    }


}