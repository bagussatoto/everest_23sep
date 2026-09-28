<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoAverage extends CI_Model
{

    protected $filters = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel rek_cache

        "jenis",
        "produk_id",
        "nama",
        "cabang_id",
        //        "satuan",
        //        "state",
        "jml",
        "hpp",
        "jml_nilai",
        "gudang_id",

    );

    public function __construct()
    {
        parent::__construct();
//        $this->tableName = "fifo_valas_avg";
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function pair($inParams)
    {

        $this->inParams = $inParams;
        $this->outParams = array();
        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                $lCounter++;
                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {

                        $_preValues = $this->cekPreValue($array_params['static']['jenis'], $array_params['static']['cabang_id'], $array_params['static']['produk_id'], $array_params['static']['gudang_id']);

                        if (sizeof($_preValues) > 0) {
                            $mode = "update";
                            $this->outParams[$lCounter][$mode][$key] = $value;

                            $_preValues_id = $_preValues['id'];
                            $_temp_qtt = $_preValues['jml'] + $array_params['static']['jml'];
                            $_temp_nilai = $_preValues['jml_nilai'] + $array_params['static']['jml_nilai'];

                            $produk_hpp_avg = $_temp_qtt == 0 ? 0 : $_temp_nilai / $_temp_qtt;


                            $this->outParams[$lCounter][$mode]["id"] = $_preValues_id;
                            $this->outParams[$lCounter][$mode]["jml"] = $_temp_qtt;
                            $this->outParams[$lCounter][$mode]["hpp"] = $produk_hpp_avg;
                            $this->outParams[$lCounter][$mode]["jml_nilai"] = $_temp_nilai;
                        }
                        else {
                            $mode = "insert";
                            $this->outParams[$lCounter][$mode][$key] = $value;
                        }
                    }
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

    private function cekPreValue($jenis, $cabang_id, $produk_id, $gudang_id)
    {
        $this->load->model("Mdls/MdlFifoAverage");
        $fa = New MdlFifoAverage();

        //  region filter dan query
        $fa->addFilter("cabang_id='$cabang_id'");
        $fa->addFilter("gudang_id='$gudang_id'");
        $fa->addFilter("produk_id='$produk_id'");
        $fa->addFilter("jenis='$jenis'");
        $tmp_result = $fa->lookupAll()->result();
        //  endregion filter dan query


        if (sizeof($tmp_result) > 0) {
            cekKuning("[$produk_id] ada isinya...");
//            $final_result = $tmp_result;
            foreach ($tmp_result as $row) {
                $final_result = array(
                    "id" => $row->id,
                    "jml" => $row->jml,
                    "jml_nilai" => $row->jml_nilai,
                );
            }
        }
        else {
            cekMerah("[$produk_id] tidak ada isinya...");
            $final_result = null;
        }

        return $final_result;
    }

    public function exec()
    {
        $this->load->model("Mdls/MdlFifoAverage");
        $fa = New MdlFifoAverage();
//        arrPrint($this->outParams);
        $insertIDs = array();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $array_out_params) {
                foreach ($array_out_params as $mode => $pSpec_mode) {
                    switch ($mode) {
                        case "insert":
                            $insertIDs[] = $fa->addData($pSpec_mode);

                            cekBiru("$mode :: " . $this->db->last_query());
                            break;
                        case "update":
                            $id = $pSpec_mode['id'];
                            unset($pSpec_mode['id']);

                            $where = array("id" => "$id");
                            $insertIDs[] = $fa->updateData($where, $pSpec_mode);


                            cekOrange("$mode :: " . $this->db->last_query());
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
            return false;
        }

    }
}