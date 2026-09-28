<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComFifoAverageSupplies extends CI_Model
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
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlFifoAverage");
//        $this->tableName = "fifo_valas_avg";
        $this->blacklist = array("1119", "2229", "1118", "2228", "2227", "3339", "5559", "4419", "4418");
        $this->jenisConversi = array("1334", "2334", "2336", "2337", "334", "335", "2335", "1337", "7620", "7622", "1336", "336", "1339", "3355");
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

    private function normalizeDecimal($value, $scale = 10)
    {
        $value = (float)$value;
        $result = round($value, $scale);
        $epsilon = pow(10, -1 * $scale);
        if (abs($result) < $epsilon) {
            $result = 0;
        }
        return $result;
    }

    public function pair($inParams)
    {

        $this->inParams = $inParams;
        $this->outParams = array();
        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
            $last_stock = array();
            foreach ($this->inParams as $array_params) {
                $lCounter++;
                // $startTime = microtime(true);
                $_preValues = $this->cekPreValue($array_params['static']['jenis'], $array_params['static']['cabang_id'], $array_params['static']['produk_id'], $array_params['static']['gudang_id']);
                cekHere($this->db->last_query());
                arrPrint($_preValues);
                // $endtime = microtime(true); // Bottom of page
                // $valAvg = $endtime - $startTime;
                // cekHitam("exec post AVG ".$valAvg);
                $pId = isset($array_params['static']['produk_id']) ? $array_params['static']['produk_id'] : 0;
                $pNama = isset($array_params['static']['nama']) ? $array_params['static']['nama'] : 0;
                if (sizeof($_preValues) > 0) {
                    $mode = "update";
                    // $this->outParams[$lCounter][$mode][$key] = $value;

                    $jmlNilai_riil = isset($array_params['static']['jml_nilai_riil']) ? $array_params['static']['jml_nilai_riil'] : 0;
                    $ppvNilai_riil = isset($array_params['static']['ppv_nilai_riil']) ? $array_params['static']['ppv_nilai_riil'] : 0;
                    $_preValues_id = $_preValues['id'];
                    $_temp_qtt = $_preValues['jml'] + $array_params['static']['jml'];
                    $_temp_nilai = $_preValues['jml_nilai'] + $array_params['static']['jml_nilai'];
                    $_temp_nilai_riil = $_preValues['jml_nilai_riil'] + $jmlNilai_riil;
                    $_ppv_nilai_riil = $_preValues['ppv_nilai_riil'] + $ppvNilai_riil;
                    if ($_temp_qtt == 0) {
                        $_temp_nilai = 0;
                        $_temp_nilai_riil = 0;
                        $_ppv_nilai_riil = 0;
                    }
                    $_temp_nilai = $this->normalizeDecimal($_temp_nilai);
                    $_temp_nilai_riil = $this->normalizeDecimal($_temp_nilai_riil);
                    $_ppv_nilai_riil = $this->normalizeDecimal($_ppv_nilai_riil);

                    $last_stock[$array_params['static']['produk_id']] = $_preValues['jml'];
                    // $_temp_nilai_riil = $_preValues['jml_nilai_riil'] + $array_params['static']['jml_nilai_riil'];
                    // $_ppv_nilai_riil = $_preValues['ppv_nilai_riil'] + $array_params['static']['ppv_nilai_riil'];
                    cekHitam("$pNama $_temp_nilai [qtt: $_temp_qtt]");
                    $produk_hpp_avg = ($_temp_qtt == 0) ? 0 : $this->normalizeDecimal($_temp_nilai / $_temp_qtt); // hpp rata-rata standart
                    $produk_hpp_avg_riil = ($_temp_qtt == 0) ? 0 : $this->normalizeDecimal($_temp_nilai_riil / $_temp_qtt); // hpp rata-rata riil
                    $produk_ppv_avg_riil = ($_temp_qtt == 0) ? 0 : $this->normalizeDecimal($_ppv_nilai_riil / $_temp_qtt); // ppv rata-rata riil

//                    matiHEre($_temp_nilai);
                    //------
                    $kiriman_jenisTr = isset($array_params['static']['jenisTr']) ? $array_params['static']['jenisTr'] : 0;
                    if (!in_array($kiriman_jenisTr, $this->blacklist)) {
                        /**
                         * proteksi nilai hpp average minimal 1 rupiah karena akan ada kemungkinan dari opanme harga 1 rupiah.
                         * sebelumnya proteksi wajib > 500 membuat sistem berhenti bekerja
                         */
                        if ($array_params['static']['jml'] > 0) {
                            if ($produk_hpp_avg < 1) {
                                /**
                                 * deteksi konversi source yang harga awal salah atau 1 rupiah dan perlu diperbaiki
                                 */
                                if (in_array($kiriman_jenisTr, $this->jenisConversi)) {
                                    $cCode = "_TR_" . $kiriman_jenisTr;
                                    $pName_surce = $_SESSION[$cCode]["items"][$array_params['static']["id_src"]]["nama"];
                                    $pHpp_surce = $_SESSION[$cCode]["items"][$array_params['static']["id_src"]]["harga"] * 1;
//                                    matiHEre(__LINE__."::".$cCode."::".$pName_surce);
                                    $msg = "Nilai rata-rata <br>$pName_surce <br>tidak valid untuk di konversi (Rp $pHpp_surce). <br>Silahkan diperbaiki dahulu melalui menu otorisasi (koreksi harga persediaan) " . __LINE__;
                                } else {
                                    $msg = "Nilai rata-rata $pNama dibawah 1 ($produk_hpp_avg). Silahkan periksa kembali transaksi anda. code: " . __LINE__;
                                }

                                mati_disini($msg);
                            }
                            if (($_temp_qtt <= $_preValues['jml'])) {// saldo akhir sama dengan saldo awal
                                $msg = "Jumlah stok $pNama tidak bertambah. Silahkan periksa kembali transaksi anda (refresh dan approve ulang). code: " . __LINE__;
                                mati_disini($msg);
                            }
                        }
                    }
                    //------
                    $this->outParams[$lCounter][$mode]["id"] = $_preValues_id;
                    $this->outParams[$lCounter][$mode]["jml"] = $_temp_qtt;
                    $this->outParams[$lCounter][$mode]["hpp"] = $produk_hpp_avg;
                    $this->outParams[$lCounter][$mode]["hpp_riil"] = $produk_hpp_avg_riil;
                    $this->outParams[$lCounter][$mode]["jml_nilai"] = $this->normalizeDecimal($_temp_nilai);
                    $this->outParams[$lCounter][$mode]["jml_nilai_riil"] = $this->normalizeDecimal($_temp_nilai_riil);
                    $this->outParams[$lCounter][$mode]["ppv_nilai_riil"] = $this->normalizeDecimal($_ppv_nilai_riil);
                    $this->outParams[$lCounter][$mode]["ppv_riil"] = $produk_ppv_avg_riil;

                } else {
                    $last_stock[$array_params['static']['produk_id']] = 0;
                    $mode = "insert";
                    foreach ($array_params['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$mode][$key] = $value;
                        }
                    }

                }

                // arrPrint($array_params);
                // matiHEre();
                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    $this->load->model("Mdls/MdlFifoAverage");
                    $fa = new MdlFifoAverage();

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
                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
                            return false;
                        }

                    } else {
                        return false;
                    }

                    // region cek stokAwal dan stokAkhir
                    if ($array_params['static']['jml'] > 0) {
                        $fa = new MdlFifoAverage();
                        $fa->addFilter("produk_id=" . $array_params['static']['produk_id']);
                        $fa->addFilter("cabang_id=" . $array_params['static']['cabang_id']);
                        $fa->addFilter("gudang_id=" . $array_params['static']['gudang_id']);
                        $fa->addFilter("jenis=" . $array_params['static']['jenis']);
                        $faTmp = $fa->lookupAll()->result();
                        if (sizeof($faTmp) > 0) {
                            $pNama = $faTmp[0]->nama;
                            $jml_hasil = $faTmp[0]->jml;
                            cekHere("cek stok: $jml_hasil <= " . $last_stock[$array_params['static']['produk_id']]);
                            if ($jml_hasil <= $last_stock[$array_params['static']['produk_id']]) {
                                $msg = "Jumlah stok $pNama tidak bertambah. Silahkan periksa kembali transaksi anda (refresh dan approve ulang). code: " . __LINE__;
                                mati_disini($msg);
                            }
                        }
                    }
                    // endregion cek stokAwal dan stokAkhir
                }
            }
