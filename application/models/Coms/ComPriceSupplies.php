<?php


class ComPriceSupplies extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $koloms = array(
        "cabang_id",
        "produk_id",
        "nama",
        "jml",
        "hpp",
        "jml_nilai",
    );
    private $outFields = array( // dari tabel cache
        "jenis",
        "jenis_value",
        "produk_id",
        "cabang_id",
        "nilai",
        "dtime",
        "status",
        "oleh_id",
        "oleh_nama",
        "keterangan",
    );


    public function __construct()
    {
        $this->tableName_master = array(
            "mutasi" => "_rek_pembantu_produk",
            "cache" => "_rek_pembantu_produk_cache",
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

    //  endregion setter, getter

    public function pair($inParams)
    {

        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $array_params) {
                $lCounter++;

                $_preValues = $this->cekPreValue($array_params['static']['jenis'], $array_params['static']['jenis_value'], $array_params['static']['cabang_id'], $array_params['static']['produk_id']);

                if (array_key_exists("id", $_preValues) && ($_preValues["id"] > 0)) {
                    $mode = "update";
                    $_preValues_id = $_preValues["id"];
                    $this->outParams[$lCounter][$mode]['id'] = $_preValues_id;

//                    foreach ($array_params['static'] as $key => $value) {
//                        if (in_array($key, $this->outFields)) {
//                            $this->outParams[$lCounter][$mode][$key] = $value;
//                        }
//                    }


                    $this->outParams[$lCounter][$mode]['status'] = 0;
                }
                else {
                }
                $mode = "insert";
                $_preValues_id = 0;
                $this->outParams[$lCounter][$mode]['id'] = $_preValues_id;

                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$mode][$key] = $value;
                    }
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

    private function cekPreValue($jenis, $jenis_value, $cabang_id, $produk_id)
    {
        $this->load->model("Mdls/MdlHargaSupplies");
        $l = new MdlHargaSupplies();

        $l->setFilters(array());
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("jenis_value='$jenis_value'");
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("produk_id='$produk_id'");

        //  region cek ricek tabel price
        $tmp = $l->lookupAll()->result();
        cekHitam($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                    "cabang_id" => $row->cabang_id,
                    "produk_id" => $row->produk_id,
                );
            }
        }
        else {
            $result = array();
        }
        //  endregion

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {

        $this->load->model("Mdls/MdlHargaSupplies");
        $l = new MdlHargaSupplies();
        $tableName = $l->getTableName();

        $insertIDs = array();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $lCounter => $pSpec) {
                foreach ($pSpec as $sub_mode => $pSpec_mode_data) {
                    $id = $pSpec_mode_data["id"];
                    unset($pSpec_mode_data["id"]);

                    switch ($sub_mode) {
                        case "insert":
                            $this->db->insert($tableName, $pSpec_mode_data);
                            $insertIDs[] = $this->db->insert_id();
                            cekBiru("$sub_mode :: " . $this->db->last_query());
                            break;
                        case "update":
                            $this->db->where('id', $id);
                            $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);

                            cekOrange("$sub_mode :: " . $this->db->last_query() . " :: $id ::");
                            break;
                    }
                }

            }
        }
        return true;
//        if (sizeof($insertIDs) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
    }

    public function lookupLastEntries($cabang_id)
    {
//        $periode = $this->getPeriode()['3'];
        $condite = array("trash" => "0", "cabang_id" => "$cabang_id");
        $arrKoloms = $this->getKoloms();
        $selectKolom = "";
        foreach ($arrKoloms as $kolom) {
            $selectKolom .= "$kolom,";
        }
        $selectKolom = rtrim($selectKolom, ",");

        $this->db->select($selectKolom);
        $this->db->where($condite);
        $q = $this->db->get($this->tableName_fifoAvg)->result();

        return $q;
    }

    public function getKoloms()
    {
        return $this->koloms;
    }


}