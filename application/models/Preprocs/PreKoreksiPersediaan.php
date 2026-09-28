<?php

/**
 * untuk koresksi slaah harga ataupun tambahan diskon dari supplier
 * untuk melakukan koreksi nilai persediaan atau jika nilai habis dibebankan ke hpp
 * koerksi perediian riil dan hpp riil
 * dipertimbangkan stok produk dan dibagi rata
 */
class PreKoreksiPersediaan extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
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

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Coms/ComRekeningPembantuProdukRiil");
//        arrPrint($inParams);
//matiHere(__LINE__);
        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        $tmp = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $sentParams) {
                arrPrint($sentParams);

                $total_selisih_items = $sentParams["static"]["subtotal"] - $sentParams["static"]["sub_harga_x"];

                $selisih_nilai = $sentParams["static"]["selisih_minus"] > 0 ? $sentParams["static"]["selisih_minus"] : $sentParams["static"]["selisih_plus"];
                $selisih_metode = $sentParams["static"]["selisih_minus"] > 0 ? "hutang_dagang" : "persediaan";
//                cekBiru($sentParams["static"]["diskon_tambahan"]);
                cekHitam($selisih_nilai);
                cekBiru($selisih_metode);

                $pakaiini = 0;
                if ($pakaiini == 1) {
                    //versi diskon globall
                    if (isset($sentParams["static"]["diskon_tambahan"]) && $sentParams["static"]["diskon_tambahan"] > 0) {
                        $b = new ComRekeningPembantuProduk();
//                    $b->addFilter("jenis='produk'");
                        $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                        $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                        $b->addFilter("extern_id='" . $sentParams['static']["extern_id"] . "'");
                        $b->addFilter("rekening='1010030030'");
                        $b->addFilter("periode='forever'");
                        $localFilters = array();
                        if (sizeof($b->getfilters()) > 0) {
                            foreach ($b->getfilters() as $f) {
                                $tmpArr = explode("=", $f);
                                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                            }
                        }
                        $query = $this->db->select()
                            ->from($b->getTableName())
                            ->where($localFilters)
                            ->limit(1)
                            ->get_compiled_select();
                        $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                        cekMerah($this->db->last_query());

                        $curentDebet = $subtmp["debet"];
                        $curentQtyDebet = $subtmp["qty_debet"];
//                    matiHere($curentQtyDebet);
                        $preval = $sentParams["static"]["diskon_tambahan"] * ($sentParams["static"]["subtotal"] / $sentParams["static"]["sisa"]);
                        $nilai_unit = $preval / $curentQtyDebet;

                        if ($curentDebet > 0) {
                            $tmp[] = array(
                                "produk_id" => $sentParams['static']["extern_id"],
                                "koreksi_hutang_dagang_nilai" => $nilai_unit,
                                "koreksi_persediaan_nilai" => $nilai_unit,
                                "koreksi_hpp_nilai" => 0,
                                "koreksi_fifo_nilai" => $nilai_unit,
                                "koreksi_fifo_nilai_unit" => $nilai_unit,
                                "jml" => $curentQtyDebet,
                                "id" => $subtmp["extern_id"],
                                "name" => $subtmp["extern_nama"],
                                "nama" => $subtmp["extern_nama"],

                            );
                        }
                        else {
                            $tmp[] = array(
                                "produk_id" => $sentParams['static']["extern_id"],
                                "koreksi_hutang_dagang_nilai" => $nilai_unit,
                                "koreksi_persediaan_nilai" => 0,
                                "koreksi_hpp_nilai" => $nilai_unit,
                                "koreksi_fifo_nilai" => 0,
                                "koreksi_fifo_nilai_unit" => 0,
                                "jml" => $curentQtyDebet,
                                "id" => $subtmp["extern_id"],
                                "name" => $subtmp["extern_nama"],
                                "nama" => $subtmp["extern_nama"],

                            );
                        }
                    }
                    else {
                        matiHere(__LINE__);
                        return true;
                    }
                }
                else {
                    switch ($selisih_metode) {
                        case "hutang_dagang":
                            break;
                    }
                    if ($selisih_nilai > 1) {
                        $b = new ComRekeningPembantuProduk();
//                        matiHere($selisih_nilai);
//                    $b->addFilter("jenis='produk'");
                        $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                        $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                        $b->addFilter("extern_id='" . $sentParams['static']["extern_id"] . "'");
                        $b->addFilter("rekening='1010030030'");
                        $b->addFilter("periode='forever'");
                        $localFilters = array();
                        if (sizeof($b->getfilters()) > 0) {
                            foreach ($b->getfilters() as $f) {
                                $tmpArr = explode("=", $f);
                                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                            }
                        }
                        $query = $this->db->select()
                            ->from($b->getTableName())
                            ->where($localFilters)
                            ->limit(1)
                            ->get_compiled_select();
                        $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                        cekMerah($this->db->last_query());

                        $curentDebet = $subtmp["debet"];
                        $curentQtyDebet = $subtmp["qty_debet"];


                        //untuk persediaan riil
                        $bb = new ComRekeningPembantuProdukRiil();
                        $bb->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                        $bb->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                        $bb->addFilter("extern_id='" . $sentParams['static']["extern_id"] . "'");
                        $bb->addFilter("rekening='8020'");
                        $bb->addFilter("periode='forever'");
                        $localFilters1 = array();
                        if (sizeof($bb->getfilters()) > 0) {
                            foreach ($bb->getfilters() as $f) {
                                $tmpArr = explode("=", $f);
                                $localFilters1[$tmpArr[0]] = trim($tmpArr[1], "'");
                            }
                        }
                        $query1 = $this->db->select()
                            ->from($bb->getTableName())
                            ->where($localFilters1)
                            ->limit(1)
                            ->get_compiled_select();
                        $subtmpRill = $this->db->query("{$query1} FOR UPDATE")->row_array();
                        cekMerah($this->db->last_query());

                        $curentDebet = $subtmp["debet"];
                        $curentQtyDebet = $subtmp["qty_debet"];

                        $curentDebet_riil = $subtmpRill["debet"];
                        $curentQtyDebet_riil = $subtmpRill["qty_debet"];

//arrprint($subtmpRill);
//matiHEre();
                        /*
                         * diperhatikan saldo debet dan saldo qty
                         */
                        if ($curentDebet > 0 && $curentQtyDebet > 0) {
                            if ($curentQtyDebet >= $sentParams["static"]["produk_qty"]) {
                                $nilai_unit = $selisih_nilai / $curentQtyDebet;
                                $selisih_dipakai = $selisih_nilai;
                                $last_debet = $curentDebet - $selisih_dipakai;
                                $last_debet_riil = $curentDebet_riil - $selisih_dipakai;
                                $koreksi_fifo_riil = $last_debet_riil / $curentQtyDebet_riil;
                                cekMerah(__LINE__ . "$last_debet_riil = $curentDebet_riil - $selisih_dipakai*$koreksi_fifo_riil");
                            }
                            else {
                                $nilai_unit = $selisih_nilai / $sentParams["static"]["produk_qty"];
                                $unit_sisa = $sentParams["static"]["produk_qty"] - $curentQtyDebet;
                                $selisih_dipakai = $nilai_unit * $curentQtyDebet;
                                if ($curentQtyDebet_riil >= $sentParams["static"]["produk_qty"]) {
                                    $selisih_dipakai_riil = $selisih_nilai;
                                    $last_debet_riil = $curentDebet_riil - $selisih_dipakai_riil;
                                    $koreksi_fifo_riil = $last_debet_riil / $curentQtyDebet;
                                }
                                else {
                                    $nilai_unit_riil = $selisih_nilai / $curentQtyDebet_riil;
                                    $selisih_dipakai_riil = $nilai_unit_riil * $curentQtyDebet_riil;
                                    $last_debet_riil = $curentDebet_riil - $selisih_dipakai_riil;
                                    $koreksi_fifo_riil = $last_debet_riil / $curentQtyDebet;
                                }

                                $last_debet = $curentDebet - $selisih_dipakai;
                                $koreksi_hpp_0 = $nilai_unit * $unit_sisa;
                                $koreksi_hpp_dipakai = ($koreksi_hpp_0 / $curentQtyDebet);
//                                matiHere(__LINE__."::".$koreksi_hpp_dipakai);

                            }
                            $preval = $sentParams["static"]["diskon_tambahan"] * ($sentParams["static"]["subtotal"] / $sentParams["static"]["sisa"]);
//                            $nilai_unit = $selisih_nilai / $curentQtyDebet;

                            switch ($selisih_metode) {
                                case "hutang_dagang":
                                    $tmp[] = array(
                                        "produk_id" => $sentParams['static']["extern_id"],
                                        "koreksi_hutang_dagang_nilai" => $selisih_nilai / $curentQtyDebet,
                                        "koreksi_persediaan_nilai" => $nilai_unit * -1,
                                        "koreksi_persediaan_riil" => $nilai_unit * -1,
                                        "koreksi_hpp_nilai_riil" => $koreksi_hpp_dipakai * -1,
                                        "koreksi_pembelian" => $nilai_unit + $koreksi_hpp_dipakai,
                                        "koreksi_hpp_nilai" => $koreksi_hpp_dipakai,
                                        "koreksi_fifo_nilai" => $nilai_unit,
                                        "koreksi_fifo_nilai_unit" => $nilai_unit,
                                        "current_debet" => $last_debet,
                                        "koreksi_fifo_nilai_unit_riil" => $koreksi_fifo_riil,
                                        "current_debet_riil" => $last_debet_riil,
                                        "jml" => $curentQtyDebet,
                                        "id" => $subtmp["extern_id"],
                                        "name" => $subtmp["extern_nama"],
                                        "nama" => $subtmp["extern_nama"],

                                    );
                                    break;
                                case "persediaan":
                                    $tmp[] = array(
                                        "produk_id" => $sentParams['static']["extern_id"],
                                        "koreksi_hutang_dagang_nilai" => $nilai_unit * -1,
                                        "koreksi_persediaan_nilai" => $nilai_unit,
                                        "koreksi_persediaan_riil" => $nilai_unit,
                                        "koreksi_hpp_nilai_riil" => $koreksi_hpp_dipakai,
                                        "koreksi_pembelian" => ($nilai_unit + $koreksi_hpp_dipakai)*-1,
                                        "koreksi_hpp_nilai" => ($koreksi_hpp_dipakai)*-1,
                                        "koreksi_fifo_nilai" => $nilai_unit,
                                        "koreksi_fifo_nilai_unit" => $nilai_unit,
                                        "current_debet" => $last_debet,
                                        "koreksi_fifo_nilai_unit_riil" => $koreksi_fifo_riil,
                                        "current_debet_riil" => $last_debet_riil,
                                        "jml" => $curentQtyDebet,
                                        "id" => $subtmp["extern_id"],
                                        "name" => $subtmp["extern_nama"],
                                        "nama" => $subtmp["extern_nama"],

                                    );
                                    break;
                                default :
                                    matiHere("Gagal menulis data silahkan hubungi admin untuk melakukan pengecekan,error  " . __FUNCTION__ . " errorcode: " . __LINE__ . "" . dtime("Y-m-d H:i"));
                                    break;
                            }

                        }
                        else {

                            $last_debet = $curentDebet - $selisih_nilai;
                            $preval = $sentParams["static"]["diskon_tambahan"] * ($sentParams["static"]["subtotal"] / $sentParams["static"]["sisa"]);
                            $nilai_unit = $selisih_nilai / $sentParams["static"]["produk_qty"];
//                            if($curentQtyDebet_riil>= $sentParams["static"]["produk_qty"] && $curentDebet_riil > 0){
//                                $koreksi_persediaan_riil= $curentDebet_riil>$selisih_nilai ? $selisih_nilai:$curentDebet_riil;
//
//                                $koreksi_nilai_persediaan_riil = $koreksi_persediaan_riil/$sentParams["static"]["produk_qty"];
//                                $koreksi_nilai_hpp_riil= 0;
//
//                            }
//                            else{
//                                $nilai_unit_riil = $selisih_nilai/$sentParams["static"]["produk_qty"];
//                                $unit_qty_sisa_riil = $curentQtyDebet_riil < $sentParams["static"]["produk_qty"] ? $sentParams["static"]["produk_qty"]-$curentQtyDebet_riil:0;
//                                $koreksi_nilai_unit_riil_persediaan = $nilai_unit_riil*$curentQtyDebet_riil;
//                                $koreksi_hpp_riil =$nilai_unit_riil *$unit_qty_sisa_riil;
//                                    $koreksi_nilai_persediaan_riil = $koreksi_nilai_unit_riil_persediaan/$sentParams["static"]["produk_qty"];
//                                    $koreksi_nilai_hpp_riil = $koreksi_hpp_riil/$sentParams["static"]["produk_qty"];
//                            }
                            switch ($selisih_metode) {
                                case "hutang_dagang":
//                                    matiHere(__LINE__.":$selisih_nilai: ".$nilai_unit);
//                                    $nilai_unit =
                                    /*
                                     * jika sudah tidak ada stok
                                     */
                                    $tmp[] = array(
                                        "produk_id" => $sentParams['static']["extern_id"],
                                        "koreksi_hutang_dagang_nilai" => $nilai_unit,
                                        "koreksi_persediaan_nilai" => 0,
                                        "koreksi_hpp_nilai" => $nilai_unit * -1,
                                        "koreksi_persediaan_riil" => 0,
                                        "koreksi_hpp_nilai_riil" => $nilai_unit * -1,
                                        "koreksi_pembelian" => $nilai_unit * -1,
                                        "koreksi_fifo_nilai" => 0,
                                        "koreksi_fifo_nilai_unit" => 0,
                                        "koreksi_fifo_nilai_unit_riil" => 0,
                                        "current_debet" => 0,
                                        "current_debet_riil" => 0,
                                        "jml" => $sentParams["static"]["produk_qty"],
                                        "id" => $subtmp["extern_id"],
                                        "name" => $subtmp["extern_nama"],
                                        "nama" => $subtmp["extern_nama"],

                                    );
                                    break;
                                case "persediaan":
                                    $tmp[] = array(
                                        "produk_id" => $sentParams['static']["extern_id"],
                                        "koreksi_hutang_dagang_nilai" => $nilai_unit * -1,
                                        "koreksi_persediaan_nilai" => 0,
                                        "koreksi_hpp_nilai" => $nilai_unit,
                                        "koreksi_persediaan_riil" => 0,
                                        "koreksi_hpp_nilai_riil" => $nilai_unit,
                                        "koreksi_pembelian" => $nilai_unit,
                                        "koreksi_fifo_nilai" => 0,
                                        "koreksi_fifo_nilai_unit" => 0,
                                        "koreksi_fifo_nilai_unit_riil" => 0,
                                        "current_debet" => 0,
                                        "current_debet_riil" => 0,
//                                        "jml" => $curentQtyDebet,
                                        "jml" => $sentParams["static"]["produk_qty"],
                                        "id" => $subtmp["extern_id"],
                                        "name" => $subtmp["extern_nama"],
                                        "nama" => $subtmp["extern_nama"],

                                    );
                                    break;
                                default :
                                    matiHere("Gagal menulis data silahkan hubungi admin untuk melakukan pengecekan,error  " . __FUNCTION__ . " errorcode: " . __LINE__ . "" . dtime("Y-m-d H:i"));
                                    break;
                            }

                        }
                    }
                    else {
                        $tmp = array();
                    }

                }
//arrPrintWebs($tmp);
//                matiHere();
                if (count($tmp) > 0) {
                    foreach ($tmp as $tmp_0) {
                        foreach ($this->resultParams as $gateName => $paramSpec) {
                            foreach ($paramSpec as $key => $val) {
                                $patchers[$gateName][$tmp_0["produk_id"]][$key] = $tmp_0[$val];
                            }
                        }
                    }
                }
                else {
                    $patchers[] = array();
                }

            }
//            matiHere(__LINE__ . "::" . $total_selisih_items);
            $this->result = $patchers;
//matiHere(__LINE__);
            return true;

        }
    }

    public function exec()
    {
        return $this->result;
    }
}