//            if(sizeof($last_stock)>0){
//                $arrKeyIds = array_keys($last_stock);
//                $fa = New MdlFifoAverage();
//
//            }

//            mati_disini(__LINE__);
        }

//        if (sizeof($insertIDs) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
        return true;
    }

    public function pair__($inParams)
    {

        $this->inParams = $inParams;
        $this->outParams = array();
        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
//                $lCounter++;
                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {

                        $_preValues = $this->cekPreValue($array_params['static']['jenis'], $array_params['static']['cabang_id'], $array_params['static']['produk_id'], $array_params['static']['gudang_id']);
                        arrPrint($_preValues);
                        if (sizeof($_preValues) > 0) {
                            $mode = "update";
                            $this->outParams[$lCounter][$mode][$key] = $value;

                            $jmlNilai_riil = isset($array_params['static']['jml_nilai_riil']) ? $array_params['static']['jml_nilai_riil'] : 0;
                            $ppvNilai_riil = isset($array_params['static']['ppv_nilai_riil']) ? $array_params['static']['ppv_nilai_riil'] : 0;
                            $_preValues_id = $_preValues['id'];
                            $_temp_qtt = $_preValues['jml'] + $array_params['static']['jml'];
                            $_temp_nilai = $_preValues['jml_nilai'] + $array_params['static']['jml_nilai'];
                            $_temp_nilai_riil = $_preValues['jml_nilai_riil'] + $jmlNilai_riil;
                            $_ppv_nilai_riil = $_preValues['ppv_nilai_riil'] + $ppvNilai_riil;
                            if ($_temp_qtt == 0) {
                                $_temp_nilai = 0;
                                $_temp_nilai_riil = 0;
                                $_ppv_nilai_riil = 0;
                            }
                            $_temp_nilai = $this->normalizeDecimal($_temp_nilai);
                            $_temp_nilai_riil = $this->normalizeDecimal($_temp_nilai_riil);
                            $_ppv_nilai_riil = $this->normalizeDecimal($_ppv_nilai_riil);

                            // $_temp_nilai_riil = $_preValues['jml_nilai_riil'] + $array_params['static']['jml_nilai_riil'];
                            // $_ppv_nilai_riil = $_preValues['ppv_nilai_riil'] + $array_params['static']['ppv_nilai_riil'];

                            $produk_hpp_avg = $_temp_qtt == 0 ? 0 : $this->normalizeDecimal($_temp_nilai / $_temp_qtt); // hpp rata-rata standart
                            $produk_hpp_avg_riil = $_temp_qtt == 0 ? 0 : $this->normalizeDecimal($_temp_nilai_riil / $_temp_qtt); // hpp rata-rata riil

                            $produk_ppv_avg_riil = $_temp_qtt == 0 ? 0 : $this->normalizeDecimal($_ppv_nilai_riil / $_temp_qtt); // ppv rata-rata riil


                            $this->outParams[$lCounter][$mode]["id"] = $_preValues_id;
                            $this->outParams[$lCounter][$mode]["jml"] = $_temp_qtt;
                            $this->outParams[$lCounter][$mode]["hpp"] = $produk_hpp_avg;
                            $this->outParams[$lCounter][$mode]["hpp_riil"] = $produk_hpp_avg_riil;
                            $this->outParams[$lCounter][$mode]["jml_nilai"] = $this->normalizeDecimal($_temp_nilai);
                            $this->outParams[$lCounter][$mode]["jml_nilai_riil"] = $this->normalizeDecimal($_temp_nilai_riil);
                            $this->outParams[$lCounter][$mode]["ppv_nilai_riil"] = $this->normalizeDecimal($_ppv_nilai_riil);
                            $this->outParams[$lCounter][$mode]["ppv_riil"] = $produk_ppv_avg_riil;
                        } else {
                            $mode = "insert";
                            $this->outParams[$lCounter][$mode][$key] = $value;
                        }
                    }
                }

                $pakai_exec = 1;
                if ($pakai_exec == 1) {

                    $this->load->model("Mdls/MdlFifoAverage");
                    $fa = new MdlFifoAverage();

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

                    } else {
                        return false;
                    }

                }

            }
        }

        if (sizeof($insertIDs) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $gudang_id)
    {
        $this->load->model("Mdls/MdlFifoAverage");
        $fa = new MdlFifoAverage();

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
        } else {
            $tmp_result = array();
        }


        if (sizeof($tmp_result) > 0) {
            /**
             * update untuk cek rekening untuk nilai rata-rata
             * ambil dari rekening supaya konsisten
             *
             */

            $this->load->model("Coms/ComRekeningPembantuSupplies");
            $m = new ComRekeningPembantuSupplies();
            $m->setFilters(array());
            $m->addFilter("cabang_id='" . $cabang_id . "'");
            $m->addFilter("gudang_id='" . $gudang_id . "'");
            $m->addFilter("extern_id='" . $produk_id . "'");
            $temp = $m->fetchBalances("1010030010");//
            foreach ($tmp_result as $row) {
                $final_result = array(
                    "id" => $row->id,
                    "jml" => isset($temp[0]->qty_debet) ? $temp[0]->qty_debet:0,
                    "jml_nilai" => isset($temp[0]->debet) ? $temp[0]->debet:0,
                    "jml_nilai_riil" => 0,
                    "ppv_nilai_riil" => 0,
                    "ppv_riil" => 0,
                );
            }
        } else {
            cekMerah("[$produk_id] tidak ada isinya...");
            $final_result = null;
        }

        return $final_result;
    }

    public function exec()
    {

        return true;
    }

}
