<?php


class ComProdukPerSupplier extends MdlMother
{

    protected $filters = array();
    protected $tableName;
    protected $tableName_fifoAvg;
    protected $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $koloms = array(
        "toko_id",
        "cabang_id",
        "suppliers_id",
        "produk_id",
        "nama",
        "jml",
        "hpp",
        "jml_nilai",
        "toko_id",
        //        "jml_ot",
        //        "jml_nilai_ot",
    );
    private $outFields = array( // dari tabel cache
        "produk_id",
        "produk_kode",
        "produk_nama",
        "suppliers_id",
        "suppliers_kode",
        "suppliers_nama",
        "status",
        "trash",
        "cabang_id",
        "cabang_nama",
        "harga",
        "hpp",
        "dtime",
        "dtime_last",
        "toko_id",
    );

//produk_per_supplier

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

                $_preValues = $this->cekPreValue(
                    $array_params['static']['suppliers_id'],
                    $array_params['static']['produk_id']
//                    $array_params['static']['toko_id']
                );


                if (array_key_exists("id", $_preValues) && ($_preValues["id"] > 0)) {
                    $mode = "update";
                    $_preValues_id = $_preValues["id"];
//                    $this->outParams[$lCounter][$mode]['id'] = $_preValues_id;
//                    $this->outParams[$lCounter][$mode]['trash'] = 1;
//                    $this->outParams[$lCounter][$mode]['status'] = 0;
//
                }
                else {
                    $_preValues_id = 0;
                    $mode = "insert";
                    $this->outParams[$lCounter][$mode]['id'] = $_preValues_id;
                    foreach ($array_params['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$mode][$key] = $value;
                        }
                    }
                }
            }
//
//            if (sizeof($this->outParams) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
            return true;
        }
        else {
            return true;
        }


    }

    private function cekPreValue($suppliers_id, $produk_id)
    {
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $l = new MdlProdukPerSupplier();

        $l->setFilters(array());
        $l->addFilter("suppliers_id='$suppliers_id'");
        $l->addFilter("produk_id='$produk_id'");
        $tmp = $l->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                    "suppliers_id" => $row->suppliers_id,
                    "produk_id" => $row->produk_id,
                );
            }
        }
        else {
            $result = array();
        }


        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {

        $this->load->model("Mdls/MdlProdukPerSupplier");
        $l = new MdlProdukPerSupplier();
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
//                            $this->db->where('id', $id);
//                            $insertIDs[] = $this->db->update($tableName, $pSpec_mode_data);
//
//                            cekOrange("$sub_mode :: " . $this->db->last_query() . " :: $id ::");
                            $insertIDs[] = 1;
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
        else {
            return true;
        }


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