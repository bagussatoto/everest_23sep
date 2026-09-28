<?php


class ComProdukProjectItems5 extends MdlMother
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
      "tgl_akhir_garansi",
      "keterangan_garansi",
      "nama",
      "master_id",
      "produk_id",
      "jumlah",
      "harga_project",
      "transaksi_id",
      "transaksi_no",
      "nomor_kontrak",
      "nomor_kontrak",
      "persen",
      "penyelesaian",
      "urut",
      "progress",
      "harga",
      "status",
      "trash",
      "project_start_nomer",
      "project_start",
      "project_start_dtime",
      "project_start_id",
      "project_start_name",
      "transaksi_dtime",
      "kontrak_oleh_id",
      "kontrak_oleh_nama",
      "kontrak_customer_id",
      "kontrak_customer_nama",
      "oleh_id",
      "oleh_nama",
      "customer_id",
      "customer_nama",
      "closing_status",
      "closing_oleh_id",
      "closing_oleh_nama",
      "closing_dtime",
      "closing_transaksi_id",
      "closing_transaksi_nomer",
    //  "keterangan",
      "project_started_id",
      "project_started_name",
      "project_started_dtime",
      "project_started_desc",
      "tanggal_kontrak"

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
        "jumlah",
        "harga_project",
        "urut",
    );

    public function __construct()
    {

    }


    public function pair($inParams)
    {
        $this->inParams = $inParams;
        arrPrint($this->inParams);
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $toUpdate = array();
                    foreach ($this->outFields as $kolom) {
                        if (isset($prev[$kolom])) {
                            $toUpdate[$kolom] = $prev[$kolom];
                        }
                    }
                    $this->load->model("Mdls/MdlProdukProjectItems5");
                    $p = new MdlProdukProjectItems5();
                    $dataBaru = array();
                    foreach ($inSpec['static'] as $keyy => $vall) {
                        if (in_array($keyy, $this->outFields)) {
                            $dataBaru[$keyy] = $vall;
                        }
                    }
                    $p->addData($dataBaru) or mati_disini("gagal menulis data project. Segera hubungi admin. #5");
//                    cekLime($this->db->last_query());
                }
            }
        }
        return true;
    }

    private function cekPreValue($array)
    {
        $this->load->model("Mdls/MdlProdukProjectItems5");
        $tr = new MdlProdukProjectItems5();
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
        $query = $this->db->select()
            ->from($tr->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();
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
                    $data = array("transaksi_no_app" => $array['transaksi_no'], "transaksi_id_app" => $array['transaksi_id']);
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