<?php


class ComCloneSetupDepresiasi extends MdlMother
{

    protected $filters = array();
    protected $tableName;
    private $tableName_mutasi;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "rekening",
        "periode",
        "cabang_id",
        "cabang_nama",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "qty_debet",
        "qty_kredit",
        "dtime",
        "tgl",
        "bln",
        "thn",
        "extern_id",
        "extern_nama",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "jenis",
        "npwp",
        "fulldate",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening
        "transaksi_id",
        "transaksi_no",
        "transaksi_jenis",
        "cabang_id",
        "cabang_nama",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "qty_debet_awal",
        "qty_debet",
        "qty_debet_akhir",
        "qty_kredit_awal",
        "qty_kredit",
        "qty_kredit_akhir",
        "dtime",
        "extern_id",
        "extern_nama",
        "extern2_id",
        "extern2_nama",
        "extern3_id",
        "extern3_nama",
        "extern4_id",
        "extern4_nama",
        "jenis",
        "npwp",
        "fulldate",
        "keterangan",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {
        $this->tableName = "setup_depresiasi";
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_subcustomer",
        );
    }

    //  region setter, getter

    public function getTableNameMaster()
    {
        return $this->tableName_master;
    }

    public function setTableNameMaster($tableName_master)
    {
        $this->tableName_master = $tableName_master;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableNameTmp()
    {
        return $this->tableName__tmp;
    }

    public function setTableNameTmp($tableName__tmp)
    {
        $this->tableName__tmp = $tableName__tmp;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
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

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutFieldsMutasi()
    {
        return $this->outFieldsMutasi;
    }

    public function setOutFieldsMutasi($outFieldsMutasi)
    {
        $this->outFieldsMutasi = $outFieldsMutasi;
    }

    public function getTableNameMutasi()
    {
        return $this->tableName_mutasi;
    }

    public function setTableNameMutasi($tableName_mutasi)
    {
        $this->tableName_mutasi = $tableName_mutasi;
    }

    //  endregion setter, getter

    public function pair($inParams)
    {
        $this->load->helper("he_mass_table");
        if (count($inParams) > 0) {
            foreach ($inParams as $params) {
                $this->load->model("Mdls/MdlSetupDepresiasi");
                $s = new MdlSetupDepresiasi();
                $s->addFilter("extern_id='" . $params["static"]["produk_id"] . "'");
                $s->addFilter("cabang_id='" . $params["static"]["cabang_id"] . "'");
                $s->addFilter("jenis='" . $params["static"]["jenis"] . "'");
                $temp = $s->lookUpAll()->result();
                if (count($temp) > 0) {
                    $insertBaru = array();
                    foreach ($temp as $temp_0) {
                        foreach ($temp_0 as $col => $val) {
                            if ($col == "cabang_id") {
                                $values = $params["static"]["cabang2_id"];
                            } else {
                                $values = $val;
                            }
                            $insertBaru[$col] = $values;
                        }
                    }
                    if (count($insertBaru) > 0) {
                        unset($insertBaru["id"]);
                        $s->addData($insertBaru) or matiHEre("gagal memindahkan data seting depresiasi");
                        cekMErah($this->db->last_query());
                    }
                }

            }
        }
        return true;
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