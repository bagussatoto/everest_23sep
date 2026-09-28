<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverage extends CI_Model
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
        $this->exceptionTr = array("9855", "9856");
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

    private function hasUnitValue($spec)
    {
        return isset($spec["produk_nilai"]) || isset($spec["harga"]) || isset($spec["hpp"]);
    }

    private function pickUnitValue($spec)
    {
        if (isset($spec["produk_nilai"])) {
            return (float)$spec["produk_nilai"];
        }
        if (isset($spec["harga"])) {
            return (float)$spec["harga"];
        }
        if (isset($spec["hpp"])) {
            return (float)$spec["hpp"];
        }
        return 0;
    }

    public function pair($master_id, $inParams)
    {
        $this->load->model("Mdls/MdlFifoAverage");
        $this->load->model("Coms/ComRekeningPembantuProduk");
//        arrPrint($inParams);

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $needlesNilai = array();
        $needlesNilaiIsProvided = array();
        $ids = array();
        $tmp = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    if (isset($pSpec["kategori_id"]) && $pSpec["kategori_id"] == "4") {
//                        $subtmp = array(
//                            "id" => "0",
//                            "last_update" => "",
//                            "jenis" => $pSpec["kategori_id"],
//                            "produk_id" => $pSpec["extern_id"],
//                            "nama" => $pSpec["extern_nama"],
//                            "jml" => $pSpec["produk_qty"],
//                            "hpp" => 0,
//                            "harga_jasa" => $pSpec["harga"],
//                            "jml_nilai" => $pSpec["harga"] * $pSpec["produk_qty"],
//                            "cabang_id" => $pSpec["cabang_id"],
//                            "gudang_id" => $pSpec["gudang_id"],
//                            "cache_id" => "0",
//                            "trash" => "0",
//                            "transaksi_id" => "0",
//                            "transaksi_jenis" => "",
//                            "dtime_last" => "",
//                            "jml_ot" => "0",
//                            "jml_nilai_ot" => "",
//                            "unit_ot" => "",
//                            "fulldate" => "",
//                            "hpp_riil" => "0",
//                            "jml_nilai_riil" => 0,
//                            "ppv_riil" => 0,
//                            "ppv_nilai_riil" => 0,
//                            "ppn_in_nilai" => 0,
//                            "ppn_in" => 0,
//                            "suppliers_id" => "",
//                            "suppliers_nama" => "",
//                            "hpp_nppv" => 0,
//                            "jml_nilai_nppv" => 0,
//                            "produk_jenis_id" => 0,
//                            "produk_jenis" => 0,
//                        );
//                        $tmp[] = (object)$subtmp;
                        $updaters[] = 1;
                    }
                    else {
                        $exception = isset($pSpec['exception']) ? $pSpec['exception'] : 0;
                        $jenisTransaksi = isset($pSpec['jenisTr']) ? $pSpec['jenisTr'] : 0;
                        if (in_array($jenisTransaksi, $this->exceptionTr) && ($exception == 1)) {
                            // boleh tetap dijalankan walaupun nol/0
                            $updaters[] = 1;
                        }
                        else {
                            // ini yang reguler

                        }

                        $externID = $pSpec['extern_id'];
                        $qty = isset($pSpec['produk_qty']) ? (float)$pSpec['produk_qty'] : 0;
                        if (!isset($needles[$externID])) {
                            $needles[$externID] = 0;
                        }
                        $needles[$externID] += $qty;
                        if ($this->hasUnitValue($pSpec)) {
                            $unitValue = $this->pickUnitValue($pSpec);
                            if (!isset($needlesNilai[$externID])) {
                                $needlesNilai[$externID] = 0;
                            }
                            $needlesNilai[$externID] += ($qty * $unitValue);
                            $needlesNilaiIsProvided[$externID] = true;
                        }
                        $ids[] = $pSpec["extern_id"];

                        /**
                         * prevalue ambil dari rekening tidak dari tabel fifo
                         *
                         */

                        //asli yangseri missmatch
                        $b = new MdlFifoAverage();
                        $b->addFilter("jenis='produk'");
                        $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                        $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                        $b->addFilter("produk_id='" . $pSpec["extern_id"] . "'");
                        // ini diupdate ke => for update
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

                        cekMerah($subtmp);
                        if ((sizeof($subtmp) == 0) || ($subtmp == NULL)) {
                            cekMErah($pSpec["kategori_id"]);
                            if (isset($pSpec["kategori_id"]) && $pSpec["kategori_id"] == "4") {
                                $subtmp = array(
                                    "id" => "0",
                                    "last_update" => "",
                                    "jenis" => $pSpec["kategori_id"],
                                    "produk_id" => $pSpec["extern_id"],
                                    "nama" => $pSpec["extern_nama"],
                                    "jml" => $pSpec["produk_qty"],
                                    "hpp" => 0,
                                    "harga_jasa" => $pSpec["harga"],
                                    "jml_nilai" => $pSpec["harga"] * $pSpec["produk_qty"],
                                    "cabang_id" => $pSpec["cabang_id"],
                                    "gudang_id" => $pSpec["gudang_id"],
                                    "cache_id" => "0",
                                    "trash" => "0",
                                    "transaksi_id" => "0",
                                    "transaksi_jenis" => "",
                                    "dtime_last" => "",
                                    "jml_ot" => "0",
                                    "jml_nilai_ot" => "",
                                    "unit_ot" => "",
                                    "fulldate" => "",
                                    "hpp_riil" => "0",
                                    "jml_nilai_riil" => 0,
                                    "ppv_riil" => 0,
                                    "ppv_nilai_riil" => 0,
                                    "ppn_in_nilai" => 0,
                                    "ppn_in" => 0,
                                    "suppliers_id" => "",
                                    "suppliers_nama" => "",
                                    "hpp_nppv" => 0,
                                    "jml_nilai_nppv" => 0,
                                    "produk_jenis_id" => 0,
                                    "produk_jenis" => 0,
                                );
                            }
                            else {
                                cekHitam("kategori id :: " . $pSpec["kategori_id"]);
                                $msg = "Stok " . $pSpec['extern_nama'] . " tidak cukup. Silahkan diperiksa stok anda." . __LINE__ . " " . __FUNCTION__;
                                matiHere($msg);
                                die(lgShowAlertBiru($msg));
                            }
                        }
                        else {
                            cekOrange($this->db->last_query());
                            cekHitam("else preproc item");


                        }


                        $pakai_ini = 0;
                        if ($pakai_ini == 1) {

                            if (count($subtmp) > 0) {
                                //overwrite baca dari rekening
                                $oldId = $subtmp["id"];
                                $subtmp = array();
                                $p = new ComRekeningPembantuProduk();
                                $p->setFilters(array());
                                $p->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                                $p->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                                $p->addFilter("extern_id='" . $pSpec["extern_id"] . "'");
                                $temp = $p->fetchBalances("1010030030");//

                                $subtmp = array(
                                    "id" => $oldId,
                                    "last_update" => "",
                                    "jenis" => "produk",
                                    "produk_id" => isset($temp[0]->extern_id) ? $temp[0]->extern_id : $pSpec["extern_id"],
                                    "nama" => isset($temp[0]->extern_nama) ? $temp[0]->extern_nama : "",

                                    "jml" => isset($temp[0]->qty_debet) ? $temp[0]->qty_debet : 0,
                                    "hpp" => (isset($temp[0]->qty_debet) && $temp[0]->qty_debet > 0) ? $temp[0]->debet / $temp[0]->qty_debet : 0,
                                    "jml_nilai" => isset($temp[0]->debet) ? $temp[0]->debet : 0,

                                    "cabang_id" => isset($temp[0]->cabang_id) ? $temp[0]->cabang_id : $sentParams['static']['cabang_id'],
                                    "gudang_id" => isset($temp[0]->gudang_id) ? $temp[0]->gudang_id : $sentParams['static']['gudang_id'],
                                    "cache_id" => "0",
                                    "trash" => "0",
                                    "transaksi_id" => "0",
                                    "transaksi_jenis" => "",
                                    "dtime_last" => "",
                                    "jml_ot" => "0",
                                    "jml_nilai_ot" => "",
                                    "unit_ot" => "",
                                    "fulldate" => "",
                                    "hpp_riil" => "0",
                                    "jml_nilai_riil" => 0,
                                    "ppv_riil" => 0,
                                    "ppv_nilai_riil" => 0,
                                    "ppn_in_nilai" => 0,
                                    "ppn_in" => 0,
                                    "suppliers_id" => "",
                                    "suppliers_nama" => "",
                                    "hpp_nppv" => 0,
                                    "jml_nilai_nppv" => 0,
                                    "produk_jenis_id" => 0,
                                    "produk_jenis" => 0,
                                );
                            }
                            else {

                            }

                        }


                        $tmp[] = (object)$subtmp;
                    }
                }
            }
