<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoAverageKoreksi extends CI_Model
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
        "jml_nilai_riil",
        "ppv_nilai_riil",
        "ppv_riil",
        "hpp_riil",

        "ppn_in",
        "ppn_in_nilai",
        "suppliers_id",
        "suppliers_nama",

        "hpp_nppv",
        "jml_nilai_nppv",
        "produk_jenis",
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
        arrPrint($inParams);
        $this->outParams = array();
        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                $lCounter++;
                // $startTime = microtime(true);
                $_preValues = $this->cekPreValue($array_params['static']['jenis'], $array_params['static']['cabang_id'], $array_params['static']['produk_id'], $array_params['static']['gudang_id']);
                // $endtime = microtime(true); // Bottom of page
                // $valAvg = $endtime - $startTime;
                // cekHitam("exec post AVG ".$valAvg);
                if (sizeof($_preValues) > 0) {
                    $mode = "update";
                    $rejection = isset($array_params['static']['rejection']) ? $array_params['static']['rejection'] : 0;
                    if ($rejection == 1) {
//                        cekHere("MASUK REJECT");
//                        arrPrint($_preValues);
                        //region bagian reject/batal
                        $jmlNilai_riil = isset($array_params['static']['jml_nilai_riil']) ? $array_params['static']['jml_nilai_riil'] : 0;
                        $ppvNilai_riil = isset($array_params['static']['ppv_nilai_riil']) ? $array_params['static']['ppv_nilai_riil'] : 0;
                        $_preValues_id = $_preValues['id'];
                        $_temp_qtt = $_preValues['jml'];
                        $_temp_jml_nilai = $_preValues['jml_nilai'];
                        $_temp_jml_nilai_riil = $_preValues['jml_nilai_riil'];
                        $_temp_jml_nilai_koreksi = $array_params['static']['jml_nilai_nppv'];
                        $_faktor_qty = ($array_params['static']['jml'] < 0) ? 1 : -1;

                        $_temp_nilai = $_temp_jml_nilai + ($_temp_jml_nilai_koreksi * $_faktor_qty);
                        $_temp_nilai_riil = $_temp_jml_nilai_riil + ($_temp_jml_nilai_koreksi * $_faktor_qty);
                        $_ppv_nilai_riil = $_preValues['ppv_nilai_riil'] + ($ppvNilai_riil * $_faktor_qty);

                        $produk_hpp_avg = ($_temp_qtt == 0) ? 0 : $_temp_nilai / $_temp_qtt;
                        $produk_hpp_avg_riil = ($_temp_qtt == 0) ? 0 : $_temp_nilai_riil / $_temp_qtt;
                        $produk_ppv_avg_riil = ($_temp_qtt == 0) ? 0 : $_ppv_nilai_riil / $_temp_qtt;

                        $this->outParams[$lCounter][$mode]["id"] = $_preValues_id;
                        //                    $this->outParams[$lCounter][$mode]["jml"] = $_temp_qtt;// jumlah tidak diupdate karena koreksi harga beli (ke nilai stok)
                        $this->outParams[$lCounter][$mode]["hpp"] = $produk_hpp_avg;
                        $this->outParams[$lCounter][$mode]["hpp_riil"] = $produk_hpp_avg_riil;
                        $this->outParams[$lCounter][$mode]["jml_nilai"] = $_temp_nilai;
                        $this->outParams[$lCounter][$mode]["jml_nilai_riil"] = $_temp_nilai_riil;
                        $this->outParams[$lCounter][$mode]["ppv_nilai_riil"] = $_ppv_nilai_riil;
                        $this->outParams[$lCounter][$mode]["ppv_riil"] = $produk_ppv_avg_riil;
                        //endregion

                    }
                    else {
                        $jmlNilai_riil = isset($array_params['static']['jml_nilai_riil']) ? $array_params['static']['jml_nilai_riil'] : 0;
                        $ppvNilai_riil = isset($array_params['static']['ppv_nilai_riil']) ? $array_params['static']['ppv_nilai_riil'] : 0;
                        $_preValues_id = $_preValues['id'];
                        $_temp_qtt = $_preValues['jml'];
//                    $_temp_qtt =  $array_params['static']['jml'];
                        $_temp_nilai = $array_params['static']['jml_nilai'];
                        $_temp_nilai_riil = $array_params['static']['jml_nilai_riil'];
                        $_ppv_nilai_riil = $_preValues['ppv_nilai_riil'] + $ppvNilai_riil;

                        $produk_hpp_avg = $_temp_qtt == 0 ? 0 : $_temp_nilai / $_temp_qtt; // hpp rata-rata standart
                        $produk_hpp_avg_riil = $_temp_qtt == 0 ? 0 : $array_params['static']['hpp_riil']; // hpp rata-rata riil
                        $produk_ppv_avg_riil = $_temp_qtt == 0 ? 0 : $_ppv_nilai_riil / $_temp_qtt; // ppv rata-rata riil

                        $this->outParams[$lCounter][$mode]["id"] = $_preValues_id;
//                    $this->outParams[$lCounter][$mode]["jml"] = $_temp_qtt;// jumlah tidak diupdate karena koreksi harga beli (ke nilai stok)
                        $this->outParams[$lCounter][$mode]["hpp"] = $produk_hpp_avg;
                        $this->outParams[$lCounter][$mode]["hpp_riil"] = $produk_hpp_avg_riil;
                        $this->outParams[$lCounter][$mode]["jml_nilai"] = $_temp_nilai;
                        $this->outParams[$lCounter][$mode]["jml_nilai_riil"] = $_temp_nilai_riil;
                        $this->outParams[$lCounter][$mode]["ppv_nilai_riil"] = $_ppv_nilai_riil;
                        $this->outParams[$lCounter][$mode]["ppv_riil"] = $produk_ppv_avg_riil;
                    }

                }
                else {
                    $mode = "insert";

                    foreach ($array_params['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$mode][$key] = $value;
                        }
                    }
                }

//                 arrPrint($array_params);
//                 matiHEre();
                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    $this->load->model("Mdls/MdlFifoAverage");
                    $fa = New MdlFifoAverage();

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
//matiHere(__LINE__." ".__FUNCTION__);
        if (sizeof($insertIDs) > 0) {
            return true;
        }
        if (sizeof($insertIDs) > 0) {
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
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("produk_id='$produk_id'");
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
                    "jml_nilai_riil" => $row->jml_nilai_riil,
                    "ppv_nilai_riil" => $row->ppv_nilai_riil,
                    "ppv_riil" => $row->ppv_nilai_riil,
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
//        $this->load->model("Mdls/MdlFifoAverage");
//        $fa = New MdlFifoAverage();
//
//        $insertIDs = array();
//        if (sizeof($this->outParams) > 0) {
//            foreach ($this->outParams as $array_out_params) {
//                foreach ($array_out_params as $mode => $pSpec_mode) {
//                    switch ($mode) {
//                        case "insert":
//                            $insertIDs[] = $fa->addData($pSpec_mode);
//
//                            cekBiru("$mode :: " . $this->db->last_query());
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