<?php

// menulis data project_produk akan digeser ke pre-procc.....


class ComProdukProjectUpdate extends MdlMother
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
        "nama",
        "kode",
        "transaksi_id",
        "transaksi_no",
        "oleh_id",
        "oleh_nama",
        "customer_id",
        "customer_nama",
        "closing_status",
        "closing_oleh_id",
        "closing_oleh_nama",
        "closing_dtime",
        "closing_transaksi_id",
        "closing_transksi_no",
        "cabang_id",
        "cabang_nama",
        "dtime",
        "start_dtime",
        "end_dtime",
        "harga",
        "spek",
    );

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

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
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    if(isset($inSpec['static']['rejection']) && ($inSpec['static']['rejection'] == 1)){
                        // kalau reject maka tidak perlu eksekusi.....
                    }
                    else{
                        $lCounter++;

                        $toUpdate = array();
                        foreach ($this->outFields as $kolom) {
                            if (isset($prev[$kolom])) {
                                $toUpdate[$kolom] = $prev[$kolom];
                            }
                        }
                        $this->load->model("Mdls/MdlProdukProject");
                        $p = new MdlProdukProject();

                        $pakai_ini = 0;
                        if ($pakai_ini == 1) {
                            $dataBaru = array();
                            foreach ($inSpec['static'] as $keyy => $vall) {
                                if (in_array($keyy, $this->outFields)) {
                                    $dataBaru[$keyy] = $vall;
                                }
                            }
                            $p->addData($dataBaru) or mati_disini("gagal menulis data project. Segera hubungi admin.");
                            cekLime($this->db->last_query());
//                    arrPrint($where);
//                    arrPrint($toUpdate);
                        }
                        else {
                            $project_id = isset($inSpec['static']['project_id']) ? $inSpec['static']['project_id'] : "";
                            if ($project_id == "" || $project_id == "0") {
                                // Lewati item phantom/kosong tanpa menghentikan aplikasi secara brutal
                                continue;
                            }
                            $p->addFilter("id='$project_id'");
                            $pTmp = $p->lookupAll()->result();
                            if (sizeof($pTmp) > 0) {
                                $tbl_id = $pTmp[0]->id;
                                $dataUpdate = array();
                                foreach ($inSpec['static'] as $keyy => $vall) {
                                    if (in_array($keyy, $this->outFields)) {
                                        $dataUpdate[$keyy] = $vall;
                                    }
                                }
                                if (sizeof($dataUpdate) > 0) {
                                    $where = array(
                                        "id" => $tbl_id,
                                    );
                                    $p->setFilters(array());
                                    $p->updateData($where, $dataUpdate);
                                    showLast_query("orange");
                                }
                            }
                            else {
                                $msg = "Transaksi gagal disimpan karena ID Project tidak dikenali. code: " . __LINE__;
                                mati_disini($msg);
                            }
                        }
                    }
                }
            }
        }

        return true;

    }

    private function cekPreValue($array)
    {

// arrPrint($array);
        $this->load->model("Mdls/MdlProdukProject");
        $tr = new MdlProdukProject();
        $tr->setFilters(array());
        $tr->addFilter("id='" . $array['id'] . "'");
        switch ($array['methode']) {
            case"open":
                $tr->addFilter("transaksi_id='0'");
                break;
            case "update":
                $tr->addFilter("id='" . $array['id'] . "'");
                break;
            case "close":
            case "revert":
                $tr->addFilter("transaksi_id='" . $array['transaksi_id'] . "'");
                break;
            default:
                matiHere("Gagal menyimpan transaksi (" . __CLASS__ . ") <br> ERROR CODE " . __LINE__ . "<br> ON " . date("Y-m-d H:i"));
                break;
        }
        $result = array();
        $localFilters = array();
        if (sizeof($tr->getFilters()) > 0) {
            foreach ($tr->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        //arrPrintWebs($localFilters);
        $query = $this->db->select()
            ->from($tr->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();
        // cekHijau($this->db->last_query());
        // matiHEre();
        switch ($array['methode']) {
            case"open":
                if (sizeof($tmpR) > 0) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            case "update":
                if (sizeof($tmpR) == 1) {
                    // unset($array["id"]);
                    // unset($array["transaksi_no"]);
                    // unset($array["transaksi_id"]);
                    $data = array("transaksi_no_app" => $array['transaksi_no'], "transaksi_id_app" => $array['transaksi_id']);
                    // "transaksi_id_app"=>"transaksi_id",
                    //         "transaksi_nomer_app"=>"transaksi_nomer",
                    // arrprint($data);
                    // matiHEre();
                }
                else {
                    $data = null;
                }
                break;
            case "close":

                if (sizeof($tmpR) == 1) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            case "revert":
                if (sizeof($tmpR) > 0) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            default:
                $data = null;
                break;
        }


        return $data;
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