//arrPrint($subtmp);
//matiHere();
            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                $patchers = array();
                foreach ($tmp as $row) {
                    foreach ($this->resultParams as $gateName => $paramSpec) {

                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$row->produk_id][$key] = $row->$val;
                        }
                    }

                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        if ($row->jenis == "4") {
                            $updatePairs[] = array(
                                "id" => $row->id,
                                "produk_id" => $row->produk_id,
                                "jml" => 0,
                                "jml_nilai" => 0,
                                "jml_nilai_riil" => 0,
                                "ppv_nilai_riil" => 0,
                                "ppn_in_nilai" => 0,
                                "kategori_id" => isset($row->jenis) ? $row->jenis : "4",
                            );
                        }
                        else {
                            $newJml = $row->jml - $needles[$row->produk_id];
                            $jmll = $row->jml;
                            $jmllbutuh = $needles[$row->produk_id];
                            $namaa = $row->nama;
                            cekHere("[newJml: $newJml] [jmll: $jmll] [jmllbutuh: $jmllbutuh] [namaa: $namaa]");
                            if ($newJml < 0) {

                                $msg = "Stok " . $row->nama . " tidak cukup. Silahkan diperiksa stok anda." . __LINE__ . " " . __FUNCTION__;
                                matiHere($msg);
//                            die(lgShowAlertBiru($msg));
                            }
                            $qtyOut = isset($needles[$row->produk_id]) ? (float)$needles[$row->produk_id] : 0;
                            $hasValueOut = isset($needlesNilaiIsProvided[$row->produk_id]) && $needlesNilaiIsProvided[$row->produk_id];
                            $nilaiOut = $hasValueOut ? (float)$needlesNilai[$row->produk_id] : ((float)$row->hpp * $qtyOut);
                            $newNilai = (float)$row->jml_nilai - $nilaiOut;
                            $newNilaiRiil = (float)$row->jml_nilai_riil - ((float)$row->hpp_riil * $qtyOut);
                            $newPpvNilaiRiil = (float)$row->ppv_nilai_riil - ((float)$row->ppv_riil * $qtyOut);
                            $newPpnInNilai = (float)$row->ppn_in_nilai - ((float)$row->ppn_in * $qtyOut);
                            if ($newJml == 0) {
                                $newNilai = 0;
                                $newNilaiRiil = 0;
                                $newPpvNilaiRiil = 0;
                                $newPpnInNilai = 0;
                            }
//                        if ($row->jenis == "produk") {
                            $updatePairs[] = array(
                                "id" => $row->id,
                                "produk_id" => $row->produk_id,
                                "jml" => $newJml,
                                "jml_nilai" => $this->normalizeDecimal($newNilai),
                                "jml_nilai_riil" => $this->normalizeDecimal($newNilaiRiil),
                                "ppv_nilai_riil" => $this->normalizeDecimal($newPpvNilaiRiil),
                                "ppn_in_nilai" => $this->normalizeDecimal($newPpnInNilai),
                                "kategori_id" => isset($row->jenis) ? $row->jenis : "produk",
                            );

                        }
//                        }
                    }
                }

                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $katID = $upSpec["kategori_id"];
//                        arrprint($upSpec);
                        if ($katID == "4") {
                            //tidak usah diupdate bro memang gak ada fifonya untuk jasa
//                            matiHere(__LINE__);
                        }
                        else {
                            $updateData = $upSpec;
                            unset($updateData["id"]);
                            unset($updateData["kategori_id"]);
                            $b = new MdlFifoAverage();
                            $updaters[] = $b->updateData(array("id" => $upSpec['id']), $updateData);
                            cekMerah($this->db->last_query());
                        }
                    }
                }

                $this->result = $patchers;

            }
            else {
//                arrprintWebs($inParams);
//                matiHere();
                $this->result = array();
            }

//            if (sizeof($updatePairs) > 0) {
            if (sizeof($updaters) > 0) {
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

    public function exec()
    {
        return $this->result;
    }
}
