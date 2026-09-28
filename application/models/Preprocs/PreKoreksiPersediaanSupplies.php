<?php

/**
 * untuk koresksi slaah harga ataupun tambahan diskon dari supplier
 * untuk melakukan koreksi nilai persediaan atau jika nilai habis dibebankan ke hpp
 */
class PreKoreksiPersediaanSupplies extends CI_Model
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
        $this->load->model("Coms/ComRekeningPembantuSupplies");
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


                $pakaiini = 0;
                if ($pakaiini == 1) {
                    //versi diskon globall
                    if (isset($sentParams["static"]["diskon_tambahan"]) && $sentParams["static"]["diskon_tambahan"] > 0) {
                        $b = new ComRekeningPembantuSupplies();
//                    $b->addFilter("jenis='produk'");
                        $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                        $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                        $b->addFilter("extern_id='" . $sentParams['static']["extern_id"] . "'");
                        $b->addFilter("rekening='1010030010'");
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
                        $b = new ComRekeningPembantuSupplies();
//                    $b->addFilter("jenis='produk'");
                        $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                        $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                        $b->addFilter("extern_id='" . $sentParams['static']["extern_id"] . "'");
                        $b->addFilter("rekening='1010030010'");
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
                        cekMerah($curentDebet - $total_selisih_items);

                        $curentQtyDebet = $subtmp["qty_debet"];
                        $last_debet = $curentDebet - $selisih_nilai;
                        cekHitam("$curentDebet - $total_selisih_items = $last_debet");

                        $preval = $sentParams["static"]["diskon_tambahan"] * ($sentParams["static"]["subtotal"] / $sentParams["static"]["sisa"]);
                        $nilai_unit = $selisih_nilai / $curentQtyDebet;


                        if ($curentDebet > 0 && $curentQtyDebet > 0) {
                            switch ($selisih_metode) {
                                case "hutang_dagang":
                                    $tmp[] = array(
                                        "produk_id" => $sentParams['static']["extern_id"],
                                        "koreksi_hutang_dagang_nilai" => $nilai_unit,
                                        "koreksi_persediaan_nilai" => $nilai_unit * -1,
                                        "koreksi_hpp_nilai" => 0,
                                        "koreksi_fifo_nilai" => $nilai_unit,
                                        "koreksi_fifo_nilai_unit" => $nilai_unit,
                                        "current_debet" => $last_debet,
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
                                        "koreksi_hpp_nilai" => 0,
                                        "koreksi_fifo_nilai" => $nilai_unit,
                                        "koreksi_fifo_nilai_unit" => $nilai_unit,
                                        "current_debet" => $last_debet,
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
                            switch ($selisih_metode) {
                                case "hutang_dagang":
                                    $tmp[] = array(
                                        "produk_id" => $sentParams['static']["extern_id"],
                                        "koreksi_hutang_dagang_nilai" => $nilai_unit,
                                        "koreksi_persediaan_nilai" => 0,
                                        "koreksi_hpp_nilai" => $nilai_unit * -1,
                                        "koreksi_fifo_nilai" => 0,
                                        "koreksi_fifo_nilai_unit" => 0,
                                        "current_debet" => 0,
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
                                        "koreksi_persediaan_nilai" => 0,
                                        "koreksi_hpp_nilai" => $nilai_unit,
                                        "koreksi_fifo_nilai" => 0,
                                        "koreksi_fifo_nilai_unit" => 0,
                                        "current_debet" => 0,
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
                    }
                    else {
                        $tmp = array();
                    }

                }

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