<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoValasExternAverage extends CI_Model
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
        "extern_id",
        "extern_nama",
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
        $this->tableName = "fifo_valas_avg_extern";
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
//                $lCounter++;
                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {

                        $_preValues = $this->cekPreValue($array_params['static']['jenis'], $array_params['static']['cabang_id'], $array_params['static']['produk_id'], $array_params['static']['gudang_id'], $array_params['static']['extern_id']);

                        if (sizeof($_preValues) > 0) {
                            $mode = "update";
                            $this->outParams[$lCounter][$mode][$key] = $value;

                            $_preValues_id = $_preValues['id'];
                            $_temp_qtt = $_preValues['jml'] + $array_params['static']['jml'];
                            $_temp_nilai = $_preValues['jml_nilai'] + $array_params['static']['jml_nilai'];

                            $produk_hpp_avg = $_temp_nilai / $_temp_qtt;


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

                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    $this->load->model("Mdls/MdlFifoValasAverageExtern");
                    $fa = New MdlFifoValasAverageExtern();

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
//                                cekUngu($this->db->last_query());
                            }
                        }
                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
                            return false;
                        }
                    }
                    else {
                        return false;
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

    private function cekPreValue($jenis, $cabang_id, $produk_id, $gudang_id, $extern_id)
    {
        $this->load->model("Mdls/MdlFifoValasAverageExtern");
        $fa = New MdlFifoValasAverageExtern();

        //  region filter dan query
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("produk_id='$produk_id'");
        $this->addFilter("extern_id='$extern_id'");
        $this->addFilter("jenis='$jenis'");
//        $tmp_result = $fa->lookupAll()->result();
        //  endregion filter dan query


        $localFilters = array();
        $tmp_result = array();

        if (sizeof($this->getfilters()) > 0) {
            foreach ($this->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($fa->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
        if (sizeof($subtmp) > 0) {
            $tmp_result[0] = (object)$subtmp;
        }
        else {
            $tmp_result = array();
        }
        if (sizeof($tmp_result) > 0) {
            foreach ($tmp_result as $row) {
                $final_result = array(
                    "id" => $row->id,
                    "jml" => $row->jml,
                    "jml_nilai" => $row->jml_nilai,
                );
            }
        }
        else {
            cekMerah("tidak ada isinya...");
            $final_result = null;
        }

        return $final_result;
    }

    public function exec()
    {
//        $this->load->model("Mdls/MdlFifoValasAverage");
//        $fa = New MdlFifoValasAverage();
//
//        $insertIDs = array();
//        if (sizeof($this->outParams) > 0) {
//            foreach ($this->outParams as $array_out_params) {
//                foreach ($array_out_params as $mode => $pSpec_mode) {
//
//                    switch ($mode) {
//                        case "insert":
//                            $insertIDs[] = $fa->addData($pSpec_mode);
//                            cekLime(" " . __LINE__);
//                            cekHitam("$mode :: " . $this->db->last_query());
////                            die();
//                            break;
//                        case "update":
//                            $id = $pSpec_mode['id'];
//                            unset($pSpec_mode['id']);
//
//                            $where = array("id" => "$id");
//                            $insertIDs[] = $fa->updateData($where, $pSpec_mode);
//
//
//                            cekOrange("$mode :: " . $this->db->last_query());
//                            break;
//                    }
//                }
//            }
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
//        }
//        else {
//            return false;
//        }

//
        return true;
    }
}