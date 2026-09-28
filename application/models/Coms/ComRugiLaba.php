<?php


class ComRugiLaba extends MdlMother
{

    protected $filters = array();
    protected $tableName;
    protected $tableName_lajur;
    protected $tableName_mutasi;
    protected $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi
    );
    private $outParams = array( //===output ke tabel
    );
    private $outParams2 = array( //===output ke tabel
    );
    private $outFields = array( // dari tabel rek_cache
        "rekening",
        "periode",
        "cabang_id",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "dtime",
        "tgl",
        "bln",
        "thn",
        "fulldate",
    );
    private $outFieldsMutasi = array( // dari tabel rek mutasi rekening umum
        "dtime",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "jenis",
        "debet_awal",
        "debet",
        "debet_akhir",
        "kredit_awal",
        "kredit",
        "kredit_akhir",
        "keterangan",
    );
    private $periode = array("harian", "bulanan", "tahunan", "forever");


    public function __construct()
    {
        $this->tableName = "_rek_master_cache";
        $this->tableName_master = array(
            "mutasi" => "_rek_master",
            //            "cache" => "_rek_master_cache",
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

    public function getTableName_lajur()
    {
        return $this->tableName_lajur;
    }

    public function setTableName_lajur($tableName_lajur)
    {
        $this->tableName_lajur = $tableName_lajur;
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

    public function getOutParams2()
    {
        return $this->outParams2;
    }

    public function setOutParams2($outParams2)
    {
        $this->outParams2 = $outParams2;
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
        return true;
    }

    private function cekPreValue($rek, $cabang_id)
    {
        $this->load->model("Mdls/MdlRugilaba");
        $rl = New MdlRugilaba();


        $this->filters = array();
        $this->addFilter("rekening='$rek'");
        $this->addFilter("cabang_id='$cabang_id'");
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                }
                else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>

                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }

        //  region mengambil saldo dari tabel rugilaba
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $this->db->where($criteria);
        $tmp = $this->db->get($rl->getTableName())->result();
        cekMerah($this->db->last_query());
        if (sizeof($tmp) > 0) {
            // bila count($tmp) > 0, maka ambil saldo periode sendiri, dan mode update
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                );
            }
        }
        else {
            $result = array(
                "debet" => 0,
                "kredit" => 0,
            );
        }
        //  endregion mengambil saldo dari rek_cache

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

    public function buildTables($inParams)
    {

        $this->load->helper("he_mass_table");

        $arrRekening = array();
        $this->inParams = $inParams;
        if (sizeof($this->inParams['loop']) > 0) {
            foreach ($this->periode as $periode) {
                $arrRekening = array();
                foreach ($this->inParams['loop'] as $key => $value) {
                    $arrRekening[] = $key;
                }
            }
        }
        else {
            $arrRekening = array();
        }

        if (sizeof($arrRekening) > 0) {
            $result = heReturnTableName($this->tableName_master, $arrRekening);
            if (sizeof($result) > 0) {
                foreach ($result as $rek => $arrSpec) {
                    foreach ($arrSpec as $key => $val) {
//                        cekMerah("create tabel $val - $key");
                        $result_c = tableForceCheck($val, $this->tableName_master[$key]);
                    }
                }
            }
        }
    }


}